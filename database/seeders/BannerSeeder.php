<?php

declare(strict_types=1);

namespace Agenciafmd\Articles\Database\Seeders;

use Agenciafmd\Banners\Models\Banner;
use Illuminate\Database\Seeder;

final class BannerSeeder extends Seeder
{
    public function run(): void
    {
        Banner::query()
            ->truncate();

        //
    }
}
