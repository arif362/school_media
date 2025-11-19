<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Post $post
 */

$this->assign('title', __('Create Post'));
$this->assign('dashboardTitle', __('Create a new story'));
$this->assign('dashboardSubtitle', __('Publish campus updates, spotlights, and media recaps to every stakeholder.'));

$this->start('dashboardActions');
echo $this->Html->link(__('View all posts'), ['action' => 'index'], ['class' => 'btn btn--ghost-dark']);
$this->end();

$this->start('breadcrumbs');
echo $this->Html->link(__('Posts'), ['action' => 'index']);
echo '<span>/</span><span>' . __('Create') . '</span>';
$this->end();
?>

<section class="dashboard-form-shell">
    <div class="dashboard-form-card">
        <header class="dashboard-form-card__header">
            <div>
                <span class="pill pill--ghost"><?= __('Editorial') ?></span>
                <h2><?= __('Story metadata') ?></h2>
                <p><?= __('Capture the essentials before publishing to School Media channels.') ?></p>
            </div>
            <div class="dashboard-status">
                <span class="status-dot is-draft"></span>
                <?= __('Draft staging') ?>
            </div>
        </header>
        <?= $this->element('Posts/form', ['post' => $post, 'cancelUrl' => ['action' => 'index']]) ?>
    </div>

    <aside class="dashboard-form-aside">
        <article class="info-card">
            <h4><?= __('Editorial checklist') ?></h4>
            <ul>
                <li><?= __('Lead with impact: what changes for students or staff?') ?></li>
                <li><?= __('Credit contributors and include relevant program tags.') ?></li>
                <li><?= __('Attach supporting media before publishing live.') ?></li>
            </ul>
        </article>
        <article class="info-card">
            <h4><?= __('Publishing tips') ?></h4>
            <p><?= __('Schedule high-traffic posts for early mornings and add a clear CTA in the first paragraph.') ?></p>
        </article>
    </aside>
</section>


