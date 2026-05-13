<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Model\Entity\Notification;

class NotificationsController extends AdminAppController
{
    public function index()
    {
        $query = $this->Notifications->find()
            ->contain(['Users'])
            ->orderByDesc('Notifications.created');

        $search = $this->request->getQuery('search');
        if ($search) {
            $query->where([
                'OR' => [
                    'Notifications.title LIKE' => '%' . $search . '%',
                    'Notifications.message LIKE' => '%' . $search . '%',
                ],
            ]);
        }

        $type = $this->request->getQuery('type');
        if ($type) {
            $query->where(['Notifications.type' => $type]);
        }

        $status = $this->request->getQuery('status');
        if ($status !== null && $status !== '') {
            $query->where(['Notifications.is_active' => $status === 'active']);
        }

        $this->paginate = ['limit' => 15];
        $notifications = $this->paginate($query);

        $types = Notification::getTypes();
        $this->set(compact('notifications', 'search', 'type', 'status', 'types'));
    }

    public function view(?string $id = null)
    {
        $notification = $this->Notifications->get($id, contain: ['Users', 'TargetUsers']);

        $userNotificationsTable = $this->fetchTable('UserNotifications');
        $readCount = $userNotificationsTable->find()
            ->where([
                'notification_id' => $id,
                'is_read' => true,
            ])
            ->count();

        $this->set(compact('notification', 'readCount'));
    }

    public function add()
    {
        $notification = $this->Notifications->newEmptyEntity();

        if ($this->request->is('post')) {
            $data = $this->request->getData();
            $data['created_by'] = $this->request->getAttribute('identity')->id;

            if (empty($data['target_role'])) {
                $data['target_role'] = null;
            }
            if (empty($data['target_user_id'])) {
                $data['target_user_id'] = null;
            }

            $notification = $this->Notifications->patchEntity($notification, $data);

            if ($this->Notifications->save($notification)) {
                $this->Flash->success(__('Notification has been created and sent.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Unable to create notification. Please try again.'));
        }

        $types = Notification::getTypes();
        $targetRoles = Notification::getTargetRoles();
        $users = $this->fetchTable('Users')->find('list', keyField: 'id', valueField: 'name')->toArray();

        $this->set(compact('notification', 'types', 'targetRoles', 'users'));
    }

    public function edit(?string $id = null)
    {
        $notification = $this->Notifications->get($id);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();

            if (empty($data['target_role'])) {
                $data['target_role'] = null;
            }
            if (empty($data['target_user_id'])) {
                $data['target_user_id'] = null;
            }

            $notification = $this->Notifications->patchEntity($notification, $data);

            if ($this->Notifications->save($notification)) {
                $this->Flash->success(__('Notification has been updated.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Unable to update notification. Please try again.'));
        }

        $types = Notification::getTypes();
        $targetRoles = Notification::getTargetRoles();
        $users = $this->fetchTable('Users')->find('list', keyField: 'id', valueField: 'name')->toArray();

        $this->set(compact('notification', 'types', 'targetRoles', 'users'));
    }

    public function delete(?string $id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $notification = $this->Notifications->get($id);

        if ($this->Notifications->delete($notification)) {
            $this->Flash->success(__('Notification has been deleted.'));
        } else {
            $this->Flash->error(__('Unable to delete notification. Please try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function toggleStatus(?string $id = null)
    {
        $this->request->allowMethod(['post']);
        $notification = $this->Notifications->get($id);
        $notification->is_active = !$notification->is_active;

        if ($this->Notifications->save($notification)) {
            $status = $notification->is_active ? __('activated') : __('deactivated');
            $this->Flash->success(__('Notification has been {0}.', $status));
        } else {
            $this->Flash->error(__('Unable to update notification status.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
