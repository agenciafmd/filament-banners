<?php

declare(strict_types=1);

namespace Agenciafmd\Banners\Services;

use Agenciafmd\Banners\Enums\Meta;
use Illuminate\Support\Collection;

/**
 * Leitura tipada do config `filament-banners.locations`.
 */
final class BannerService
{
    /**
     * Largura e altura usadas quando o arquivo não define as suas.
     *
     * @var array<string, array{width: int, height: int}>
     */
    private const array DEFAULT_SIZES = [
        'desktop' => [
            'width' => 1920,
            'height' => 1080,
        ],
        'notebook' => [
            'width' => 1440,
            'height' => 810,
        ],
        'mobile' => [
            'width' => 1440,
            'height' => 810,
        ],
    ];

    public static function make(): static
    {
        return resolve(self::class);
    }

    /**
     * @return Collection<string, string> localização => label
     */
    public function locations(): Collection
    {
        return collect($this->config('filament-banners.locations'))
            ->filter(static fn (mixed $location): bool => is_array($location))
            ->mapWithKeys(static fn (array $location, int|string $key): array => [
                (string) $key => self::toString($location['label'] ?? null, ucfirst((string) $key)),
            ]);
    }

    /**
     * @return Collection<string, array{visible: bool, width: int, height: int, media: string}> nome do arquivo => config
     */
    public function files(string $location): Collection
    {
        return collect($this->config("filament-banners.locations.{$location}.files"))
            ->keys()
            ->mapWithKeys(fn (int|string $name): array => [(string) $name => $this->file($location, (string) $name)]);
    }

    /**
     * @return array{visible: bool, width: int, height: int, media: string}
     */
    public function file(string $location, string $name): array
    {
        $file = $this->config("filament-banners.locations.{$location}.files.{$name}");
        $defaultSize = self::DEFAULT_SIZES[$name] ?? self::DEFAULT_SIZES['desktop'];

        return [
            'visible' => (bool) ($file['visible'] ?? false),
            'width' => $this->toInteger($file['width'] ?? null, $defaultSize['width']),
            'height' => $this->toInteger($file['height'] ?? null, $defaultSize['height']),
            'media' => self::toString($file['media'] ?? null, ''),
        ];
    }

    /**
     * Campos adicionais (`meta`) da localização; campos com `type` inválido são ignorados.
     *
     * @return Collection<int, array{type: Meta, name: string, label: string, options: array<string, string>}>
     */
    public function meta(string $location): Collection
    {
        return collect($this->config("filament-banners.locations.{$location}.meta"))
            ->map(static function (mixed $field): ?array {
                $type = is_array($field) ? ($field['type'] ?? null) : null;

                if (! is_array($field) || ! $type instanceof Meta) {
                    return null;
                }

                return [
                    'type' => $type,
                    'name' => self::toString($field['name'] ?? null, ''),
                    'label' => self::toString($field['label'] ?? null, ''),
                    'options' => collect(is_array($field['options'] ?? null) ? $field['options'] : [])
                        ->mapWithKeys(static fn (mixed $label, int|string $value): array => [
                            (string) $value => self::toString($label, (string) $value),
                        ])
                        ->all(),
                ];
            })
            ->filter()
            ->values();
    }

    private static function toString(mixed $value, string $default): string
    {
        return is_scalar($value) ? (string) $value : $default;
    }

    private function toInteger(mixed $value, int $default): int
    {
        return is_numeric($value) ? (int) $value : $default;
    }

    /**
     * @return array<array-key, mixed>
     */
    private function config(string $key): array
    {
        $value = config($key);

        return is_array($value) ? $value : [];
    }
}
