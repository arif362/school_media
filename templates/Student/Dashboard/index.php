<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 * @var \Cake\ORM\ResultSet $recentPosts
 * @var array $attendanceSummary
 * @var array $monthlyAttendance
 * @var \Cake\ORM\ResultSet $recentAttendance
 * @var \App\Model\Entity\SchoolClass|null $studentClass
 * @var int $currentYear
 * @var int $currentMonth
 */
use App\Model\Entity\Attendance;

$this->assign('title', __('Student Dashboard'));

$monthNames = ['', 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
$firstName = explode(' ', $user->name)[0];
?>

<section class="admin-section student-dashboard-section">
    <!-- Welcome Header -->
    <header class="student-welcome-header">
        <div class="student-welcome-header__content">
            <h1><?= __('Welcome back, {0}!', h($firstName)) ?></h1>
            <p><?= __('Here\'s an overview of your academic progress and recent activities.') ?></p>
        </div>
        <div class="student-welcome-header__date">
            <span class="current-date"><?= date('l, F j, Y') ?></span>
        </div>
    </header>

    <!-- Stats Overview -->
    <div class="student-stats-row">
        <div class="student-stat-card student-stat-card--attendance">
            <div class="student-stat-card__icon student-stat-card__icon--cyan">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                    <line x1="16" y1="2" x2="16" y2="6"/>
                    <line x1="8" y1="2" x2="8" y2="6"/>
                    <line x1="3" y1="10" x2="21" y2="10"/>
                    <path d="M9 16l2 2 4-4"/>
                </svg>
            </div>
            <div class="student-stat-card__content">
                <span class="student-stat-card__value <?= $attendanceSummary['percentage'] >= 75 ? 'text-success' : ($attendanceSummary['percentage'] >= 50 ? 'text-warning' : 'text-danger') ?>"><?= $attendanceSummary['percentage'] ?>%</span>
                <span class="student-stat-card__label"><?= __('Attendance Rate') ?></span>
            </div>
        </div>

        <div class="student-stat-card">
            <div class="student-stat-card__icon student-stat-card__icon--green">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="20 6 9 17 4 12"/>
                </svg>
            </div>
            <div class="student-stat-card__content">
                <span class="student-stat-card__value"><?= $attendanceSummary['present'] ?></span>
                <span class="student-stat-card__label"><?= __('Days Present') ?></span>
            </div>
        </div>

        <div class="student-stat-card">
            <div class="student-stat-card__icon student-stat-card__icon--red">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="6" x2="6" y2="18"/>
                    <line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </div>
            <div class="student-stat-card__content">
                <span class="student-stat-card__value"><?= $attendanceSummary['absent'] ?></span>
                <span class="student-stat-card__label"><?= __('Days Absent') ?></span>
            </div>
        </div>

        <div class="student-stat-card">
            <div class="student-stat-card__icon student-stat-card__icon--amber">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"/>
                    <polyline points="12 6 12 12 16 14"/>
                </svg>
            </div>
            <div class="student-stat-card__content">
                <span class="student-stat-card__value"><?= $attendanceSummary['late'] ?></span>
                <span class="student-stat-card__label"><?= __('Late Arrivals') ?></span>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="student-dashboard-grid">
        <!-- Left Column -->
        <div class="student-dashboard-main">
            <!-- Profile Card -->
            <div class="student-card student-profile-card">
                <div class="student-card__header">
                    <h3><?= __('My Profile') ?></h3>
                    <?= $this->Html->link(__('Edit Profile'), ['controller' => 'Profile', 'action' => 'edit'], ['class' => 'btn btn--outline btn--sm']) ?>
                </div>
                <div class="student-card__body">
                    <div class="student-profile-info">
                        <div class="student-profile-avatar">
                            <?php if ($user->avatar): ?>
                                <img src="<?= $this->Url->image($user->avatar) ?>" alt="<?= h($user->name) ?>">
                            <?php else: ?>
                                <span class="avatar-initial"><?= strtoupper(substr($user->name, 0, 1)) ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="student-profile-details">
                            <h4><?= h($user->name) ?></h4>
                            <p class="student-profile-email"><?= h($user->email) ?></p>
                            <div class="student-profile-badges">
                                <?php if ($studentClass): ?>
                                    <span class="badge badge--primary"><?= h($studentClass->name) ?><?= $studentClass->section ? ' (' . h($studentClass->section) . ')' : '' ?></span>
                                <?php endif; ?>
                                <?php if ($user->grade_level): ?>
                                    <span class="badge badge--outline"><?= h($user->grade_level) ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php if (!$user->bio && !$user->phone): ?>
                        <div class="profile-completion-alert">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"/>
                                <line x1="12" y1="8" x2="12" y2="12"/>
                                <line x1="12" y1="16" x2="12.01" y2="16"/>
                            </svg>
                            <span><?= __('Complete your profile to get the most out of the portal.') ?></span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Attendance Overview -->
            <div class="student-card">
                <div class="student-card__header">
                    <h3><?= __('Attendance Overview') ?> - <?= $currentYear ?></h3>
                    <?= $this->Html->link(__('View All'), ['controller' => 'Attendance', 'action' => 'index'], ['class' => 'btn btn--outline btn--sm']) ?>
                </div>
                <div class="student-card__body">
                    <div class="attendance-visual">
                        <div class="attendance-ring-container">
                            <svg class="attendance-ring" viewBox="0 0 36 36">
                                <path class="attendance-ring__bg"
                                    d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                                <path class="attendance-ring__fill <?= $attendanceSummary['percentage'] >= 75 ? 'attendance-ring__fill--good' : ($attendanceSummary['percentage'] >= 50 ? 'attendance-ring__fill--warning' : 'attendance-ring__fill--danger') ?>"
                                    stroke-dasharray="<?= $attendanceSummary['percentage'] ?>, 100"
                                    d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                            </svg>
                            <div class="attendance-ring__center">
                                <span class="attendance-ring__value"><?= $attendanceSummary['percentage'] ?>%</span>
                                <span class="attendance-ring__label"><?= __('Overall') ?></span>
                            </div>
                        </div>
                        <div class="attendance-breakdown-list">
                            <div class="attendance-breakdown-item attendance-breakdown-item--present">
                                <span class="breakdown-dot"></span>
                                <span class="breakdown-label"><?= __('Present') ?></span>
                                <span class="breakdown-value"><?= $attendanceSummary['present'] ?> <?= __('days') ?></span>
                            </div>
                            <div class="attendance-breakdown-item attendance-breakdown-item--absent">
                                <span class="breakdown-dot"></span>
                                <span class="breakdown-label"><?= __('Absent') ?></span>
                                <span class="breakdown-value"><?= $attendanceSummary['absent'] ?> <?= __('days') ?></span>
                            </div>
                            <div class="attendance-breakdown-item attendance-breakdown-item--late">
                                <span class="breakdown-dot"></span>
                                <span class="breakdown-label"><?= __('Late') ?></span>
                                <span class="breakdown-value"><?= $attendanceSummary['late'] ?> <?= __('days') ?></span>
                            </div>
                            <div class="attendance-breakdown-item attendance-breakdown-item--excused">
                                <span class="breakdown-dot"></span>
                                <span class="breakdown-label"><?= __('Excused') ?></span>
                                <span class="breakdown-value"><?= $attendanceSummary['excused'] ?> <?= __('days') ?></span>
                            </div>
                        </div>
                    </div>

                    <?php if ($attendanceSummary['percentage'] < 75 && $attendanceSummary['total'] > 0): ?>
                        <div class="attendance-warning-alert">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                                <line x1="12" y1="9" x2="12" y2="13"/>
                                <line x1="12" y1="17" x2="12.01" y2="17"/>
                            </svg>
                            <div>
                                <strong><?= __('Attendance Alert') ?></strong>
                                <p><?= __('Your attendance is below 75%. Please improve your attendance for better academic standing.') ?></p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Recent Attendance -->
            <?php if (!$recentAttendance->isEmpty()): ?>
            <div class="student-card">
                <div class="student-card__header">
                    <h3><?= __('Recent Attendance') ?></h3>
                </div>
                <div class="student-card__body student-card__body--no-padding">
                    <table class="student-data-table">
                        <thead>
                            <tr>
                                <th><?= __('Date') ?></th>
                                <th><?= __('Status') ?></th>
                                <th><?= __('Check In') ?></th>
                                <th><?= __('Remarks') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recentAttendance as $record): ?>
                                <tr>
                                    <td>
                                        <div class="date-cell">
                                            <strong><?= $record->date->format('M j') ?></strong>
                                            <span><?= $record->date->format('D') ?></span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="attendance-status attendance-status--<?= $record->status ?>">
                                            <?= h(ucfirst($record->status)) ?>
                                        </span>
                                    </td>
                                    <td><?= $record->check_in_time ? $record->check_in_time->format('h:i A') : '-' ?></td>
                                    <td class="remarks-cell"><?= $record->remarks ? h($record->remarks) : '-' ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Right Column (Sidebar) -->
        <div class="student-dashboard-sidebar">
            <!-- This Month Card -->
            <div class="student-card student-card--highlight">
                <div class="student-card__header">
                    <h3><?= __('This Month') ?></h3>
                    <span class="month-badge"><?= $monthNames[$currentMonth] ?></span>
                </div>
                <div class="student-card__body">
                    <div class="monthly-attendance-display">
                        <div class="monthly-percentage <?= $monthlyAttendance['percentage'] >= 75 ? 'monthly-percentage--good' : 'monthly-percentage--warning' ?>">
                            <?= $monthlyAttendance['percentage'] ?>%
                        </div>
                        <span class="monthly-label"><?= __('Attendance Rate') ?></span>
                    </div>
                    <div class="monthly-stats-list">
                        <div class="monthly-stat-item">
                            <span class="stat-label"><?= __('Present') ?></span>
                            <span class="stat-value stat-value--success"><?= $monthlyAttendance['present'] ?></span>
                        </div>
                        <div class="monthly-stat-item">
                            <span class="stat-label"><?= __('Absent') ?></span>
                            <span class="stat-value stat-value--danger"><?= $monthlyAttendance['absent'] ?></span>
                        </div>
                        <div class="monthly-stat-item">
                            <span class="stat-label"><?= __('Late') ?></span>
                            <span class="stat-value stat-value--warning"><?= $monthlyAttendance['late'] ?></span>
                        </div>
                        <div class="monthly-stat-item">
                            <span class="stat-label"><?= __('Total Days') ?></span>
                            <span class="stat-value"><?= $monthlyAttendance['total'] ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="student-card">
                <div class="student-card__header">
                    <h3><?= __('Quick Actions') ?></h3>
                </div>
                <div class="student-card__body">
                    <div class="quick-actions-list">
                        <?= $this->Html->link(
                            '<span class="quick-action-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg></span><span>' . __('View Profile') . '</span>',
                            ['controller' => 'Profile', 'action' => 'view'],
                            ['class' => 'quick-action-link', 'escape' => false]
                        ) ?>
                        <?= $this->Html->link(
                            '<span class="quick-action-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg></span><span>' . __('My Attendance') . '</span>',
                            ['controller' => 'Attendance', 'action' => 'index'],
                            ['class' => 'quick-action-link', 'escape' => false]
                        ) ?>
                        <?= $this->Html->link(
                            '<span class="quick-action-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 2 7 12 12 22 7 12 2"/><polyline points="2 17 12 22 22 17"/><polyline points="2 12 12 17 22 12"/></svg></span><span>' . __('My Courses') . '</span>',
                            ['controller' => 'Courses', 'action' => 'index'],
                            ['class' => 'quick-action-link', 'escape' => false]
                        ) ?>
                        <?= $this->Html->link(
                            '<span class="quick-action-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg></span><span>' . __('Browse Posts') . '</span>',
                            ['prefix' => false, 'controller' => 'Posts', 'action' => 'index'],
                            ['class' => 'quick-action-link', 'escape' => false]
                        ) ?>
                    </div>
                </div>
            </div>

            <!-- Recent Posts -->
            <div class="student-card">
                <div class="student-card__header">
                    <h3><?= __('Recent Posts') ?></h3>
                    <?= $this->Html->link(__('View All'), ['prefix' => false, 'controller' => 'Posts', 'action' => 'index'], ['class' => 'btn btn--link btn--sm']) ?>
                </div>
                <div class="student-card__body">
                    <?php if ($recentPosts->isEmpty()): ?>
                        <p class="empty-message"><?= __('No posts available yet.') ?></p>
                    <?php else: ?>
                        <div class="recent-posts-list">
                            <?php foreach ($recentPosts as $post): ?>
                                <article class="recent-post-item">
                                    <h4><?= $this->Html->link(h($post->title), ['prefix' => false, 'controller' => 'Posts', 'action' => 'view', $post->slug]) ?></h4>
                                    <span class="post-date"><?= $post->created->format('M j, Y') ?></span>
                                </article>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>
