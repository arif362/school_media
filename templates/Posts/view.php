<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Post $post
 */

$this->assign('title', h($post->title));
$identity = $this->request->getAttribute('identity');
$canManage = $identity && in_array($identity->get('role'), ['admin', 'teacher'], true);
$statusLabel = $post->published ? __('Published') : __('Draft');
$statusClass = $post->published ? 'pill--success' : 'pill--ghost';
$shareUrl = $this->Url->build(['action' => 'view', $post->slug], ['fullBase' => true]);
?>

<section class="story-hero">
    <div class="shell story-hero__inner">
        <p class="eyebrow text-muted"><?= __('School Media Stories') ?></p>
        <h1><?= h($post->title) ?></h1>
        <div class="story-hero__meta">
            <span class="pill <?= h($statusClass) ?>"><?= h($statusLabel) ?></span>
            <span><?= __('Slug: {0}', h($post->slug)) ?></span>
            <span><?= __('Created {0}', $this->Time->timeAgoInWords($post->created)) ?></span>
        </div>
        <?php if ($canManage): ?>
            <div class="story-hero__actions">
                <?= $this->Html->link(__('Edit story'), ['action' => 'edit', $post->id], ['class' => 'btn btn--solid']) ?>
                <?= $this->Form->postLink(
                    __('Delete'),
                    ['action' => 'delete', $post->id],
                    [
                        'confirm' => __('Are you sure you want to delete "{0}"?', $post->title),
                        'class' => 'btn btn--danger',
                    ]
                ) ?>
                <?= $this->Html->link(__('All stories'), ['action' => 'index'], ['class' => 'btn btn--ghost-dark']) ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<section class="story-layout shell">
    <article class="story-body card">
        <?= $this->Text->autoParagraph(h($post->body)) ?>
    </article>
    <aside class="story-aside">
        <div class="story-card">
            <h4><?= __('Story details') ?></h4>
            <dl>
                <dt><?= __('Slug') ?></dt>
                <dd><?= h($post->slug) ?></dd>
                <dt><?= __('Published') ?></dt>
                <dd><?= $post->published ? __('Visible to public') : __('Hidden (draft)') ?></dd>
                <dt><?= __('Created') ?></dt>
                <dd><?= $this->Time->i18nFormat($post->created, 'MMM d, yyyy HH:mm') ?></dd>
                <dt><?= __('Last edited') ?></dt>
                <dd><?= $this->Time->i18nFormat($post->modified, 'MMM d, yyyy HH:mm') ?></dd>
            </dl>
        </div>
        <div class="story-card">
            <h4><?= __('Share & next steps') ?></h4>
            <p><?= __('Copy the URL below to share with teachers, community partners, or district leaders.') ?></p>
            <div class="story-share">
                <code id="story-share"><?= h($shareUrl) ?></code>
                <button type="button" class="btn btn--ghost-dark" data-copy="<?= h($shareUrl) ?>">
                    <?= __('Copy link') ?>
                </button>
            </div>
            <p class="story-tip"><?= __('Tip: highlight this story on the home page hero for 24h after publishing.') ?></p>
        </div>
    </aside>
</section>

<?= $this->Html->scriptBlock("
    document.addEventListener('click', function (event) {
        const button = event.target.closest('[data-copy]');
        if (!button) {
            return;
        }
        event.preventDefault();
        const value = button.getAttribute('data-copy');
        if (!navigator.clipboard) {
            return;
        }
        navigator.clipboard.writeText(value).then(function () {
            const original = button.textContent;
            button.textContent = '" . addslashes(__('Copied!')) . "';
            setTimeout(function () {
                button.textContent = original;
            }, 1600);
        });
    });
") ?>

