<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
$this->assign('title', __('Create account'));
?>

<section class="auth-wrap auth-wrap--enhanced">
    <div class="auth-card">
        <header class="auth-card__brand">
            <span class="auth-logo">SM</span>
            <div>
                <p class="eyebrow text-muted"><?= __('Join School Media') ?></p>
                <h1><?= __('Create your account') ?></h1>
                <p class="auth-lede"><?= __('Launch verified storytelling programs for your campus in days.') ?></p>
            </div>
        </header>

        <div class="auth-pill-row">
            <span class="auth-pill"><?= __('Secure onboarding') ?></span>
            <span class="auth-pill"><?= __('Admin approved') ?></span>
            <span class="auth-pill"><?= __('5 min setup') ?></span>
        </div>

        <?= $this->Form->create($user, [
            'class' => 'auth-form',
            'templates' => ['inputContainer' => '{{content}}'],
        ]) ?>
            <div class="auth-form__grid">
                <div class="auth-field">
                    <label class="auth-label" for="name-field">
                        <span><?= __('Full name') ?></span>
                        <small><?= __('Match staff directory') ?></small>
                    </label>
                    <div class="auth-input-wrap">
                        <span class="auth-input-icon" aria-hidden="true">👤</span>
                        <?= $this->Form->text('name', [
                            'id' => 'name-field',
                            'required' => true,
                            'placeholder' => __('Jordan Lee'),
                            'class' => 'auth-input',
                        ]) ?>
                    </div>
                </div>
                <div class="auth-field">
                    <label class="auth-label" for="email-field">
                        <span><?= __('Work email') ?></span>
                        <small><?= __('Use your school domain') ?></small>
                    </label>
                    <div class="auth-input-wrap">
                        <span class="auth-input-icon" aria-hidden="true">@</span>
                        <?= $this->Form->email('email', [
                            'id' => 'email-field',
                            'required' => true,
                            'placeholder' => 'you@schoolmedia.edu',
                            'class' => 'auth-input',
                            'autocomplete' => 'email',
                        ]) ?>
                    </div>
                </div>
            </div>
            <div class="auth-form__grid">
                <div class="auth-field">
                    <label class="auth-label" for="password-field">
                        <span><?= __('Password') ?></span>
                        <small><?= __('8+ characters, mixed case') ?></small>
                    </label>
                    <div class="auth-input-wrap">
                        <span class="auth-input-icon" aria-hidden="true">🔒</span>
                        <?= $this->Form->password('password', [
                            'id' => 'password-field',
                            'required' => true,
                            'placeholder' => '••••••••',
                            'class' => 'auth-input',
                            'autocomplete' => 'new-password',
                        ]) ?>
                    </div>
                </div>
                <div class="auth-field">
                    <label class="auth-label" for="role-field">
                        <span><?= __('I am a') ?></span>
                        <small><?= __('Choose your role') ?></small>
                    </label>
                    <div class="auth-input-wrap">
                        <?= $this->Form->select('role', [
                            'student' => __('Student'),
                            'teacher' => __('Teacher'),
                            'admin' => __('Admin'),
                        ], [
                            'id' => 'role-field',
                            'empty' => __('Select role'),
                            'class' => 'auth-input auth-select',
                            'required' => true,
                        ]) ?>
                        <span class="auth-select-icon" aria-hidden="true">▾</span>
                    </div>
                </div>
            </div>
            <div class="auth-field auth-field--full">
                <div class="auth-hint">
                    <strong><?= __('Why we need this') ?></strong>
                    <p><?= __('Roles ensure each member receives the right dashboard, permissions, and onboarding checklist.') ?></p>
                </div>
            </div>
            <div class="auth-actions">
                <label class="remember">
                    <input type="checkbox" name="subscribe_updates" value="1">
                    <span><?= __('Notify me about training resources') ?></span>
                </label>
                <span class="auth-policy"><?= __('By continuing you agree to our ') ?>
                    <a href="#"><?= __('usage policy') ?></a>
                </span>
            </div>
            <?= $this->Form->button(__('Create account'), ['class' => 'btn btn--solid w-full']) ?>
        <?= $this->Form->end() ?>

        <div class="auth-support">
            <p><?= __('Already registered?') ?></p>
            <a class="auth-link" href="<?= $this->Url->build('/login') ?>"><?= __('Go to login →') ?></a>
        </div>
    </div>
    <div class="auth-hero auth-hero--gradient">
        <div class="auth-hero__content">
            <p class="eyebrow"><?= __('Collaborate faster') ?></p>
            <h2><?= __('Students, teachers, admins — united in one workspace') ?></h2>
            <p><?= __('School Media aligns curriculum, editorial calendars, and broadcast schedules inside a single command center.') ?></p>

            <div class="auth-highlights auth-highlights--grid">
                <div>
                    <h4><?= __('Smart guardrails') ?></h4>
                    <p><?= __('Role-based workflows and review steps keep stories on-brand.') ?></p>
                </div>
                <div>
                    <h4><?= __('Signal boosting') ?></h4>
                    <p><?= __('Promote stories to district, community, and donor audiences instantly.') ?></p>
                </div>
            </div>

            <blockquote class="auth-quote">
                <p>“<?= __('Our journalism lab onboarded 80 students in two weeks and doubled publication cadence.') ?>”</p>
                <cite>— <?= __('Dr. Simone Reyes, Arts & Media Dean') ?></cite>
            </blockquote>
        </div>
    </div>
</section>
