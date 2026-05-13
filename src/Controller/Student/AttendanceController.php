<?php
declare(strict_types=1);

namespace App\Controller\Student;

class AttendanceController extends StudentAppController
{
    public function index()
    {
        $user = $this->request->getAttribute('identity');
        $attendancesTable = $this->fetchTable('Attendances');

        // Get filter parameters
        $year = $this->request->getQuery('year', (int)date('Y'));
        $month = $this->request->getQuery('month');

        // Get attendance summary
        $attendanceSummary = $attendancesTable->getStudentSummary(
            $user->id,
            (int)$year,
            $month ? (int)$month : null
        );

        // Build query for attendance records
        $query = $attendancesTable->find()
            ->where(['student_id' => $user->id])
            ->contain(['Classes'])
            ->orderByDesc('date');

        // Apply year filter
        if ($year) {
            $query->where(['YEAR(Attendances.date)' => $year]);
        }

        // Apply month filter
        if ($month) {
            $query->where(['MONTH(Attendances.date)' => $month]);
        }

        $this->paginate = ['limit' => 20];
        $attendance = $this->paginate($query);

        // Get monthly trend for the year
        $monthlyTrend = $attendancesTable->getMonthlyTrend($user->id, (int)$year);

        // Get available years for filter
        $years = $attendancesTable->find()
            ->select(['year' => 'YEAR(Attendances.date)'])
            ->where(['student_id' => $user->id])
            ->distinct()
            ->orderByDesc('year')
            ->all()
            ->combine('year', 'year')
            ->toArray();

        if (empty($years)) {
            $years = [(int)date('Y') => (int)date('Y')];
        }

        // Get student's class info
        $studentClass = null;
        if ($user->class_id) {
            $classesTable = $this->fetchTable('Classes');
            $studentClass = $classesTable->get($user->class_id);
        }

        $months = [
            '' => __('All Months'),
            1 => __('January'),
            2 => __('February'),
            3 => __('March'),
            4 => __('April'),
            5 => __('May'),
            6 => __('June'),
            7 => __('July'),
            8 => __('August'),
            9 => __('September'),
            10 => __('October'),
            11 => __('November'),
            12 => __('December'),
        ];

        $this->set(compact(
            'attendance',
            'attendanceSummary',
            'monthlyTrend',
            'studentClass',
            'years',
            'months',
            'year',
            'month'
        ));
    }
}
