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
    <header class="sticky top-0 z-30 backdrop-blur site-shadow bg-white/80 border-b border-white/60">
        <div class="max-w-6xl mx-auto flex flex-wrap items-center justify-between px-6 py-4">
            <a href="<?= $this->Url->build('/') ?>" class="text-smdark text-lg tracking-[0.4em] font-semibold uppercase">School Media</a>
            <nav class="flex gap-5 text-sm font-semibold text-smdark/80">
                <?php foreach (['About' => '#about', 'Programs' => '#programs', 'Contact' => '#contact', 'Map' => '#map'] as $label => $fragment): ?>
                    <a class="transition hover:text-smsecondary" href="<?= $this->Url->build('/') . $fragment ?>"><?= $label ?></a>
                <?php endforeach; ?>
                <a class="transition hover:text-smsecondary" href="<?= $this->Url->build('/posts') ?>">Stories</a>
            </nav>
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
                    <a aria-label="Twitter" href="#" class="hover:text-smsecondary transition">𝕏</a>
                    <a aria-label="LinkedIn" href="#" class="hover:text-smsecondary transition">in</a>
                    <a aria-label="YouTube" href="#" class="hover:text-smsecondary transition">▶</a>
                </div>
            </div>
            <div>
                <h4 class="text-white font-semibold mb-3">Studios & Labs</h4>
                <ul class="space-y-2 text-sm">
                    <li><a class="hover:text-smsecondary transition" href="<?= $this->Url->build('/') ?>#programs">Broadcast Lab</a></li>
                    <li><a class="hover:text-smsecondary transition" href="<?= $this->Url->build('/') ?>#programs">Story Accelerators</a></li>
                    <li><a class="hover:text-smsecondary transition" href="<?= $this->Url->build('/') ?>#programs">Community Desk</a></li>
                    <li><a class="hover:text-smsecondary transition" href="<?= $this->Url->build('/') ?>#programs">Leadership Cohort</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-white font-semibold mb-3">Resources</h4>
                <ul class="space-y-2 text-sm">
                    <li><a class="hover:text-smsecondary transition" href="<?= $this->Url->build('/posts') ?>">Success Stories</a></li>
                    <li><a class="hover:text-smsecondary transition" href="#">Funding Playbook</a></li>
                    <li><a class="hover:text-smsecondary transition" href="#">Program Checklist</a></li>
                    <li><a class="hover:text-smsecondary transition" href="#">Media Curriculum</a></li>
                </ul>
            </div>
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
</body>
</html>
