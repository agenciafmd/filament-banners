<?php

namespace Agenciafmd\Banners\Resources\Banners\Pages;

use Agenciafmd\Admix\Resources\Concerns\RedirectBack;
use Agenciafmd\Banners\Resources\Banners\BannerResource;
use Filament\Resources\Pages\CreateRecord;

class CreateBanner extends CreateRecord
{
    use RedirectBack;

    protected static string $resource = BannerResource::class;

    public function mount(): void
    {
        parent::mount();

        $location = request()->query('location') ?? 'home';

        if ($location) {
            $this->form->fill([
                'location' => $location,
            ]);
        }
    }
}
