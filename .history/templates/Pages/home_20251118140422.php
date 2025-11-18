<?php
/**
 * @var \App\View\AppView $this
 */
$this->assign('title', 'Welcome to School Media');
?>
<style>
    .landing-hero {
        display: grid;
        gap: 2rem;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        padding: 6rem 5vw 4rem;
        background:
            linear-gradient(120deg, rgba(15,31,58,0.95), rgba(31,61,122,0.88)),
            url('https://images.unsplash.com/photo-1523580846011-d3a5bc25702b?auto=format&fit=crop&w=1600&q=80') center/cover;
        color: #fff;
    }
    .landing-hero h1 {
        font-size: clamp(2.5rem, 4vw, 3.5rem);
        margin-bottom: 1rem;
        line-height: 1.2;
    }
    .landing-hero p {
        font-size: 1.1rem;
        max-width: 530px;
    }
    .hero-cta {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
        margin-top: 2rem;
    }
    .btn-primary,
    .btn-secondary {
        border: none;
        padding: 0.85rem 1.8rem;
        border-radius: 999px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: .35rem;
    }
    .btn-primary {
        background: var(--sm-secondary);
        color: #0f1f3a;
    }
    .btn-secondary {
        background: transparent;
        border: 1px solid rgba(255,255,255,0.55);
        color: #fff;
    }
    .section {
        padding: 4.5rem 5vw;
        background: #fff;
    }
    .section:nth-of-type(even) {
        background: var(--sm-light);
    }
    .section h2 {
        font-size: 2rem;
        margin-bottom: 1rem;
        color: var(--sm-primary);
    }
    .summary-grid,
    .program-grid,
    .contact-grid {
        display: grid;
        gap: 1.5rem;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    }
    .card {
        background: #fff;
        border-radius: 1rem;
        padding: 1.5rem;
        box-shadow: 0 15px 40px rgba(15,31,58,0.08);
        border: 1px solid rgba(15,31,58,0.05);
    }
    .card h3 {
        margin-top: 0;
        color: var(--sm-primary);
    }
    .metrics {
        display: flex;
        gap: 2rem;
        flex-wrap: wrap;
        margin-top: 2rem;
    }
    .metric {
        flex: 1;
        min-width: 220px;
        background: var(--sm-primary);
        color: #fff;
        padding: 1.5rem;
        border-radius: 1rem;
        text-align: center;
    }
    .metric strong {
        font-size: 2rem;
        display: block;
    }
    .contact-grid {
        margin-top: 2rem;
        align-items: stretch;
    }
    .contact-form input,
    .contact-form textarea {
        width: 100%;
        padding: 0.85rem 1rem;
        border-radius: 0.75rem;
        border: 1px solid rgba(15,31,58,0.2);
        margin-bottom: 1rem;
    }
    .contact-form button {
        width: 100%;
    }
    #map iframe {
        width: 100%;
        min-height: 320px;
        border: 0;
        border-radius: 1rem;
        box-shadow: 0 15px 30px rgba(15,31,58,0.12);
    }
</style>

<section class="landing-hero" id="home">
    <div>
        <p style="letter-spacing: .45em; text-transform: uppercase; margin-bottom: 1rem;">School Media</p>
        <h1>Storytelling labs powering every voice on campus.</h1>
        <p>We design a full-stack media ecosystem for schools: broadcast studios, student-led newsrooms, content strategy, and partnerships that amplify community impact.</p>
        <div class="hero-cta">
            <a class="btn-primary" href="#contact">Book a campus audit</a>
            <a class="btn-secondary" href="<?= $this->Url->build('/posts') ?>">Explore stories →</a>
        </div>
    </div>
    <div class="card" style="background: rgba(255,255,255,0.1); border-color: rgba(255,255,255,0.25); color: #fff;">
        <h3 style="color:#fff;">What we deliver</h3>
        <ul style="list-style:none;padding:0;margin:0;color:rgba(255,255,255,0.85);">
            <li>• Media curriculum & certifications</li>
            <li>• On-campus production studios</li>
            <li>• District-wide storytelling campaigns</li>
            <li>• Internship & mentorship pipelines</li>
        </ul>
    </div>
