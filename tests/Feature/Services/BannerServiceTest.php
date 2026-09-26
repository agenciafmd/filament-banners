<?php

declare(strict_types=1);

namespace Agenciafmd\Banners\Tests\Feature\Services;

use Agenciafmd\Banners\Enums\Meta;
use Agenciafmd\Banners\Services\BannerService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

it('lists the locations with their labels', function (): void {
    config()->set('filament-banners.locations', [
        'home' => ['label' => 'Home'],
        'blog' => [],
        'invalid' => 'not a location',
    ]);

    expect(BannerService::make()->locations()->all())->toBe([
        'home' => 'Home',
        'blog' => 'Blog',
    ]);
});

it('falls back to the default size when the file does not define one', function (): void {
    config()->set('filament-banners.locations.home.files', [
        'mobile' => ['visible' => true, 'media' => '(max-width: 767px)'],
    ]);

    expect(BannerService::make()->file('home', 'mobile'))->toBe([
        'visible' => true,
        'width' => 1440,
        'height' => 810,
        'media' => '(max-width: 767px)',
    ]);
});

it('treats a file missing from the config as hidden', function (): void {
    config()->set('filament-banners.locations.home.files', []);

    expect(BannerService::make()->file('home', 'video')['visible'])->toBeFalse();
});

it('ignores the meta fields with an unknown type', function (): void {
    config()->set('filament-banners.locations.home.meta', [
        ['type' => Meta::TEXT, 'label' => 'Título', 'name' => 'title'],
        ['type' => 'text', 'label' => 'Inválido', 'name' => 'invalid'],
        ['type' => Meta::SELECT, 'label' => 'Status', 'name' => 'status', 'options' => ['pronto' => 'Pronto']],
    ]);

    expect(BannerService::make()->meta('home')->all())->toBe([
        ['type' => Meta::TEXT, 'name' => 'title', 'label' => 'Título', 'options' => []],
        ['type' => Meta::SELECT, 'name' => 'status', 'label' => 'Status', 'options' => ['pronto' => 'Pronto']],
    ]);
});
