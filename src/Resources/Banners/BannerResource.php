<?php

declare(strict_types=1);

namespace Agenciafmd\Banners\Resources\Banners;

use Agenciafmd\Banners\Models\Banner;
use Agenciafmd\Banners\Resources\Banners\Pages\CreateBanner;
use Agenciafmd\Banners\Resources\Banners\Pages\EditBanner;
use Agenciafmd\Banners\Resources\Banners\Pages\ListBanners;
use Agenciafmd\Banners\Resources\Banners\Schemas\BannerForm;
use Agenciafmd\Banners\Resources\Banners\Tables\BannersTable;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Tapp\FilamentAuditing\RelationManagers\AuditsRelationManager;

final class BannerResource extends Resource
{
    protected static ?string $model = Banner::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::ComputerDesktop;

    protected static ?int $navigationSort = 4;

    protected static ?string $recordTitleAttribute = 'name';

    public static function getModelLabel(): string
    {
        return __('Banner');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Banners');
    }

    public static function form(Schema $schema): Schema
    {
        return BannerForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BannersTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            AuditsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListBanners::route('/'),
            'create' => CreateBanner::route('/create'),
            'edit' => EditBanner::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
