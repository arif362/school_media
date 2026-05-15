<?php
/**
 * ClassRoutine Entity
 *
 * Represents a single timetable entry (one period slot for one class on one day).
 *
 * @created 2026-05-15
 * @author Arif
 */
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

class ClassRoutine extends Entity
{
    protected array $_accessible = [
        'class_id' => true,
        'period_id' => true,
        'day_of_week' => true,
        'subject_id' => true,
        'teacher_id' => true,
        'room' => true,
        'notes' => true,
        'academic_year' => true,
        'is_active' => true,
        'created' => true,
        'modified' => true,
        'class' => true,
        'period' => true,
        'subject' => true,
        'teacher' => true,
    ];

    // Day of week constants (ISO-8601: 1=Monday, 7=Sunday)
    public const MONDAY = 1;
    public const TUESDAY = 2;
    public const WEDNESDAY = 3;
    public const THURSDAY = 4;
    public const FRIDAY = 5;
    public const SATURDAY = 6;
    public const SUNDAY = 7;

    /**
     * Get all weekdays (Monday-Friday) for school schedule
     */
    public static function getWeekdays(): array
    {
        return [
            self::MONDAY => __('Monday'),
            self::TUESDAY => __('Tuesday'),
            self::WEDNESDAY => __('Wednesday'),
            self::THURSDAY => __('Thursday'),
            self::FRIDAY => __('Friday'),
        ];
    }

    /**
     * Get all days including weekend
     */
    public static function getAllDays(): array
    {
        return [
            self::MONDAY => __('Monday'),
            self::TUESDAY => __('Tuesday'),
            self::WEDNESDAY => __('Wednesday'),
            self::THURSDAY => __('Thursday'),
            self::FRIDAY => __('Friday'),
            self::SATURDAY => __('Saturday'),
            self::SUNDAY => __('Sunday'),
        ];
    }

    /**
     * Get short day names for display
     */
    public static function getShortDays(): array
    {
        return [
            self::MONDAY => __('Mon'),
            self::TUESDAY => __('Tue'),
            self::WEDNESDAY => __('Wed'),
            self::THURSDAY => __('Thu'),
            self::FRIDAY => __('Fri'),
            self::SATURDAY => __('Sat'),
            self::SUNDAY => __('Sun'),
        ];
    }

    /**
     * Get day name from number
     */
    public static function getDayName(int $day): string
    {
        $days = self::getAllDays();
        return $days[$day] ?? '';
    }

    /**
     * Get short day name from number
     */
    public static function getShortDayName(int $day): string
    {
        $days = self::getShortDays();
        return $days[$day] ?? '';
    }

    /**
     * Virtual property: day name
     */
    protected function _getDayName(): string
    {
        return self::getDayName($this->day_of_week);
    }

    /**
     * Virtual property: short day name
     */
    protected function _getShortDayName(): string
    {
        return self::getShortDayName($this->day_of_week);
    }

    /**
     * Virtual property: display text for the routine slot
     */
    protected function _getDisplayText(): string
    {
        $parts = [];

        if ($this->has('subject') && $this->subject) {
            $parts[] = $this->subject->name;
        }

        if ($this->has('teacher') && $this->teacher) {
            $parts[] = $this->teacher->name;
        }

        if ($this->room) {
            $parts[] = __('Room: {0}', $this->room);
        }

        return implode(' | ', $parts) ?: __('Empty slot');
    }
}
