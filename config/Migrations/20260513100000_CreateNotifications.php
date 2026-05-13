<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class CreateNotifications extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('notifications');
        $table
            ->addColumn('title', 'string', ['limit' => 255, 'null' => false])
            ->addColumn('message', 'text', ['null' => false])
            ->addColumn('type', 'string', ['limit' => 50, 'default' => 'info']) // info, success, warning, danger
            ->addColumn('target_role', 'string', ['limit' => 50, 'null' => true]) // null = all, or specific role
            ->addColumn('target_user_id', 'integer', ['null' => true]) // null = broadcast, or specific user
            ->addColumn('link', 'string', ['limit' => 500, 'null' => true])
            ->addColumn('is_active', 'boolean', ['default' => true])
            ->addColumn('created_by', 'integer', ['null' => true])
            ->addColumn('created', 'datetime', ['null' => false])
            ->addColumn('modified', 'datetime', ['null' => false])
            ->addIndex(['target_role'])
            ->addIndex(['target_user_id'])
            ->addIndex(['is_active'])
            ->addIndex(['created'])
            ->create();

        // User notification read status tracking
        $userNotifications = $this->table('user_notifications');
        $userNotifications
            ->addColumn('user_id', 'integer', ['null' => false])
            ->addColumn('notification_id', 'integer', ['null' => false])
            ->addColumn('is_read', 'boolean', ['default' => false])
            ->addColumn('read_at', 'datetime', ['null' => true])
            ->addColumn('created', 'datetime', ['null' => false])
            ->addForeignKey('user_id', 'users', 'id', ['delete' => 'CASCADE'])
            ->addForeignKey('notification_id', 'notifications', 'id', ['delete' => 'CASCADE'])
            ->addIndex(['user_id', 'notification_id'], ['unique' => true])
            ->addIndex(['user_id', 'is_read'])
            ->create();
    }
}
