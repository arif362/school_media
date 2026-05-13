<?php
/**
 * @var \App\View\AppView $this
 * @var \Cake\Collection\CollectionInterface $enrollments
 * @var \Cake\Collection\CollectionInterface $activeEnrollments
 * @var \Cake\Collection\CollectionInterface $completedEnrollments
 * @var int $totalCourses
 * @var int $activeCourses
 * @var int $completedCourses
 * @var float|null $averageMarks
 */
$this->assign('title', __('My Courses'));
$this->assign('dashboardTitle', __('My Courses'));
$this->assign('dashboardSubtitle', __('View your enrolled courses and track your progress.'));
?>

<section class="student-courses">
    <div class="stats-grid stats-grid--4">
        <div class="stat-card stat-card--primary">
            <span class="stat-card__icon">&#128218;</span>
            <div class="stat-card__content">
                <span class="stat-card__value"><?= $totalCourses ?></span>
                <span class="stat-card__label"><?= __('Total Courses') ?></span>
            </div>
        </div>
        <div class="stat-card stat-card--info">
            <span class="stat-card__icon">&#128214;</span>
            <div class="stat-card__content">
                <span class="stat-card__value"><?= $activeCourses ?></span>
                <span class="stat-card__label"><?= __('Active') ?></span>
            </div>
        </div>
        <div class="stat-card stat-card--success">
            <span class="stat-card__icon">&#9989;</span>
            <div class="stat-card__content">
                <span class="stat-card__value"><?= $completedCourses ?></span>
                <span class="stat-card__label"><?= __('Completed') ?></span>
            </div>
        </div>
        <div class="stat-card stat-card--warning">
            <span class="stat-card__icon">&#128200;</span>
            <div class="stat-card__content">
                <span class="stat-card__value"><?= $averageMarks !== null ? number_format($averageMarks, 1) . '%' : '-' ?></span>
                <span class="stat-card__label"><?= __('Average') ?></span>
            </div>
        </div>
    </div>

    <?php if ($activeEnrollments->count() > 0): ?>
        <div class="admin-card mt-4">
            <div class="admin-card__header">
                <h3><?= __('Currently Enrolled') ?> (<?= $activeEnrollments->count() ?>)</h3>
            </div>
            <div class="admin-card__body">
                <div class="course-grid">
                    <?php foreach ($activeEnrollments as $enrollment): ?>
                        <div class="course-card course-card--student">
                            <div class="course-card__header">
                                <span class="course-card__icon">&#128218;</span>
                                <div class="course-card__info">
                                    <h4><?= h($enrollment->course->subject->name ?? __('Unknown Subject')) ?></h4>
                                    <p class="text-muted">
                                        <?= h($enrollment->course->class->name ?? '') ?>
                                        <?= $enrollment->course->class && $enrollment->course->class->section ? ' - ' . h($enrollment->course->class->section) : '' ?>
                                    </p>
                                </div>
                            </div>
                            <div class="course-card__body">
                                <div class="course-card__meta">
                                    <span><strong><?= __('Teacher:') ?></strong> <?= h($enrollment->course->teacher->name ?? __('TBA')) ?></span>
                                    <span><strong><?= __('Year:') ?></strong> <?= h($enrollment->course->academic_year) ?></span>
                                    <?php if ($enrollment->course->term): ?>
                                        <span><strong><?= __('Term:') ?></strong> <?= h($enrollment->course->term) ?></span>
                                    <?php endif; ?>
                                </div>
                                <?php if ($enrollment->grade || $enrollment->marks !== null): ?>
                                    <div class="course-card__grade">
                                        <?php if ($enrollment->grade): ?>
                                            <span class="grade-badge grade-badge--lg"><?= h($enrollment->grade) ?></span>
                                        <?php endif; ?>
                                        <?php if ($enrollment->marks !== null): ?>
                                            <span class="marks-display"><?= number_format($enrollment->marks, 1) ?>%</span>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="course-card__footer">
                                <?= $this->Html->link(__('View Details'), ['action' => 'view', $enrollment->id], ['class' => 'btn btn--sm btn--solid w-full']) ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($completedEnrollments->count() > 0): ?>
        <div class="admin-card mt-4">
            <div class="admin-card__header">
                <h3><?= __('Completed Courses') ?> (<?= $completedEnrollments->count() ?>)</h3>
            </div>
            <div class="admin-card__body">
                <div class="table-responsive">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th><?= __('Course') ?></th>
                                <th><?= __('Class') ?></th>
                                <th><?= __('Year') ?></th>
                                <th><?= __('Grade') ?></th>
                                <th><?= __('Marks') ?></th>
                                <th><?= __('Actions') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($completedEnrollments as $enrollment): ?>
                                <tr>
                                    <td>
                                        <strong><?= h($enrollment->course->subject->name ?? __('Unknown')) ?></strong>
                                    </td>
                                    <td>
                                        <?= h($enrollment->course->class->name ?? '-') ?>
                                    </td>
                                    <td><?= h($enrollment->course->academic_year) ?></td>
                                    <td>
                                        <?php if ($enrollment->grade): ?>
                                            <span class="grade-badge"><?= h($enrollment->grade) ?></span>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= $enrollment->marks !== null ? number_format($enrollment->marks, 1) . '%' : '-' ?></td>
                                    <td>
                                        <?= $this->Html->link(__('View'), ['action' => 'view', $enrollment->id], ['class' => 'btn btn--sm btn--ghost']) ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($enrollments->count() === 0): ?>
        <div class="empty-state mt-4">
            <span class="empty-state__icon">&#128218;</span>
            <h3><?= __('No Courses Yet') ?></h3>
            <p><?= __('You are not enrolled in any courses yet. Contact your class teacher or administrator for enrollment.') ?></p>
        </div>
    <?php endif; ?>
</section>