</section>

<section class="section" id="about">
    <div class="summary-grid">
        <div>
            <h2>About School Media</h2>
            <p>We are a creative innovation group helping K-12 and higher-ed communities build professional-grade media programs. From strategy to installation to launch, we coach faculty and students to produce films, podcasts, broadcast news, and social content that elevates every story.</p>
            <div class="metrics">
                <div class="metric">
                    <strong>120+</strong>
                    Active campus partners
                </div>
                <div class="metric" style="background: var(--sm-secondary); color: var(--sm-dark);">
                    <strong>18</strong>
                    Regional media awards
                </div>
                <div class="metric">
                    <strong>4.8/5</strong>
                    Average program rating
                </div>
            </div>
        </div>
        <div class="card">
            <h3>Why it matters</h3>
            <p>Students learn to think critically, lead with empathy, and master the digital tools shaping modern careers. Administrators get a trusted partner to activate communications, enrollment, and community relationships.</p>
            <ul>
                <li>Hands-on workshops with industry pros</li>
                <li>Turnkey studio design & funding support</li>
                <li>Content calendars aligned to academic goals</li>
            </ul>
        </div>
    </div>
</section>

<section class="section" id="programs">
    <div style="text-align:center;margin-bottom:2rem;">
        <h2>Signature Programs</h2>
        <p>Flexible tracks to launch or scale your media presence.</p>
    </div>
    <div class="program-grid">
        <div class="card">
            <h3>Broadcast Lab</h3>
            <p>Design, install, and activate an on-campus studio with teleprompters, switchers, and cloud workflows. Includes curriculum guides and certification paths.</p>
        </div>
        <div class="card">
            <h3>Story Accelerators</h3>
            <p>Semester-long coaching for student journalists, podcasters, and film crews. We pair talent with real district campaigns.</p>
        </div>
        <div class="card">
            <h3>Community Desk</h3>
            <p>A managed service that turns campus wins into polished newsletters, social series, and press-ready assets.</p>
        </div>
        <div class="card">
            <h3>Media Leadership Cohort</h3>
            <p>Professional learning for teachers and administrators covering equity-centered storytelling, analytics, and program funding.</p>
        </div>
    </div>
</section>

<section class="section" id="contact">
    <div style="text-align:center;margin-bottom:2rem;">
        <h2>Contact Us</h2>
        <p>Tell us about your campus goals and we'll tailor a roadmap within 48 hours.</p>
    </div>
    <div class="contact-grid">
        <div class="card">
            <h3>Visit or call</h3>
            <p>120 Media Avenue<br>Innovation District<br>Springfield, USA</p>
            <p>+1 (555) 010-2025<br>hello@schoolmedia.edu</p>
            <p>Office hours: Mon–Fri, 8am–6pm CST</p>
        </div>
        <div class="card">
            <form class="contact-form">
                <input type="text" name="name" placeholder="Full name" required>
                <input type="email" name="email" placeholder="Work email" required>
                <input type="text" name="institution" placeholder="School or district">
                <textarea name="message" rows="4" placeholder="How can we help?"></textarea>
                <button class="btn-primary" type="submit">Request a call back</button>
            </form>
        </div>
    </div>
</section>

<section class="section" id="map">
    <div style="text-align:center;margin-bottom:2rem;">
        <h2>Find us</h2>
        <p>Our studio lab and collaboration space are located minutes from the Springfield Innovation District.</p>
    </div>
    <div class="card">
        <iframe
            title="School Media HQ"
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3153.0191846926207!2d-122.40134922366743!3d37.79361317198401!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x80858064f3a0d1c1%3A0x9fa8c1f1f1a9711!2s123%20Mission%20St%2C%20San%20Francisco%2C%20CA%2094105!5e0!3m2!1sen!2sus!4v1700000000000"
            allowfullscreen=""
            loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>
</section>

