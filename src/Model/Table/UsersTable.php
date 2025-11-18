<?php
declare(strict_types=1);

namespace App\Model\Table;

use Authentication\PasswordHasher\DefaultPasswordHasher;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class UsersTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('users');
        $this->setPrimaryKey('id');
        $this->addBehavior('Timestamp');
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->scalar('name')
            ->maxLength('name', 120)
            ->requirePresence('name', 'create')
            ->notEmptyString('name');

        $validator
            ->email('email')
            ->requirePresence('email', 'create')
            ->notEmptyString('email');

        $validator
            ->scalar('password')
            ->minLength('password', 8)
            ->requirePresence('password', 'create')
            ->notEmptyString('password');

        $validator
            ->scalar('role')
            ->inList('role', ['student', 'teacher', 'admin'])
            ->requirePresence('role', 'create')
            ->notEmptyString('role');

        return $validator;
    }

    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->isUnique(['email']), ['errorField' => 'email']);

        return $rules;
    }

    public function beforeSave($event, $entity, $options)
    {
        if ($entity->isDirty('password')) {
            $entity->password = (new DefaultPasswordHasher())->hash((string)$entity->password);
        }
    }
}

