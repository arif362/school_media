<?php
/**
 * Print Layout
 *
 * Minimal layout for print-friendly pages (timetables, reports, etc.)
 *
 * @var \App\View\AppView $this
 *
 * @created 2026-05-15
 * @author Arif
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

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #1f2937;
            background: white;
            padding: 20px;
        }

        @media print {
            body {
                padding: 0;
            }
        }

        .print-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #1f2937;
        }

        .print-header__logo {
            font-size: 18px;
            font-weight: 700;
            color: #1f2937;
        }

        .print-header__info {
            text-align: right;
            font-size: 10px;
            color: #6b7280;
        }

        .print-actions {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }

        @media print {
            .print-actions {
                display: none;
            }
        }

        .print-actions button {
            padding: 8px 16px;
            font-size: 14px;
            font-weight: 500;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        .print-actions .btn-print {
            background: #2563eb;
            color: white;
        }

        .print-actions .btn-back {
            background: #e5e7eb;
            color: #374151;
        }

        .print-footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #d1d5db;
            font-size: 9px;
            color: #9ca3af;
            text-align: center;
        }

        @page {
            size: A4 landscape;
            margin: 15mm;
        }
    </style>
</head>
<body>
    <div class="print-actions">
        <button class="btn-print" onclick="window.print()">Print Timetable</button>
        <button class="btn-back" onclick="history.back()">Back</button>
    </div>

    <?= $this->fetch('content') ?>

    <div class="print-footer">
        <?= $appTitle ?> - Generated on <?= date('F j, Y \a\t g:i A') ?>
    </div>
</body>
</html>
