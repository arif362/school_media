<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\SchoolClass $class
 * @var array $students
 */
$this->assign('title', __('Enroll Student'));
?>

<section class="admin-section">
    <header class="admin-section__header">
        <div>
            <nav class="breadcrumb">
                <?= $this->Html->link(__('Classes'), ['action' => 'index']) ?> /
                <?= $this->Html->link(h($class->name), ['action' => 'view', $class->id]) ?> /
                <?= __('Enroll Student') ?>
            </nav>
            <h1><?= __('Enroll Student to {0}', h($class->name)) ?></h1>
        </div>
    </header>

    <div class="form-card">
        <?= $this->Form->create(null, ['class' => 'admin-form']) ?>

        <div class="form-row">
            <div class="form-group form-group--full">
                <?= $this->Form->control('student_id', [
                    'type' => 'select',
                    'options' => $students,
                    'empty' => __('-- Select Student --'),
                    'label' => __('Student'),
                    'class' => 'form-control',
                    'required' => true,
                ]) ?>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <?= $this->Form->control('roll_number', [
                    'type' => 'text',
                    'label' => __('Roll Number'),
                    'class' => 'form-control',
                    'placeholder' => __('Optional - Auto-assigned if empty'),
                ]) ?>
            </div>
        </div>

        <div class="class-info-summary">
            <h4><?= __('Class Information') ?></h4>
            <div class="info-grid">
                <div class="info-item">
                    <span class="label"><?= __('Class') ?></span>
                    <span class="value"><?= h($class->name) ?> <?= $class->section ? '(' . h($class->section) . ')' : '' ?></span>
                </div>
                <div class="info-item">
                    <span class="label"><?= __('Grade Level') ?></span>
                    <span class="value"><?= h($class->grade_level) ?></span>
                </div>
                <div class="info-item">
                    <span class="label"><?= __('Academic Year') ?></span>
                    <span class="value"><?= h($class->academic_year) ?></span>
                </div>
                <div class="info-item">
                    <span class="label"><?= __('Capacity') ?></span>
                    <span class="value"><?= $class->capacity ?> <?= __('students') ?></span>
                </div>
            </div>
        </div>

        <?php if (empty($students)): ?>
            <div class="alert alert--warning">
                <p><?= __('No students available to enroll. All students are either already enrolled in this class or there are no students registered in the system.') ?></p>
            </div>
        <?php endif; ?>

        <div class="form-actions">
            <?= $this->Html->link(__('Cancel'), ['action' => 'view', $class->id], ['class' => 'btn btn--ghost-dark']) ?>
            <?= $this->Form->button(__('Enroll Student'), [
                'class' => 'btn btn--solid',
                'disabled' => empty($students),
            ]) ?>
        </div>

        <?= $this->Form->end() ?>
    </div>
</section>
