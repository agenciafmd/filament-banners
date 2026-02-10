<?php

namespace Agenciafmd\Banners\Models;

use Agenciafmd\Articles\Database\Factories\BannerFactory;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

#[UseFactory(BannerFactory::class)]
class Banner extends Model implements AuditableContract
{
    use Auditable, HasFactory, Prunable, SoftDeletes;

    public function prunable(): Builder
    {
        return self::query()
            ->where('deleted_at', '<=', now()->subDays(30));
    }

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'star' => 'boolean',
            'published_at' => 'timestamp',
            'until_then' => 'timestamp',
        ];
    }
}
