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

    <script>
        window.tailwind = {
            theme: {
                extend: {
                    colors: {
                        smprimary: '#1f3d7a',
                        smsecondary: '#fcbf49',
                        smdark: '#0f1f3a',
                        smlight: '#f7f9fc',
                    },
                },
                fontFamily: {
                    sans: ['Inter', 'Segoe UI', 'system-ui', 'sans-serif'],
                },
            },
        };
    </script>
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
    <?= $this->Html->css(['https://cdnjs.cloudflare.com/ajax/libs/tiny-slider/2.9.4/tiny-slider.css', 'school_media']) ?>
    <?= $this->Html->script('https://cdnjs.cloudflare.com/ajax/libs/tiny-slider/2.9.4/min/tiny-slider.js', ['block' => true]) ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
</head>
<body class="font-sans bg-smlight text-smdark">
    <header class="sticky top-0 z-30 backdrop-blur site-shadow bg-white/90 border-b border-white/60">
        <div class="max-w-6xl mx-auto flex items-center justify-between px-6 py-4 gap-4">
            <a href="<?= $this->Url->build('/') ?>" class="text-smdark text-lg tracking-[0.4em] font-semibold uppercase">School Media</a>
            <nav class="hidden lg:flex items-center gap-5 text-sm font-semibold text-smdark/80">
                <?php foreach ($primaryNav as $link): ?>
                    <a class="transition hover:text-smsecondary" href="<?= h($link['url']) ?>"><?= h($link['label']) ?></a>
                <?php endforeach; ?>
            </nav>
            <div class="hidden lg:flex items-center gap-3">
                <a href="<?= $this->Url->build('/') ?>#contact" class="btn-primary">Get Started</a>
            </div>
            <button id="nav-toggle" class="lg:hidden inline-flex items-center justify-center rounded-full border border-smdark/15 p-2 text-smdark focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-smsecondary" aria-label="Toggle navigation">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 7.5h16.5M3.75 12h16.5M3.75 16.5h16.5"/>
                </svg>
            </button>
        </div>
        <div id="mobile-nav" class="lg:hidden hidden border-t border-white/60 bg-white/95 backdrop-blur px-6 py-4 space-y-3">
            <?php foreach ($primaryNav as $link): ?>
                <a class="block text-sm font-semibold text-smdark/80 py-2 border-b border-smdark/5 last:border-none" href="<?= h($link['url']) ?>"><?= h($link['label']) ?></a>
            <?php endforeach; ?>
            <a href="<?= $this->Url->build('/') ?>#contact" class="btn-primary w-full justify-center">Get Started</a>
        </div>
    </header>

    <main>
        <?= $this->Flash->render() ?>
        <?= $this->fetch('content') ?>
    </main>

    <footer class="bg-smdark text-white/80 mt-16">
        <div class="max-w-6xl mx-auto px-6 py-12 grid gap-8 md:grid-cols-4">
            <div class="space-y-3">
                <span class="text-lg tracking-[0.4em] uppercase text-white">School Media</span>
                <p class="text-sm">Elevating campus stories with modern production labs, editorial coaching, and strategic communications.</p>
                <div class="flex gap-3 text-white/60 text-xl">
                    <?php foreach (['Twitter' => '#', 'LinkedIn' => '#', 'YouTube' => '#'] as $label => $url): ?>
                        <a aria-label="<?= h($label) ?>" href="<?= h($url) ?>" class="hover:text-smsecondary transition"><?= substr($label, 0, 1) ?></a>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php foreach ($footerColumns as $column): ?>
                <div>
                    <h4 class="text-white font-semibold mb-3"><?= h($column['title']) ?></h4>
                    <ul class="space-y-2 text-sm">
                        <?php foreach ($column['links'] as $link): ?>
                            <li><a class="hover:text-smsecondary transition" href="<?= h($link['url']) ?>"><?= h($link['label']) ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endforeach; ?>
            <div>
                <h4 class="text-white font-semibold mb-3">Contact</h4>
                <ul class="space-y-2 text-sm">
                    <li>120 Media Avenue<br>Innovation District<br>Springfield, USA</li>
                    <li>+1 (555) 010-2025</li>
                    <li>hello@schoolmedia.edu</li>
                    <li><a class="hover:text-smsecondary transition" href="<?= $this->Url->build('/') ?>#contact">Schedule a consult →</a></li>
                </ul>
            </div>
        </div>
        <p class="text-center text-xs text-white/50 border-t border-white/10 py-4">© <?= date('Y') ?> School Media. Built with CakePHP.</p>
    </footer>

    <?= $this->Html->scriptBlock("
        (() => {
            const toggle = document.getElementById('nav-toggle');
            const menu = document.getElementById('mobile-nav');
            if (!toggle || !menu) {
                return;
            }
            toggle.addEventListener('click', () => {
                menu.classList.toggle('hidden');
                document.body.classList.toggle('overflow-hidden', !menu.classList.contains('hidden'));
            });
        })();
    ") ?>
</body>
</html>
