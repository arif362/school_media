<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller;

use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;

/**
 * @uses \App\Controller\PostsController
 */
class PostsControllerTest extends TestCase
{
    use IntegrationTestTrait;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected array $fixtures = [
        'app.Posts',
    ];

    public function testIndex(): void
    {
        $this->get('/posts');

        $this->assertResponseOk();
        $this->assertResponseContains('Welcome to CakePHP');
    }

    public function testView(): void
    {
        $this->get('/posts/view/1');
        $this->assertResponseOk();
        $this->assertResponseContains('Welcome to CakePHP');

        $this->get('/school-media/welcome-to-cakephp');
        $this->assertResponseOk();
    }

    public function testAdd(): void
    {
        $this->enableCsrfToken();
        $this->enableSecurityToken();

        $data = [
            'title' => 'Second Post',
            'slug' => 'second-post',
            'body' => 'Another entry for the blog.',
            'published' => true,
        ];

        $this->post('/posts/add', $data);

        $this->assertResponseSuccess();
        $this->assertRedirectContains('/posts');
    }

    public function testEdit(): void
    {
        $this->enableCsrfToken();
        $this->enableSecurityToken();

        $data = [
            'title' => 'Updated title',
            'slug' => 'welcome-to-cakephp',
            'body' => 'Updated body text.',
            'published' => true,
        ];

        $this->put('/posts/edit/1', $data);

        $this->assertResponseSuccess();
        $this->assertRedirectContains('/posts/view/1');
    }

    public function testDelete(): void
    {
        $this->enableCsrfToken();
        $this->enableSecurityToken();

        $this->delete('/posts/delete/1');
        $this->assertResponseSuccess();
        $this->assertRedirectContains('/posts');
    }
}

