<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

class Subject extends Entity
{
    protected array $_accessible = [
        'name' => true,
        'code' => true,
        'description' => true,
        'category' => true,
        'credit_hours' => true,
        'is_active' => true,
        'created' => true,
        'modified' => true,
    ];

    // Subject categories for Cambridge curriculum
    public const CATEGORY_CORE = 'Core';
    public const CATEGORY_ELECTIVE = 'Elective';
    public const CATEGORY_COCURRICULAR = 'Co-curricular';

    public static function getCategories(): array
    {
        return [
            self::CATEGORY_CORE => __('Core Subject'),
            self::CATEGORY_ELECTIVE => __('Elective Subject'),
            self::CATEGORY_COCURRICULAR => __('Co-curricular'),
        ];
    }

    // Cambridge curriculum subjects
    public static function getCambridgeSubjects(): array
    {
        return [
            // Core Subjects
            ['name' => 'English Language', 'code' => 'ENG', 'category' => self::CATEGORY_CORE],
            ['name' => 'Mathematics', 'code' => 'MATH', 'category' => self::CATEGORY_CORE],
            ['name' => 'Science', 'code' => 'SCI', 'category' => self::CATEGORY_CORE],
            ['name' => 'Urdu', 'code' => 'URD', 'category' => self::CATEGORY_CORE],
            ['name' => 'Islamiat', 'code' => 'ISL', 'category' => self::CATEGORY_CORE],
            ['name' => 'Pakistan Studies', 'code' => 'PST', 'category' => self::CATEGORY_CORE],

            // O Level Subjects
            ['name' => 'Physics', 'code' => 'PHY', 'category' => self::CATEGORY_CORE],
            ['name' => 'Chemistry', 'code' => 'CHM', 'category' => self::CATEGORY_CORE],
            ['name' => 'Biology', 'code' => 'BIO', 'category' => self::CATEGORY_CORE],
            ['name' => 'Additional Mathematics', 'code' => 'AMATH', 'category' => self::CATEGORY_ELECTIVE],
            ['name' => 'Computer Science', 'code' => 'CS', 'category' => self::CATEGORY_ELECTIVE],
            ['name' => 'Economics', 'code' => 'ECO', 'category' => self::CATEGORY_ELECTIVE],
            ['name' => 'Business Studies', 'code' => 'BUS', 'category' => self::CATEGORY_ELECTIVE],
            ['name' => 'Accounting', 'code' => 'ACC', 'category' => self::CATEGORY_ELECTIVE],

            // Co-curricular
            ['name' => 'Physical Education', 'code' => 'PE', 'category' => self::CATEGORY_COCURRICULAR],
            ['name' => 'Art & Design', 'code' => 'ART', 'category' => self::CATEGORY_COCURRICULAR],
            ['name' => 'Music', 'code' => 'MUS', 'category' => self::CATEGORY_COCURRICULAR],
        ];
    }
}
