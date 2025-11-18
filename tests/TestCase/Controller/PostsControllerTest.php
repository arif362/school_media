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

    /**
     * @testdox GET /posts lists published posts
     */
    public function testIndex(): void
    {
        $this->get('/posts');

        $this->assertResponseOk();
        $this->assertResponseContains('Welcome to CakePHP');
    }

    /**
     * @testdox GET /posts/view/:id renders the requested post and slug route
     */
    public function testView(): void
    {
        $this->get('/posts/view/1');
        $this->assertResponseOk();
        $this->assertResponseContains('Welcome to CakePHP');

        $this->get('/school-media/welcome-to-cakephp');
        $this->assertResponseOk();
    }

    /**
     * @testdox POST /posts/add persists a new post and redirects to index
     */
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

    /**
     * @testdox PUT /posts/edit/:id updates an existing post
     */
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
        $this->assertRedirectContains('/school-media/1');
    }

    /**
     * @testdox DELETE /posts/delete/:id removes the post and redirects
     */
    public function testDelete(): void
    {
        $this->enableCsrfToken();
        $this->enableSecurityToken();

        $this->delete('/posts/delete/1');
        $this->assertResponseSuccess();
        $this->assertRedirectContains('/posts');
    }
}

