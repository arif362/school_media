<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class CreateCourses extends AbstractMigration
{
    public function change(): void
    {
        // Subjects table - core subjects for Cambridge curriculum
        $subjects = $this->table('subjects');
        $subjects
            ->addColumn('name', 'string', ['limit' => 100, 'null' => false])
            ->addColumn('code', 'string', ['limit' => 20, 'null' => false])
            ->addColumn('description', 'text', ['null' => true])
            ->addColumn('category', 'string', ['limit' => 50, 'null' => true]) // Core, Elective, Co-curricular
            ->addColumn('credit_hours', 'integer', ['default' => 1])
            ->addColumn('is_active', 'boolean', ['default' => true])
            ->addColumn('created', 'datetime', ['null' => false])
            ->addColumn('modified', 'datetime', ['null' => false])
            ->addIndex(['code'], ['unique' => true])
            ->addIndex(['category'])
            ->addIndex(['is_active'])
            ->create();

        // Courses table - subject offerings for specific classes
        $courses = $this->table('courses');
        $courses
            ->addColumn('subject_id', 'integer', ['null' => false])
            ->addColumn('class_id', 'integer', ['null' => false])
            ->addColumn('teacher_id', 'integer', ['null' => true])
            ->addColumn('name', 'string', ['limit' => 150, 'null' => true]) // Optional custom name
            ->addColumn('academic_year', 'string', ['limit' => 20, 'null' => false])
            ->addColumn('term', 'string', ['limit' => 20, 'null' => true]) // Term 1, Term 2, Term 3
            ->addColumn('schedule', 'text', ['null' => true]) // JSON: days and times
            ->addColumn('syllabus', 'text', ['null' => true])
            ->addColumn('max_students', 'integer', ['default' => 40])
            ->addColumn('is_active', 'boolean', ['default' => true])
            ->addColumn('created', 'datetime', ['null' => false])
            ->addColumn('modified', 'datetime', ['null' => false])
            ->addForeignKey('subject_id', 'subjects', 'id', ['delete' => 'CASCADE'])
            ->addForeignKey('class_id', 'classes', 'id', ['delete' => 'CASCADE'])
            ->addForeignKey('teacher_id', 'users', 'id', ['delete' => 'SET_NULL'])
            ->addIndex(['subject_id', 'class_id', 'academic_year'], ['unique' => true])
            ->addIndex(['teacher_id'])
            ->addIndex(['academic_year'])
            ->addIndex(['is_active'])
            ->create();

        // Student course enrollments
        $studentCourses = $this->table('student_courses');
        $studentCourses
            ->addColumn('student_id', 'integer', ['null' => false])
            ->addColumn('course_id', 'integer', ['null' => false])
            ->addColumn('enrolled_date', 'date', ['null' => false])
            ->addColumn('status', 'string', ['limit' => 20, 'default' => 'enrolled']) // enrolled, completed, dropped, failed
            ->addColumn('grade', 'string', ['limit' => 10, 'null' => true]) // A*, A, B, C, D, E, U
            ->addColumn('marks', 'decimal', ['precision' => 5, 'scale' => 2, 'null' => true])
            ->addColumn('remarks', 'text', ['null' => true])
            ->addColumn('created', 'datetime', ['null' => false])
            ->addColumn('modified', 'datetime', ['null' => false])
            ->addForeignKey('student_id', 'users', 'id', ['delete' => 'CASCADE'])
            ->addForeignKey('course_id', 'courses', 'id', ['delete' => 'CASCADE'])
            ->addIndex(['student_id', 'course_id'], ['unique' => true])
            ->addIndex(['status'])
            ->create();

        // Teacher-Subject specializations
        $teacherSubjects = $this->table('teacher_subjects');
        $teacherSubjects
            ->addColumn('teacher_id', 'integer', ['null' => false])
            ->addColumn('subject_id', 'integer', ['null' => false])
            ->addColumn('is_primary', 'boolean', ['default' => false]) // Primary subject expertise
            ->addColumn('created', 'datetime', ['null' => false])
            ->addColumn('modified', 'datetime', ['null' => false])
            ->addForeignKey('teacher_id', 'users', 'id', ['delete' => 'CASCADE'])
            ->addForeignKey('subject_id', 'subjects', 'id', ['delete' => 'CASCADE'])
            ->addIndex(['teacher_id', 'subject_id'], ['unique' => true])
            ->create();

        // Course materials/resources
        $courseMaterials = $this->table('course_materials');
        $courseMaterials
            ->addColumn('course_id', 'integer', ['null' => false])
            ->addColumn('title', 'string', ['limit' => 200, 'null' => false])
            ->addColumn('description', 'text', ['null' => true])
            ->addColumn('type', 'string', ['limit' => 50, 'null' => false]) // document, video, link, assignment
            ->addColumn('file_path', 'string', ['limit' => 255, 'null' => true])
            ->addColumn('external_url', 'string', ['limit' => 500, 'null' => true])
            ->addColumn('uploaded_by', 'integer', ['null' => true])
            ->addColumn('is_visible', 'boolean', ['default' => true])
            ->addColumn('order_num', 'integer', ['default' => 0])
            ->addColumn('created', 'datetime', ['null' => false])
            ->addColumn('modified', 'datetime', ['null' => false])
            ->addForeignKey('course_id', 'courses', 'id', ['delete' => 'CASCADE'])
            ->addForeignKey('uploaded_by', 'users', 'id', ['delete' => 'SET_NULL'])
            ->addIndex(['course_id'])
            ->addIndex(['type'])
            ->create();
    }
}
