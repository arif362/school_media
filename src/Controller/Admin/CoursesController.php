<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Model\Entity\Course;
use App\Model\Entity\StudentCourse;

class CoursesController extends AdminAppController
{
    public function index()
    {
        $query = $this->Courses->find()
            ->contain(['Subjects', 'Classes', 'Teachers'])
            ->orderBy(['Classes.grade_level' => 'ASC', 'Subjects.name' => 'ASC']);

        // Filters
        $classId = $this->request->getQuery('class_id');
        $subjectId = $this->request->getQuery('subject_id');
        $teacherId = $this->request->getQuery('teacher_id');
        $academicYear = $this->request->getQuery('academic_year');

        if ($classId) {
            $query->where(['Courses.class_id' => $classId]);
        }
        if ($subjectId) {
            $query->where(['Courses.subject_id' => $subjectId]);
        }
        if ($teacherId) {
            $query->where(['Courses.teacher_id' => $teacherId]);
        }
        if ($academicYear) {
            $query->where(['Courses.academic_year' => $academicYear]);
        }

        $this->paginate = ['limit' => 25];
        $courses = $this->paginate($query);

        // Get filter options
        $classesTable = $this->fetchTable('Classes');
        $classes = $classesTable->find('list', keyField: 'id', valueField: function ($class) {
            return $class->name . ($class->section ? ' - ' . $class->section : '');
        })->find('active')->toArray();

        $subjectsTable = $this->fetchTable('Subjects');
        $subjects = $subjectsTable->find('list')->find('active')->toArray();

        $usersTable = $this->fetchTable('Users');
        $teachers = $usersTable->find('list', keyField: 'id', valueField: 'name')
            ->where(['role IN' => ['admin', 'teacher']])
            ->toArray();

        $academicYears = $this->getAcademicYears();

        $this->set(compact('courses', 'classes', 'subjects', 'teachers', 'academicYears', 'classId', 'subjectId', 'teacherId', 'academicYear'));
    }

