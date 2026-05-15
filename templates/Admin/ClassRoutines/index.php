<?php
/**
 * Class Routines Index Template
 *
 * Shows all classes with their routine completion status.
 *
 * @var \App\View\AppView $this
 * @var array $classesWithCompletion
 * @var array $gradeLevels
 * @var array $academicYears
 * @var string|null $gradeLevel
 * @var string|null $academicYear
 *
 * @created 2026-05-15
 * @author Arif
 */
$this->assign('title', __('Class Routines'));
?>

<section class="admin-section">
    <header class="admin-section__header">
        <div>
            <h1><?= __('Class Routines') ?></h1>
            <p class="text-muted"><?= __('Manage weekly timetables for each class') ?></p>
        </div>
        <div class="header-actions">
            <?= $this->Html->link(__('Manage Periods'), ['controller' => 'Periods', 'action' => 'index'], ['class' => 'btn btn--ghost-dark']) ?>
        </div>
    </header>

    <div class="filters-bar">
        <?= $this->Form->create(null, ['type' => 'get', 'class' => 'filters-form']) ?>
        <div class="filters-row">
            <div class="filter-group">
                <label><?= __('Grade Level') ?></label>
                <?= $this->Form->control('grade_level', [
                    'type' => 'select',
                    'options' => $gradeLevels,
                    'empty' => __('All Grades'),
                    'value' => $gradeLevel ?? null,
                    'label' => false,
                    'class' => 'form-control',
                ]) ?>
            </div>
            <div class="filter-group">
                <label><?= __('Academic Year') ?></label>
                <?= $this->Form->control('academic_year', [
                    'type' => 'select',
                    'options' => $academicYears,
                    'value' => $academicYear ?? null,
                    'label' => false,
                    'class' => 'form-control',
                ]) ?>
            </div>
            <button type="submit" class="btn btn--solid"><?= __('Filter') ?></button>
            <?= $this->Html->link(__('Reset'), ['action' => 'index'], ['class' => 'btn btn--ghost-dark']) ?>
        </div>
        <?= $this->Form->end() ?>
    </div>

    <?php if (empty($classesWithCompletion)): ?>
        <div class="empty-state">
            <div class="empty-state__icon">&#128197;</div>
            <h3><?= __('No classes found') ?></h3>
            <p><?= __('No active classes found for the selected filters.') ?></p>
        </div>
    <?php else: ?>
        <div class="routine-classes-grid">
            <?php foreach ($classesWithCompletion as $item): ?>
                <?php
                $class = $item['class'];
                $completion = $item['completion'];
                $completionClass = $completion >= 80 ? 'high' : ($completion >= 40 ? 'medium' : 'low');
                ?>
                <div class="routine-class-card">
                    <div class="routine-class-card__header">
                        <h3 class="routine-class-card__title">
                            <?= h($class->name) ?>
                            <?php if ($class->section): ?>
                                <span class="routine-class-card__section">- <?= h($class->section) ?></span>
                            <?php endif; ?>
                        </h3>
                        <span class="routine-class-card__grade"><?= h($class->grade_level) ?></span>
                    </div>

                    <div class="routine-class-card__body">
                        <div class="routine-class-card__info">
                            <div class="info-item">
                                <span class="info-label"><?= __('Year') ?></span>
                                <span class="info-value"><?= h($class->academic_year) ?></span>
                            </div>
                            <?php if ($class->class_teacher): ?>
                                <div class="info-item">
                                    <span class="info-label"><?= __('Class Teacher') ?></span>
                                    <span class="info-value"><?= h($class->class_teacher->name) ?></span>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="routine-class-card__progress">
                            <div class="progress-header">
                                <span><?= __('Routine Completion') ?></span>
                                <span class="progress-value progress-value--<?= $completionClass ?>"><?= $completion ?>%</span>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-bar__fill progress-bar__fill--<?= $completionClass ?>" style="width: <?= $completion ?>%"></div>
                            </div>
                        </div>
                    </div>

                    <div class="routine-class-card__actions">
                        <?= $this->Html->link(__('Edit Routine'), ['action' => 'edit', $class->id], ['class' => 'btn btn--solid btn--sm']) ?>
                        <?= $this->Html->link(__('Apply Template'), ['action' => 'applyTemplate', $class->id], ['class' => 'btn btn--ghost-dark btn--sm']) ?>
                        <?= $this->Html->link(__('Copy'), ['action' => 'copy', $class->id], ['class' => 'btn btn--ghost-dark btn--sm']) ?>
                        <?= $this->Html->link(__('View'), ['action' => 'view', $class->id], ['class' => 'btn btn--ghost-dark btn--sm']) ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

<style>
.routine-classes-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 20px;
    margin-top: 20px;
}

.routine-class-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    transition: box-shadow 0.2s, transform 0.2s;
}

.routine-class-card:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    transform: translateY(-2px);
}

.routine-class-card__header {
    padding: 16px 20px;
    background: linear-gradient(135deg, var(--primary-50) 0%, var(--primary-100) 100%);
    border-bottom: 1px solid var(--gray-200);
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
}

.routine-class-card__title {
    margin: 0;
    font-size: 1.1rem;
    font-weight: 600;
    color: var(--gray-900);
}

.routine-class-card__section {
    font-weight: 500;
    color: var(--gray-600);
}

.routine-class-card__grade {
    font-size: 0.75rem;
    font-weight: 500;
    padding: 4px 10px;
    background: white;
    border-radius: 20px;
    color: var(--gray-700);
    white-space: nowrap;
}

.routine-class-card__body {
    padding: 16px 20px;
}

.routine-class-card__info {
    display: flex;
    gap: 24px;
    margin-bottom: 16px;
}

.info-item {
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.info-label {
    font-size: 0.75rem;
    color: var(--gray-500);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.info-value {
    font-size: 0.9rem;
    font-weight: 500;
    color: var(--gray-800);
}

.routine-class-card__progress {
    margin-top: 12px;
}

.progress-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 6px;
    font-size: 0.85rem;
    color: var(--gray-600);
}

.progress-value {
    font-weight: 600;
}

.progress-value--high { color: var(--green-600); }
.progress-value--medium { color: var(--amber-600); }
.progress-value--low { color: var(--gray-500); }

.progress-bar {
    height: 8px;
    background: var(--gray-200);
    border-radius: 4px;
    overflow: hidden;
}

.progress-bar__fill {
    height: 100%;
    border-radius: 4px;
    transition: width 0.3s ease;
}

.progress-bar__fill--high { background: var(--green-500); }
.progress-bar__fill--medium { background: var(--amber-500); }
.progress-bar__fill--low { background: var(--gray-400); }

.routine-class-card__actions {
    padding: 12px 20px;
    background: var(--gray-50);
    border-top: 1px solid var(--gray-200);
    display: flex;
    gap: 8px;
}

.btn--sm {
    padding: 6px 14px;
    font-size: 0.85rem;
}
</style>
