<?php
/**
 * @var \App\View\AppView $this
 */
$identity = $this->request->getAttribute('identity');
$identityName = $identity?->get('name') ?? __('Admin User');
$identityRole = $identity?->get('role') ?? 'admin';
$identityAvatar = $identity?->get('avatar');
$identityInitial = strtoupper(substr((string)$identityName, 0, 1)) ?: 'A';
$currentUrl = $this->request->getUri()->getPath();

$navLinks = [
    ['label' => __('Dashboard'), 'url' => '/admin', 'icon' => '&#127968;'],
    ['label' => __('Posts'), 'url' => '/posts', 'icon' => '&#128196;'],
    ['label' => __('Classes'), 'url' => '/admin/classes', 'icon' => '&#127979;'],
    ['label' => __('Subjects'), 'url' => '/admin/subjects', 'icon' => '&#128218;'],
    ['label' => __('Courses'), 'url' => '/admin/courses', 'icon' => '&#128214;'],
    ['label' => __('Attendance'), 'url' => '/admin/attendance', 'icon' => '&#128197;'],
    ['label' => __('Notifications'), 'url' => '/admin/notifications', 'icon' => '&#128276;'],
    ['label' => __('Teachers'), 'url' => '/admin/users/teachers', 'icon' => '&#128104;&#8205;&#127979;'],
    ['label' => __('Students'), 'url' => '/admin/users/students', 'icon' => '&#127891;'],
    ['label' => __('All Users'), 'url' => '/admin/users', 'icon' => '&#128101;'],
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
    <div class="admin-layout">
        <!-- Sidebar -->
        <aside class="admin-sidebar">
            <div class="admin-sidebar__header">
                <a class="admin-sidebar__brand" href="<?= $this->Url->build('/') ?>">
                    <span class="admin-sidebar__logo">&#127979;</span>
                    <span>School Media</span>
                </a>
            </div>

            <div class="admin-sidebar__profile">
                <?php if ($identityAvatar): ?>
                    <img src="<?= $this->Url->image($identityAvatar) ?>" alt="<?= h($identityName) ?>" class="admin-sidebar__avatar">
                <?php else: ?>
                    <span class="admin-sidebar__avatar"><?= $identityInitial ?></span>
                <?php endif; ?>
                <div class="admin-sidebar__user">
                    <strong><?= h($identityName) ?></strong>
                    <span><?= h(ucfirst($identityRole)) ?></span>
                </div>
            </div>

            <nav class="admin-sidebar__nav">
                <?php foreach ($navLinks as $link): ?>
                    <?php $isActive = $currentUrl === $link['url'] || ($link['url'] !== '/admin' && str_starts_with($currentUrl, $link['url'])); ?>
                    <a class="admin-sidebar__link<?= $isActive ? ' is-active' : '' ?>" href="<?= $this->Url->build($link['url']) ?>">
                        <span class="admin-sidebar__icon"><?= $link['icon'] ?></span>
                        <?= h($link['label']) ?>
                    </a>
                <?php endforeach; ?>
            </nav>

            <div class="admin-sidebar__footer">
                <a class="admin-sidebar__link admin-sidebar__link--logout" href="<?= $this->Url->build('/logout') ?>">
                    <span class="admin-sidebar__icon">&#128682;</span>
                    <?= __('Logout') ?>
                </a>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="admin-main">
            <!-- Top Bar -->
            <header class="admin-topbar">
                <button class="admin-topbar__toggle" id="sidebarToggle" type="button">
                    <span></span><span></span><span></span>
                </button>

                <div class="admin-topbar__spacer"></div>

                <div class="admin-topbar__actions">
                    <a href="<?= $this->Url->build('/') ?>" class="admin-topbar__btn" target="_blank" title="<?= __('View Site') ?>">
                        <span>&#127760;</span>
                        <span class="admin-topbar__btn-text"><?= __('View Site') ?></span>
                    </a>
                    <?= $this->element('notification_bell', ['unreadCount' => $notificationCount]) ?>
                </div>
            </header>

            <!-- Content Area -->
            <main class="admin-content">
                <div class="toast-stack" aria-live="polite" aria-atomic="true">
                    <?= $this->Flash->render() ?>
                </div>

                <?= $this->fetch('content') ?>
            </main>
        </div>
    </div>

    <script>
    document.getElementById('sidebarToggle')?.addEventListener('click', function() {
        document.querySelector('.admin-layout').classList.toggle('sidebar-open');
    });
    </script>
    <?= $this->fetch('script') ?>
</body>
</html>
