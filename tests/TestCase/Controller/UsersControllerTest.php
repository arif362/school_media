<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller;

use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;

class UsersControllerTest extends TestCase
{
    use IntegrationTestTrait;

    protected array $fixtures = [
        'app.Users',
    ];

    public function testRegisterSuccess(): void
    {
        $this->enableCsrfToken();
        $this->enableSecurityToken();

        $data = [
            'name' => 'Teacher One',
            'email' => 'teacher1@schoolmedia.edu',
            'password' => 'password123',
            'role' => 'teacher',
        ];

        $this->post('/register', $data);

        $this->assertResponseSuccess();
        $this->assertRedirectContains('/login');
        $users = $this->getTableLocator()->get('Users');
        $this->assertTrue($users->exists(['email' => $data['email']]));
    }

    public function testLoginSuccess(): void
    {
        $this->enableCsrfToken();
        $this->enableSecurityToken();

        $this->post('/login', [
            'email' => 'admin@schoolmedia.edu',
            'password' => 'password123',
        ]);

        $this->assertRedirectContains('/admin/dashboard');
    }
}

