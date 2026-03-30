<?php

declare(strict_types=1);

namespace App\Providers;

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
