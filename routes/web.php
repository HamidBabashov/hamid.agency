<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\Site;
use App\Http\Middleware\SetLocale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Kök domen → seçilmiş dil (cookie) və ya əsas dil (az)
|--------------------------------------------------------------------------
*/
Route::get('/', function (Request $request) {
    $locale = $request->cookie(SetLocale::COOKIE);
    $locale = in_array($locale, config('agency.locales'), true) ? $locale : config('agency.default_locale');

    return redirect("/{$locale}", 302);
})->name('root');

Route::get('sitemap.xml', Site\SitemapController::class)->name('sitemap');

/*
|--------------------------------------------------------------------------
| Sayt səhifələri: /az/... və /en/...
| URL seqmentləri lang/{locale}/routes.php faylından götürülür.
|--------------------------------------------------------------------------
*/
foreach (config('agency.locales') as $locale) {
    $seg = fn (string $key) => trans("routes.{$key}", [], $locale);

    Route::prefix($locale)
        ->name("{$locale}.")
        ->middleware("locale:{$locale}")
        ->group(function () use ($seg) {
            Route::get('/', [Site\HomeController::class, 'index'])->name('home');

            Route::get($seg('services'), [Site\ServiceController::class, 'index'])->name('services.index');
            Route::get($seg('services').'/{slug}', [Site\ServiceController::class, 'show'])->name('services.show');

            Route::get($seg('hosting'), [Site\HostingController::class, 'index'])->name('hosting');

            Route::get($seg('domains'), [Site\DomainController::class, 'index'])->name('domains');
            Route::get($seg('domains').'/check', [Site\DomainController::class, 'check'])
                ->middleware('throttle:domain-check')
                ->name('domains.check');

            Route::get($seg('portfolio'), [Site\PortfolioController::class, 'index'])->name('portfolio');
            Route::get($seg('about'), [Site\PageController::class, 'about'])->name('about');

            Route::get($seg('contact'), [Site\ContactController::class, 'show'])->name('contact');
            Route::post($seg('contact'), [Site\ContactController::class, 'store'])
                ->middleware(['throttle:forms', 'spam'])
                ->name('contact.store');

            Route::get($seg('quote'), [Site\QuoteController::class, 'create'])->name('quote');
            Route::post($seg('quote'), [Site\QuoteController::class, 'store'])
                ->middleware(['throttle:forms', 'spam'])
                ->name('quote.store');

            Route::get($seg('privacy'), [Site\PageController::class, 'privacy'])->name('privacy');
            Route::get($seg('terms'), [Site\PageController::class, 'terms'])->name('terms');
        });
}

/*
|--------------------------------------------------------------------------
| Admin panel: /admin
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->middleware('locale:az')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('login', [Admin\AuthController::class, 'showLogin'])->name('login');
        Route::post('login', [Admin\AuthController::class, 'login'])->middleware('throttle:admin-login')->name('login.attempt');
    });

    Route::middleware(['auth', 'admin'])->group(function () {
        Route::post('logout', [Admin\AuthController::class, 'logout'])->name('logout');
        Route::get('/', [Admin\DashboardController::class, 'index'])->name('dashboard');

        Route::resource('services', Admin\ServiceController::class)->except('show');
        Route::resource('hosting-plans', Admin\HostingPlanController::class)->except('show');
        Route::resource('domain-extensions', Admin\DomainExtensionController::class)->except('show');
        Route::resource('project-categories', Admin\ProjectCategoryController::class)->except('show');
        Route::resource('projects', Admin\ProjectController::class)->except('show');
        Route::resource('testimonials', Admin\TestimonialController::class)->except('show');

        Route::resource('orders', Admin\OrderController::class)->only(['index', 'show', 'update', 'destroy']);
        Route::resource('messages', Admin\MessageController::class)->only(['index', 'show', 'update', 'destroy']);
    });
});

// SSH olmayan hostinqdə deploy-dan sonra miqrasiya/keş (GitHub Actions çağırır)
Route::post('_deploy/post-deploy', Site\PostDeployController::class)
    ->middleware('throttle:5,1')
    ->name('deploy.post');
