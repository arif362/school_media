<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

class StudentClass extends Entity
{
    protected array $_accessible = [
        'student_id' => true,
        'class_id' => true,
        'roll_number' => true,
        'enrolled_date' => true,
        'status' => true,
        'created' => true,
        'modified' => true,
    ];

    public const STATUS_ACTIVE = 'active';
    public const STATUS_TRANSFERRED = 'transferred';
    public const STATUS_WITHDRAWN = 'withdrawn';

    public static function getStatuses(): array
    {
        return [
            self::STATUS_ACTIVE => __('Active'),
            self::STATUS_TRANSFERRED => __('Transferred'),
            self::STATUS_WITHDRAWN => __('Withdrawn'),
        ];
    }
}