    public function add()
    {
        $course = $this->Courses->newEmptyEntity();

        if ($this->request->is('post')) {
            $course = $this->Courses->patchEntity($course, $this->request->getData());
            if ($this->Courses->save($course)) {
                $this->Flash->success(__('Course has been created.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Unable to create course. Please try again.'));
        }

        $this->setFormData();
        $this->set(compact('course'));
    }

    public function edit(?string $id = null)
    {
        $course = $this->Courses->get($id);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $course = $this->Courses->patchEntity($course, $this->request->getData());
            if ($this->Courses->save($course)) {
                $this->Flash->success(__('Course has been updated.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Unable to update course. Please try again.'));
        }

        $this->setFormData();
        $this->set(compact('course'));
    }

    public function view(?string $id = null)
    {
        $course = $this->Courses->get($id, contain: [
            'Subjects',
            'Classes',
            'Teachers',
            'CourseMaterials' => ['Uploaders'],
        ]);

        // Get enrolled students
        $studentCoursesTable = $this->fetchTable('StudentCourses');
        $enrollments = $studentCoursesTable->find()
            ->contain(['Students'])
            ->where(['StudentCourses.course_id' => $id])
            ->orderBy(['Students.name' => 'ASC'])
            ->all();

        $enrolledCount = $enrollments->filter(fn($e) => $e->status === 'enrolled')->count();

        $this->set(compact('course', 'enrollments', 'enrolledCount'));
    }

    public function delete(?string $id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $course = $this->Courses->get($id);

        if ($this->Courses->delete($course)) {
            $this->Flash->success(__('Course has been deleted.'));
        } else {
            $this->Flash->error(__('Unable to delete course. Please try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function enrollStudents(?string $id = null)
    {
        $course = $this->Courses->get($id, contain: ['Subjects', 'Classes']);
        $studentCoursesTable = $this->fetchTable('StudentCourses');

        if ($this->request->is('post')) {
            $studentIds = $this->request->getData('student_ids', []);
            $enrolled = 0;

            foreach ($studentIds as $studentId) {
                // Check if already enrolled
                $existing = $studentCoursesTable->find()
                    ->where([
                        'student_id' => $studentId,
                        'course_id' => $id,
                    ])
                    ->first();

                if (!$existing) {
                    $enrollment = $studentCoursesTable->newEntity([
                        'student_id' => $studentId,
                        'course_id' => $id,
                        'enrolled_date' => date('Y-m-d'),
                        'status' => 'enrolled',
                    ]);

                    if ($studentCoursesTable->save($enrollment)) {
                        $enrolled++;
                    }
                }
            }

            if ($enrolled > 0) {
                $this->Flash->success(__('Enrolled {0} students in the course.', $enrolled));
            }
            return $this->redirect(['action' => 'view', $id]);
        }

        // Get students in the class not yet enrolled
        $enrolledStudentIds = $studentCoursesTable->find()
            ->select(['student_id'])
            ->where(['course_id' => $id])
            ->all()
            ->extract('student_id')
            ->toArray();

        $studentClassesTable = $this->fetchTable('StudentClasses');
        $query = $studentClassesTable->find()
            ->contain(['Students'])
            ->where([
                'StudentClasses.class_id' => $course->class_id,
                'StudentClasses.status' => 'active',
            ]);

        if (!empty($enrolledStudentIds)) {
            $query->where(['StudentClasses.student_id NOT IN' => $enrolledStudentIds]);
        }

        $availableStudents = $query->all();

        $this->set(compact('course', 'availableStudents'));
    }

    public function updateGrades(?string $id = null)
    {
        $course = $this->Courses->get($id, contain: ['Subjects', 'Classes']);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $studentCoursesTable = $this->fetchTable('StudentCourses');
            $grades = $this->request->getData('grades', []);
            $updated = 0;

            foreach ($grades as $enrollmentId => $data) {
                $enrollment = $studentCoursesTable->get($enrollmentId);
                $enrollment = $studentCoursesTable->patchEntity($enrollment, [
                    'grade' => $data['grade'] ?? null,
                    'marks' => $data['marks'] ?? null,
                    'status' => $data['status'] ?? 'enrolled',
                    'remarks' => $data['remarks'] ?? null,
                ]);

                if ($studentCoursesTable->save($enrollment)) {
                    $updated++;
                }
            }

            $this->Flash->success(__('Updated grades for {0} students.', $updated));
            return $this->redirect(['action' => 'view', $id]);
        }

        $studentCoursesTable = $this->fetchTable('StudentCourses');
        $enrollments = $studentCoursesTable->find()
            ->contain(['Students'])
            ->where(['StudentCourses.course_id' => $id])
            ->orderBy(['Students.name' => 'ASC'])
            ->all();

        $grades = StudentCourse::getGrades();
        $statuses = StudentCourse::getStatuses();

        $this->set(compact('course', 'enrollments', 'grades', 'statuses'));
    }

    private function setFormData(): void
    {
        $classesTable = $this->fetchTable('Classes');
        $classes = $classesTable->find('list', keyField: 'id', valueField: function ($class) {
            return $class->name . ($class->section ? ' - ' . $class->section : '') . ' (' . $class->grade_level . ')';
        })->find('active')->toArray();

        $subjectsTable = $this->fetchTable('Subjects');
        $subjects = $subjectsTable->find('list')->find('active')->toArray();

        $usersTable = $this->fetchTable('Users');
        $teachers = $usersTable->find('list', keyField: 'id', valueField: 'name')
            ->where(['role IN' => ['admin', 'teacher']])
            ->toArray();

        $academicYears = $this->getAcademicYears();
        $terms = Course::getTerms();

        $this->set(compact('classes', 'subjects', 'teachers', 'academicYears', 'terms'));
    }

    private function getAcademicYears(): array
    {
        $currentYear = (int)date('Y');
        $years = [];
        for ($i = -1; $i < 3; $i++) {
            $year = $currentYear + $i;
            $key = $year . '-' . ($year + 1);
            $years[$key] = $key;
        }
        return $years;
    }
}
