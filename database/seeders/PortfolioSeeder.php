<?php

namespace Database\Seeders;

use App\Models\Education;
use App\Models\Experience;
use App\Models\Project;
use App\Models\SiteCopy;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PortfolioSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedAdmin();
        $this->seedSiteCopy();
        $this->seedProjects();
        $this->seedExperiences();
        $this->seedSkills();
        $this->seedEducation();
    }

    protected function seedAdmin(): void
    {
        User::query()->updateOrCreate(
            ['email' => env('ADMIN_EMAIL', 'mirzarusyaidi891@gmail.com')],
            [
                'name' => config('portfolio.name'),
                'password' => Hash::make(env('ADMIN_PASSWORD', 'password')),
            ],
        );
    }

    protected function seedSiteCopy(): void
    {
        $copy = SiteCopy::current();
        $copy->fill(SiteCopy::defaultAttributes())->save();
    }

    protected function seedProjects(): void
    {
        $projects = [
            [
                'title' => 'Xcellorate',
                'slug' => 'xcellorate',
                'category' => 'Full Stack / Platform',
                'short_description' => 'Gamified engineering platform for learning software engineering.',
                'description' => 'A gamified engineering learning platform designed to help developers practice technical skills through interactive challenges and mini-games.',
                'role' => 'Full Stack Developer',
                'technologies' => ['React 19', 'TypeScript', 'Golang', 'PostgreSQL', 'REST API', 'Tailwind CSS'],
                'problem' => 'Most engineering practice still happens in isolation — tutorials that fade, interviews that do not resemble real work, and almost no feedback loop. Students and junior developers needed a structured way to train technical judgment, not just syntax.',
                'solution' => 'Xcellorate treats practice as a product. Challenges, timed sessions, scoring, and leaderboards sit on top of a real backend so learners train against systems that behave like software, not flashcards.',
                'features' => [
                    'Interactive challenge sessions with multiple game modes',
                    'Published learning sessions with access control',
                    'Global and session leaderboards',
                    'Question authoring and AI-assisted generation',
                    'Role-aware flows for students, mentors, and admins',
                ],
                'technical_details' => 'The client is a React 19 and TypeScript application. A Go REST API owns game state, scoring, sessions, and authorization. PostgreSQL stores users, attempts, and published content. The platform is designed around clear API contracts so the frontend stays thin and the backend remains the source of truth.',
                'challenges' => 'The hardest parts were keeping game state consistent across attempts, designing scoring that felt fair, and building an authoring flow that mentors could use without touching the database. Authorization had to stay explicit as roles expanded.',
                'outcome' => 'A working engineering practice platform with sessions, challenges, and competitive feedback — built as a real product, not a demo.',
                'hero_image' => 'assets/projects/xcellorate/dashboard.png',
                'gallery' => [
                    'assets/projects/xcellorate/landingpage.png',
                    'assets/projects/xcellorate/gamehub.png',
                    'assets/projects/xcellorate/leaderboard.png',
                    'assets/projects/xcellorate/profile.png',
                ],
                'video_url' => null,
                'github_url' => null,
                'live_url' => null,
                'live_unavailable' => true,
                'featured' => true,
                'published' => true,
                'sort_order' => 1,
            ],
            [
                'title' => 'BondaCare',
                'slug' => 'bondacare',
                'category' => 'Web Application',
                'short_description' => 'Digital maternal health platform for records, appointments and postpartum care.',
                'description' => 'A digital maternal health platform designed to modernize pregnancy records, appointments, reminders and postpartum care.',
                'role' => 'Full Stack Developer',
                'technologies' => ['Laravel', 'PHP', 'MySQL', 'JavaScript', 'Tailwind CSS'],
                'problem' => 'Pregnancy records and postpartum follow-up are still often paper-based or split across clinics. Families lose continuity, and care teams spend time reconstructing history instead of acting on it.',
                'solution' => 'BondaCare centralizes maternal records, appointments, and reminders in a single web application so patients and care teams share the same timeline from pregnancy through postpartum.',
                'features' => [
                    'Structured pregnancy and postpartum records',
                    'Appointment scheduling and reminders',
                    'Care timeline across trimesters',
                    'Role-based access for patients and staff',
                ],
                'technical_details' => 'Built with Laravel, Blade, and MySQL. The domain model treats records, appointments, and reminders as first-class entities with validation at the request layer. The interface is intentionally calm — health software should be readable under stress.',
                'challenges' => 'Health data needs careful validation and access control. Reminder logic had to be reliable without becoming noisy, and the UI had to stay simple for non-technical users.',
                'outcome' => 'A focused maternal-care workflow that replaces fragmented notes with a single, structured record.',
                'hero_image' => 'assets/projects/bondacare/landingpage.png',
                'gallery' => [],
                'video_url' => null,
                'github_url' => null,
                'live_url' => null,
                'live_unavailable' => true,
                'featured' => true,
                'published' => true,
                'sort_order' => 2,
            ],
            [
                'title' => 'FixEase',
                'slug' => 'fixease',
                'category' => 'Mobile / React Native',
                'short_description' => 'React Native app that makes booking and tracking home repairs straightforward.',
                'description' => 'A React Native mobile application for requesting, scheduling and tracking home repair services from a single place.',
                'role' => 'Mobile Developer',
                'technologies' => ['React Native', 'TypeScript', 'REST API', 'JavaScript'],
                'problem' => 'Booking a repair still means chasing quotes across chats, calls, and walk-ins. Customers lose track of who is coming, when, and what it will cost. Technicians spend time on coordination instead of the job.',
                'solution' => 'FixEase puts the whole repair flow in one mobile app: request a job, get a schedule, follow the technician, and close the work without leaving the phone.',
                'features' => [
                    'Repair requests with photos and job details',
                    'Scheduling and status tracking from booked to done',
                    'Technician profiles and job history',
                    'In-app updates so customers are not left waiting',
                ],
                'technical_details' => 'The client is React Native with TypeScript. Screens stay focused on the job lifecycle — request, schedule, track, complete — on top of a REST API. Native navigation and forms are built for one-handed use on site, not a shrunk-down website.',
                'challenges' => 'The hard parts were keeping job status consistent across customer and technician views, and making the booking flow fast enough that someone with a leaking pipe would actually finish it. The mobile UI had to stay readable with photos, dates, and status without feeling crowded.',
                'outcome' => 'A focused React Native product for home repairs — one request, one timeline, one place to check what happens next.',
                'hero_image' => 'assets/projects/fixease/landingpage.jpeg',
                'gallery' => [],
                'video_url' => null,
                'github_url' => null,
                'live_url' => null,
                'live_unavailable' => false,
                'featured' => true,
                'published' => true,
                'sort_order' => 3,
            ],
            [
                'title' => 'Bake by Mel',
                'slug' => 'bake-by-mel',
                'category' => 'Business Platform',
                'short_description' => 'Digital platform for a bakery to showcase products and run campaigns.',
                'description' => 'A digital platform for a bakery business to showcase products, manage campaigns and support online operations.',
                'role' => 'Full Stack Developer',
                'technologies' => ['Laravel', 'PHP', 'MySQL', 'JavaScript'],
                'problem' => 'A small bakery needed a digital presence that could show products and run campaigns without depending on a patchwork of social posts and spreadsheets.',
                'solution' => 'Bake by Mel is a Laravel business platform: product catalogue, campaign surfaces, and operational pages that a non-technical owner can actually use.',
                'features' => [
                    'Product catalogue and campaign pages',
                    'Simple content updates for the business owner',
                    'Enquiry and operations-ready contact flows',
                    'Clean storefront that works on mobile',
                ],
                'technical_details' => 'Laravel and MySQL hold products and campaigns. The frontend stays light — Blade and JavaScript — so the owner is not locked into a heavy CMS. Pages are structured for scanning, not decoration.',
                'challenges' => 'The product had to look premium without becoming fragile. Admin flows needed to be obvious; a bakery should not need a developer to update a campaign.',
                'outcome' => 'A durable digital storefront that supports day-to-day bakery operations instead of sitting unused.',
                'hero_image' => 'assets/projects/bake-by-mel/puzzle.png',
                'gallery' => [],
                'video_url' => null,
                'github_url' => null,
                'live_url' => null,
                'live_unavailable' => true,
                'featured' => true,
                'published' => true,
                'sort_order' => 4,
            ],
        ];

        foreach ($projects as $project) {
            Project::query()->updateOrCreate(
                ['slug' => $project['slug']],
                $project,
            );
        }

        Project::query()->where('slug', 'nadidakwah')->delete();
    }

    protected function seedExperiences(): void
    {
        $experiences = [
            [
                'company' => 'CiptaKod',
                'role' => 'Freelance Web & Mobile Developer',
                'start_date' => '2026-07-01',
                'end_date' => null,
                'description' => 'Building websites, web applications and mobile products for clients under CiptaKod.',
                'technologies' => ['Laravel', 'React', 'React Native', 'PHP', 'JavaScript', 'MySQL'],
                'sort_order' => 1,
            ],
            [
                'company' => 'Xsolla KL',
                'role' => 'Software Engineer Intern',
                'start_date' => '2026-03-01',
                'end_date' => '2026-08-01',
                'description' => 'Worked on software engineering projects involving APIs, frontend development, backend systems, databases, Docker and cloud-oriented development.',
                'technologies' => ['React', 'Go', 'PostgreSQL', 'Docker', 'GitLab', 'APIs'],
                'sort_order' => 2,
            ],
            [
                'company' => 'Self Employed',
                'role' => 'Freelance Photographer',
                'start_date' => '2023-10-01',
                'end_date' => null,
                'description' => 'Independent photography for clients, covering portraits, events and visual work.',
                'technologies' => [],
                'sort_order' => 3,
            ],
            [
                'company' => 'Telekom Malaysia Berhad Seremban',
                'role' => 'Intern',
                'start_date' => '2023-03-01',
                'end_date' => '2023-08-01',
                'description' => 'Supported engineering work during an internship, contributing to internal tools and day-to-day software tasks.',
                'technologies' => [],
                'sort_order' => 4,
            ],
        ];

        Experience::query()->delete();

        foreach ($experiences as $experience) {
            Experience::query()->create($experience);
        }
    }

    protected function seedSkills(): void
    {
        $groups = [
            'Frontend' => ['React', 'React Native', 'JavaScript', 'TypeScript', 'Tailwind CSS', 'HTML', 'CSS'],
            'Backend' => ['Laravel', 'PHP', 'Golang', 'REST APIs'],
            'Database' => ['MySQL', 'PostgreSQL'],
            'Tools' => ['Git', 'GitLab', 'Docker', 'VS Code', 'Cursor'],
        ];

        Skill::query()->delete();

        $order = 1;
        foreach ($groups as $category => $names) {
            foreach ($names as $name) {
                Skill::query()->create([
                    'name' => $name,
                    'category' => $category,
                    'sort_order' => $order++,
                ]);
            }
        }
    }

    protected function seedEducation(): void
    {
        $records = [
            [
                'institution' => 'Universiti Malaysia Pahang Al-Sultan Abdullah',
                'degree' => 'Bachelor of Computer Science (Software Engineering)',
                'field' => 'Faculty of Computing',
                'start_year' => 2023,
                'end_year' => 2026,
                'description' => null,
            ],
            [
                'institution' => 'Universiti Malaysia Pahang Al-Sultan Abdullah',
                'degree' => 'Diploma in Computer Science',
                'field' => 'Faculty of Computing',
                'start_year' => 2021,
                'end_year' => 2023,
                'description' => null,
            ],
        ];

        foreach ($records as $record) {
            Education::query()->updateOrCreate(
                [
                    'institution' => $record['institution'],
                    'degree' => $record['degree'],
                ],
                $record,
            );
        }
    }
}
