<?php
declare(strict_types=1);

use Migrations\AbstractSeed;

class PostsSeed extends AbstractSeed
{
    private array $categories = [
        'Workshop' => ['Learn', 'Master', 'Discover', 'Explore'],
        'Event' => ['Join us for', 'Don\'t miss', 'Upcoming', 'Save the date for'],
        'News' => ['Breaking', 'Announcing', 'Update on', 'Latest news about'],
        'Spotlight' => ['Meet', 'Celebrating', 'Recognizing', 'Featuring'],
        'Club' => ['Join the', 'Welcome to', 'Introducing', 'Now recruiting for'],
    ];

    private array $topics = [
        'Photography', 'Video Production', 'Journalism', 'Podcasting', 'Broadcasting',
        'Digital Art', 'Graphic Design', 'Social Media', 'Film Making', 'Animation',
        'Audio Engineering', 'Content Creation', 'Storytelling', 'News Writing', 'Editing',
    ];

    private array $actions = [
        'workshop', 'seminar', 'competition', 'showcase', 'exhibition',
        'training session', 'meetup', 'collaboration', 'project launch', 'open house',
    ];

    private array $bodyTemplates = [
        'workshop' => 'Join us for an exciting %s session where you\'ll learn essential skills in %s. This hands-on %s is perfect for students of all skill levels. Our experienced instructors will guide you through %s techniques and best practices. Don\'t miss this opportunity to enhance your media skills!',
        'event' => 'We\'re thrilled to announce our upcoming %s event focused on %s. This is a great opportunity for students interested in %s to connect, learn, and showcase their talents. The event will feature %s demonstrations and networking opportunities.',
        'news' => 'Important update for all %s enthusiasts! We\'re expanding our %s program with new resources and opportunities. Students can now access %s equipment and facilities. Stay tuned for more exciting developments in our %s department.',
        'spotlight' => 'This week we celebrate the outstanding achievements in %s. Our talented students have been working hard on various %s projects. Their dedication to %s excellence continues to inspire the entire school community. Congratulations to everyone involved in %s!',
        'club' => 'The %s Club is actively seeking new members! Whether you\'re experienced in %s or just getting started, we welcome all skill levels. Our club meets regularly to work on %s projects and share %s knowledge. Join us and be part of something amazing!',
    ];

    public function run(): void
    {
        $data = [];
        $categories = array_keys($this->categories);
        $templateKeys = ['workshop', 'event', 'news', 'spotlight', 'club'];

        for ($i = 1; $i <= 100; $i++) {
            $category = $categories[$i % count($categories)];
            $prefix = $this->categories[$category][$i % count($this->categories[$category])];
            $topic = $this->topics[$i % count($this->topics)];
            $action = $this->actions[$i % count($this->actions)];
            $templateKey = $templateKeys[$i % count($templateKeys)];

            $title = $this->generateTitle($prefix, $topic, $action, $i);
            $slug = $this->slugify($title) . '-' . $i;
            $body = sprintf($this->bodyTemplates[$templateKey], $action, $topic, $action, $topic);
            $daysAgo = ($i % 120) + 1;

            $data[] = [
                'title' => $title,
                'slug' => $slug,
                'body' => $body,
                'published' => $i % 5 !== 0, // 80% published, 20% draft
                'created' => date('Y-m-d H:i:s', strtotime("-{$daysAgo} days")),
                'modified' => date('Y-m-d H:i:s', strtotime("-{$daysAgo} days")),
            ];
        }

        $table = $this->table('posts');
        $table->insert($data)->save();
    }

    private function generateTitle(string $prefix, string $topic, string $action, int $index): string
    {
        $formats = [
            "{$prefix} {$topic} {$action}",
            "{$topic}: {$prefix} the Basics",
            "Student {$topic} {$action} #{$index}",
            "{$prefix} Our {$topic} Program",
            "{$topic} Skills {$action}",
        ];

        return $formats[$index % count($formats)];
    }

    private function slugify(string $text): string
    {
        $text = strtolower(trim($text));
        $text = preg_replace('/[^a-z0-9\s-]/', '', $text);
        $text = preg_replace('/[\s-]+/', '-', $text);

        return trim($text, '-');
    }
}
