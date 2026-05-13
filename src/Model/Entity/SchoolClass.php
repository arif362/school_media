<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

class SchoolClass extends Entity
{
    protected array $_accessible = [
        'name' => true,
        'section' => true,
        'grade_level' => true,
        'academic_year' => true,
        'class_teacher_id' => true,
        'capacity' => true,
        'room_number' => true,
        'is_active' => true,
        'created' => true,
        'modified' => true,
    ];

    // Cambridge curriculum grade levels
    public const GRADE_LEVELS = [
        'Play Group' => 'Play Group',
        'Nursery' => 'Nursery',
        'KG' => 'KG (Kindergarten)',
        'Grade 1' => 'Grade 1',
        'Grade 2' => 'Grade 2',
        'Grade 3' => 'Grade 3',
        'Grade 4' => 'Grade 4',
        'Grade 5' => 'Grade 5',
        'Grade 6' => 'Grade 6',
        'Grade 7' => 'Grade 7',
        'Grade 8' => 'Grade 8',
        'Grade 9' => 'Grade 9 (O Level Year 1)',
        'Grade 10' => 'Grade 10 (O Level Year 2)',
    ];

    public const SECTIONS = [
        'A' => 'Section A',
        'B' => 'Section B',
        'C' => 'Section C',
        'D' => 'Section D',
    ];

    protected function _getDisplayName(): string
    {
        $name = $this->name;
        if ($this->section) {
            $name .= ' - ' . $this->section;
        }
        return $name;
    }
}
