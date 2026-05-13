<?php
/**
 * @var \App\View\AppView $this
 * @var \Cake\Collection\CollectionInterface $courses
 * @var array $courseStats
 * @var int $totalStudents
 */
$this->assign('title', __('Teacher Dashboard'));
$this->assign('dashboardTitle', __('Welcome Back!'));
$this->assign('dashboardSubtitle', __('Manage your courses and track student progress.'));
?>

<div class="teacher-dashboard">
    <div class="stats-grid stats-grid--3">
        <div class="stat-card stat-card--primary">
            <span class="stat-card__icon">&#128218;</span>
            <div class="stat-card__content">
                <span class="stat-card__value"><?= $courses->count() ?></span>
                <span class="stat-card__label"><?= __('Active Courses') ?></span>
            </div>
        </div>
        <div class="stat-card stat-card--info">
            <span class="stat-card__icon">&#128101;</span>
            <div class="stat-card__content">
                <span class="stat-card__value"><?= $totalStudents ?></span>
                <span class="stat-card__label"><?= __('Total Students') ?></span>
            </div>
        </div>
        <div class="stat-card stat-card--success">
            <span class="stat-card__icon">&#127979;</span>
            <div class="stat-card__content">
                <span class="stat-card__value"><?= $courses->filter(fn($c) => $c->class)->groupBy(fn($c) => $c->class_id)->count() ?></span>
                <span class="stat-card__label"><?= __('Classes') ?></span>
            </div>
        </div>
    </div>

    <div class="admin-card mt-4">
        <div class="admin-card__header">
            <h3><?= __('My Courses') ?></h3>
            <?= $this->Html->link(__('View All'), ['controller' => 'Courses', 'action' => 'index'], ['class' => 'btn btn--sm btn--outline']) ?>
        </div>
        <div class="admin-card__body">
            <?php if ($courses->count() > 0): ?>
                <div class="course-grid">
                    <?php foreach ($courses as $course): ?>
                        <div class="course-card">
                            <div class="course-card__header">
                                <span class="course-card__icon">&#128218;</span>
                                <div class="course-card__info">
                                    <h4><?= h($course->subject->name ?? __('Unknown Subject')) ?></h4>
                                    <p class="text-muted"><?= h($course->class->name ?? '') ?><?= $course->class && $course->class->section ? ' - ' . h($course->class->section) : '' ?></p>
                                </div>
                            </div>
                            <div class="course-card__body">
                                <div class="course-card__stats">
                                    <div class="course-stat">
                                        <span class="course-stat__value"><?= $courseStats[$course->id]['enrolled'] ?? 0 ?></span>
                                        <span class="course-stat__label"><?= __('Students') ?></span>
                                    </div>
                                    <div class="course-stat">
                                        <span class="course-stat__value"><?= h($course->academic_year) ?></span>
                                        <span class="course-stat__label"><?= __('Year') ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="course-card__footer">
                                <?= $this->Html->link(__('View Course'), ['controller' => 'Courses', 'action' => 'view', $course->id], ['class' => 'btn btn--sm btn--solid w-full']) ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <span class="empty-state__icon">&#128218;</span>
                    <h3><?= __('No Courses Assigned') ?></h3>
                    <p><?= __('You have not been assigned to any courses yet. Contact an administrator to get started.') ?></p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
