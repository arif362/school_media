<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
$this->assign('title', __('Create account'));
?>

<section class="auth-wrap">
    <div class="auth-card">
        <header class="auth-card__brand">
            <span class="auth-logo">SM</span>
            <div>
                <p class="eyebrow text-muted"><?= __('Join School Media') ?></p>
                <h1><?= __('Create your account') ?></h1>
            </div>
        </header>
        <p class="auth-intro"><?= __('Teachers, students, and admins collaborate here. Fill in your details to request access.') ?></p>

        <?= $this->Form->create($user, ['class' => 'auth-form']) ?>
            <div class="auth-field">
                <?= $this->Form->control('name', [
                    'label' => __('Full name'),
                    'required' => true,
                    'class' => 'auth-input',
                ]) ?>
            </div>
            <div class="auth-field">
                <?= $this->Form->control('email', [
                    'label' => __('Work email'),
                    'required' => true,
                    'class' => 'auth-input',
                    'placeholder' => 'you@schoolmedia.edu',
                ]) ?>
            </div>
            <div class="auth-field">
                <?= $this->Form->control('password', [
                    'label' => __('Password'),
                    'required' => true,
                    'class' => 'auth-input',
                    'placeholder' => '••••••••',
                ]) ?>
            </div>
            <div class="auth-field">
                <?= $this->Form->control('role', [
                    'label' => __('I am a'),
                    'type' => 'select',
                    'options' => [
                        'student' => __('Student'),
                        'teacher' => __('Teacher'),
                        'admin' => __('Admin'),
                    ],
                    'empty' => __('Select role'),
                    'class' => 'auth-input',
                ]) ?>
            </div>
            <?= $this->Form->button(__('Create account'), ['class' => 'btn btn--solid w-full']) ?>
        <?= $this->Form->end() ?>

        <div class="auth-support">
            <p><?= __('Already registered?') ?></p>
            <a class="auth-link" href="<?= $this->Url->build('/login') ?>"><?= __('Go to login →') ?></a>
        </div>
    </div>
    <div class="auth-hero">
        <div class="auth-hero__content">
            <p class="eyebrow"><?= __('Collaborate faster') ?></p>
            <h2><?= __('Students, teachers, admins — united in one workspace') ?></h2>
            <p><?= __('School Media gives every stakeholder the tools to build incredible storytelling programs.') ?></p>
        </div>
    </div>
</section>

