<?php

namespace Tests\Feature;

use App\Mail\NewOrder;
use App\Mail\OrderReceived;
use App\Mail\OrderStatusChanged;
use App\Models\Order;
use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class CustomerAccountTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seedContent();
        Mail::fake();
        config(['agency.notify_email' => 'owner@example.com']);
    }

    private function registration(array $overrides = []): array
    {
        return array_merge([
            'name' => 'Aysel Məmmədova',
            'email' => 'Aysel@Example.com',
            'phone' => '+994501234567',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
            'terms' => '1',
        ], $this->spamFields(), $overrides);
    }

    public function test_auth_pages_render_in_both_languages(): void
    {
        foreach (['az', 'en'] as $locale) {
            foreach (['login', 'register', 'password.request'] as $route) {
                $this->get(lroute($route, [], $locale))->assertOk()->assertSee('noindex', false);
            }
        }
        $this->get('/az/sifreni-yenile/token123?email=a@example.com')->assertOk();
    }

    public function test_customer_can_register(): void
    {
        $this->post('/az/qeydiyyat', $this->registration())
            ->assertRedirect(lroute('account', [], 'az'))
            ->assertSessionHas('success');

        $user = User::sole();
        $this->assertAuthenticatedAs($user);
        $this->assertSame('aysel@example.com', $user->email);
        $this->assertSame('az', $user->locale);
        $this->assertFalse($user->is_admin);
    }

    public function test_registration_validation(): void
    {
        User::factory()->create(['email' => 'taken@example.com']);

        $this->post('/en/register', $this->registration(['email' => 'taken@example.com', 'password_confirmation' => 'other', 'terms' => null]))
            ->assertSessionHasErrors(['email', 'password', 'terms']);

        $this->post('/en/register', $this->registration(['password' => 'short', 'password_confirmation' => 'short']))
            ->assertSessionHasErrors('password');

        $this->assertGuest();
    }

    public function test_registration_is_protected_from_bots(): void
    {
        $this->post('/az/qeydiyyat', $this->registration(['website' => 'spam']))->assertSessionHasErrors('spam');
        $this->assertSame(0, User::count());
    }

    public function test_customer_can_log_in_and_out(): void
    {
        $user = User::factory()->create(['email' => 'c@example.com', 'password' => Hash::make('secret123'), 'locale' => 'az']);

        $this->post('/en/login', ['email' => 'c@example.com', 'password' => 'wrong'])->assertSessionHasErrors('email');
        $this->post('/en/login', ['email' => 'C@example.com', 'password' => 'secret123'])->assertRedirect(lroute('account', [], 'en'));
        $this->assertAuthenticatedAs($user);
        $this->assertSame('en', $user->fresh()->locale);

        $this->post('/en/logout')->assertRedirect(lroute('home', [], 'en'));
        $this->assertGuest();
    }

    public function test_login_redirect_param_is_safe(): void
    {
        User::factory()->create(['email' => 'c@example.com', 'password' => Hash::make('secret123')]);

        $this->post('/az/giris', ['email' => 'c@example.com', 'password' => 'secret123', 'redirect' => '/az/qiymet-teklifi?service=hosting'])
            ->assertRedirect('/az/qiymet-teklifi?service=hosting');

        $this->post('/az/cixis');
        $this->post('/az/giris', ['email' => 'c@example.com', 'password' => 'secret123', 'redirect' => '//evil.example.com'])
            ->assertRedirect(lroute('account', [], 'az'));
    }

    public function test_account_pages_require_login(): void
    {
        $this->get('/az/hesabim')->assertRedirect(lroute('login', [], 'az'));
        $this->get('/en/account/profile')->assertRedirect(lroute('login', [], 'en'));
    }

    public function test_logged_in_users_are_redirected_away_from_guest_pages(): void
    {
        $this->actingAs(User::factory()->create());
        $this->get('/en/login')->assertRedirect(lroute('account', [], 'en'));
    }

    public function test_logged_in_order_is_attached_to_account_and_customer_is_emailed(): void
    {
        $user = User::factory()->create(['name' => 'Müştəri', 'email' => 'client@example.com', 'phone' => '+994551112233', 'locale' => 'en']);
        $this->actingAs($user);

        $this->get('/en/get-a-quote')->assertOk()->assertSee('value="client@example.com"', false)->assertSee('value="+994551112233"', false);

        $response = $this->post('/en/get-a-quote', [
            'service_type' => 'mobile_app', 'app_platform' => 'ios',
            'name' => $user->name, 'email' => $user->email, 'phone' => $user->phone,
        ] + $this->spamFields());

        $order = Order::sole();
        $response->assertRedirect(lroute('account.orders.show', ['order' => $order->id], 'en'));
        $this->assertSame($user->id, $order->user_id);

        Mail::assertSent(NewOrder::class);
        Mail::assertSent(OrderReceived::class, fn ($mail) => $mail->hasTo('client@example.com') && $mail->locale === 'en');

        $this->get(lroute('account', [], 'en'))->assertOk()->assertSee(__('site.service_types.mobile_app', [], 'en'));
        $this->get(lroute('account.orders.show', ['order' => $order->id], 'en'))->assertOk()
            ->assertSee(__('account.statuses.new', [], 'en'))
            ->assertSee(__('quote.options.app_platform.ios', [], 'en'));
    }

    public function test_guest_order_still_works_and_shows_account_prompt(): void
    {
        $this->get('/az/qiymet-teklifi')->assertSee(__('account.quote.guest_title', [], 'az'));

        $this->post('/az/qiymet-teklifi', [
            'service_type' => 'web_design', 'web_type' => 'landing',
            'name' => 'Qonaq', 'email' => 'guest@example.com', 'phone' => '+994501234567',
        ] + $this->spamFields())->assertRedirect(lroute('quote', [], 'az'));

        $this->assertNull(Order::sole()->user_id);
        Mail::assertSent(OrderReceived::class, fn ($mail) => $mail->hasTo('guest@example.com'));
    }

    public function test_customers_cannot_see_other_customers_orders(): void
    {
        $owner = User::factory()->create();
        $order = Order::create(['user_id' => $owner->id, 'service_type' => 'domain', 'name' => 'A', 'email' => 'a@example.com']);

        $this->actingAs(User::factory()->create())
            ->get(lroute('account.orders.show', ['order' => $order->id], 'az'))
            ->assertNotFound();
    }

    public function test_customer_cannot_access_admin_panel(): void
    {
        $this->actingAs(User::factory()->create())->get('/admin')->assertForbidden();
    }

    public function test_profile_and_password_can_be_updated(): void
    {
        $user = User::factory()->create(['password' => Hash::make('secret123'), 'locale' => 'az']);
        $this->actingAs($user);

        $this->get('/az/hesabim/profil')->assertOk();

        $this->put('/az/hesabim/profil', ['name' => 'Yeni Ad', 'email' => 'new@example.com', 'phone' => '', 'company' => 'MMC', 'locale' => 'en'])
            ->assertRedirect(lroute('account.profile', [], 'en'));
        $this->assertSame('Yeni Ad', $user->fresh()->name);
        $this->assertSame('en', $user->fresh()->locale);

        $this->put('/en/account/password', ['current_password' => 'wrong', 'password' => 'newpass123', 'password_confirmation' => 'newpass123'])
            ->assertSessionHasErrors('current_password');
        $this->put('/en/account/password', ['current_password' => 'secret123', 'password' => 'newpass123', 'password_confirmation' => 'newpass123'])
            ->assertSessionHas('success');
        $this->assertTrue(Hash::check('newpass123', $user->fresh()->password));
    }

    public function test_password_reset_flow(): void
    {
        Notification::fake();
        $user = User::factory()->create(['email' => 'reset@example.com', 'locale' => 'en']);

        // Mövcud olmayan email üçün də eyni cavab
        $this->post('/en/forgot-password', ['email' => 'nobody@example.com'])->assertSessionHas('success');
        $this->post('/en/forgot-password', ['email' => 'reset@example.com'])->assertSessionHas('success');

        $token = null;
        Notification::assertSentTo($user, ResetPassword::class, function ($notification) use ($user, &$token) {
            $token = $notification->token;
            $mail = $notification->toMail($user);
            $this->assertStringContainsString('/en/reset-password/'.$token, $mail->actionUrl);

            return true;
        });

        $this->post('/en/reset-password', ['token' => $token, 'email' => 'reset@example.com', 'password' => 'brandnew123', 'password_confirmation' => 'brandnew123'])
            ->assertRedirect(lroute('account', [], 'en'));
        $this->assertTrue(Hash::check('brandnew123', $user->fresh()->password));
        $this->assertAuthenticatedAs($user);
    }

    public function test_admin_status_change_notifies_customer_in_their_language(): void
    {
        $order = Order::create(['service_type' => 'hosting', 'name' => 'A', 'email' => 'a@example.com', 'locale' => 'en']);
        $this->actingAs($this->admin());

        $this->put('/admin/orders/'.$order->id, ['status' => 'contacted', 'notify_customer' => '1'])->assertRedirect();
        Mail::assertSent(OrderStatusChanged::class, fn ($mail) => $mail->hasTo('a@example.com') && $mail->locale === 'en');

        // Status dəyişməyibsə və ya bildiriş söndürülübsə — email yoxdur
        $this->put('/admin/orders/'.$order->id, ['status' => 'contacted', 'notify_customer' => '1']);
        $this->put('/admin/orders/'.$order->id, ['status' => 'completed', 'notify_customer' => '0']);
        Mail::assertSent(OrderStatusChanged::class, 1);
    }

    public function test_admin_can_see_customers(): void
    {
        $customer = User::factory()->create(['name' => 'Müştəri Bir']);
        Order::create(['user_id' => $customer->id, 'service_type' => 'domain', 'name' => 'A', 'email' => 'a@example.com']);
        $admin = $this->admin();

        $this->actingAs($admin)->get('/admin/customers')->assertOk()->assertSee('Müştəri Bir')->assertViewHas('items', fn ($items) => $items->total() === 1);
        $this->get('/admin/customers/'.$customer->id)->assertOk();
        $this->get('/admin/customers/'.$admin->id)->assertNotFound();
        $this->get('/admin/orders/'.Order::first()->id)->assertOk()->assertSee('Müştəri Bir');
    }

    public function test_customer_emails_render(): void
    {
        $order = Order::create(['user_id' => User::factory()->create()->id, 'service_type' => 'domain', 'name' => 'A', 'email' => 'a@example.com', 'locale' => 'az',
            'details' => ['domain_name' => 'a.az', 'domain_action' => 'register', 'domain_years' => 1]]);

        $html = (new OrderReceived($order))->locale('az')->render();
        $this->assertStringContainsString(__('account.mail.view_order', [], 'az'), $html);
        $this->assertStringContainsString('/az/hesabim/sifarisler/'.$order->id, $html);
        $this->assertStringContainsString('Bütün hüquqlar qorunur.', $html);

        $order->status = 'completed';
        $html = (new OrderStatusChanged($order))->locale('en')->render();
        $this->assertStringContainsString(__('account.statuses.completed', [], 'en'), $html);
    }
}
