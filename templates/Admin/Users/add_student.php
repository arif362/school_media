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
                <?= __('Add New') ?>
            </nav>
            <h1><?= __('Add New Student') ?></h1>
            <p class="text-muted"><?= __('Enroll a new student in the school') ?></p>
        </div>
    </header>

    <div class="onboarding-form">
        <?= $this->Form->create($user, ['class' => 'onboarding-form__inner', 'type' => 'file']) ?>

        <div class="onboarding-form__sidebar">
            <div class="onboarding-welcome">
                <div class="onboarding-welcome__icon">&#127891;</div>
                <h3><?= __('New Student') ?></h3>
                <p><?= __('Enroll a new student and set up their account') ?></p>
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
                <div class="onboarding-progress__item" data-section="academic">
                    <span class="onboarding-progress__number">3</span>
                    <span class="onboarding-progress__label"><?= __('Academic') ?></span>
                </div>
            </div>

            <div class="onboarding-tips">
                <h4><?= __('Quick Tips') ?></h4>
                <ul>
                    <li><?= __('Parent/guardian contact info is recommended') ?></li>
                    <li><?= __('Class assignment can be done later if needed') ?></li>
                    <li><?= __('Password will be auto-generated if left blank') ?></li>
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
                        <p><?= __('Set up login credentials for the student') ?></p>
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
                                    'placeholder' => __('e.g., Jane Doe'),
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
                                    'placeholder' => __('student@school.edu'),
                                    'id' => 'email',
                                ]) ?>
                            </div>
                        </div>

                        <div class="form-field">
                            <label class="form-label" for="password">
                                <?= __('Password') ?>
                                <span class="form-label__optional"><?= __('(Optional)') ?></span>
                            </label>
                            <div class="form-input-wrapper">
                                <span class="form-input-icon">&#128274;</span>
                                <?= $this->Form->password('password', [
                                    'class' => 'form-input form-input--with-icon',
                                    'placeholder' => __('Leave blank to auto-generate'),
                                    'id' => 'password',
                                    'autocomplete' => 'new-password',
                                ]) ?>
                            </div>
                            <p class="form-hint">
                                <span class="form-hint__icon">&#128161;</span>
                                <?= __('A secure password will be generated if left empty') ?>
                            </p>
                        </div>

                        <div class="form-field">
                            <label class="form-label" for="phone">
                                <?= __('Phone / Guardian Phone') ?>
                            </label>
                            <div class="form-input-wrapper">
                                <span class="form-input-icon">&#128222;</span>
                                <?= $this->Form->text('phone', [
                                    'class' => 'form-input form-input--with-icon',
                                    'placeholder' => __('e.g., +1 234 567 8900'),
                                    'id' => 'phone',
                                ]) ?>
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
                        <p><?= __('Additional details about the student') ?></p>
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
                                    'placeholder' => __('Enter home address'),
                                    'id' => 'address',
                                ]) ?>
                            </div>
                        </div>

                        <div class="form-field form-field--full">
                            <label class="form-label" for="bio">
                                <?= __('About Student') ?>
                            </label>
                            <div class="form-input-wrapper">
                                <span class="form-input-icon form-input-icon--top">&#128221;</span>
                                <?= $this->Form->textarea('bio', [
                                    'class' => 'form-input form-input--with-icon form-input--textarea',
                                    'rows' => 3,
                                    'placeholder' => __('Interests, achievements, goals...'),
                                    'id' => 'bio',
                                ]) ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 3: Academic Information -->
            <div class="form-card" id="section-academic">
                <div class="form-card__header">
                    <div class="form-card__icon">
                        <span>&#127979;</span>
                    </div>
                    <div>
                        <h3><?= __('Academic Information') ?></h3>
                        <p><?= __('Set grade level and class assignment') ?></p>
                    </div>
                </div>

                <div class="form-card__body">
                    <div class="form-grid">
                        <div class="form-field">
                            <label class="form-label" for="grade-level">
                                <?= __('Grade Level') ?>
                            </label>
                            <div class="form-input-wrapper">
                                <span class="form-input-icon">&#128218;</span>
                                <?= $this->Form->select('grade_level', $gradeLevels, [
                                    'empty' => __('-- Select Grade Level --'),
                                    'class' => 'form-input form-input--with-icon',
                                    'id' => 'grade-level',
                                ]) ?>
                            </div>
                        </div>

                        <div class="form-field">
                            <label class="form-label" for="class-id">
                                <?= __('Assign to Class') ?>
                                <span class="form-label__optional"><?= __('(Optional)') ?></span>
                            </label>
                            <div class="form-input-wrapper">
                                <span class="form-input-icon">&#127979;</span>
                                <?= $this->Form->select('class_id', $classes, [
                                    'empty' => __('-- Select Class --'),
                                    'class' => 'form-input form-input--with-icon',
                                    'id' => 'class-id',
                                ]) ?>
                            </div>
                            <p class="form-hint">
                                <span class="form-hint__icon">&#128161;</span>
                                <?= __('You can assign the student to a class later if needed') ?>
                            </p>
                        </div>
                    </div>

                    <div class="info-box mt-4">
                        <div class="info-box__icon">&#128204;</div>
                        <div class="info-box__content">
                            <h4><?= __('What\'s Next?') ?></h4>
                            <p><?= __('After creating the student account, you can:') ?></p>
                            <ul>
                                <li><?= __('Enroll them in courses and subjects') ?></li>
                                <li><?= __('Set up their tuition fees') ?></li>
                                <li><?= __('Manage their class schedule') ?></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="form-actions-bar">
                <?= $this->Html->link(__('Cancel'), ['action' => 'students'], ['class' => 'btn btn--ghost']) ?>
                <div class="form-actions-bar__right">
                    <?= $this->Form->button(__('Create Student Account'), [
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
    // Progress indicator - highlight active section on scroll
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

    // Click on progress item to scroll to section
    progressItems.forEach(item => {
        item.addEventListener('click', function() {
            const sectionId = 'section-' + this.dataset.section;
            const section = document.getElementById(sectionId);
            if (section) {
                section.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });

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
