<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
$this->assign('title', __('My Profile'));
$this->assign('dashboardTitle', __('My Profile'));
$this->assign('dashboardSubtitle', __('View and manage your personal information'));
?>

<?php $this->start('dashboardActions'); ?>
    <?= $this->Html->link(__('Edit Profile'), ['action' => 'edit'], ['class' => 'btn btn--primary']) ?>
<?php $this->end(); ?>

<div class="profile-view">
    <div class="profile-card">
        <div class="profile-card__header">
            <div class="profile-avatar-large">
                <?php if ($user->avatar): ?>
                    <img src="<?= $this->Url->image($user->avatar) ?>" alt="<?= h($user->name) ?>">
                <?php else: ?>
                    <span class="profile-avatar-initial"><?= strtoupper(substr($user->name, 0, 1)) ?></span>
                <?php endif; ?>
            </div>
            <div class="profile-card__info">
                <h2><?= h($user->name) ?></h2>
                <p class="profile-card__email"><?= h($user->email) ?></p>
                <?php if ($user->grade_level): ?>
                    <span class="badge badge--primary"><?= h($user->grade_level) ?></span>
                <?php endif; ?>
            </div>
        </div>

        <div class="profile-card__body">
            <?php if ($user->bio): ?>
                <div class="profile-section">
                    <h3><?= __('About Me') ?></h3>
                    <p><?= nl2br(h($user->bio)) ?></p>
                </div>
            <?php endif; ?>

            <div class="profile-details">
                <h3><?= __('Contact Information') ?></h3>
                <dl class="profile-dl">
                    <div class="profile-dl__item">
                        <dt><?= __('Email') ?></dt>
                        <dd><?= h($user->email) ?></dd>
                    </div>
                    <?php if ($user->phone): ?>
                        <div class="profile-dl__item">
                            <dt><?= __('Phone') ?></dt>
                            <dd><?= h($user->phone) ?></dd>
                        </div>
                    <?php endif; ?>
                    <?php if ($user->address): ?>
                        <div class="profile-dl__item">
                            <dt><?= __('Address') ?></dt>
                            <dd><?= nl2br(h($user->address)) ?></dd>
                        </div>
                    <?php endif; ?>
                </dl>
            </div>

            <div class="profile-details">
                <h3><?= __('Academic Information') ?></h3>
                <dl class="profile-dl">
                    <?php if ($user->grade_level): ?>
                        <div class="profile-dl__item">
                            <dt><?= __('Grade Level') ?></dt>
                            <dd><?= h($user->grade_level) ?></dd>
                        </div>
                    <?php endif; ?>
                    <?php if ($user->date_of_birth): ?>
                        <div class="profile-dl__item">
                            <dt><?= __('Date of Birth') ?></dt>
                            <dd><?= $user->date_of_birth->format('F j, Y') ?></dd>
                        </div>
                    <?php endif; ?>
                </dl>
            </div>

            <div class="profile-details">
                <h3><?= __('Account Information') ?></h3>
                <dl class="profile-dl">
                    <div class="profile-dl__item">
                        <dt><?= __('Member Since') ?></dt>
                        <dd><?= $user->created->format('F j, Y') ?></dd>
                    </div>
                    <div class="profile-dl__item">
                        <dt><?= __('Last Updated') ?></dt>
                        <dd><?= $user->modified->format('F j, Y') ?></dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>
</div>
