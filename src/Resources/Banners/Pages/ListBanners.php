<?php

namespace Agenciafmd\Banners\Resources\Banners\Pages;

use Agenciafmd\Banners\Resources\Banners\BannerResource;
use Agenciafmd\Banners\Services\BannerService;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Resources\Pages\ListRecords;

class ListBanners extends ListRecords
{
    protected static string $resource = BannerResource::class;

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
                ->action(function (array $data) {
                    return redirect()->to(
                        BannerResource::getUrl('create', ['location' => $data['location']])
                    );
                })
                ->modalSubmitActionLabel('Continuar')
                ->modalWidth('lg'),
        ];
    }
}
