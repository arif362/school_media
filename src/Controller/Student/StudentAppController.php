<?php
declare(strict_types=1);

namespace App\Controller\Student;

use App\Controller\AppController as BaseController;
use Cake\Event\EventInterface;

class StudentAppController extends BaseController
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
        if (!$user || $user->role !== 'student') {
            $this->Flash->error(__('You must be a student to access that area.'));

            return $this->redirect('/');
        }
        $this->set('currentUser', $user);
        $this->viewBuilder()->setLayout('student');
    }
}
