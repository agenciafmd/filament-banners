<?php

declare(strict_types=1);

namespace Agenciafmd\Banners\Providers;

use Agenciafmd\Banners\View\Components\Banner;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\Compilers\BladeCompiler;
use Override;

final class BladeServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->bootBladeComponents();

        $this->bootBladeDirectives();

        $this->bootBladeComposers();

        $this->bootViews();

        $this->bootPublish();
    }

    #[Override]
    public function register(): void
    {
        //
    }

    private function bootBladeComponents(): void
    {
        Blade::componentNamespace('Agenciafmd\\Banners\\View\\Components', 'filament-banners');

        $this->callAfterResolving(BladeCompiler::class, function (BladeCompiler $blade): void {
            $blade->component(Banner::class, 'banner');
        });
    }

    private function bootBladeComposers(): void
    {
        //
    }

    private function bootBladeDirectives(): void
    {
        //
    }

    private function bootViews(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'filament-banners');
    }

    private function bootPublish(): void
    {
        //
    }
}
