<?php
declare(strict_types=1);

use Migrations\AbstractSeed;

class NotificationsSeed extends AbstractSeed
{
    private array $templates = [
        'info' => [
            'System maintenance scheduled for this weekend',
            'New features have been added to the platform',
            'Profile verification is now available',
            'Check out the updated help documentation',
        ],
        'success' => [
            'Your submission has been approved',
            'Congratulations on completing the course',
            'Your profile is now verified',
            'Achievement unlocked: Active contributor',
        ],
        'warning' => [
            'Your password will expire in 7 days',
            'Please update your profile information',
            'Incomplete registration detected',
            'Action required: Review pending items',
        ],
        'danger' => [
            'Security alert: New login detected',
            'Account suspension warning',
            'Urgent: Policy update requires acknowledgment',
            'Critical system update required',
        ],
    ];

    private array $messages = [
        'info' => 'This is an informational notification to keep you updated about the latest changes and updates on our platform. Please read through for more details.',
        'success' => 'Great news! This notification confirms a successful action or achievement. Keep up the excellent work!',
        'warning' => 'Attention required: This notification indicates something that needs your attention soon. Please review and take necessary action.',
        'danger' => 'Important: This is an urgent notification that requires immediate attention. Please review and respond accordingly.',
    ];

    public function run(): void
    {
        $data = [];
        $types = ['info', 'success', 'warning', 'danger'];
        $roles = [null, 'admin', 'teacher', 'student'];

        for ($i = 1; $i <= 15; $i++) {
            $type = $types[$i % count($types)];
            $titleIndex = ($i - 1) % count($this->templates[$type]);
            $targetRole = $roles[$i % count($roles)];

            $data[] = [
                'title' => $this->templates[$type][$titleIndex],
                'message' => $this->messages[$type] . ' Notification ID: ' . $i,
                'type' => $type,
                'target_role' => $targetRole,
                'target_user_id' => null,
                'link' => $i % 3 === 0 ? '/posts' : null,
                'is_active' => $i % 7 !== 0,
                'created_by' => 1,
                'created' => date('Y-m-d H:i:s', strtotime("-{$i} days")),
                'modified' => date('Y-m-d H:i:s', strtotime("-{$i} days")),
            ];
        }

        $table = $this->table('notifications');
        $table->insert($data)->save();
    }
}
