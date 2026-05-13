<?php
declare(strict_types=1);

namespace App\Controller\Teacher;

use App\Model\Entity\StudentCourse;

class CoursesController extends TeacherAppController
{
    public function index()
    {
        $user = $this->request->getAttribute('identity');
        $coursesTable = $this->fetchTable('Courses');

        $query = $coursesTable->find()
            ->contain(['Subjects', 'Classes'])
            ->where(['Courses.teacher_id' => $user->id])
            ->orderBy(['Courses.academic_year' => 'DESC', 'Classes.grade_level' => 'ASC']);

        // Filter by academic year
        $academicYear = $this->request->getQuery('academic_year');
        if ($academicYear) {
            $query->where(['Courses.academic_year' => $academicYear]);
        }

        // Filter by active status
        $showInactive = $this->request->getQuery('show_inactive');
        if (!$showInactive) {
            $query->where(['Courses.is_active' => true]);
        }

        $courses = $query->all();

        // Get enrollment counts
        $studentCoursesTable = $this->fetchTable('StudentCourses');
        $courseStats = [];
        foreach ($courses as $course) {
            $stats = $studentCoursesTable->find()
                ->select([
                    'status',
                    'count' => $studentCoursesTable->find()->func()->count('*'),
                ])
                ->where(['course_id' => $course->id])
                ->group(['status'])
                ->all()
                ->combine('status', 'count')
                ->toArray();

            $courseStats[$course->id] = $stats;
        }

        $academicYears = $this->getAcademicYears();

        $this->set(compact('courses', 'courseStats', 'academicYears', 'academicYear', 'showInactive'));
    }

    public function view(?string $id = null)
    {
        $user = $this->request->getAttribute('identity');
        $coursesTable = $this->fetchTable('Courses');

        $course = $coursesTable->get($id, contain: [
            'Subjects',
            'Classes',
            'CourseMaterials' => ['Uploaders'],
        ]);

        // Ensure teacher owns this course
        if ($course->teacher_id !== $user->id && $user->role !== 'admin') {
            $this->Flash->error(__('You do not have access to this course.'));
            return $this->redirect(['action' => 'index']);
        }

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

    public function updateGrades(?string $id = null)
    {
        $user = $this->request->getAttribute('identity');
        $coursesTable = $this->fetchTable('Courses');

        $course = $coursesTable->get($id, contain: ['Subjects', 'Classes']);

        // Ensure teacher owns this course
        if ($course->teacher_id !== $user->id && $user->role !== 'admin') {
            $this->Flash->error(__('You do not have access to this course.'));
            return $this->redirect(['action' => 'index']);
        }

        $studentCoursesTable = $this->fetchTable('StudentCourses');

        if ($this->request->is(['patch', 'post', 'put'])) {
            $grades = $this->request->getData('grades', []);
            $updated = 0;

            foreach ($grades as $enrollmentId => $data) {
                $enrollment = $studentCoursesTable->get($enrollmentId);

                // Verify this enrollment belongs to this course
                if ($enrollment->course_id !== (int)$id) {
                    continue;
                }

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

        $enrollments = $studentCoursesTable->find()
            ->contain(['Students'])
            ->where(['StudentCourses.course_id' => $id])
            ->orderBy(['Students.name' => 'ASC'])
            ->all();

        $grades = StudentCourse::getGrades();
        $statuses = StudentCourse::getStatuses();

        $this->set(compact('course', 'enrollments', 'grades', 'statuses'));
    }

    public function materials(?string $id = null)
    {
        $user = $this->request->getAttribute('identity');
        $coursesTable = $this->fetchTable('Courses');

        $course = $coursesTable->get($id, contain: ['Subjects', 'Classes']);

        // Ensure teacher owns this course
        if ($course->teacher_id !== $user->id && $user->role !== 'admin') {
            $this->Flash->error(__('You do not have access to this course.'));
            return $this->redirect(['action' => 'index']);
        }

        $courseMaterialsTable = $this->fetchTable('CourseMaterials');
        $materials = $courseMaterialsTable->find()
            ->contain(['Uploaders'])
            ->where(['course_id' => $id])
            ->orderBy(['order_num' => 'ASC', 'created' => 'DESC'])
            ->all();

        $this->set(compact('course', 'materials'));
    }

    public function addMaterial(?string $id = null)
    {
        $user = $this->request->getAttribute('identity');
        $coursesTable = $this->fetchTable('Courses');

        $course = $coursesTable->get($id, contain: ['Subjects', 'Classes']);

        // Ensure teacher owns this course
        if ($course->teacher_id !== $user->id && $user->role !== 'admin') {
            $this->Flash->error(__('You do not have access to this course.'));
            return $this->redirect(['action' => 'index']);
        }

        $courseMaterialsTable = $this->fetchTable('CourseMaterials');
        $material = $courseMaterialsTable->newEmptyEntity();

        if ($this->request->is('post')) {
            $data = $this->request->getData();
            $data['course_id'] = $id;
            $data['uploaded_by'] = $user->id;

            // Handle file upload if present
            $uploadedFile = $this->request->getData('file');
            if ($uploadedFile && $uploadedFile->getError() === UPLOAD_ERR_OK) {
                $filename = time() . '_' . $uploadedFile->getClientFilename();
                $uploadPath = WWW_ROOT . 'uploads' . DS . 'materials' . DS;

                if (!is_dir($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }

                $uploadedFile->moveTo($uploadPath . $filename);
                $data['file_path'] = 'materials/' . $filename;
            }

            $material = $courseMaterialsTable->patchEntity($material, $data);

            if ($courseMaterialsTable->save($material)) {
                $this->Flash->success(__('Course material has been added.'));
                return $this->redirect(['action' => 'materials', $id]);
            }
            $this->Flash->error(__('Unable to add course material. Please try again.'));
        }

        $types = \App\Model\Entity\CourseMaterial::getTypes();
        $this->set(compact('course', 'material', 'types'));
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
