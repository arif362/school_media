<?php
/**
 * Periods Table
 *
 * Manages daily time slot definitions for class routines.
 *
 * @created 2026-05-15
 * @author Arif
 */
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class PeriodsTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('periods');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasMany('ClassRoutines', [
            'foreignKey' => 'period_id',
            'dependent' => true,
        ]);
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->scalar('academic_year')
            ->maxLength('academic_year', 20)
            ->requirePresence('academic_year', 'create')
            ->notEmptyString('academic_year');

        $validator
            ->scalar('name')
            ->maxLength('name', 50)
            ->requirePresence('name', 'create')
            ->notEmptyString('name');

        $validator
            ->time('start_time')
            ->requirePresence('start_time', 'create')
            ->notEmptyTime('start_time');

        $validator
            ->time('end_time')
            ->requirePresence('end_time', 'create')
            ->notEmptyTime('end_time');

        $validator
            ->boolean('is_break');

        $validator
            ->integer('order_num')
            ->notEmptyString('order_num');

        $validator
            ->boolean('is_active');

        return $validator;
    }

    /**
     * Find active periods
     */
    public function findActive(SelectQuery $query): SelectQuery
    {
        return $query->where(['Periods.is_active' => true]);
    }

    /**
     * Find periods by academic year
     */
    public function findByAcademicYear(SelectQuery $query, string $year): SelectQuery
    {
        return $query->where(['Periods.academic_year' => $year]);
    }

    /**
     * Find periods ordered by order_num
     */
    public function findOrdered(SelectQuery $query): SelectQuery
    {
        return $query->orderBy(['Periods.order_num' => 'ASC']);
    }

    /**
     * Get periods for a specific academic year, ordered and active
     */
    public function getPeriodsForYear(string $academicYear): array
    {
        return $this->find()
            ->find('active')
            ->find('byAcademicYear', year: $academicYear)
            ->find('ordered')
            ->all()
            ->toArray();
    }

    /**
     * Get the next order number for a new period in an academic year
     */
    public function getNextOrderNum(string $academicYear): int
    {
        $maxOrder = $this->find()
            ->where(['academic_year' => $academicYear])
            ->select(['max_order' => $this->find()->func()->max('order_num')])
            ->first();

        return ($maxOrder->max_order ?? 0) + 1;
    }
}
