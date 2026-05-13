<?php
/**
 * @var \App\View\AppView $this
 */
$identity = $this->request->getAttribute('identity');
$identityName = $identity?->get('name') ?? __('Content Lead');
$identityRole = $identity?->get('role') ?? 'admin';
$identityAvatar = $identity?->get('avatar');
$identityInitial = strtoupper(substr((string)$identityName, 0, 1)) ?: 'A';
$navLinks = [
    ['label' => __('Dashboard'), 'url' => $this->Url->build('/admin'), 'icon' => '&#127968;'],
    ['label' => __('Posts'), 'url' => $this->Url->build('/posts'), 'icon' => '&#128196;'],
    ['label' => __('Classes'), 'url' => $this->Url->build('/admin/classes'), 'icon' => '&#127979;'],
    ['label' => __('Subjects'), 'url' => $this->Url->build('/admin/subjects'), 'icon' => '&#128218;'],
    ['label' => __('Courses'), 'url' => $this->Url->build('/admin/courses'), 'icon' => '&#128214;'],
    ['label' => __('Attendance'), 'url' => $this->Url->build('/admin/attendance'), 'icon' => '&#128197;'],
    ['label' => __('Notifications'), 'url' => $this->Url->build('/admin/notifications'), 'icon' => '&#128276;'],
    ['label' => __('Teachers'), 'url' => $this->Url->build('/admin/users/teachers'), 'icon' => '&#128104;&#8205;&#127979;'],
    ['label' => __('Students'), 'url' => $this->Url->build('/admin/users/students'), 'icon' => '&#128100;'],
    ['label' => __('All Users'), 'url' => $this->Url->build('/admin/users'), 'icon' => '&#128101;'],
];
$userNotificationsTable = \Cake\ORM\TableRegistry::getTableLocator()->get('UserNotifications');
$notificationCount = $identity ? $userNotificationsTable->getUnreadCount($identity->id, $identity->role) : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $this->fetch('title') ? $this->fetch('title') . ' | ' : '' ?>School Media</title>
    <?= $this->Html->meta('icon') ?>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <?= $this->Html->css('school_media') ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
</head>
<body class="sm-body dashboard-body">
    <div class="dashboard-shell">
        <aside class="dashboard-sidebar">
            <div class="dashboard-sidebar__inner">
                <a class="dashboard-sidebar__brand" href="<?= $this->Url->build('/') ?>">School Media</a>
                <div class="dashboard-profile">
                    <?php if ($identityAvatar): ?>
                        <img src="<?= $this->Url->image($identityAvatar) ?>" alt="<?= h($identityName) ?>" class="dashboard-avatar-img">
                    <?php else: ?>
                        <span class="dashboard-avatar"><?= $identityInitial ?></span>
                    <?php endif; ?>
                    <div>
                        <strong><?= h($identityName) ?></strong>
                        <p><?= h(ucfirst($identityRole)) ?></p>
                    </div>
                </div>
                <nav class="dashboard-nav">
                    <?php foreach ($navLinks as $link): ?>
                        <a class="dashboard-nav__link" href="<?= h($link['url']) ?>">
                            <?php if (!empty($link['icon'])): ?>
                                <span class="nav-icon"><?= $link['icon'] ?></span>
                            <?php endif; ?>
                            <?= h($link['label']) ?>
                        </a>
                    <?php endforeach; ?>
                </nav>
            </div>
            <div class="dashboard-sidebar__cta">
                <a class="btn btn--ghost w-full" href="<?= $this->Url->build('/logout') ?>"><?= __('Logout') ?></a>
            </div>
        </aside>

        <main class="dashboard-main">
            <div class="dashboard-main__top-actions">
                <?= $this->element('notification_bell', ['unreadCount' => $notificationCount]) ?>
                <?= $this->Html->link(__('View site →'), '/', ['class' => 'dashboard-main__link']) ?>
            </div>
            <?php if ($this->fetch('dashboardTitle') || $this->fetch('dashboardSubtitle') || $this->fetch('dashboardActions')): ?>
            <header class="dashboard-header">
                <div>
                    <?php if ($this->fetch('dashboardEyebrow')): ?>
                        <p class="eyebrow text-muted"><?= $this->fetch('dashboardEyebrow') ?></p>
                    <?php endif; ?>
                    <?php if ($this->fetch('dashboardTitle')): ?>
                        <h1><?= $this->fetch('dashboardTitle') ?></h1>
                    <?php endif; ?>
                    <?php if ($this->fetch('dashboardSubtitle')): ?>
                        <p><?= $this->fetch('dashboardSubtitle') ?></p>
                    <?php endif; ?>
                </div>
                <?php if (trim($this->fetch('dashboardActions')) !== ''): ?>
                    <div class="dashboard-header__actions">
                        <?= $this->fetch('dashboardActions') ?>
                    </div>
                <?php endif; ?>
            </header>
            <?php endif; ?>

            <?php if (trim($this->fetch('breadcrumbs')) !== ''): ?>
                <div class="dashboard-breadcrumbs">
                    <?= $this->fetch('breadcrumbs') ?>
                </div>
            <?php endif; ?>

            <div class="toast-stack" aria-live="polite" aria-atomic="true">
                <?= $this->Flash->render() ?>
            </div>

            <?= $this->fetch('content') ?>
        </main>
    </div>

    <?= $this->fetch('script') ?>
</body>
</html>

