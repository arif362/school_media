<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Post $post
 */

$this->assign('title', __('Edit Post'));
$this->assign('dashboardTitle', __('Update story'));
$this->assign('dashboardSubtitle', __('Refresh copy, metadata, or visibility before sending it back to readers.'));

$this->start('dashboardActions');
echo $this->Html->link(__('View story'), ['action' => 'view', $post->id], ['class' => 'btn btn--ghost-dark']);
echo $this->Form->postLink(
    __('Delete'),
    ['action' => 'delete', $post->id],
    [
        'confirm' => __('Are you sure you want to delete "{0}"?', $post->title),
        'class' => 'btn btn--danger',
    ]
);
$this->end();

$this->start('breadcrumbs');
echo $this->Html->link(__('Posts'), ['action' => 'index']);
echo '<span>/</span>';
echo $this->Html->link(h($post->title), ['action' => 'view', $post->id]);
echo '<span>/</span><span>' . __('Edit') . '</span>';
$this->end();

$statusLabel = $post->published ? __('Live') : __('Draft');
$statusClass = $post->published ? 'is-live' : 'is-draft';
?>

<section class="dashboard-form-shell">
    <div class="dashboard-form-card">
        <header class="dashboard-form-card__header">
            <div>
                <span class="pill pill--ghost"><?= __('Editorial') ?></span>
                <h2><?= __('Story metadata') ?></h2>
                <p><?= __('Make precise edits while keeping your audit trail intact.') ?></p>
            </div>
            <div class="dashboard-status">
                <span class="status-dot <?= h($statusClass) ?>"></span>
                <?= h($statusLabel) ?>
            </div>
        </header>
        <?= $this->element('Posts/form', [
            'post' => $post,
            'cancelUrl' => ['action' => 'view', $post->id],
        ]) ?>
    </div>

    <aside class="dashboard-form-aside">
        <article class="info-card">
            <h4><?= __('Revision log') ?></h4>
            <p><?= __('Keep notes on major changes so admins can review the editorial timeline quickly.') ?></p>
            <ul>
                <li><?= __('Highlight new sources or quotes added') ?></li>
                <li><?= __('Call out multimedia or embeds that changed') ?></li>
                <li><?= __('List compliance or approval steps completed') ?></li>
            </ul>
        </article>
        <article class="info-card">
            <h4><?= __('Need approvals?') ?></h4>
            <p><?= __('Ping admins via the dashboard chat before toggling a story live to ensure consistent messaging.') ?></p>
        </article>
    </aside>
</section>


