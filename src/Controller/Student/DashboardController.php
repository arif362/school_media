<?php
declare(strict_types=1);

namespace App\Controller\Student;

class DashboardController extends StudentAppController
{
    public function index()
    {
        $user = $this->request->getAttribute('identity');

        $postsTable = $this->fetchTable('Posts');
        $recentPosts = $postsTable->find('published')
            ->limit(5)
            ->all();

        // Get attendance summary for current academic year
        $attendancesTable = $this->fetchTable('Attendances');
        $currentYear = (int)date('Y');
        $currentMonth = (int)date('m');

        // Get overall year summary
        $attendanceSummary = $attendancesTable->getStudentSummary($user->id, $currentYear);

        // Get current month summary
        $monthlyAttendance = $attendancesTable->getStudentSummary($user->id, $currentYear, $currentMonth);

        // Get recent attendance records (last 10)
        $recentAttendance = $attendancesTable->find()
            ->where(['student_id' => $user->id])
            ->contain(['Classes'])
            ->orderByDesc('date')
            ->limit(10)
            ->all();

        // Get student's current class info
        $studentClass = null;
        if ($user->class_id) {
            $classesTable = $this->fetchTable('Classes');
            $studentClass = $classesTable->get($user->class_id);
        }

        $this->set(compact(
            'user',
            'recentPosts',
            'attendanceSummary',
            'monthlyAttendance',
            'recentAttendance',
            'studentClass',
            'currentYear',
            'currentMonth'
        ));
    }
}
