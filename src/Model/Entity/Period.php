<?php
/**
 * Period Entity
 *
 * Represents a daily time slot for class routines.
 *
 * @created 2026-05-15
 * @author Arif
 */
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

class Period extends Entity
{
    protected array $_accessible = [
        'academic_year' => true,
        'name' => true,
        'start_time' => true,
        'end_time' => true,
        'is_break' => true,
        'order_num' => true,
        'is_active' => true,
        'created' => true,
        'modified' => true,
    ];

    /**
     * Get formatted time range for display
     */
    protected function _getTimeRange(): string
    {
        $start = $this->start_time instanceof \DateTime
            ? $this->start_time->format('H:i')
            : (string) $this->start_time;
        $end = $this->end_time instanceof \DateTime
            ? $this->end_time->format('H:i')
            : (string) $this->end_time;

        return $start . ' - ' . $end;
    }

    /**
     * Get display name with time
     */
    protected function _getDisplayName(): string
    {
        return $this->name . ' (' . $this->time_range . ')';
    }
}
