<?php

namespace Agenciafmd\Banners\Services;

class BannerService
{
    public function locations(): array
    {
        $locations = config('filament-banners.locations');

        return collect($locations)
            ->flatMap(fn ($location, $key) => [$key => $location['label']])
            ->toArray();
    }
}
