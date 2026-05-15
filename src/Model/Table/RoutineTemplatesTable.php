<?php
/**
 * RoutineTemplates Table
 *
 * Manages Cambridge curriculum routine templates.
 *
 * @created 2026-05-15
 * @author Arif
 */
declare(strict_types=1);

namespace App\Model\Table;

use App\Model\Entity\RoutineTemplate;
use Cake\ORM\Query\SelectQuery;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class RoutineTemplatesTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('routine_templates');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasMany('RoutineTemplateItems', [
            'foreignKey' => 'routine_template_id',
            'dependent' => true,
            'cascadeCallbacks' => true,
        ]);
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->scalar('name')
            ->maxLength('name', 100)
            ->requirePresence('name', 'create')
            ->notEmptyString('name');

        $validator
            ->scalar('cambridge_stage')
            ->maxLength('cambridge_stage', 30)
            ->requirePresence('cambridge_stage', 'create')
            ->notEmptyString('cambridge_stage')
            ->inList('cambridge_stage', array_keys(RoutineTemplate::getCambridgeStages()), __('Invalid Cambridge stage'));

        $validator
            ->scalar('description')
            ->allowEmptyString('description');

        $validator
            ->boolean('is_active');

        return $validator;
    }

    /**
     * Find active templates
     */
    public function findActive(SelectQuery $query): SelectQuery
    {
        return $query->where(['RoutineTemplates.is_active' => true]);
    }

    /**
     * Find templates by Cambridge stage
     */
    public function findByStage(SelectQuery $query, string $stage): SelectQuery
    {
        return $query->where(['RoutineTemplates.cambridge_stage' => $stage]);
    }

    /**
     * Find templates with their items
     */
    public function findWithItems(SelectQuery $query): SelectQuery
    {
        return $query->contain([
            'RoutineTemplateItems' => [
                'Subjects',
            ],
        ]);
    }

    /**
     * Get templates for a specific grade level
     */
    public function getTemplatesForGradeLevel(string $gradeLevel): array
    {
        $stage = RoutineTemplate::getStageForGradeLevel($gradeLevel);

        if (!$stage) {
            return [];
        }

        return $this->find()
            ->find('active')
            ->find('byStage', stage: $stage)
            ->find('withItems')
            ->orderBy(['RoutineTemplates.name' => 'ASC'])
            ->all()
            ->toArray();
    }

    /**
     * Get all templates grouped by stage
     */
    public function getAllGroupedByStage(): array
    {
        $templates = $this->find()
            ->find('active')
            ->find('withItems')
            ->orderBy(['RoutineTemplates.cambridge_stage' => 'ASC', 'RoutineTemplates.name' => 'ASC'])
            ->all();

        $grouped = [];
        foreach ($templates as $template) {
            $grouped[$template->cambridge_stage][] = $template;
        }

        return $grouped;
    }
}
