<?php

declare(strict_types=1);

namespace Agenciafmd\Banners\View\Components;

use Agenciafmd\Banners\Models\Banner as BannerModel;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\Component;

final class Banner extends Component
{
    public function __construct(
        public int $quantity = 3,
        public string $location = 'home',
        public bool $random = false,
        public string $template = 'filament-banners::components.home',
    ) {}

    public function render(): View
    {
        $query = BannerModel::query()
            ->where('location', $this->location)
            ->where('is_active', true);

        if ($this->random) {
            $query->inRandomOrder();
        } else {
            $query->orderBy('is_active', 'desc')
                ->orderBy('star', 'desc')
                ->latest('published_at')
                ->orderBy('name');
        }

        $banners = $query->take($this->quantity)
            ->get();

        $view['banners'] = $banners->map(function ($banner): array {
            $files = config(sprintf('filament-banners.locations.%s.files', $this->location));
            foreach ($files as $fileKey => $file) {
                $collection = $fileKey;
                $media = Storage::url($banner->$fileKey);
                if ($collection !== 'video') {
                    $responsiveImages[$file['media']] = $media;
                }

                if ($collection === 'video') {
                    $video = $media;
                }
            }

            return [
                'name' => $banner->name,
                'meta' => $banner->meta,
                'link' => $banner->link,
                'target' => $banner->target,
                'images' => $responsiveImages ?? null,
                'video' => $video ?? null,
            ];
        });

        return view($this->template, $view);
    }
}
