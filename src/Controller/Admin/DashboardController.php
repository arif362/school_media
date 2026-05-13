<?php
declare(strict_types=1);

namespace App\Controller\Admin;

class DashboardController extends AdminAppController
{
    public function index(): void
    {
        $user = $this->request->getAttribute('identity');

        // Core stats
        $stats = [
            'posts' => $this->fetchCount('Posts', ['published' => true]),
            'totalUsers' => $this->fetchCount('Users'),
            'teachers' => $this->fetchCount('Users', ['role' => 'teacher']),
            'students' => $this->fetchCount('Users', ['role' => 'student']),
            'classes' => $this->fetchCount('Classes', ['is_active' => true]),
            'courses' => $this->fetchCount('Courses', ['is_active' => true]),
            'subjects' => $this->fetchCount('Subjects', ['is_active' => true]),
        ];

        // Get active vs inactive users
        $activeUsers = $this->fetchCount('Users', ['active' => true]);
        $inactiveUsers = $this->fetchCount('Users', ['active' => false]);

        // Recent teachers
        $usersTable = $this->fetchTable('Users');
        $recentTeachers = $usersTable->find()
            ->where(['role' => 'teacher'])
            ->orderBy(['created' => 'DESC'])
            ->limit(5)
            ->all();

        // Recent students
        $recentStudents = $usersTable->find()
            ->where(['role' => 'student'])
            ->orderBy(['created' => 'DESC'])
            ->limit(5)
            ->all();

        // Recent courses
        $coursesTable = $this->fetchTable('Courses');
        $recentCourses = $coursesTable->find()
            ->contain(['Subjects', 'Classes', 'Teachers'])
            ->where(['Courses.is_active' => true])
            ->orderBy(['Courses.created' => 'DESC'])
            ->limit(5)
            ->all();

        // Attendance summary for today
        $attendanceTable = $this->fetchTable('Attendances');
        $today = date('Y-m-d');
        $todayAttendance = [
            'present' => $attendanceTable->find()->where(['date' => $today, 'status' => 'present'])->count(),
            'absent' => $attendanceTable->find()->where(['date' => $today, 'status' => 'absent'])->count(),
            'late' => $attendanceTable->find()->where(['date' => $today, 'status' => 'late'])->count(),
        ];

        // Get enrollment stats
        $studentCoursesTable = $this->fetchTable('StudentCourses');
        $enrollmentStats = [
            'enrolled' => $studentCoursesTable->find()->where(['status' => 'enrolled'])->count(),
            'completed' => $studentCoursesTable->find()->where(['status' => 'completed'])->count(),
        ];

        $this->set(compact(
            'user',
            'stats',
            'activeUsers',
            'inactiveUsers',
            'recentTeachers',
            'recentStudents',
            'recentCourses',
            'todayAttendance',
            'enrollmentStats'
        ));
    }

    private function fetchCount(string $table, array $conditions = []): int
    {
        return $this->getTableLocator()->get($table)->find()->where($conditions)->count();
    }
}

