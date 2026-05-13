<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 * @var array $gradeLevels
 */
$this->assign('title', __('Edit {0}', h($user->name)));

$roleIcon = match($user->role) {
    'admin' => '&#128081;',
    'teacher' => '&#128104;&#8205;&#127979;',
    'student' => '&#127891;',
    default => '&#128100;'
};
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
                <?php else: ?>
                    <?= $this->Html->link(__('All Users'), ['action' => 'index']) ?>
                <?php endif; ?>
                / <?= $this->Html->link(h($user->name), ['action' => 'view', $user->id]) ?>
                / <?= __('Edit') ?>
            </nav>
            <h1><?= __('Edit User Profile') ?></h1>
            <p class="text-muted"><?= __('Update information for {0}', h($user->name)) ?></p>
        </div>
    </header>

    <div class="onboarding-form">
        <?= $this->Form->create($user, ['class' => 'onboarding-form__inner', 'type' => 'file']) ?>

        <div class="onboarding-form__sidebar">
            <!-- User Preview -->
            <div class="edit-user-preview">
                <div class="edit-user-preview__avatar">
                    <?php if ($user->avatar): ?>
                        <img src="<?= $this->Url->image($user->avatar) ?>" alt="<?= h($user->name) ?>">
                    <?php else: ?>
                        <span class="edit-user-preview__initial"><?= strtoupper(substr($user->name, 0, 1)) ?></span>
                    <?php endif; ?>
                    <span class="edit-user-preview__role"><?= $roleIcon ?></span>
                </div>
                <h3><?= h($user->name) ?></h3>
                <p><?= h($user->email) ?></p>
                <span class="badge badge--outline"><?= h(ucfirst($user->role)) ?></span>
            </div>

            <div class="onboarding-progress">
                <div class="onboarding-progress__item is-active" data-section="account">
                    <span class="onboarding-progress__number">1</span>
                    <span class="onboarding-progress__label"><?= __('Account') ?></span>
                </div>
                <div class="onboarding-progress__item" data-section="personal">
                    <span class="onboarding-progress__number">2</span>
                    <span class="onboarding-progress__label"><?= __('Personal') ?></span>
                </div>
                <?php if ($user->role === 'student'): ?>
                    <div class="onboarding-progress__item" data-section="academic">
                        <span class="onboarding-progress__number">3</span>
                        <span class="onboarding-progress__label"><?= __('Academic') ?></span>
                    </div>
                <?php endif; ?>
            </div>

            <div class="onboarding-tips">
                <h4><?= __('Quick Links') ?></h4>
                <ul>
                    <li><?= $this->Html->link(__('View Full Profile'), ['action' => 'view', $user->id]) ?></li>
                    <?php if ($user->role === 'teacher'): ?>
                        <li><?= $this->Html->link(__('Manage Subjects'), ['action' => 'teacherSubjects', $user->id]) ?></li>
                    <?php elseif ($user->role === 'student'): ?>
                        <li><?= $this->Html->link(__('Manage Classes'), ['action' => 'studentClasses', $user->id]) ?></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>

        <div class="onboarding-form__main">
            <!-- Section 1: Account Information -->
            <div class="form-card" id="section-account">
                <div class="form-card__header">
                    <div class="form-card__icon">
                        <span>&#128272;</span>
                    </div>
                    <div>
                        <h3><?= __('Account Information') ?></h3>
                        <p><?= __('Update login credentials and contact details') ?></p>
                    </div>
                </div>

                <div class="form-card__body">
                    <div class="form-grid">
                        <div class="form-field">
                            <label class="form-label" for="name">
                                <?= __('Full Name') ?>
                                <span class="form-label__required">*</span>
                            </label>
                            <div class="form-input-wrapper">
                                <span class="form-input-icon">&#128100;</span>
                                <?= $this->Form->text('name', [
                                    'class' => 'form-input form-input--with-icon',
                                    'required' => true,
                                    'id' => 'name',
                                ]) ?>
                            </div>
                        </div>

                        <div class="form-field">
                            <label class="form-label" for="email">
                                <?= __('Email Address') ?>
                                <span class="form-label__required">*</span>
                            </label>
                            <div class="form-input-wrapper">
                                <span class="form-input-icon">&#9993;</span>
                                <?= $this->Form->email('email', [
                                    'class' => 'form-input form-input--with-icon',
                                    'required' => true,
                                    'id' => 'email',
                                ]) ?>
                            </div>
                        </div>

                        <div class="form-field">
                            <label class="form-label" for="password">
                                <?= __('New Password') ?>
                                <span class="form-label__optional"><?= __('(Leave blank to keep current)') ?></span>
                            </label>
                            <div class="form-input-wrapper">
                                <span class="form-input-icon">&#128274;</span>
                                <?= $this->Form->password('password', [
                                    'class' => 'form-input form-input--with-icon',
                                    'value' => '',
                                    'id' => 'password',
                                    'autocomplete' => 'new-password',
                                ]) ?>
                            </div>
                            <p class="form-hint">
                                <span class="form-hint__icon">&#128161;</span>
                                <?= __('Only fill this if you want to change the password') ?>
                            </p>
                        </div>

                        <div class="form-field">
                            <label class="form-label" for="phone">
                                <?= __('Phone Number') ?>
                            </label>
                            <div class="form-input-wrapper">
                                <span class="form-input-icon">&#128222;</span>
                                <?= $this->Form->text('phone', [
                                    'class' => 'form-input form-input--with-icon',
                                    'id' => 'phone',
                                ]) ?>
                            </div>
                        </div>

                        <div class="form-field form-field--full">
                            <label class="form-label"><?= __('Account Status') ?></label>
                            <div class="status-toggle">
                                <label class="toggle-switch">
                                    <?= $this->Form->checkbox('active', ['class' => 'toggle-switch__input']) ?>
                                    <span class="toggle-switch__slider"></span>
                                </label>
                                <span class="status-toggle__label">
                                    <?= __('Account is {0}', '<strong id="status-text">' . ($user->active ? __('Active') : __('Inactive')) . '</strong>') ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 2: Personal Information -->
            <div class="form-card" id="section-personal">
                <div class="form-card__header">
                    <div class="form-card__icon">
                        <span>&#128113;</span>
                    </div>
                    <div>
                        <h3><?= __('Personal Information') ?></h3>
                        <p><?= __('Update personal details and biography') ?></p>
                    </div>
                </div>

                <div class="form-card__body">
                    <div class="form-grid">
                        <div class="form-field">
                            <label class="form-label" for="date-of-birth">
                                <?= __('Date of Birth') ?>
                            </label>
                            <div class="form-input-wrapper">
                                <span class="form-input-icon">&#128197;</span>
                                <?= $this->Form->date('date_of_birth', [
                                    'class' => 'form-input form-input--with-icon',
                                    'id' => 'date-of-birth',
                                ]) ?>
                            </div>
                        </div>

                        <div class="form-field">
                            <label class="form-label" for="avatar">
                                <?= __('Profile Photo') ?>
                            </label>
                            <div class="file-upload">
                                <?= $this->Form->file('avatar_file', [
                                    'class' => 'file-upload__input',
                                    'id' => 'avatar',
                                    'accept' => 'image/*',
                                ]) ?>
                                <label for="avatar" class="file-upload__label">
                                    <span class="file-upload__icon">&#128247;</span>
                                    <span class="file-upload__text"><?= __('Choose photo...') ?></span>
                                </label>
                            </div>
                        </div>

                        <div class="form-field form-field--full">
                            <label class="form-label" for="address">
                                <?= __('Address') ?>
                            </label>
                            <div class="form-input-wrapper">
                                <span class="form-input-icon form-input-icon--top">&#127968;</span>
                                <?= $this->Form->textarea('address', [
                                    'class' => 'form-input form-input--with-icon form-input--textarea',
                                    'rows' => 3,
                                    'id' => 'address',
                                ]) ?>
                            </div>
                        </div>

                        <div class="form-field form-field--full">
                            <label class="form-label" for="bio">
                                <?= __('Bio / About') ?>
                            </label>
                            <div class="form-input-wrapper">
                                <span class="form-input-icon form-input-icon--top">&#128221;</span>
                                <?= $this->Form->textarea('bio', [
                                    'class' => 'form-input form-input--with-icon form-input--textarea',
                                    'rows' => 4,
                                    'id' => 'bio',
                                    'placeholder' => $user->role === 'teacher'
                                        ? __('Teaching experience, qualifications, specializations...')
                                        : __('Interests, achievements, goals...'),
                                ]) ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php if ($user->role === 'student'): ?>
                <!-- Section 3: Academic Information (Students Only) -->
                <div class="form-card" id="section-academic">
                    <div class="form-card__header">
                        <div class="form-card__icon">
                            <span>&#127891;</span>
                        </div>
                        <div>
                            <h3><?= __('Academic Information') ?></h3>
                            <p><?= __('Update academic details and grade level') ?></p>
                        </div>
                    </div>

                    <div class="form-card__body">
                        <div class="form-grid">
                            <div class="form-field">
                                <label class="form-label" for="grade-level">
                                    <?= __('Grade Level') ?>
                                </label>
                                <div class="form-input-wrapper">
                                    <span class="form-input-icon">&#127979;</span>
                                    <?= $this->Form->select('grade_level', $gradeLevels, [
                                        'empty' => __('-- Select Grade Level --'),
                                        'class' => 'form-input form-input--with-icon',
                                        'id' => 'grade-level',
                                    ]) ?>
                                </div>
                            </div>
                        </div>

                        <div class="quick-links-box mt-4">
                            <h4><?= __('Manage Academics') ?></h4>
                            <div class="quick-links-grid">
                                <?= $this->Html->link(
                                    '<span class="quick-link__icon">&#127979;</span><span>' . __('Class Assignments') . '</span>',
                                    ['action' => 'studentClasses', $user->id],
                                    ['class' => 'quick-link', 'escape' => false]
                                ) ?>
                                <?= $this->Html->link(
                                    '<span class="quick-link__icon">&#128218;</span><span>' . __('Course Enrollments') . '</span>',
                                    ['action' => 'view', $user->id, '#' => 'panel-academics'],
                                    ['class' => 'quick-link', 'escape' => false]
                                ) ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Form Actions -->
            <div class="form-actions-bar">
                <?= $this->Html->link(__('Cancel'), ['action' => 'view', $user->id], ['class' => 'btn btn--ghost']) ?>
                <div class="form-actions-bar__right">
                    <?= $this->Form->button(__('Save Changes'), [
                        'class' => 'btn btn--primary btn--lg',
                        'type' => 'submit',
                    ]) ?>
                </div>
            </div>
        </div>

        <?= $this->Form->end() ?>
    </div>
