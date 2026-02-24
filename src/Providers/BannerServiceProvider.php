<?php

declare(strict_types=1);

namespace Agenciafmd\Banners\Providers;

use Illuminate\Support\ServiceProvider;

final class BannerServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->bootProviders();

        $this->bootMigrations();

        $this->bootTranslations();

        $this->bootPublish();
    }

    public function register(): void
    {
        $this->registerConfigs();
    }

    private function bootProviders(): void
    {
        //
    }

    private function bootPublish(): void
    {
        $this->publishes([
            __DIR__ . '/../../config/filament-banners.php' => base_path('config/filament-banners.php'),
        ], 'filament-banners:config');
    }

    private function bootMigrations(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
    }

    private function bootTranslations(): void
    {
        $this->loadTranslationsFrom(__DIR__ . '/../../lang', 'filament-banners');
        $this->loadJsonTranslationsFrom(__DIR__ . '/../../lang');
    }

    private function registerConfigs(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/filament-banners.php', 'filament-banners');
    }
}
