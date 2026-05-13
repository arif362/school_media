<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Notification $notification
 * @var int $readCount
 */
use App\Model\Entity\Notification;

$this->assign('title', __('View Notification'));
$types = Notification::getTypes();
?>

<section class="admin-section">
    <header class="admin-section__header">
        <div>
            <nav class="breadcrumb">
                <?= $this->Html->link(__('Notifications'), ['action' => 'index']) ?> / <?= __('View') ?>
            </nav>
            <h1><?= h($notification->title) ?></h1>
        </div>
        <div class="header-actions">
            <?= $this->Html->link(__('Edit'), ['action' => 'edit', $notification->id], ['class' => 'btn btn--ghost-dark']) ?>
            <?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $notification->id],
                ['confirm' => __('Delete this notification?'), 'class' => 'btn btn--danger']
            ) ?>
        </div>
    </header>

    <div class="view-card">
        <div class="view-card__header">
            <div class="notification-meta">
                <span class="type-badge type-badge--<?= h($notification->type) ?> type-badge--large">
                    <?= h($types[$notification->type] ?? $notification->type) ?>
                </span>
                <span class="status-badge <?= $notification->is_active ? 'status-badge--published' : 'status-badge--draft' ?>">
                    <?= $notification->is_active ? __('Active') : __('Inactive') ?>
                </span>
            </div>
        </div>

        <div class="view-card__body">
            <div class="view-section">
                <h3><?= __('Message') ?></h3>
                <div class="notification-message">
                    <?= nl2br(h($notification->message)) ?>
                </div>
            </div>

            <?php if ($notification->link): ?>
                <div class="view-section">
                    <h3><?= __('Link') ?></h3>
                    <a href="<?= h($notification->link) ?>" class="notification-link" target="_blank">
                        <?= h($notification->link) ?>
                    </a>
                </div>
            <?php endif; ?>

            <div class="view-grid">
                <div class="view-section">
                    <h3><?= __('Target Audience') ?></h3>
                    <?php if ($notification->target_user_id): ?>
                        <p>
                            <strong><?= __('Specific User:') ?></strong>
                            <?= $notification->target_user ? h($notification->target_user->name) : __('User #{0}', $notification->target_user_id) ?>
                        </p>
                    <?php elseif ($notification->target_role): ?>
                        <p><strong><?= h(ucfirst($notification->target_role)) ?>s</strong></p>
                    <?php else: ?>
                        <p><strong><?= __('All Users') ?></strong></p>
                    <?php endif; ?>
                </div>

                <div class="view-section">
                    <h3><?= __('Statistics') ?></h3>
                    <p>
                        <span class="stat-number"><?= $readCount ?></span>
                        <span class="stat-label"><?= __('users have read this notification') ?></span>
                    </p>
                </div>

                <div class="view-section">
                    <h3><?= __('Created') ?></h3>
                    <p>
                        <?= $notification->created->format('F j, Y \a\t g:i A') ?>
                        <?php if ($notification->creator): ?>
                            <br><span class="text-muted"><?= __('by {0}', h($notification->creator->name)) ?></span>
                        <?php endif; ?>
                    </p>
                </div>

                <div class="view-section">
                    <h3><?= __('Last Modified') ?></h3>
                    <p><?= $notification->modified->format('F j, Y \a\t g:i A') ?></p>
                </div>
            </div>
        </div>
    </div>
</section>
