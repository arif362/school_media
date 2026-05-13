<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 * @var array $gradeLevels
 */
$this->assign('title', __('Edit Profile'));
$this->assign('dashboardTitle', __('Edit Profile'));
$this->assign('dashboardSubtitle', __('Update your personal information'));
?>

<?php $this->start('dashboardActions'); ?>
    <?= $this->Html->link(__('View Profile'), ['action' => 'view'], ['class' => 'btn btn--secondary']) ?>
<?php $this->end(); ?>

<div class="profile-edit">
    <div class="profile-card">
        <?= $this->Form->create($user, ['type' => 'file', 'class' => 'profile-form']) ?>

        <div class="profile-form__section">
            <h3><?= __('Profile Photo') ?></h3>
            <div class="profile-avatar-upload">
                <div class="profile-avatar-preview">
                    <?php if ($user->avatar): ?>
                        <img src="<?= $this->Url->image($user->avatar) ?>" alt="<?= h($user->name) ?>" id="avatar-preview">
                    <?php else: ?>
                        <span class="profile-avatar-initial" id="avatar-initial"><?= strtoupper(substr($user->name, 0, 1)) ?></span>
                    <?php endif; ?>
                </div>
                <div class="profile-avatar-input">
                    <?= $this->Form->control('avatar_file', [
                        'type' => 'file',
                        'label' => __('Upload new photo'),
                        'accept' => 'image/jpeg,image/png,image/gif,image/webp',
                        'class' => 'form-control-file',
                    ]) ?>
                    <p class="form-hint"><?= __('JPG, PNG, GIF or WebP. Max 2MB.') ?></p>
                </div>
            </div>
        </div>

        <div class="profile-form__section">
            <h3><?= __('Personal Information') ?></h3>
            <div class="form-grid">
                <?= $this->Form->control('name', [
                    'label' => __('Full Name'),
                    'class' => 'form-control',
                    'required' => true,
                ]) ?>
                <?= $this->Form->control('date_of_birth', [
                    'label' => __('Date of Birth'),
                    'type' => 'date',
                    'class' => 'form-control',
                    'empty' => true,
                ]) ?>
            </div>
            <?= $this->Form->control('bio', [
                'label' => __('About Me'),
                'type' => 'textarea',
                'class' => 'form-control',
                'rows' => 4,
                'placeholder' => __('Tell us a little about yourself...'),
            ]) ?>
        </div>

        <div class="profile-form__section">
            <h3><?= __('Contact Information') ?></h3>
            <div class="form-grid">
                <?= $this->Form->control('phone', [
                    'label' => __('Phone Number'),
                    'class' => 'form-control',
                    'placeholder' => __('e.g., +1 234 567 8900'),
                ]) ?>
            </div>
            <?= $this->Form->control('address', [
                'label' => __('Address'),
                'type' => 'textarea',
                'class' => 'form-control',
                'rows' => 3,
                'placeholder' => __('Your full address'),
            ]) ?>
        </div>

        <div class="profile-form__section">
            <h3><?= __('Academic Information') ?></h3>
            <?= $this->Form->control('grade_level', [
                'label' => __('Grade Level'),
                'type' => 'select',
                'options' => $gradeLevels,
                'empty' => __('-- Select Grade --'),
                'class' => 'form-control',
            ]) ?>
        </div>

        <div class="profile-form__actions">
            <?= $this->Form->button(__('Save Changes'), ['class' => 'btn btn--primary']) ?>
            <?= $this->Html->link(__('Cancel'), ['action' => 'view'], ['class' => 'btn btn--secondary']) ?>
        </div>

        <?= $this->Form->end() ?>
    </div>
</div>
