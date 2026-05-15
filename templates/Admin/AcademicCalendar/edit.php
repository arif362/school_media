<?php
/**
 * Academic Calendar Edit Template
 *
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\AcademicEvent $event
 * @var array $academicYears
 * @var array $eventTypes
 *
 * @created 2026-05-15
 * @author Arif
 */
$this->assign('title', __('Edit Event'));
?>

<section class="admin-section">
    <header class="admin-section__header">
        <div>
            <nav class="breadcrumb">
                <?= $this->Html->link(__('Academic Calendar'), ['action' => 'index']) ?> / <?= __('Edit') ?>
            </nav>
            <h1><?= __('Edit Event') ?></h1>
        </div>
    </header>

    <div class="form-card">
        <?= $this->Form->create($event, ['class' => 'admin-form']) ?>

        <div class="form-row">
            <div class="form-group form-group--full">
                <?= $this->Form->control('title', [
                    'label' => __('Event Title'),
                    'class' => 'form-control',
                    'required' => true,
                ]) ?>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <?= $this->Form->control('event_type', [
                    'type' => 'select',
                    'options' => $eventTypes,
                    'label' => __('Event Type'),
                    'class' => 'form-control',
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
                ]) ?>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <?= $this->Form->control('start_date', [
                    'type' => 'date',
                    'label' => __('Start Date'),
                    'class' => 'form-control',
                    'required' => true,
                ]) ?>
            </div>
            <div class="form-group">
                <?= $this->Form->control('end_date', [
                    'type' => 'date',
                    'label' => __('End Date (optional)'),
                    'class' => 'form-control',
                ]) ?>
                <small class="form-hint"><?= __('Leave blank for single-day events') ?></small>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group form-group--full">
                <?= $this->Form->control('description', [
                    'type' => 'textarea',
                    'label' => __('Description (optional)'),
                    'class' => 'form-control',
                    'rows' => 3,
                ]) ?>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <?= $this->Form->control('is_active', [
                    'type' => 'checkbox',
                    'label' => __('Active'),
                ]) ?>
            </div>
        </div>

        <div class="form-actions">
            <?= $this->Html->link(__('Cancel'), ['action' => 'index', '?' => ['academic_year' => $event->academic_year]], ['class' => 'btn btn--ghost-dark']) ?>
            <?= $this->Form->button(__('Update Event'), ['class' => 'btn btn--solid']) ?>
        </div>

        <?= $this->Form->end() ?>
    </div>
</section>

<style>
.form-hint {
    display: block;
    margin-top: 4px;
    font-size: 0.85rem;
    color: var(--gray-500);
}
</style>
