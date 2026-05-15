<?php
/**
 * RoutineTemplateItems Table
 *
 * Manages individual subject allocations within routine templates.
 *
 * @created 2026-05-15
 * @author Arif
 */
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class RoutineTemplateItemsTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('routine_template_items');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('RoutineTemplates', [
            'foreignKey' => 'routine_template_id',
            'joinType' => 'INNER',
        ]);

        $this->belongsTo('Subjects', [
            'foreignKey' => 'subject_id',
            'joinType' => 'INNER',
        ]);
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('routine_template_id')
            ->requirePresence('routine_template_id', 'create')
            ->notEmptyString('routine_template_id');

        $validator
            ->integer('subject_id')
            ->requirePresence('subject_id', 'create')
            ->notEmptyString('subject_id');

        $validator
            ->integer('periods_per_week')
            ->requirePresence('periods_per_week', 'create')
            ->notEmptyString('periods_per_week')
            ->range('periods_per_week', [1, 20], __('Periods per week must be between 1 and 20'));

        $validator
            ->boolean('is_required');

        return $validator;
    }

    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->existsIn(['routine_template_id'], 'RoutineTemplates'), ['errorField' => 'routine_template_id']);
        $rules->add($rules->existsIn(['subject_id'], 'Subjects'), ['errorField' => 'subject_id']);

        // Ensure unique subject per template
        $rules->add($rules->isUnique(['routine_template_id', 'subject_id'], __('This subject is already in the template')));

        return $rules;
    }

    /**
     * Find items by template
     */
    public function findByTemplate(SelectQuery $query, int $templateId): SelectQuery
    {
        return $query
            ->where(['RoutineTemplateItems.routine_template_id' => $templateId])
            ->contain(['Subjects'])
            ->orderBy(['Subjects.name' => 'ASC']);
    }

    /**
     * Find required items only
     */
    public function findRequired(SelectQuery $query): SelectQuery
    {
        return $query->where(['RoutineTemplateItems.is_required' => true]);
    }

    /**
     * Get total periods for a template
     */
    public function getTotalPeriods(int $templateId): int
    {
        $result = $this->find()
            ->where(['routine_template_id' => $templateId])
            ->select(['total' => $this->find()->func()->sum('periods_per_week')])
            ->first();

        return (int) ($result->total ?? 0);
    }
}
