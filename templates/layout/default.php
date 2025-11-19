<?php
/**
 * @var \App\View\AppView $this
 */

$appTitle = 'School Media';
$primaryNav = [
    ['label' => 'About', 'url' => $this->Url->build('/') . '#about'],
    ['label' => 'Programs', 'url' => $this->Url->build('/') . '#programs'],
    ['label' => 'Contact', 'url' => $this->Url->build('/') . '#contact'],
    ['label' => 'Map', 'url' => $this->Url->build('/') . '#map'],
    ['label' => 'Stories', 'url' => $this->Url->build('/posts')],
];
if (!empty($this->request->getAttribute('identity')) && $this->request->getAttribute('identity')->role === 'admin') {
    $primaryNav[] = ['label' => 'Dashboard', 'url' => $this->Url->build('/admin/dashboard')];
}
$footerColumns = [
    [
        'title' => 'Studios & Labs',
        'links' => [
            ['label' => 'Broadcast Lab', 'url' => $this->Url->build('/') . '#programs'],
            ['label' => 'Story Accelerators', 'url' => $this->Url->build('/') . '#programs'],
            ['label' => 'Community Desk', 'url' => $this->Url->build('/') . '#programs'],
            ['label' => 'Leadership Cohort', 'url' => $this->Url->build('/') . '#programs'],
        ],
    ],
    [
        'title' => 'Resources',
        'links' => [
            ['label' => 'Success Stories', 'url' => $this->Url->build('/posts')],
            ['label' => 'Funding Playbook', 'url' => '#'],
            ['label' => 'Program Checklist', 'url' => '#'],
            ['label' => 'Media Curriculum', 'url' => '#'],
        ],
    ],
];
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
    <?= $this->fetch('script') ?>
</head>
<body class="sm-body">
    <header class="site-header">
        <div class="shell header__inner">
            <a class="brand" href="<?= $this->Url->build('/') ?>">School Media</a>
            <button class="nav-toggle" type="button" aria-label="Toggle navigation" data-nav-toggle>
                <span></span><span></span><span></span>
            </button>
            <nav class="primary-nav" data-nav-panel>
                <?php foreach ($primaryNav as $link): ?>
                    <a href="<?= h($link['url']) ?>"><?= h($link['label']) ?></a>
                <?php endforeach; ?>
            </nav>
            <div class="header-cta hide-mobile">
                <?php if (!empty($this->request->getAttribute('identity'))): ?>
                    <span class="nav-login">
                        <span class="nav-login__icon" aria-hidden="true">👤</span>
                        <span><?= h($this->request->getAttribute('identity')->get('name') ?? __('Logged in')) ?></span>
                    </span>
                    <a class="btn btn--ghost" href="<?= $this->Url->build('/logout') ?>"><?= __('Logout') ?></a>
                <?php else: ?>
                    <a class="nav-login" href="<?= $this->Url->build('/login') ?>">
                        <span class="nav-login__icon" aria-hidden="true">👤</span>
                        <span><?= __('Login') ?></span>
                    </a>
                    <a class="btn btn--ghost" href="<?= $this->Url->build('/') ?>#contact">Get Started</a>
                <?php endif; ?>
            </div>
        </div>
    </header>
    <div id="mobile-nav" class="mobile-nav" data-nav-panel>
        <?php foreach ($primaryNav as $link): ?>
            <a class="mobile-nav__link" href="<?= h($link['url']) ?>"><?= h($link['label']) ?></a>
        <?php endforeach; ?>
        <?php if (!empty($this->request->getAttribute('identity'))): ?>
            <a class="btn btn--solid w-full justify-center" href="<?= $this->Url->build('/logout') ?>"><?= __('Logout') ?></a>
        <?php else: ?>
            <a class="btn btn--solid w-full justify-center" href="<?= $this->Url->build('/login') ?>"><?= __('Login') ?></a>
            <a href="<?= $this->Url->build('/') ?>#contact" class="btn btn--ghost w-full justify-center">Get Started</a>
        <?php endif; ?>
    </div>

    <main class="page-content">
        <div class="toast-stack" aria-live="polite" aria-atomic="true">
            <?= $this->Flash->render() ?>
        </div>
        <?= $this->fetch('content') ?>
    </main>

    <footer class="site-footer" id="site-footer">
        <div class="shell footer-grid">
            <div>
                <p class="brand">School Media</p>
                <p>Elevating campus stories with modern production labs, editorial coaching, and strategic communications.</p>
                <div class="social">
                    <?php foreach (['Twitter' => '#', 'LinkedIn' => '#', 'YouTube' => '#'] as $label => $url): ?>
                        <a aria-label="<?= h($label) ?>" href="<?= h($url) ?>"><?= substr($label, 0, 1) ?></a>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php foreach ($footerColumns as $column): ?>
                <div>
                    <h4><?= h($column['title']) ?></h4>
                    <ul>
                        <?php foreach ($column['links'] as $link): ?>
                            <li><a href="<?= h($link['url']) ?>"><?= h($link['label']) ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endforeach; ?>
            <div>
                <h4>Contact</h4>
                <ul>
                    <li>120 Media Avenue<br>Innovation District<br>Springfield, USA</li>
                    <li>+1 (555) 010-2025</li>
                    <li>hello@schoolmedia.edu</li>
                    <li><a href="<?= $this->Url->build('/') ?>#contact">Schedule a consult →</a></li>
                </ul>
            </div>
        </div>
        <p class="footer-meta">© <?= date('Y') ?> School Media. Built with CakePHP.</p>
    </footer>

    <?= $this->Html->scriptBlock("
        (function () {
            const toggle = document.querySelector('[data-nav-toggle]');
            const panel = document.querySelector('[data-nav-panel]');
            if (toggle && panel) {
                toggle.addEventListener('click', () => {
                    panel.classList.toggle('is-open');
                    toggle.classList.toggle('is-open');
                    document.body.classList.toggle('nav-open', panel.classList.contains('is-open'));
                });
            }

            const slider = document.querySelector('[data-hero-slider]');
            const prevBtn = document.querySelector('[data-slider-prev]');
            const nextBtn = document.querySelector('[data-slider-next]');
            if (slider && prevBtn && nextBtn) {
                const scrollAmount = 280;
                prevBtn.addEventListener('click', () => {
                    slider.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
                });
                nextBtn.addEventListener('click', () => {
                    slider.scrollBy({ left: scrollAmount, behavior: 'smooth' });
                });
            }
        })();
    ") ?>
</body>
</html>
