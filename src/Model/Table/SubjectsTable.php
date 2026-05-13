<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class SubjectsTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('subjects');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasMany('Courses', [
            'foreignKey' => 'subject_id',
            'dependent' => true,
        ]);

        $this->hasMany('TeacherSubjects', [
            'foreignKey' => 'subject_id',
            'dependent' => true,
        ]);

        $this->belongsToMany('Teachers', [
            'className' => 'Users',
            'through' => 'TeacherSubjects',
            'foreignKey' => 'subject_id',
            'targetForeignKey' => 'teacher_id',
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
            ->scalar('code')
            ->maxLength('code', 20)
            ->requirePresence('code', 'create')
            ->notEmptyString('code')
            ->add('code', 'unique', [
                'rule' => 'validateUnique',
                'provider' => 'table',
                'message' => __('This subject code is already in use.'),
            ]);

        $validator
            ->scalar('category')
            ->maxLength('category', 50)
            ->allowEmptyString('category');

        $validator
            ->integer('credit_hours')
            ->allowEmptyString('credit_hours');

        $validator
            ->boolean('is_active');

        return $validator;
    }

    public function findActive(SelectQuery $query): SelectQuery
    {
        return $query->where(['Subjects.is_active' => true]);
    }

    public function findByCategory(SelectQuery $query, string $category): SelectQuery
    {
        return $query->where(['Subjects.category' => $category]);
    }
}
