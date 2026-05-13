<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 * @var array $classes
 * @var array $gradeLevels
 */
$this->assign('title', __('Add Student'));
?>

<section class="admin-section">
    <header class="admin-section__header">
        <div>
            <nav class="breadcrumb">
                <?= $this->Html->link(__('Users'), ['action' => 'index']) ?> /
                <?= $this->Html->link(__('Students'), ['action' => 'students']) ?> /
                <?= __('Add') ?>
            </nav>
            <h1><?= __('Add New Student') ?></h1>
            <p class="text-muted"><?= __('Enroll a new student in the school') ?></p>
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
                        'placeholder' => __('Enter student\'s full name'),
                    ]) ?>
                </div>
                <div class="form-group">
                    <?= $this->Form->control('email', [
                        'type' => 'email',
                        'label' => __('Email Address'),
                        'class' => 'form-control',
                        'required' => true,
                        'placeholder' => __('student@school.edu'),
                    ]) ?>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <?= $this->Form->control('password', [
                        'type' => 'password',
                        'label' => __('Password'),
                        'class' => 'form-control',
                        'placeholder' => __('Leave blank to auto-generate'),
                    ]) ?>
                    <small class="form-hint"><?= __('If left blank, a random password will be generated.') ?></small>
                </div>
                <div class="form-group">
                    <?= $this->Form->control('phone', [
                        'label' => __('Phone / Guardian Phone'),
                        'class' => 'form-control',
                        'placeholder' => __('Optional'),
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
                <div class="form-group">
                    <?= $this->Form->control('grade_level', [
                        'type' => 'select',
                        'options' => $gradeLevels,
                        'empty' => __('-- Select Grade Level --'),
                        'label' => __('Grade Level'),
                        'class' => 'form-control',
                    ]) ?>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group form-group--full">
                    <?= $this->Form->control('address', [
                        'type' => 'textarea',
                        'label' => __('Address'),
                        'class' => 'form-control',
                        'rows' => 2,
                        'placeholder' => __('Student\'s home address'),
                    ]) ?>
                </div>
            </div>
        </div>

        <div class="form-section">
            <h3 class="form-section__title"><?= __('Class Assignment') ?></h3>
            <p class="text-muted"><?= __('Assign student to a class (optional - can be done later).') ?></p>

            <div class="form-row">
                <div class="form-group">
                    <?= $this->Form->control('class_id', [
                        'type' => 'select',
                        'options' => $classes,
                        'empty' => __('-- Select Class --'),
                        'label' => __('Assign to Class'),
                        'class' => 'form-control',
                    ]) ?>
                </div>
            </div>
        </div>

        <div class="form-actions">
            <?= $this->Html->link(__('Cancel'), ['action' => 'students'], ['class' => 'btn btn--ghost-dark']) ?>
            <?= $this->Form->button(__('Create Student Account'), ['class' => 'btn btn--solid']) ?>
        </div>

        <?= $this->Form->end() ?>
    </div>
</section>
