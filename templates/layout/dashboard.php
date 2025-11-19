<?php
/**
 * @var \App\View\AppView $this
 */
$identity = $this->request->getAttribute('identity');
$identityName = $identity?->get('name') ?? __('Content Lead');
$identityRole = $identity?->get('role') ?? 'admin';
$identityInitial = strtoupper(substr((string)$identityName, 0, 1)) ?: 'A';
$navLinks = [
    ['label' => __('Dashboard'), 'url' => $this->Url->build('/admin')],
    ['label' => __('Posts'), 'url' => $this->Url->build('/posts')],
    ['label' => __('Teachers'), 'url' => '#'],
    ['label' => __('Students'), 'url' => '#'],
    ['label' => __('Settings'), 'url' => '#'],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $this->fetch('title') ? $this->fetch('title') . ' | ' : '' ?>School Media</title>
    <?= $this->Html->meta('icon') ?>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <?= $this->Html->css('school_media') ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
</head>
<body class="sm-body dashboard-body">
    <div class="dashboard-shell">
        <aside class="dashboard-sidebar">
            <a class="dashboard-sidebar__brand" href="<?= $this->Url->build('/') ?>">School Media</a>
            <div class="dashboard-profile">
                <span class="dashboard-avatar"><?= $identityInitial ?></span>
                <div>
                    <strong><?= h($identityName) ?></strong>
                    <p><?= h(ucfirst($identityRole)) ?></p>
                </div>
            </div>
            <nav class="dashboard-nav">
                <?php foreach ($navLinks as $link): ?>
                    <a class="dashboard-nav__link" href="<?= h($link['url']) ?>"><?= h($link['label']) ?></a>
                <?php endforeach; ?>
            </nav>
            <div class="dashboard-sidebar__cta">
                <a class="btn btn--ghost w-full" href="<?= $this->Url->build('/logout') ?>"><?= __('Logout') ?></a>
            </div>
        </aside>

        <main class="dashboard-main">
            <header class="dashboard-header">
                <div>
                    <p class="eyebrow text-muted">
                        <?= $this->fetch('dashboardEyebrow') ?: __('Content Studio') ?>
                    </p>
                    <h1><?= $this->fetch('dashboardTitle') ?: ($this->fetch('title') ?: __('Dashboard')) ?></h1>
                    <?php if ($this->fetch('dashboardSubtitle')): ?>
                        <p><?= $this->fetch('dashboardSubtitle') ?></p>
                    <?php else: ?>
                        <p><?= __('Ship campus-wide announcements, stories, and updates with confidence.') ?></p>
                    <?php endif; ?>
                </div>
                <?php if (trim($this->fetch('dashboardActions')) !== ''): ?>
                    <div class="dashboard-header__actions">
                        <?= $this->fetch('dashboardActions') ?>
                    </div>
                <?php endif; ?>
            </header>

            <?php if (trim($this->fetch('breadcrumbs')) !== ''): ?>
                <div class="dashboard-breadcrumbs">
                    <?= $this->fetch('breadcrumbs') ?>
                </div>
            <?php endif; ?>

            <div class="toast-stack" aria-live="polite" aria-atomic="true">
                <?= $this->Flash->render() ?>
            </div>

            <?= $this->fetch('content') ?>
        </main>
    </div>

    <?= $this->fetch('script') ?>
</body>
</html>

