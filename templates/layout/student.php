<?php
/**
 * @var \App\View\AppView $this
 */
$identity = $this->request->getAttribute('identity');
$identityName = $identity?->get('name') ?? __('Student');
$identityRole = $identity?->get('role') ?? 'student';
$identityAvatar = $identity?->get('avatar');
$identityInitial = strtoupper(substr((string)$identityName, 0, 1)) ?: 'S';
$currentUrl = $this->request->getRequestTarget();
$navLinks = [
    ['label' => __('Dashboard'), 'url' => '/student', 'icon' => '&#127968;'],
    ['label' => __('My Profile'), 'url' => '/student/profile', 'icon' => '&#128100;'],
    ['label' => __('Edit Profile'), 'url' => '/student/profile/edit', 'icon' => '&#9998;'],
    ['label' => __('Notifications'), 'url' => '/notifications', 'icon' => '&#128276;'],
    ['label' => __('Browse Posts'), 'url' => '/posts', 'icon' => '&#128196;'],
];
$userNotificationsTable = \Cake\ORM\TableRegistry::getTableLocator()->get('UserNotifications');
$notificationCount = $identity ? $userNotificationsTable->getUnreadCount($identity->id, $identity->role) : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $this->fetch('title') ? $this->fetch('title') . ' | ' : '' ?>School Media - Student Portal</title>
    <?= $this->Html->meta('icon') ?>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <?= $this->Html->css('school_media') ?>
    <?= $this->Html->css('student') ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
</head>
<body class="sm-body student-body">
    <div class="dashboard-shell">
        <aside class="dashboard-sidebar student-sidebar">
            <div class="dashboard-sidebar__inner">
                <a class="dashboard-sidebar__brand" href="<?= $this->Url->build('/') ?>">School Media</a>
                <div class="dashboard-profile">
                    <?php if ($identityAvatar): ?>
                        <img src="<?= $this->Url->image($identityAvatar) ?>" alt="<?= h($identityName) ?>" class="dashboard-avatar-img">
                    <?php else: ?>
                        <span class="dashboard-avatar student-avatar"><?= $identityInitial ?></span>
                    <?php endif; ?>
                    <div>
                        <strong><?= h($identityName) ?></strong>
                        <p><?= h(ucfirst($identityRole)) ?></p>
                    </div>
                </div>
                <nav class="dashboard-nav">
                    <?php foreach ($navLinks as $link): ?>
                        <?php
                        $isActive = ($currentUrl === $link['url']) ||
                            ($link['url'] === '/student' && $currentUrl === '/student/') ||
                            ($link['url'] === '/student/profile' && str_starts_with($currentUrl, '/student/profile') && $currentUrl !== '/student/profile/edit');
                        ?>
                        <a class="dashboard-nav__link<?= $isActive ? ' is-active' : '' ?>" href="<?= $this->Url->build($link['url']) ?>">
                            <span class="nav-icon"><?= $link['icon'] ?></span>
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
                <?= $this->Html->link(__('View site'), '/', ['class' => 'dashboard-main__link']) ?>
            </div>
            <header class="dashboard-header">
                <div>
                    <p class="eyebrow text-muted">
                        <?= $this->fetch('dashboardEyebrow') ?: __('Student Portal') ?>
                    </p>
                    <h1><?= $this->fetch('dashboardTitle') ?: ($this->fetch('title') ?: __('Dashboard')) ?></h1>
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
