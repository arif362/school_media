<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Model\Entity\Subject;

class SubjectsController extends AdminAppController
{
    public function index()
    {
        $query = $this->Subjects->find()
            ->orderBy(['Subjects.category' => 'ASC', 'Subjects.name' => 'ASC']);

        $category = $this->request->getQuery('category');
        if ($category) {
            $query->where(['Subjects.category' => $category]);
        }

        $this->paginate = ['limit' => 25];
        $subjects = $this->paginate($query);

        $categories = Subject::getCategories();

        $this->set(compact('subjects', 'categories', 'category'));
    }

    public function add()
    {
        $subject = $this->Subjects->newEmptyEntity();

        if ($this->request->is('post')) {
            $subject = $this->Subjects->patchEntity($subject, $this->request->getData());
            if ($this->Subjects->save($subject)) {
                $this->Flash->success(__('Subject has been created.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Unable to create subject. Please try again.'));
        }

        $categories = Subject::getCategories();
        $this->set(compact('subject', 'categories'));
    }

    public function edit(?string $id = null)
    {
        $subject = $this->Subjects->get($id);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $subject = $this->Subjects->patchEntity($subject, $this->request->getData());
            if ($this->Subjects->save($subject)) {
                $this->Flash->success(__('Subject has been updated.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Unable to update subject. Please try again.'));
        }

        $categories = Subject::getCategories();
        $this->set(compact('subject', 'categories'));
    }

    public function view(?string $id = null)
    {
        $subject = $this->Subjects->get($id, contain: ['Courses' => ['Classes', 'Teachers']]);

        // Get teachers who teach this subject
        $teacherSubjectsTable = $this->fetchTable('TeacherSubjects');
        $teachers = $teacherSubjectsTable->find()
            ->contain(['Teachers'])
            ->where(['TeacherSubjects.subject_id' => $id])
            ->all();

        $this->set(compact('subject', 'teachers'));
    }

    public function delete(?string $id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $subject = $this->Subjects->get($id);

        if ($this->Subjects->delete($subject)) {
            $this->Flash->success(__('Subject has been deleted.'));
        } else {
            $this->Flash->error(__('Unable to delete subject. It may have associated courses.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function seedCambridge()
    {
        $this->request->allowMethod(['post']);

        $cambridgeSubjects = Subject::getCambridgeSubjects();
        $added = 0;

        foreach ($cambridgeSubjects as $subjectData) {
            $existing = $this->Subjects->find()
                ->where(['code' => $subjectData['code']])
                ->first();

            if (!$existing) {
                $subject = $this->Subjects->newEntity([
                    'name' => $subjectData['name'],
                    'code' => $subjectData['code'],
                    'category' => $subjectData['category'],
                    'credit_hours' => 1,
                    'is_active' => true,
                ]);

                if ($this->Subjects->save($subject)) {
                    $added++;
                }
            }
        }

        if ($added > 0) {
            $this->Flash->success(__('Added {0} Cambridge curriculum subjects.', $added));
        } else {
            $this->Flash->info(__('All Cambridge subjects already exist.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
