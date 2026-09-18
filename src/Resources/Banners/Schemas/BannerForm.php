<?php

declare(strict_types=1);

namespace Agenciafmd\Banners\Resources\Banners\Schemas;

use Agenciafmd\Admix\Resources\Forms\Components\ImageUploadWithAutomaticallyResize;
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
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Operation;

final class BannerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(3)
                    ->schema([
                        Group::make([
                            Section::make(__('General'))
                                ->schema([
                                    Hidden::make('location'),
                                    TextInput::make('name')
                                        ->translateLabel()
                                        ->generateSlug()
                                        ->autofocus()
                                        ->minLength(3)
                                        ->maxLength(255)
                                        ->required(),
                                    TextInput::make('slug')
                                        ->translateLabel()
                                        ->unique()
                                        ->required(),
                                    ImageUploadWithAutomaticallyResize::make(
                                        name: 'desktop',
                                        directory: 'banner/desktop',
                                        width: static fn (Get $get) => config(sprintf('filament-banners.locations.%s.files.desktop.width', $get('location')), 1920),
                                        height: static fn (Get $get) => config(sprintf('filament-banners.locations.%s.files.desktop.height', $get('location')), 1080),
                                    )
                                        ->visible(static fn (Get $get) => config(sprintf('filament-banners.locations.%s.files.desktop.visible', $get('location')), false))
                                        ->required(),
                                    ImageUploadWithAutomaticallyResize::make(
                                        name: 'notebook',
                                        directory: 'banner/notebook',
                                        width: static fn (Get $get) => config(sprintf('filament-banners.locations.%s.files.notebook.width', $get('location')), 1440),
                                        height: static fn (Get $get) => config(sprintf('filament-banners.locations.%s.files.notebook.height', $get('location')), 810),
                                    )
                                        ->visible(static fn (Get $get) => config(sprintf('filament-banners.locations.%s.files.notebook.visible', $get('location')), false))
                                        ->required(),
                                    ImageUploadWithAutomaticallyResize::make(
                                        name: 'mobile',
                                        directory: 'banner/mobile',
                                        width: static fn (Get $get) => config(sprintf('filament-banners.locations.%s.files.mobile.width', $get('location')), 1440),
                                        height: static fn (Get $get) => config(sprintf('filament-banners.locations.%s.files.mobile.height', $get('location')), 810),
                                    )
                                        ->visible(static fn (Get $get) => config(sprintf('filament-banners.locations.%s.files.mobile.visible', $get('location')), false))
                                        ->required(),
                                    VideoUploadWithDefault::make(name: 'video', directory: 'banner/video')
                                        ->visible(static fn (Get $get) => config(sprintf('filament-banners.locations.%s.files.video.visible', $get('location')), false)),
                                    TextInput::make('link')
                                        ->translateLabel()
                                        ->url(),
                                    Select::make('target')
                                        ->translateLabel()
                                        ->options([
                                            '_self' => __('_self'),
                                            '_blank' => __('_blank'),
                                        ]),
                                ])
                                ->collapsible()
                                ->columns()
                                ->columnSpan(2),
                            Section::make(__('Additional fields'))
                                ->statePath('meta')
                                ->schema(fn (Get $get) => collect(config(sprintf('filament-banners.locations.%s.meta', $get('location')), []))
                                    ->map(fn (array $field): TextInput|Select|Repeater => match ($field['type']) {
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
                                    })
                                    ->toArray())
                                ->collapsible()
                                ->columns()
                                ->columnSpan(2)
                                ->live()
                                ->visible(static fn (Get $get) => config(sprintf('filament-banners.locations.%s.meta', $get('location')), false)),
                        ])
                            ->columnSpan(2),
                        Group::make([
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
                                        ->formatStateUsing(static fn (string $state) => config(sprintf('filament-banners.locations.%s.label', $state)))
                                        ->hiddenOn(Operation::Create),
                                ])
                                ->collapsible()
                                ->columns(),
                        ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
