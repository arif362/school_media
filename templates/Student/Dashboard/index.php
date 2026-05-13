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
$this->assign('dashboardTitle', __('Welcome back, {0}!', $user->name));
$this->assign('dashboardSubtitle', __('Here\'s what\'s happening in your school media portal.'));

$monthNames = ['', 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
?>

<div class="student-dashboard">
    <div class="dashboard-grid">
        <div class="dashboard-card profile-summary">
            <div class="dashboard-card__header">
                <h3><?= __('My Profile') ?></h3>
                <?= $this->Html->link(__('Edit'), ['controller' => 'Profile', 'action' => 'edit'], ['class' => 'btn btn--small btn--secondary']) ?>
            </div>
            <div class="dashboard-card__body">
                <div class="profile-summary__content">
                    <div class="profile-summary__avatar">
                        <?php if ($user->avatar): ?>
                            <img src="<?= $this->Url->image($user->avatar) ?>" alt="<?= h($user->name) ?>">
                        <?php else: ?>
                            <span class="profile-avatar-initial"><?= strtoupper(substr($user->name, 0, 1)) ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="profile-summary__info">
                        <h4><?= h($user->name) ?></h4>
                        <p><?= h($user->email) ?></p>
                        <?php if ($user->grade_level): ?>
                            <span class="badge badge--primary"><?= h($user->grade_level) ?></span>
                        <?php endif; ?>
                    </div>
                </div>
                <?php if (!$user->bio && !$user->phone && !$user->grade_level): ?>
                    <div class="profile-summary__cta">
                        <p><?= __('Your profile is incomplete.') ?></p>
                        <?= $this->Html->link(__('Complete your profile'), ['controller' => 'Profile', 'action' => 'edit'], ['class' => 'btn btn--primary btn--small']) ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="dashboard-card quick-links">
            <div class="dashboard-card__header">
                <h3><?= __('Quick Links') ?></h3>
            </div>
            <div class="dashboard-card__body">
                <div class="quick-links__grid">
                    <?= $this->Html->link(
                        '<span class="quick-link__icon">&#128100;</span><span>' . __('My Profile') . '</span>',
                        ['controller' => 'Profile', 'action' => 'view'],
                        ['class' => 'quick-link', 'escape' => false]
                    ) ?>
                    <?= $this->Html->link(
                        '<span class="quick-link__icon">&#128196;</span><span>' . __('Browse Posts') . '</span>',
                        ['prefix' => false, 'controller' => 'Posts', 'action' => 'index'],
                        ['class' => 'quick-link', 'escape' => false]
                    ) ?>
                    <?= $this->Html->link(
                        '<span class="quick-link__icon">&#128197;</span><span>' . __('My Attendance') . '</span>',
                        ['controller' => 'Attendance', 'action' => 'index'],
                        ['class' => 'quick-link', 'escape' => false]
                    ) ?>
                    <?= $this->Html->link(
                        '<span class="quick-link__icon">&#9881;</span><span>' . __('Edit Profile') . '</span>',
                        ['controller' => 'Profile', 'action' => 'edit'],
                        ['class' => 'quick-link', 'escape' => false]
                    ) ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Attendance Summary Section -->
    <div class="dashboard-grid attendance-section">
        <div class="dashboard-card attendance-overview">
            <div class="dashboard-card__header">
                <h3><?= __('Attendance Overview') ?> - <?= $currentYear ?></h3>
                <?= $this->Html->link(__('View Details'), ['controller' => 'Attendance', 'action' => 'index'], ['class' => 'btn btn--small btn--secondary']) ?>
            </div>
            <div class="dashboard-card__body">
                <?php if ($studentClass): ?>
                    <div class="class-info-badge">
                        <span class="badge badge--info"><?= h($studentClass->name) ?> <?= $studentClass->section ? '(' . h($studentClass->section) . ')' : '' ?></span>
                        <span class="badge badge--outline"><?= h($studentClass->grade_level) ?></span>
                    </div>
                <?php endif; ?>

                <div class="attendance-stats">
                    <div class="attendance-stat attendance-stat--main">
                        <div class="attendance-percentage <?= $attendanceSummary['percentage'] >= 75 ? 'attendance-percentage--good' : ($attendanceSummary['percentage'] >= 50 ? 'attendance-percentage--warning' : 'attendance-percentage--danger') ?>">
                            <span class="percentage-value"><?= $attendanceSummary['percentage'] ?>%</span>
                            <span class="percentage-label"><?= __('Overall Attendance') ?></span>
                        </div>
                        <p class="attendance-stat__subtitle"><?= __('Total {0} school days recorded', $attendanceSummary['total']) ?></p>
                    </div>

                    <div class="attendance-breakdown">
                        <div class="attendance-breakdown__item">
                            <span class="count count--present"><?= $attendanceSummary['present'] ?></span>
                            <span class="label"><?= __('Present') ?></span>
                        </div>
                        <div class="attendance-breakdown__item">
                            <span class="count count--absent"><?= $attendanceSummary['absent'] ?></span>
                            <span class="label"><?= __('Absent') ?></span>
                        </div>
                        <div class="attendance-breakdown__item">
                            <span class="count count--late"><?= $attendanceSummary['late'] ?></span>
                            <span class="label"><?= __('Late') ?></span>
                        </div>
                        <div class="attendance-breakdown__item">
                            <span class="count count--excused"><?= $attendanceSummary['excused'] ?></span>
                            <span class="label"><?= __('Excused') ?></span>
                        </div>
                    </div>
                </div>

                <?php if ($attendanceSummary['percentage'] < 75 && $attendanceSummary['total'] > 0): ?>
                    <div class="attendance-alert alert alert--warning">
                        <strong><?= __('Attendance Alert') ?>:</strong>
                        <?= __('Your attendance is below 75%. Please try to improve your attendance to maintain good academic standing.') ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="dashboard-card monthly-summary">
            <div class="dashboard-card__header">
                <h3><?= __('This Month') ?> - <?= $monthNames[$currentMonth] ?></h3>
            </div>
            <div class="dashboard-card__body">
                <div class="monthly-stats">
                    <div class="monthly-stat">
                        <span class="stat-value <?= $monthlyAttendance['percentage'] >= 75 ? 'text-success' : 'text-warning' ?>"><?= $monthlyAttendance['percentage'] ?>%</span>
                        <span class="stat-label"><?= __('Attendance Rate') ?></span>
                    </div>
                    <div class="monthly-details">
                        <div class="detail-row">
                            <span><?= __('Days Present') ?></span>
                            <span class="text-success"><?= $monthlyAttendance['present'] ?></span>
                        </div>
                        <div class="detail-row">
                            <span><?= __('Days Absent') ?></span>
                            <span class="text-danger"><?= $monthlyAttendance['absent'] ?></span>
                        </div>
                        <div class="detail-row">
                            <span><?= __('Late Arrivals') ?></span>
                            <span class="text-warning"><?= $monthlyAttendance['late'] ?></span>
                        </div>
                        <div class="detail-row">
                            <span><?= __('Total Days') ?></span>
                            <span><?= $monthlyAttendance['total'] ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Attendance Records -->
    <?php if (!$recentAttendance->isEmpty()): ?>
    <div class="dashboard-card recent-attendance">
        <div class="dashboard-card__header">
            <h3><?= __('Recent Attendance Records') ?></h3>
        </div>
        <div class="dashboard-card__body">
            <table class="data-table data-table--compact">
                <thead>
                    <tr>
                        <th><?= __('Date') ?></th>
                        <th><?= __('Status') ?></th>
                        <th><?= __('Check In') ?></th>
                        <th><?= __('Check Out') ?></th>
                        <th><?= __('Remarks') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recentAttendance as $record): ?>
                        <tr>
                            <td>
                                <strong><?= $record->date->format('D, M j') ?></strong>
                                <small class="text-muted"><?= $record->date->format('Y') ?></small>
                            </td>
                            <td>
                                <span class="attendance-status attendance-status--<?= $record->status ?>">
                                    <?= h(ucfirst($record->status)) ?>
                                </span>
                            </td>
                            <td><?= $record->check_in_time ? $record->check_in_time->format('h:i A') : '-' ?></td>
                            <td><?= $record->check_out_time ? $record->check_out_time->format('h:i A') : '-' ?></td>
                            <td><?= $record->remarks ? h($record->remarks) : '-' ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>

    <div class="dashboard-card recent-posts">
        <div class="dashboard-card__header">
            <h3><?= __('Recent Posts') ?></h3>
            <?= $this->Html->link(__('View All'), ['prefix' => false, 'controller' => 'Posts', 'action' => 'index'], ['class' => 'btn btn--small btn--secondary']) ?>
        </div>
        <div class="dashboard-card__body">
            <?php if ($recentPosts->isEmpty()): ?>
                <p class="text-muted"><?= __('No posts available yet.') ?></p>
            <?php else: ?>
                <div class="posts-list">
                    <?php foreach ($recentPosts as $post): ?>
                        <article class="post-item">
                            <h4>
                                <?= $this->Html->link(h($post->title), ['prefix' => false, 'controller' => 'Posts', 'action' => 'view', $post->slug]) ?>
                            </h4>
                            <p class="post-item__meta">
                                <?= $post->created->format('M j, Y') ?>
                            </p>
                            <p class="post-item__excerpt">
                                <?= $this->Text->truncate(strip_tags($post->body), 120, ['ellipsis' => '...', 'exact' => false]) ?>
                            </p>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
