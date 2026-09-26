<?php

declare(strict_types=1);

namespace Agenciafmd\Banners\View\Components;

use Agenciafmd\Banners\Models\Banner as BannerModel;
use Agenciafmd\Banners\Services\BannerService;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\Component;

final class Banner extends Component
{
    /**
     * @param  view-string  $template
     */
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
            ->isActive();

        if ($this->random) {
            $query->inRandomOrder();
        } else {
            $query->sort();
        }

        $banners = $query->take($this->quantity)
            ->get();

        $files = BannerService::make()->files($this->location);

        $view['banners'] = $banners->map(static function (BannerModel $banner) use ($files): array {
            $responsiveImages = [];
            $video = null;

            foreach ($files as $fileKey => $file) {
                $path = $banner->getAttribute($fileKey);

                if (! $file['visible'] || ! is_string($path) || $path === '') {
                    continue;
                }

                $media = Storage::url($path);
                if ($fileKey === 'video') {
                    $video = $media;

                    continue;
                }

                $responsiveImages[$file['media']] = $media;
            }

            return [
                'name' => $banner->name,
                'meta' => $banner->meta,
                'link' => $banner->link,
                'target' => $banner->target,
                'images' => $responsiveImages,
                'video' => $video,
            ];
        });

        return view($this->template, $view);
    }
}
