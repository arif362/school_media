<?php
/**
 * Class Routine Edit Template - Visual Timetable Builder
 *
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\SchoolClass $class
 * @var iterable<\App\Model\Entity\Period> $periods
 * @var array $routineGrid
 * @var array $subjects
 * @var array $teachers
 * @var array $weekdays
 *
 * @created 2026-05-15
 * @author Arif
 */
$this->assign('title', __('Edit Class Routine'));
?>

<section class="admin-section">
    <header class="admin-section__header">
        <div>
            <nav class="breadcrumb">
                <?= $this->Html->link(__('Class Routines'), ['action' => 'index']) ?> / <?= __('Edit') ?>
            </nav>
            <h1><?= __('Edit Routine: {0}', h($class->display_name)) ?></h1>
            <p class="text-muted">
                <?= h($class->grade_level) ?> &bull; <?= __('Academic Year') ?>: <?= h($class->academic_year) ?>
                <?php if ($class->class_teacher): ?>
                    &bull; <?= __('Class Teacher') ?>: <?= h($class->class_teacher->name) ?>
                <?php endif; ?>
            </p>
        </div>
        <div class="header-actions">
            <?= $this->Html->link(__('Apply Template'), ['action' => 'applyTemplate', $class->id], ['class' => 'btn btn--ghost-dark']) ?>
            <?= $this->Html->link(__('Copy To...'), ['action' => 'copy', $class->id], ['class' => 'btn btn--ghost-dark']) ?>
            <?= $this->Html->link(__('View Printable'), ['action' => 'view', $class->id], ['class' => 'btn btn--ghost-dark', 'target' => '_blank']) ?>
        </div>
    </header>

    <?= $this->Form->create(null, ['class' => 'routine-form']) ?>

    <div class="routine-builder">
        <div class="routine-grid-wrapper">
            <table class="routine-grid">
                <thead>
                    <tr>
                        <th class="routine-grid__period-col"><?= __('Period') ?></th>
                        <?php foreach ($weekdays as $dayNum => $dayName): ?>
                            <th class="routine-grid__day-col"><?= h($dayName) ?></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($periods as $period): ?>
                        <tr class="<?= $period->is_break ? 'routine-grid__break-row' : '' ?>">
                            <td class="routine-grid__period-cell">
                                <div class="period-info">
                                    <strong><?= h($period->name) ?></strong>
                                    <span class="period-time">
                                        <?= $period->start_time instanceof \DateTime
                                            ? $period->start_time->format('h:i A')
                                            : h($period->start_time) ?>
                                        -
                                        <?= $period->end_time instanceof \DateTime
                                            ? $period->end_time->format('h:i A')
                                            : h($period->end_time) ?>
                                    </span>
                                    <?php if ($period->is_break): ?>
                                        <span class="period-badge period-badge--break"><?= __('Break') ?></span>
                                    <?php endif; ?>
                                </div>
                            </td>

                            <?php foreach ($weekdays as $dayNum => $dayName): ?>
                                <td class="routine-grid__slot-cell <?= $period->is_break ? 'routine-grid__slot-cell--break' : '' ?>">
                                    <?php if ($period->is_break): ?>
                                        <div class="break-slot">
                                            <span><?= h($period->name) ?></span>
                                        </div>
                                    <?php else: ?>
                                        <?php
                                        $existingRoutine = $routineGrid[$period->id][$dayNum] ?? null;
                                        $fieldPrefix = "routine[{$period->id}][{$dayNum}]";
                                        ?>
                                        <div class="slot-editor">
                                            <div class="slot-editor__field">
                                                <?= $this->Form->select(
                                                    "{$fieldPrefix}[subject_id]",
                                                    $subjects,
                                                    [
                                                        'empty' => __('-- Subject --'),
                                                        'value' => $existingRoutine->subject_id ?? null,
                                                        'class' => 'slot-select slot-select--subject',
                                                        'data-period' => $period->id,
                                                        'data-day' => $dayNum,
                                                    ]
                                                ) ?>
                                            </div>
                                            <div class="slot-editor__field">
                                                <?= $this->Form->select(
                                                    "{$fieldPrefix}[teacher_id]",
                                                    $teachers,
                                                    [
                                                        'empty' => __('-- Teacher --'),
                                                        'value' => $existingRoutine->teacher_id ?? null,
                                                        'class' => 'slot-select slot-select--teacher',
                                                        'data-period' => $period->id,
                                                        'data-day' => $dayNum,
                                                    ]
                                                ) ?>
                                                <div class="conflict-warning" data-period="<?= $period->id ?>" data-day="<?= $dayNum ?>"></div>
                                            </div>
                                            <div class="slot-editor__field slot-editor__field--room">
                                                <?= $this->Form->text(
                                                    "{$fieldPrefix}[room]",
                                                    [
                                                        'value' => $existingRoutine->room ?? '',
                                                        'placeholder' => __('Room'),
                                                        'class' => 'slot-input slot-input--room',
                                                    ]
                                                ) ?>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="routine-builder__actions">
            <?= $this->Html->link(__('Cancel'), ['action' => 'index'], ['class' => 'btn btn--ghost-dark']) ?>
            <?= $this->Form->button(__('Save Routine'), ['class' => 'btn btn--solid btn--lg']) ?>
        </div>
    </div>

    <?= $this->Form->end() ?>
</section>

<style>
.routine-builder {
    margin-top: 20px;
}

