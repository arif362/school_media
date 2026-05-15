<?php
/**
 * AcademicCalendar Controller
 *
 * Manages academic calendar events (terms, holidays, exams).
 *
 * @created 2026-05-15
 * @author Arif
 */
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Model\Entity\AcademicEvent;

class AcademicCalendarController extends AdminAppController
{
    public function index()
    {
        $academicEventsTable = $this->fetchTable('AcademicEvents');

        // Filters
        $academicYear = $this->request->getQuery('academic_year');
        $eventType = $this->request->getQuery('event_type');
        $viewMode = $this->request->getQuery('view', 'list'); // list or calendar

        $query = $academicEventsTable->find()
            ->find('ordered');

        if ($academicYear) {
            $query->find('byAcademicYear', year: $academicYear);
        } else {
            // Default to current academic year
            $academicYear = $this->getCurrentAcademicYear();
            $query->find('byAcademicYear', year: $academicYear);
        }

        if ($eventType) {
            $query->find('byType', eventType: $eventType);
        }

        $events = $query->all();

        // For calendar view, organize events by month
        $eventsByMonth = [];
        if ($viewMode === 'calendar') {
            foreach ($events as $event) {
                // Cake\I18n\Date has format() method
                $month = $event->start_date->format('Y-m');
                $eventsByMonth[$month][] = $event;
            }
        }

        $academicYears = $this->getAcademicYears();
        $eventTypes = AcademicEvent::getEventTypes();

        $this->set(compact(
            'events',
            'eventsByMonth',
            'academicYears',
            'eventTypes',
            'academicYear',
            'eventType',
            'viewMode'
        ));
    }

    public function add()
    {
        $academicEventsTable = $this->fetchTable('AcademicEvents');
        $event = $academicEventsTable->newEmptyEntity();

        if ($this->request->is('post')) {
            $event = $academicEventsTable->patchEntity($event, $this->request->getData());
            if ($academicEventsTable->save($event)) {
                $this->Flash->success(__('Event has been created.'));
                return $this->redirect(['action' => 'index', '?' => ['academic_year' => $event->academic_year]]);
            }
            $this->Flash->error(__('Unable to create event. Please try again.'));
        }

        $this->setFormData();
        $this->set(compact('event'));
    }

    public function edit(?string $id = null)
    {
        $academicEventsTable = $this->fetchTable('AcademicEvents');
        $event = $academicEventsTable->get($id);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $event = $academicEventsTable->patchEntity($event, $this->request->getData());
            if ($academicEventsTable->save($event)) {
                $this->Flash->success(__('Event has been updated.'));
                return $this->redirect(['action' => 'index', '?' => ['academic_year' => $event->academic_year]]);
            }
            $this->Flash->error(__('Unable to update event. Please try again.'));
        }

        $this->setFormData();
        $this->set(compact('event'));
    }

    public function delete(?string $id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $academicEventsTable = $this->fetchTable('AcademicEvents');
        $event = $academicEventsTable->get($id);
        $academicYear = $event->academic_year;

        if ($academicEventsTable->delete($event)) {
            $this->Flash->success(__('Event has been deleted.'));
        } else {
            $this->Flash->error(__('Unable to delete event. Please try again.'));
        }

        return $this->redirect(['action' => 'index', '?' => ['academic_year' => $academicYear]]);
    }

    private function setFormData(): void
    {
        $academicYears = $this->getAcademicYears();
        $eventTypes = AcademicEvent::getEventTypes();

        $this->set(compact('academicYears', 'eventTypes'));
    }

    private function getAcademicYears(): array
    {
        $currentYear = (int) date('Y');
        $years = [];
        for ($i = -1; $i < 3; $i++) {
            $year = $currentYear + $i;
            $key = $year . '-' . ($year + 1);
            $years[$key] = $key;
        }
        return $years;
    }

    private function getCurrentAcademicYear(): string
    {
        $month = (int) date('n');
        $year = (int) date('Y');

        // Academic year starts in August
        if ($month >= 8) {
            return $year . '-' . ($year + 1);
        }
        return ($year - 1) . '-' . $year;
    }
}
