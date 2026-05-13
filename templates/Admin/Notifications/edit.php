<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Notification $notification
 * @var array $types
 * @var array $targetRoles
 * @var array $users
 */
$this->assign('title', __('Edit Notification'));
?>

<section class="admin-section">
    <header class="admin-section__header">
        <div>
            <nav class="breadcrumb">
                <?= $this->Html->link(__('Notifications'), ['action' => 'index']) ?> / <?= __('Edit') ?>
            </nav>
            <h1><?= __('Edit Notification') ?></h1>
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
                        'required' => true,
                    ]) ?>
                </div>

                <div class="form-group">
                    <?= $this->Form->control('message', [
                        'label' => __('Message'),
                        'type' => 'textarea',
                        'class' => 'form-control',
                        'rows' => 4,
                        'required' => true,
                    ]) ?>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <?= $this->Form->control('type', [
                            'label' => __('Type'),
                            'options' => $types,
                            'class' => 'form-control',
                        ]) ?>
                    </div>
                    <div class="form-group">
                        <?= $this->Form->control('link', [
                            'label' => __('Link (Optional)'),
                            'class' => 'form-control',
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
                    </div>
                    <div class="form-group">
                        <?= $this->Form->control('target_user_id', [
                            'label' => __('Specific User (Optional)'),
                            'options' => $users,
                            'class' => 'form-control',
                            'empty' => __('-- Broadcast to all --'),
                        ]) ?>
                    </div>
                </div>
            </div>

            <div class="form-section">
                <h3><?= __('Settings') ?></h3>

                <div class="form-group">
                    <?= $this->Form->control('is_active', [
                        'label' => __('Active'),
                        'type' => 'checkbox',
                    ]) ?>
                </div>
            </div>

            <div class="form-actions">
                <?= $this->Html->link(__('Cancel'), ['action' => 'index'], ['class' => 'btn btn--ghost-dark']) ?>
                <?= $this->Form->button(__('Update Notification'), ['class' => 'btn btn--solid']) ?>
            </div>
        <?= $this->Form->end() ?>
    </div>
</section>
