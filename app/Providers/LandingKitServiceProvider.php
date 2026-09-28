<?php

declare(strict_types=1);

namespace App\Providers;

use App\Services\SectionCatalog;
use App\Services\SectionRegistry;
use Illuminate\Support\ServiceProvider;

class LandingKitServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(SectionRegistry::class, function () {
            $registry = new SectionRegistry;

            // Auto-register the 107+ standard sections from SectionCatalog
            foreach (SectionCatalog::getDefinitions() as $section) {
                $registry->register($section);
            }

            return $registry;
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
