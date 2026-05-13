<?php
/**
 * @var \App\View\AppView $this
 * @var iterable $teachers
 * @var array $teacherSubjects
 * @var array $courseCounts
 * @var array $stats
 * @var string|null $status
 * @var string|null $search
 */
$this->assign('title', __('Teacher Management'));
?>

<section class="admin-section">
    <header class="admin-section__header">
        <div>
            <nav class="breadcrumb">
                <?= $this->Html->link(__('Users'), ['action' => 'index']) ?> / <?= __('Teachers') ?>
            </nav>
            <h1><?= __('Teachers') ?></h1>
            <p class="text-muted"><?= __('Manage teaching staff members') ?></p>
        </div>
        <div class="admin-section__actions">
            <?= $this->Html->link(__('Import Teachers'), ['action' => 'importTeachers'], ['class' => 'btn btn--outline']) ?>
            <?= $this->Html->link(__('Add Teacher'), ['action' => 'addTeacher'], ['class' => 'btn btn--solid']) ?>
        </div>
    </header>

    <div class="stats-grid stats-grid--3">
        <div class="stat-card stat-card--primary">
            <span class="stat-card__icon">&#128104;&#8205;&#127979;</span>
            <div class="stat-card__content">
                <span class="stat-card__value"><?= $stats['total'] ?></span>
                <span class="stat-card__label"><?= __('Total Teachers') ?></span>
            </div>
        </div>
        <div class="stat-card stat-card--success">
            <span class="stat-card__icon">&#9989;</span>
            <div class="stat-card__content">
                <span class="stat-card__value"><?= $stats['active'] ?></span>
                <span class="stat-card__label"><?= __('Active') ?></span>
            </div>
        </div>
        <div class="stat-card stat-card--danger">
            <span class="stat-card__icon">&#10060;</span>
            <div class="stat-card__content">
                <span class="stat-card__value"><?= $stats['inactive'] ?></span>
                <span class="stat-card__label"><?= __('Inactive') ?></span>
            </div>
        </div>
    </div>

    <div class="filter-bar mt-4">
        <?= $this->Form->create(null, ['type' => 'get', 'class' => 'filter-form']) ?>
        <div class="filter-group">
            <input type="text" name="search" value="<?= h($search) ?>" placeholder="<?= __('Search teachers...') ?>" class="form-control">
            <select name="status" class="form-control">
                <option value=""><?= __('All Status') ?></option>
                <option value="active" <?= $status === 'active' ? 'selected' : '' ?>><?= __('Active') ?></option>
                <option value="inactive" <?= $status === 'inactive' ? 'selected' : '' ?>><?= __('Inactive') ?></option>
            </select>
            <?= $this->Form->button(__('Filter'), ['class' => 'btn btn--outline btn--sm']) ?>
        </div>
        <?= $this->Form->end() ?>
    </div>

    <div class="admin-card mt-4">
        <div class="admin-card__body">
            <?php if (count($teachers) > 0): ?>
                <div class="table-responsive">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th><?= __('Teacher') ?></th>
                                <th><?= __('Contact') ?></th>
                                <th><?= __('Subjects') ?></th>
                                <th><?= __('Courses') ?></th>
                                <th><?= __('Status') ?></th>
                                <th class="text-right"><?= __('Actions') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($teachers as $teacher): ?>
                                <tr>
                                    <td>
                                        <div class="user-info">
                                            <?php if ($teacher->avatar): ?>
                                                <img src="<?= $this->Url->image($teacher->avatar) ?>" class="user-avatar" alt="">
                                            <?php else: ?>
                                                <span class="user-avatar user-avatar--teacher"><?= strtoupper(substr($teacher->name, 0, 1)) ?></span>
                                            <?php endif; ?>
                                            <div>
                                                <strong><?= h($teacher->name) ?></strong>
                                                <br><small class="text-muted"><?= __('Joined: {0}', $teacher->created->format('M Y')) ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <?= h($teacher->email) ?>
                                        <?php if ($teacher->phone): ?>
                                            <br><small class="text-muted"><?= h($teacher->phone) ?></small>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (!empty($teacherSubjects[$teacher->id])): ?>
                                            <?php foreach ($teacherSubjects[$teacher->id] as $ts): ?>
                                                <span class="badge badge--info<?= $ts->is_primary ? ' badge--primary' : '' ?>">
                                                    <?= h($ts->subject->name ?? '') ?>
                                                    <?= $ts->is_primary ? '*' : '' ?>
                                                </span>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <span class="text-muted"><?= __('No subjects assigned') ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="badge badge--secondary"><?= $courseCounts[$teacher->id] ?? 0 ?> <?= __('courses') ?></span>
                                    </td>
                                    <td>
                                        <?php if ($teacher->active): ?>
                                            <span class="badge badge--success"><?= __('Active') ?></span>
                                        <?php else: ?>
                                            <span class="badge badge--danger"><?= __('Inactive') ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-right">
                                        <div class="btn-group">
                                            <?= $this->Html->link(__('View'), ['action' => 'view', $teacher->id], ['class' => 'btn btn--sm btn--ghost']) ?>
                                            <?= $this->Html->link(__('Subjects'), ['action' => 'teacherSubjects', $teacher->id], ['class' => 'btn btn--sm btn--outline']) ?>
                                            <?= $this->Html->link(__('Edit'), ['action' => 'edit', $teacher->id], ['class' => 'btn btn--sm btn--outline']) ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="pagination-wrapper mt-4">
                    <?= $this->element('pagination') ?>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <span class="empty-state__icon">&#128104;&#8205;&#127979;</span>
                    <h3><?= __('No Teachers Found') ?></h3>
                    <p><?= __('Start by adding teachers to your school.') ?></p>
                    <?= $this->Html->link(__('Add Teacher'), ['action' => 'addTeacher'], ['class' => 'btn btn--solid']) ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
