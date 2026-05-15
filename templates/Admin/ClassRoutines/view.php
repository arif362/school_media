<?php
/**
 * Class Routine Print View
 *
 * Print-friendly timetable display.
 *
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\SchoolClass $class
 * @var iterable<\App\Model\Entity\Period> $periods
 * @var array $routineGrid
 * @var array $weekdays
 * @var int $completion
 * @var string $generatedDate
 *
 * @created 2026-05-15
 * @author Arif
 */
$this->assign('title', __('Timetable - {0}', $class->name));
?>

<div class="print-header">
    <div>
        <div class="print-header__logo">School Media</div>
        <div class="print-header__subtitle"><?= __('Class Timetable') ?></div>
    </div>
    <div class="print-header__info">
        <div><?= __('Generated: {0}', $generatedDate) ?></div>
    </div>
</div>

<div class="timetable-info">
    <h1 class="class-name">
        <?= h($class->name) ?>
        <?php if ($class->section): ?>
            <span class="class-section">- <?= h($class->section) ?></span>
        <?php endif; ?>
    </h1>
    <div class="class-details">
        <span class="detail-item">
            <strong><?= __('Grade:') ?></strong> <?= h($class->grade_level) ?>
        </span>
        <span class="detail-item">
            <strong><?= __('Academic Year:') ?></strong> <?= h($class->academic_year) ?>
        </span>
        <?php if ($class->class_teacher): ?>
            <span class="detail-item">
                <strong><?= __('Class Teacher:') ?></strong> <?= h($class->class_teacher->name) ?>
            </span>
        <?php endif; ?>
    </div>
</div>

<?php if ($periods->isEmpty()): ?>
    <div class="empty-message">
        <?= __('No periods defined for this academic year.') ?>
    </div>
<?php else: ?>
    <table class="timetable">
        <thead>
            <tr>
                <th class="col-period"><?= __('Time') ?></th>
                <?php foreach ($weekdays as $dayNum => $dayName): ?>
                    <th class="col-day"><?= h($dayName) ?></th>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($periods as $period): ?>
                <?php $isBreak = $period->is_break; ?>
                <tr class="<?= $isBreak ? 'row-break' : '' ?>">
                    <td class="cell-period">
                        <div class="period-name"><?= h($period->name) ?></div>
                        <div class="period-time"><?= h($period->time_range) ?></div>
                    </td>
                    <?php if ($isBreak): ?>
                        <td colspan="<?= count($weekdays) ?>" class="cell-break">
                            <?= h($period->name) ?>
                        </td>
                    <?php else: ?>
                        <?php foreach ($weekdays as $dayNum => $dayName): ?>
                            <?php
                            $slot = $routineGrid[$period->id][$dayNum] ?? null;
                            ?>
                            <td class="cell-slot <?= $slot ? 'cell-slot--filled' : '' ?>">
                                <?php if ($slot): ?>
                                    <div class="slot-subject"><?= h($slot->subject->name ?? '-') ?></div>
                                    <?php if ($slot->teacher): ?>
                                        <div class="slot-teacher"><?= h($slot->teacher->name) ?></div>
                                    <?php endif; ?>
                                    <?php if ($slot->room): ?>
                                        <div class="slot-room"><?= __('Room: {0}', h($slot->room)) ?></div>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="slot-empty">-</span>
                                <?php endif; ?>
                            </td>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="timetable-legend">
        <span class="legend-item">
            <span class="legend-color legend-color--filled"></span>
            <?= __('Assigned') ?>
        </span>
        <span class="legend-item">
            <span class="legend-color legend-color--empty"></span>
            <?= __('Not assigned') ?>
        </span>
        <span class="legend-item">
            <span class="legend-color legend-color--break"></span>
            <?= __('Break') ?>
        </span>
        <span class="completion-info">
            <?= __('Schedule Completion: {0}%', $completion) ?>
        </span>
    </div>
<?php endif; ?>

<style>
.print-header__subtitle {
    font-size: 12px;
    color: #6b7280;
    margin-top: 4px;
}

.timetable-info {
    margin-bottom: 20px;
}

.class-name {
    font-size: 24px;
    font-weight: 700;
    color: #1f2937;
    margin: 0 0 8px 0;
}

.class-section {
    font-weight: 500;
    color: #6b7280;
}

.class-details {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    font-size: 12px;
    color: #374151;
}

.detail-item strong {
    color: #6b7280;
}

.empty-message {
    padding: 40px;
    text-align: center;
    background: #f9fafb;
    border-radius: 8px;
    color: #6b7280;
    font-size: 14px;
}

.timetable {
    width: 100%;
    border-collapse: collapse;
    font-size: 11px;
    background: white;
}

.timetable th,
.timetable td {
    border: 1px solid #d1d5db;
    padding: 8px;
    text-align: center;
    vertical-align: middle;
}

.timetable th {
    background: #1f2937;
    color: white;
    font-weight: 600;
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.col-period {
    width: 100px;
}

.col-day {
    width: calc((100% - 100px) / 5);
}

.cell-period {
    background: #f3f4f6;
    text-align: left !important;
}

.period-name {
    font-weight: 600;
    color: #374151;
}

.period-time {
    font-size: 10px;
    color: #6b7280;
    margin-top: 2px;
}

.row-break {
    background: #fef3c7;
}

.cell-break {
    font-weight: 600;
    font-style: italic;
    color: #92400e;
    background: #fef3c7;
}

.cell-slot {
    background: #f9fafb;
    min-height: 60px;
}

.cell-slot--filled {
    background: white;
}

.slot-subject {
    font-weight: 600;
    color: #1f2937;
    margin-bottom: 4px;
}

.slot-teacher {
    font-size: 10px;
    color: #2563eb;
}

.slot-room {
    font-size: 9px;
    color: #6b7280;
    margin-top: 2px;
}

.slot-empty {
    color: #d1d5db;
}

.timetable-legend {
    margin-top: 15px;
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    font-size: 10px;
    color: #6b7280;
}

.legend-item {
    display: flex;
    align-items: center;
    gap: 6px;
}

.legend-color {
    width: 16px;
    height: 12px;
    border: 1px solid #d1d5db;
    border-radius: 2px;
}

.legend-color--filled {
    background: white;
}

.legend-color--empty {
    background: #f9fafb;
}

.legend-color--break {
    background: #fef3c7;
}

.completion-info {
    margin-left: auto;
    font-weight: 600;
    color: #374151;
}

@media print {
    .timetable th {
        background: #1f2937 !important;
        color: white !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }

    .cell-break,
    .row-break {
        background: #fef3c7 !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }

    .cell-period {
        background: #f3f4f6 !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
}
</style>
