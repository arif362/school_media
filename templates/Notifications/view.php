<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Notification $notification
 */
use App\Model\Entity\Notification;

$this->assign('title', h($notification->title));
$types = Notification::getTypes();
?>

<section class="notification-view">
    <div class="shell">
        <nav class="notification-view__breadcrumb">
            <?= $this->Html->link(__('&larr; All Notifications'), ['action' => 'index'], ['escape' => false]) ?>
        </nav>

        <article class="notification-card">
            <header class="notification-card__header">
                <span class="type-badge type-badge--<?= h($notification->type) ?> type-badge--large">
                    <?= h($types[$notification->type] ?? $notification->type) ?>
                </span>
                <h1><?= h($notification->title) ?></h1>
                <time class="notification-card__time">
                    <?= $notification->created->format('F j, Y \a\t g:i A') ?>
                </time>
            </header>

            <div class="notification-card__body">
                <div class="notification-card__message">
                    <?= nl2br(h($notification->message)) ?>
                </div>

                <?php if ($notification->link): ?>
                    <div class="notification-card__action">
                        <a href="<?= h($notification->link) ?>" class="btn btn--solid">
                            <?= __('Go to link') ?> &rarr;
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </article>
    </div>
</section>
