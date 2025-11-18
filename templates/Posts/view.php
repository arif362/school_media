<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Post $post
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Post'), ['action' => 'edit', $post->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(
                __('Delete Post'),
                ['action' => 'delete', $post->id],
                [
                    'confirm' => __('Are you sure you want to delete "{0}"?', $post->title),
                    'class' => 'side-nav-item',
                ]
            ) ?>
            <?= $this->Html->link(__('List Posts'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Post'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="posts view content">
            <h3><?= h($post->title) ?></h3>
            <table>
                <tr>
                    <th><?= __('Slug') ?></th>
                    <td><?= h($post->slug) ?></td>
                </tr>
                <tr>
                    <th><?= __('Published') ?></th>
                    <td><?= $post->published ? __('Yes') : __('No') ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= $this->Time->i18nFormat($post->created, 'yyyy-MM-dd HH:mm') ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified') ?></th>
                    <td><?= $this->Time->i18nFormat($post->modified, 'yyyy-MM-dd HH:mm') ?></td>
                </tr>
            </table>
            <div class="text">
                <h4><?= __('Body') ?></h4>
                <?= $this->Text->autoParagraph(h($post->body)) ?>
            </div>
        </div>
    </div>
</div>

