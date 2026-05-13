<?php
declare(strict_types=1);

namespace App\Controller\Teacher;

use App\Controller\AppController as BaseController;
use Cake\Event\EventInterface;

class TeacherAppController extends BaseController
{
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('Authentication.Authentication');
        $this->loadComponent('Flash');
    }

    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);
        $result = $this->Authentication->getResult();
        if (!$result || !$result->isValid()) {
            return $this->redirect(['/login']);
        }

        $user = $this->request->getAttribute('identity');
        if (!$user || !in_array($user->role, ['teacher', 'admin'])) {
            $this->Flash->error(__('You must be a teacher to access that area.'));

            return $this->redirect('/');
        }
        $this->set('currentUser', $user);
        $this->viewBuilder()->setLayout('teacher');
    }
}
