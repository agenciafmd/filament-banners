<?php

declare(strict_types=1);

namespace Agenciafmd\Banners\Database\Factories;

use Agenciafmd\Banners\Services\BannerService;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;

final class BannerFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->sentence(4);
        $slug = str()->slug($name);
        $location = fake()->randomElement(BannerService::make()
            ->locations()
            ->keys()
            ->toArray());
        $config = "filament-banners.locations.{$location}.files";

        return [
            'is_active' => fake()->boolean(),
            'star' => fake()->boolean(),
            'location' => $location,
            'name' => $name,
            'published_at' => fake()->dateTimeBetween(now()->subMonths(6), now()->addDay()),
            'until_then' => fake()->dateTimeBetween(now()->subDay(), now()->addMonths(6)),
            'link' => fake()->url(),
            'target' => '_blank',
            'desktop' => config("{$config}.desktop.visible") ? Storage::putFile('fake', fake()->localImage(ratio: config("{$config}.desktop.ratio")[0])) : null,
            'notebook' => config("{$config}.notebook.visible") ? Storage::putFile('fake', fake()->localImage(ratio: config("{$config}.notebook.ratio")[0])) : null,
            'mobile' => config("{$config}.mobile.visible") ? Storage::putFile('fake', fake()->localImage(ratio: config("{$config}.mobile.ratio")[0])) : null,
            'slug' => $slug,
        ];
    }
}
