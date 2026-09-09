<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\SiteCopy;
use App\Models\User;
use Database\Seeders\PortfolioSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PortfolioTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(PortfolioSeeder::class);
    }

    public function test_home_page_presents_the_engineer_profile(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('MIRZA')
            ->assertSee('Software Engineer')
            ->assertSee('I build products with a pulse')
            ->assertSee('Formerly Dee')
            ->assertSee('Hello!')
            ->assertSee('Xcellorate')
            ->assertSee('assets/projects/xcellorate/dashboard.png')
            ->assertSee('BondaCare')
            ->assertSee('FixEase')
            ->assertDontSee('NadiDakwah')
            ->assertSee('CiptaKod')
            ->assertSee('Xsolla')
            ->assertSee('⌘K')
            ->assertSee('poke me')
            ->assertSee('Minimize character')
            ->assertSee('Pause slideshow')
            ->assertSee('Bake by Mel');
    }

    public function test_project_pages_resolve_by_slug(): void
    {
        $this->get('/projects')->assertOk()->assertSee('Proof, not a pitch deck.', false)->assertSee('is-phone');
        $this->get('/projects/xcellorate')
            ->assertOk()
            ->assertSee('Xcellorate')
            ->assertSee('The problem')
            ->assertSee('Next project');
        $this->get('/projects/fixease')
            ->assertOk()
            ->assertSee('FixEase')
            ->assertSee('React Native')
            ->assertSee('max-w-[18rem]');
        $this->get('/projects/bake-by-mel')
            ->assertOk()
            ->assertSee('Bake by Mel')
            ->assertSee('max-w-[18rem]');
        $this->get('/projects/nadidakwah')->assertNotFound();
    }

    public function test_portrait_screenshots_use_the_compact_phone_frame(): void
    {
        $bake = Project::query()->where('slug', 'bake-by-mel')->first();
        $wide = Project::query()->where('slug', 'xcellorate')->first();

        $this->assertNotNull($bake);
        $this->assertNotNull($wide);
        $this->assertTrue($bake->isCompactPreview());
        $this->assertTrue($bake->previewIsPortrait());
        $this->assertFalse($wide->isCompactPreview());
        $this->assertFalse($wide->previewIsPortrait());
    }

    public function test_about_and_contact_pages_are_available(): void
    {
        $this->get('/about')
            ->assertOk()
            ->assertSee('Formerly Dee. Still shipping.')
            ->assertSee('Diploma in Computer Science')
            ->assertSee('Universiti Malaysia Pahang')
            ->assertSee('CiptaKod');
        $this->get('/contact')->assertOk()->assertSee('PING ME.', false);
    }

    public function test_contact_form_stores_a_valid_message(): void
    {
        $this->post('/contact', [
            'name' => 'Recruiter',
            'email' => 'recruiter@example.com',
            'subject' => 'Role inquiry',
            'message' => 'Would you be open to a conversation about a software engineering role?',
            'company_website' => '',
        ])->assertRedirect();

        $this->assertDatabaseHas('contact_messages', [
            'email' => 'recruiter@example.com',
            'subject' => 'Role inquiry',
        ]);
    }

    public function test_contact_form_honeypot_is_quietly_ignored(): void
    {
        $this->post('/contact', [
            'name' => 'Bot',
            'email' => 'bot@example.com',
            'subject' => 'Spam title here',
            'message' => 'This is a sufficiently long spam message.',
            'company_website' => 'https://spam.test',
        ])->assertRedirect();

        $this->assertDatabaseCount('contact_messages', 0);
    }

    public function test_unpublished_projects_are_hidden(): void
    {
        Project::query()->where('slug', 'xcellorate')->update(['published' => false]);

        $this->get('/projects/xcellorate')->assertNotFound();
        $this->get('/')->assertDontSee('Xcellorate');
    }

    public function test_admin_requires_authentication(): void
    {
        $this->get('/admin')->assertRedirect('/login');
    }

    public function test_admin_can_sign_in_and_open_the_dashboard(): void
    {
        $user = User::query()->first();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ])->assertRedirect(route('admin.dashboard'));

        $this->actingAs($user)
            ->get('/admin')
            ->assertOk()
            ->assertSee('Build log');
    }

    public function test_admin_can_create_a_project(): void
    {
        $user = User::query()->first();

        $this->actingAs($user)->post('/admin/projects', [
            'title' => 'Studio OS',
            'slug' => 'studio-os',
            'category' => 'Platform',
            'short_description' => 'A compact operating layer for studio tools.',
            'description' => 'A compact operating layer for studio tools and internal workflows.',
            'role' => 'Full Stack Developer',
            'technologies' => "Laravel\nPHP",
            'sort_order' => 9,
            'published' => '1',
            'featured' => '1',
        ])->assertRedirect(route('admin.projects.index'));

        $this->assertDatabaseHas('projects', ['slug' => 'studio-os']);
    }

    public function test_admin_can_edit_introduction_about_and_currently(): void
    {
        $user = User::query()->first();

        $this->actingAs($user)
            ->put(route('admin.pages.update'), [
                'first_name' => 'Mirza',
                'last_name' => 'Rusyaidi',
                'title' => 'Product Engineer',
                'headline' => 'HELLO FROM THE CMS',
                'tagline' => 'Edited tagline for the homepage.',
                'location' => 'Kuala Lumpur',
                'meta_degree' => 'BCS',
                'meta_discipline' => 'Software Engineering',
                'about_heading' => 'Always shipping.',
                'about_body' => "I'm **Mirza**.\n\nI build things.",
                'currently_heading' => 'Right now.',
                'currently_body' => 'Open to roles that ship.',
                'availability' => 'Available for work',
                'focus' => "Laravel\nGo",
            ])
            ->assertRedirect(route('admin.pages.edit'));

        $this->get('/')
            ->assertOk()
            ->assertSee('HELLO FROM THE CMS')
            ->assertSee('Product Engineer')
            ->assertSee('Always shipping.')
            ->assertSee('Right now.')
            ->assertSee('Open to roles that ship.');

        $this->get('/about')
            ->assertOk()
            ->assertSee('Always shipping.')
            ->assertSee('I build things.');
    }

    public function test_admin_can_upload_and_replace_the_resume(): void
    {
        Storage::fake('local');
        $user = User::query()->first();

        $this->actingAs($user)
            ->put(route('admin.pages.update'), [
                'first_name' => 'Mirza',
                'last_name' => 'Rusyaidi',
                'resume' => UploadedFile::fake()->create('cv.pdf', 120, 'application/pdf'),
            ])
            ->assertRedirect(route('admin.pages.edit'));

        Storage::disk('local')->assertExists('resumes/resume.pdf');
        $this->assertDatabaseHas('site_copies', ['resume_path' => 'resumes/resume.pdf']);

        $this->get('/')
            ->assertOk()
            ->assertSee('Download resume')
            ->assertSee(route('resume'), false);

        $this->get(route('resume'))
            ->assertOk()
            ->assertDownload('mirza-rusyaidi-resume.pdf');
    }

    public function test_admin_can_remove_the_resume(): void
    {
        Storage::fake('local');
        $user = User::query()->first();
        $copy = SiteCopy::current();
        Storage::disk('local')->put('resumes/resume.pdf', 'pdf');
        $copy->update(['resume_path' => 'resumes/resume.pdf']);

        $this->actingAs($user)
            ->put(route('admin.pages.update'), [
                'first_name' => 'Mirza',
                'last_name' => 'Rusyaidi',
                'remove_resume' => '1',
            ])
            ->assertRedirect(route('admin.pages.edit'));

        Storage::disk('local')->assertMissing('resumes/resume.pdf');
        $this->assertDatabaseHas('site_copies', ['resume_path' => null]);

        $this->get('/')->assertOk()->assertDontSee('Download resume');
        $this->get(route('resume'))->assertNotFound();
    }

    public function test_empty_project_fields_are_hidden_on_the_public_page(): void
    {
        $user = User::query()->first();

        $this->actingAs($user)->post('/admin/projects', [
            'title' => 'Quiet App',
            'sort_order' => 20,
            'published' => '1',
        ])->assertRedirect(route('admin.projects.index'));

        $this->get('/projects/quiet-app')
            ->assertOk()
            ->assertSee('Quiet App')
            ->assertDontSee('The problem')
            ->assertDontSee('The approach')
            ->assertDontSee('Key features')
            ->assertDontSee('Architecture')
            ->assertDontSee('Technical details')
            ->assertDontSee('Challenges')
            ->assertDontSee('Overview')
            ->assertDontSee('Live site')
            ->assertDontSee('Walkthrough')
            ->assertDontSee('Screens');
    }

    public function test_project_gallery_and_video_render_on_the_public_page(): void
    {
        $user = User::query()->first();

        $this->actingAs($user)->post('/admin/projects', [
            'title' => 'Repair App',
            'sort_order' => 21,
            'published' => '1',
            'gallery' => "assets/projects/repair-app/home.png\nassets/projects/repair-app/job.png",
            'video_url' => 'https://youtu.be/dQw4w9WgXcQ',
        ])->assertRedirect(route('admin.projects.index'));

        $this->assertDatabaseHas('projects', ['slug' => 'repair-app']);

        $this->get('/projects/repair-app')
            ->assertOk()
            ->assertSee('Screens')
            ->assertSee('Walkthrough')
            ->assertSee('assets/projects/repair-app/home.png')
            ->assertSee('assets/projects/repair-app/job.png')
            ->assertSee('https://www.youtube-nocookie.com/embed/dQw4w9WgXcQ', false);
    }

    public function test_project_features_keep_pasted_sentences(): void
    {
        $user = User::query()->first();

        $this->actingAs($user)->post('/admin/projects', [
            'title' => 'Sentence App',
            'sort_order' => 22,
            'published' => '1',
            'description' => 'A full-width overview of the product and why it exists.',
            'technologies' => "Laravel\nPostgreSQL",
            'features' => "* **Daily Check-In** — Streaks, spins, and rewards.\nMentors can review attempts without touching the database.",
        ])->assertRedirect(route('admin.projects.index'));

        $this->get('/projects/sentence-app')
            ->assertOk()
            ->assertSee('Daily Check-In')
            ->assertSee('Streaks, spins, and rewards.', false)
            ->assertSee('Mentors can review attempts without touching the database.', false)
            ->assertSee('A full-width overview of the product and why it exists.')
            ->assertSee('Overview')
            ->assertSee('Stack');
    }
}
