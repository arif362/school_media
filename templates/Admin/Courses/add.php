<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Course $course
 * @var array $classes
 * @var array $subjects
 * @var array $teachers
 * @var array $academicYears
 * @var array $terms
 */
$this->assign('title', __('Add Course'));

// Pre-select subject if passed in query
$preSelectedSubject = $this->request->getQuery('subject_id');
?>

<section class="admin-section">
    <header class="admin-section__header">
        <div>
            <nav class="breadcrumb">
                <?= $this->Html->link(__('Courses'), ['action' => 'index']) ?> / <?= __('Add') ?>
            </nav>
            <h1><?= __('Add Course') ?></h1>
        </div>
    </header>

    <div class="form-card">
        <?= $this->Form->create($course, ['class' => 'admin-form']) ?>

        <div class="form-row">
            <div class="form-group">
                <?= $this->Form->control('subject_id', [
                    'type' => 'select',
                    'options' => $subjects,
                    'empty' => __('-- Select Subject --'),
                    'label' => __('Subject'),
                    'class' => 'form-control',
                    'required' => true,
                    'value' => $preSelectedSubject,
                ]) ?>
            </div>
            <div class="form-group">
                <?= $this->Form->control('class_id', [
                    'type' => 'select',
                    'options' => $classes,
                    'empty' => __('-- Select Class --'),
                    'label' => __('Class'),
                    'class' => 'form-control',
                    'required' => true,
                ]) ?>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <?= $this->Form->control('teacher_id', [
                    'type' => 'select',
                    'options' => $teachers,
                    'empty' => __('-- Assign Teacher --'),
                    'label' => __('Teacher'),
                    'class' => 'form-control',
                ]) ?>
            </div>
            <div class="form-group">
                <?= $this->Form->control('name', [
                    'label' => __('Custom Course Name (Optional)'),
                    'class' => 'form-control',
                    'placeholder' => __('Leave blank to use Subject - Class'),
                ]) ?>
            </div>
        </div>

        <div class="form-row">
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
            <div class="form-group">
                <?= $this->Form->control('term', [
                    'type' => 'select',
                    'options' => $terms,
                    'empty' => __('-- Select Term --'),
                    'label' => __('Term'),
                    'class' => 'form-control',
                ]) ?>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <?= $this->Form->control('max_students', [
                    'type' => 'number',
                    'label' => __('Maximum Students'),
                    'class' => 'form-control',
                    'default' => 40,
                    'min' => 1,
                ]) ?>
            </div>
            <div class="form-group">
                <?= $this->Form->control('is_active', [
                    'type' => 'checkbox',
                    'label' => __('Active'),
                    'default' => true,
                ]) ?>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group form-group--full">
                <?= $this->Form->control('syllabus', [
                    'type' => 'textarea',
                    'label' => __('Syllabus / Course Outline'),
                    'class' => 'form-control',
                    'rows' => 5,
                    'placeholder' => __('Enter the course syllabus or outline...'),
                ]) ?>
            </div>
        </div>

        <div class="form-actions">
            <?= $this->Html->link(__('Cancel'), ['action' => 'index'], ['class' => 'btn btn--ghost-dark']) ?>
            <?= $this->Form->button(__('Create Course'), ['class' => 'btn btn--solid']) ?>
        </div>

        <?= $this->Form->end() ?>
    </div>
</section>
