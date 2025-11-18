<?php
declare(strict_types=1);

namespace App\Controller\Admin;

class DashboardController extends AdminAppController
{
    public function index(): void
    {
        $user = $this->request->getAttribute('identity');
        $stats = [
            ['label' => __('Published posts'), 'value' => $this->fetchCount('Posts', ['published' => true])],
            ['label' => __('Total users'), 'value' => $this->fetchCount('Users')],
            ['label' => __('Teachers'), 'value' => $this->fetchCount('Users', ['role' => 'teacher'])],
            ['label' => __('Students'), 'value' => $this->fetchCount('Users', ['role' => 'student'])],
        ];
        $this->set(compact('user', 'stats'));
    }

    private function fetchCount(string $table, array $conditions = []): int
    {
        return $this->getTableLocator()->get($table)->find()->where($conditions)->count();
    }
}

