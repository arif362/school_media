<?php
/**
 * @var \App\View\AppView $this
 */
$this->assign('title', __('Sign in'));
?>

<section class="auth-wrap auth-wrap--enhanced">
    <div class="auth-card">
        <header class="auth-card__brand">
            <span class="auth-logo">SM</span>
            <div>
                <p class="eyebrow text-muted"><?= __('Welcome back') ?></p>
                <h1><?= __('Sign in to School Media') ?></h1>
                <p class="auth-lede"><?= __('Enter your campus credentials to access analytics, approvals, and production pipelines.') ?></p>
            </div>
        </header>

        <div class="auth-pill-row">
            <span class="auth-pill"><?= __('SSO ready') ?></span>
            <span class="auth-pill"><?= __('AES-256 encrypted') ?></span>
            <span class="auth-pill"><?= __('Always-on support') ?></span>
        </div>

        <div class="auth-divider">
            <span><?= __('Use your credentials') ?></span>
        </div>

        <?= $this->Form->create(null, [
            'class' => 'auth-form',
            'templates' => ['inputContainer' => '{{content}}'],
        ]) ?>
            <div class="auth-field">
                <label class="auth-label" for="login-email">
                    <span><?= __('Email address') ?></span>
                    <small><?= __('Provided by your institution') ?></small>
                </label>
                <div class="auth-input-wrap">
                    <span class="auth-input-icon" aria-hidden="true">@</span>
                    <?= $this->Form->email('email', [
                        'id' => 'login-email',
                        'required' => true,
                        'class' => 'auth-input',
                        'placeholder' => 'you@schoolmedia.edu',
                        'autocomplete' => 'email',
                    ]) ?>
                </div>
            </div>
            <div class="auth-field">
                <label class="auth-label" for="login-password">
                    <span><?= __('Password') ?></span>
                    <small><?= __('Minimum 8 characters') ?></small>
                </label>
                <div class="auth-input-wrap">
                    <span class="auth-input-icon" aria-hidden="true">🔒</span>
                    <?= $this->Form->password('password', [
                        'id' => 'login-password',
                        'required' => true,
                        'class' => 'auth-input',
                        'placeholder' => '••••••••',
                        'autocomplete' => 'current-password',
                    ]) ?>
                </div>
                <a href="#" class="auth-link auth-link--inline"><?= __('Forgot password?') ?></a>
            </div>
            <div class="auth-actions">
                <label class="remember">
                    <input type="checkbox" name="remember" value="1">
                    <span><?= __('Remember me on this device') ?></span>
                </label>
                <a href="#" class="auth-link"><?= __('Need a reset?') ?></a>
            </div>
            <div class="auth-secondary">
                <p><?= __('Tip: Bookmark dashboard.schoolmedia.edu for fastest access.') ?></p>
            </div>
            <?= $this->Form->button(__('Sign in'), ['class' => 'btn btn--solid w-full']) ?>
        <?= $this->Form->end() ?>

        <div class="auth-support">
            <p><?= __('New to School Media?') ?></p>
            <a href="<?= $this->Url->build('/register') ?>" class="auth-link"><?= __('Create an account →') ?></a>
        </div>
    </div>

    <div class="auth-hero auth-hero--gradient">
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
