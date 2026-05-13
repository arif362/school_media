<?php
declare(strict_types=1);

use Migrations\AbstractSeed;

class PostsSeed extends AbstractSeed
{
    public function run(): void
    {
        $data = [
            [
                'title' => 'Welcome to School Media',
                'slug' => 'welcome-to-school-media',
                'body' => 'We are excited to launch School Media, your campus hub for storytelling, broadcasting, and digital media production. Our platform empowers students to share their voices and connect with the school community through professional-grade content creation tools and resources.',
                'published' => true,
                'created' => date('Y-m-d H:i:s', strtotime('-10 days')),
                'modified' => date('Y-m-d H:i:s', strtotime('-10 days')),
            ],
            [
                'title' => 'Student Journalism Workshop This Friday',
                'slug' => 'student-journalism-workshop-this-friday',
                'body' => 'Join us this Friday for an exciting journalism workshop led by award-winning reporter Sarah Mitchell. Learn the fundamentals of investigative reporting, interview techniques, and how to craft compelling stories that matter. The workshop runs from 3 PM to 5 PM in the Media Lab. All students are welcome, no prior experience required!',
                'published' => true,
                'created' => date('Y-m-d H:i:s', strtotime('-7 days')),
                'modified' => date('Y-m-d H:i:s', strtotime('-7 days')),
            ],
            [
                'title' => 'New Podcast Studio Now Open',
                'slug' => 'new-podcast-studio-now-open',
                'body' => 'Great news! Our brand new podcast studio is officially open for student use. The studio features professional-grade microphones, soundproofing, and editing software. Students can book 2-hour sessions through the media portal. Whether you want to start your own podcast or work on class projects, the studio is here for you.',
                'published' => true,
                'created' => date('Y-m-d H:i:s', strtotime('-5 days')),
                'modified' => date('Y-m-d H:i:s', strtotime('-5 days')),
            ],
            [
                'title' => 'Annual Film Festival Call for Submissions',
                'slug' => 'annual-film-festival-call-for-submissions',
                'body' => 'The 5th Annual School Media Film Festival is accepting submissions! This year\'s theme is "Connections" - exploring how we relate to each other, our community, and the world around us. Short films (under 10 minutes), documentaries, and animations are all welcome. Deadline for submissions is March 15th. Winners will be screened at the Spring Assembly.',
                'published' => true,
                'created' => date('Y-m-d H:i:s', strtotime('-3 days')),
                'modified' => date('Y-m-d H:i:s', strtotime('-3 days')),
            ],
            [
                'title' => 'Meet Our New Media Teacher: Mr. Rodriguez',
                'slug' => 'meet-our-new-media-teacher-mr-rodriguez',
                'body' => 'We\'re thrilled to welcome Mr. Carlos Rodriguez to our media department! Mr. Rodriguez brings 15 years of experience in broadcast journalism, having worked at major networks before dedicating himself to education. He\'ll be teaching Digital Storytelling and Advanced Video Production. Stop by room 204 to say hello!',
                'published' => true,
                'created' => date('Y-m-d H:i:s', strtotime('-2 days')),
                'modified' => date('Y-m-d H:i:s', strtotime('-2 days')),
            ],
            [
                'title' => 'Photography Contest Winners Announced',
                'slug' => 'photography-contest-winners-announced',
                'body' => 'Congratulations to the winners of our Fall Photography Contest! First place goes to Emma Chen for her stunning capture "Morning Light in the Library." Second place is awarded to Marcus Johnson for "Game Day Spirit," and third place to Aisha Patel for "After the Rain." All winning photos will be displayed in the main hallway throughout the semester.',
                'published' => true,
                'created' => date('Y-m-d H:i:s', strtotime('-1 day')),
                'modified' => date('Y-m-d H:i:s', strtotime('-1 day')),
            ],
            [
                'title' => 'Broadcast Club Seeking New Members',
                'slug' => 'broadcast-club-seeking-new-members',
                'body' => 'The Broadcast Club is looking for enthusiastic students to join our team! We produce the weekly morning announcements, cover school events, and create content for our YouTube channel. No experience necessary - we\'ll teach you everything from camera operation to video editing. Meetings are every Tuesday and Thursday after school.',
                'published' => true,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
            ],
            [
                'title' => 'Upcoming: Social Media Best Practices Seminar',
                'slug' => 'upcoming-social-media-best-practices-seminar',
                'body' => 'Learn how to use social media responsibly and effectively! Our upcoming seminar will cover personal branding, digital citizenship, content creation strategies, and online safety. Guest speakers include local influencers and digital marketing professionals. Mark your calendars for next Wednesday at 2 PM in the auditorium.',
                'published' => false,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
            ],
            [
                'title' => 'Equipment Checkout Policy Update',
                'slug' => 'equipment-checkout-policy-update',
                'body' => 'Important update regarding media equipment checkout: Students can now borrow cameras, tripods, and audio recorders for up to 5 days (previously 3 days). A valid student ID and signed responsibility form are required. Equipment must be returned clean and in working condition. Late returns may result in temporary suspension of checkout privileges.',
                'published' => false,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s'),
            ],
            [
                'title' => 'Student Spotlight: The Morning Show Team',
                'slug' => 'student-spotlight-the-morning-show-team',
                'body' => 'This week we celebrate the incredible work of our Morning Show production team! Led by senior producer Jake Williams, the team of 12 students delivers daily news, weather, and entertainment to our school community. Their dedication means early 6 AM call times and countless hours of preparation. Thank you for keeping us informed and entertained!',
                'published' => true,
                'created' => date('Y-m-d H:i:s', strtotime('-4 days')),
                'modified' => date('Y-m-d H:i:s', strtotime('-4 days')),
            ],
        ];

        $table = $this->table('posts');
        $table->insert($data)->save();
    }
}
