<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Http\Response;

class NotificationsController extends AppController
{
    public function index()
    {
        $identity = $this->request->getAttribute('identity');
        $userId = $identity->id;
        $userRole = $identity->role;

        $query = $this->Notifications->find()
            ->where([
                'Notifications.is_active' => true,
                'OR' => [
                    ['Notifications.target_user_id IS' => null, 'Notifications.target_role IS' => null],
                    ['Notifications.target_user_id IS' => null, 'Notifications.target_role' => $userRole],
                    ['Notifications.target_user_id' => $userId],
                ],
            ])
            ->orderByDesc('Notifications.created');

        $filter = $this->request->getQuery('filter');
        $userNotificationsTable = $this->fetchTable('UserNotifications');

        $this->paginate = ['limit' => 10];
        $notifications = $this->paginate($query);

        // Get read status for each notification
        $readStatus = [];
        foreach ($notifications as $notification) {
            $readStatus[$notification->id] = $userNotificationsTable->isRead($userId, $notification->id);
        }

        $unreadCount = $userNotificationsTable->getUnreadCount($userId, $userRole);

        $this->set(compact('notifications', 'readStatus', 'unreadCount', 'filter'));
    }

    public function view(?string $id = null)
    {
        $identity = $this->request->getAttribute('identity');
        $userId = $identity->id;
        $userRole = $identity->role;

        $notification = $this->Notifications->find()
            ->where([
                'Notifications.id' => $id,
                'Notifications.is_active' => true,
                'OR' => [
                    ['Notifications.target_user_id IS' => null, 'Notifications.target_role IS' => null],
                    ['Notifications.target_user_id IS' => null, 'Notifications.target_role' => $userRole],
                    ['Notifications.target_user_id' => $userId],
                ],
            ])
            ->firstOrFail();

        // Mark as read
        $userNotificationsTable = $this->fetchTable('UserNotifications');
        $userNotificationsTable->markAsRead($userId, (int)$id);

        $this->set(compact('notification'));
    }

    public function markRead(?string $id = null): ?Response
    {
        $this->request->allowMethod(['post']);
        $identity = $this->request->getAttribute('identity');
        $userId = $identity->id;

        $userNotificationsTable = $this->fetchTable('UserNotifications');
        $userNotificationsTable->markAsRead($userId, (int)$id);

        if ($this->request->is('ajax')) {
            $this->autoRender = false;
            return $this->response->withType('application/json')
                ->withStringBody(json_encode(['success' => true]));
        }

        return $this->redirect($this->referer(['action' => 'index']));
    }

    public function markAllRead(): ?Response
    {
        $this->request->allowMethod(['post']);
        $identity = $this->request->getAttribute('identity');
        $userId = $identity->id;
        $userRole = $identity->role;

        $userNotificationsTable = $this->fetchTable('UserNotifications');
        $count = $userNotificationsTable->markAllAsRead($userId, $userRole);

        if ($this->request->is('ajax')) {
            $this->autoRender = false;
            return $this->response->withType('application/json')
                ->withStringBody(json_encode(['success' => true, 'count' => $count]));
        }

        $this->Flash->success(__('All notifications marked as read.'));
        return $this->redirect(['action' => 'index']);
    }

    public function getUnreadCount(): Response
    {
        $this->request->allowMethod(['get']);
        $this->autoRender = false;

        $identity = $this->request->getAttribute('identity');
        if (!$identity) {
            return $this->response->withType('application/json')
                ->withStringBody(json_encode(['count' => 0]));
        }

        $userNotificationsTable = $this->fetchTable('UserNotifications');
        $count = $userNotificationsTable->getUnreadCount($identity->id, $identity->role);

        return $this->response->withType('application/json')
            ->withStringBody(json_encode(['count' => $count]));
    }

    public function dropdown(): Response
    {
        $this->autoRender = false;

        $identity = $this->request->getAttribute('identity');
        if (!$identity) {
            return $this->response->withType('application/json')
                ->withStringBody(json_encode(['notifications' => [], 'count' => 0]));
        }

        $userId = $identity->id;
        $userRole = $identity->role;

        $notifications = $this->Notifications->find()
            ->where([
                'Notifications.is_active' => true,
                'OR' => [
                    ['Notifications.target_user_id IS' => null, 'Notifications.target_role IS' => null],
                    ['Notifications.target_user_id IS' => null, 'Notifications.target_role' => $userRole],
                    ['Notifications.target_user_id' => $userId],
                ],
            ])
            ->orderByDesc('Notifications.created')
            ->limit(5)
            ->all();

        $userNotificationsTable = $this->fetchTable('UserNotifications');
        $unreadCount = $userNotificationsTable->getUnreadCount($userId, $userRole);

        $data = [];
        foreach ($notifications as $notification) {
            $isRead = $userNotificationsTable->isRead($userId, $notification->id);
            $data[] = [
                'id' => $notification->id,
                'title' => $notification->title,
                'message' => $this->Text->truncate($notification->message, 80),
                'type' => $notification->type,
                'link' => $notification->link,
                'is_read' => $isRead,
                'created' => $notification->created->format('M j, g:i A'),
            ];
        }

        return $this->response->withType('application/json')
            ->withStringBody(json_encode(['notifications' => $data, 'count' => $unreadCount]));
    }
}