</section>

<?php $this->start('script'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Progress indicator
    const sections = document.querySelectorAll('.form-card[id^="section-"]');
    const progressItems = document.querySelectorAll('.onboarding-progress__item');

    const observerOptions = {
        root: null,
        rootMargin: '-20% 0px -60% 0px',
        threshold: 0
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const sectionId = entry.target.id.replace('section-', '');
                progressItems.forEach(item => {
                    item.classList.toggle('is-active', item.dataset.section === sectionId);
                });
            }
        });
    }, observerOptions);

    sections.forEach(section => observer.observe(section));

    // Click on progress item to scroll
    progressItems.forEach(item => {
        item.addEventListener('click', function() {
            const sectionId = 'section-' + this.dataset.section;
            const section = document.getElementById(sectionId);
            if (section) {
                section.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });

    // Status toggle text update
    const activeCheckbox = document.querySelector('input[name="active"]');
    const statusText = document.getElementById('status-text');
    if (activeCheckbox && statusText) {
        activeCheckbox.addEventListener('change', function() {
            statusText.textContent = this.checked ? '<?= __('Active') ?>' : '<?= __('Inactive') ?>';
        });
    }

    // File upload preview
    const fileInput = document.getElementById('avatar');
    const fileText = document.querySelector('.file-upload__text');
    if (fileInput && fileText) {
        fileInput.addEventListener('change', function() {
            if (this.files.length > 0) {
                fileText.textContent = this.files[0].name;
            } else {
                fileText.textContent = '<?= __('Choose photo...') ?>';
            }
        });
    }
});
</script>
<?php $this->end(); ?>
