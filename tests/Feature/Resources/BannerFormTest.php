<?php

declare(strict_types=1);

namespace Agenciafmd\Banners\Tests\Feature\Resources;

use Agenciafmd\Admix\Models\User;
use Agenciafmd\Banners\Resources\Banners\Pages\CreateBanner;
use Filament\Facades\Filament;
use Filament\Forms\Components\FileUpload;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
