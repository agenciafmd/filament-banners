<?php

declare(strict_types=1);

namespace Agenciafmd\Banners\Tests\Feature\Resources;

use Agenciafmd\Admix\Models\User;
use Agenciafmd\Banners\Models\Banner;
use Agenciafmd\Banners\Resources\Banners\Pages\CreateBanner;
use Filament\Facades\Filament;
use Filament\Forms\Components\FileUpload;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

use function Pest\Laravel\actingAs;

uses(TestCase::class, RefreshDatabase::class);

it('resizes each image to the dimensions configured for the location', function (): void {
    config()->set('filament-banners.locations.home.files.desktop.width', 1600);
    config()->set('filament-banners.locations.home.files.desktop.height', 900);

    actingAs(User::factory()->create()->fresh(), 'admix-web');
    Filament::setCurrentPanel('admix');

    Livewire::test(CreateBanner::class)
        ->assertFormFieldExists('desktop', static fn (FileUpload $field): bool => $field->getAutomaticallyResizeImagesWidth() === '1600'
            && $field->getAutomaticallyResizeImagesHeight() === '900')
        ->assertSee('Max. 1600x900');
});

it('upscales and crops a smaller image with a different aspect ratio to the exact dimensions', function (): void {
    Storage::fake('public');
    config()->set('filament-banners.locations.home.files.desktop.width', 1600);
    config()->set('filament-banners.locations.home.files.desktop.height', 900);
    config()->set('filament-banners.locations.home.files.notebook.visible', false);
    config()->set('filament-banners.locations.home.files.mobile.visible', false);

    actingAs(User::factory()->create()->fresh(), 'admix-web');
    Filament::setCurrentPanel('admix');

    Livewire::test(CreateBanner::class)
        ->fillForm([
            'name' => 'Banner',
            'slug' => 'banner',
            'desktop' => UploadedFile::fake()->image('banner.png', 400, 400),
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $path = Banner::query()->sole()->desktop;

    [$width, $height] = getimagesizefromstring(Storage::disk('public')->get($path));

    expect($path)->toEndWith('.jpg')
        ->and([$width, $height])->toBe([1600, 900]);
});
