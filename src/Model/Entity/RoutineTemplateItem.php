<?php
/**
 * RoutineTemplateItem Entity
 *
 * Individual subject allocations within a routine template.
 *
 * @created 2026-05-15
 * @author Arif
 */
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

class RoutineTemplateItem extends Entity
{
    protected array $_accessible = [
        'routine_template_id' => true,
        'subject_id' => true,
        'periods_per_week' => true,
        'is_required' => true,
        'created' => true,
        'modified' => true,
        'routine_template' => true,
        'subject' => true,
    ];

    /**
     * Get subject name from related subject
     */
    protected function _getSubjectName(): string
    {
        if ($this->has('subject') && $this->subject) {
            return $this->subject->name;
        }
        return __('Unknown Subject');
    }

    /**
     * Get formatted periods display
     */
    protected function _getPeriodsDisplay(): string
    {
        $periods = (int) $this->periods_per_week;
        return __n('{0} period/week', '{0} periods/week', $periods, $periods);
    }

    /**
     * Get requirement badge
     */
    protected function _getRequirementBadge(): string
    {
        return $this->is_required ? __('Required') : __('Optional');
    }
}
