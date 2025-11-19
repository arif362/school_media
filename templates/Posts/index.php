<?php
/**
 * @var \App\View\AppView $this
 * @var \Cake\Datasource\ResultSetInterface<\App\Model\Entity\Post> $posts
 */

$identity = $this->request->getAttribute('identity');
$canManage = $identity && in_array($identity->get('role'), ['admin', 'teacher'], true);
?>

<section class="shell posts-list">
    <header class="posts-list__header">
        <div>
            <p class="eyebrow text-muted"><?= $canManage ? __('Editorial Hub') : __('Campus Stories') ?></p>
            <h2><?= $canManage ? __('Latest Stories') : __('Fresh from School Media') ?></h2>
            <p class="subtitle">
                <?= $canManage
                    ? __('Manage media narratives, ensure quality, and keep the newsroom organized.')
                    : __('Spotlight achievements, events, and creative work across every program.') ?>
            </p>
        </div>
        <?php if ($canManage): ?>
            <?= $this->Html->link(
                __('New Post'),
                ['action' => 'add'],
                ['class' => 'btn btn--solid']
            ) ?>
        <?php endif; ?>
    </header>

    <?php if ($canManage): ?>
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
                                'class' => 'pill pill--danger',
                            ]
                        ) ?>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="stories-grid">
            <?php foreach ($posts as $post): ?>
                <article class="stories-card">
                    <p class="stories-card__eyebrow"><?= $post->published ? __('Featured') : __('Coming soon') ?></p>
                    <h3><?= h($post->title) ?></h3>
                    <p class="stories-card__excerpt">
                        <?= h($this->Text->truncate($post->body, 140, ['ellipsis' => '…'])) ?>
                    </p>
                    <div class="stories-card__meta">
                        <span><?= $this->Time->i18nFormat($post->created, 'MMM dd, yyyy') ?></span>
                        <?php if (!empty($post->slug)): ?>
                            <span><?= strtoupper($post->slug) ?></span>
                        <?php endif; ?>
                    </div>
                    <?= $this->Html->link(
                        __('Read story →'),
                        ['action' => 'view', $post->slug ?? $post->id],
                        ['class' => 'stories-card__link']
                    ) ?>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

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


