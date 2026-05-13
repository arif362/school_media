<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class CreateAttendance extends AbstractMigration
{
    public function change(): void
    {
        // Classes/Sections table for Cambridge curriculum
        $classes = $this->table('classes');
        $classes
            ->addColumn('name', 'string', ['limit' => 100, 'null' => false])
            ->addColumn('section', 'string', ['limit' => 10, 'null' => true])
            ->addColumn('grade_level', 'string', ['limit' => 50, 'null' => false]) // Play Group, Nursery, KG, Grade 1-10, O Level
            ->addColumn('academic_year', 'string', ['limit' => 20, 'null' => false])
            ->addColumn('class_teacher_id', 'integer', ['null' => true])
            ->addColumn('capacity', 'integer', ['default' => 30])
            ->addColumn('room_number', 'string', ['limit' => 20, 'null' => true])
            ->addColumn('is_active', 'boolean', ['default' => true])
            ->addColumn('created', 'datetime', ['null' => false])
            ->addColumn('modified', 'datetime', ['null' => false])
            ->addIndex(['grade_level'])
            ->addIndex(['academic_year'])
            ->addIndex(['is_active'])
            ->create();

        // Student-Class enrollment
        $studentClasses = $this->table('student_classes');
        $studentClasses
            ->addColumn('student_id', 'integer', ['null' => false])
            ->addColumn('class_id', 'integer', ['null' => false])
            ->addColumn('roll_number', 'string', ['limit' => 20, 'null' => true])
            ->addColumn('enrolled_date', 'date', ['null' => false])
            ->addColumn('status', 'string', ['limit' => 20, 'default' => 'active']) // active, transferred, withdrawn
            ->addColumn('created', 'datetime', ['null' => false])
            ->addColumn('modified', 'datetime', ['null' => false])
            ->addForeignKey('student_id', 'users', 'id', ['delete' => 'CASCADE'])
            ->addForeignKey('class_id', 'classes', 'id', ['delete' => 'CASCADE'])
            ->addIndex(['student_id', 'class_id'], ['unique' => true])
            ->addIndex(['status'])
            ->create();

        // Attendance records
        $attendances = $this->table('attendances');
        $attendances
            ->addColumn('student_id', 'integer', ['null' => false])
            ->addColumn('class_id', 'integer', ['null' => false])
            ->addColumn('date', 'date', ['null' => false])
            ->addColumn('status', 'string', ['limit' => 20, 'null' => false]) // present, absent, late, excused, half_day
            ->addColumn('check_in_time', 'time', ['null' => true])
            ->addColumn('check_out_time', 'time', ['null' => true])
            ->addColumn('remarks', 'text', ['null' => true])
            ->addColumn('marked_by', 'integer', ['null' => true])
            ->addColumn('created', 'datetime', ['null' => false])
            ->addColumn('modified', 'datetime', ['null' => false])
            ->addForeignKey('student_id', 'users', 'id', ['delete' => 'CASCADE'])
            ->addForeignKey('class_id', 'classes', 'id', ['delete' => 'CASCADE'])
            ->addForeignKey('marked_by', 'users', 'id', ['delete' => 'SET_NULL'])
            ->addIndex(['student_id', 'date'], ['unique' => true])
            ->addIndex(['class_id', 'date'])
            ->addIndex(['status'])
            ->addIndex(['date'])
            ->create();

        // Add class_id to users table for student's current class
        $users = $this->table('users');
        $users
            ->addColumn('class_id', 'integer', ['null' => true, 'after' => 'grade_level'])
            ->addColumn('admission_number', 'string', ['limit' => 50, 'null' => true, 'after' => 'class_id'])
            ->addColumn('admission_date', 'date', ['null' => true, 'after' => 'admission_number'])
            ->update();
    }
}
