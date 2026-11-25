<?php

namespace Tests\Feature;

use App\Mail\NewOrder;
use App\Models\HostingPlan;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class QuoteFormTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seedContent();
        Mail::fake();
        config(['agency.notify_email' => 'owner@example.com']);
    }

    private function contact(): array
    {
        return ['name' => 'Müştəri', 'email' => 'client@example.com', 'phone' => '+994501234567', 'company' => 'ABC MMC'] + $this->spamFields();
    }

    public function test_quote_page_prefills_service_from_query(): void
    {
        $plan = HostingPlan::first();

        $this->get('/az/qiymet-teklifi?service=hosting&plan='.$plan->id.'&billing=yearly')
            ->assertOk()
            ->assertSee("service: 'hosting'", false)
            ->assertSee('value="'.$plan->id.'" selected', false)
            ->assertSee('value="yearly" selected', false);

        $this->get('/en/get-a-quote?service=domain&domain=example.com')
            ->assertOk()
            ->assertSee('value="example.com"', false);
    }

    public function test_web_design_order(): void
    {
        $this->post('/az/qiymet-teklifi', $this->contact() + [
            'service_type' => 'web_design', 'web_type' => 'ecommerce', 'pages' => '6_15', 'budget' => '1000_3000', 'deadline' => '1_month',
            // başqa xidmətə aid sahələr nəzərə alınmamalıdır
            'app_platform' => 'ios', 'domain_name' => 'x',
        ])->assertRedirect(lroute('quote', [], 'az'))->assertSessionHas('success');

        $order = Order::sole();
        $this->assertSame('web_design', $order->service_type);
        $this->assertSame(['web_type' => 'ecommerce', 'pages' => '6_15', 'budget' => '1000_3000', 'deadline' => '1_month'], $order->details);
        $this->assertSame('new', $order->status);

        Mail::assertSent(NewOrder::class, fn ($mail) => $mail->hasTo('owner@example.com'));
    }

    public function test_web_design_requires_type(): void
    {
        $this->post('/az/qiymet-teklifi', $this->contact() + ['service_type' => 'web_design'])
            ->assertSessionHasErrors('web_type');
    }

    public function test_mobile_app_order(): void
    {
        $this->post('/en/get-a-quote', $this->contact() + ['service_type' => 'mobile_app', 'app_platform' => 'both', 'has_design' => 'no'])
            ->assertSessionHas('success');

        $this->assertSame(['app_platform' => 'both', 'has_design' => 'no'], Order::sole()->details);
        $this->assertSame('en', Order::sole()->locale);
    }

    public function test_hosting_order_snapshots_plan_and_price(): void
    {
        $plan = HostingPlan::where('is_featured', true)->first();

        $this->post('/az/qiymet-teklifi', $this->contact() + ['service_type' => 'hosting', 'hosting_plan_id' => $plan->id, 'billing_cycle' => 'yearly'])
            ->assertSessionHas('success');

        $details = Order::sole()->details;
        $this->assertSame($plan->name_az, $details['hosting_plan_name']);
        $this->assertSame(money($plan->price_yearly), $details['price']);
    }

    public function test_inactive_hosting_plan_is_rejected(): void
    {
        $plan = HostingPlan::first();
        $plan->update(['is_active' => false]);

        $this->post('/az/qiymet-teklifi', $this->contact() + ['service_type' => 'hosting', 'hosting_plan_id' => $plan->id, 'billing_cycle' => 'monthly'])
            ->assertSessionHasErrors('hosting_plan_id');
    }

    public function test_domain_order(): void
    {
        $this->post('/az/qiymet-teklifi', $this->contact() + ['service_type' => 'domain', 'domain_name' => 'Hamid-Test.AZ', 'domain_action' => 'register', 'domain_years' => 2])
            ->assertSessionHas('success');

        $details = Order::sole()->details;
        $this->assertSame('hamid-test.az', $details['domain_name']);
        $this->assertSame(money(80), $details['price']);

        $rows = collect(Order::sole()->detailRows())->pluck('value', 'label');
        $this->assertSame(__('quote.options.domain_action.register'), $rows[__('quote.fields.domain_action')]);
    }

    public function test_domain_with_unsupported_extension_is_rejected(): void
    {
        $this->post('/az/qiymet-teklifi', $this->contact() + ['service_type' => 'domain', 'domain_name' => 'example.xyz', 'domain_action' => 'register', 'domain_years' => 1])
            ->assertSessionHasErrors(['domain_name' => __('quote.errors.domain_name', [], 'az')]);
    }

    public function test_invalid_service_type_is_rejected(): void
    {
        $this->post('/az/qiymet-teklifi', $this->contact() + ['service_type' => 'hacking'])->assertSessionHasErrors('service_type');
        $this->assertSame(0, Order::count());
    }
}
