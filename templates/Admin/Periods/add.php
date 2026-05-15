<?php
/**
 * Periods Add Template
 *
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Period $period
 * @var array $academicYears
 *
 * @created 2026-05-15
 * @author Arif
 */
$this->assign('title', __('Add Period'));
?>

<section class="admin-section">
    <header class="admin-section__header">
        <div>
            <nav class="breadcrumb">
                <?= $this->Html->link(__('Periods'), ['action' => 'index']) ?> / <?= __('Add') ?>
            </nav>
            <h1><?= __('Add Period') ?></h1>
        </div>
    </header>

    <div class="form-card">
        <?= $this->Form->create($period, ['class' => 'admin-form']) ?>

        <div class="form-row">
            <div class="form-group">
                <?= $this->Form->control('name', [
                    'label' => __('Period Name'),
                    'class' => 'form-control',
                    'placeholder' => __('e.g., Period 1, Morning Break, Lunch'),
                    'required' => true,
                ]) ?>
            </div>
            <div class="form-group">
                <?= $this->Form->control('academic_year', [
                    'type' => 'select',
                    'options' => $academicYears,
                    'label' => __('Academic Year'),
                    'class' => 'form-control',
                    'required' => true,
                    'default' => date('Y') . '-' . (date('Y') + 1),
                ]) ?>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <?= $this->Form->control('start_time', [
                    'type' => 'time',
                    'label' => __('Start Time'),
                    'class' => 'form-control',
                    'required' => true,
                ]) ?>
            </div>
            <div class="form-group">
                <?= $this->Form->control('end_time', [
                    'type' => 'time',
                    'label' => __('End Time'),
                    'class' => 'form-control',
                    'required' => true,
                ]) ?>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <?= $this->Form->control('order_num', [
                    'type' => 'number',
                    'label' => __('Order Number'),
                    'class' => 'form-control',
                    'min' => 1,
                    'placeholder' => __('Leave blank for auto-assign'),
                ]) ?>
                <small class="form-hint"><?= __('Determines the display order of periods. Lower numbers appear first.') ?></small>
            </div>
            <div class="form-group">
                <div class="checkbox-group">
                    <?= $this->Form->control('is_break', [
                        'type' => 'checkbox',
                        'label' => __('This is a break period (not a class)'),
                    ]) ?>
                    <?= $this->Form->control('is_active', [
                        'type' => 'checkbox',
                        'label' => __('Active'),
                        'default' => true,
                    ]) ?>
                </div>
            </div>
        </div>

        <div class="form-actions">
            <?= $this->Html->link(__('Cancel'), ['action' => 'index'], ['class' => 'btn btn--ghost-dark']) ?>
            <?= $this->Form->button(__('Create Period'), ['class' => 'btn btn--solid']) ?>
        </div>

        <?= $this->Form->end() ?>
    </div>
</section>

<style>
.checkbox-group {
    display: flex;
    flex-direction: column;
    gap: 12px;
    padding-top: 28px;
}
.form-hint {
    display: block;
    margin-top: 4px;
    font-size: 0.85rem;
    color: var(--gray-500);
}
</style>
