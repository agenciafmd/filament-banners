<?php

declare(strict_types=1);

namespace Agenciafmd\Banners\Resources\Banners\Schemas;

use Agenciafmd\Admix\Resources\Forms\Components\ImageUploadWithDefault;
use Agenciafmd\Admix\Resources\Forms\Components\VideoUploadWithDefault;
use Agenciafmd\Admix\Resources\Infolists\Components\DateTimeEntry;
use Agenciafmd\Banners\Enums\Meta;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Repeater\TableColumn;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Operation;

final class BannerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('General'))
                    ->schema([
                        Hidden::make('location'),
                        TextInput::make('name')
                            ->translateLabel()
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (Get $get, Set $set, ?string $old, ?string $state) {
                                if (($get('slug') ?? '') !== str($old)
                                    ->slug()
                                    ->toString()) {
                                    return;
                                }

                                $set('slug', str($state)
                                    ->slug()
                                    ->toString());
                            })
                            ->autofocus()
                            ->minLength(3)
                            ->maxLength(255)
                            ->required(),
                        TextInput::make('slug')
                            ->translateLabel()
                            ->unique()
                            ->required(),
                        ImageUploadWithDefault::make(name: 'desktop', directory: 'banner/desktop')
                            ->afterLabel(fn (Get $get) => 'Max. ' . config("filament-banners.locations.{$get('location')}.files.desktop.width", 1920) . 'x' . config("filament-banners.locations.{$get('location')}.files.desktop.height", 1080))
                            ->imageEditorAspectRatioOptions(fn (Get $get) => config("filament-banners.locations.{$get('location')}.files.desktop.ratio", ['16:9']))
                            ->imageEditorViewportWidth(fn (Get $get) => config("filament-banners.locations.{$get('location')}.files.desktop.width", 1920))
                            ->imageEditorViewportHeight(fn (Get $get) => config("filament-banners.locations.{$get('location')}.files.desktop.height", 1080))
                            ->visible(fn (Get $get) => config("filament-banners.locations.{$get('location')}.files.desktop.visible", false))
                            ->required(),
                        ImageUploadWithDefault::make(name: 'notebook', directory: 'banner/notebook')
                            ->afterLabel(fn (Get $get) => 'Max. ' . config("filament-banners.locations.{$get('location')}.files.notebook.width", 1920) . 'x' . config("filament-banners.locations.{$get('location')}.files.notebook.height", 1080))
                            ->imageEditorAspectRatioOptions(fn (Get $get) => config("filament-banners.locations.{$get('location')}.files.notebook.ratio", ['16:9']))
                            ->imageEditorViewportWidth(fn (Get $get) => config("filament-banners.locations.{$get('location')}.files.notebook.width", 1920))
                            ->imageEditorViewportHeight(fn (Get $get) => config("filament-banners.locations.{$get('location')}.files.notebook.height", 1080))
                            ->visible(fn (Get $get) => config("filament-banners.locations.{$get('location')}.files.notebook.visible", false))
                            ->required(),
                        ImageUploadWithDefault::make(name: 'mobile', directory: 'banner/mobile')
                            ->afterLabel(fn (Get $get) => 'Max. ' . config("filament-banners.locations.{$get('location')}.files.mobile.width", 1920) . 'x' . config("filament-banners.locations.{$get('location')}.files.mobile.height", 1080))
                            ->imageEditorAspectRatioOptions(fn (Get $get) => config("filament-banners.locations.{$get('location')}.files.mobile.ratio", ['9:16']))
                            ->imageEditorViewportWidth(fn (Get $get) => config("filament-banners.locations.{$get('location')}.files.mobile.width", 1920))
                            ->imageEditorViewportHeight(fn (Get $get) => config("filament-banners.locations.{$get('location')}.files.mobile.height", 1080))
                            ->visible(fn (Get $get) => config("filament-banners.locations.{$get('location')}.files.mobile.visible", false))
                            ->required(),
                        VideoUploadWithDefault::make(name: 'video', directory: 'banner/video')
                            ->visible(fn (Get $get) => config("filament-banners.locations.{$get('location')}.files.video.visible", false)),
                        TextInput::make('link')
                            ->translateLabel()
                            ->url(),
                        Select::make('target')
                            ->translateLabel()
                            ->options([
                                '_self' => __('_self'),
                                '_blank' => __('_blank'),
                            ]),
                        Section::make(__('Additional fields'))
                            ->statePath('meta')
                            ->schema(function (Get $get) {
                                return collect(config("filament-banners.locations.{$get('location')}.meta", []))
                                    ->map(function ($field) {
                                        return match ($field['type']) {
                                            Meta::TEXT => TextInput::make($field['name'])
                                                ->label($field['label']),
                                            Meta::SELECT => Select::make($field['name'])
                                                ->label($field['label'])
                                                ->options($field['options'] ?? []),
                                            Meta::REPEATER => Repeater::make($field['name'])
                                                ->label($field['label'])
                                                ->table([
                                                    TableColumn::make($field['label']),
                                                ])
                                                ->schema([
                                                    TextInput::make('name')
                                                        ->label($field['label'])
                                                        ->required(),
                                                ])
                                                ->compact()
                                                ->columnSpanFull(),
                                        };
                                    })
                                    ->toArray();
                            })
                            ->collapsible()
                            ->columns()
                            ->columnSpanFull()
                            ->live()
                            ->visible(fn(Get $get) => config("filament-banners.locations.{$get('location')}.meta", false)),
                    ])
                    ->collapsible()
                    ->columns()
                    ->columnSpan(2),
                Section::make(__('Information'))
                    ->schema([
                        Toggle::make('is_active')
                            ->translateLabel()
                            ->default(true),
                        Toggle::make('star')
                            ->translateLabel()
                            ->default(false),
                        DateTimePicker::make('published_at')
                            ->translateLabel(),
                        DateTimePicker::make('until_then')
                            ->translateLabel(),
                        DateTimeEntry::make('created_at'),
                        DateTimeEntry::make('updated_at'),
                        TextEntry::make('location')
                            ->translateLabel()
                            ->formatStateUsing(fn ($state) => config("filament-banners.locations.{$state}.label"))
                            ->hiddenOn(Operation::Create),
                    ])
                    ->collapsible()
                    ->columns(),
            ])
            ->columns(3);
    }
}
