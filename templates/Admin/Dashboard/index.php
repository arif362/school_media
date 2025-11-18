<?php
/**
 * @var \App\View\AppView $this
 * @var \Authentication\IdentityInterface $user
 * @var array $stats
 */
?>

<section class="admin-layout shell">
    <aside class="admin-sidebar">
        <div class="admin-profile">
            <span class="admin-avatar"><?= strtoupper(substr($user->name ?? 'A', 0, 1)) ?></span>
            <div>
                <strong><?= h($user->name ?? 'Admin User') ?></strong>
                <p><?= h(ucfirst($user->role ?? 'admin')) ?></p>
            </div>
        </div>
        <nav class="admin-nav">
            <?= $this->Html->link(__('Dashboard overview'), ['action' => 'index'], ['class' => 'admin-nav__link is-active']) ?>
            <?= $this->Html->link(__('Posts'), ['prefix' => false, 'controller' => 'Posts', 'action' => 'index'], ['class' => 'admin-nav__link']) ?>
            <?= $this->Html->link(__('Teachers'), '#', ['class' => 'admin-nav__link']) ?>
            <?= $this->Html->link(__('Students'), '#', ['class' => 'admin-nav__link']) ?>
            <?= $this->Html->link(__('User management'), '#', ['class' => 'admin-nav__link']) ?>
        </nav>
    </aside>

    <div class="admin-content">
        <header class="admin-dashboard__header">
            <div>
                <p class="eyebrow text-muted"><?= __('Admin control panel') ?></p>
                <h1><?= __('Welcome back, {0}', h($user->name ?? 'Admin')) ?></h1>
                <p><?= __('Monitor posts, teachers, and students from the central dashboard.') ?></p>
            </div>
            <div class="admin-actions">
                <?= $this->Html->link(__('New Post'), ['prefix' => false, 'controller' => 'Posts', 'action' => 'add'], ['class' => 'btn btn--solid']) ?>
                <?= $this->Html->link(__('Logout'), ['prefix' => false, 'controller' => 'Users', 'action' => 'logout'], ['class' => 'btn btn--ghost']) ?>
            </div>
        </header>

        <div class="admin-metrics">
            <?php foreach ($stats as $stat): ?>
                <article class="metric-card">
                    <p class="metric-label"><?= h($stat['label']) ?></p>
                    <p class="metric-value"><?= (int)$stat['value'] ?></p>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

