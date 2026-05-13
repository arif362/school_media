<?php
/**
 * @var \App\View\AppView $this
 * @var \Authentication\IdentityInterface $user
 * @var array $stats
 * @var int $activeUsers
 * @var int $inactiveUsers
 * @var \Cake\Collection\CollectionInterface $recentTeachers
 * @var \Cake\Collection\CollectionInterface $recentStudents
 * @var \Cake\Collection\CollectionInterface $recentCourses
 * @var array $todayAttendance
 * @var array $enrollmentStats
 */

$this->assign('title', __('Admin Dashboard'));
$this->assign('dashboardTitle', __('Welcome back, {0}', h($user->name ?? 'Admin')));
$this->assign('dashboardSubtitle', __('Manage your school from the central dashboard.'));

$this->start('dashboardActions');
echo $this->Html->link(__('Add Teacher'), ['controller' => 'Users', 'action' => 'addTeacher'], ['class' => 'btn btn--outline']);
echo ' ';
echo $this->Html->link(__('Add Student'), ['controller' => 'Users', 'action' => 'addStudent'], ['class' => 'btn btn--solid']);
$this->end();
?>

<section class="admin-dashboard">
    <!-- Main Stats -->
    <div class="stats-grid stats-grid--4">
        <div class="stat-card stat-card--primary">
            <span class="stat-card__icon">&#128100;</span>
            <div class="stat-card__content">
                <span class="stat-card__value"><?= $stats['students'] ?></span>
                <span class="stat-card__label"><?= __('Students') ?></span>
            </div>
        </div>
        <div class="stat-card stat-card--info">
            <span class="stat-card__icon">&#128104;&#8205;&#127979;</span>
            <div class="stat-card__content">
                <span class="stat-card__value"><?= $stats['teachers'] ?></span>
                <span class="stat-card__label"><?= __('Teachers') ?></span>
            </div>
        </div>
        <div class="stat-card stat-card--success">
            <span class="stat-card__icon">&#127979;</span>
            <div class="stat-card__content">
                <span class="stat-card__value"><?= $stats['classes'] ?></span>
                <span class="stat-card__label"><?= __('Classes') ?></span>
            </div>
        </div>
        <div class="stat-card stat-card--warning">
            <span class="stat-card__icon">&#128218;</span>
            <div class="stat-card__content">
                <span class="stat-card__value"><?= $stats['courses'] ?></span>
                <span class="stat-card__label"><?= __('Courses') ?></span>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="quick-actions mt-4">
        <h3><?= __('Quick Actions') ?></h3>
        <div class="quick-actions-grid">
            <?= $this->Html->link('<span class="quick-action__icon">&#128100;</span><span>' . __('Add Student') . '</span>', ['controller' => 'Users', 'action' => 'addStudent'], ['class' => 'quick-action', 'escape' => false]) ?>
            <?= $this->Html->link('<span class="quick-action__icon">&#128104;&#8205;&#127979;</span><span>' . __('Add Teacher') . '</span>', ['controller' => 'Users', 'action' => 'addTeacher'], ['class' => 'quick-action', 'escape' => false]) ?>
            <?= $this->Html->link('<span class="quick-action__icon">&#127979;</span><span>' . __('Manage Classes') . '</span>', ['controller' => 'Classes', 'action' => 'index'], ['class' => 'quick-action', 'escape' => false]) ?>
            <?= $this->Html->link('<span class="quick-action__icon">&#128218;</span><span>' . __('Manage Courses') . '</span>', ['controller' => 'Courses', 'action' => 'index'], ['class' => 'quick-action', 'escape' => false]) ?>
            <?= $this->Html->link('<span class="quick-action__icon">&#128197;</span><span>' . __('Attendance') . '</span>', ['controller' => 'Attendance', 'action' => 'index'], ['class' => 'quick-action', 'escape' => false]) ?>
            <?= $this->Html->link('<span class="quick-action__icon">&#128230;</span><span>' . __('Import Students') . '</span>', ['controller' => 'Users', 'action' => 'importStudents'], ['class' => 'quick-action', 'escape' => false]) ?>
        </div>
    </div>

    <div class="admin-grid admin-grid--2 mt-4">
        <!-- Today's Attendance -->
        <div class="admin-card">
            <div class="admin-card__header">
                <h3><?= __("Today's Attendance") ?></h3>
                <?= $this->Html->link(__('View All'), ['controller' => 'Attendance', 'action' => 'index'], ['class' => 'btn btn--sm btn--outline']) ?>
            </div>
            <div class="admin-card__body">
                <div class="attendance-summary">
                    <div class="attendance-stat attendance-stat--present">
                        <span class="attendance-stat__icon">&#9989;</span>
                        <div>
                            <span class="attendance-stat__value"><?= $todayAttendance['present'] ?></span>
                            <span class="attendance-stat__label"><?= __('Present') ?></span>
                        </div>
                    </div>
                    <div class="attendance-stat attendance-stat--absent">
                        <span class="attendance-stat__icon">&#10060;</span>
                        <div>
                            <span class="attendance-stat__value"><?= $todayAttendance['absent'] ?></span>
                            <span class="attendance-stat__label"><?= __('Absent') ?></span>
                        </div>
                    </div>
                    <div class="attendance-stat attendance-stat--late">
                        <span class="attendance-stat__icon">&#9200;</span>
                        <div>
                            <span class="attendance-stat__value"><?= $todayAttendance['late'] ?></span>
                            <span class="attendance-stat__label"><?= __('Late') ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Enrollment Stats -->
        <div class="admin-card">
            <div class="admin-card__header">
                <h3><?= __('Course Enrollments') ?></h3>
            </div>
            <div class="admin-card__body">
                <div class="enrollment-summary">
                    <div class="enrollment-stat">
                        <span class="enrollment-stat__value"><?= $enrollmentStats['enrolled'] ?></span>
                        <span class="enrollment-stat__label"><?= __('Active Enrollments') ?></span>
                    </div>
                    <div class="enrollment-stat">
                        <span class="enrollment-stat__value"><?= $enrollmentStats['completed'] ?></span>
                        <span class="enrollment-stat__label"><?= __('Completed') ?></span>
                    </div>
                    <div class="enrollment-stat">
                        <span class="enrollment-stat__value"><?= $stats['subjects'] ?></span>
                        <span class="enrollment-stat__label"><?= __('Subjects') ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="admin-grid admin-grid--2 mt-4">
        <!-- Recent Teachers -->
        <div class="admin-card">
            <div class="admin-card__header">
                <h3><?= __('Recent Teachers') ?></h3>
                <?= $this->Html->link(__('View All'), ['controller' => 'Users', 'action' => 'teachers'], ['class' => 'btn btn--sm btn--outline']) ?>
            </div>
            <div class="admin-card__body">
                <?php if ($recentTeachers->count() > 0): ?>
                    <div class="user-list">
                        <?php foreach ($recentTeachers as $teacher): ?>
                            <div class="user-list-item">
                                <div class="user-info">
                                    <span class="user-avatar user-avatar--teacher"><?= strtoupper(substr($teacher->name, 0, 1)) ?></span>
                                    <div>
                                        <strong><?= h($teacher->name) ?></strong>
                                        <br><small class="text-muted"><?= h($teacher->email) ?></small>
                                    </div>
                                </div>
                                <small class="text-muted"><?= $teacher->created->format('M j') ?></small>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="text-muted"><?= __('No teachers added yet.') ?></p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Recent Students -->
        <div class="admin-card">
            <div class="admin-card__header">
                <h3><?= __('Recent Students') ?></h3>
                <?= $this->Html->link(__('View All'), ['controller' => 'Users', 'action' => 'students'], ['class' => 'btn btn--sm btn--outline']) ?>
            </div>
            <div class="admin-card__body">
                <?php if ($recentStudents->count() > 0): ?>
                    <div class="user-list">
                        <?php foreach ($recentStudents as $student): ?>
                            <div class="user-list-item">
                                <div class="user-info">
                                    <span class="user-avatar user-avatar--student"><?= strtoupper(substr($student->name, 0, 1)) ?></span>
                                    <div>
                                        <strong><?= h($student->name) ?></strong>
                                        <?php if ($student->grade_level): ?>
                                            <br><small class="text-muted"><?= h($student->grade_level) ?></small>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <small class="text-muted"><?= $student->created->format('M j') ?></small>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="text-muted"><?= __('No students added yet.') ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Recent Courses -->
    <div class="admin-card mt-4">
        <div class="admin-card__header">
            <h3><?= __('Active Courses') ?></h3>
            <?= $this->Html->link(__('View All'), ['controller' => 'Courses', 'action' => 'index'], ['class' => 'btn btn--sm btn--outline']) ?>
        </div>
        <div class="admin-card__body">
            <?php if ($recentCourses->count() > 0): ?>
                <div class="table-responsive">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th><?= __('Course') ?></th>
                                <th><?= __('Class') ?></th>
                                <th><?= __('Teacher') ?></th>
                                <th><?= __('Year') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recentCourses as $course): ?>
                                <tr>
                                    <td>
                                        <strong><?= h($course->subject->name ?? '-') ?></strong>
                                        <br><small class="text-muted"><?= h($course->subject->code ?? '') ?></small>
                                    </td>
                                    <td><?= h($course->class->name ?? '-') ?><?= $course->class && $course->class->section ? ' - ' . h($course->class->section) : '' ?></td>
                                    <td><?= h($course->teacher->name ?? __('Unassigned')) ?></td>
                                    <td><?= h($course->academic_year) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="text-muted"><?= __('No active courses yet.') ?></p>
            <?php endif; ?>
        </div>
    </div>

    <!-- System Overview -->
    <div class="admin-card mt-4">
        <div class="admin-card__header">
            <h3><?= __('System Overview') ?></h3>
        </div>
        <div class="admin-card__body">
            <div class="system-stats">
                <div class="system-stat">
                    <span class="system-stat__label"><?= __('Total Users') ?></span>
                    <span class="system-stat__value"><?= $stats['totalUsers'] ?></span>
                </div>
                <div class="system-stat">
                    <span class="system-stat__label"><?= __('Active Users') ?></span>
                    <span class="system-stat__value text-success"><?= $activeUsers ?></span>
                </div>
                <div class="system-stat">
                    <span class="system-stat__label"><?= __('Inactive Users') ?></span>
                    <span class="system-stat__value text-danger"><?= $inactiveUsers ?></span>
                </div>
                <div class="system-stat">
                    <span class="system-stat__label"><?= __('Published Posts') ?></span>
                    <span class="system-stat__value"><?= $stats['posts'] ?></span>
                </div>
            </div>
        </div>
    </div>
</section>
