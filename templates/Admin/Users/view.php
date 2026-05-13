<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 * @var \Cake\Collection\CollectionInterface|null $teacherSubjects
 * @var \Cake\Collection\CollectionInterface|null $teacherCourses
 * @var \Cake\Collection\CollectionInterface|null $studentClasses
 * @var \Cake\Collection\CollectionInterface|null $studentCourses
 * @var array|null $attendanceStats
 */
$this->assign('title', h($user->name));
?>

<section class="admin-section">
    <header class="admin-section__header">
        <div>
            <nav class="breadcrumb">
                <?= $this->Html->link(__('Users'), ['action' => 'index']) ?> /
                <?php if ($user->role === 'teacher'): ?>
                    <?= $this->Html->link(__('Teachers'), ['action' => 'teachers']) ?>
                <?php elseif ($user->role === 'student'): ?>
                    <?= $this->Html->link(__('Students'), ['action' => 'students']) ?>
                <?php endif; ?>
                / <?= __('View') ?>
            </nav>
            <h1><?= h($user->name) ?></h1>
            <p class="text-muted">
                <?php
                $roleClass = match($user->role) {
                    'admin' => 'badge--warning',
                    'teacher' => 'badge--primary',
                    'student' => 'badge--info',
                    default => 'badge--secondary'
                };
                ?>
                <span class="badge <?= $roleClass ?>"><?= h(ucfirst($user->role)) ?></span>
                <?php if ($user->active): ?>
                    <span class="badge badge--success"><?= __('Active') ?></span>
                <?php else: ?>
                    <span class="badge badge--danger"><?= __('Inactive') ?></span>
                <?php endif; ?>
            </p>
        </div>
        <div class="admin-section__actions">
            <?= $this->Html->link(__('Edit'), ['action' => 'edit', $user->id], ['class' => 'btn btn--outline']) ?>
            <?= $this->Form->postLink(__('Reset Password'), ['action' => 'resetPassword', $user->id], [
                'class' => 'btn btn--ghost-dark',
                'confirm' => __('Generate a new password for this user?')
            ]) ?>
            <?= $this->Form->postLink(
                $user->active ? __('Deactivate') : __('Activate'),
                ['action' => 'toggleStatus', $user->id],
                ['class' => 'btn ' . ($user->active ? 'btn--ghost-dark' : 'btn--solid')]
            ) ?>
        </div>
    </header>

    <div class="user-profile-header">
        <div class="user-profile-avatar">
            <?php if ($user->avatar): ?>
                <img src="<?= $this->Url->image($user->avatar) ?>" alt="<?= h($user->name) ?>">
            <?php else: ?>
                <span class="avatar-initial avatar-initial--xl"><?= strtoupper(substr($user->name, 0, 1)) ?></span>
            <?php endif; ?>
        </div>
        <div class="user-profile-info">
            <h2><?= h($user->name) ?></h2>
            <p class="text-muted"><?= h($user->email) ?></p>
            <?php if ($user->phone): ?>
                <p><?= h($user->phone) ?></p>
            <?php endif; ?>
        </div>
    </div>

    <div class="admin-grid admin-grid--2 mt-4">
        <div class="admin-card">
            <div class="admin-card__header">
                <h3><?= __('Personal Information') ?></h3>
            </div>
            <div class="admin-card__body">
                <table class="detail-table">
                    <tr>
                        <th><?= __('Full Name') ?></th>
                        <td><?= h($user->name) ?></td>
                    </tr>
                    <tr>
                        <th><?= __('Email') ?></th>
                        <td><?= h($user->email) ?></td>
                    </tr>
                    <tr>
                        <th><?= __('Phone') ?></th>
                        <td><?= h($user->phone ?? '-') ?></td>
                    </tr>
                    <tr>
                        <th><?= __('Date of Birth') ?></th>
                        <td><?= $user->date_of_birth ? $user->date_of_birth->format('F j, Y') : '-' ?></td>
                    </tr>
                    <tr>
                        <th><?= __('Address') ?></th>
                        <td><?= h($user->address ?? '-') ?></td>
                    </tr>
                    <?php if ($user->role === 'student'): ?>
                        <tr>
                            <th><?= __('Grade Level') ?></th>
                            <td><?= h($user->grade_level ?? '-') ?></td>
                        </tr>
                    <?php endif; ?>
                </table>
            </div>
        </div>

        <div class="admin-card">
            <div class="admin-card__header">
                <h3><?= __('Account Information') ?></h3>
            </div>
            <div class="admin-card__body">
                <table class="detail-table">
                    <tr>
                        <th><?= __('Role') ?></th>
                        <td><span class="badge <?= $roleClass ?>"><?= h(ucfirst($user->role)) ?></span></td>
                    </tr>
                    <tr>
                        <th><?= __('Status') ?></th>
                        <td>
                            <?php if ($user->active): ?>
                                <span class="badge badge--success"><?= __('Active') ?></span>
                            <?php else: ?>
                                <span class="badge badge--danger"><?= __('Inactive') ?></span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th><?= __('Joined') ?></th>
                        <td><?= $user->created->format('F j, Y') ?></td>
                    </tr>
                    <tr>
                        <th><?= __('Last Updated') ?></th>
                        <td><?= $user->modified->format('F j, Y g:i A') ?></td>
                    </tr>
                </table>

                <?php if ($user->bio): ?>
                    <div class="mt-3">
                        <h4><?= __('Bio') ?></h4>
                        <p><?= nl2br(h($user->bio)) ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php if ($user->role === 'teacher'): ?>
        <!-- Teacher Subjects -->
        <div class="admin-card mt-4">
            <div class="admin-card__header">
                <h3><?= __('Subject Assignments') ?></h3>
                <?= $this->Html->link(__('Manage Subjects'), ['action' => 'teacherSubjects', $user->id], ['class' => 'btn btn--sm btn--outline']) ?>
            </div>
            <div class="admin-card__body">
                <?php if ($teacherSubjects && $teacherSubjects->count() > 0): ?>
                    <div class="subject-tags">
                        <?php foreach ($teacherSubjects as $ts): ?>
                            <span class="subject-tag<?= $ts->is_primary ? ' subject-tag--primary' : '' ?>">
                                <?= h($ts->subject->name ?? '') ?>
                                <?php if ($ts->is_primary): ?>
                                    <small>(<?= __('Primary') ?>)</small>
                                <?php endif; ?>
                            </span>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="text-muted"><?= __('No subjects assigned yet.') ?></p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Teacher Courses -->
        <div class="admin-card mt-4">
            <div class="admin-card__header">
                <h3><?= __('Assigned Courses') ?> (<?= $teacherCourses ? $teacherCourses->count() : 0 ?>)</h3>
            </div>
            <div class="admin-card__body">
                <?php if ($teacherCourses && $teacherCourses->count() > 0): ?>
                    <div class="table-responsive">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th><?= __('Course') ?></th>
                                    <th><?= __('Class') ?></th>
                                    <th><?= __('Academic Year') ?></th>
                                    <th><?= __('Status') ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($teacherCourses as $course): ?>
                                    <tr>
                                        <td><?= h($course->subject->name ?? '') ?></td>
                                        <td><?= h($course->class->name ?? '') ?></td>
                                        <td><?= h($course->academic_year) ?></td>
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
                    </div>
                <?php else: ?>
                    <p class="text-muted"><?= __('No courses assigned yet.') ?></p>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($user->role === 'student'): ?>
        <!-- Student Stats -->
        <?php if ($attendanceStats): ?>
            <div class="stats-grid stats-grid--3 mt-4">
                <div class="stat-card stat-card--info">
                    <span class="stat-card__icon">&#128197;</span>
                    <div class="stat-card__content">
                        <span class="stat-card__value"><?= $attendanceStats['total'] ?></span>
                        <span class="stat-card__label"><?= __('Total Attendance Days') ?></span>
                    </div>
                </div>
                <div class="stat-card stat-card--success">
                    <span class="stat-card__icon">&#9989;</span>
                    <div class="stat-card__content">
                        <span class="stat-card__value"><?= $attendanceStats['present'] ?></span>
                        <span class="stat-card__label"><?= __('Days Present') ?></span>
                    </div>
                </div>
                <div class="stat-card stat-card--primary">
                    <span class="stat-card__icon">&#128200;</span>
                    <div class="stat-card__content">
                        <span class="stat-card__value"><?= $attendanceStats['percentage'] ?>%</span>
                        <span class="stat-card__label"><?= __('Attendance Rate') ?></span>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Student Classes -->
        <div class="admin-card mt-4">
            <div class="admin-card__header">
                <h3><?= __('Class Assignments') ?></h3>
                <?= $this->Html->link(__('Manage Classes'), ['action' => 'studentClasses', $user->id], ['class' => 'btn btn--sm btn--outline']) ?>
            </div>
            <div class="admin-card__body">
                <?php if ($studentClasses && $studentClasses->count() > 0): ?>
                    <div class="table-responsive">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th><?= __('Class') ?></th>
                                    <th><?= __('Academic Year') ?></th>
                                    <th><?= __('Status') ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($studentClasses as $sc): ?>
                                    <tr>
                                        <td>
                                            <?= h($sc->class->name ?? '') ?>
                                            <?= $sc->class && $sc->class->section ? ' - ' . h($sc->class->section) : '' ?>
                                        </td>
                                        <td><?= h($sc->academic_year) ?></td>
                                        <td>
                                            <?php
                                            $statusClass = match($sc->status) {
                                                'active' => 'badge--success',
                                                'graduated' => 'badge--info',
                                                'transferred' => 'badge--warning',
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
                    <p class="text-muted"><?= __('No class assignments yet.') ?></p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Student Courses -->
        <div class="admin-card mt-4">
            <div class="admin-card__header">
                <h3><?= __('Enrolled Courses') ?> (<?= $studentCourses ? $studentCourses->count() : 0 ?>)</h3>
            </div>
            <div class="admin-card__body">
                <?php if ($studentCourses && $studentCourses->count() > 0): ?>
                    <div class="table-responsive">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th><?= __('Course') ?></th>
                                    <th><?= __('Class') ?></th>
                                    <th><?= __('Teacher') ?></th>
                                    <th><?= __('Grade') ?></th>
                                    <th><?= __('Status') ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($studentCourses as $sc): ?>
                                    <tr>
                                        <td><?= h($sc->course->subject->name ?? '') ?></td>
                                        <td><?= h($sc->course->class->name ?? '') ?></td>
                                        <td><?= h($sc->course->teacher->name ?? __('TBA')) ?></td>
                                        <td>
                                            <?php if ($sc->grade): ?>
                                                <span class="grade-badge"><?= h($sc->grade) ?></span>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
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
                    <p class="text-muted"><?= __('No course enrollments yet.') ?></p>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>

    <div class="admin-card mt-4">
        <div class="admin-card__header">
            <h3><?= __('Danger Zone') ?></h3>
        </div>
        <div class="admin-card__body">
            <div class="danger-actions">
                <div class="danger-action">
                    <div>
                        <strong><?= __('Delete User Account') ?></strong>
                        <p class="text-muted"><?= __('Permanently delete this user account. This action cannot be undone.') ?></p>
                    </div>
                    <?= $this->Form->postLink(__('Delete User'), ['action' => 'delete', $user->id], [
                        'class' => 'btn btn--danger',
                        'confirm' => __('Are you sure you want to permanently delete this user? This action cannot be undone.')
                    ]) ?>
                </div>
            </div>
        </div>
    </div>
</section>
