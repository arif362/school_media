<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

class Course extends Entity
{
    protected array $_accessible = [
        'subject_id' => true,
        'class_id' => true,
        'teacher_id' => true,
        'name' => true,
        'academic_year' => true,
        'term' => true,
        'schedule' => true,
        'syllabus' => true,
        'max_students' => true,
        'is_active' => true,
        'created' => true,
        'modified' => true,
        'subject' => true,
        'class' => true,
        'teacher' => true,
    ];

    // Terms
    public const TERM_1 = 'Term 1';
    public const TERM_2 = 'Term 2';
    public const TERM_3 = 'Term 3';
    public const FULL_YEAR = 'Full Year';

    public static function getTerms(): array
    {
        return [
            self::TERM_1 => __('Term 1 (Aug-Nov)'),
            self::TERM_2 => __('Term 2 (Dec-Mar)'),
            self::TERM_3 => __('Term 3 (Apr-Jul)'),
            self::FULL_YEAR => __('Full Year'),
        ];
    }

    protected function _getDisplayName(): string
    {
        if ($this->name) {
            return $this->name;
        }

        $name = '';
        if ($this->has('subject')) {
            $name = $this->subject->name;
        }
        if ($this->has('class')) {
            $name .= ' - ' . $this->class->name;
        }
        return $name ?: __('Course #{0}', $this->id);
    }

    protected function _getScheduleArray(): ?array
    {
        if (empty($this->schedule)) {
            return null;
        }
        return json_decode($this->schedule, true);
    }
}
