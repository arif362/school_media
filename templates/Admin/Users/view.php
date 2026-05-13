<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 * @var \Cake\Collection\CollectionInterface|null $teacherSubjects
 * @var \Cake\Collection\CollectionInterface|null $teacherCourses
 * @var array|null $teacherStats
 * @var \Cake\Collection\CollectionInterface|null $studentClasses
 * @var \Cake\Collection\CollectionInterface|null $studentCourses
 * @var array|null $attendanceStats
 * @var array|null $attendanceHistory
 * @var array|null $feesSummary
 * @var \Cake\Collection\CollectionInterface|null $recentPayments
 * @var array|null $grades
 * @var string $academicYear
 */
$this->assign('title', h($user->name));

$roleClass = match($user->role) {
    'admin' => 'badge--warning',
    'teacher' => 'badge--primary',
    'student' => 'badge--info',
    default => 'badge--secondary'
};
$roleIcon = match($user->role) {
    'admin' => '&#128081;',
    'teacher' => '&#128104;&#8205;&#127979;',
    'student' => '&#127891;',
    default => '&#128100;'
};
?>

<section class="user-profile">
    <!-- Profile Header -->
    <div class="profile-header">
        <div class="profile-header__bg"></div>
        <div class="profile-header__content">
            <div class="profile-avatar">
                <?php if ($user->avatar): ?>
                    <img src="<?= $this->Url->image($user->avatar) ?>" alt="<?= h($user->name) ?>">
                <?php else: ?>
                    <span class="profile-avatar__initial"><?= strtoupper(substr($user->name, 0, 1)) ?></span>
                <?php endif; ?>
                <span class="profile-avatar__role" title="<?= h(ucfirst($user->role)) ?>"><?= $roleIcon ?></span>
            </div>
            <div class="profile-info">
                <div class="profile-info__main">
                    <h1><?= h($user->name) ?></h1>
                    <div class="profile-badges">
                        <span class="badge <?= $roleClass ?>"><?= h(ucfirst($user->role)) ?></span>
                        <?php if ($user->active): ?>
                            <span class="badge badge--success"><?= __('Active') ?></span>
                        <?php else: ?>
                            <span class="badge badge--danger"><?= __('Inactive') ?></span>
                        <?php endif; ?>
                        <?php if ($user->role === 'student' && $user->grade_level): ?>
                            <span class="badge badge--outline"><?= h($user->grade_level) ?></span>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="profile-contact">
                    <span><span class="profile-contact__icon">&#9993;</span> <?= h($user->email) ?></span>
                    <?php if ($user->phone): ?>
                        <span><span class="profile-contact__icon">&#128222;</span> <?= h($user->phone) ?></span>
                    <?php endif; ?>
                    <span><span class="profile-contact__icon">&#128197;</span> <?= __('Joined {0}', $user->created->format('M Y')) ?></span>
                </div>
            </div>
            <div class="profile-actions">
                <?= $this->Html->link('<span>&#9998;</span> ' . __('Edit'), ['action' => 'edit', $user->id], [
                    'class' => 'btn btn--outline btn--white',
                    'escape' => false
                ]) ?>
                <?= $this->Form->postLink(
                    '<span>' . ($user->active ? '&#10060;' : '&#9989;') . '</span> ' . ($user->active ? __('Deactivate') : __('Activate')),
                    ['action' => 'toggleStatus', $user->id],
                    ['class' => 'btn btn--ghost btn--white', 'escape' => false]
                ) ?>
            </div>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="profile-tabs">
        <button class="profile-tab is-active" data-tab="overview"><?= __('Overview') ?></button>
        <?php if ($user->role === 'student'): ?>
            <button class="profile-tab" data-tab="academics"><?= __('Academics') ?></button>
            <button class="profile-tab" data-tab="attendance"><?= __('Attendance') ?></button>
            <button class="profile-tab" data-tab="finances"><?= __('Finances') ?></button>
        <?php elseif ($user->role === 'teacher'): ?>
            <button class="profile-tab" data-tab="courses"><?= __('Courses') ?></button>
            <button class="profile-tab" data-tab="subjects"><?= __('Subjects') ?></button>
        <?php endif; ?>
        <button class="profile-tab" data-tab="settings"><?= __('Settings') ?></button>
    </div>

    <!-- Tab Content -->
    <div class="profile-content">
        <!-- Overview Tab -->
        <div class="profile-panel is-active" id="panel-overview">
            <?php if ($user->role === 'student'): ?>
                <!-- Student Quick Stats -->
                <div class="quick-stats-grid">
                    <div class="quick-stat quick-stat--primary">
                        <span class="quick-stat__icon">&#128218;</span>
                        <div class="quick-stat__data">
                            <span class="quick-stat__value"><?= $studentCourses ? $studentCourses->count() : 0 ?></span>
                            <span class="quick-stat__label"><?= __('Enrolled Courses') ?></span>
                        </div>
                    </div>
                    <div class="quick-stat quick-stat--success">
                        <span class="quick-stat__icon">&#9989;</span>
                        <div class="quick-stat__data">
                            <span class="quick-stat__value"><?= $attendanceStats['percentage'] ?? 0 ?>%</span>
                            <span class="quick-stat__label"><?= __('Attendance Rate') ?></span>
                        </div>
                    </div>
                    <div class="quick-stat quick-stat--info">
                        <span class="quick-stat__icon">&#127942;</span>
                        <div class="quick-stat__data">
                            <span class="quick-stat__value"><?= $grades['gpa'] ?? '-' ?></span>
                            <span class="quick-stat__label"><?= __('GPA') ?></span>
                        </div>
                    </div>
                    <div class="quick-stat <?= ($feesSummary['total_pending'] ?? 0) > 0 ? 'quick-stat--warning' : 'quick-stat--success' ?>">
                        <span class="quick-stat__icon">&#128176;</span>
                        <div class="quick-stat__data">
                            <span class="quick-stat__value"><?= number_format($feesSummary['total_pending'] ?? 0, 2) ?></span>
                            <span class="quick-stat__label"><?= __('Pending Fees') ?></span>
                        </div>
                    </div>
                </div>
            <?php elseif ($user->role === 'teacher'): ?>
                <!-- Teacher Quick Stats -->
                <div class="quick-stats-grid">
                    <div class="quick-stat quick-stat--primary">
                        <span class="quick-stat__icon">&#128218;</span>
                        <div class="quick-stat__data">
                            <span class="quick-stat__value"><?= $teacherStats['active_courses'] ?? 0 ?></span>
                            <span class="quick-stat__label"><?= __('Active Courses') ?></span>
                        </div>
                    </div>
                    <div class="quick-stat quick-stat--info">
                        <span class="quick-stat__icon">&#128214;</span>
                        <div class="quick-stat__data">
                            <span class="quick-stat__value"><?= $teacherStats['subjects_count'] ?? 0 ?></span>
                            <span class="quick-stat__label"><?= __('Subjects') ?></span>
                        </div>
                    </div>
                    <div class="quick-stat quick-stat--success">
                        <span class="quick-stat__icon">&#128101;</span>
                        <div class="quick-stat__data">
                            <span class="quick-stat__value"><?= $teacherStats['total_students'] ?? 0 ?></span>
                            <span class="quick-stat__label"><?= __('Total Students') ?></span>
                        </div>
                    </div>
                    <div class="quick-stat quick-stat--warning">
                        <span class="quick-stat__icon">&#128197;</span>
                        <div class="quick-stat__data">
                            <span class="quick-stat__value"><?= $teacherStats['total_courses'] ?? 0 ?></span>
                            <span class="quick-stat__label"><?= __('All Courses') ?></span>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <div class="profile-grid">
                <!-- Personal Information -->
                <div class="profile-card">
                    <div class="profile-card__header">
                        <h3><span class="profile-card__icon">&#128100;</span> <?= __('Personal Information') ?></h3>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $user->id], ['class' => 'btn btn--sm btn--ghost']) ?>
                    </div>
                    <div class="profile-card__body">
                        <div class="info-grid">
                            <div class="info-item">
                                <span class="info-item__label"><?= __('Full Name') ?></span>
                                <span class="info-item__value"><?= h($user->name) ?></span>
                            </div>
                            <div class="info-item">
                                <span class="info-item__label"><?= __('Email') ?></span>
                                <span class="info-item__value"><?= h($user->email) ?></span>
                            </div>
                            <div class="info-item">
                                <span class="info-item__label"><?= __('Phone') ?></span>
                                <span class="info-item__value"><?= h($user->phone ?? '-') ?></span>
                            </div>
                            <div class="info-item">
                                <span class="info-item__label"><?= __('Date of Birth') ?></span>
                                <span class="info-item__value"><?= $user->date_of_birth ? $user->date_of_birth->format('F j, Y') : '-' ?></span>
                            </div>
                            <?php if ($user->role === 'student'): ?>
                                <div class="info-item">
                                    <span class="info-item__label"><?= __('Grade Level') ?></span>
                                    <span class="info-item__value"><?= h($user->grade_level ?? '-') ?></span>
                                </div>
                            <?php endif; ?>
                            <div class="info-item info-item--full">
                                <span class="info-item__label"><?= __('Address') ?></span>
                                <span class="info-item__value"><?= h($user->address ?? '-') ?></span>
                            </div>
                            <?php if ($user->bio): ?>
                                <div class="info-item info-item--full">
                                    <span class="info-item__label"><?= __('Bio') ?></span>
                                    <span class="info-item__value"><?= nl2br(h($user->bio)) ?></span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Current Class/Courses Summary -->
                <?php if ($user->role === 'student' && $studentClasses && $studentClasses->count() > 0): ?>
                    <?php $currentClass = $studentClasses->first(); ?>
                    <div class="profile-card">
                        <div class="profile-card__header">
                            <h3><span class="profile-card__icon">&#127979;</span> <?= __('Current Class') ?></h3>
                            <?= $this->Html->link(__('Manage'), ['action' => 'studentClasses', $user->id], ['class' => 'btn btn--sm btn--ghost']) ?>
                        </div>
                        <div class="profile-card__body">
                            <div class="current-class">
                                <div class="current-class__name">
                                    <?= h($currentClass->class->name ?? '') ?>
                                    <?= $currentClass->class && $currentClass->class->section ? ' - ' . h($currentClass->class->section) : '' ?>
                                </div>
                                <div class="current-class__details">
                                    <span class="badge badge--outline"><?= h($currentClass->academic_year) ?></span>
                                    <span class="badge badge--success"><?= h(ucfirst($currentClass->status)) ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php elseif ($user->role === 'teacher' && $teacherSubjects && $teacherSubjects->count() > 0): ?>
                    <div class="profile-card">
                        <div class="profile-card__header">
                            <h3><span class="profile-card__icon">&#128218;</span> <?= __('Teaching Subjects') ?></h3>
                            <?= $this->Html->link(__('Manage'), ['action' => 'teacherSubjects', $user->id], ['class' => 'btn btn--sm btn--ghost']) ?>
                        </div>
                        <div class="profile-card__body">
                            <div class="subject-chips">
                                <?php foreach ($teacherSubjects as $ts): ?>
                                    <span class="subject-chip<?= $ts->is_primary ? ' subject-chip--primary' : '' ?>">
                                        <?= h($ts->subject->name ?? '') ?>
                                        <?php if ($ts->is_primary): ?>
                                            <span class="subject-chip__badge"><?= __('Primary') ?></span>
                                        <?php endif; ?>
                                    </span>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <?php if ($user->role === 'student' && $attendanceHistory): ?>
                <!-- Attendance Chart -->
                <div class="profile-card profile-card--wide mt-4">
                    <div class="profile-card__header">
                        <h3><span class="profile-card__icon">&#128200;</span> <?= __('Attendance Trend (Last 6 Months)') ?></h3>
                    </div>
                    <div class="profile-card__body">
                        <div class="attendance-chart">
                            <?php foreach ($attendanceHistory as $month): ?>
                                <div class="attendance-chart__bar">
                                    <div class="attendance-chart__fill" style="height: <?= $month['percentage'] ?>%"></div>
                                    <span class="attendance-chart__value"><?= $month['percentage'] ?>%</span>
                                    <span class="attendance-chart__label"><?= $month['month'] ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <?php if ($user->role === 'student'): ?>
            <!-- Academics Tab -->
            <div class="profile-panel" id="panel-academics">
                <?php if ($grades): ?>
                    <div class="grade-overview">
                        <div class="grade-overview__gpa">
                            <span class="grade-overview__gpa-value"><?= $grades['gpa'] ?? '-' ?></span>
                            <span class="grade-overview__gpa-label"><?= __('Current GPA') ?></span>
                        </div>
                        <div class="grade-overview__stats">
                            <div class="grade-overview__stat">
                                <span class="grade-overview__stat-value"><?= $grades['graded_courses'] ?></span>
                                <span class="grade-overview__stat-label"><?= __('Graded') ?></span>
                            </div>
                            <div class="grade-overview__stat">
                                <span class="grade-overview__stat-value"><?= $grades['total_courses'] - $grades['graded_courses'] ?></span>
                                <span class="grade-overview__stat-label"><?= __('In Progress') ?></span>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="profile-card mt-4">
                    <div class="profile-card__header">
                        <h3><span class="profile-card__icon">&#128218;</span> <?= __('Enrolled Courses') ?></h3>
                    </div>
                    <div class="profile-card__body">
                        <?php if ($studentCourses && $studentCourses->count() > 0): ?>
                            <div class="courses-table-wrapper">
                                <table class="profile-table">
                                    <thead>
                                        <tr>
                                            <th><?= __('Course') ?></th>
                                            <th><?= __('Class') ?></th>
                                            <th><?= __('Teacher') ?></th>
                                            <th><?= __('Grade') ?></th>
                                            <th><?= __('Marks') ?></th>
                                            <th><?= __('Status') ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($studentCourses as $sc): ?>
                                            <tr>
                                                <td>
                                                    <strong><?= h($sc->course->subject->name ?? '') ?></strong>
                                                    <br><small class="text-muted"><?= h($sc->course->subject->code ?? '') ?></small>
                                                </td>
                                                <td><?= h($sc->course->class->name ?? '-') ?></td>
                                                <td><?= h($sc->course->teacher->name ?? __('TBA')) ?></td>
                                                <td>
                                                    <?php if ($sc->grade): ?>
                                                        <?php
                                                        $gradeClass = match($sc->grade) {
                                                            'A*', 'A' => 'grade-badge--a',
                                                            'B' => 'grade-badge--b',
                                                            'C' => 'grade-badge--c',
                                                            'D' => 'grade-badge--d',
                                                            'E' => 'grade-badge--e',
                                                            default => 'grade-badge--u'
                                                        };
                                                        ?>
                                                        <span class="grade-badge <?= $gradeClass ?>"><?= h($sc->grade) ?></span>
                                                    <?php else: ?>
                                                        <span class="text-muted">-</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td><?= $sc->marks ? h($sc->marks) . '%' : '-' ?></td>
                                                <td>
                                                    <?php
                                                    $statusClass = match($sc->status) {
                                                        'enrolled' => 'badge--info',
                                                        'completed' => 'badge--success',
                                                        'dropped' => 'badge--warning',
                                                        'failed' => 'badge--danger',
                                                        default => 'badge--secondary'
                                                    };
                                                    ?>
                                                    <span class="badge <?= $statusClass ?>"><?= h(ucfirst($sc->status)) ?></span>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="empty-state empty-state--sm">
                                <span class="empty-state__icon">&#128218;</span>
                                <p><?= __('No course enrollments yet.') ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Class History -->
                <div class="profile-card mt-4">
                    <div class="profile-card__header">
                        <h3><span class="profile-card__icon">&#127979;</span> <?= __('Class History') ?></h3>
                        <?= $this->Html->link(__('Manage'), ['action' => 'studentClasses', $user->id], ['class' => 'btn btn--sm btn--ghost']) ?>
                    </div>
                    <div class="profile-card__body">
                        <?php if ($studentClasses && $studentClasses->count() > 0): ?>
                            <div class="class-history">
                                <?php foreach ($studentClasses as $sc): ?>
                                    <div class="class-history__item">
                                        <div class="class-history__year"><?= h($sc->academic_year) ?></div>
                                        <div class="class-history__details">
                                            <strong><?= h($sc->class->name ?? '') ?><?= $sc->class && $sc->class->section ? ' - ' . h($sc->class->section) : '' ?></strong>
                                            <br><small class="text-muted"><?= h($sc->class->grade_level ?? '') ?></small>
                                        </div>
                                        <div class="class-history__status">
                                            <?php
                                            $statusClass = match($sc->status) {
                                                'active' => 'badge--success',
                                                'graduated' => 'badge--info',
                                                'transferred' => 'badge--warning',
                                                default => 'badge--secondary'
                                            };
                                            ?>
                                            <span class="badge <?= $statusClass ?>"><?= h(ucfirst($sc->status)) ?></span>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="empty-state empty-state--sm">
                                <span class="empty-state__icon">&#127979;</span>
                                <p><?= __('No class assignments yet.') ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Attendance Tab -->
            <div class="profile-panel" id="panel-attendance">
                <?php if ($attendanceStats): ?>
                    <div class="attendance-overview">
                        <div class="attendance-overview__main">
                            <div class="attendance-percentage">
                                <svg class="attendance-ring" viewBox="0 0 100 100">
                                    <circle class="attendance-ring__bg" cx="50" cy="50" r="45"/>
                                    <circle class="attendance-ring__fill" cx="50" cy="50" r="45"
                                            style="stroke-dasharray: <?= $attendanceStats['percentage'] * 2.83 ?>, 283"/>
                                </svg>
                                <div class="attendance-percentage__value"><?= $attendanceStats['percentage'] ?>%</div>
                            </div>
                            <div class="attendance-overview__label"><?= __('Overall Attendance Rate') ?></div>
                        </div>
                        <div class="attendance-breakdown">
                            <div class="attendance-breakdown__item attendance-breakdown__item--present">
                                <span class="attendance-breakdown__icon">&#9989;</span>
                                <span class="attendance-breakdown__value"><?= $attendanceStats['present'] ?></span>
                                <span class="attendance-breakdown__label"><?= __('Present') ?></span>
                            </div>
                            <div class="attendance-breakdown__item attendance-breakdown__item--absent">
                                <span class="attendance-breakdown__icon">&#10060;</span>
                                <span class="attendance-breakdown__value"><?= $attendanceStats['absent'] ?></span>
                                <span class="attendance-breakdown__label"><?= __('Absent') ?></span>
                            </div>
                            <div class="attendance-breakdown__item attendance-breakdown__item--late">
                                <span class="attendance-breakdown__icon">&#9200;</span>
                                <span class="attendance-breakdown__value"><?= $attendanceStats['late'] ?></span>
                                <span class="attendance-breakdown__label"><?= __('Late') ?></span>
                            </div>
                            <div class="attendance-breakdown__item">
                                <span class="attendance-breakdown__icon">&#128197;</span>
                                <span class="attendance-breakdown__value"><?= $attendanceStats['total'] ?></span>
                                <span class="attendance-breakdown__label"><?= __('Total Days') ?></span>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if ($attendanceHistory): ?>
                    <div class="profile-card mt-4">
                        <div class="profile-card__header">
                            <h3><span class="profile-card__icon">&#128200;</span> <?= __('Monthly Breakdown') ?></h3>
                        </div>
                        <div class="profile-card__body">
                            <table class="profile-table">
                                <thead>
                                    <tr>
                                        <th><?= __('Month') ?></th>
                                        <th><?= __('Present') ?></th>
                                        <th><?= __('Total Days') ?></th>
                                        <th><?= __('Attendance Rate') ?></th>
                                        <th><?= __('Progress') ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($attendanceHistory as $month): ?>
                                        <tr>
                                            <td><strong><?= $month['month'] ?></strong></td>
                                            <td><?= $month['present'] ?></td>
                                            <td><?= $month['total'] ?></td>
                                            <td>
                                                <span class="badge <?= $month['percentage'] >= 90 ? 'badge--success' : ($month['percentage'] >= 75 ? 'badge--warning' : 'badge--danger') ?>">
                                                    <?= $month['percentage'] ?>%
                                                </span>
                                            </td>
                                            <td>
                                                <div class="progress-bar">
                                                    <div class="progress-bar__fill <?= $month['percentage'] >= 90 ? 'progress-bar__fill--success' : ($month['percentage'] >= 75 ? 'progress-bar__fill--warning' : 'progress-bar__fill--danger') ?>"
                                                         style="width: <?= $month['percentage'] ?>%"></div>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Finances Tab -->
            <div class="profile-panel" id="panel-finances">
                <?php if ($feesSummary): ?>
                    <div class="finance-overview">
                        <div class="finance-stat finance-stat--total">
                            <span class="finance-stat__label"><?= __('Total Fees') ?></span>
                            <span class="finance-stat__value"><?= number_format($feesSummary['total_fees'], 2) ?></span>
                        </div>
                        <div class="finance-stat finance-stat--discount">
                            <span class="finance-stat__label"><?= __('Discount') ?></span>
                            <span class="finance-stat__value">-<?= number_format($feesSummary['total_discount'], 2) ?></span>
                        </div>
                        <div class="finance-stat finance-stat--paid">
                            <span class="finance-stat__label"><?= __('Paid') ?></span>
                            <span class="finance-stat__value"><?= number_format($feesSummary['total_paid'], 2) ?></span>
                        </div>
                        <div class="finance-stat finance-stat--pending">
                            <span class="finance-stat__label"><?= __('Pending') ?></span>
                            <span class="finance-stat__value"><?= number_format($feesSummary['total_pending'], 2) ?></span>
                        </div>
                    </div>

                    <?php if (!empty($feesSummary['fees'])): ?>
                        <div class="profile-card mt-4">
                            <div class="profile-card__header">
                                <h3><span class="profile-card__icon">&#128176;</span> <?= __('Fee Details') ?> (<?= $academicYear ?>)</h3>
                            </div>
                            <div class="profile-card__body">
                                <table class="profile-table">
                                    <thead>
                                        <tr>
                                            <th><?= __('Fee Type') ?></th>
                                            <th><?= __('Amount') ?></th>
                                            <th><?= __('Discount') ?></th>
                                            <th><?= __('Paid') ?></th>
                                            <th><?= __('Pending') ?></th>
                                            <th><?= __('Due Date') ?></th>
                                            <th><?= __('Status') ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($feesSummary['fees'] as $feeData): ?>
                                            <?php $fee = $feeData['fee']; ?>
                                            <tr>
                                                <td><strong><?= h($fee->fee_type->name ?? '-') ?></strong></td>
                                                <td><?= number_format($fee->amount, 2) ?></td>
                                                <td class="text-success"><?= $fee->discount > 0 ? '-' . number_format($fee->discount, 2) : '-' ?></td>
                                                <td class="text-info"><?= number_format($feeData['paid_amount'], 2) ?></td>
                                                <td class="<?= $feeData['pending_amount'] > 0 ? 'text-danger' : 'text-success' ?>">
                                                    <?= number_format($feeData['pending_amount'], 2) ?>
                                                </td>
                                                <td><?= $fee->due_date->format('M j, Y') ?></td>
                                                <td>
                                                    <?php
                                                    $statusClass = match($fee->status) {
                                                        'paid' => 'badge--success',
                                                        'partial' => 'badge--info',
                                                        'pending' => 'badge--warning',
                                                        'overdue' => 'badge--danger',
                                                        'waived' => 'badge--secondary',
                                                        default => 'badge--secondary'
                                                    };
                                                    ?>
                                                    <span class="badge <?= $statusClass ?>"><?= h(ucfirst($fee->status)) ?></span>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if ($recentPayments && $recentPayments->count() > 0): ?>
                        <div class="profile-card mt-4">
                            <div class="profile-card__header">
                                <h3><span class="profile-card__icon">&#128179;</span> <?= __('Recent Payments') ?></h3>
                            </div>
                            <div class="profile-card__body">
                                <div class="payment-history">
                                    <?php foreach ($recentPayments as $payment): ?>
                                        <div class="payment-item">
                                            <div class="payment-item__icon">&#128179;</div>
                                            <div class="payment-item__details">
                                                <strong><?= h($payment->student_fee->fee_type->name ?? '-') ?></strong>
                                                <br><small class="text-muted"><?= h($payment->payment_method) ?> - <?= h($payment->receipt_number) ?></small>
                                            </div>
                                            <div class="payment-item__amount">
                                                <strong><?= number_format($payment->amount, 2) ?></strong>
                                                <br><small class="text-muted"><?= $payment->payment_date->format('M j, Y') ?></small>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="empty-state">
                        <span class="empty-state__icon">&#128176;</span>
                        <h3><?= __('No Fee Records') ?></h3>
                        <p><?= __('Fee information will appear here once assigned.') ?></p>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if ($user->role === 'teacher'): ?>
            <!-- Courses Tab -->
            <div class="profile-panel" id="panel-courses">
                <div class="profile-card">
                    <div class="profile-card__header">
                        <h3><span class="profile-card__icon">&#128218;</span> <?= __('Assigned Courses') ?></h3>
                    </div>
                    <div class="profile-card__body">
                        <?php if ($teacherCourses && $teacherCourses->count() > 0): ?>
                            <table class="profile-table">
                                <thead>
                                    <tr>
                                        <th><?= __('Course') ?></th>
                                        <th><?= __('Class') ?></th>
                                        <th><?= __('Academic Year') ?></th>
                                        <th><?= __('Term') ?></th>
                                        <th><?= __('Status') ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($teacherCourses as $course): ?>
                                        <tr>
                                            <td>
                                                <strong><?= h($course->subject->name ?? '') ?></strong>
                                                <br><small class="text-muted"><?= h($course->subject->code ?? '') ?></small>
                                            </td>
                                            <td><?= h($course->class->name ?? '') ?><?= $course->class && $course->class->section ? ' - ' . h($course->class->section) : '' ?></td>
                                            <td><?= h($course->academic_year) ?></td>
                                            <td><?= h($course->term ?? '-') ?></td>
                                            <td>
                                                <?php if ($course->is_active): ?>
                                                    <span class="badge badge--success"><?= __('Active') ?></span>
                                                <?php else: ?>
                                                    <span class="badge badge--secondary"><?= __('Inactive') ?></span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <div class="empty-state empty-state--sm">
                                <span class="empty-state__icon">&#128218;</span>
                                <p><?= __('No courses assigned yet.') ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Subjects Tab -->
            <div class="profile-panel" id="panel-subjects">
                <div class="profile-card">
                    <div class="profile-card__header">
                        <h3><span class="profile-card__icon">&#128214;</span> <?= __('Subject Assignments') ?></h3>
                        <?= $this->Html->link(__('Manage Subjects'), ['action' => 'teacherSubjects', $user->id], ['class' => 'btn btn--sm btn--outline']) ?>
                    </div>
                    <div class="profile-card__body">
                        <?php if ($teacherSubjects && $teacherSubjects->count() > 0): ?>
                            <div class="subject-cards">
                                <?php foreach ($teacherSubjects as $ts): ?>
                                    <div class="subject-card<?= $ts->is_primary ? ' subject-card--primary' : '' ?>">
                                        <div class="subject-card__icon">&#128218;</div>
                                        <div class="subject-card__info">
                                            <strong><?= h($ts->subject->name ?? '') ?></strong>
                                            <br><small class="text-muted"><?= h($ts->subject->code ?? '') ?></small>
                                            <?php if ($ts->is_primary): ?>
                                                <span class="badge badge--warning badge--sm"><?= __('Primary') ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="empty-state empty-state--sm">
                                <span class="empty-state__icon">&#128214;</span>
                                <p><?= __('No subjects assigned yet.') ?></p>
                                <?= $this->Html->link(__('Assign Subjects'), ['action' => 'teacherSubjects', $user->id], ['class' => 'btn btn--outline']) ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Settings Tab -->
        <div class="profile-panel" id="panel-settings">
            <div class="profile-grid">
                <div class="profile-card">
                    <div class="profile-card__header">
                        <h3><span class="profile-card__icon">&#128274;</span> <?= __('Account Settings') ?></h3>
                    </div>
                    <div class="profile-card__body">
                        <div class="settings-actions">
                            <div class="settings-action">
                                <div>
                                    <strong><?= __('Reset Password') ?></strong>
                                    <p class="text-muted"><?= __('Generate a new secure password for this account.') ?></p>
                                </div>
                                <?= $this->Form->postLink(__('Reset Password'), ['action' => 'resetPassword', $user->id], [
                                    'class' => 'btn btn--outline',
                                    'confirm' => __('Generate a new password for this user?')
                                ]) ?>
                            </div>
                            <div class="settings-action">
                                <div>
                                    <strong><?= __('Account Status') ?></strong>
                                    <p class="text-muted"><?= $user->active ? __('This account is currently active.') : __('This account is currently deactivated.') ?></p>
                                </div>
                                <?= $this->Form->postLink(
                                    $user->active ? __('Deactivate Account') : __('Activate Account'),
                                    ['action' => 'toggleStatus', $user->id],
                                    ['class' => 'btn ' . ($user->active ? 'btn--warning' : 'btn--success')]
                                ) ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="profile-card profile-card--danger">
                    <div class="profile-card__header">
                        <h3><span class="profile-card__icon">&#9888;</span> <?= __('Danger Zone') ?></h3>
                    </div>
                    <div class="profile-card__body">
                        <div class="danger-action">
                            <div>
                                <strong><?= __('Delete Account') ?></strong>
                                <p class="text-muted"><?= __('Permanently delete this user account and all associated data. This action cannot be undone.') ?></p>
                            </div>
                            <?= $this->Form->postLink(__('Delete Account'), ['action' => 'delete', $user->id], [
                                'class' => 'btn btn--danger',
                                'confirm' => __('Are you sure you want to permanently delete this user? This action cannot be undone.')
                            ]) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php $this->start('script'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const tabs = document.querySelectorAll('.profile-tab');
    const panels = document.querySelectorAll('.profile-panel');

    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            const targetId = 'panel-' + this.dataset.tab;

            // Update tabs
            tabs.forEach(t => t.classList.remove('is-active'));
            this.classList.add('is-active');

            // Update panels
            panels.forEach(p => {
                p.classList.remove('is-active');
                if (p.id === targetId) {
                    p.classList.add('is-active');
                }
            });
        });
    });
});
</script>
<?php $this->end(); ?>
