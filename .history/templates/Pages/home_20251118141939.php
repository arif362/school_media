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

$programs = [
    ['title' => 'Broadcast Lab', 'copy' => 'Studio installs with teleprompters, switchers, cloud workflows, and curriculum blueprints.'],
    ['title' => 'Story Accelerators', 'copy' => 'Coaching pods for student journalists, podcasters, and filmmakers paired with district campaigns.'],
    ['title' => 'Community Desk', 'copy' => 'Managed storytelling squad that turns campus wins into newsletters, socials, and press kits.'],
    ['title' => 'Media Leadership Cohort', 'copy' => 'Professional learning for staff covering equity-centered storytelling and analytics.'],
];

$sliderItems = [
    ['title' => 'Student Newsrooms', 'copy' => 'Launch daily broadcast blocks with rundown automation and graphics packages.'],
    ['title' => 'Podcast Studios', 'copy' => 'Mobile-first rigs for classroom storytelling, featuring modern mics and live mixing.'],
    ['title' => 'Digital Campaigns', 'copy' => 'Omni-channel content plans aligned to enrollment, recruitment, and board priorities.'],
];
?>

<section id="home" class="hero-wrap text-white">
    <div class="max-w-6xl mx-auto grid gap-8 md:grid-cols-2 px-6 py-16">
        <div class="space-y-6">
            <p class="tracking-[0.45em] uppercase text-sm text-white/70">School Media</p>
            <h1 class="text-4xl md:text-5xl font-semibold leading-tight">Storytelling labs powering every voice on campus.</h1>
            <p class="text-white/80 text-lg">We design a full-stack media ecosystem for schools: broadcast studios, student-led newsrooms, content strategy, and partnerships that amplify community impact.</p>
            <div class="flex flex-wrap gap-4">
                <a class="btn-primary" href="#contact">Book a campus audit</a>
                <a class="btn-secondary" href="<?= $this->Url->build('/posts') ?>">Explore stories →</a>
            </div>
        </div>
        <div class="hero-card rounded-3xl p-6">
            <h3 class="text-white text-xl font-semibold mb-4">What we deliver</h3>
            <ul class="space-y-3 text-white/90 text-sm">
                <li>Media curriculum & certifications</li>
                <li>On-campus production studios</li>
                <li>District-wide storytelling campaigns</li>
                <li>Internship & mentorship pipelines</li>
            </ul>
        </div>
    </div>
    <div class="max-w-6xl mx-auto px-6 pb-12">
        <div id="studio-slider" class="tiny-slider">
            <?php foreach ($sliderItems as $item): ?>
                <article class="rounded-2xl bg-white/10 border border-white/15 p-6 text-white h-full flex flex-col justify-between">
                    <h4 class="text-lg font-semibold mb-3"><?= h($item['title']) ?></h4>
                    <p class="text-white/80 text-sm"><?= h($item['copy']) ?></p>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section id="about" class="bg-white py-16">
    <div class="max-w-6xl mx-auto px-6 grid gap-8 md:grid-cols-2 items-start">
        <div class="space-y-5">
            <p class="text-smuppercase tracking-[0.3em] text-smdark/60">About School Media</p>
            <h2 class="text-3xl font-semibold text-smprimary">Media strategy, production labs, and launch partners for modern schools.</h2>
            <p class="text-smdark/80">We help K-12 and higher-ed teams install professional-grade media programs. From research and funding to installation and editorial launch, our coaches and engineers support faculty and students to produce films, podcasts, broadcast news, and social content that elevate every story.</p>
            <div class="grid gap-4 md:grid-cols-3">
                <?php foreach ($metrics as $metric): ?>
                    <div class="stat-card rounded-2xl bg-white p-5 text-center">
                        <span class="block text-2xl font-bold text-smprimary"><?= h($metric['value']) ?></span>
                        <span class="text-sm text-smdark/70"><?= h($metric['label']) ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="program-card rounded-3xl bg-white p-6 space-y-4">
            <h3 class="text-xl font-semibold text-smprimary">What makes School Media different</h3>
            <ul class="space-y-3 text-smdark/80 text-sm">
                <li>• Hands-on workshops led by industry professionals</li>
                <li>• Turnkey studio design, funding guidance, and procurement support</li>
                <li>• Content calendars aligned to academic, enrollment, and community goals</li>
            </ul>
        </div>
    </div>
