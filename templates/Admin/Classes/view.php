<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\SchoolClass $class
 * @var iterable $students
 */
$this->assign('title', __('View Class'));
?>

<section class="admin-section">
    <header class="admin-section__header">
        <div>
            <nav class="breadcrumb">
                <?= $this->Html->link(__('Classes'), ['action' => 'index']) ?> / <?= __('View') ?>
            </nav>
            <h1><?= h($class->name) ?> <?= $class->section ? '- ' . h($class->section) : '' ?></h1>
        </div>
        <div class="header-actions">
            <?= $this->Html->link(__('Edit'), ['action' => 'edit', $class->id], ['class' => 'btn btn--ghost-dark']) ?>
            <?= $this->Html->link(__('+ Enroll Student'), ['action' => 'enrollStudent', $class->id], ['class' => 'btn btn--solid']) ?>
        </div>
    </header>

    <div class="class-overview">
        <div class="class-info-card">
            <div class="class-info-grid">
                <div class="class-info-item">
                    <span class="label"><?= __('Grade Level') ?></span>
                    <span class="value"><?= h($class->grade_level) ?></span>
                </div>
                <div class="class-info-item">
                    <span class="label"><?= __('Academic Year') ?></span>
                    <span class="value"><?= h($class->academic_year) ?></span>
                </div>
                <div class="class-info-item">
                    <span class="label"><?= __('Class Teacher') ?></span>
                    <span class="value"><?= $class->class_teacher ? h($class->class_teacher->name) : '-' ?></span>
                </div>
                <div class="class-info-item">
                    <span class="label"><?= __('Capacity') ?></span>
                    <span class="value"><?= $class->capacity ?> <?= __('students') ?></span>
                </div>
                <div class="class-info-item">
                    <span class="label"><?= __('Enrolled') ?></span>
                    <span class="value"><?= $students->count() ?> <?= __('students') ?></span>
                </div>
                <div class="class-info-item">
                    <span class="label"><?= __('Status') ?></span>
                    <span class="status-badge <?= $class->is_active ? 'status-badge--published' : 'status-badge--draft' ?>">
                        <?= $class->is_active ? __('Active') : __('Inactive') ?>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="students-section">
        <div class="students-section__header">
            <h3><?= __('Enrolled Students') ?></h3>
            <?= $this->Html->link(
                __('Mark Attendance'),
                ['controller' => 'Attendance', 'action' => 'index', '?' => ['class_id' => $class->id]],
                ['class' => 'btn btn--small btn--solid']
            ) ?>
        </div>

        <?php if ($students->isEmpty()): ?>
            <div class="empty-state empty-state--small">
                <p><?= __('No students enrolled in this class yet.') ?></p>
                <?= $this->Html->link(__('Enroll Students'), ['action' => 'enrollStudent', $class->id], ['class' => 'btn btn--solid btn--small']) ?>
            </div>
        <?php else: ?>
            <table class="data-table">
                <thead>
                    <tr>
                        <th><?= __('Roll #') ?></th>
                        <th><?= __('Student Name') ?></th>
                        <th><?= __('Email') ?></th>
                        <th><?= __('Enrolled Date') ?></th>
                        <th><?= __('Status') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $index = 1; ?>
                    <?php foreach ($students as $studentClass): ?>
                        <tr>
                            <td><?= $studentClass->roll_number ?: $index ?></td>
                            <td>
                                <div class="student-info">
                                    <?php if ($studentClass->student->avatar): ?>
                                        <img src="<?= $this->Url->image($studentClass->student->avatar) ?>" alt="" class="student-avatar-small">
                                    <?php else: ?>
                                        <span class="student-avatar-initial-small"><?= strtoupper(substr($studentClass->student->name, 0, 1)) ?></span>
                                    <?php endif; ?>
                                    <span><?= h($studentClass->student->name) ?></span>
                                </div>
                            </td>
                            <td><?= h($studentClass->student->email) ?></td>
                            <td><?= $studentClass->enrolled_date->format('M j, Y') ?></td>
                            <td>
                                <span class="status-badge status-badge--<?= $studentClass->status === 'active' ? 'published' : 'draft' ?>">
                                    <?= h(ucfirst($studentClass->status)) ?>
                                </span>
                            </td>
                        </tr>
                        <?php $index++; ?>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</section>
