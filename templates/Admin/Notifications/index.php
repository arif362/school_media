<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\Notification> $notifications
 * @var array $types
 */
$this->assign('title', __('Manage Notifications'));
?>

<section class="admin-section">
    <header class="admin-section__header">
        <div>
            <h1><?= __('Notifications') ?></h1>
            <p class="text-muted"><?= __('Create and manage system notifications for users') ?></p>
        </div>
        <?= $this->Html->link(__('+ New Notification'), ['action' => 'add'], ['class' => 'btn btn--solid']) ?>
    </header>

    <div class="admin-filters">
        <?= $this->Form->create(null, ['type' => 'get', 'class' => 'filters-form']) ?>
            <div class="filters-row">
                <div class="filter-group">
                    <?= $this->Form->control('search', [
                        'label' => false,
                        'placeholder' => __('Search notifications...'),
                        'value' => $search ?? '',
                        'class' => 'form-control',
                        'templates' => ['inputContainer' => '{{content}}'],
                    ]) ?>
                </div>
                <div class="filter-group">
                    <?= $this->Form->control('type', [
                        'label' => false,
                        'options' => ['' => __('All Types')] + $types,
                        'value' => $type ?? '',
                        'class' => 'form-control',
                        'templates' => ['inputContainer' => '{{content}}'],
                    ]) ?>
                </div>
                <div class="filter-group">
                    <?= $this->Form->control('status', [
                        'label' => false,
                        'options' => ['' => __('All Status'), 'active' => __('Active'), 'inactive' => __('Inactive')],
                        'value' => $status ?? '',
                        'class' => 'form-control',
                        'templates' => ['inputContainer' => '{{content}}'],
                    ]) ?>
                </div>
                <button type="submit" class="btn btn--solid btn--small"><?= __('Filter') ?></button>
                <?= $this->Html->link(__('Clear'), ['action' => 'index'], ['class' => 'btn btn--ghost-dark btn--small']) ?>
            </div>
        <?= $this->Form->end() ?>
    </div>

    <?php if ($notifications->isEmpty()): ?>
        <div class="empty-state">
            <div class="empty-state__icon">&#128276;</div>
            <h3><?= __('No notifications found') ?></h3>
            <p><?= __('Create your first notification to communicate with users.') ?></p>
            <?= $this->Html->link(__('Create Notification'), ['action' => 'add'], ['class' => 'btn btn--solid']) ?>
        </div>
    <?php else: ?>
        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th><?= $this->Paginator->sort('title', __('Title')) ?></th>
                        <th><?= $this->Paginator->sort('type', __('Type')) ?></th>
                        <th><?= __('Target') ?></th>
                        <th><?= $this->Paginator->sort('is_active', __('Status')) ?></th>
                        <th><?= $this->Paginator->sort('created', __('Created')) ?></th>
                        <th class="text-right"><?= __('Actions') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($notifications as $notification): ?>
                        <tr>
                            <td>
                                <div class="notification-title-cell">
                                    <strong><?= $this->Html->link(h($notification->title), ['action' => 'view', $notification->id]) ?></strong>
                                    <span class="text-muted text-small"><?= h($this->Text->truncate($notification->message, 60)) ?></span>
                                </div>
                            </td>
                            <td>
                                <span class="type-badge type-badge--<?= h($notification->type) ?>">
                                    <?= h($types[$notification->type] ?? $notification->type) ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($notification->target_user_id): ?>
                                    <span class="target-badge"><?= __('User #{0}', $notification->target_user_id) ?></span>
                                <?php elseif ($notification->target_role): ?>
                                    <span class="target-badge"><?= h(ucfirst($notification->target_role)) ?>s</span>
                                <?php else: ?>
                                    <span class="target-badge target-badge--all"><?= __('All Users') ?></span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="status-badge <?= $notification->is_active ? 'status-badge--published' : 'status-badge--draft' ?>">
                                    <?= $notification->is_active ? __('Active') : __('Inactive') ?>
                                </span>
                            </td>
                            <td>
                                <div class="date-cell">
                                    <span><?= $notification->created->format('M j, Y') ?></span>
                                    <span class="text-muted text-small"><?= $notification->created->format('g:i A') ?></span>
                                </div>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <?= $this->Html->link(__('View'), ['action' => 'view', $notification->id], ['class' => 'action-btn action-btn--view']) ?>
                                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $notification->id], ['class' => 'action-btn action-btn--edit']) ?>
                                    <?= $this->Form->postLink(
                                        $notification->is_active ? __('Deactivate') : __('Activate'),
                                        ['action' => 'toggleStatus', $notification->id],
                                        ['class' => 'action-btn action-btn--' . ($notification->is_active ? 'warning' : 'success')]
                                    ) ?>
                                    <?= $this->Form->postLink(
                                        __('Delete'),
                                        ['action' => 'delete', $notification->id],
                                        [
                                            'confirm' => __('Delete this notification?'),
                                            'class' => 'action-btn action-btn--delete',
                                        ]
                                    ) ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="pagination-wrapper">
            <div class="pagination-summary">
                <?= $this->Paginator->counter(__('Showing {{start}} to {{end}} of {{count}}')) ?>
            </div>
            <nav class="pagination-modern">
                <?= $this->Paginator->prev(__('Prev')) ?>
                <?= $this->Paginator->numbers() ?>
                <?= $this->Paginator->next(__('Next')) ?>
            </nav>
        </div>
    <?php endif; ?>
</section>
