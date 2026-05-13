<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class Updated extends BaseMigration
{
    /**
     * Up Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-up-method
     *
     * @return void
     */
    public function up(): void
    {
        $this->table('attendances')
            ->addColumn('student_id', 'integer', [
                'default' => null,
                'limit' => null,
                'null' => false,
                'signed' => true,
            ])
            ->addColumn('class_id', 'integer', [
                'default' => null,
                'limit' => null,
                'null' => false,
                'signed' => true,
            ])
            ->addColumn('date', 'date', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('status', 'string', [
                'default' => null,
                'limit' => 20,
                'null' => false,
            ])
            ->addColumn('check_in_time', 'time', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('check_out_time', 'time', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('remarks', 'text', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('marked_by', 'integer', [
                'default' => null,
                'limit' => null,
                'null' => true,
                'signed' => true,
            ])
            ->addColumn('created', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('modified', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addIndex(
                $this->index([
                        'student_id',
                        'date',
                    ])
                    ->setName('student_id')
                    ->setType('unique')
            )
            ->addIndex(
                $this->index([
                        'class_id',
                        'date',
                    ])
                    ->setName('class_id')
            )
            ->addIndex(
                $this->index('status')
                    ->setName('status')
            )
            ->addIndex(
                $this->index('date')
                    ->setName('date')
            )
            ->addIndex(
                $this->index('marked_by')
                    ->setName('marked_by')
            )
            ->create();

        $this->table('classes')
            ->addColumn('name', 'string', [
                'default' => null,
                'limit' => 100,
                'null' => false,
            ])
            ->addColumn('section', 'string', [
                'default' => null,
                'limit' => 10,
                'null' => true,
            ])
            ->addColumn('grade_level', 'string', [
                'default' => null,
                'limit' => 50,
                'null' => false,
            ])
            ->addColumn('academic_year', 'string', [
                'default' => null,
                'limit' => 20,
                'null' => false,
            ])
            ->addColumn('class_teacher_id', 'integer', [
                'default' => null,
                'limit' => null,
                'null' => true,
                'signed' => true,
            ])
            ->addColumn('capacity', 'integer', [
                'default' => '30',
                'limit' => null,
                'null' => false,
                'signed' => true,
            ])
            ->addColumn('room_number', 'string', [
                'default' => null,
                'limit' => 20,
                'null' => true,
            ])
            ->addColumn('is_active', 'boolean', [
                'default' => true,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('created', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('modified', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addIndex(
                $this->index('grade_level')
                    ->setName('grade_level')
            )
            ->addIndex(
                $this->index('academic_year')
                    ->setName('academic_year')
            )
            ->addIndex(
                $this->index('is_active')
                    ->setName('is_active')
            )
            ->create();

        $this->table('notifications')
            ->addColumn('title', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('message', 'text', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('type', 'string', [
                'default' => 'info',
                'limit' => 50,
                'null' => false,
            ])
            ->addColumn('target_role', 'string', [
                'default' => null,
                'limit' => 50,
                'null' => true,
            ])
            ->addColumn('target_user_id', 'integer', [
                'default' => null,
                'limit' => null,
                'null' => true,
                'signed' => true,
            ])
            ->addColumn('link', 'string', [
                'default' => null,
                'limit' => 500,
                'null' => true,
            ])
            ->addColumn('is_active', 'boolean', [
                'default' => true,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('created_by', 'integer', [
                'default' => null,
                'limit' => null,
                'null' => true,
                'signed' => true,
            ])
            ->addColumn('created', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('modified', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addIndex(
                $this->index('target_role')
                    ->setName('target_role')
            )
            ->addIndex(
                $this->index('target_user_id')
                    ->setName('target_user_id')
            )
            ->addIndex(
                $this->index('is_active')
                    ->setName('is_active')
            )
            ->addIndex(
                $this->index('created')
                    ->setName('created')
            )
            ->create();

        $this->table('posts')
            ->addColumn('title', 'string', [
                'default' => null,
                'limit' => 200,
                'null' => false,
            ])
            ->addColumn('slug', 'string', [
                'default' => null,
                'limit' => 200,
                'null' => false,
            ])
            ->addColumn('body', 'text', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('published', 'boolean', [
                'default' => false,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('created', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('modified', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addIndex(
                $this->index('slug')
                    ->setName('slug')
                    ->setType('unique')
            )
            ->create();

        $this->table('student_classes')
            ->addColumn('student_id', 'integer', [
                'default' => null,
                'limit' => null,
                'null' => false,
                'signed' => true,
            ])
            ->addColumn('class_id', 'integer', [
                'default' => null,
                'limit' => null,
                'null' => false,
                'signed' => true,
            ])
            ->addColumn('roll_number', 'string', [
                'default' => null,
                'limit' => 20,
                'null' => true,
            ])
            ->addColumn('enrolled_date', 'date', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('status', 'string', [
                'default' => 'active',
                'limit' => 20,
                'null' => false,
            ])
            ->addColumn('created', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('modified', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addIndex(
                $this->index([
                        'student_id',
                        'class_id',
                    ])
                    ->setName('student_id')
                    ->setType('unique')
            )
            ->addIndex(
                $this->index('status')
                    ->setName('status')
            )
            ->addIndex(
                $this->index('class_id')
                    ->setName('class_id')
            )
            ->create();

        $this->table('user_notifications')
            ->addColumn('user_id', 'integer', [
                'default' => null,
                'limit' => null,
                'null' => false,
                'signed' => true,
            ])
            ->addColumn('notification_id', 'integer', [
                'default' => null,
                'limit' => null,
                'null' => false,
                'signed' => true,
            ])
            ->addColumn('is_read', 'boolean', [
                'default' => false,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('read_at', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('created', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addIndex(
                $this->index([
                        'user_id',
                        'notification_id',
                    ])
                    ->setName('user_id')
                    ->setType('unique')
            )
            ->addIndex(
                $this->index([
                        'user_id',
                        'is_read',
                    ])
                    ->setName('user_id_2')
            )
            ->addIndex(
                $this->index('notification_id')
                    ->setName('notification_id')
            )
            ->create();

        $this->table('users')
            ->addColumn('name', 'string', [
                'default' => null,
                'limit' => 120,
                'null' => false,
            ])
            ->addColumn('email', 'string', [
                'default' => null,
                'limit' => 180,
                'null' => false,
            ])
            ->addColumn('password', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('role', 'string', [
                'default' => 'student',
                'limit' => 20,
                'null' => false,
            ])
            ->addColumn('active', 'boolean', [
                'default' => true,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('created', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('modified', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('bio', 'text', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('avatar', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('phone', 'string', [
                'default' => null,
                'limit' => 20,
                'null' => true,
            ])
            ->addColumn('address', 'text', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('date_of_birth', 'date', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('grade_level', 'string', [
                'default' => null,
                'limit' => 50,
                'null' => true,
            ])
            ->addColumn('class_id', 'integer', [
                'default' => null,
                'limit' => null,
                'null' => true,
                'signed' => true,
            ])
            ->addColumn('admission_number', 'string', [
                'default' => null,
                'limit' => 50,
                'null' => true,
            ])
            ->addColumn('admission_date', 'date', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addIndex(
                $this->index('email')
                    ->setName('email')
                    ->setType('unique')
            )
            ->create();

        $this->table('attendances')
            ->addForeignKey(
                $this->foreignKey('marked_by')
                    ->setReferencedTable('users')
                    ->setReferencedColumns('id')
                    ->setOnDelete('SET_NULL')
                    ->setOnUpdate('RESTRICT')
                    ->setName('attendances_ibfk_3')
            )
            ->addForeignKey(
                $this->foreignKey('class_id')
                    ->setReferencedTable('classes')
                    ->setReferencedColumns('id')
                    ->setOnDelete('CASCADE')
                    ->setOnUpdate('RESTRICT')
                    ->setName('attendances_ibfk_2')
            )
            ->addForeignKey(
                $this->foreignKey('student_id')
                    ->setReferencedTable('users')
                    ->setReferencedColumns('id')
                    ->setOnDelete('CASCADE')
                    ->setOnUpdate('RESTRICT')
                    ->setName('attendances_ibfk_1')
            )
            ->update();

        $this->table('student_classes')
            ->addForeignKey(
                $this->foreignKey('class_id')
                    ->setReferencedTable('classes')
                    ->setReferencedColumns('id')
                    ->setOnDelete('CASCADE')
                    ->setOnUpdate('RESTRICT')
                    ->setName('student_classes_ibfk_2')
            )
            ->addForeignKey(
                $this->foreignKey('student_id')
                    ->setReferencedTable('users')
                    ->setReferencedColumns('id')
                    ->setOnDelete('CASCADE')
                    ->setOnUpdate('RESTRICT')
                    ->setName('student_classes_ibfk_1')
            )
            ->update();

        $this->table('user_notifications')
            ->addForeignKey(
                $this->foreignKey('notification_id')
                    ->setReferencedTable('notifications')
                    ->setReferencedColumns('id')
                    ->setOnDelete('CASCADE')
                    ->setOnUpdate('RESTRICT')
                    ->setName('user_notifications_ibfk_2')
            )
            ->addForeignKey(
                $this->foreignKey('user_id')
                    ->setReferencedTable('users')
                    ->setReferencedColumns('id')
                    ->setOnDelete('CASCADE')
                    ->setOnUpdate('RESTRICT')
                    ->setName('user_notifications_ibfk_1')
            )
            ->update();
    }

    /**
     * Down Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-down-method
     *
     * @return void
     */
    public function down(): void
    {
        $this->table('attendances')
            ->dropForeignKey(
                'marked_by'
            )
            ->dropForeignKey(
                'class_id'
            )
            ->dropForeignKey(
                'student_id'
            )->save();

        $this->table('student_classes')
            ->dropForeignKey(
                'class_id'
            )
            ->dropForeignKey(
                'student_id'
            )->save();

        $this->table('user_notifications')
            ->dropForeignKey(
                'notification_id'
            )
            ->dropForeignKey(
                'user_id'
            )->save();

        $this->table('attendances')->drop()->save();
        $this->table('classes')->drop()->save();
        $this->table('notifications')->drop()->save();
        $this->table('posts')->drop()->save();
        $this->table('student_classes')->drop()->save();
        $this->table('user_notifications')->drop()->save();
        $this->table('users')->drop()->save();
    }
}
