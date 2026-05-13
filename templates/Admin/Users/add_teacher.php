<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 * @var array $subjects
 */
$this->assign('title', __('Add Teacher'));
?>

<section class="admin-section">
    <header class="admin-section__header">
        <div>
            <nav class="breadcrumb">
                <?= $this->Html->link(__('Users'), ['action' => 'index']) ?> /
                <?= $this->Html->link(__('Teachers'), ['action' => 'teachers']) ?> /
                <?= __('Add') ?>
            </nav>
            <h1><?= __('Add New Teacher') ?></h1>
            <p class="text-muted"><?= __('Onboard a new teaching staff member') ?></p>
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
                        'placeholder' => __('Enter teacher\'s full name'),
                    ]) ?>
                </div>
                <div class="form-group">
                    <?= $this->Form->control('email', [
                        'type' => 'email',
                        'label' => __('Email Address'),
                        'class' => 'form-control',
                        'required' => true,
                        'placeholder' => __('teacher@school.edu'),
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
                        'label' => __('Phone Number'),
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
                    <?= $this->Form->control('address', [
                        'type' => 'textarea',
                        'label' => __('Address'),
                        'class' => 'form-control',
                        'rows' => 2,
                    ]) ?>
                </div>
            </div>
        </div>

        <div class="form-section">
            <h3 class="form-section__title"><?= __('Subject Assignments') ?></h3>
            <p class="text-muted"><?= __('Select subjects this teacher can teach.') ?></p>

            <div class="checkbox-grid">
                <?php foreach ($subjects as $subjectId => $subjectName): ?>
                    <label class="checkbox-card">
                        <input type="checkbox" name="subject_ids[]" value="<?= $subjectId ?>">
                        <span class="checkbox-card__content">
                            <span class="checkbox-card__icon">&#128218;</span>
                            <span class="checkbox-card__label"><?= h($subjectName) ?></span>
                        </span>
                    </label>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="form-actions">
            <?= $this->Html->link(__('Cancel'), ['action' => 'teachers'], ['class' => 'btn btn--ghost-dark']) ?>
            <?= $this->Form->button(__('Create Teacher Account'), ['class' => 'btn btn--solid']) ?>
        </div>

        <?= $this->Form->end() ?>
    </div>
</section>
