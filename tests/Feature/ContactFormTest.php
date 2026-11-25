<?php

namespace Tests\Feature;

use App\Http\Middleware\ProtectAgainstSpam;
use App\Mail\NewContactMessage;
use App\Models\ContactMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ContactFormTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seedContent();
        Mail::fake();
        config(['agency.notify_email' => 'owner@example.com']);
    }

    private function payload(array $overrides = []): array
    {
        return array_merge([
            'name' => 'Test İstifadəçi',
            'email' => 'test@example.com',
            'phone' => '+994 50 123 45 67',
            'service_type' => 'web_design',
            'message' => 'Salam, korporativ sayt hazırlatmaq istəyirəm.',
        ], $this->spamFields(), $overrides);
    }

    public function test_valid_message_is_stored_and_emailed(): void
    {
        $this->post('/az/elaqe', $this->payload())
            ->assertRedirect(lroute('contact', [], 'az'))
            ->assertSessionHas('success', __('contact.form.success', [], 'az'));

        $message = ContactMessage::sole();
        $this->assertSame('new', $message->status);
        $this->assertSame('az', $message->locale);

        Mail::assertSent(NewContactMessage::class, fn ($mail) => $mail->hasTo('owner@example.com') && $mail->hasReplyTo('test@example.com'));
    }

    public function test_english_form_works_and_stores_locale(): void
    {
        $this->post('/en/contact', $this->payload())->assertRedirect(lroute('contact', [], 'en'));
        $this->assertSame('en', ContactMessage::sole()->locale);
    }

    public function test_validation_errors_are_localized(): void
    {
        $this->from('/az/elaqe')->post('/az/elaqe', $this->payload(['email' => 'not-an-email', 'message' => '']))
            ->assertSessionHasErrors(['email', 'message']);

        $this->assertStringContainsString('email', session('errors')->first('email'));
        $this->assertStringContainsString('məcburidir', session('errors')->first('message'));
        $this->assertSame(0, ContactMessage::count());
    }

    public function test_honeypot_blocks_bots(): void
    {
        $this->from('/az/elaqe')->post('/az/elaqe', $this->payload([ProtectAgainstSpam::HONEYPOT_FIELD => 'http://spam.example']))
            ->assertSessionHasErrors('spam');

        $this->assertSame(0, ContactMessage::count());
        Mail::assertNothingSent();
    }

    public function test_too_fast_submission_is_blocked(): void
    {
        $this->from('/az/elaqe')->post('/az/elaqe', $this->payload([ProtectAgainstSpam::TIME_FIELD => Crypt::encryptString((string) time())]))
            ->assertSessionHasErrors('spam');

        $this->from('/az/elaqe')->post('/az/elaqe', $this->payload([ProtectAgainstSpam::TIME_FIELD => 'tampered']))
            ->assertSessionHasErrors('spam');

        $this->assertSame(0, ContactMessage::count());
    }

    public function test_form_is_rate_limited(): void
    {
        for ($i = 0; $i < 5; $i++) {
            $this->post('/az/elaqe', $this->payload())->assertRedirect();
        }

        $this->post('/az/elaqe', $this->payload())->assertStatus(429);
        $this->assertSame(5, ContactMessage::count());
    }

    public function test_message_is_saved_even_if_mail_fails(): void
    {
        config(['agency.notify_email' => null]);

        $this->post('/az/elaqe', $this->payload())->assertSessionHas('success');
        $this->assertSame(1, ContactMessage::count());
    }
}
