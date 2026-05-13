<?php
/**
 * @var \App\View\AppView $this
 * @var iterable $students
 * @var array $studentClasses
 * @var array $courseCounts
 * @var array $stats
 * @var array $classes
 * @var string|null $status
 * @var string|null $search
 * @var string|null $classId
 */
$this->assign('title', __('Student Management'));
?>

<section class="admin-section">
    <header class="admin-section__header">
        <div>
            <nav class="breadcrumb">
                <?= $this->Html->link(__('Users'), ['action' => 'index']) ?> / <?= __('Students') ?>
            </nav>
            <h1><?= __('Student Management') ?></h1>
            <p class="text-muted"><?= __('Onboard and manage student accounts') ?></p>
        </div>
        <div class="admin-section__actions">
            <?= $this->Html->link(__('Import Students'), ['action' => 'importStudents'], ['class' => 'btn btn--outline']) ?>
            <?= $this->Html->link(__('Add Student'), ['action' => 'addStudent'], ['class' => 'btn btn--solid']) ?>
        </div>
    </header>

    <div class="stats-grid stats-grid--3">
        <div class="stat-card stat-card--info">
            <span class="stat-card__icon">&#128100;</span>
            <div class="stat-card__content">
                <span class="stat-card__value"><?= $stats['total'] ?></span>
                <span class="stat-card__label"><?= __('Total Students') ?></span>
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
            <input type="text" name="search" value="<?= h($search) ?>" placeholder="<?= __('Search students...') ?>" class="form-control">
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
            <?php if (count($students) > 0): ?>
                <div class="table-responsive">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th><?= __('Student') ?></th>
                                <th><?= __('Contact') ?></th>
                                <th><?= __('Class') ?></th>
                                <th><?= __('Courses') ?></th>
                                <th><?= __('Status') ?></th>
                                <th class="text-right"><?= __('Actions') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($students as $student): ?>
                                <tr>
                                    <td>
                                        <div class="user-info">
                                            <?php if ($student->avatar): ?>
                                                <img src="<?= $this->Url->image($student->avatar) ?>" class="user-avatar" alt="">
                                            <?php else: ?>
                                                <span class="user-avatar user-avatar--student"><?= strtoupper(substr($student->name, 0, 1)) ?></span>
                                            <?php endif; ?>
                                            <div>
                                                <strong><?= h($student->name) ?></strong>
                                                <?php if ($student->grade_level): ?>
                                                    <br><small class="text-muted"><?= h($student->grade_level) ?></small>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <?= h($student->email) ?>
                                        <?php if ($student->phone): ?>
                                            <br><small class="text-muted"><?= h($student->phone) ?></small>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (!empty($studentClasses[$student->id])): ?>
                                            <?php foreach ($studentClasses[$student->id] as $sc): ?>
                                                <span class="badge badge--info">
                                                    <?= h($sc->class->name ?? '') ?>
                                                    <?= $sc->class && $sc->class->section ? ' - ' . h($sc->class->section) : '' ?>
                                                </span>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <span class="text-muted"><?= __('Not assigned') ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="badge badge--secondary"><?= $courseCounts[$student->id] ?? 0 ?> <?= __('courses') ?></span>
                                    </td>
                                    <td>
                                        <?php if ($student->active): ?>
                                            <span class="badge badge--success"><?= __('Active') ?></span>
                                        <?php else: ?>
                                            <span class="badge badge--danger"><?= __('Inactive') ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-right">
                                        <div class="btn-group">
                                            <?= $this->Html->link(__('View'), ['action' => 'view', $student->id], ['class' => 'btn btn--sm btn--ghost']) ?>
                                            <?= $this->Html->link(__('Classes'), ['action' => 'studentClasses', $student->id], ['class' => 'btn btn--sm btn--outline']) ?>
                                            <?= $this->Html->link(__('Edit'), ['action' => 'edit', $student->id], ['class' => 'btn btn--sm btn--outline']) ?>
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
                    <span class="empty-state__icon">&#128100;</span>
                    <h3><?= __('No Students Found') ?></h3>
                    <p><?= __('Start by adding students to your school.') ?></p>
                    <?= $this->Html->link(__('Add Student'), ['action' => 'addStudent'], ['class' => 'btn btn--solid']) ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
