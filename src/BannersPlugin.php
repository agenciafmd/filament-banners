<?php

declare(strict_types=1);

namespace Agenciafmd\Banners;

use Agenciafmd\Banners\Resources\Banners\BannerResource;
use Filament\Contracts\Plugin;
use Filament\Panel;

final class BannersPlugin implements Plugin
{
    public static function make(): static
    {
        return app(self::class);
    }

    public function getId(): string
    {
        return 'banners';
    }

    public function register(Panel $panel): void
    {
        $panel
            ->resources([
                BannerResource::class,
            ]);
    }

    public function boot(Panel $panel): void
    {
        //
    }
}
