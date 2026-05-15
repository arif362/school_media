<?php
/**
 * RoutineTemplate Entity
 *
 * Pre-built Cambridge curriculum templates with recommended period allocations.
 *
 * @created 2026-05-15
 * @author Arif
 */
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

class RoutineTemplate extends Entity
{
    protected array $_accessible = [
        'name' => true,
        'cambridge_stage' => true,
        'description' => true,
        'is_active' => true,
        'created' => true,
        'modified' => true,
        'routine_template_items' => true,
    ];

    // Cambridge Stages
    public const STAGE_PRIMARY = 'primary';
    public const STAGE_LOWER_SECONDARY = 'lower_secondary';
    public const STAGE_IGCSE = 'igcse';

    /**
     * Get Cambridge stages for dropdown
     */
    public static function getCambridgeStages(): array
    {
        return [
            self::STAGE_PRIMARY => __('Primary (Grades 1-6)'),
            self::STAGE_LOWER_SECONDARY => __('Lower Secondary (Grades 7-9)'),
            self::STAGE_IGCSE => __('IGCSE (Grades 10-11)'),
        ];
    }

    /**
     * Get stage label
     */
    protected function _getStageLabel(): string
    {
        $stages = self::getCambridgeStages();
        return $stages[$this->cambridge_stage] ?? $this->cambridge_stage;
    }

    /**
     * Get total periods per week from template items
     */
    protected function _getTotalPeriods(): int
    {
        if (empty($this->routine_template_items)) {
            return 0;
        }

        $total = 0;
        foreach ($this->routine_template_items as $item) {
            $total += (int) $item->periods_per_week;
        }
        return $total;
    }

    /**
     * Get required subjects count
     */
    protected function _getRequiredSubjectsCount(): int
    {
        if (empty($this->routine_template_items)) {
            return 0;
        }

        $count = 0;
        foreach ($this->routine_template_items as $item) {
            if ($item->is_required) {
                $count++;
            }
        }
        return $count;
    }

    /**
     * Map grade levels to Cambridge stages
     */
    public static function getStageForGradeLevel(string $gradeLevel): ?string
    {
        // Primary: Grades 1-6
        $primaryGrades = ['Grade 1', 'Grade 2', 'Grade 3', 'Grade 4', 'Grade 5', 'Grade 6'];
        if (in_array($gradeLevel, $primaryGrades)) {
            return self::STAGE_PRIMARY;
        }

        // Lower Secondary: Grades 7-9
        $lowerSecondaryGrades = ['Grade 7', 'Grade 8', 'Grade 9'];
        if (in_array($gradeLevel, $lowerSecondaryGrades)) {
            return self::STAGE_LOWER_SECONDARY;
        }

        // IGCSE: Grades 10-11 (O Level)
        $igcseGrades = ['Grade 10', 'O Level', 'Grade 11'];
        if (in_array($gradeLevel, $igcseGrades)) {
            return self::STAGE_IGCSE;
        }

        return null;
    }
}
