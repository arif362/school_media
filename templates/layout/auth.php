<?php
/**
 * @var \App\View\AppView $this
 */
$appTitle = 'School Media';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $this->fetch('title') ? $this->fetch('title') . ' | ' : '' ?><?= $appTitle ?></title>
    <?= $this->Html->meta('icon') ?>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <?= $this->Html->css('school_media') ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
</head>
<body class="sm-body auth-body">
    <div class="auth-page">
        <header class="auth-page__top">
            <a class="auth-brand" href="<?= $this->Url->build('/') ?>">School Media</a>
            <a class="auth-back" href="<?= $this->Url->build('/') ?>"><?= __('← Back to site') ?></a>
        </header>

        <div class="toast-stack" aria-live="polite" aria-atomic="true">
            <?= $this->Flash->render() ?>
        </div>

        <?= $this->fetch('content') ?>

        <footer class="auth-footer">
            <p>© <?= date('Y') ?> School Media. <?= __('All rights reserved.') ?></p>
            <a href="<?= $this->Url->build('/') ?>#contact"><?= __('Need help? Contact support') ?></a>
        </footer>
    </div>

    <?= $this->fetch('script') ?>
</body>
</html>

