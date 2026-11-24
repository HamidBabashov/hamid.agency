<?php

namespace Tests\Feature;

use App\Models\ContactMessage;
use App\Models\DomainExtension;
use App\Models\HostingPlan;
use App\Models\Order;
use App\Models\Project;
use App\Models\ProjectCategory;
use App\Models\Service;
use App\Models\Testimonial;
use App\Models\User;
use Database\Seeders\AdminSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminPanelTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seedContent();
    }

    public function test_guests_are_redirected_to_login(): void
    {
        $this->get('/admin')->assertRedirect('/admin/login');
        $this->get('/admin/orders')->assertRedirect('/admin/login');
        $this->get('/admin/login')->assertOk()->assertSee(__('admin.auth.title'));
    }

    public function test_non_admin_users_are_forbidden(): void
    {
        $this->actingAs(User::factory()->create(['is_admin' => false]))->get('/admin')->assertForbidden();
    }

    public function test_admin_can_log_in_and_out(): void
    {
        User::factory()->create(['email' => 'boss@example.com', 'password' => Hash::make('secret-password'), 'is_admin' => true]);

        $this->post('/admin/login', ['email' => 'boss@example.com', 'password' => 'wrong'])->assertSessionHasErrors('email');
        $this->post('/admin/login', ['email' => 'boss@example.com', 'password' => 'secret-password'])->assertRedirect('/admin');
        $this->assertAuthenticated();

        $this->post('/admin/logout')->assertRedirect('/admin/login');
        $this->assertGuest();
    }

    public function test_non_admin_cannot_log_in(): void
    {
        User::factory()->create(['email' => 'user@example.com', 'password' => Hash::make('secret-password'), 'is_admin' => false]);

        $this->post('/admin/login', ['email' => 'user@example.com', 'password' => 'secret-password'])->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_login_is_rate_limited(): void
    {
        for ($i = 0; $i < 5; $i++) {
            $this->post('/admin/login', ['email' => 'x@example.com', 'password' => 'wrong']);
        }
        $this->post('/admin/login', ['email' => 'x@example.com', 'password' => 'wrong'])->assertStatus(429);
    }

    public function test_admin_seeder_reads_credentials_from_env(): void
    {
        putenv('ADMIN_EMAIL=seed@example.com');
        putenv('ADMIN_PASSWORD=very-secure-password');
        $_ENV['ADMIN_EMAIL'] = 'seed@example.com';
        $_ENV['ADMIN_PASSWORD'] = $_SERVER['ADMIN_PASSWORD'] = 'very-secure-password';
        $_SERVER['ADMIN_EMAIL'] = 'seed@example.com';

        $this->seed(AdminSeeder::class);

        $user = User::where('email', 'seed@example.com')->sole();
        $this->assertTrue($user->is_admin);
        $this->assertTrue(Hash::check('very-secure-password', $user->password));
    }

    public function test_all_admin_pages_render(): void
    {
        $order = Order::create(['service_type' => 'domain', 'name' => 'A', 'email' => 'a@example.com', 'phone' => '+994501112233', 'details' => ['domain_name' => 'a.az', 'domain_action' => 'register', 'domain_years' => 1]]);
        $message = ContactMessage::create(['name' => 'B', 'email' => 'b@example.com', 'message' => 'Salam dünya, test mesajı']);
        $project = Project::create(['title_az' => 'Layihə', 'title_en' => 'Project', 'project_category_id' => ProjectCategory::first()->id]);
        $testimonial = Testimonial::create(['author_name' => 'C', 'content_az' => 'Rəy', 'content_en' => 'Review']);

        $this->actingAs($this->admin());

        $urls = [
            '/admin',
            '/admin/services', '/admin/services/create', '/admin/services/'.Service::first()->id.'/edit',
            '/admin/hosting-plans', '/admin/hosting-plans/create', '/admin/hosting-plans/'.HostingPlan::first()->id.'/edit',
            '/admin/domain-extensions', '/admin/domain-extensions/create', '/admin/domain-extensions/'.DomainExtension::first()->id.'/edit',
            '/admin/project-categories', '/admin/project-categories/create', '/admin/project-categories/'.ProjectCategory::first()->id.'/edit',
            '/admin/projects', '/admin/projects/create', '/admin/projects/'.$project->id.'/edit',
            '/admin/testimonials', '/admin/testimonials/create', '/admin/testimonials/'.$testimonial->id.'/edit',
            '/admin/orders', '/admin/orders?status=new&service=domain&q=A', '/admin/orders/'.$order->id,
            '/admin/messages', '/admin/messages?status=new', '/admin/messages/'.$message->id,
        ];

        foreach ($urls as $url) {
            $this->get($url)->assertOk();
        }
    }

    public function test_admin_can_manage_hosting_plans(): void
    {
        $this->actingAs($this->admin());

        $this->post('/admin/hosting-plans', [
            'name_az' => 'Mega', 'name_en' => 'Mega', 'price_monthly' => '30', 'price_yearly' => '300',
            'features_az' => "A\nB", 'features_en' => "A\nB", 'is_active' => '1', 'is_featured' => '0',
        ])->assertRedirect('/admin/hosting-plans');

        $plan = HostingPlan::where('name_en', 'Mega')->sole();
        $this->assertTrue($plan->is_active);
        $this->assertSame(17, $plan->yearlySavingPercent());

        $this->get('/en/hosting')->assertSee('Mega');

        $this->put('/admin/hosting-plans/'.$plan->id, ['name_az' => 'Mega', 'name_en' => 'Mega', 'price_monthly' => '30', 'price_yearly' => '300', 'is_active' => '0'])
            ->assertRedirect();
        $this->get('/en/hosting')->assertDontSee('Mega');

        $this->delete('/admin/hosting-plans/'.$plan->id)->assertRedirect();
        $this->assertModelMissing($plan);
    }

    public function test_translatable_fields_are_required_in_both_languages(): void
    {
        $this->actingAs($this->admin());

        $this->post('/admin/hosting-plans', ['name_az' => 'Yalnız AZ', 'price_monthly' => 1, 'price_yearly' => 10])
            ->assertSessionHasErrors('name_en');
    }

    public function test_domain_extension_is_normalized(): void
    {
        $this->actingAs($this->admin());

        $this->post('/admin/domain-extensions', ['extension' => ' .EDU.AZ ', 'register_price' => 50, 'renew_price' => 50, 'is_active' => 1])
            ->assertRedirect();

        $this->assertDatabaseHas('domain_extensions', ['extension' => 'edu.az']);
        $this->post('/admin/domain-extensions', ['extension' => 'az', 'register_price' => 1, 'renew_price' => 1])->assertSessionHasErrors('extension');
    }

    public function test_project_image_is_converted_to_resized_webp(): void
    {
        $this->actingAs($this->admin());

        $this->post('/admin/projects', [
            'title_az' => 'Sayt', 'title_en' => 'Website', 'is_active' => 1, 'is_featured' => 1,
            'project_category_id' => ProjectCategory::first()->id,
            'image' => UploadedFile::fake()->image('shot.jpg', 2400, 1200),
        ])->assertRedirect('/admin/projects');

        $project = Project::sole();
        $path = public_path('uploads/'.$project->image);

        try {
            $this->assertStringEndsWith('.webp', $project->image);
            $this->assertFileExists($path);
            [$width, $height, $type] = getimagesize($path);
            $this->assertSame(IMAGETYPE_WEBP, $type);
            $this->assertSame(1200, $width);
            $this->assertSame(600, $height);

            // Ana səhifədə və portfolioda görünür, lazy-load ilə
            $this->get('/az')->assertSee('Sayt')->assertSee('loading="lazy"', false);
            $this->get('/en/portfolio')->assertSee('Website');

            $this->delete('/admin/projects/'.$project->id);
            $this->assertFileDoesNotExist($path);
        } finally {
            File::delete($path);
        }
    }

    public function test_testimonials_appear_on_home_page_only_when_added(): void
    {
        $this->get('/az')->assertDontSee(__('home.testimonials.title', [], 'az'));

        $this->actingAs($this->admin())->post('/admin/testimonials', [
            'author_name' => 'Real Müştəri', 'rating' => 5, 'content_az' => 'Əla iş', 'content_en' => 'Great work', 'is_active' => 1,
        ])->assertRedirect();

        $this->get('/az')->assertSee(__('home.testimonials.title', [], 'az'))->assertSee('Əla iş');
        $this->get('/en')->assertSee('Great work');
    }

    public function test_order_status_can_be_updated(): void
    {
        $order = Order::create(['service_type' => 'hosting', 'name' => 'A', 'email' => 'a@example.com']);
        $this->actingAs($this->admin());

        $this->put('/admin/orders/'.$order->id, ['status' => 'contacted', 'admin_note' => 'Zəng edildi'])->assertRedirect();
        $this->assertSame('contacted', $order->fresh()->status);

        $this->put('/admin/orders/'.$order->id, ['status' => 'invalid'])->assertSessionHasErrors('status');

        $this->put('/admin/orders/'.$order->id, ['status' => 'completed'])->assertRedirect();
        $this->assertSame('completed', $order->fresh()->status);
    }

    public function test_message_status_can_be_updated(): void
    {
        $message = ContactMessage::create(['name' => 'B', 'email' => 'b@example.com', 'message' => 'Salam']);
        $this->actingAs($this->admin());

        $this->put('/admin/messages/'.$message->id, ['status' => 'completed'])->assertRedirect();
        $this->assertSame('completed', $message->fresh()->status);
    }

    public function test_service_slug_change_updates_public_url(): void
    {
        $service = Service::where('key', 'web_design')->sole();
        $this->actingAs($this->admin());

        $data = $service->only(['key', 'slug_en', 'icon', 'title_az', 'title_en', 'excerpt_az', 'excerpt_en', 'body_az', 'body_en', 'features_az', 'features_en']);
        $this->put('/admin/services/'.$service->id, ['slug_az' => 'sayt-hazirlanmasi', 'is_active' => 1] + $data)->assertRedirect();

        $this->get('/az/xidmetler/sayt-hazirlanmasi')->assertOk();
        $this->get('/az/xidmetler/veb-dizayn')->assertNotFound();
    }
}
