<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class CourseMaterialsTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('course_materials');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Courses', [
            'foreignKey' => 'course_id',
            'joinType' => 'INNER',
        ]);

        $this->belongsTo('Uploaders', [
            'className' => 'Users',
            'foreignKey' => 'uploaded_by',
            'propertyName' => 'uploader',
        ]);
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('course_id')
            ->requirePresence('course_id', 'create')
            ->notEmptyString('course_id');

        $validator
            ->scalar('title')
            ->maxLength('title', 200)
            ->requirePresence('title', 'create')
            ->notEmptyString('title');

        $validator
            ->scalar('type')
            ->maxLength('type', 50)
            ->requirePresence('type', 'create')
            ->notEmptyString('type')
            ->inList('type', ['document', 'video', 'link', 'assignment', 'notes']);

        $validator
            ->scalar('file_path')
            ->maxLength('file_path', 255)
            ->allowEmptyString('file_path');

        $validator
            ->scalar('external_url')
            ->maxLength('external_url', 500)
            ->allowEmptyString('external_url');

        $validator
            ->boolean('is_visible');

        $validator
            ->integer('order_num')
            ->allowEmptyString('order_num');

        return $validator;
    }

    public function findVisible(SelectQuery $query): SelectQuery
    {
        return $query
            ->where(['CourseMaterials.is_visible' => true])
            ->orderBy(['CourseMaterials.order_num' => 'ASC']);
    }

    public function findByCourse(SelectQuery $query, int $courseId): SelectQuery
    {
        return $query->where(['CourseMaterials.course_id' => $courseId]);
    }

    public function findByType(SelectQuery $query, string $type): SelectQuery
    {
        return $query->where(['CourseMaterials.type' => $type]);
    }
}
