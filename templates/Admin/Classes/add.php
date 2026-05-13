<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\SchoolClass $class
 * @var array $teachers
 * @var array $gradeLevels
 * @var array $sections
 * @var array $academicYears
 */
$this->assign('title', __('Add Class'));
?>

<section class="admin-section">
    <header class="admin-section__header">
        <div>
            <nav class="breadcrumb">
                <?= $this->Html->link(__('Classes'), ['action' => 'index']) ?> / <?= __('Add') ?>
            </nav>
            <h1><?= __('Add New Class') ?></h1>
        </div>
    </header>

    <div class="form-card">
        <?= $this->Form->create($class, ['class' => 'class-form']) ?>
            <div class="form-section">
                <h3><?= __('Class Information') ?></h3>

                <div class="form-row">
                    <div class="form-group">
                        <?= $this->Form->control('name', [
                            'label' => __('Class Name'),
                            'class' => 'form-control',
                            'placeholder' => __('e.g., Grade 5'),
                            'required' => true,
                        ]) ?>
                    </div>
                    <div class="form-group">
                        <?= $this->Form->control('section', [
                            'label' => __('Section'),
                            'options' => $sections,
                            'empty' => __('-- No Section --'),
                            'class' => 'form-control',
                        ]) ?>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <?= $this->Form->control('grade_level', [
                            'label' => __('Grade Level'),
                            'options' => $gradeLevels,
                            'class' => 'form-control',
                            'required' => true,
                        ]) ?>
                    </div>
                    <div class="form-group">
                        <?= $this->Form->control('academic_year', [
                            'label' => __('Academic Year'),
                            'options' => $academicYears,
                            'class' => 'form-control',
                            'required' => true,
                        ]) ?>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <?= $this->Form->control('class_teacher_id', [
                            'label' => __('Class Teacher'),
                            'options' => $teachers,
                            'empty' => __('-- Select Teacher --'),
                            'class' => 'form-control',
                        ]) ?>
                    </div>
                    <div class="form-group">
                        <?= $this->Form->control('capacity', [
                            'label' => __('Capacity'),
                            'type' => 'number',
                            'class' => 'form-control',
                            'default' => 30,
                            'min' => 1,
                        ]) ?>
                    </div>
                </div>

                <div class="form-group">
                    <?= $this->Form->control('is_active', [
                        'label' => __('Active'),
                        'type' => 'checkbox',
                        'default' => true,
                    ]) ?>
                </div>
            </div>

            <div class="form-actions">
                <?= $this->Html->link(__('Cancel'), ['action' => 'index'], ['class' => 'btn btn--ghost-dark']) ?>
                <?= $this->Form->button(__('Create Class'), ['class' => 'btn btn--solid']) ?>
            </div>
        <?= $this->Form->end() ?>
    </div>
</section>
