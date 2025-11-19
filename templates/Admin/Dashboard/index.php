<?php
/**
 * @var \App\View\AppView $this
 * @var \Authentication\IdentityInterface $user
 * @var array $stats
 */

$this->assign('title', __('Admin Dashboard'));
$this->assign('dashboardTitle', __('Welcome back, {0}', h($user->name ?? 'Admin')));
$this->assign('dashboardSubtitle', __('Monitor posts, teachers, and students from the central dashboard.'));

$this->start('dashboardActions');
echo $this->Html->link(
    __('New Post'),
    ['prefix' => false, 'controller' => 'Posts', 'action' => 'add'],
    ['class' => 'btn btn--solid']
);
$this->end();
?>

<section class="admin-content">
    <div class="admin-metrics">
        <?php foreach ($stats as $stat): ?>
            <article class="metric-card">
                <p class="metric-label"><?= h($stat['label']) ?></p>
                <p class="metric-value"><?= (int)$stat['value'] ?></p>
            </article>
        <?php endforeach; ?>
    </div>
</section>

