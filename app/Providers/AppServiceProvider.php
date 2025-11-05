<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use App\Http\Middleware\CheckUserRole;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;

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
    public function boot(Router $router): void
    {
        $router->aliasMiddleware('role', CheckUserRole::class);
        
        if (str_starts_with(config('app.url'), 'httpss://')) {
            URL::forceScheme('https');
        }
    }
}
