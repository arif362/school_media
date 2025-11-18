<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Authentication\PasswordHasher\DefaultPasswordHasher;
use Cake\TestSuite\Fixture\TestFixture;

class UsersFixture extends TestFixture
{
    public function init(): void
    {
        $hasher = new DefaultPasswordHasher();
        $this->records = [
            [
                'id' => 1,
                'name' => 'Admin User',
                'email' => 'admin@schoolmedia.edu',
                'password' => $hasher->hash('password123'),
                'role' => 'admin',
                'active' => true,
                'created' => '2025-11-17 00:00:00',
                'modified' => '2025-11-17 00:00:00',
            ],
        ];
        parent::init();
    }
}