</section>

<section id="programs" class="bg-smlight py-16">
    <div class="max-w-6xl mx-auto px-6 text-center mb-10">
        <p class="text-smuppercase tracking-[0.3em] text-smdark/60">Signature tracks</p>
        <h2 class="text-3xl font-semibold text-smprimary">Launch or scale your media presence</h2>
        <p class="text-smdark/70 mt-3">Pick from modular experiences or mix-and-match for district-wide rollouts.</p>
    </div>
    <div class="max-w-6xl mx-auto px-6 grid gap-6 md:grid-cols-2">
        <?php foreach ($programs as $program): ?>
            <article class="program-card rounded-3xl bg-white p-6 text-left">
                <h3 class="text-xl font-semibold text-smprimary mb-2"><?= h($program['title']) ?></h3>
                <p class="text-smdark/80 text-sm"><?= h($program['copy']) ?></p>
            </article>
        <?php endforeach; ?>
    </div>
</section>

<section id="contact" class="bg-white py-16">
    <div class="max-w-6xl mx-auto px-6 text-center mb-10">
        <p class="text-smuppercase tracking-[0.3em] text-smdark/60">Contact us</p>
        <h2 class="text-3xl font-semibold text-smprimary">Tell us about your campus goals</h2>
        <p class="text-smdark/70 mt-3">We’ll deliver a tailored roadmap and budget within 48 hours.</p>
    </div>
    <div class="max-w-6xl mx-auto px-6 grid gap-8 md:grid-cols-2">
        <div class="program-card rounded-3xl bg-white p-6 space-y-3">
            <h3 class="text-xl font-semibold text-smprimary">Visit or call</h3>
            <p class="text-smdark/80 text-sm">120 Media Avenue<br>Innovation District<br>Springfield, USA</p>
            <p class="text-smdark/80 text-sm">+1 (555) 010-2025<br>hello@schoolmedia.edu</p>
            <p class="text-smdark/60 text-sm">Office hours: Mon–Fri, 8am–6pm CST</p>
        </div>
        <div class="program-card rounded-3xl bg-white p-6">
            <form class="space-y-4">
                <input class="w-full rounded-2xl border-slate-200 focus:ring-smsecondary" type="text" placeholder="Full Name" required>
                <input class="w-full rounded-2xl border-slate-200 focus:ring-smsecondary" type="email" placeholder="Work Email" required>
                <input class="w-full rounded-2xl border-slate-200 focus:ring-smsecondary" type="text" placeholder="School or District">
                <textarea class="w-full rounded-2xl border-slate-200 focus:ring-smsecondary" rows="4" placeholder="How can we help?"></textarea>
                <button class="btn-primary w-full justify-center" type="submit">Request a call back</button>
            </form>
        </div>
    </div>
</section>

<section id="map" class="bg-smlight py-16">
    <div class="max-w-6xl mx-auto px-6 text-center mb-8">
        <p class="text-smuppercase tracking-[0.3em] text-smdark/60">Location</p>
        <h2 class="text-3xl font-semibold text-smprimary">Visit the School Media lab</h2>
        <p class="text-smdark/70 mt-3">Minutes from the Springfield Innovation District with ample parking and transit access.</p>
    </div>
    <div class="max-w-6xl mx-auto px-6">
        <div class="overflow-hidden rounded-3xl shadow-2xl border border-white">
            <iframe
                title="School Media HQ"
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3153.0191846926207!2d-122.40134922366743!3d37.79361317198401!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x80858064f3a0d1c1%3A0x9fa8c1f1f1a9711!2s123%20Mission%20St%2C%20San%20Francisco%2C%20CA%2094105!5e0!3m2!1sen!2sus!4v1700000000000"
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"
                class="w-full min-h-[320px] border-0"></iframe>
        </div>
    </div>
</section>

<?= $this->Html->scriptBlock("
    document.addEventListener('DOMContentLoaded', function () {
        if (window.tns) {
            tns({
                container: '#studio-slider',
                items: 1,
                gutter: 16,
                autoplay: true,
                autoplayButtonOutput: false,
                controls: false,
                nav: true,
                responsive: {
                    640: { items: 2 },
                    1024: { items: 3 }
                }
            });
        }
    });
") ?>

