<?php
/**
 * Copy Class Routine Template
 *
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\SchoolClass $sourceClass
 * @var int $sourceRoutineCount
 * @var array $classesGrouped
 * @var array $academicYears
 *
 * @created 2026-05-15
 * @author Arif
 */
$this->assign('title', __('Copy Routine - {0}', $sourceClass->name));
?>

<section class="admin-section">
    <header class="admin-section__header">
        <div>
            <nav class="breadcrumb">
                <?= $this->Html->link(__('Class Routines'), ['action' => 'index']) ?>
                / <?= $this->Html->link(h($sourceClass->name), ['action' => 'edit', $sourceClass->id]) ?>
                / <?= __('Copy') ?>
            </nav>
            <h1><?= __('Copy Routine') ?></h1>
            <p class="text-muted"><?= __('Copy timetable entries from one class to another') ?></p>
        </div>
    </header>

    <div class="copy-layout">
        <div class="source-info-card">
            <h3><?= __('Source Class') ?></h3>
            <div class="source-class-details">
                <div class="class-name"><?= h($sourceClass->name) ?><?= $sourceClass->section ? ' - ' . h($sourceClass->section) : '' ?></div>
                <dl>
                    <dt><?= __('Grade Level') ?></dt>
                    <dd><?= h($sourceClass->grade_level) ?></dd>
                    <dt><?= __('Academic Year') ?></dt>
                    <dd><?= h($sourceClass->academic_year) ?></dd>
                    <dt><?= __('Routine Entries') ?></dt>
                    <dd><strong><?= $sourceRoutineCount ?></strong> <?= __('slots') ?></dd>
                </dl>
            </div>

            <div class="copy-arrow">
                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 5v14M19 12l-7 7-7-7"/>
                </svg>
            </div>
        </div>

        <div class="target-section">
            <?= $this->Form->create(null, ['class' => 'copy-form']) ?>

            <h3><?= __('Select Target Class') ?></h3>
            <p class="text-muted"><?= __('Choose which class should receive the copied routine:') ?></p>

            <?php if (empty($classesGrouped)): ?>
                <div class="empty-state empty-state--small">
                    <p><?= __('No other active classes available as copy targets.') ?></p>
                </div>
            <?php else: ?>
                <div class="target-classes">
                    <?php foreach ($classesGrouped as $year => $classes): ?>
                        <details class="year-group" <?= $year === $sourceClass->academic_year ? 'open' : '' ?>>
                            <summary class="year-group__header">
                                <span class="year-badge"><?= h($year) ?></span>
                                <span class="year-group__count"><?= count($classes) ?> <?= __('classes') ?></span>
                            </summary>
                            <div class="year-group__classes">
                                <?php foreach ($classes as $class): ?>
                                    <?php
                                    $isSameGrade = $class->grade_level === $sourceClass->grade_level;
                                    ?>
                                    <label class="target-class-card <?= $isSameGrade ? 'target-class-card--recommended' : '' ?>">
                                        <input type="radio" name="target_class_id" value="<?= $class->id ?>">
                                        <div class="target-class-card__content">
                                            <div class="target-class-card__name">
                                                <?= h($class->name) ?>
                                                <?= $class->section ? ' - ' . h($class->section) : '' ?>
                                                <?php if ($isSameGrade): ?>
                                                    <span class="recommended-badge"><?= __('Same Grade') ?></span>
                                                <?php endif; ?>
                                            </div>
                                            <div class="target-class-card__meta">
                                                <?= h($class->grade_level) ?>
                                            </div>
                                        </div>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </details>
                    <?php endforeach; ?>
                </div>

                <div class="copy-options">
                    <h4><?= __('Copy Options') ?></h4>

                    <label class="option-checkbox">
                        <input type="checkbox" name="copy_teachers" value="1">
                        <span><?= __('Copy teacher assignments') ?></span>
                        <small><?= __('Include teacher IDs from source routine (may cause conflicts)') ?></small>
                    </label>

                    <label class="option-checkbox">
                        <input type="checkbox" name="clear_target" value="1">
                        <span><?= __('Clear existing routine in target class') ?></span>
                        <small><?= __('Delete all existing entries before copying') ?></small>
                    </label>
                </div>

                <div class="form-actions">
                    <?= $this->Html->link(__('Cancel'), ['action' => 'edit', $sourceClass->id], ['class' => 'btn btn--ghost-dark']) ?>
                    <?= $this->Form->button(__('Copy Routine'), ['class' => 'btn btn--solid']) ?>
                </div>
            <?php endif; ?>

            <?= $this->Form->end() ?>
        </div>
    </div>
