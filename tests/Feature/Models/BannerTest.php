<?php

declare(strict_types=1);

namespace Agenciafmd\Banners\Tests\Feature\Models;

use Agenciafmd\Banners\Models\Banner;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

use function Pest\Laravel\travelTo;

uses(TestCase::class, RefreshDatabase::class);

it('keeps only the active banners inside the display period', function (): void {
    Storage::fake();
    travelTo('2026-09-25 12:00:00');
    $current = Banner::factory()->create(['is_active' => true, 'published_at' => '2026-09-01 00:00:00', 'until_then' => '2026-10-01 00:00:00']);
    $withoutPeriod = Banner::factory()->create(['is_active' => true, 'published_at' => null, 'until_then' => null]);
    Banner::factory()->create(['is_active' => false, 'published_at' => null, 'until_then' => null]);
    Banner::factory()->create(['is_active' => true, 'published_at' => '2026-10-01 00:00:00', 'until_then' => null]);
    Banner::factory()->create(['is_active' => true, 'published_at' => null, 'until_then' => '2026-09-01 00:00:00']);

    $activeIds = Banner::query()->isActive()->pluck('id')->sort()->values()->all();

    expect($activeIds)->toBe([$current->getKey(), $withoutPeriod->getKey()]);
});
