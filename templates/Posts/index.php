<?php
/**
 * @var \App\View\AppView $this
 * @var \Cake\Datasource\ResultSetInterface<\App\Model\Entity\Post> $posts
 * @var string|null $search
 * @var string|null $status
 * @var bool $canManage
 * @var int $totalCount
 * @var int $publishedCount
 * @var int $draftCount
 */

$this->assign('title', __('Posts'));
?>

<section class="admin-section">
    <header class="admin-section__header">
        <div>
            <h1><?= $canManage ? __('Manage Posts') : __('Latest Stories') ?></h1>
            <p class="text-muted">
                <?= $canManage
                    ? __('Create, edit, and manage all content from one place.')
                    : __('Discover news, events, and creative work from our school community.') ?>
            </p>
        </div>
        <?php if ($canManage): ?>
            <div class="admin-section__actions">
                <?= $this->Html->link(
                    __('+ New Post'),
                    ['action' => 'add'],
                    ['class' => 'btn btn--solid']
                ) ?>
            </div>
        <?php endif; ?>
    </header>

        <div class="posts-filters">
            <?= $this->Form->create(null, ['type' => 'get', 'class' => 'posts-search-form']) ?>
                <div class="posts-search-box">
                    <span class="search-icon">&#128269;</span>
                    <?= $this->Form->control('search', [
                        'label' => false,
                        'placeholder' => __('Search posts by title or content...'),
                        'value' => $search ?? '',
                        'class' => 'posts-search-input',
                        'templates' => ['inputContainer' => '{{content}}'],
                    ]) ?>
                    <?php if ($search): ?>
                        <?= $this->Html->link(__('Clear'), ['action' => 'index'], ['class' => 'search-clear']) ?>
                    <?php endif; ?>
                    <button type="submit" class="btn btn--solid btn--small"><?= __('Search') ?></button>
                </div>

                <?php if ($canManage): ?>
                    <div class="posts-status-filters">
                        <span class="filter-label"><?= __('Filter:') ?></span>
                        <?= $this->Html->link(
                            __('All') . ' (' . $totalCount . ')',
                            ['action' => 'index', '?' => array_filter(['search' => $search])],
                            ['class' => 'filter-chip' . ($status === null || $status === '' ? ' is-active' : '')]
                        ) ?>
                        <?= $this->Html->link(
                            __('Published') . ' (' . $publishedCount . ')',
                            ['action' => 'index', '?' => array_filter(['search' => $search, 'status' => 'published'])],
                            ['class' => 'filter-chip filter-chip--success' . ($status === 'published' ? ' is-active' : '')]
                        ) ?>
                        <?= $this->Html->link(
                            __('Drafts') . ' (' . $draftCount . ')',
                            ['action' => 'index', '?' => array_filter(['search' => $search, 'status' => 'draft'])],
                            ['class' => 'filter-chip filter-chip--warning' . ($status === 'draft' ? ' is-active' : '')]
                        ) ?>
                    </div>
                <?php endif; ?>
            <?= $this->Form->end() ?>
        </div>

        <?php if ($search): ?>
            <div class="search-results-info">
                <?= __('Showing results for "{0}"', h($search)) ?>
                <span class="results-count">(<?= $this->Paginator->param('count') ?> <?= __('found') ?>)</span>
            </div>
        <?php endif; ?>

        <?php if ($posts->isEmpty()): ?>
            <div class="posts-empty">
                <div class="posts-empty__icon">&#128196;</div>
                <h3><?= $search ? __('No posts found') : __('No posts yet') ?></h3>
                <p><?= $search
                    ? __('Try adjusting your search terms or filters.')
                    : __('Check back soon for new content!') ?></p>
                <?php if ($search): ?>
                    <?= $this->Html->link(__('Clear search'), ['action' => 'index'], ['class' => 'btn btn--ghost-dark']) ?>
                <?php elseif ($canManage): ?>
                    <?= $this->Html->link(__('Create your first post'), ['action' => 'add'], ['class' => 'btn btn--solid']) ?>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <?php if ($canManage): ?>
                <div class="posts-table-wrapper">
                    <table class="posts-table-modern">
                        <thead>
                            <tr>
                                <th class="col-title"><?= $this->Paginator->sort('title', __('Title')) ?></th>
                                <th class="col-status"><?= $this->Paginator->sort('published', __('Status')) ?></th>
                                <th class="col-date"><?= $this->Paginator->sort('created', __('Date')) ?></th>
                                <th class="col-actions"><?= __('Actions') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($posts as $post): ?>
                                <tr>
                                    <td class="col-title">
                                        <div class="post-title-cell">
                                            <strong class="post-title">
                                                <?= $this->Html->link(h($post->title), ['action' => 'view', $post->slug ?? $post->id]) ?>
                                            </strong>
                                            <span class="post-slug">/<?= h($post->slug) ?></span>
                                        </div>
                                    </td>
                                    <td class="col-status">
                                        <span class="status-badge <?= $post->published ? 'status-badge--published' : 'status-badge--draft' ?>">
                                            <?= $post->published ? __('Published') : __('Draft') ?>
                                        </span>
                                    </td>
                                    <td class="col-date">
                                        <div class="date-cell">
                                            <span class="date-primary"><?= $post->created->format('M j, Y') ?></span>
                                            <span class="date-secondary"><?= $post->created->format('g:i A') ?></span>
                                        </div>
                                    </td>
                                    <td class="col-actions">
                                        <div class="action-buttons">
                                            <?= $this->Html->link(__('View'), ['action' => 'view', $post->slug ?? $post->id], ['class' => 'action-btn action-btn--view']) ?>
                                            <?= $this->Html->link(__('Edit'), ['action' => 'edit', $post->id], ['class' => 'action-btn action-btn--edit']) ?>
                                            <?= $this->Form->postLink(
                                                __('Delete'),
                                                ['action' => 'delete', $post->id],
                                                [
                                                    'confirm' => __('Are you sure you want to delete "{0}"?', $post->title),
                                                    'class' => 'action-btn action-btn--delete',
                                                ]
                                            ) ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="posts-grid">
                    <?php foreach ($posts as $post): ?>
                        <article class="post-card">
                            <div class="post-card__badge">
                                <?= $post->published ? __('Published') : __('Coming Soon') ?>
                            </div>
                            <h3 class="post-card__title">
                                <?= $this->Html->link(h($post->title), ['action' => 'view', $post->slug ?? $post->id]) ?>
                            </h3>
                            <p class="post-card__excerpt">
                                <?= h($this->Text->truncate(strip_tags($post->body), 120, ['ellipsis' => '...', 'exact' => false])) ?>
                            </p>
                            <div class="post-card__footer">
                                <span class="post-card__date">
                                    <span class="date-icon">&#128197;</span>
                                    <?= $post->created->format('M j, Y') ?>
                                </span>
                                <?= $this->Html->link(
                                    __('Read more'),
                                    ['action' => 'view', $post->slug ?? $post->id],
                                    ['class' => 'post-card__link']
                                ) ?>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <div class="pagination-wrapper">
                <div class="pagination-summary">
                    <span class="pagination-summary__text">
                        <?= $this->Paginator->counter(__('Showing {{start}} to {{end}} of {{count}} results')) ?>
                    </span>
                </div>
                <nav class="pagination-modern">
                    <?= $this->Paginator->first('&#171;', ['class' => 'pagination-modern__btn pagination-modern__btn--edge', 'escape' => false]) ?>
                    <?= $this->Paginator->prev('&#8249; ' . __('Prev'), ['class' => 'pagination-modern__btn pagination-modern__btn--nav', 'escape' => false]) ?>

                    <div class="pagination-modern__numbers">
                        <?= $this->Paginator->numbers([
                            'first' => 1,
                            'last' => 1,
                            'modulus' => 3,
                            'class' => 'pagination-modern__num',
                            'currentClass' => 'is-active',
                        ]) ?>
                    </div>

                    <?= $this->Paginator->next(__('Next') . ' &#8250;', ['class' => 'pagination-modern__btn pagination-modern__btn--nav', 'escape' => false]) ?>
                    <?= $this->Paginator->last('&#187;', ['class' => 'pagination-modern__btn pagination-modern__btn--edge', 'escape' => false]) ?>
                </nav>
                <div class="pagination-jump">
                    <span><?= __('Go to page:') ?></span>
                    <input type="number" class="pagination-jump__input" min="1" max="<?= $this->Paginator->param('pageCount') ?>" value="<?= $this->Paginator->param('page') ?>" id="pageJump">
                    <button type="button" class="pagination-jump__btn" onclick="jumpToPage()"><?= __('Go') ?></button>
                </div>
            </div>
            <script>
            function jumpToPage() {
                const page = document.getElementById('pageJump').value;
                const max = <?= $this->Paginator->param('pageCount') ?>;
                if (page >= 1 && page <= max) {
                    const url = new URL(window.location.href);
                    url.searchParams.set('page', page);
                    window.location.href = url.toString();
                }
            }
            document.getElementById('pageJump').addEventListener('keypress', function(e) {
                if (e.key === 'Enter') jumpToPage();
            });
            </script>
        <?php endif; ?>
</section>
