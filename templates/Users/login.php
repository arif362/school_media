<?php
/**
 * @var \App\View\AppView $this
 */
$this->assign('title', __('Sign in'));
?>

<section class="auth-wrap">
    <div class="auth-card">
        <header class="auth-card__brand">
            <span class="auth-logo">SM</span>
            <div>
                <p class="eyebrow text-muted"><?= __('Welcome back') ?></p>
                <h1><?= __('Sign in to School Media') ?></h1>
            </div>
        </header>
        <p class="auth-intro"><?= __('Manage admin dashboards, teachers, and student media programs in one command center.') ?></p>

        <div class="auth-divider">
            <span><?= __('Use your credentials') ?></span>
        </div>

        <?= $this->Form->create(null, ['class' => 'auth-form']) ?>
            <div class="auth-field">
                <?= $this->Form->control('email', [
                    'label' => __('Email address'),
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
            <div class="auth-actions">
                <label class="remember">
                    <input type="checkbox" name="remember" value="1">
                    <span><?= __('Remember me') ?></span>
                </label>
                <a href="#" class="auth-link"><?= __('Forgot password?') ?></a>
            </div>
            <?= $this->Form->button(__('Sign in'), ['class' => 'btn btn--solid w-full']) ?>
        <?= $this->Form->end() ?>

        <div class="auth-support">
            <p><?= __('New to School Media?') ?></p>
            <a href="<?= $this->Url->build('/register') ?>" class="auth-link"><?= __('Create an account →') ?></a>
        </div>
    </div>

    <div class="auth-hero">
        <div class="auth-hero__content">
            <p class="eyebrow"><?= __('School Media Platform') ?></p>
            <h2><?= __('Every story, every lab, one dashboard') ?></h2>
            <p><?= __('Monitor student newsrooms, teacher resources, and admin decisions in real time.') ?></p>
            <ul class="auth-highlights">
                <li><?= __('Instant visibility across campuses') ?></li>
                <li><?= __('Role-based controls for admin, teacher, student') ?></li>
                <li><?= __('Analytics on stories and engagement') ?></li>
            </ul>
            <div class="auth-metrics">
                <div>
                    <strong>120+</strong>
                    <span><?= __('Campuses onboarded') ?></span>
                </div>
                <div>
                    <strong>4.9/5</strong>
                    <span><?= __('Admin satisfaction score') ?></span>
                </div>
            </div>
        </div>
    </div>
</section>

