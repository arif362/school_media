<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Subject $subject
 * @var iterable $teachers
 */
$this->assign('title', __('View Subject'));
?>

<section class="admin-section">
    <header class="admin-section__header">
        <div>
            <nav class="breadcrumb">
                <?= $this->Html->link(__('Subjects'), ['action' => 'index']) ?> / <?= __('View') ?>
            </nav>
            <h1><?= h($subject->name) ?> <span class="subject-code"><?= h($subject->code) ?></span></h1>
        </div>
        <div class="header-actions">
            <?= $this->Html->link(__('Edit'), ['action' => 'edit', $subject->id], ['class' => 'btn btn--ghost-dark']) ?>
            <?= $this->Html->link(__('+ Create Course'), ['controller' => 'Courses', 'action' => 'add', '?' => ['subject_id' => $subject->id]], ['class' => 'btn btn--solid']) ?>
        </div>
    </header>

    <div class="view-grid">
        <div class="view-card">
            <h3><?= __('Subject Details') ?></h3>
            <div class="detail-list">
                <div class="detail-item">
                    <span class="detail-label"><?= __('Category') ?></span>
                    <span class="detail-value">
                        <?php
                        $categoryClass = match ($subject->category) {
                            'Core' => 'badge--primary',
                            'Elective' => 'badge--info',
                            'Co-curricular' => 'badge--success',
                            default => 'badge--secondary',
                        };
                        ?>
                        <span class="badge <?= $categoryClass ?>"><?= h($subject->category) ?></span>
                    </span>
                </div>
                <div class="detail-item">
                    <span class="detail-label"><?= __('Credit Hours') ?></span>
                    <span class="detail-value"><?= $subject->credit_hours ?></span>
                </div>
                <div class="detail-item">
                    <span class="detail-label"><?= __('Status') ?></span>
                    <span class="detail-value">
                        <span class="status-badge <?= $subject->is_active ? 'status-badge--published' : 'status-badge--draft' ?>">
                            <?= $subject->is_active ? __('Active') : __('Inactive') ?>
                        </span>
                    </span>
                </div>
                <?php if ($subject->description): ?>
                    <div class="detail-item detail-item--full">
                        <span class="detail-label"><?= __('Description') ?></span>
                        <span class="detail-value"><?= h($subject->description) ?></span>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="view-card">
            <h3><?= __('Teachers') ?></h3>
            <?php if ($teachers->isEmpty()): ?>
                <p class="text-muted"><?= __('No teachers assigned to this subject.') ?></p>
            <?php else: ?>
                <ul class="teacher-list">
                    <?php foreach ($teachers as $ts): ?>
                        <li>
                            <span class="teacher-name"><?= h($ts->teacher->name) ?></span>
                            <?php if ($ts->is_primary): ?>
                                <span class="badge badge--primary"><?= __('Primary') ?></span>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>

    <div class="section-card">
        <div class="section-card__header">
            <h3><?= __('Courses') ?></h3>
        </div>
        <?php if (empty($subject->courses)): ?>
            <div class="empty-state empty-state--small">
                <p><?= __('No courses created for this subject yet.') ?></p>
                <?= $this->Html->link(__('Create Course'), ['controller' => 'Courses', 'action' => 'add', '?' => ['subject_id' => $subject->id]], ['class' => 'btn btn--solid btn--small']) ?>
            </div>
        <?php else: ?>
            <table class="data-table">
                <thead>
                    <tr>
                        <th><?= __('Class') ?></th>
                        <th><?= __('Teacher') ?></th>
                        <th><?= __('Academic Year') ?></th>
                        <th><?= __('Term') ?></th>
                        <th><?= __('Status') ?></th>
                        <th><?= __('Actions') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($subject->courses as $course): ?>
                        <tr>
                            <td>
                                <strong><?= h($course->class->name) ?></strong>
                                <?= $course->class->section ? '- ' . h($course->class->section) : '' ?>
                            </td>
                            <td><?= $course->teacher ? h($course->teacher->name) : '-' ?></td>
                            <td><?= h($course->academic_year) ?></td>
                            <td><?= $course->term ?: '-' ?></td>
                            <td>
                                <span class="status-badge <?= $course->is_active ? 'status-badge--published' : 'status-badge--draft' ?>">
                                    <?= $course->is_active ? __('Active') : __('Inactive') ?>
                                </span>
                            </td>
                            <td>
                                <?= $this->Html->link(__('View'), ['controller' => 'Courses', 'action' => 'view', $course->id], ['class' => 'action-btn action-btn--view']) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</section>
