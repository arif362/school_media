<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 * @var array $gradeLevels
 */
$this->assign('title', __('Edit User'));
?>

<section class="admin-section">
    <header class="admin-section__header">
        <div>
            <nav class="breadcrumb">
                <?= $this->Html->link(__('Users'), ['action' => 'index']) ?> /
                <?php if ($user->role === 'teacher'): ?>
                    <?= $this->Html->link(__('Teachers'), ['action' => 'teachers']) ?>
                <?php elseif ($user->role === 'student'): ?>
                    <?= $this->Html->link(__('Students'), ['action' => 'students']) ?>
                <?php endif; ?>
                / <?= __('Edit') ?>
            </nav>
            <h1><?= __('Edit User') ?></h1>
            <p class="text-muted"><?= h($user->name) ?></p>
        </div>
    </header>

    <div class="form-card">
        <?= $this->Form->create($user, ['class' => 'admin-form']) ?>

        <div class="form-section">
            <h3 class="form-section__title"><?= __('Account Information') ?></h3>

            <div class="form-row">
                <div class="form-group">
                    <?= $this->Form->control('name', [
                        'label' => __('Full Name'),
                        'class' => 'form-control',
                        'required' => true,
                    ]) ?>
                </div>
                <div class="form-group">
                    <?= $this->Form->control('email', [
                        'type' => 'email',
                        'label' => __('Email Address'),
                        'class' => 'form-control',
                        'required' => true,
                    ]) ?>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <?= $this->Form->control('password', [
                        'type' => 'password',
                        'label' => __('New Password'),
                        'class' => 'form-control',
                        'value' => '',
                        'placeholder' => __('Leave blank to keep current'),
                    ]) ?>
                    <small class="form-hint"><?= __('Leave blank to keep the current password.') ?></small>
                </div>
                <div class="form-group">
                    <?= $this->Form->control('phone', [
                        'label' => __('Phone Number'),
                        'class' => 'form-control',
                    ]) ?>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <?= $this->Form->control('active', [
                        'type' => 'checkbox',
                        'label' => __('Account Active'),
                    ]) ?>
                </div>
            </div>
        </div>

        <div class="form-section">
            <h3 class="form-section__title"><?= __('Personal Information') ?></h3>

            <div class="form-row">
                <div class="form-group">
                    <?= $this->Form->control('date_of_birth', [
                        'type' => 'date',
                        'label' => __('Date of Birth'),
                        'class' => 'form-control',
                    ]) ?>
                </div>
                <?php if ($user->role === 'student'): ?>
                    <div class="form-group">
                        <?= $this->Form->control('grade_level', [
                            'type' => 'select',
                            'options' => $gradeLevels,
                            'empty' => __('-- Select Grade Level --'),
                            'label' => __('Grade Level'),
                            'class' => 'form-control',
                        ]) ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="form-row">
                <div class="form-group form-group--full">
                    <?= $this->Form->control('address', [
                        'type' => 'textarea',
                        'label' => __('Address'),
                        'class' => 'form-control',
                        'rows' => 2,
                    ]) ?>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group form-group--full">
                    <?= $this->Form->control('bio', [
                        'type' => 'textarea',
                        'label' => __('Bio'),
                        'class' => 'form-control',
                        'rows' => 3,
                    ]) ?>
                </div>
            </div>
        </div>

        <div class="form-actions">
            <?= $this->Html->link(__('Cancel'), ['action' => 'view', $user->id], ['class' => 'btn btn--ghost-dark']) ?>
            <?= $this->Form->button(__('Save Changes'), ['class' => 'btn btn--solid']) ?>
        </div>

        <?= $this->Form->end() ?>
    </div>
</section>
