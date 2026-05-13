<?php
declare(strict_types=1);

namespace App\Controller\Student;

class DashboardController extends StudentAppController
{
    public function index()
    {
        $user = $this->request->getAttribute('identity');

        $postsTable = $this->fetchTable('Posts');
        $recentPosts = $postsTable->find('published')
            ->limit(5)
            ->all();

        $this->set(compact('user', 'recentPosts'));
    }
}
