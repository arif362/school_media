<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Post $post
 * @var array|null $cancelUrl
 */

$cancelUrl = $cancelUrl ?? ['action' => 'index'];
$formTemplates = [
    'inputContainer' => '{{content}}',
    'label' => '<label{{attrs}}>{{text}}</label>',
];
?>

<div class="dashboard-form-panel">
    <?= $this->Form->create($post, ['class' => 'dashboard-form', 'templates' => $formTemplates]) ?>
        <div class="dashboard-form__grid">
            <div class="dashboard-field">
                <label for="post-title">
                    <span><?= __('Headline / Title') ?></span>
                    <small><?= __('Max 200 characters') ?></small>
                </label>
                <div class="dashboard-input-wrap">
                    <?= $this->Form->text('title', [
                        'id' => 'post-title',
                        'required' => true,
                        'placeholder' => __('e.g. Journalism Lab wins national broadcast award'),
                        'class' => 'dashboard-input',
                    ]) ?>
                </div>
            </div>
        </div>

        <div class="dashboard-field dashboard-field--full">
            <label for="post-body">
                <span><?= __('Body copy') ?></span>
                <small><?= __('Use full sentences, add CTA in final paragraph') ?></small>
            </label>
            <?= $this->Form->textarea('body', [
                'id' => 'post-body',
                'rows' => 12,
                'class' => 'dashboard-input dashboard-input--area',
                'placeholder' => __('Start with a compelling overview, then add supporting details, quotes, and next steps.'),
            ]) ?>
        </div>

        <div class="dashboard-form__grid">
            <div class="dashboard-field">
                <label>
                    <span><?= __('Publish status') ?></span>
                    <small><?= __('Live stories appear immediately') ?></small>
                </label>
                <label class="switch">
                    <?= $this->Form->checkbox('published', [
                        'class' => 'switch-input',
                        'hiddenField' => false,
                    ]) ?>
                    <span class="switch-slider"></span>
                    <span class="switch-label"><?= __('Publish immediately') ?></span>
                </label>
            </div>

            <div class="dashboard-field">
                <label>
                    <span><?= __('Content tags') ?></span>
                    <small><?= __('Optional keywords for search') ?></small>
                </label>
                <div class="dashboard-hint">
                    <p><?= __('Add tags from the post sidebar once saved to boost discovery.') ?></p>
                </div>
            </div>
        </div>

        <div class="dashboard-form__footer">
            <?= $this->Html->link(__('Cancel'), $cancelUrl, ['class' => 'btn btn--ghost-dark']) ?>
            <?= $this->Form->button(__('Save post'), ['class' => 'btn btn--solid']) ?>
        </div>
    <?= $this->Form->end() ?>
</div>


