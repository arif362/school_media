<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

class Notification extends Entity
{
    protected array $_accessible = [
        'title' => true,
        'message' => true,
        'type' => true,
        'target_role' => true,
        'target_user_id' => true,
        'link' => true,
        'is_active' => true,
        'created_by' => true,
        'created' => true,
        'modified' => true,
    ];

    public const TYPE_INFO = 'info';
    public const TYPE_SUCCESS = 'success';
    public const TYPE_WARNING = 'warning';
    public const TYPE_DANGER = 'danger';

    public static function getTypes(): array
    {
        return [
            self::TYPE_INFO => __('Information'),
            self::TYPE_SUCCESS => __('Success'),
            self::TYPE_WARNING => __('Warning'),
            self::TYPE_DANGER => __('Important'),
        ];
    }

    public static function getTargetRoles(): array
    {
        return [
            '' => __('All Users'),
            'admin' => __('Admins Only'),
            'teacher' => __('Teachers Only'),
            'student' => __('Students Only'),
        ];
    }
}
