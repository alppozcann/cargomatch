<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Yönlendirme yapılacak varsayılan yol.
     *
     * @var string
     */
    public const HOME = '/dashboard'; // Changed from '/profile/edit' to '/dashboard'

    /**
     * Uygulamanın "route"ları başlatıldığında yapılacaklar.
     */
    public function boot(): void
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::middleware('web')
                ->group(base_path('routes/web.php'));

        });
    }


}