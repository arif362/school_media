<?php
/**
 * @var \App\View\AppView $this
 */

$this->assign('title', 'Welcome to School Media');

$metrics = [
    ['value' => '120+', 'label' => 'Active campus partners'],
    ['value' => '18', 'label' => 'Regional media awards'],
    ['value' => '4.8/5', 'label' => 'Average program rating'],
];

$mediaHighlights = [
    ['title' => 'Student Newsrooms', 'copy' => 'Launch daily broadcast blocks with rundown automation, live graphics, and real-time analytics.'],
    ['title' => 'Podcast Studios', 'copy' => 'Modular rigs for classroom storytelling featuring modern mics, acoustic kits, and cloud editing.'],
    ['title' => 'Digital Campaigns', 'copy' => 'Omni-channel campaigns aligned to enrollment, recruitment, and board priorities.'],
];

$programs = [
    ['title' => 'Broadcast Lab', 'copy' => 'Studio installs with teleprompters, switchers, cloud workflows, and curriculum blueprints.'],
    ['title' => 'Story Accelerators', 'copy' => 'Coaching pods for student journalists, podcasters, and filmmakers tied to district campaigns.'],
    ['title' => 'Community Desk', 'copy' => 'Managed content squad that turns campus wins into newsletters, socials, and press kits.'],
    ['title' => 'Media Leadership Cohort', 'copy' => 'Professional learning for staff covering equity-centered storytelling and analytics.'],
];
?>

<section class="hero" id="home">
    <div class="shell hero__grid">
        <div class="hero__content">
            <p class="eyebrow">School Media</p>
            <h1>Storytelling labs powering every voice on campus.</h1>
            <p>We design a full-stack media ecosystem for schools: broadcast studios, student-led newsrooms, content strategy, and partnerships that amplify community impact.</p>
            <div class="cta-group">
                <a class="btn btn--solid" href="#contact">Book a campus audit</a>
                <a class="btn btn--outline" href="<?= $this->Url->build('/posts') ?>">Explore stories →</a>
            </div>
        </div>
        <div class="hero__card">
            <h3>What we deliver</h3>
            <ul>
                <li>Media curriculum & certifications</li>
                <li>On-campus production studios</li>
                <li>District-wide storytelling campaigns</li>
                <li>Internship & mentorship pipelines</li>
            </ul>
        </div>
    </div>
    <div class="hero-slider" data-hero-slider>
        <?php foreach ($mediaHighlights as $highlight): ?>
            <article class="hero-slide">
                <h4><?= h($highlight['title']) ?></h4>
                <p><?= h($highlight['copy']) ?></p>
            </article>
        <?php endforeach; ?>
    </div>
</section>

<section class="section" id="about">
    <div class="shell two-grid">
        <div>
            <p class="eyebrow text-muted">About School Media</p>
            <h2>Media strategy, production labs, and launch partners for modern schools.</h2>
            <p>We help K-12 and higher-ed teams install professional-grade media programs. From research and funding to installation and editorial launch, our coaches and engineers support faculty and students to produce films, podcasts, broadcast news, and social content that elevate every story.</p>
            <div class="metrics">
                <?php foreach ($metrics as $metric): ?>
                    <div class="metric">
                        <strong><?= h($metric['value']) ?></strong>
                        <span><?= h($metric['label']) ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="card">
            <h3>Why it matters</h3>
            <p>Students learn to think critically, lead with empathy, and master the digital tools shaping modern careers. Administrators get a trusted partner to activate communications, enrollment, and community relationships.</p>
            <ul>
                <li>Hands-on workshops led by industry professionals</li>
                <li>Turnkey studio design, funding guidance, and procurement support</li>
                <li>Content calendars aligned to academic and district goals</li>
            </ul>
        </div>
    </div>
</section>

<section class="section" id="programs">
    <div class="shell">
        <div class="section-heading">
            <p class="eyebrow text-muted">Signature tracks</p>
            <h2>Launch or scale your media presence</h2>
            <p>Pick from modular experiences or mix-and-match for district-wide rollouts.</p>
        </div>
        <div class="program-grid">
            <?php foreach ($programs as $program): ?>
                <article class="card">
                    <h3><?= h($program['title']) ?></h3>
                    <p><?= h($program['copy']) ?></p>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="section" id="contact">
    <div class="shell">
        <div class="section-heading">
            <p class="eyebrow text-muted">Contact us</p>
            <h2>Tell us about your campus goals</h2>
            <p>We’ll deliver a tailored roadmap and budget within 48 hours.</p>
        </div>
        <div class="contact-grid">
            <div class="card">
                <h3>Visit or call</h3>
                <p>120 Media Avenue<br>Innovation District<br>Springfield, USA</p>
                <p>+1 (555) 010-2025<br>hello@schoolmedia.edu</p>
                <p>Office hours: Mon–Fri, 8am–6pm CST</p>
            </div>
            <div class="card">
                <form class="contact-form" method="post" action="#">
                    <input type="text" name="name" placeholder="Full name" required>
                    <input type="email" name="email" placeholder="Work email" required>
                    <input type="text" name="institution" placeholder="School or district">
                    <textarea name="message" rows="4" placeholder="How can we help?"></textarea>
                    <button class="btn btn--solid" type="submit">Request a call back</button>
                </form>
            </div>
        </div>
    </div>
</section>

<section class="section" id="map">
    <div class="shell">
        <div class="section-heading">
            <p class="eyebrow text-muted">Location</p>
            <h2>Visit the School Media lab</h2>
            <p>Minutes from the Springfield Innovation District with ample parking and transit access.</p>
        </div>
        <div class="card">
            <iframe
                class="map-embed"
                title="School Media HQ"
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3153.0191846926207!2d-122.40134922366743!3d37.79361317198401!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x80858064f3a0d1c1%3A0x9fa8c1f1f1a9711!2s123%20Mission%20St%2C%20San%20Francisco%2C%20CA%2094105!5e0!3m2!1sen!2sus!4v1700000000000"
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
    </div>
</section>

