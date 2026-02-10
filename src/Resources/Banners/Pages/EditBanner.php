<?php

namespace Agenciafmd\Banners\Resources\Banners\Pages;

use Agenciafmd\Admix\Resources\Concerns\RedirectBack;
use Agenciafmd\Banners\Resources\Banners\BannerResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditBanner extends EditRecord
{
    use RedirectBack;

    protected static string $resource = BannerResource::class;

    protected $listeners = [
        'auditRestored',
    ];

    public function auditRestored(): void
    {
        $this->fillForm();
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
