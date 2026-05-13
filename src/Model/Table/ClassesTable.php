<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class ClassesTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('classes');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');
        $this->setEntityClass(\App\Model\Entity\SchoolClass::class);

        $this->addBehavior('Timestamp');

        $this->belongsTo('ClassTeachers', [
            'className' => 'Users',
            'foreignKey' => 'class_teacher_id',
            'propertyName' => 'class_teacher',
        ]);

        $this->hasMany('StudentClasses', [
            'foreignKey' => 'class_id',
            'dependent' => true,
        ]);

        $this->hasMany('Attendances', [
            'foreignKey' => 'class_id',
            'dependent' => true,
        ]);

        $this->belongsToMany('Students', [
            'className' => 'Users',
            'through' => 'StudentClasses',
            'foreignKey' => 'class_id',
            'targetForeignKey' => 'student_id',
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
            ->scalar('section')
            ->maxLength('section', 10)
            ->allowEmptyString('section');

        $validator
            ->scalar('grade_level')
            ->maxLength('grade_level', 50)
            ->requirePresence('grade_level', 'create')
            ->notEmptyString('grade_level');

        $validator
            ->scalar('academic_year')
            ->maxLength('academic_year', 20)
            ->requirePresence('academic_year', 'create')
            ->notEmptyString('academic_year');

        $validator
            ->integer('capacity')
            ->allowEmptyString('capacity');

        $validator
            ->boolean('is_active');

        return $validator;
    }

    public function findActive(SelectQuery $query): SelectQuery
    {
        return $query->where(['Classes.is_active' => true]);
    }

    public function findByAcademicYear(SelectQuery $query, string $year): SelectQuery
    {
        return $query->where(['Classes.academic_year' => $year]);
    }
}
