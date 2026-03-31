<?php

declare(strict_types=1);

namespace Agenciafmd\Banners\Resources\Banners\Tables;

use Agenciafmd\Banners\Services\BannerService;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

final class BannersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->translateLabel()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('published_at')
                    ->translateLabel()
                    ->dateTime(config('filament-admix.timestamp.format'))
                    ->sortable(),
                TextColumn::make('until_then')
                    ->translateLabel()
                    ->dateTime(config('filament-admix.timestamp.format'))
                    ->sortable(),
                ToggleColumn::make('star')
                    ->translateLabel()
                    ->sortable(),
                ToggleColumn::make('is_active')
                    ->translateLabel()
                    ->sortable(),
            ])
            ->filters([
                TernaryFilter::make('is_active')
                    ->translateLabel(),
                TernaryFilter::make('star')
                    ->translateLabel(),
                Filter::make('published_at')
                    ->schema([
                        DateTimePicker::make('published_at')
                            ->translateLabel(),
                        DateTimePicker::make('until_then')
                            ->translateLabel(),
                    ])
                    ->query(fn (Builder $query, array $data): Builder => $query
                        ->when(
                            $data['published_at'],
                            static fn (Builder $query, $date): Builder => $query->whereDate('published_at', '>=', $date),
                        )
                        ->when(
                            $data['until_then'],
                            static fn (Builder $query, $date): Builder => $query->whereDate('until_then', '<=', $date),
                        )),
                SelectFilter::make('location')
                    ->translateLabel()
                    ->options(BannerService::make()
                        ->locations()
                        ->toArray()),
                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ])
            ->defaultSort(fn (Builder $query): Builder => $query->orderBy('is_active', 'desc')
                ->orderBy('star', 'desc')
                ->latest('published_at')
                ->orderBy('name'));
    }
}
