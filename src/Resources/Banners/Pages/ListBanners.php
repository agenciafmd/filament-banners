<?php

declare(strict_types=1);

namespace Agenciafmd\Banners\Resources\Banners\Pages;

use Agenciafmd\Banners\Resources\Banners\BannerResource;
use Agenciafmd\Banners\Services\BannerService;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Resources\Pages\ListRecords;
use Override;

final class ListBanners extends ListRecords
{
    protected static string $resource = BannerResource::class;

    #[Override]
    protected function getHeaderActions(): array
    {
        return [
            Action::make('create')
                ->label('Criar Banner')
                ->schema([
                    Select::make('location')
                        ->translateLabel()
                        ->options(BannerService::make()
                            ->locations()
                            ->toArray())
                        ->required(),
                ])
                ->action(fn (array $data) => redirect()->to(
                    BannerResource::getUrl('create', ['location' => $data['location']])
                ))
                ->modalSubmitActionLabel('Continuar')
                ->modalWidth('lg'),
        ];
    }
}
