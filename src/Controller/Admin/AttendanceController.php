<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Model\Entity\Attendance;
use App\Model\Entity\SchoolClass;
use Cake\I18n\Date;

class AttendanceController extends AdminAppController
{
    public function index()
    {
        $classesTable = $this->fetchTable('Classes');
        $attendancesTable = $this->fetchTable('Attendances');

        $selectedDate = $this->request->getQuery('date') ?: date('Y-m-d');
        $selectedClassId = $this->request->getQuery('class_id');

        $classes = $classesTable->find('active')
            ->orderBy(['Classes.grade_level' => 'ASC', 'Classes.section' => 'ASC'])
            ->all();

        $attendance = [];
        $students = [];
        $selectedClass = null;

        if ($selectedClassId) {
            $selectedClass = $classesTable->get($selectedClassId);

            $studentClassesTable = $this->fetchTable('StudentClasses');
            $students = $studentClassesTable->find()
                ->contain(['Students', 'Classes'])
                ->where([
                    'StudentClasses.class_id' => $selectedClassId,
                    'StudentClasses.status' => 'active',
                ])
                ->orderBy(['Students.name' => 'ASC'])
                ->all();

            // Get existing attendance for the date
            $existingAttendance = $attendancesTable->find()
                ->where([
                    'Attendances.class_id' => $selectedClassId,
                    'Attendances.date' => $selectedDate,
                ])
                ->all()
                ->combine('student_id', function ($entity) {
                    return $entity;
                })
                ->toArray();

            $attendance = $existingAttendance;
        }

        $statuses = Attendance::getStatuses();
        $classesList = $classes->combine('id', function ($class) {
            return $class->name . ($class->section ? ' - ' . $class->section : '') . ' (' . $class->grade_level . ')';
        })->toArray();

        $this->set(compact(
            'classes',
            'classesList',
            'students',
            'attendance',
            'selectedDate',
            'selectedClassId',
            'selectedClass',
            'statuses'
        ));
    }

    public function mark()
    {
        $this->request->allowMethod(['post']);

        $attendancesTable = $this->fetchTable('Attendances');
        $data = $this->request->getData();
        $classId = $data['class_id'];
        $date = $data['date'];
        $attendanceData = $data['attendance'] ?? [];
        $markedBy = $this->request->getAttribute('identity')->id;

        $savedCount = 0;
        $errors = [];

        foreach ($attendanceData as $studentId => $record) {
            if (empty($record['status'])) {
                continue;
            }

            // Check if attendance already exists
            $existing = $attendancesTable->find()
                ->where([
                    'student_id' => $studentId,
                    'date' => $date,
                ])
                ->first();

            if ($existing) {
                $attendance = $attendancesTable->patchEntity($existing, [
                    'status' => $record['status'],
                    'check_in_time' => $record['check_in_time'] ?? null,
                    'remarks' => $record['remarks'] ?? null,
                    'marked_by' => $markedBy,
                ]);
            } else {
                $attendance = $attendancesTable->newEntity([
                    'student_id' => $studentId,
                    'class_id' => $classId,
                    'date' => $date,
                    'status' => $record['status'],
                    'check_in_time' => $record['check_in_time'] ?? null,
                    'remarks' => $record['remarks'] ?? null,
                    'marked_by' => $markedBy,
                ]);
            }

            if ($attendancesTable->save($attendance)) {
                $savedCount++;
            } else {
                $errors[] = $studentId;
            }
        }

        if ($savedCount > 0) {
            $this->Flash->success(__('Attendance marked for {0} students.', $savedCount));
        }

        if (!empty($errors)) {
            $this->Flash->error(__('Failed to mark attendance for {0} students.', count($errors)));
        }

        return $this->redirect(['action' => 'index', '?' => ['class_id' => $classId, 'date' => $date]]);
    }

    public function report()
    {
        $classesTable = $this->fetchTable('Classes');
        $attendancesTable = $this->fetchTable('Attendances');

        $selectedClassId = $this->request->getQuery('class_id');
        $selectedMonth = $this->request->getQuery('month') ?: date('Y-m');

        $classes = $classesTable->find('active')
            ->orderBy(['Classes.grade_level' => 'ASC'])
            ->all();

        $classesList = $classes->combine('id', function ($class) {
            return $class->name . ($class->section ? ' - ' . $class->section : '') . ' (' . $class->grade_level . ')';
        })->toArray();

        $report = [];
        $selectedClass = null;

        if ($selectedClassId) {
            $selectedClass = $classesTable->get($selectedClassId);

            [$year, $month] = explode('-', $selectedMonth);

            $studentClassesTable = $this->fetchTable('StudentClasses');
            $students = $studentClassesTable->find()
                ->contain(['Students'])
                ->where([
                    'StudentClasses.class_id' => $selectedClassId,
                    'StudentClasses.status' => 'active',
                ])
                ->orderBy(['Students.name' => 'ASC'])
                ->all();

            foreach ($students as $studentClass) {
                $summary = $attendancesTable->getStudentSummary(
                    $studentClass->student_id,
                    (int)$year,
                    (int)$month
                );
                $report[] = [
                    'student' => $studentClass->student,
                    'roll_number' => $studentClass->roll_number,
                    'summary' => $summary,
                ];
            }
        }

        // Generate month options
        $months = [];
        for ($i = 0; $i < 12; $i++) {
            $date = strtotime("-{$i} months");
            $key = date('Y-m', $date);
            $months[$key] = date('F Y', $date);
        }

        $this->set(compact('classesList', 'selectedClassId', 'selectedMonth', 'selectedClass', 'report', 'months'));
    }

    public function studentReport(?int $studentId = null)
    {
        $usersTable = $this->fetchTable('Users');
        $attendancesTable = $this->fetchTable('Attendances');

        $student = $usersTable->get($studentId);
        $year = (int)($this->request->getQuery('year') ?: date('Y'));

        $summary = $attendancesTable->getStudentSummary($studentId, $year);
        $monthlyTrend = $attendancesTable->getMonthlyTrend($studentId, $year);

        $recentAttendance = $attendancesTable->find()
            ->contain(['Classes'])
            ->where(['Attendances.student_id' => $studentId])
            ->orderByDesc('Attendances.date')
            ->limit(30)
            ->all();

        $years = range(date('Y'), date('Y') - 3);

        $this->set(compact('student', 'summary', 'monthlyTrend', 'recentAttendance', 'year', 'years'));
    }
}
