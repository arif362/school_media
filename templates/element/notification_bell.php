<?php
/**
 * Notification Bell Element
 * @var \App\View\AppView $this
 * @var int $unreadCount
 */
$unreadCount = $unreadCount ?? 0;
?>

<div class="notification-bell" id="notificationBell">
    <button class="notification-bell__trigger" type="button" aria-label="<?= __('Notifications') ?>" onclick="toggleNotificationDropdown()">
        <span class="notification-bell__icon">&#128276;</span>
        <?php if ($unreadCount > 0): ?>
            <span class="notification-bell__badge" id="notificationBadge"><?= $unreadCount > 99 ? '99+' : $unreadCount ?></span>
        <?php endif; ?>
    </button>

    <div class="notification-dropdown" id="notificationDropdown">
        <header class="notification-dropdown__header">
            <h4><?= __('Notifications') ?></h4>
            <?php if ($unreadCount > 0): ?>
                <button type="button" class="notification-dropdown__mark-all" onclick="markAllNotificationsRead()">
                    <?= __('Mark all read') ?>
                </button>
            <?php endif; ?>
        </header>
        <div class="notification-dropdown__body" id="notificationList">
            <div class="notification-dropdown__loading">
                <?= __('Loading...') ?>
            </div>
        </div>
        <footer class="notification-dropdown__footer">
            <?= $this->Html->link(__('View all notifications'), ['controller' => 'Notifications', 'action' => 'index', 'prefix' => false], ['class' => 'notification-dropdown__view-all']) ?>
        </footer>
    </div>
</div>

<script>
(function() {
    let dropdownOpen = false;

    window.toggleNotificationDropdown = function() {
        const dropdown = document.getElementById('notificationDropdown');
        dropdownOpen = !dropdownOpen;
        dropdown.classList.toggle('is-open', dropdownOpen);

        if (dropdownOpen) {
            loadNotifications();
        }
    };

    window.loadNotifications = function() {
        fetch('<?= $this->Url->build(['controller' => 'Notifications', 'action' => 'dropdown', 'prefix' => false]) ?>')
            .then(response => response.json())
            .then(data => {
                renderNotifications(data.notifications, data.count);
            })
            .catch(() => {
                document.getElementById('notificationList').innerHTML =
                    '<div class="notification-dropdown__empty"><?= __('Failed to load notifications') ?></div>';
            });
    };

    function renderNotifications(notifications, count) {
        const list = document.getElementById('notificationList');
        const badge = document.getElementById('notificationBadge');

        if (badge) {
            if (count > 0) {
                badge.textContent = count > 99 ? '99+' : count;
                badge.style.display = '';
            } else {
                badge.style.display = 'none';
            }
        }

        if (notifications.length === 0) {
            list.innerHTML = '<div class="notification-dropdown__empty"><?= __('No notifications') ?></div>';
            return;
        }

        let html = '';
        notifications.forEach(n => {
            const unreadClass = n.is_read ? '' : 'notification-dropdown__item--unread';
            html += `
                <a href="<?= $this->Url->build(['controller' => 'Notifications', 'action' => 'view', 'prefix' => false]) ?>/${n.id}"
                   class="notification-dropdown__item ${unreadClass}">
                    <div class="notification-dropdown__item-indicator type-indicator--${n.type}"></div>
                    <div class="notification-dropdown__item-content">
                        <strong>${escapeHtml(n.title)}</strong>
                        <p>${escapeHtml(n.message)}</p>
                        <time>${n.created}</time>
                    </div>
                </a>
            `;
        });
        list.innerHTML = html;
    }

    window.markAllNotificationsRead = function() {
        fetch('<?= $this->Url->build(['controller' => 'Notifications', 'action' => 'markAllRead', 'prefix' => false]) ?>', {
            method: 'POST',
            headers: {
                'X-CSRF-Token': '<?= $this->request->getAttribute('csrfToken') ?>',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(() => {
            loadNotifications();
            const badge = document.getElementById('notificationBadge');
            if (badge) badge.style.display = 'none';
        });
    };

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        const bell = document.getElementById('notificationBell');
        if (dropdownOpen && !bell.contains(e.target)) {
            dropdownOpen = false;
            document.getElementById('notificationDropdown').classList.remove('is-open');
        }
    });

    // Poll for new notifications every 60 seconds
    setInterval(function() {
        fetch('<?= $this->Url->build(['controller' => 'Notifications', 'action' => 'getUnreadCount', 'prefix' => false]) ?>')
            .then(response => response.json())
            .then(data => {
                const badge = document.getElementById('notificationBadge');
                const bell = document.querySelector('.notification-bell__trigger');
                if (data.count > 0) {
                    if (badge) {
                        badge.textContent = data.count > 99 ? '99+' : data.count;
                        badge.style.display = '';
                    } else {
                        const newBadge = document.createElement('span');
                        newBadge.id = 'notificationBadge';
                        newBadge.className = 'notification-bell__badge';
                        newBadge.textContent = data.count > 99 ? '99+' : data.count;
                        bell.appendChild(newBadge);
                    }
                } else if (badge) {
                    badge.style.display = 'none';
                }
            });
    }, 60000);
})();
</script>
