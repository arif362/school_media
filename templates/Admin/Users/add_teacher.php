<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 * @var \Cake\Collection\CollectionInterface $subjects
 */
$this->assign('title', __('Add Teacher'));
?>

<section class="admin-section">
    <header class="admin-section__header">
        <div>
            <nav class="breadcrumb">
                <?= $this->Html->link(__('Users'), ['action' => 'index']) ?> /
                <?= $this->Html->link(__('Teachers'), ['action' => 'teachers']) ?> /
                <?= __('Add New') ?>
            </nav>
            <h1><?= __('Add New Teacher') ?></h1>
            <p class="text-muted"><?= __('Onboard a new teaching staff member to the school') ?></p>
        </div>
    </header>

    <div class="onboarding-form">
        <?= $this->Form->create($user, ['class' => 'onboarding-form__inner', 'type' => 'file']) ?>

        <div class="onboarding-form__sidebar">
            <div class="onboarding-welcome">
                <div class="onboarding-welcome__icon">&#128104;&#8205;&#127979;</div>
                <h3><?= __('New Teacher') ?></h3>
                <p><?= __('Create an account for a new teaching staff member') ?></p>
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
                <div class="onboarding-progress__item" data-section="subjects">
                    <span class="onboarding-progress__number">3</span>
                    <span class="onboarding-progress__label"><?= __('Subjects') ?></span>
                </div>
            </div>

            <div class="onboarding-tips">
                <h4><?= __('Quick Tips') ?></h4>
                <ul>
                    <li><?= __('Use the official school email format') ?></li>
                    <li><?= __('Leave password blank to auto-generate a secure one') ?></li>
                    <li><?= __('Assign subjects the teacher is qualified to teach') ?></li>
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
                        <p><?= __('Set up login credentials for the teacher') ?></p>
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
                                    'placeholder' => __('e.g., John Smith'),
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
                                    'placeholder' => __('teacher@school.edu'),
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
                                <?= __('Phone Number') ?>
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
                        <p><?= __('Additional details about the teacher') ?></p>
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
                                    'placeholder' => __('Enter full address'),
                                    'id' => 'address',
                                ]) ?>
                            </div>
                        </div>

                        <div class="form-field form-field--full">
                            <label class="form-label" for="bio">
                                <?= __('Bio / Qualifications') ?>
                            </label>
                            <div class="form-input-wrapper">
                                <span class="form-input-icon form-input-icon--top">&#128221;</span>
                                <?= $this->Form->textarea('bio', [
                                    'class' => 'form-input form-input--with-icon form-input--textarea',
                                    'rows' => 4,
                                    'placeholder' => __('Teaching experience, qualifications, specializations...'),
                                    'id' => 'bio',
                                ]) ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 3: Subject Assignments -->
            <div class="form-card" id="section-subjects">
                <div class="form-card__header">
                    <div class="form-card__icon">
                        <span>&#128218;</span>
                    </div>
                    <div>
                        <h3><?= __('Subject Assignments') ?></h3>
                        <p><?= __('Select subjects this teacher is qualified to teach') ?></p>
                    </div>
                </div>

                <div class="form-card__body">
                    <div class="form-field form-field--full">
                        <label class="form-label">
                            <?= __('Teaching Subjects') ?>
                        </label>
                        <div class="token-select" id="subject-token-select">
                            <div class="token-select__tokens" id="selected-tokens"></div>
                            <input type="text"
                                   class="token-select__input"
                                   id="subject-search"
                                   placeholder="<?= __('Search and select subjects...') ?>"
                                   autocomplete="off">
                            <div class="token-select__dropdown" id="subject-dropdown">
                                <?php if ($subjects->count() > 0): ?>
                                    <?php foreach ($subjects as $subject): ?>
                                        <div class="token-select__option"
                                             data-id="<?= $subject->id ?>"
                                             data-name="<?= h($subject->name) ?>"
                                             data-code="<?= h($subject->code) ?>"
                                             data-category="<?= h($subject->category ?? '') ?>">
                                            <div class="token-select__option-main">
                                                <span class="token-select__option-name"><?= h($subject->name) ?></span>
                                                <?php if ($subject->category): ?>
                                                    <span class="token-select__option-category"><?= h($subject->category) ?></span>
                                                <?php endif; ?>
                                            </div>
                                            <span class="token-select__option-code"><?= h($subject->code) ?></span>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="token-select__empty">
                                        <?= __('No subjects available. Please add subjects first.') ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div id="subject-hidden-inputs"></div>
                        <p class="form-hint">
                            <span class="form-hint__icon">&#128269;</span>
                            <?= __('Type to search by name or code. Press Enter or click to select.') ?>
                        </p>
                    </div>

                    <div class="selected-subjects-preview" id="subjects-preview" style="display: none;">
                        <h4><?= __('Selected Subjects') ?> (<span id="subject-count">0</span>)</h4>
                        <div class="selected-subjects-list" id="selected-subjects-list"></div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="form-actions-bar">
                <?= $this->Html->link(__('Cancel'), ['action' => 'teachers'], ['class' => 'btn btn--ghost']) ?>
                <div class="form-actions-bar__right">
                    <?= $this->Form->button(__('Create Teacher Account'), [
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
    // Token Select functionality
    const tokenSelect = document.getElementById('subject-token-select');
    const searchInput = document.getElementById('subject-search');
    const dropdown = document.getElementById('subject-dropdown');
    const tokensContainer = document.getElementById('selected-tokens');
    const hiddenInputsContainer = document.getElementById('subject-hidden-inputs');
    const subjectsPreview = document.getElementById('subjects-preview');
    const subjectsList = document.getElementById('selected-subjects-list');
    const subjectCount = document.getElementById('subject-count');
    const options = dropdown.querySelectorAll('.token-select__option');

    let selectedSubjects = [];

    // Show dropdown on focus
    searchInput.addEventListener('focus', function() {
        dropdown.classList.add('is-open');
        filterOptions('');
    });

    // Hide dropdown on click outside
    document.addEventListener('click', function(e) {
        if (!tokenSelect.contains(e.target)) {
            dropdown.classList.remove('is-open');
        }
    });

    // Filter options as user types
    searchInput.addEventListener('input', function() {
        filterOptions(this.value.toLowerCase());
    });

    // Filter function
    function filterOptions(query) {
        let visibleCount = 0;
        options.forEach(option => {
            const name = option.dataset.name.toLowerCase();
            const code = option.dataset.code.toLowerCase();
            const category = (option.dataset.category || '').toLowerCase();
            const isSelected = selectedSubjects.some(s => s.id === option.dataset.id);

            if (isSelected) {
                option.style.display = 'none';
            } else if (query === '' || name.includes(query) || code.includes(query) || category.includes(query)) {
                option.style.display = 'flex';
                visibleCount++;
            } else {
                option.style.display = 'none';
            }
        });
    }

    // Select option on click
    options.forEach(option => {
        option.addEventListener('click', function() {
            const id = this.dataset.id;
            const name = this.dataset.name;
            const code = this.dataset.code;
            const category = this.dataset.category;

            if (!selectedSubjects.some(s => s.id === id)) {
                selectedSubjects.push({ id, name, code, category });
                renderTokens();
                renderHiddenInputs();
                renderPreview();
                filterOptions(searchInput.value.toLowerCase());
                searchInput.value = '';
                searchInput.focus();
            }
        });
    });

    // Render selected tokens
    function renderTokens() {
        tokensContainer.innerHTML = selectedSubjects.map(subject => `
            <span class="token-select__token" data-id="${subject.id}">
                <span class="token-select__token-text">${subject.name}</span>
                <button type="button" class="token-select__token-remove" data-id="${subject.id}">&times;</button>
            </span>
        `).join('');

        // Add remove event listeners
        tokensContainer.querySelectorAll('.token-select__token-remove').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.stopPropagation();
                removeSubject(this.dataset.id);
            });
        });
    }

    // Remove subject
    function removeSubject(id) {
        selectedSubjects = selectedSubjects.filter(s => s.id !== id);
        renderTokens();
        renderHiddenInputs();
        renderPreview();
        filterOptions(searchInput.value.toLowerCase());
    }

    // Render hidden inputs for form submission
    function renderHiddenInputs() {
        hiddenInputsContainer.innerHTML = selectedSubjects.map(subject =>
            `<input type="hidden" name="subject_ids[]" value="${subject.id}">`
        ).join('');
    }

    // Render preview list
    function renderPreview() {
        if (selectedSubjects.length === 0) {
            subjectsPreview.style.display = 'none';
            return;
        }

        subjectsPreview.style.display = 'block';
        subjectCount.textContent = selectedSubjects.length;
        subjectsList.innerHTML = selectedSubjects.map(subject => `
            <div class="selected-subject-item">
                <div class="selected-subject-item__info">
                    <span class="selected-subject-item__name">${subject.name}</span>
                    <span class="selected-subject-item__code">${subject.code}</span>
                </div>
                <button type="button" class="selected-subject-item__remove" data-id="${subject.id}">
                    &#128465;
                </button>
            </div>
        `).join('');

        // Add remove listeners to preview items
        subjectsList.querySelectorAll('.selected-subject-item__remove').forEach(btn => {
            btn.addEventListener('click', function() {
                removeSubject(this.dataset.id);
            });
        });
    }

    // Handle backspace to remove last token
    searchInput.addEventListener('keydown', function(e) {
        if (e.key === 'Backspace' && this.value === '' && selectedSubjects.length > 0) {
            selectedSubjects.pop();
            renderTokens();
            renderHiddenInputs();
            renderPreview();
            filterOptions('');
        }

        // Arrow key navigation
        if (e.key === 'ArrowDown' || e.key === 'ArrowUp') {
            e.preventDefault();
            const visibleOptions = Array.from(options).filter(o => o.style.display !== 'none');
            if (visibleOptions.length === 0) return;

            const currentFocus = dropdown.querySelector('.token-select__option--focused');
            let index = currentFocus ? visibleOptions.indexOf(currentFocus) : -1;

            if (currentFocus) {
                currentFocus.classList.remove('token-select__option--focused');
            }

            if (e.key === 'ArrowDown') {
                index = (index + 1) % visibleOptions.length;
            } else {
                index = index <= 0 ? visibleOptions.length - 1 : index - 1;
            }

            visibleOptions[index].classList.add('token-select__option--focused');
            visibleOptions[index].scrollIntoView({ block: 'nearest' });
        }

        // Enter to select focused option
        if (e.key === 'Enter') {
            e.preventDefault();
            const focused = dropdown.querySelector('.token-select__option--focused');
            if (focused) {
                focused.click();
            }
        }
    });

    // Click on token container to focus input
    tokenSelect.addEventListener('click', function(e) {
        if (e.target === tokenSelect || e.target === tokensContainer) {
            searchInput.focus();
        }
    });

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
