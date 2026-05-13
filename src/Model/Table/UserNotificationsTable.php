<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class UserNotificationsTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('user_notifications');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp', [
            'events' => [
                'Model.beforeSave' => [
                    'created' => 'new',
                ],
            ],
        ]);

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER',
        ]);

        $this->belongsTo('Notifications', [
            'foreignKey' => 'notification_id',
            'joinType' => 'INNER',
        ]);
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('user_id')
            ->requirePresence('user_id', 'create')
            ->notEmptyString('user_id');

        $validator
            ->integer('notification_id')
            ->requirePresence('notification_id', 'create')
            ->notEmptyString('notification_id');

        $validator
            ->boolean('is_read');

        $validator
            ->dateTime('read_at')
            ->allowEmptyDateTime('read_at');

        return $validator;
    }

    public function findUnreadForUser(SelectQuery $query, int $userId): SelectQuery
    {
        return $query
            ->where([
                'UserNotifications.user_id' => $userId,
                'UserNotifications.is_read' => false,
            ]);
    }

    public function getUnreadCount(int $userId, ?string $userRole = null): int
    {
        $notificationsTable = $this->Notifications;

        // Get all applicable notification IDs for user
        $applicableNotifications = $notificationsTable->find()
            ->select(['id'])
            ->where([
                'Notifications.is_active' => true,
                'OR' => [
                    ['Notifications.target_user_id IS' => null, 'Notifications.target_role IS' => null],
                    ['Notifications.target_user_id IS' => null, 'Notifications.target_role' => $userRole],
                    ['Notifications.target_user_id' => $userId],
                ],
            ])
            ->all()
            ->extract('id')
            ->toArray();

        if (empty($applicableNotifications)) {
            return 0;
        }

        // Count read notifications
        $readCount = $this->find()
            ->where([
                'user_id' => $userId,
                'notification_id IN' => $applicableNotifications,
                'is_read' => true,
            ])
            ->count();

        return count($applicableNotifications) - $readCount;
    }

    public function markAsRead(int $userId, int $notificationId): bool
    {
        $existing = $this->find()
            ->where([
                'user_id' => $userId,
                'notification_id' => $notificationId,
            ])
            ->first();

        if ($existing) {
            if (!$existing->is_read) {
                $existing->is_read = true;
                $existing->read_at = new \Cake\I18n\DateTime();
                return (bool)$this->save($existing);
            }
            return true;
        }

        $userNotification = $this->newEntity([
            'user_id' => $userId,
            'notification_id' => $notificationId,
            'is_read' => true,
            'read_at' => new \Cake\I18n\DateTime(),
        ]);

        return (bool)$this->save($userNotification);
    }

    public function markAllAsRead(int $userId, ?string $userRole = null): int
    {
        $notificationsTable = $this->Notifications;

        $applicableNotifications = $notificationsTable->find()
            ->select(['id'])
            ->where([
                'Notifications.is_active' => true,
                'OR' => [
                    ['Notifications.target_user_id IS' => null, 'Notifications.target_role IS' => null],
                    ['Notifications.target_user_id IS' => null, 'Notifications.target_role' => $userRole],
                    ['Notifications.target_user_id' => $userId],
                ],
            ])
            ->all()
            ->extract('id')
            ->toArray();

        $count = 0;
        foreach ($applicableNotifications as $notificationId) {
            if ($this->markAsRead($userId, $notificationId)) {
                $count++;
            }
        }

        return $count;
    }

    public function isRead(int $userId, int $notificationId): bool
    {
        return $this->exists([
            'user_id' => $userId,
            'notification_id' => $notificationId,
            'is_read' => true,
        ]);
    }
}
