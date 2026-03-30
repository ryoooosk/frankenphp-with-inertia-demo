<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;
use Laravel\Passport\Passport;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // レートリミット: ログイン試行（5回/分）
        RateLimiter::for('login', function (Request $request) {
            $key = $request->string('email')->lower() . '|' . $request->ip();

            return Limit::perMinute(5)->by($key);
        });

        // レートリミット: APIリクエスト（60回/分）
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        // トークン有効期限
        Passport::tokensExpireIn(now()->addDays(15));
        Passport::refreshTokensExpireIn(now()->addDays(30));
        Passport::personalAccessTokensExpireIn(now()->addMonths(6));

        // OAuth2認可同意画面をInertiaページでレンダリング
        Passport::authorizationView(function (array $params) {
            /** @var array<int, \Laravel\Passport\Scope> $scopes */
            $scopes = $params['scopes'];

            return Inertia::render('Auth/Authorize', [
                'client' => [
                    'name' => $params['client']->name,
                ],
                'scopes' => array_map(fn (\Laravel\Passport\Scope $scope) => [
                    'id' => $scope->id,
                    'description' => $scope->description,
                ], $scopes),
                'authToken' => $params['authToken'],
            ])->toResponse(request());
        });
    }
}
