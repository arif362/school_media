<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

class StudentCourse extends Entity
{
    protected array $_accessible = [
        'student_id' => true,
        'course_id' => true,
        'enrolled_date' => true,
        'status' => true,
        'grade' => true,
        'marks' => true,
        'remarks' => true,
        'created' => true,
        'modified' => true,
        'student' => true,
        'course' => true,
    ];

    // Enrollment statuses
    public const STATUS_ENROLLED = 'enrolled';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_DROPPED = 'dropped';
    public const STATUS_FAILED = 'failed';

    public static function getStatuses(): array
    {
        return [
            self::STATUS_ENROLLED => __('Enrolled'),
            self::STATUS_COMPLETED => __('Completed'),
            self::STATUS_DROPPED => __('Dropped'),
            self::STATUS_FAILED => __('Failed'),
        ];
    }

    // Cambridge grading scale
    public static function getGrades(): array
    {
        return [
            'A*' => __('A* (Outstanding)'),
            'A' => __('A (Excellent)'),
            'B' => __('B (Very Good)'),
            'C' => __('C (Good)'),
            'D' => __('D (Satisfactory)'),
            'E' => __('E (Pass)'),
            'U' => __('U (Ungraded)'),
        ];
    }

    public static function getGradeColors(): array
    {
        return [
            'A*' => 'success',
            'A' => 'success',
            'B' => 'info',
            'C' => 'primary',
            'D' => 'warning',
            'E' => 'warning',
            'U' => 'danger',
        ];
    }
}
