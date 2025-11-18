<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 * @var \App\View\AppView $this
 */

$cakeDescription = 'School Media';
?>
<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $this->fetch('title') ? $this->fetch('title') . ' | ' : '' ?><?= $cakeDescription ?></title>
    <?= $this->Html->meta('icon') ?>

    <?= $this->Html->css(['normalize.min', 'milligram.min', 'fonts', 'cake']) ?>

    <style>
        :root {
            --sm-primary: #1f3d7a;
            --sm-secondary: #fcbf49;
            --sm-dark: #0f1f3a;
            --sm-light: #f7f9fc;
        }
        body {
            font-family: 'Inter', 'Segoe UI', system-ui, -apple-system, BlinkMacSystemFont, sans-serif;
            margin: 0;
            background: var(--sm-light);
            color: #1f2933;
            line-height: 1.6;
        }
        .site-header {
            position: sticky;
            top: 0;
            z-index: 20;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem 5vw;
            background: rgba(15, 31, 58, 0.92);
            backdrop-filter: blur(8px);
            box-shadow: 0 10px 25px rgba(15, 31, 58, 0.25);
        }
        .site-header .brand {
            font-size: 1.3rem;
            font-weight: 700;
            letter-spacing: .08em;
            color: #fff;
            text-transform: uppercase;
        }
        .site-header nav {
            display: flex;
            gap: 1.25rem;
        }
        .site-header nav a {
            color: rgba(255,255,255,0.9);
            text-decoration: none;
            font-weight: 500;
            transition: color .2s ease, transform .2s ease;
        }
        .site-header nav a:hover {
            color: var(--sm-secondary);
            transform: translateY(-1px);
        }
        .site-content {
            padding: 0;
        }
        .flash-messages {
            max-width: 1100px;
            margin: 0 auto;
            padding: 1rem 5vw;
        }
        .site-footer {
            background: var(--sm-dark);
            color: rgba(255,255,255,0.75);
            padding: 3rem 5vw;
            margin-top: 3rem;
        }
        .footer-grid {
            display: grid;
            gap: 1.5rem;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        }
        .site-footer h4 {
            color: #fff;
            margin-bottom: 0.5rem;
        }
        .site-footer a {
            color: rgba(255,255,255,0.75);
            text-decoration: none;
        }
        .site-footer a:hover {
            color: var(--sm-secondary);
        }
        @media (max-width: 768px) {
            .site-header {
                flex-direction: column;
                gap: .75rem;
            }
        }
    </style>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
</head>
<body>
    <header class="site-header">
        <div class="brand">School Media</div>
        <nav>
            <a href="<?= $this->Url->build('/') ?>#about">About</a>
            <a href="<?= $this->Url->build('/') ?>#programs">Programs</a>
            <a href="<?= $this->Url->build('/') ?>#contact">Contact</a>
            <a href="<?= $this->Url->build('/') ?>#map">Location</a>
            <a href="<?= $this->Url->build('/posts') ?>">Stories</a>
        </nav>
    </header>
    <main class="site-content">
        <div class="flash-messages">
            <?= $this->Flash->render() ?>
        </div>
        <?= $this->fetch('content') ?>
    </main>
    <footer class="site-footer">
        <div class="footer-grid">
            <div>
                <h4>School Media</h4>
                <p>Elevating voices across campus with modern storytelling, production labs, and strategic partnerships.</p>
            </div>
            <div>
                <h4>Visit</h4>
                <p>120 Media Avenue<br>Innovation District<br>Springfield, USA</p>
            </div>
            <div>
                <h4>Connect</h4>
                <p>+1 (555) 010-2025<br>hello@schoolmedia.edu</p>
            </div>
        </div>
        <p style="margin-top:2rem;">© <?= date('Y') ?> School Media. Crafted with CakePHP.</p>
    </footer>
</body>
</html>
