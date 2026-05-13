<?php
/**
 * @var \App\View\AppView $this
 */
$identity = $this->request->getAttribute('identity');
$identityName = $identity?->get('name') ?? __('Student');
$identityRole = $identity?->get('role') ?? 'student';
$identityAvatar = $identity?->get('avatar');
$identityInitial = strtoupper(substr((string)$identityName, 0, 1)) ?: 'S';
$currentUrl = $this->request->getUri()->getPath();

// Grouped navigation for better organization with colorful icons
$navGroups = [
    'main' => [
        'label' => __('Main'),
        'items' => [
            ['label' => __('Dashboard'), 'url' => '/student', 'icon' => 'dashboard', 'color' => 'blue'],
            ['label' => __('Browse Posts'), 'url' => '/posts', 'icon' => 'posts', 'color' => 'orange'],
        ],
    ],
    'academic' => [
        'label' => __('Academic'),
        'items' => [
            ['label' => __('My Courses'), 'url' => '/student/courses', 'icon' => 'courses', 'color' => 'indigo'],
            ['label' => __('My Attendance'), 'url' => '/student/attendance', 'icon' => 'attendance', 'color' => 'green'],
        ],
    ],
    'account' => [
        'label' => __('Account'),
        'items' => [
            ['label' => __('My Profile'), 'url' => '/student/profile', 'icon' => 'profile', 'color' => 'cyan'],
            ['label' => __('Edit Profile'), 'url' => '/student/profile/edit', 'icon' => 'edit', 'color' => 'slate'],
            ['label' => __('Notifications'), 'url' => '/notifications', 'icon' => 'notifications', 'color' => 'amber'],
        ],
    ],
];