</section>

<style>
.copy-layout {
    display: grid;
    grid-template-columns: 280px 1fr;
    gap: 24px;
    margin-top: 20px;
}

@media (max-width: 900px) {
    .copy-layout {
        grid-template-columns: 1fr;
    }
}

.source-info-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    padding: 20px;
    height: fit-content;
    position: sticky;
    top: 20px;
}

.source-info-card h3 {
    margin: 0 0 16px 0;
    font-size: 0.9rem;
    font-weight: 500;
    color: var(--gray-500);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.source-class-details {
    padding: 16px;
    background: var(--primary-50);
    border-radius: 8px;
    border-left: 4px solid var(--primary-500);
}

.class-name {
    font-size: 1.1rem;
    font-weight: 600;
    color: var(--gray-800);
    margin-bottom: 12px;
}

.source-class-details dl {
    display: grid;
    grid-template-columns: auto 1fr;
    gap: 6px 12px;
    margin: 0;
}

.source-class-details dt {
    font-size: 0.8rem;
    color: var(--gray-500);
}

.source-class-details dd {
    margin: 0;
    font-size: 0.9rem;
    color: var(--gray-700);
}

.copy-arrow {
    display: flex;
    justify-content: center;
    margin-top: 20px;
    color: var(--gray-400);
}

.target-section h3 {
    margin: 0 0 8px 0;
    font-size: 1rem;
    color: var(--gray-800);
}

.target-classes {
    margin-top: 16px;
}

.year-group {
    margin-bottom: 12px;
    border: 1px solid var(--gray-200);
    border-radius: 8px;
    overflow: hidden;
}

.year-group__header {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 16px;
    background: var(--gray-50);
    cursor: pointer;
    user-select: none;
}

.year-group__header:hover {
    background: var(--gray-100);
}

.year-badge {
    display: inline-block;
    padding: 4px 10px;
    font-size: 0.8rem;
    font-weight: 600;
    background: var(--blue-100);
    color: var(--blue-700);
    border-radius: 12px;
}

.year-group__count {
    font-size: 0.85rem;
    color: var(--gray-500);
}

.year-group__classes {
    padding: 12px;
    display: grid;
    gap: 8px;
    border-top: 1px solid var(--gray-200);
}

.target-class-card {
    display: block;
    cursor: pointer;
}

.target-class-card input[type="radio"] {
    position: absolute;
    opacity: 0;
}

.target-class-card__content {
    padding: 12px 16px;
    background: white;
    border: 2px solid var(--gray-200);
    border-radius: 8px;
    transition: all 0.2s;
}

.target-class-card input[type="radio"]:checked + .target-class-card__content {
    border-color: var(--blue-500);
    background: var(--blue-50);
}

.target-class-card:hover .target-class-card__content {
    border-color: var(--blue-300);
}

.target-class-card__name {
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: 500;
    color: var(--gray-800);
}

.target-class-card__meta {
    font-size: 0.85rem;
    color: var(--gray-500);
    margin-top: 4px;
}

.target-class-card--recommended .target-class-card__content {
    border-color: var(--green-300);
    background: var(--green-50);
}

.target-class-card--recommended input[type="radio"]:checked + .target-class-card__content {
    border-color: var(--green-500);
    background: var(--green-100);
}

.recommended-badge {
    font-size: 0.7rem;
    font-weight: 500;
    padding: 2px 8px;
    background: var(--green-500);
    color: white;
    border-radius: 10px;
}

.copy-options {
    margin-top: 24px;
    padding: 20px;
    background: var(--gray-50);
    border-radius: 8px;
}

.copy-options h4 {
    margin: 0 0 16px 0;
    font-size: 0.9rem;
    font-weight: 600;
    color: var(--gray-700);
}

.option-checkbox {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 8px;
    padding: 12px;
    background: white;
    border-radius: 6px;
    cursor: pointer;
    margin-bottom: 8px;
}

.option-checkbox:last-child {
    margin-bottom: 0;
}

.option-checkbox input[type="checkbox"] {
    flex-shrink: 0;
}

.option-checkbox span {
    font-weight: 500;
    color: var(--gray-800);
}

.option-checkbox small {
    width: 100%;
    margin-left: 24px;
    font-size: 0.8rem;
    color: var(--gray-500);
}

.form-actions {
    display: flex;
    gap: 12px;
    justify-content: flex-end;
    margin-top: 24px;
    padding-top: 20px;
    border-top: 1px solid var(--gray-200);
}

.empty-state--small {
    padding: 30px;
    text-align: center;
    background: var(--gray-50);
    border-radius: 8px;
}
</style>
