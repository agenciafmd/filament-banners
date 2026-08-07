<?php

declare(strict_types=1);

namespace Agenciafmd\Banners\Models;

use Agenciafmd\Banners\Database\Factories\BannerFactory;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Override;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

#[UseFactory(BannerFactory::class)]
final class Banner extends Model implements AuditableContract
{
    use Auditable;
    use HasFactory;
    use Prunable;
    use SoftDeletes;

    public function prunable(): Builder
    {
        return self::query()
            ->where('deleted_at', '<=', now()->subDays(30));
    }

    #[Scope]
    protected function isActive(Builder $query): void
    {
        $query->where('is_active', true)
            ->where(function ($query): void {
                $query->where('published_at', '<=', now())
                    ->orWhereNull('published_at');
            })
            ->where(function ($query): void {
                $query->where('until_then', '>=', now())
                    ->orWhereNull('until_then');
            });
    }

    #[Override]
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'star' => 'boolean',
            'meta' => 'array',
            'published_at' => 'timestamp',
            'until_then' => 'timestamp',
        ];
    }
}
