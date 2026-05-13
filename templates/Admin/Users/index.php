<?php
/**
 * @var \App\View\AppView $this
 * @var iterable $users
 * @var array $stats
 * @var string|null $role
 * @var string|null $status
 * @var string|null $search
 */
$this->assign('title', __('User Management'));
?>

<section class="admin-section">
    <header class="admin-section__header">
        <div>
            <h1><?= __('User Management') ?></h1>
            <p class="text-muted"><?= __('Manage all system users') ?></p>
        </div>
        <div class="admin-section__actions">
            <?= $this->Html->link(__('Add Teacher'), ['action' => 'addTeacher'], ['class' => 'btn btn--outline']) ?>
            <?= $this->Html->link(__('Add Student'), ['action' => 'addStudent'], ['class' => 'btn btn--solid']) ?>
        </div>
    </header>

    <div class="stats-grid stats-grid--6">
        <div class="stat-card stat-card--compact">
            <span class="stat-card__value"><?= $stats['total'] ?></span>
            <span class="stat-card__label"><?= __('Total Users') ?></span>
        </div>
        <div class="stat-card stat-card--compact stat-card--info">
            <span class="stat-card__value"><?= $stats['students'] ?></span>
            <span class="stat-card__label"><?= __('Students') ?></span>
        </div>
        <div class="stat-card stat-card--compact stat-card--primary">
            <span class="stat-card__value"><?= $stats['teachers'] ?></span>
            <span class="stat-card__label"><?= __('Teachers') ?></span>
        </div>
        <div class="stat-card stat-card--compact stat-card--warning">
            <span class="stat-card__value"><?= $stats['admins'] ?></span>
            <span class="stat-card__label"><?= __('Admins') ?></span>
        </div>
        <div class="stat-card stat-card--compact stat-card--success">
            <span class="stat-card__value"><?= $stats['active'] ?></span>
            <span class="stat-card__label"><?= __('Active') ?></span>
        </div>
        <div class="stat-card stat-card--compact stat-card--danger">
            <span class="stat-card__value"><?= $stats['inactive'] ?></span>
            <span class="stat-card__label"><?= __('Inactive') ?></span>
        </div>
    </div>

    <div class="filter-bar mt-4">
        <?= $this->Form->create(null, ['type' => 'get', 'class' => 'filter-form']) ?>
        <div class="filter-group">
            <input type="text" name="search" value="<?= h($search) ?>" placeholder="<?= __('Search by name or email...') ?>" class="form-control">
            <select name="role" class="form-control">
                <option value=""><?= __('All Roles') ?></option>
                <option value="student" <?= $role === 'student' ? 'selected' : '' ?>><?= __('Students') ?></option>
                <option value="teacher" <?= $role === 'teacher' ? 'selected' : '' ?>><?= __('Teachers') ?></option>
                <option value="admin" <?= $role === 'admin' ? 'selected' : '' ?>><?= __('Admins') ?></option>
            </select>
            <select name="status" class="form-control">
                <option value=""><?= __('All Status') ?></option>
                <option value="active" <?= $status === 'active' ? 'selected' : '' ?>><?= __('Active') ?></option>
                <option value="inactive" <?= $status === 'inactive' ? 'selected' : '' ?>><?= __('Inactive') ?></option>
            </select>
            <?= $this->Form->button(__('Filter'), ['class' => 'btn btn--outline btn--sm']) ?>
            <?= $this->Html->link(__('Clear'), ['action' => 'index'], ['class' => 'btn btn--ghost-dark btn--sm']) ?>
        </div>
        <?= $this->Form->end() ?>
    </div>

    <div class="admin-card mt-4">
        <div class="admin-card__body">
            <?php if (count($users) > 0): ?>
                <div class="table-responsive">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th><?= __('User') ?></th>
                                <th><?= __('Email') ?></th>
                                <th><?= __('Role') ?></th>
                                <th><?= __('Status') ?></th>
                                <th><?= __('Joined') ?></th>
                                <th class="text-right"><?= __('Actions') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $user): ?>
                                <tr>
                                    <td>
                                        <div class="user-info">
                                            <?php if ($user->avatar): ?>
                                                <img src="<?= $this->Url->image($user->avatar) ?>" class="user-avatar" alt="">
                                            <?php else: ?>
                                                <span class="user-avatar"><?= strtoupper(substr($user->name, 0, 1)) ?></span>
                                            <?php endif; ?>
                                            <div>
                                                <strong><?= h($user->name) ?></strong>
                                                <?php if ($user->phone): ?>
                                                    <br><small class="text-muted"><?= h($user->phone) ?></small>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </td>
                                    <td><?= h($user->email) ?></td>
                                    <td>
                                        <?php
                                        $roleClass = match($user->role) {
                                            'admin' => 'badge--warning',
                                            'teacher' => 'badge--primary',
                                            'student' => 'badge--info',
                                            default => 'badge--secondary'
                                        };
                                        ?>
                                        <span class="badge <?= $roleClass ?>"><?= h(ucfirst($user->role)) ?></span>
                                    </td>
                                    <td>
                                        <?php if ($user->active): ?>
                                            <span class="badge badge--success"><?= __('Active') ?></span>
                                        <?php else: ?>
                                            <span class="badge badge--danger"><?= __('Inactive') ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= $user->created->format('M j, Y') ?></td>
                                    <td class="text-right">
                                        <div class="btn-group">
                                            <?= $this->Html->link(__('View'), ['action' => 'view', $user->id], ['class' => 'btn btn--sm btn--ghost']) ?>
                                            <?= $this->Html->link(__('Edit'), ['action' => 'edit', $user->id], ['class' => 'btn btn--sm btn--outline']) ?>
                                            <?= $this->Form->postLink(
                                                $user->active ? __('Deactivate') : __('Activate'),
                                                ['action' => 'toggleStatus', $user->id],
                                                ['class' => 'btn btn--sm ' . ($user->active ? 'btn--ghost-dark' : 'btn--solid')]
                                            ) ?>
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
                    <span class="empty-state__icon">&#128101;</span>
                    <h3><?= __('No Users Found') ?></h3>
                    <p><?= __('No users match your current filters.') ?></p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
