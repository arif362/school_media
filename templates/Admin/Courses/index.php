<?php
/**
 * @var \App\View\AppView $this
 * @var iterable $courses
 * @var array $classes
 * @var array $subjects
 * @var array $teachers
 * @var array $academicYears
 */
$this->assign('title', __('Courses'));
?>

<section class="admin-section">
    <header class="admin-section__header">
        <div>
            <h1><?= __('Courses') ?></h1>
            <p class="text-muted"><?= __('Manage course offerings for classes') ?></p>
        </div>
        <div class="header-actions">
            <?= $this->Html->link(__('Subjects'), ['controller' => 'Subjects', 'action' => 'index'], ['class' => 'btn btn--ghost-dark']) ?>
            <?= $this->Html->link(__('+ Add Course'), ['action' => 'add'], ['class' => 'btn btn--solid']) ?>
        </div>
    </header>

    <div class="filters-bar">
        <?= $this->Form->create(null, ['type' => 'get', 'class' => 'filters-form']) ?>
        <div class="filters-row">
            <div class="filter-group">
                <label><?= __('Class') ?></label>
                <?= $this->Form->control('class_id', [
                    'type' => 'select',
                    'options' => $classes,
                    'empty' => __('All Classes'),
                    'value' => $classId ?? null,
                    'label' => false,
                    'class' => 'form-control',
                ]) ?>
            </div>
            <div class="filter-group">
                <label><?= __('Subject') ?></label>
                <?= $this->Form->control('subject_id', [
                    'type' => 'select',
                    'options' => $subjects,
                    'empty' => __('All Subjects'),
                    'value' => $subjectId ?? null,
                    'label' => false,
                    'class' => 'form-control',
                ]) ?>
            </div>
            <div class="filter-group">
                <label><?= __('Teacher') ?></label>
                <?= $this->Form->control('teacher_id', [
                    'type' => 'select',
                    'options' => $teachers,
                    'empty' => __('All Teachers'),
                    'value' => $teacherId ?? null,
                    'label' => false,
                    'class' => 'form-control',
                ]) ?>
            </div>
            <div class="filter-group">
                <label><?= __('Year') ?></label>
                <?= $this->Form->control('academic_year', [
                    'type' => 'select',
                    'options' => $academicYears,
                    'empty' => __('All Years'),
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

    <?php if ($courses->isEmpty()): ?>
        <div class="empty-state">
            <div class="empty-state__icon">&#128218;</div>
            <h3><?= __('No courses found') ?></h3>
            <p><?= __('Start by creating courses for your classes.') ?></p>
            <?= $this->Html->link(__('Add Course'), ['action' => 'add'], ['class' => 'btn btn--solid']) ?>
        </div>
    <?php else: ?>
        <div class="card">
            <table class="data-table">
                <thead>
                    <tr>
                        <th><?= __('Subject') ?></th>
                        <th><?= __('Class') ?></th>
                        <th><?= __('Teacher') ?></th>
                        <th><?= __('Year') ?></th>
                        <th><?= __('Term') ?></th>
                        <th><?= __('Status') ?></th>
                        <th><?= __('Actions') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($courses as $course): ?>
                        <tr>
                            <td>
                                <div class="course-subject">
                                    <span class="subject-code"><?= h($course->subject->code) ?></span>
                                    <strong><?= h($course->subject->name) ?></strong>
                                </div>
                            </td>
                            <td>
                                <?= h($course->class->name) ?>
                                <?= $course->class->section ? '- ' . h($course->class->section) : '' ?>
                                <small class="text-muted"><?= h($course->class->grade_level) ?></small>
                            </td>
                            <td>
                                <?php if ($course->teacher): ?>
                                    <?= h($course->teacher->name) ?>
                                <?php else: ?>
                                    <span class="text-muted"><?= __('Not assigned') ?></span>
                                <?php endif; ?>
                            </td>
                            <td><?= h($course->academic_year) ?></td>
                            <td><?= $course->term ?: '-' ?></td>
                            <td>
                                <span class="status-badge <?= $course->is_active ? 'status-badge--published' : 'status-badge--draft' ?>">
                                    <?= $course->is_active ? __('Active') : __('Inactive') ?>
                                </span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <?= $this->Html->link(__('View'), ['action' => 'view', $course->id], ['class' => 'action-btn action-btn--view']) ?>
                                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $course->id], ['class' => 'action-btn action-btn--edit']) ?>
                                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $course->id], [
                                        'class' => 'action-btn action-btn--delete',
                                        'confirm' => __('Are you sure you want to delete this course?'),
                                    ]) ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="pagination-wrapper">
            <?= $this->Paginator->counter(__('Page {{page}} of {{pages}}')) ?>
            <div class="pagination">
                <?= $this->Paginator->prev(__('Previous')) ?>
                <?= $this->Paginator->numbers() ?>
                <?= $this->Paginator->next(__('Next')) ?>
            </div>
        </div>
    <?php endif; ?>
</section>
