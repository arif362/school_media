<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\Notification> $notifications
 * @var array $readStatus
 * @var int $unreadCount
 */
use App\Model\Entity\Notification;

$this->assign('title', __('Notifications'));
$types = Notification::getTypes();
?>

<section class="notifications-page">
    <div class="shell">
        <header class="notifications-header">
            <div>
                <h1><?= __('Notifications') ?></h1>
                <p class="text-muted">
                    <?= $unreadCount > 0
                        ? __('You have {0} unread notification(s)', $unreadCount)
                        : __('You\'re all caught up!') ?>
                </p>
            </div>
            <?php if ($unreadCount > 0): ?>
                <?= $this->Form->postLink(
                    __('Mark all as read'),
                    ['action' => 'markAllRead'],
                    ['class' => 'btn btn--ghost-dark']
                ) ?>
            <?php endif; ?>
        </header>

        <?php if ($notifications->isEmpty()): ?>
            <div class="notifications-empty">
                <div class="notifications-empty__icon">&#128276;</div>
                <h3><?= __('No notifications') ?></h3>
                <p><?= __('You don\'t have any notifications yet.') ?></p>
            </div>
        <?php else: ?>
            <div class="notifications-list">
                <?php foreach ($notifications as $notification): ?>
                    <?php $isRead = $readStatus[$notification->id] ?? false; ?>
                    <article class="notification-item <?= !$isRead ? 'notification-item--unread' : '' ?>">
                        <div class="notification-item__indicator type-indicator--<?= h($notification->type) ?>"></div>
                        <div class="notification-item__content">
                            <header class="notification-item__header">
                                <h3>
                                    <?= $this->Html->link(h($notification->title), ['action' => 'view', $notification->id]) ?>
                                </h3>
                                <span class="notification-item__time"><?= $notification->created->timeAgoInWords() ?></span>
                            </header>
                            <p class="notification-item__message">
                                <?= h($this->Text->truncate($notification->message, 150)) ?>
                            </p>
                            <footer class="notification-item__footer">
                                <span class="type-badge type-badge--<?= h($notification->type) ?> type-badge--small">
                                    <?= h($types[$notification->type] ?? $notification->type) ?>
                                </span>
                                <?php if ($notification->link): ?>
                                    <a href="<?= h($notification->link) ?>" class="notification-item__link">
                                        <?= __('View details') ?> &rarr;
                                    </a>
                                <?php endif; ?>
                                <?php if (!$isRead): ?>
                                    <?= $this->Form->postLink(
                                        __('Mark as read'),
                                        ['action' => 'markRead', $notification->id],
                                        ['class' => 'notification-item__mark-read']
                                    ) ?>
                                <?php endif; ?>
                            </footer>
                        </div>
                    </article>
                <?php endforeach; ?>
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
    </div>
</section>
