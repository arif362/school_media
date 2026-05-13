<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Model\Entity\SchoolClass;

class ClassesController extends AdminAppController
{
    public function index()
    {
        $query = $this->Classes->find()
            ->contain(['ClassTeachers'])
            ->orderBy(['Classes.grade_level' => 'ASC', 'Classes.section' => 'ASC']);

        $academicYear = $this->request->getQuery('academic_year');
        if ($academicYear) {
            $query->where(['Classes.academic_year' => $academicYear]);
        }

        $this->paginate = ['limit' => 20];
        $classes = $this->paginate($query);

        // Get available academic years
        $years = $this->Classes->find()
            ->select(['academic_year'])
            ->distinct()
            ->orderByDesc('academic_year')
            ->all()
            ->combine('academic_year', 'academic_year')
            ->toArray();

        $this->set(compact('classes', 'years', 'academicYear'));
    }

    public function add()
    {
        $class = $this->Classes->newEmptyEntity();

        if ($this->request->is('post')) {
            $class = $this->Classes->patchEntity($class, $this->request->getData());
            if ($this->Classes->save($class)) {
                $this->Flash->success(__('Class has been created.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Unable to create class. Please try again.'));
        }

        $teachers = $this->fetchTable('Users')
            ->find('list', keyField: 'id', valueField: 'name')
            ->where(['role IN' => ['admin', 'teacher']])
            ->toArray();

        $gradeLevels = SchoolClass::GRADE_LEVELS;
        $sections = SchoolClass::SECTIONS;
        $academicYears = $this->getAcademicYears();

        $this->set(compact('class', 'teachers', 'gradeLevels', 'sections', 'academicYears'));
    }

    public function edit(?string $id = null)
    {
        $class = $this->Classes->get($id);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $class = $this->Classes->patchEntity($class, $this->request->getData());
            if ($this->Classes->save($class)) {
                $this->Flash->success(__('Class has been updated.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Unable to update class. Please try again.'));
        }

        $teachers = $this->fetchTable('Users')
            ->find('list', keyField: 'id', valueField: 'name')
            ->where(['role IN' => ['admin', 'teacher']])
            ->toArray();

        $gradeLevels = SchoolClass::GRADE_LEVELS;
        $sections = SchoolClass::SECTIONS;
        $academicYears = $this->getAcademicYears();

        $this->set(compact('class', 'teachers', 'gradeLevels', 'sections', 'academicYears'));
    }

    public function view(?string $id = null)
    {
        $class = $this->Classes->get($id, contain: ['ClassTeachers']);

        $studentClassesTable = $this->fetchTable('StudentClasses');
        $students = $studentClassesTable->find()
            ->contain(['Students'])
            ->where([
                'StudentClasses.class_id' => $id,
                'StudentClasses.status' => 'active',
            ])
            ->orderBy(['Students.name' => 'ASC'])
            ->all();

        $this->set(compact('class', 'students'));
    }

    public function delete(?string $id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $class = $this->Classes->get($id);

        if ($this->Classes->delete($class)) {
            $this->Flash->success(__('Class has been deleted.'));
        } else {
            $this->Flash->error(__('Unable to delete class. Please try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function enrollStudent(?string $classId = null)
    {
        $class = $this->Classes->get($classId);
        $studentClassesTable = $this->fetchTable('StudentClasses');

        if ($this->request->is('post')) {
            $data = $this->request->getData();
            $data['class_id'] = $classId;
            $data['enrolled_date'] = date('Y-m-d');
            $data['status'] = 'active';

            $studentClass = $studentClassesTable->newEntity($data);
            if ($studentClassesTable->save($studentClass)) {
                // Update student's class_id
                $usersTable = $this->fetchTable('Users');
                $student = $usersTable->get($data['student_id']);
                $student->class_id = $classId;
                $usersTable->save($student);

                $this->Flash->success(__('Student enrolled successfully.'));
                return $this->redirect(['action' => 'view', $classId]);
            }
            $this->Flash->error(__('Unable to enroll student. Please try again.'));
        }

        // Get students not already enrolled in this class
        $enrolledStudentIds = $studentClassesTable->find()
            ->select(['student_id'])
            ->where(['class_id' => $classId, 'status' => 'active'])
            ->all()
            ->extract('student_id')
            ->toArray();

        $studentsQuery = $this->fetchTable('Users')
            ->find('list', keyField: 'id', valueField: 'name')
            ->where(['role' => 'student']);

        if (!empty($enrolledStudentIds)) {
            $studentsQuery->where(['id NOT IN' => $enrolledStudentIds]);
        }

        $students = $studentsQuery->toArray();

        $this->set(compact('class', 'students'));
    }

    private function getAcademicYears(): array
    {
        $currentYear = (int)date('Y');
        $years = [];
        for ($i = 0; $i < 3; $i++) {
            $year = $currentYear - $i;
            $key = $year . '-' . ($year + 1);
            $years[$key] = $key;
        }
        return $years;
    }
}
