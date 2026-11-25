<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class DeployEndpointTest extends TestCase
{
    use RefreshDatabase;

    public function test_endpoint_is_disabled_without_token(): void
    {
        config(['agency.deploy_token' => null]);
        $this->post('/_deploy/post-deploy')->assertNotFound();
    }

    public function test_wrong_token_is_rejected(): void
    {
        config(['agency.deploy_token' => str_repeat('a', 40)]);
        $this->post('/_deploy/post-deploy', [], ['X-Deploy-Token' => 'wrong'])->assertNotFound();
    }

    public function test_correct_token_runs_post_deploy_commands(): void
    {
        config(['agency.deploy_token' => $token = str_repeat('b', 40)]);
        Artisan::shouldReceive('call')->times(5)->andReturn(0);
        Artisan::shouldReceive('output')->andReturn('ok');

        $this->post('/_deploy/post-deploy', [], ['X-Deploy-Token' => $token])->assertOk()->assertJson(['ok' => true]);
    }

    public function test_seed_flag_runs_seeders(): void
    {
        config(['agency.deploy_token' => $token = str_repeat('c', 40)]);
        Artisan::shouldReceive('call')->times(6)->andReturn(0);
        Artisan::shouldReceive('output')->andReturn('ok');

        $this->post('/_deploy/post-deploy?seed=1', [], ['X-Deploy-Token' => $token])->assertOk()->assertJsonPath('output.db:seed', 'ok');
    }
}
