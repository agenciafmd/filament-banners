<?php

declare(strict_types=1);

namespace Agenciafmd\Banners\Resources\Banners\Schemas;

use Agenciafmd\Admix\Resources\Forms\Components\ImageUploadWithAutomaticallyResize;
use Agenciafmd\Admix\Resources\Forms\Components\VideoUploadWithDefault;
use Agenciafmd\Admix\Resources\Infolists\Components\DateTimeEntry;
use Agenciafmd\Banners\Enums\Meta;
use Agenciafmd\Banners\Services\BannerService;
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
                                        width: static fn (Get $get): int => self::file($get, 'desktop')['width'],
                                        height: static fn (Get $get): int => self::file($get, 'desktop')['height'],
                                    )
                                        ->visible(static fn (Get $get): bool => self::file($get, 'desktop')['visible'])
                                        ->required(),
                                    ImageUploadWithAutomaticallyResize::make(
                                        name: 'notebook',
                                        directory: 'banner/notebook',
                                        width: static fn (Get $get): int => self::file($get, 'notebook')['width'],
                                        height: static fn (Get $get): int => self::file($get, 'notebook')['height'],
                                    )
                                        ->visible(static fn (Get $get): bool => self::file($get, 'notebook')['visible'])
                                        ->required(),
                                    ImageUploadWithAutomaticallyResize::make(
                                        name: 'mobile',
                                        directory: 'banner/mobile',
                                        width: static fn (Get $get): int => self::file($get, 'mobile')['width'],
                                        height: static fn (Get $get): int => self::file($get, 'mobile')['height'],
                                    )
                                        ->visible(static fn (Get $get): bool => self::file($get, 'mobile')['visible'])
                                        ->required(),
                                    VideoUploadWithDefault::make(name: 'video', directory: 'banner/video')
                                        ->visible(static fn (Get $get): bool => self::file($get, 'video')['visible']),
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
                                ->schema(static fn (Get $get): array => BannerService::make()
                                    ->meta(self::location($get))
                                    ->map(static fn (array $field): TextInput|Select|Repeater => match ($field['type']) {
                                        Meta::TEXT => TextInput::make($field['name'])
                                            ->label($field['label']),
                                        Meta::SELECT => Select::make($field['name'])
                                            ->label($field['label'])
                                            ->options($field['options']),
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
                                    ->all())
                                ->collapsible()
                                ->columns()
                                ->columnSpan(2)
                                ->live()
                                ->visible(static fn (Get $get): bool => BannerService::make()
                                    ->meta(self::location($get))
                                    ->isNotEmpty()),
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
                                        ->formatStateUsing(static fn (string $state): string => BannerService::make()->locations()->get($state, $state))
                                        ->hiddenOn(Operation::Create),
                                ])
                                ->collapsible()
                                ->columns(),
                        ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    private static function location(Get $get): string
    {
        $location = $get('location');

        return is_string($location) ? $location : '';
    }

    /**
     * @return array{visible: bool, width: int, height: int, media: string}
     */
    private static function file(Get $get, string $name): array
    {
        return BannerService::make()->file(self::location($get), $name);
    }
}
