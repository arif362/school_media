<?php
/**
 * @var \App\View\AppView $this
 * @var \Cake\Collection\CollectionInterface $courses
 * @var array $courseStats
 * @var array $academicYears
 * @var string|null $academicYear
 * @var bool $showInactive
 */
$this->assign('title', __('My Courses'));
$this->assign('dashboardTitle', __('My Courses'));
$this->assign('dashboardSubtitle', __('View and manage your assigned courses.'));
?>

<section class="teacher-courses">
    <div class="filter-bar">
        <?= $this->Form->create(null, ['type' => 'get', 'class' => 'filter-form']) ?>
        <div class="filter-group">
            <?= $this->Form->control('academic_year', [
                'type' => 'select',
                'options' => $academicYears,
                'empty' => __('All Years'),
                'value' => $academicYear,
                'label' => false,
                'class' => 'form-control',
            ]) ?>
            <label class="checkbox-label">
                <input type="checkbox" name="show_inactive" value="1" <?= $showInactive ? 'checked' : '' ?>>
                <?= __('Show Inactive') ?>
            </label>
            <?= $this->Form->button(__('Filter'), ['class' => 'btn btn--outline btn--sm']) ?>
        </div>
        <?= $this->Form->end() ?>
    </div>

    <?php if ($courses->count() > 0): ?>
        <div class="table-responsive mt-4">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th><?= __('Course') ?></th>
                        <th><?= __('Class') ?></th>
                        <th><?= __('Year / Term') ?></th>
                        <th><?= __('Students') ?></th>
                        <th><?= __('Status') ?></th>
                        <th class="text-right"><?= __('Actions') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($courses as $course): ?>
                        <tr>
                            <td>
                                <strong><?= h($course->subject->name ?? __('Unknown')) ?></strong>
                                <?php if ($course->name): ?>
                                    <br><small class="text-muted"><?= h($course->name) ?></small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?= h($course->class->name ?? '-') ?>
                                <?= $course->class && $course->class->section ? ' - ' . h($course->class->section) : '' ?>
                            </td>
                            <td>
                                <?= h($course->academic_year) ?>
                                <?= $course->term ? '<br><small class="text-muted">' . h($course->term) . '</small>' : '' ?>
                            </td>
                            <td>
                                <?php
                                $stats = $courseStats[$course->id] ?? [];
                                $enrolled = $stats['enrolled'] ?? 0;
                                $completed = $stats['completed'] ?? 0;
                                ?>
                                <span class="badge badge--info"><?= $enrolled ?> <?= __('Enrolled') ?></span>
                                <?php if ($completed > 0): ?>
                                    <span class="badge badge--success"><?= $completed ?> <?= __('Completed') ?></span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($course->is_active): ?>
                                    <span class="badge badge--success"><?= __('Active') ?></span>
                                <?php else: ?>
                                    <span class="badge badge--secondary"><?= __('Inactive') ?></span>
                                <?php endif; ?>
                            </td>
                            <td class="text-right">
                                <div class="btn-group">
                                    <?= $this->Html->link(__('View'), ['action' => 'view', $course->id], ['class' => 'btn btn--sm btn--ghost']) ?>
                                    <?= $this->Html->link(__('Grades'), ['action' => 'updateGrades', $course->id], ['class' => 'btn btn--sm btn--outline']) ?>
                                    <?= $this->Html->link(__('Materials'), ['action' => 'materials', $course->id], ['class' => 'btn btn--sm btn--ghost']) ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="empty-state mt-4">
            <span class="empty-state__icon">&#128218;</span>
            <h3><?= __('No Courses Found') ?></h3>
            <p><?= __('No courses match your current filters, or you have not been assigned to any courses yet.') ?></p>
        </div>
    <?php endif; ?>
</section>
