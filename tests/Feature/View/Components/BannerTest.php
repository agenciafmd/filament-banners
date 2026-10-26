<?php

declare(strict_types=1);

namespace Agenciafmd\Banners\Tests\Feature\View\Components;

use Agenciafmd\Banners\Models\Banner;
use Agenciafmd\Banners\View\Components\Banner as BannerComponent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

pest()->extend(TestCase::class)->use(RefreshDatabase::class);

it('lists only the visible files that the banner has', function (): void {
    Storage::fake();
    config()->set('filament-banners.locations.home.files', [
        'desktop' => ['visible' => true, 'media' => '(min-width: 1400px)'],
        'notebook' => ['visible' => true, 'media' => '(min-width: 768px)'],
        'mobile' => ['visible' => false, 'media' => '(max-width: 767px)'],
    ]);
    Banner::factory()->create([
        'location' => 'home',
        'is_active' => true,
        'published_at' => null,
        'until_then' => null,
        'desktop' => 'banners/desktop.jpg',
        'notebook' => null,
        'mobile' => 'banners/mobile.jpg',
    ]);

    $banners = new BannerComponent(location: 'home')->render()->getData()['banners'];

    expect($banners->first()['images'])->toBe([
        '(min-width: 1400px)' => Storage::url('banners/desktop.jpg'),
    ]);
});

it('includes video and mobile video URLs when visible', function (): void {
    Storage::fake();
    config()->set('filament-banners.locations.home.files', [
        'desktop' => ['visible' => true, 'media' => '(min-width: 1400px)'],
        'video' => ['visible' => true],
        'mobile_video' => ['visible' => true],
    ]);
    Banner::factory()->create([
        'location' => 'home',
        'is_active' => true,
        'published_at' => null,
        'until_then' => null,
        'desktop' => 'banners/desktop.jpg',
        'video' => 'banners/video.mp4',
        'mobile_video' => 'banners/mobile_video.mp4',
    ]);

    $banners = new BannerComponent(location: 'home')->render()->getData()['banners'];

    expect($banners->first()['video'])->toBe(Storage::url('banners/video.mp4'))
        ->and($banners->first()['mobile_video'])->toBe(Storage::url('banners/mobile_video.mp4'));
});
