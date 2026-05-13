<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class StudentClassesTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('student_classes');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Students', [
            'className' => 'Users',
            'foreignKey' => 'student_id',
            'joinType' => 'INNER',
        ]);

        $this->belongsTo('Classes', [
            'foreignKey' => 'class_id',
            'joinType' => 'INNER',
        ]);
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('student_id')
            ->requirePresence('student_id', 'create')
            ->notEmptyString('student_id');

        $validator
            ->integer('class_id')
            ->requirePresence('class_id', 'create')
            ->notEmptyString('class_id');

        $validator
            ->scalar('roll_number')
            ->maxLength('roll_number', 20)
            ->allowEmptyString('roll_number');

        $validator
            ->date('enrolled_date')
            ->requirePresence('enrolled_date', 'create')
            ->notEmptyDate('enrolled_date');

        $validator
            ->scalar('status')
            ->maxLength('status', 20)
            ->inList('status', ['active', 'transferred', 'withdrawn']);

        return $validator;
    }

    public function findActive(SelectQuery $query): SelectQuery
    {
        return $query->where(['StudentClasses.status' => 'active']);
    }
}
