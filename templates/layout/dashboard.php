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

// Grouped navigation for better organization
$navGroups = [
    'main' => [
        'label' => __('Main'),
        'items' => [
            ['label' => __('Dashboard'), 'url' => '/admin', 'icon' => 'dashboard'],
            ['label' => __('Posts'), 'url' => '/posts', 'icon' => 'posts'],
        ],
    ],
    'academic' => [
        'label' => __('Academic'),
        'items' => [
            ['label' => __('Classes'), 'url' => '/admin/classes', 'icon' => 'classes'],
            ['label' => __('Subjects'), 'url' => '/admin/subjects', 'icon' => 'subjects'],
            ['label' => __('Courses'), 'url' => '/admin/courses', 'icon' => 'courses'],
            ['label' => __('Attendance'), 'url' => '/admin/attendance', 'icon' => 'attendance'],
        ],
    ],
    'people' => [
        'label' => __('People'),
        'items' => [
            ['label' => __('Teachers'), 'url' => '/admin/users/teachers', 'icon' => 'teachers'],
            ['label' => __('Students'), 'url' => '/admin/users/students', 'icon' => 'students'],
            ['label' => __('All Users'), 'url' => '/admin/users', 'icon' => 'users'],
        ],
    ],
    'system' => [
        'label' => __('System'),
        'items' => [
            ['label' => __('Notifications'), 'url' => '/admin/notifications', 'icon' => 'notifications'],
        ],
    ],
];

// SVG icons for cleaner look
$icons = [
    'dashboard' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>',
    'posts' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><line x1="10" y1="9" x2="8" y2="9"/></svg>',
    'classes' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>',
    'subjects' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/><line x1="8" y1="7" x2="16" y2="7"/><line x1="8" y1="11" x2="14" y2="11"/></svg>',
    'courses' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 2 7 12 12 22 7 12 2"/><polyline points="2 17 12 22 22 17"/><polyline points="2 12 12 17 22 12"/></svg>',
    'attendance' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/><path d="M9 16l2 2 4-4"/></svg>',
    'teachers' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>',
    'students' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c0 2 2 3 6 3s6-1 6-3v-5"/></svg>',
    'users' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>',
    'notifications' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>',
    'logout' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>',
    'external' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>',
    'search' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>',
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
                <a class="admin-sidebar__brand" href="<?= $this->Url->build('/admin') ?>">
                    <div class="admin-sidebar__logo-wrap">
                        <span class="admin-sidebar__logo-icon">S</span>
                    </div>
                    <div class="admin-sidebar__brand-text">
                        <span class="admin-sidebar__brand-name">School Media</span>
                        <span class="admin-sidebar__brand-tag">Administration</span>
                    </div>
                </a>
            </div>

            <div class="admin-sidebar__body">
                <nav class="admin-sidebar__nav">
                    <?php foreach ($navGroups as $groupKey => $group): ?>
                        <div class="admin-nav-group">
                            <span class="admin-nav-group__label"><?= $group['label'] ?></span>
                            <?php foreach ($group['items'] as $link): ?>
                                <?php $isActive = $currentUrl === $link['url'] || ($link['url'] !== '/admin' && str_starts_with($currentUrl, $link['url'])); ?>
                                <a class="admin-sidebar__link<?= $isActive ? ' is-active' : '' ?>" href="<?= $this->Url->build($link['url']) ?>">
                                    <span class="admin-sidebar__icon"><?= $icons[$link['icon']] ?? '' ?></span>
                                    <span class="admin-sidebar__label"><?= h($link['label']) ?></span>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endforeach; ?>
                </nav>
            </div>

            <div class="admin-sidebar__footer">
                <div class="admin-sidebar__user-card">
                    <?php if ($identityAvatar): ?>
                        <img src="<?= $this->Url->image($identityAvatar) ?>" alt="<?= h($identityName) ?>" class="admin-sidebar__avatar">
                    <?php else: ?>
                        <span class="admin-sidebar__avatar"><?= $identityInitial ?></span>
                    <?php endif; ?>
                    <div class="admin-sidebar__user-info">
                        <strong><?= h($identityName) ?></strong>
                        <span><?= h(ucfirst($identityRole)) ?></span>
                    </div>
                    <a class="admin-sidebar__logout-btn" href="<?= $this->Url->build('/logout') ?>" title="<?= __('Logout') ?>">
                        <?= $icons['logout'] ?>
                    </a>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="admin-main">
            <!-- Top Bar -->
            <header class="admin-topbar">
                <button class="admin-topbar__toggle" id="sidebarToggle" type="button" aria-label="Toggle sidebar">
                    <span></span><span></span><span></span>
                </button>

                <div class="admin-topbar__search">
                    <span class="admin-topbar__search-icon"><?= $icons['search'] ?></span>
                    <input type="text" class="admin-topbar__search-input" placeholder="<?= __('Search...') ?>">
                </div>

                <div class="admin-topbar__actions">
                    <a href="<?= $this->Url->build('/') ?>" class="admin-topbar__btn" target="_blank" title="<?= __('View Site') ?>">
                        <span class="admin-topbar__btn-icon"><?= $icons['external'] ?></span>
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
