<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class TeacherSubjectsTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('teacher_subjects');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Teachers', [
            'className' => 'Users',
            'foreignKey' => 'teacher_id',
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
            ->integer('teacher_id')
            ->requirePresence('teacher_id', 'create')
            ->notEmptyString('teacher_id');

        $validator
            ->integer('subject_id')
            ->requirePresence('subject_id', 'create')
            ->notEmptyString('subject_id');

        $validator
            ->boolean('is_primary');

        return $validator;
    }

    public function findByTeacher(SelectQuery $query, int $teacherId): SelectQuery
    {
        return $query->where(['TeacherSubjects.teacher_id' => $teacherId]);
    }

    public function findPrimary(SelectQuery $query): SelectQuery
    {
        return $query->where(['TeacherSubjects.is_primary' => true]);
    }
}
