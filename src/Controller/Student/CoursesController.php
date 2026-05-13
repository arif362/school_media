<?php
declare(strict_types=1);

namespace App\Controller\Student;

class CoursesController extends StudentAppController
{
    public function index()
    {
        $user = $this->request->getAttribute('identity');
        $studentCoursesTable = $this->fetchTable('StudentCourses');

        // Get student's enrolled courses
        $enrollments = $studentCoursesTable->find()
            ->contain([
                'Courses' => ['Subjects', 'Classes', 'Teachers'],
            ])
            ->where(['StudentCourses.student_id' => $user->id])
            ->orderBy(['Courses.academic_year' => 'DESC', 'StudentCourses.status' => 'ASC'])
            ->all();

        // Group by status
        $activeEnrollments = $enrollments->filter(fn($e) => $e->status === 'enrolled');
        $completedEnrollments = $enrollments->filter(fn($e) => $e->status === 'completed');

        // Calculate stats
        $totalCourses = $enrollments->count();
        $activeCourses = $activeEnrollments->count();
        $completedCourses = $completedEnrollments->count();

        // Calculate average grade for completed courses
        $gradedEnrollments = $enrollments->filter(fn($e) => $e->marks !== null);
        $averageMarks = $gradedEnrollments->count() > 0
            ? $gradedEnrollments->reduce(fn($acc, $e) => $acc + $e->marks, 0) / $gradedEnrollments->count()
            : null;

        $this->set(compact('enrollments', 'activeEnrollments', 'completedEnrollments', 'totalCourses', 'activeCourses', 'completedCourses', 'averageMarks'));
    }

    public function view(?string $id = null)
    {
        $user = $this->request->getAttribute('identity');
        $studentCoursesTable = $this->fetchTable('StudentCourses');

        // Get the enrollment
        $enrollment = $studentCoursesTable->find()
            ->contain([
                'Courses' => [
                    'Subjects',
                    'Classes',
                    'Teachers',
                    'CourseMaterials' => function ($q) {
                        return $q->where(['CourseMaterials.is_visible' => true])
                            ->orderBy(['CourseMaterials.order_num' => 'ASC']);
                    },
                ],
            ])
            ->where([
                'StudentCourses.id' => $id,
                'StudentCourses.student_id' => $user->id,
            ])
            ->first();

        if (!$enrollment) {
            $this->Flash->error(__('You are not enrolled in this course.'));
            return $this->redirect(['action' => 'index']);
        }

        // Get classmates (other students in the same course)
        $classmates = $studentCoursesTable->find()
            ->contain(['Students'])
            ->where([
                'StudentCourses.course_id' => $enrollment->course_id,
                'StudentCourses.student_id !=' => $user->id,
                'StudentCourses.status' => 'enrolled',
            ])
            ->limit(10)
            ->all();

        $this->set(compact('enrollment', 'classmates'));
    }
}
