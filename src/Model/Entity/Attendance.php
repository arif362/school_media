<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

class Attendance extends Entity
{
    protected array $_accessible = [
        'student_id' => true,
        'class_id' => true,
        'date' => true,
        'status' => true,
        'check_in_time' => true,
        'check_out_time' => true,
        'remarks' => true,
        'marked_by' => true,
        'created' => true,
        'modified' => true,
    ];

    public const STATUS_PRESENT = 'present';
    public const STATUS_ABSENT = 'absent';
    public const STATUS_LATE = 'late';
    public const STATUS_EXCUSED = 'excused';
    public const STATUS_HALF_DAY = 'half_day';

    public static function getStatuses(): array
    {
        return [
            self::STATUS_PRESENT => __('Present'),
            self::STATUS_ABSENT => __('Absent'),
            self::STATUS_LATE => __('Late'),
            self::STATUS_EXCUSED => __('Excused'),
            self::STATUS_HALF_DAY => __('Half Day'),
        ];
    }

    public static function getStatusColors(): array
    {
        return [
            self::STATUS_PRESENT => 'success',
            self::STATUS_ABSENT => 'danger',
            self::STATUS_LATE => 'warning',
            self::STATUS_EXCUSED => 'info',
            self::STATUS_HALF_DAY => 'warning',
        ];
    }
}
