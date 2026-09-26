<?php

declare(strict_types=1);

namespace Agenciafmd\Banners\Database\Factories;

use Agenciafmd\Banners\Enums\Meta;
use Agenciafmd\Banners\Models\Banner;
use Agenciafmd\Banners\Services\BannerService;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;

/**
 * @extends Factory<Banner>
 */
final class BannerFactory extends Factory
{
    protected $model = Banner::class;

    public function definition(): array
    {
        $name = fake()->sentence(4);
        $slug = str($name)->slug();
        $location = BannerService::make()
            ->locations()
            ->keys()
            ->random();

        return [
            'is_active' => fake()->boolean(),
            'star' => fake()->boolean(),
            'location' => $location,
            'name' => $name,
            'published_at' => fake()->dateTimeBetween(now()->subMonths(6), now()->addDay()),
            'until_then' => fake()->dateTimeBetween(now()->subDay(), now()->addMonths(6)),
            'link' => fake()->url(),
            'target' => '_blank',
            'desktop' => $this->image($location, 'desktop', '16:9'),
            'notebook' => $this->image($location, 'notebook', '16:9'),
            'mobile' => $this->image($location, 'mobile', '9:16'),
            'meta' => $this->meta($location),
            'slug' => $slug,
        ];
    }

    private function image(string $location, string $file, string $ratio): ?string
    {
        if (! BannerService::make()->file($location, $file)['visible']) {
            return null;
        }

        return Storage::putFile('fake', fake()->localImage(ratio: $ratio)) ?: null;
    }

    /**
     * @return array<string, mixed>
     */
    private function meta(string $location): array
    {
        return BannerService::make()
            ->meta($location)
            ->mapWithKeys(static fn (array $field): array => [
                $field['name'] => match ($field['type']) {
                    Meta::TEXT => fake()->sentence(),
                    Meta::SELECT => fake()->randomElement(array_keys($field['options'])),
                    Meta::REPEATER => collect(range(1, fake()->numberBetween(1, 3)))
                        ->map(static fn (): array => [
                            'name' => fake()->word(),
                        ])
                        ->all(),
                },
            ])
            ->all();
    }
}