// SVG icons for cleaner look
$icons = [
    'dashboard' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>',
    'posts' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><line x1="10" y1="9" x2="8" y2="9"/></svg>',
    'courses' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 2 7 12 12 22 7 12 2"/><polyline points="2 17 12 22 22 17"/><polyline points="2 12 12 17 22 12"/></svg>',
    'attendance' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/><path d="M9 16l2 2 4-4"/></svg>',
    'profile' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>',
    'edit' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>',
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
    <title><?= $this->fetch('title') ? $this->fetch('title') . ' | ' : '' ?>School Media - Student Portal</title>
    <?= $this->Html->meta('icon') ?>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <?= $this->Html->css('school_media') ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
</head>
<body class="sm-body dashboard-body">
    <div class="admin-layout student-layout">
        <!-- Sidebar -->
        <aside class="admin-sidebar student-sidebar">
            <div class="admin-sidebar__header">
                <a class="admin-sidebar__brand" href="<?= $this->Url->build('/student') ?>">
                    <div class="admin-sidebar__logo-wrap student-logo-wrap">
                        <span class="admin-sidebar__logo-icon">S</span>
                    </div>
                    <div class="admin-sidebar__brand-text">
                        <span class="admin-sidebar__brand-name">School Media</span>
                        <span class="admin-sidebar__brand-tag">Student Portal</span>
                    </div>
                </a>
            </div>

            <div class="admin-sidebar__body">
                <nav class="admin-sidebar__nav">
                    <?php foreach ($navGroups as $groupKey => $group): ?>
                        <div class="admin-nav-group">
                            <span class="admin-nav-group__label"><?= $group['label'] ?></span>
                            <?php foreach ($group['items'] as $link): ?>
                                <?php
                                $isActive = $currentUrl === $link['url'] ||
                                    ($link['url'] === '/student' && $currentUrl === '/student/') ||
                                    ($link['url'] !== '/student' && $link['url'] !== '/posts' && str_starts_with($currentUrl, $link['url']));
                                ?>
                                <a class="admin-sidebar__link<?= $isActive ? ' is-active' : '' ?>" href="<?= $this->Url->build($link['url']) ?>">
                                    <span class="admin-sidebar__icon admin-sidebar__icon--<?= $link['color'] ?? 'default' ?>"><?= $icons[$link['icon']] ?? '' ?></span>
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
                        <span class="admin-sidebar__avatar student-avatar-icon"><?= $identityInitial ?></span>
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

                <div class="admin-topbar__search" id="globalSearch">
                    <span class="admin-topbar__search-icon"><?= $icons['search'] ?></span>
                    <input type="text" class="admin-topbar__search-input" id="globalSearchInput" placeholder="<?= __('Search courses, posts...') ?>" autocomplete="off">
                    <kbd class="admin-topbar__search-kbd">/</kbd>
                    <div class="global-search-dropdown" id="globalSearchDropdown">
                        <div class="global-search-dropdown__content" id="globalSearchResults">
                            <!-- Results will be populated by JavaScript -->
                        </div>
                    </div>
                </div>

                <div class="admin-topbar__actions">
                    <a href="<?= $this->Url->build('/') ?>" class="admin-topbar__btn" target="_blank" title="<?= __('View Site') ?>">
                        <span class="admin-topbar__btn-icon"><?= $icons['external'] ?></span>
                        <span class="admin-topbar__btn-text"><?= __('View Site') ?></span>
                    </a>
                    <?= $this->element('notification_bell', ['unreadCount' => $notificationCount]) ?>
                </div>
            </header>

            <main class="admin-content">
                <div class="toast-stack" aria-live="polite" aria-atomic="true">
                    <?= $this->Flash->render() ?>
                </div>

                <?= $this->fetch('content') ?>
            </main>
        </div>
    </div>

    <script>
    // Sidebar toggle
    document.getElementById('sidebarToggle')?.addEventListener('click', function() {
        document.querySelector('.admin-layout').classList.toggle('sidebar-open');
    });

    // Global Search Functionality for Students
    (function() {
        const searchContainer = document.getElementById('globalSearch');
        const searchInput = document.getElementById('globalSearchInput');
        const searchDropdown = document.getElementById('globalSearchDropdown');
        const searchResults = document.getElementById('globalSearchResults');

        if (!searchInput || !searchDropdown || !searchResults) return;

        let debounceTimer = null;
        let currentQuery = '';
        let selectedIndex = -1;

        // Icon templates for different result types
        const icons = {
            post: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>',
            course: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 2 7 12 12 22 7 12 2"/><polyline points="2 17 12 22 22 17"/><polyline points="2 12 12 17 22 12"/></svg>',
        };

        function performSearch(query) {
            if (query.length < 2) {
                hideDropdown();
                return;
            }

            currentQuery = query;

            fetch(`<?= $this->Url->build(['controller' => 'Search', 'action' => 'studentSearch', 'prefix' => 'Student']) ?>?q=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    if (query !== currentQuery) return;
                    renderResults(data);
                })
                .catch(error => {
                    console.error('Search error:', error);
                    searchResults.innerHTML = '<div class="global-search-dropdown__empty"><?= __('Search failed. Please try again.') ?></div>';
                    showDropdown();
                });
        }

        function renderResults(data) {
            selectedIndex = -1;

            if (data.results.length === 0) {
                searchResults.innerHTML = `
                    <div class="global-search-dropdown__empty">
                        <span class="global-search-dropdown__empty-icon"><?= $icons['search'] ?></span>
                        <p><?= __('No results found for') ?> "<strong>${escapeHtml(data.query)}</strong>"</p>
                    </div>
                `;
                showDropdown();
                return;
            }

            let html = '';

            // Group results by type
            const grouped = {};
            data.results.forEach(result => {
                if (!grouped[result.type]) grouped[result.type] = [];
                grouped[result.type].push(result);
            });

            const typeLabels = {
                post: '<?= __('Posts') ?>',
                course: '<?= __('Courses') ?>',
            };

            let itemIndex = 0;
            for (const [type, results] of Object.entries(grouped)) {
                html += `<div class="global-search-dropdown__group">
                    <span class="global-search-dropdown__group-label">${typeLabels[type] || type}</span>`;

                results.forEach(result => {
                    const icon = icons[result.icon] || icons.post;
                    html += `
                        <a href="${result.url}" class="global-search-dropdown__item" data-index="${itemIndex}">
                            <span class="global-search-dropdown__item-icon">${icon}</span>
                            <div class="global-search-dropdown__item-content">
                                <span class="global-search-dropdown__item-title">${highlightMatch(result.title, data.query)}</span>
                                <span class="global-search-dropdown__item-subtitle">${escapeHtml(result.subtitle)}</span>
                            </div>
                        </a>
                    `;
                    itemIndex++;
                });

                html += '</div>';
            }

            if (data.total > data.results.length) {
                html += `<div class="global-search-dropdown__footer">
                    <?= __('Showing') ?> ${data.results.length} <?= __('of') ?> ${data.total} <?= __('results') ?>
                </div>`;
            }

            searchResults.innerHTML = html;
            showDropdown();
        }

        function highlightMatch(text, query) {
            const escaped = escapeHtml(text);
            const regex = new RegExp(`(${escapeRegex(query)})`, 'gi');
            return escaped.replace(regex, '<mark>$1</mark>');
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        function escapeRegex(string) {
            return string.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
        }

        function showDropdown() {
            searchDropdown.classList.add('is-open');
            searchContainer.classList.add('is-searching');
        }

        function hideDropdown() {
            searchDropdown.classList.remove('is-open');
            searchContainer.classList.remove('is-searching');
            selectedIndex = -1;
        }

        function navigateResults(direction) {
            const items = searchResults.querySelectorAll('.global-search-dropdown__item');
            if (items.length === 0) return;

            items[selectedIndex]?.classList.remove('is-selected');

            if (direction === 'down') {
                selectedIndex = selectedIndex < items.length - 1 ? selectedIndex + 1 : 0;
            } else {
                selectedIndex = selectedIndex > 0 ? selectedIndex - 1 : items.length - 1;
            }

            items[selectedIndex]?.classList.add('is-selected');
            items[selectedIndex]?.scrollIntoView({ block: 'nearest' });
        }

        function selectCurrentResult() {
            const items = searchResults.querySelectorAll('.global-search-dropdown__item');
            if (items[selectedIndex]) {
                window.location.href = items[selectedIndex].href;
            }
        }

        // Event listeners
        searchInput.addEventListener('input', function() {
            const query = this.value.trim();

            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => performSearch(query), 250);
        });

        searchInput.addEventListener('keydown', function(e) {
            if (e.key === 'ArrowDown') {
                e.preventDefault();
                navigateResults('down');
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                navigateResults('up');
            } else if (e.key === 'Enter') {
                e.preventDefault();
                if (selectedIndex >= 0) {
                    selectCurrentResult();
                }
            } else if (e.key === 'Escape') {
                hideDropdown();
                searchInput.blur();
            }
        });

        searchInput.addEventListener('focus', function() {
            if (this.value.trim().length >= 2) {
                showDropdown();
            }
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!searchContainer.contains(e.target)) {
                hideDropdown();
            }
        });

        // Keyboard shortcut: "/" to focus search
        document.addEventListener('keydown', function(e) {
            if (e.key === '/' && document.activeElement.tagName !== 'INPUT' && document.activeElement.tagName !== 'TEXTAREA') {
                e.preventDefault();
                searchInput.focus();
            }
        });
    })();
    </script>
    <?= $this->fetch('script') ?>
</body>
</html>
