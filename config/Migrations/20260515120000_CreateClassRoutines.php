<?php
/**
 * Migration: CreateClassRoutines
 *
 * Creates tables for class routine/timetable management with Cambridge curriculum support.
 *
 * @created 2026-05-15
 * @author Arif
 */
declare(strict_types=1);

use Migrations\AbstractMigration;

class CreateClassRoutines extends AbstractMigration
{
    public function change(): void
    {
        // Periods table - daily time slot definitions
        $periods = $this->table('periods');
        $periods
            ->addColumn('academic_year', 'string', ['limit' => 20, 'null' => false])
            ->addColumn('name', 'string', ['limit' => 50, 'null' => false])
            ->addColumn('start_time', 'time', ['null' => false])
            ->addColumn('end_time', 'time', ['null' => false])
            ->addColumn('is_break', 'boolean', ['default' => false])
            ->addColumn('order_num', 'integer', ['default' => 0])
            ->addColumn('is_active', 'boolean', ['default' => true])
            ->addColumn('created', 'datetime', ['null' => false])
            ->addColumn('modified', 'datetime', ['null' => false])
            ->addIndex(['academic_year'])
            ->addIndex(['order_num'])
            ->addIndex(['is_active'])
            ->create();

        // Class routines table - weekly timetable entries
        $classRoutines = $this->table('class_routines');
        $classRoutines
            ->addColumn('class_id', 'integer', ['null' => false])
            ->addColumn('period_id', 'integer', ['null' => false])
            ->addColumn('day_of_week', 'integer', ['null' => false]) // 1=Monday, 5=Friday
            ->addColumn('subject_id', 'integer', ['null' => true])
            ->addColumn('teacher_id', 'integer', ['null' => true])
            ->addColumn('room', 'string', ['limit' => 50, 'null' => true])
            ->addColumn('notes', 'text', ['null' => true])
            ->addColumn('academic_year', 'string', ['limit' => 20, 'null' => false])
            ->addColumn('is_active', 'boolean', ['default' => true])
            ->addColumn('created', 'datetime', ['null' => false])
            ->addColumn('modified', 'datetime', ['null' => false])
            ->addForeignKey('class_id', 'classes', 'id', ['delete' => 'CASCADE'])
            ->addForeignKey('period_id', 'periods', 'id', ['delete' => 'CASCADE'])
            ->addForeignKey('subject_id', 'subjects', 'id', ['delete' => 'SET_NULL'])
            ->addForeignKey('teacher_id', 'users', 'id', ['delete' => 'SET_NULL'])
            ->addIndex(['class_id', 'period_id', 'day_of_week', 'academic_year'], ['unique' => true])
            ->addIndex(['teacher_id'])
            ->addIndex(['academic_year'])
            ->addIndex(['is_active'])
            ->create();

        // Academic events table - calendar events (terms, holidays, exams)
        $academicEvents = $this->table('academic_events');
        $academicEvents
            ->addColumn('academic_year', 'string', ['limit' => 20, 'null' => false])
            ->addColumn('title', 'string', ['limit' => 150, 'null' => false])
            ->addColumn('event_type', 'string', ['limit' => 30, 'null' => false]) // term_start, term_end, holiday, exam, other
            ->addColumn('start_date', 'date', ['null' => false])
            ->addColumn('end_date', 'date', ['null' => true])
            ->addColumn('description', 'text', ['null' => true])
            ->addColumn('is_active', 'boolean', ['default' => true])
            ->addColumn('created', 'datetime', ['null' => false])
            ->addColumn('modified', 'datetime', ['null' => false])
            ->addIndex(['academic_year'])
            ->addIndex(['event_type'])
            ->addIndex(['start_date'])
            ->addIndex(['is_active'])
            ->create();

        // Routine templates table - pre-built Cambridge curriculum templates
        $routineTemplates = $this->table('routine_templates');
        $routineTemplates
            ->addColumn('name', 'string', ['limit' => 100, 'null' => false])
            ->addColumn('cambridge_stage', 'string', ['limit' => 30, 'null' => false]) // primary, lower_secondary, igcse
            ->addColumn('description', 'text', ['null' => true])
            ->addColumn('is_active', 'boolean', ['default' => true])
            ->addColumn('created', 'datetime', ['null' => false])
            ->addColumn('modified', 'datetime', ['null' => false])
            ->addIndex(['cambridge_stage'])
            ->addIndex(['is_active'])
            ->create();

        // Routine template items table - subject-period allocations per template
        $routineTemplateItems = $this->table('routine_template_items');
        $routineTemplateItems
            ->addColumn('routine_template_id', 'integer', ['null' => false])
            ->addColumn('subject_id', 'integer', ['null' => false])
            ->addColumn('periods_per_week', 'integer', ['default' => 1])
            ->addColumn('is_required', 'boolean', ['default' => true])
            ->addColumn('created', 'datetime', ['null' => false])
            ->addColumn('modified', 'datetime', ['null' => false])
            ->addForeignKey('routine_template_id', 'routine_templates', 'id', ['delete' => 'CASCADE'])
            ->addForeignKey('subject_id', 'subjects', 'id', ['delete' => 'CASCADE'])
            ->addIndex(['routine_template_id', 'subject_id'], ['unique' => true])
            ->create();
    }
}
