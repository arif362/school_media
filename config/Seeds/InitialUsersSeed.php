<?php
declare(strict_types=1);

use Authentication\PasswordHasher\DefaultPasswordHasher;
use Migrations\AbstractSeed;

class InitialUsersSeed extends AbstractSeed
{
    public function run(): void
    {
        $hasher = new DefaultPasswordHasher();
        $data = [
            [
                'name' => 'Admin User',
                'email' => 'admin@schoolmedia.edu',
                'password' => $hasher->hash('AdminPass123'),
                'role' => 'admin',
                'active' => true,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Teacher User',
                'email' => 'teacher@schoolmedia.edu',
                'password' => $hasher->hash('TeacherPass123'),
                'role' => 'teacher',
                'active' => true,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Student User',
                'email' => 'student@schoolmedia.edu',
                'password' => $hasher->hash('StudentPass123'),
                'role' => 'student',
                'active' => true,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
            ],
        ];

        $table = $this->table('users');
        $table->insert($data)->save();
    }
}

