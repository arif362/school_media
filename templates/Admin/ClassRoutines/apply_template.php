<?php
/**
 * Apply Template to Class Routine
 *
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\SchoolClass $class
 * @var array $templates
 * @var array $allTemplates
 * @var string|null $cambridgeStage
 * @var array $periods
 * @var int $existingCount
 *
 * @created 2026-05-15
 * @author Arif
 */
use App\Model\Entity\RoutineTemplate;

$this->assign('title', __('Apply Template - {0}', $class->name));
$stages = RoutineTemplate::getCambridgeStages();
?>

<section class="admin-section">
    <header class="admin-section__header">
        <div>
            <nav class="breadcrumb">
                <?= $this->Html->link(__('Class Routine'), ['action' => 'index']) ?>
                / <?= $this->Html->link(h($class->name), ['action' => 'edit', $class->id]) ?>
                / <?= __('Apply Template') ?>
            </nav>
            <h1><?= __('Apply Cambridge Template') ?></h1>
            <p class="text-muted"><?= __('Select a pre-built curriculum template to auto-fill the timetable') ?></p>
        </div>
    </header>

    <div class="apply-template-layout">
        <div class="class-info-card">
            <h3><?= h($class->name) ?><?= $class->section ? ' - ' . h($class->section) : '' ?></h3>
            <dl>
                <dt><?= __('Grade Level') ?></dt>
                <dd><?= h($class->grade_level) ?></dd>
                <dt><?= __('Academic Year') ?></dt>
                <dd><?= h($class->academic_year) ?></dd>
                <dt><?= __('Cambridge Stage') ?></dt>
                <dd>
                    <?php if ($cambridgeStage): ?>
                        <span class="stage-badge stage-badge--<?= h($cambridgeStage) ?>">
                            <?= h($stages[$cambridgeStage] ?? $cambridgeStage) ?>
                        </span>
                    <?php else: ?>
                        <span class="text-muted"><?= __('Not mapped to Cambridge stage') ?></span>
                    <?php endif; ?>
                </dd>
                <dt><?= __('Teaching Periods/Day') ?></dt>
                <dd><?= count(array_filter($periods, fn($p) => !$p->is_break)) ?> <?= __('periods') ?></dd>
            </dl>

            <?php if ($existingCount > 0): ?>
                <div class="warning-box">
                    <strong><?= __('Warning:') ?></strong>
                    <?= __('This class already has {0} routine entries.', $existingCount) ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="templates-section">
            <?= $this->Form->create(null, ['class' => 'apply-template-form']) ?>

            <?php if (!empty($templates)): ?>
                <h3><?= __('Recommended Templates') ?></h3>
                <p class="text-muted"><?= __('Templates matching the Cambridge stage for this grade level:') ?></p>

                <div class="template-cards">
                    <?php foreach ($templates as $template): ?>
                        <label class="template-card">
                            <input type="radio" name="template_id" value="<?= $template->id ?>">
                            <div class="template-card__content">
                                <h4><?= h($template->name) ?></h4>
                                <p class="template-card__desc"><?= h($template->description) ?></p>
                                <div class="template-card__stats">
                                    <span><?= count($template->routine_template_items) ?> <?= __('subjects') ?></span>
                                    <span><?= $template->total_periods ?> <?= __('periods/week') ?></span>
                                </div>
                                <div class="template-card__items">
                                    <?php
                                    $items = array_slice($template->routine_template_items, 0, 6);
                                    foreach ($items as $item):
                                    ?>
                                        <span class="template-item <?= $item->is_required ? 'template-item--required' : '' ?>">
                                            <?= h($item->subject->name ?? 'N/A') ?> (<?= $item->periods_per_week ?>)
                                        </span>
                                    <?php endforeach; ?>
                                    <?php if (count($template->routine_template_items) > 6): ?>
                                        <span class="template-item template-item--more">
                                            +<?= count($template->routine_template_items) - 6 ?> <?= __('more') ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </label>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($allTemplates)): ?>
                <h3><?= __('All Available Templates') ?></h3>
                <p class="text-muted"><?= __('Browse templates from all Cambridge stages:') ?></p>

                <?php foreach ($allTemplates as $stageKey => $stageTemplates): ?>
                    <details class="template-group" <?= $stageKey === $cambridgeStage ? 'open' : '' ?>>
                        <summary class="template-group__header">
                            <span class="stage-badge stage-badge--<?= h($stageKey) ?>">
                                <?= h($stages[$stageKey] ?? $stageKey) ?>
                            </span>
                            <span class="template-group__count"><?= count($stageTemplates) ?> <?= __('templates') ?></span>
                        </summary>
                        <div class="template-cards template-cards--compact">
                            <?php foreach ($stageTemplates as $template): ?>
                                <label class="template-card template-card--compact">
                                    <input type="radio" name="template_id" value="<?= $template->id ?>">
                                    <div class="template-card__content">
                                        <h4><?= h($template->name) ?></h4>
                                        <div class="template-card__stats">
                                            <span><?= count($template->routine_template_items) ?> <?= __('subjects') ?></span>
                                            <span><?= $template->total_periods ?> <?= __('periods/week') ?></span>
                                        </div>
                                    </div>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </details>
                <?php endforeach; ?>
            <?php endif; ?>

            <?php if ($existingCount > 0): ?>
                <div class="form-group form-group--checkbox">
                    <label>
                        <input type="checkbox" name="clear_existing" value="1">
                        <?= __('Clear existing routine entries before applying template') ?>
                    </label>
                    <small class="form-hint"><?= __('If unchecked, template will only fill empty slots') ?></small>
                </div>
            <?php endif; ?>

            <div class="form-actions">
                <?= $this->Html->link(__('Cancel'), ['action' => 'edit', $class->id], ['class' => 'btn btn--ghost-dark']) ?>
                <?= $this->Form->button(__('Apply Template'), ['class' => 'btn btn--solid']) ?>
            </div>

            <?= $this->Form->end() ?>
        </div>
    </div>
