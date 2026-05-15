<?php
/**
 * AcademicEvent Entity
 *
 * Represents calendar events like terms, holidays, and exams.
 *
 * @created 2026-05-15
 * @author Arif
 */
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

class AcademicEvent extends Entity
{
    protected array $_accessible = [
        'academic_year' => true,
        'title' => true,
        'event_type' => true,
        'start_date' => true,
        'end_date' => true,
        'description' => true,
        'is_active' => true,
        'created' => true,
        'modified' => true,
    ];

    // Event type constants
    public const TYPE_TERM_START = 'term_start';
    public const TYPE_TERM_END = 'term_end';
    public const TYPE_HOLIDAY = 'holiday';
    public const TYPE_EXAM = 'exam';
    public const TYPE_OTHER = 'other';

    /**
     * Get all event types
     */
    public static function getEventTypes(): array
    {
        return [
            self::TYPE_TERM_START => __('Term Start'),
            self::TYPE_TERM_END => __('Term End'),
            self::TYPE_HOLIDAY => __('Holiday'),
            self::TYPE_EXAM => __('Examination'),
            self::TYPE_OTHER => __('Other'),
        ];
    }

    /**
     * Get event type label
     */
    public static function getEventTypeLabel(string $type): string
    {
        $types = self::getEventTypes();
        return $types[$type] ?? $type;
    }

    /**
     * Get CSS class for event type (for calendar styling)
     */
    public static function getEventTypeColor(string $type): string
    {
        return match ($type) {
            self::TYPE_TERM_START => 'green',
            self::TYPE_TERM_END => 'blue',
            self::TYPE_HOLIDAY => 'amber',
            self::TYPE_EXAM => 'red',
            default => 'gray',
        };
    }

    /**
     * Virtual property: event type label
     */
    protected function _getEventTypeLabel(): string
    {
        return self::getEventTypeLabel($this->event_type);
    }

    /**
     * Virtual property: event type color
     */
    protected function _getEventTypeColor(): string
    {
        return self::getEventTypeColor($this->event_type);
    }

    /**
     * Virtual property: formatted date range
     */
    protected function _getDateRange(): string
    {
        if (!$this->start_date) {
            return '';
        }

        // Cake\I18n\Date has format() method
        $start = $this->start_date->format('M j, Y');

        if (!$this->end_date || $this->start_date->equals($this->end_date)) {
            return $start;
        }

        $end = $this->end_date->format('M j, Y');

        return $start . ' - ' . $end;
    }

    /**
     * Virtual property: duration in days
     */
    protected function _getDurationDays(): int
    {
        if (!$this->end_date || !$this->start_date) {
            return 1;
        }

        // Cake\I18n\Date has diffInDays() method
        return $this->start_date->diffInDays($this->end_date) + 1;
    }
}
