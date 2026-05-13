<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class NotificationsTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('notifications');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Users', [
            'foreignKey' => 'created_by',
            'propertyName' => 'creator',
        ]);

        $this->belongsTo('TargetUsers', [
            'className' => 'Users',
            'foreignKey' => 'target_user_id',
            'propertyName' => 'target_user',
        ]);

        $this->hasMany('UserNotifications', [
            'foreignKey' => 'notification_id',
            'dependent' => true,
        ]);
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->scalar('title')
            ->maxLength('title', 255)
            ->requirePresence('title', 'create')
            ->notEmptyString('title');

        $validator
            ->scalar('message')
            ->requirePresence('message', 'create')
            ->notEmptyString('message');

        $validator
            ->scalar('type')
            ->maxLength('type', 50)
            ->inList('type', ['info', 'success', 'warning', 'danger']);

        $validator
            ->scalar('target_role')
            ->maxLength('target_role', 50)
            ->allowEmptyString('target_role');

        $validator
            ->integer('target_user_id')
            ->allowEmptyString('target_user_id');

        $validator
            ->scalar('link')
            ->maxLength('link', 500)
            ->allowEmptyString('link');

        $validator
            ->boolean('is_active');

        return $validator;
    }

    public function findForUser(SelectQuery $query, int $userId, ?string $userRole = null): SelectQuery
    {
        return $query
            ->where([
                'Notifications.is_active' => true,
                'OR' => [
                    ['Notifications.target_user_id IS' => null, 'Notifications.target_role IS' => null],
                    ['Notifications.target_user_id IS' => null, 'Notifications.target_role' => $userRole],
                    ['Notifications.target_user_id' => $userId],
                ],
            ])
            ->orderByDesc('Notifications.created');
    }

    public function findActive(SelectQuery $query): SelectQuery
    {
        return $query->where(['Notifications.is_active' => true]);
    }
}