</section>

<style>
.apply-template-layout {
    display: grid;
    grid-template-columns: 280px 1fr;
    gap: 24px;
    margin-top: 20px;
}

@media (max-width: 900px) {
    .apply-template-layout {
        grid-template-columns: 1fr;
    }
}

.class-info-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    padding: 20px;
    height: fit-content;
    position: sticky;
    top: 20px;
}

.class-info-card h3 {
    margin: 0 0 16px 0;
    font-size: 1.1rem;
    color: var(--gray-800);
}

.class-info-card dl {
    display: grid;
    grid-template-columns: auto 1fr;
    gap: 8px 12px;
    margin: 0;
}

.class-info-card dt {
    font-size: 0.85rem;
    color: var(--gray-500);
}

.class-info-card dd {
    margin: 0;
    font-weight: 500;
    color: var(--gray-800);
}

.warning-box {
    margin-top: 16px;
    padding: 12px;
    background: var(--amber-50);
    border-left: 3px solid var(--amber-500);
    border-radius: 4px;
    font-size: 0.85rem;
    color: var(--amber-800);
}

.templates-section h3 {
    margin: 0 0 8px 0;
    font-size: 1rem;
    color: var(--gray-800);
}

.templates-section h3:not(:first-of-type) {
    margin-top: 32px;
}

.template-cards {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 16px;
    margin-top: 16px;
}

.template-cards--compact {
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
}

.template-card {
    display: block;
    cursor: pointer;
}

.template-card input[type="radio"] {
    position: absolute;
    opacity: 0;
}

.template-card__content {
    background: white;
    border: 2px solid var(--gray-200);
    border-radius: 12px;
    padding: 16px;
    transition: all 0.2s;
}

.template-card input[type="radio"]:checked + .template-card__content {
    border-color: var(--blue-500);
    background: var(--blue-50);
}

.template-card:hover .template-card__content {
    border-color: var(--blue-300);
}

.template-card__content h4 {
    margin: 0 0 8px 0;
    font-size: 0.95rem;
    color: var(--gray-800);
}

.template-card__desc {
    font-size: 0.85rem;
    color: var(--gray-600);
    margin: 0 0 12px 0;
    line-height: 1.4;
}

.template-card__stats {
    display: flex;
    gap: 16px;
    font-size: 0.8rem;
    color: var(--gray-500);
    margin-bottom: 12px;
}

.template-card__items {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
}

.template-item {
    display: inline-block;
    padding: 3px 8px;
    font-size: 0.75rem;
    background: var(--gray-100);
    color: var(--gray-700);
    border-radius: 4px;
}

.template-item--required {
    background: var(--blue-100);
    color: var(--blue-700);
}

.template-item--more {
    background: var(--gray-200);
    font-style: italic;
}

.template-card--compact .template-card__content {
    padding: 12px;
}

.template-card--compact .template-card__content h4 {
    font-size: 0.9rem;
    margin-bottom: 6px;
}

.template-card--compact .template-card__stats {
    margin-bottom: 0;
}

.template-group {
    margin-top: 12px;
    border: 1px solid var(--gray-200);
    border-radius: 8px;
    overflow: hidden;
}

.template-group__header {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 16px;
    background: var(--gray-50);
    cursor: pointer;
    user-select: none;
}

.template-group__header:hover {
    background: var(--gray-100);
}

.template-group__count {
    font-size: 0.85rem;
    color: var(--gray-500);
}

.template-group[open] > .template-cards {
    padding: 16px;
    border-top: 1px solid var(--gray-200);
}

.stage-badge {
    display: inline-block;
    padding: 4px 10px;
    font-size: 0.75rem;
    font-weight: 500;
    border-radius: 12px;
}

.stage-badge--primary {
    background: var(--green-100);
    color: var(--green-700);
}

.stage-badge--lower_secondary {
    background: var(--blue-100);
    color: var(--blue-700);
}

.stage-badge--igcse {
    background: var(--purple-100);
    color: var(--purple-700);
}

.form-group--checkbox {
    margin-top: 24px;
    padding: 16px;
    background: var(--gray-50);
    border-radius: 8px;
}

.form-group--checkbox label {
    display: flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
    font-weight: 500;
}

.form-hint {
    display: block;
    margin-top: 6px;
    margin-left: 24px;
    font-size: 0.85rem;
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
</style>
