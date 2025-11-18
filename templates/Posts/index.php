<?php
/**
 * @var \App\View\AppView $this
 * @var \Cake\Datasource\ResultSetInterface<\App\Model\Entity\Post> $posts
 */
?>
<section class="shell posts-list">
    <header class="posts-list__header">
        <div>
            <p class="eyebrow text-muted"><?= __('Editorial Hub') ?></p>
            <h2><?= __('Latest Stories') ?></h2>
            <p class="subtitle">
                <?= __('Manage media narratives, ensure quality, and keep the newsroom organized.') ?>
            </p>
        </div>
        <?= $this->Html->link(
            __('New Post'),
            ['action' => 'add'],
            ['class' => 'btn btn--solid']
        ) ?>
    </header>

    <div class="posts-table">
        <div class="posts-table__head">
            <span><?= $this->Paginator->sort('title', __('Title')) ?></span>
            <span><?= $this->Paginator->sort('slug', __('Slug')) ?></span>
            <span><?= $this->Paginator->sort('published', __('Status')) ?></span>
            <span><?= $this->Paginator->sort('created', __('Published On')) ?></span>
            <span class="actions"><?= __('Actions') ?></span>
        </div>
        <?php foreach ($posts as $post): ?>
            <article class="posts-table__row">
                <div>
                    <p class="posts-table__title"><?= h($post->title) ?></p>
                    <p class="posts-table__meta"><?= __('ID') ?> <?= h($post->id) ?></p>
                </div>
                <p class="posts-table__slug"><?= h($post->slug) ?></p>
                <p class="posts-table__status <?= $post->published ? 'is-published' : 'is-draft' ?>">
                    <?= $post->published ? __('Published') : __('Draft') ?>
                </p>
                <p class="posts-table__date">
                    <?= $this->Time->i18nFormat($post->created, 'MMM dd, yyyy HH:mm') ?>
                </p>
                <div class="actions pills">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $post->id], ['class' => 'pill']) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $post->id], ['class' => 'pill pill--ghost']) ?>
                    <?= $this->Form->postLink(
                        __('Delete'),
                        ['action' => 'delete', $post->id],
                        [
                            'confirm' => __('Are you sure you want to delete "{0}"?', $post->title),
                            'class' => 'pill pill--danger'
                        ]
                    ) ?>
                </div>
            </article>
        <?php endforeach; ?>
    </div>

    <div class="paginator posts-pager">
        <div class="count">
            <?= $this->Paginator->counter(
                __('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')
            ) ?>
        </div>
        <ul class="pagination">
            <?= $this->Paginator->first('← ' . __('First')) ?>
            <?= $this->Paginator->prev('‹ ' . __('Prev')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('Next') . ' ›') ?>
            <?= $this->Paginator->last(__('Last') . ' →') ?>
        </ul>
    </div>
</section>

