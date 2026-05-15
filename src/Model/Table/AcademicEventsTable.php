<?php
/**
 * AcademicEvents Table
 *
 * Manages academic calendar events (terms, holidays, exams).
 *
 * @created 2026-05-15
 * @author Arif
 */
declare(strict_types=1);

namespace App\Model\Table;

use App\Model\Entity\AcademicEvent;
use Cake\ORM\Query\SelectQuery;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class AcademicEventsTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('academic_events');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->scalar('academic_year')
            ->maxLength('academic_year', 20)
            ->requirePresence('academic_year', 'create')
            ->notEmptyString('academic_year');

        $validator
            ->scalar('title')
            ->maxLength('title', 150)
            ->requirePresence('title', 'create')
            ->notEmptyString('title');

        $validator
            ->scalar('event_type')
            ->maxLength('event_type', 30)
            ->requirePresence('event_type', 'create')
            ->notEmptyString('event_type')
            ->inList('event_type', array_keys(AcademicEvent::getEventTypes()), __('Invalid event type'));

        $validator
            ->date('start_date')
            ->requirePresence('start_date', 'create')
            ->notEmptyDate('start_date');

        $validator
            ->date('end_date')
            ->allowEmptyDate('end_date');

        $validator
            ->scalar('description')
            ->allowEmptyString('description');

        $validator
            ->boolean('is_active');

        return $validator;
    }

    /**
     * Find active events
     */
    public function findActive(SelectQuery $query): SelectQuery
    {
        return $query->where(['AcademicEvents.is_active' => true]);
    }

    /**
     * Find events by academic year
     */
    public function findByAcademicYear(SelectQuery $query, string $year): SelectQuery
    {
        return $query->where(['AcademicEvents.academic_year' => $year]);
    }

    /**
     * Find events by type
     */
    public function findByType(SelectQuery $query, string $eventType): SelectQuery
    {
        return $query->where(['AcademicEvents.event_type' => $eventType]);
    }

    /**
     * Find upcoming events
     */
    public function findUpcoming(SelectQuery $query, int $days = 30): SelectQuery
    {
        $today = date('Y-m-d');
        $futureDate = date('Y-m-d', strtotime("+{$days} days"));

        return $query
            ->where([
                'AcademicEvents.start_date >=' => $today,
                'AcademicEvents.start_date <=' => $futureDate,
            ])
            ->orderBy(['AcademicEvents.start_date' => 'ASC']);
    }

    /**
     * Find events ordered by date
     */
    public function findOrdered(SelectQuery $query): SelectQuery
    {
        return $query->orderBy(['AcademicEvents.start_date' => 'ASC']);
    }

    /**
     * Get events for a specific month (for calendar view)
     */
    public function getEventsForMonth(int $year, int $month, ?string $academicYear = null): array
    {
        $startOfMonth = sprintf('%04d-%02d-01', $year, $month);
        $endOfMonth = date('Y-m-t', strtotime($startOfMonth));

        $query = $this->find()
            ->find('active')
            ->where([
                'OR' => [
                    // Event starts in this month
                    [
                        'AcademicEvents.start_date >=' => $startOfMonth,
                        'AcademicEvents.start_date <=' => $endOfMonth,
                    ],
                    // Event ends in this month
                    [
                        'AcademicEvents.end_date >=' => $startOfMonth,
                        'AcademicEvents.end_date <=' => $endOfMonth,
                    ],
                    // Event spans this month
                    [
                        'AcademicEvents.start_date <=' => $startOfMonth,
                        'AcademicEvents.end_date >=' => $endOfMonth,
                    ],
                ],
            ])
            ->orderBy(['AcademicEvents.start_date' => 'ASC']);

        if ($academicYear) {
            $query->where(['AcademicEvents.academic_year' => $academicYear]);
        }

        return $query->all()->toArray();
    }

    /**
     * Check if a date falls on a holiday
     */
    public function isHoliday(string $date, ?string $academicYear = null): bool
    {
        $query = $this->find()
            ->find('active')
            ->find('byType', eventType: AcademicEvent::TYPE_HOLIDAY)
            ->where([
                'AcademicEvents.start_date <=' => $date,
                'OR' => [
                    'AcademicEvents.end_date >=' => $date,
                    'AcademicEvents.end_date IS' => null,
                ],
            ]);

        if ($academicYear) {
            $query->where(['AcademicEvents.academic_year' => $academicYear]);
        }

        return $query->count() > 0;
    }
}
