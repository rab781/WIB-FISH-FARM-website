<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use App\Helpers\FinancialHelper;

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
        // Register helper functions for Blade
        Blade::directive('getCategoryColor', function ($expression) {
            return "<?php echo App\Helpers\FinancialHelper::getCategoryColor($expression); ?>";
        });
    }
}
