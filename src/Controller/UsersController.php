<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Event\EventInterface;

class UsersController extends AppController
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
        $this->Authentication->allowUnauthenticated(['login', 'register']);
    }

    public function login()
    {
        $this->request->allowMethod(['get', 'post']);
        $result = $this->Authentication->getResult();
        if ($result && $result->isValid()) {
            $redirect = $this->Authentication->getLoginRedirect() ?? '/admin/dashboard';

            return $this->redirect($redirect);
        }

        if ($this->request->is('post') && $result && !$result->isValid()) {
            $this->Flash->error(__('Invalid email or password'));
        }
    }

    public function logout()
    {
        $result = $this->Authentication->logout();

        return $this->redirect($result ?? '/login');
    }

    public function register()
    {
        $user = $this->Users->newEmptyEntity();
        if ($this->request->is('post')) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->Flash->success(__('Account created, you can now sign in.'));

                return $this->redirect(['action' => 'login']);
            }
            $this->Flash->error(__('Unable to register user. Please, try again.'));
        }

        $this->set(compact('user'));
    }
}

