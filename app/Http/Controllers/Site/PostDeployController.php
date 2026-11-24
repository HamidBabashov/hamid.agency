<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * SSH-sız (FTP) deploy-dan sonra GitHub Actions bu ünvanı çağırır:
 *   POST /_deploy/post-deploy   Header: X-Deploy-Token: <DEPLOY_TOKEN>
 * .env-də DEPLOY_TOKEN boşdursa, bu endpoint tamamilə bağlıdır (404).
 */
class PostDeployController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $token = (string) config('agency.deploy_token');

        abort_if(strlen($token) < 32, 404);
        abort_unless(hash_equals($token, (string) $request->header('X-Deploy-Token')), 404);

        $output = [];

        try {
            // Köhnə keşləri təmizlə, sonra miqrasiya və yeni keşlər
            foreach ([
                ['optimize:clear', []],
                ['migrate', ['--force' => true]],
                ['config:cache', []],
                ['route:cache', []],
                ['view:cache', []],
            ] as [$command, $params]) {
                Artisan::call($command, $params);
                $output[$command] = trim(Artisan::output());
            }
        } catch (Throwable $e) {
            Log::error('Post-deploy failed: '.$e->getMessage());

            return response()->json(['ok' => false, 'error' => $e->getMessage(), 'output' => $output], 500);
        }

        return response()->json(['ok' => true, 'output' => $output]);
    }
}
