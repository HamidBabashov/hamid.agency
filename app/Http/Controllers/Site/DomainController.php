<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\DomainExtension;
use App\Services\Domain\DomainAvailabilityService;
use App\Services\Domain\InvalidDomainException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DomainController extends Controller
{
    public function index(Request $request): View
    {
        return view('pages.domains', [
            'extensions' => DomainExtension::active()->get(),
            'initialQuery' => (string) $request->query('domain', ''),
        ]);
    }

    public function check(Request $request, DomainAvailabilityService $service): JsonResponse
    {
        $validated = $request->validate([
            'domain' => ['required', 'string', 'max:100'],
        ]);

        try {
            $results = $service->search($validated['domain']);
        } catch (InvalidDomainException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json([
            'results' => array_map(fn (array $r) => $r + [
                'status_label' => __('domains.status.'.$r['status']),
                'order_url' => lroute('quote', ['service' => 'domain', 'domain' => $r['domain']]),
            ], $results),
        ]);
    }
}
