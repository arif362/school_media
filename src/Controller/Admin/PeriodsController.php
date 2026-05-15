<?php
/**
 * Periods Controller
 *
 * Manages period (time slot) CRUD operations for class routines.
 *
 * @created 2026-05-15
 * @author Arif
 */
declare(strict_types=1);

namespace App\Controller\Admin;

class PeriodsController extends AdminAppController
{
    public function index()
    {
        $query = $this->Periods->find()
            ->orderBy(['Periods.academic_year' => 'DESC', 'Periods.order_num' => 'ASC']);

        // Filter by academic year
        $academicYear = $this->request->getQuery('academic_year');
        if ($academicYear) {
            $query->where(['Periods.academic_year' => $academicYear]);
        }

        $this->paginate = ['limit' => 50];
        $periods = $this->paginate($query);

        $academicYears = $this->getAcademicYears();

        $this->set(compact('periods', 'academicYears', 'academicYear'));
    }

    public function add()
    {
        $period = $this->Periods->newEmptyEntity();

        if ($this->request->is('post')) {
            $data = $this->request->getData();

            // Auto-set order_num if not provided
            if (empty($data['order_num']) && !empty($data['academic_year'])) {
                $data['order_num'] = $this->Periods->getNextOrderNum($data['academic_year']);
            }

            $period = $this->Periods->patchEntity($period, $data);
            if ($this->Periods->save($period)) {
                $this->Flash->success(__('Period has been created.'));
                return $this->redirect(['action' => 'index', '?' => ['academic_year' => $period->academic_year]]);
            }
            $this->Flash->error(__('Unable to create period. Please try again.'));
        }

        $academicYears = $this->getAcademicYears();
        $this->set(compact('period', 'academicYears'));
    }

    public function edit(?string $id = null)
    {
        $period = $this->Periods->get($id);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $period = $this->Periods->patchEntity($period, $this->request->getData());
            if ($this->Periods->save($period)) {
                $this->Flash->success(__('Period has been updated.'));
                return $this->redirect(['action' => 'index', '?' => ['academic_year' => $period->academic_year]]);
            }
            $this->Flash->error(__('Unable to update period. Please try again.'));
        }

        $academicYears = $this->getAcademicYears();
        $this->set(compact('period', 'academicYears'));
    }

    public function delete(?string $id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $period = $this->Periods->get($id);
        $academicYear = $period->academic_year;

        if ($this->Periods->delete($period)) {
            $this->Flash->success(__('Period has been deleted.'));
        } else {
            $this->Flash->error(__('Unable to delete period. It may be in use by class routines.'));
        }

        return $this->redirect(['action' => 'index', '?' => ['academic_year' => $academicYear]]);
    }

    public function reorder()
    {
        $this->request->allowMethod(['post']);
        $data = $this->request->getData();

        if (!empty($data['order']) && is_array($data['order'])) {
            foreach ($data['order'] as $position => $id) {
                $period = $this->Periods->get($id);
                $period->order_num = $position + 1;
                $this->Periods->save($period);
            }
            $this->Flash->success(__('Period order has been updated.'));
        }

        return $this->redirect(['action' => 'index', '?' => ['academic_year' => $data['academic_year'] ?? null]]);
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
