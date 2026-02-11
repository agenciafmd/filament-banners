<?php

namespace Agenciafmd\Banners\Services;

use Illuminate\Support\Collection;

class BannerService
{
    public static function make(): static
    {
        return app(self::class);
    }

    public function locations(): Collection
    {
        $locations = config('filament-banners.locations');

        return collect($locations)
            ->flatMap(fn ($location, $key) => [$key => $location['label']]);
    }
}
