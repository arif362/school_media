<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

class CourseMaterial extends Entity
{
    protected array $_accessible = [
        'course_id' => true,
        'title' => true,
        'description' => true,
        'type' => true,
        'file_path' => true,
        'external_url' => true,
        'uploaded_by' => true,
        'is_visible' => true,
        'order_num' => true,
        'created' => true,
        'modified' => true,
        'course' => true,
        'uploader' => true,
    ];

    // Material types
    public const TYPE_DOCUMENT = 'document';
    public const TYPE_VIDEO = 'video';
    public const TYPE_LINK = 'link';
    public const TYPE_ASSIGNMENT = 'assignment';
    public const TYPE_NOTES = 'notes';

    public static function getTypes(): array
    {
        return [
            self::TYPE_DOCUMENT => __('Document'),
            self::TYPE_VIDEO => __('Video'),
            self::TYPE_LINK => __('External Link'),
            self::TYPE_ASSIGNMENT => __('Assignment'),
            self::TYPE_NOTES => __('Lecture Notes'),
        ];
    }

    public static function getTypeIcons(): array
    {
        return [
            self::TYPE_DOCUMENT => '📄',
            self::TYPE_VIDEO => '🎥',
            self::TYPE_LINK => '🔗',
            self::TYPE_ASSIGNMENT => '📝',
            self::TYPE_NOTES => '📋',
        ];
    }
}
