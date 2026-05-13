<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 * @var \Cake\ORM\ResultSet $recentPosts
 */
$this->assign('title', __('Student Dashboard'));
$this->assign('dashboardTitle', __('Welcome back, {0}!', $user->name));
$this->assign('dashboardSubtitle', __('Here\'s what\'s happening in your school media portal.'));
?>

<div class="student-dashboard">
    <div class="dashboard-grid">
        <div class="dashboard-card profile-summary">
            <div class="dashboard-card__header">
                <h3><?= __('My Profile') ?></h3>
                <?= $this->Html->link(__('Edit'), ['controller' => 'Profile', 'action' => 'edit'], ['class' => 'btn btn--small btn--secondary']) ?>
            </div>
            <div class="dashboard-card__body">
                <div class="profile-summary__content">
                    <div class="profile-summary__avatar">
                        <?php if ($user->avatar): ?>
                            <img src="<?= $this->Url->image($user->avatar) ?>" alt="<?= h($user->name) ?>">
                        <?php else: ?>
                            <span class="profile-avatar-initial"><?= strtoupper(substr($user->name, 0, 1)) ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="profile-summary__info">
                        <h4><?= h($user->name) ?></h4>
                        <p><?= h($user->email) ?></p>
                        <?php if ($user->grade_level): ?>
                            <span class="badge badge--primary"><?= h($user->grade_level) ?></span>
                        <?php endif; ?>
                    </div>
                </div>
                <?php if (!$user->bio && !$user->phone && !$user->grade_level): ?>
                    <div class="profile-summary__cta">
                        <p><?= __('Your profile is incomplete.') ?></p>
                        <?= $this->Html->link(__('Complete your profile'), ['controller' => 'Profile', 'action' => 'edit'], ['class' => 'btn btn--primary btn--small']) ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="dashboard-card quick-links">
            <div class="dashboard-card__header">
                <h3><?= __('Quick Links') ?></h3>
            </div>
            <div class="dashboard-card__body">
                <div class="quick-links__grid">
                    <?= $this->Html->link(
                        '<span class="quick-link__icon">&#128100;</span><span>' . __('My Profile') . '</span>',
                        ['controller' => 'Profile', 'action' => 'view'],
                        ['class' => 'quick-link', 'escape' => false]
                    ) ?>
                    <?= $this->Html->link(
                        '<span class="quick-link__icon">&#128196;</span><span>' . __('Browse Posts') . '</span>',
                        ['prefix' => false, 'controller' => 'Posts', 'action' => 'index'],
                        ['class' => 'quick-link', 'escape' => false]
                    ) ?>
                    <?= $this->Html->link(
                        '<span class="quick-link__icon">&#9881;</span><span>' . __('Edit Profile') . '</span>',
                        ['controller' => 'Profile', 'action' => 'edit'],
                        ['class' => 'quick-link', 'escape' => false]
                    ) ?>
                </div>
            </div>
        </div>
    </div>

    <div class="dashboard-card recent-posts">
        <div class="dashboard-card__header">
            <h3><?= __('Recent Posts') ?></h3>
            <?= $this->Html->link(__('View All'), ['prefix' => false, 'controller' => 'Posts', 'action' => 'index'], ['class' => 'btn btn--small btn--secondary']) ?>
        </div>
        <div class="dashboard-card__body">
            <?php if ($recentPosts->isEmpty()): ?>
                <p class="text-muted"><?= __('No posts available yet.') ?></p>
            <?php else: ?>
                <div class="posts-list">
                    <?php foreach ($recentPosts as $post): ?>
                        <article class="post-item">
                            <h4>
                                <?= $this->Html->link(h($post->title), ['prefix' => false, 'controller' => 'Posts', 'action' => 'view', $post->slug]) ?>
                            </h4>
                            <p class="post-item__meta">
                                <?= $post->created->format('M j, Y') ?>
                            </p>
                            <p class="post-item__excerpt">
                                <?= $this->Text->truncate(strip_tags($post->body), 120, ['ellipsis' => '...', 'exact' => false]) ?>
                            </p>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
