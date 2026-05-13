<?php
declare(strict_types=1);

namespace App\Controller\Teacher;

class DashboardController extends TeacherAppController
{
    public function index()
    {
        $user = $this->request->getAttribute('identity');
        $coursesTable = $this->fetchTable('Courses');

        // Get teacher's courses
        $courses = $coursesTable->find()
            ->contain(['Subjects', 'Classes'])
            ->where([
                'Courses.teacher_id' => $user->id,
                'Courses.is_active' => true,
            ])
            ->orderBy(['Classes.grade_level' => 'ASC', 'Subjects.name' => 'ASC'])
            ->all();

        // Get enrollment stats for each course
        $studentCoursesTable = $this->fetchTable('StudentCourses');
        $courseStats = [];
        foreach ($courses as $course) {
            $enrolledCount = $studentCoursesTable->find()
                ->where([
                    'course_id' => $course->id,
                    'status' => 'enrolled',
                ])
                ->count();
            $courseStats[$course->id] = [
                'enrolled' => $enrolledCount,
            ];
        }

        // Get total students across all courses
        $totalStudents = $studentCoursesTable->find()
            ->where([
                'course_id IN' => $courses->extract('id')->toArray() ?: [0],
                'status' => 'enrolled',
            ])
            ->group(['student_id'])
            ->count();

        $this->set(compact('courses', 'courseStats', 'totalStudents'));
    }
}