.routine-grid-wrapper {
    overflow-x: auto;
    background: white;
    border-radius: 12px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.routine-grid {
    width: 100%;
    border-collapse: collapse;
    min-width: 900px;
}

.routine-grid th,
.routine-grid td {
    border: 1px solid var(--gray-200);
    padding: 0;
}

.routine-grid thead th {
    background: var(--gray-50);
    padding: 14px 12px;
    font-weight: 600;
    font-size: 0.9rem;
    text-align: center;
    color: var(--gray-700);
}

.routine-grid__period-col {
    width: 160px;
    text-align: left !important;
}

.routine-grid__day-col {
    width: calc((100% - 160px) / 5);
}

.routine-grid__period-cell {
    background: var(--gray-50);
    padding: 12px 14px !important;
    vertical-align: middle;
}

.period-info {
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.period-info strong {
    font-size: 0.9rem;
    color: var(--gray-800);
}

.period-time {
    font-size: 0.75rem;
    color: var(--gray-500);
}

.period-badge {
    display: inline-block;
    margin-top: 4px;
    padding: 2px 8px;
    font-size: 0.7rem;
    font-weight: 500;
    border-radius: 10px;
    width: fit-content;
}

.period-badge--break {
    background: var(--amber-100);
    color: var(--amber-700);
}

.routine-grid__slot-cell {
    vertical-align: top;
    padding: 8px !important;
    min-height: 100px;
}

.routine-grid__slot-cell--break {
    background: var(--amber-50);
}

.routine-grid__break-row .routine-grid__period-cell {
    background: var(--amber-50);
}

.break-slot {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 80px;
    color: var(--amber-600);
    font-weight: 500;
    font-size: 0.85rem;
}

.slot-editor {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.slot-editor__field {
    width: 100%;
}

.slot-editor__field--room {
    margin-top: 2px;
}

.slot-select,
.slot-input {
    width: 100%;
    padding: 6px 8px;
    font-size: 0.8rem;
    border: 1px solid var(--gray-300);
    border-radius: 6px;
    background: white;
    transition: border-color 0.15s, box-shadow 0.15s;
}

.slot-select:focus,
.slot-input:focus {
    outline: none;
    border-color: var(--primary-500);
    box-shadow: 0 0 0 3px var(--primary-100);
}

.slot-select--subject {
    font-weight: 500;
}

.slot-select--teacher {
    color: var(--gray-600);
}

.slot-select--teacher.has-conflict {
    border-color: var(--amber-500);
    background: var(--amber-50);
}

.conflict-warning {
    display: none;
    margin-top: 4px;
    padding: 4px 8px;
    font-size: 0.7rem;
    color: var(--amber-700);
    background: var(--amber-100);
    border-radius: 4px;
    line-height: 1.3;
}

.conflict-warning.is-visible {
    display: block;
}

.slot-input--room {
    font-size: 0.75rem;
    padding: 4px 8px;
    color: var(--gray-600);
}

.slot-input--room::placeholder {
    color: var(--gray-400);
}

.routine-builder__actions {
    display: flex;
    justify-content: flex-end;
    gap: 12px;
    margin-top: 20px;
    padding: 20px;
    background: var(--gray-50);
    border-radius: 12px;
}

.btn--lg {
    padding: 12px 32px;
    font-size: 1rem;
}

/* Highlight filled slots */
.slot-select--subject:not([value=""]):valid {
    background: var(--green-50);
    border-color: var(--green-300);
}

/* Responsive */
@media (max-width: 1200px) {
    .routine-grid-wrapper {
        margin: 0 -20px;
        border-radius: 0;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const classId = <?= $class->id ?>;
    const academicYear = '<?= h($class->academic_year) ?>';
    const checkConflictUrl = '<?= $this->Url->build(['action' => 'checkConflict']) ?>';

    // Visual feedback when subject is selected
    document.querySelectorAll('.slot-select--subject').forEach(select => {
        select.addEventListener('change', function() {
            const slot = this.closest('.slot-editor');
            if (this.value) {
                slot.classList.add('slot-filled');
            } else {
                slot.classList.remove('slot-filled');
            }
        });

        // Initial state
        if (select.value) {
            select.closest('.slot-editor')?.classList.add('slot-filled');
        }
    });

    // Teacher conflict detection
    document.querySelectorAll('.slot-select--teacher').forEach(select => {
        select.addEventListener('change', function() {
            const teacherId = this.value;
            const periodId = this.dataset.period;
            const dayOfWeek = this.dataset.day;
            const warningEl = this.parentElement.querySelector('.conflict-warning');

            // Clear previous warning
            this.classList.remove('has-conflict');
            if (warningEl) {
                warningEl.classList.remove('is-visible');
                warningEl.textContent = '';
            }

            if (!teacherId) return;

            // Check for conflicts via AJAX
            const params = new URLSearchParams({
                teacher_id: teacherId,
                period_id: periodId,
                day_of_week: dayOfWeek,
                academic_year: academicYear,
                exclude_class_id: classId
            });

            fetch(`${checkConflictUrl}?${params}`)
                .then(response => response.json())
                .then(data => {
                    if (data.hasConflict && warningEl) {
                        this.classList.add('has-conflict');
                        warningEl.textContent = data.message;
                        warningEl.classList.add('is-visible');
                    }
                })
                .catch(err => console.error('Conflict check failed:', err));
        });
    });
});
</script>
