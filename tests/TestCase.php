<?php

namespace Tests;

use App\Http\Middleware\ProtectAgainstSpam;
use App\Models\User;
use Database\Seeders\DomainExtensionSeeder;
use Database\Seeders\HostingPlanSeeder;
use Database\Seeders\ProjectCategorySeeder;
use Database\Seeders\ServiceSeeder;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Crypt;

abstract class TestCase extends BaseTestCase
{
    protected function seedContent(): void
    {
        $this->seed([ServiceSeeder::class, HostingPlanSeeder::class, DomainExtensionSeeder::class, ProjectCategorySeeder::class]);
    }

    /** Spam qorunmasını keçən sahələr (forma 10 saniyə əvvəl açılıb) */
    protected function spamFields(): array
    {
        return [
            ProtectAgainstSpam::HONEYPOT_FIELD => '',
            ProtectAgainstSpam::TIME_FIELD => Crypt::encryptString((string) (time() - 10)),
        ];
    }

    protected function admin(): User
    {
        return User::factory()->create(['is_admin' => true]);
    }
}
