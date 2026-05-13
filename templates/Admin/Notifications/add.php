<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Notification $notification
 * @var array $types
 * @var array $targetRoles
 * @var array $users
 */
$this->assign('title', __('Create Notification'));
?>

<section class="admin-section">
    <header class="admin-section__header">
        <div>
            <nav class="breadcrumb">
                <?= $this->Html->link(__('Notifications'), ['action' => 'index']) ?> / <?= __('Create') ?>
            </nav>
            <h1><?= __('Create Notification') ?></h1>
        </div>
    </header>

    <div class="form-card">
        <?= $this->Form->create($notification, ['class' => 'notification-form']) ?>
            <div class="form-section">
                <h3><?= __('Notification Content') ?></h3>

                <div class="form-group">
                    <?= $this->Form->control('title', [
                        'label' => __('Title'),
                        'class' => 'form-control',
                        'placeholder' => __('Enter notification title'),
                        'required' => true,
                    ]) ?>
                </div>

                <div class="form-group">
                    <?= $this->Form->control('message', [
                        'label' => __('Message'),
                        'type' => 'textarea',
                        'class' => 'form-control',
                        'rows' => 4,
                        'placeholder' => __('Enter notification message'),
                        'required' => true,
                    ]) ?>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <?= $this->Form->control('type', [
                            'label' => __('Type'),
                            'options' => $types,
                            'class' => 'form-control',
                            'default' => 'info',
                        ]) ?>
                    </div>
                    <div class="form-group">
                        <?= $this->Form->control('link', [
                            'label' => __('Link (Optional)'),
                            'class' => 'form-control',
                            'placeholder' => __('/posts or https://example.com'),
                        ]) ?>
                    </div>
                </div>
            </div>

            <div class="form-section">
                <h3><?= __('Target Audience') ?></h3>

                <div class="form-row">
                    <div class="form-group">
                        <?= $this->Form->control('target_role', [
                            'label' => __('Target Role'),
                            'options' => $targetRoles,
                            'class' => 'form-control',
                            'empty' => false,
                        ]) ?>
                        <p class="form-hint"><?= __('Select a role or leave as "All Users" to notify everyone') ?></p>
                    </div>
                    <div class="form-group">
                        <?= $this->Form->control('target_user_id', [
                            'label' => __('Specific User (Optional)'),
                            'options' => $users,
                            'class' => 'form-control',
                            'empty' => __('-- Broadcast to all --'),
                        ]) ?>
                        <p class="form-hint"><?= __('Select a specific user to send a personal notification') ?></p>
                    </div>
                </div>
            </div>

            <div class="form-section">
                <h3><?= __('Settings') ?></h3>

                <div class="form-group">
                    <?= $this->Form->control('is_active', [
                        'label' => __('Active'),
                        'type' => 'checkbox',
                        'default' => true,
                    ]) ?>
                    <p class="form-hint"><?= __('Only active notifications are visible to users') ?></p>
                </div>
            </div>

            <div class="form-actions">
                <?= $this->Html->link(__('Cancel'), ['action' => 'index'], ['class' => 'btn btn--ghost-dark']) ?>
                <?= $this->Form->button(__('Create Notification'), ['class' => 'btn btn--solid']) ?>
            </div>
        <?= $this->Form->end() ?>
    </div>
</section>
