<?php
declare(strict_types=1);

use Migrations\AbstractSeed;

class ClassesSeed extends AbstractSeed
{
    public function run(): void
    {
        $academicYear = date('Y') . '-' . (date('Y') + 1);

        $classes = [
            // Early Years
            [
                'name' => 'Play Group',
                'section' => 'A',
                'grade_level' => 'Play Group',
                'academic_year' => $academicYear,
                'class_teacher_id' => 2, // Assuming ID 2 is a teacher
                'capacity' => 20,
                'room_number' => 'EY-01',
                'is_active' => true,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Nursery',
                'section' => 'A',
                'grade_level' => 'Nursery',
                'academic_year' => $academicYear,
                'class_teacher_id' => 2,
                'capacity' => 25,
                'room_number' => 'EY-02',
                'is_active' => true,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'KG',
                'section' => 'A',
                'grade_level' => 'KG',
                'academic_year' => $academicYear,
                'class_teacher_id' => 2,
                'capacity' => 25,
                'room_number' => 'EY-03',
                'is_active' => true,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
            ],
            // Primary
            [
                'name' => 'Grade 1',
                'section' => 'A',
                'grade_level' => 'Grade 1',
                'academic_year' => $academicYear,
                'class_teacher_id' => 2,
                'capacity' => 30,
                'room_number' => 'P-01',
                'is_active' => true,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Grade 2',
                'section' => 'A',
                'grade_level' => 'Grade 2',
                'academic_year' => $academicYear,
                'class_teacher_id' => 2,
                'capacity' => 30,
                'room_number' => 'P-02',
                'is_active' => true,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Grade 3',
                'section' => 'A',
                'grade_level' => 'Grade 3',
                'academic_year' => $academicYear,
                'class_teacher_id' => 2,
                'capacity' => 30,
                'room_number' => 'P-03',
                'is_active' => true,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
            ],
            // Middle School
            [
                'name' => 'Grade 5',
                'section' => 'A',
                'grade_level' => 'Grade 5',
                'academic_year' => $academicYear,
                'class_teacher_id' => 2,
                'capacity' => 30,
                'room_number' => 'M-01',
                'is_active' => true,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
            ],
            // O Level
            [
                'name' => 'O Level Year 1',
                'section' => 'A',
                'grade_level' => 'Grade 9 (O Level Year 1)',
                'academic_year' => $academicYear,
                'class_teacher_id' => 2,
                'capacity' => 25,
                'room_number' => 'O-01',
                'is_active' => true,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'O Level Year 2',
                'section' => 'A',
                'grade_level' => 'Grade 10 (O Level Year 2)',
                'academic_year' => $academicYear,
                'class_teacher_id' => 2,
                'capacity' => 25,
                'room_number' => 'O-02',
                'is_active' => true,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
            ],
        ];

        $table = $this->table('classes');
        $table->insert($classes)->save();
    }
}
