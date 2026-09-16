<?php

declare(strict_types=1);

namespace Agenciafmd\Banners\Services;

use Illuminate\Support\Collection;

final class BannerService
{
    public static function make(): static
    {
        return resolve(self::class);
    }

    public function locations(): Collection
    {
        $locations = config('filament-banners.locations');

        return collect($locations)
            ->flatMap(static fn (array $location, string $key): array => [$key => $location['label']]);
    }
}
