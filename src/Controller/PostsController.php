<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Event\EventInterface;

/**
 * Posts Controller
 */
class PostsController extends AppController
{
    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);

        $action = $this->request->getParam('action');
        $identity = $this->request->getAttribute('identity');
        $canManage = $identity && in_array($identity->get('role'), ['admin', 'teacher'], true);

        if (in_array($action, ['add', 'edit'], true) || ($action === 'index' && $canManage)) {
            $this->viewBuilder()->setLayout('dashboard');
        }
    }

    /**
     * Index method
     *
     * @return void
     */
    public function index(): void
    {
        $identity = $this->request->getAttribute('identity');
        $canManage = $identity && in_array($identity->get('role'), ['admin', 'teacher'], true);

        $query = $this->Posts
            ->find()
            ->orderByDesc('Posts.created');

        // Filter by search term
        $search = $this->request->getQuery('search');
        if ($search) {
            $query->where([
                'OR' => [
                    'Posts.title LIKE' => '%' . $search . '%',
                    'Posts.body LIKE' => '%' . $search . '%',
                ],
            ]);
        }

        // Filter by status (for admin/teacher)
        $status = $this->request->getQuery('status');
        if ($canManage && $status !== null && $status !== '') {
            $query->where(['Posts.published' => $status === 'published']);
        } elseif (!$canManage) {
            // Non-admin users only see published posts
            $query->where(['Posts.published' => true]);
        }

        $this->paginate = [
            'limit' => 10,
            'order' => ['Posts.created' => 'DESC'],
        ];

        $posts = $this->paginate($query);

        // Get counts for filters
        $totalCount = $this->Posts->find()->count();
        $publishedCount = $this->Posts->find()->where(['published' => true])->count();
        $draftCount = $this->Posts->find()->where(['published' => false])->count();

        $this->set(compact('posts', 'search', 'status', 'canManage', 'totalCount', 'publishedCount', 'draftCount'));
    }

    /**
     * View method
     *
     * @param string|null $id Post id.
     * @return void
     */
    public function view(?string $id = null): void
    {
        $this->request->allowMethod(['get']);
        $query = $this->Posts->find();
        if ($id === null) {
            throw new \Cake\Http\Exception\NotFoundException('Post not found');
        }

        if (ctype_digit($id)) {
            $query->where(['Posts.id' => (int)$id]);
        } else {
            $query->where(['Posts.slug' => $id]);
        }

        $post = $query->firstOrFail();
        $this->set(compact('post'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $post = $this->Posts->newEmptyEntity();
        if ($this->request->is('post')) {
            $post = $this->Posts->patchEntity($post, $this->request->getData());
            if ($this->Posts->save($post)) {
                $this->Flash->success(__('The post has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The post could not be saved. Please, try again.'));
        }
        $this->set(compact('post'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Post id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     */
    public function edit(?string $id = null)
    {
        $post = $this->Posts->get($id);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $post = $this->Posts->patchEntity($post, $this->request->getData());
            if ($this->Posts->save($post)) {
                $this->Flash->success(__('The post has been updated.'));

                return $this->redirect(['action' => 'view', $post->id]);
            }
            $this->Flash->error(__('The post could not be updated. Please, try again.'));
        }
        $this->set(compact('post'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Post id.
     * @return \Cake\Http\Response|null Redirects to index.
     */
    public function delete(?string $id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $post = $this->Posts->get($id);
        if ($this->Posts->delete($post)) {
            $this->Flash->success(__('The post has been deleted.'));
        } else {
            $this->Flash->error(__('The post could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}

