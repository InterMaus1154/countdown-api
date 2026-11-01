<?php

namespace App\Providers;

use App\Services\AuthService;
use Illuminate\Contracts\Auth\Factory;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Hashing\BcryptHasher;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->scoped(AuthService::class);
        $this->app->when(AuthService::class)
            ->needs(StatefulGuard::class)
            ->give(fn($app) => $app->make(Factory::class)->guard('web'));

        $this->app->bind(Hasher::class, BcryptHasher::class);


    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->app->get(\Illuminate\View\Factory::class)->composer('*', function (View $view) {
            $view->with('auth', $this->app->make(AuthService::class));
        });
    }
}
