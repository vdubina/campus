<?php

namespace App\Filament\Resources\CmsSettings\Schemas;

use AmidEsfahani\FilamentTinyEditor\TinyEditor;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;

class CmsSettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('home_settings')
                    ->tabs([
                        Tabs\Tab::make('Branding')->schema([
                            TextInput::make('site_name')->required()->maxLength(255),
                            Grid::make(2)->schema([
                                Placeholder::make('logo_light_preview')
                                    ->label('Current light logo')
                                    ->content(fn ($record): HtmlString => self::renderPreview($record?->logo_light_url)),
                                Placeholder::make('logo_dark_preview')
                                    ->label('Current dark logo')
                                    ->content(fn ($record): HtmlString => self::renderPreview($record?->logo_dark_url)),
                                FileUpload::make('logo_light_path')
                                    ->disk('public')
                                    ->directory('cms')
                                    ->afterStateHydrated(static fn (FileUpload $component): mixed => $component->state(null))
                                    ->dehydrated(static fn (mixed $state): bool => filled($state))
                                    ->image(),
                                FileUpload::make('logo_dark_path')
                                    ->disk('public')
                                    ->directory('cms')
                                    ->afterStateHydrated(static fn (FileUpload $component): mixed => $component->state(null))
                                    ->dehydrated(static fn (mixed $state): bool => filled($state))
                                    ->image(),
                                Toggle::make('remove_logo_light')
                                    ->label('Remove light logo')
                                    ->dehydrated(false)
                                    ->default(false),
                                Toggle::make('remove_logo_dark')
                                    ->label('Remove dark logo')
                                    ->dehydrated(false)
                                    ->default(false),
                            ])->columnSpanFull(),
                        ]),
                        Tabs\Tab::make('Who & Mission')->schema([
                            Grid::make(2)->schema([
                                TextInput::make('hero_title_en')->maxLength(255),
                                TextInput::make('hero_title_uk')->maxLength(255),
                            ]),
                            Grid::make(2)->schema([
                                TinyEditor::make('hero_subtitle_en')->profile('simple')->columnSpanFull(),
                                TinyEditor::make('hero_subtitle_uk')->profile('simple')->columnSpanFull(),
                            ]),
                            Grid::make(2)->schema([
                                TextInput::make('hero_cta_label_en')->maxLength(255),
                                TextInput::make('hero_cta_label_uk')->maxLength(255),
                            ]),
                            Grid::make(2)->schema([
                                TextInput::make('hero_stats_label_en')->maxLength(255),
                                TextInput::make('hero_stats_label_uk')->maxLength(255),
                            ]),
                            TextInput::make('hero_cta_url')->maxLength(255),
                            Grid::make(2)->schema([
                                TextInput::make('who_title_en')->maxLength(255),
                                TextInput::make('who_title_uk')->maxLength(255),
                            ]),
                            TinyEditor::make('who_text_en')->profile('simple')->columnSpanFull(),
                            TinyEditor::make('who_text_uk')->profile('simple')->columnSpanFull(),
                            Grid::make(2)->schema([
                                TextInput::make('mission_title_en')->maxLength(255),
                                TextInput::make('mission_title_uk')->maxLength(255),
                            ]),
                            TinyEditor::make('mission_text_en')->profile('simple')->columnSpanFull(),
                            TinyEditor::make('mission_text_uk')->profile('simple')->columnSpanFull(),
                        ]),
                        Tabs\Tab::make('Section Titles')->schema([
                            Grid::make(2)->schema([
                                TextInput::make('specializations_title_en')->maxLength(255),
                                TextInput::make('specializations_title_uk')->maxLength(255),
                            ]),
                            Grid::make(2)->schema([
                                TextInput::make('press_title_en')->maxLength(255),
                                TextInput::make('press_title_uk')->maxLength(255),
                            ]),
                            Grid::make(2)->schema([
                                TextInput::make('partners_title_en')->maxLength(255),
                                TextInput::make('partners_title_uk')->maxLength(255),
                            ]),
                        ]),
                        Tabs\Tab::make('Footer')->schema([
                            Grid::make(2)->schema([
                                TextInput::make('footer_title_en')->maxLength(255),
                                TextInput::make('footer_title_uk')->maxLength(255),
                            ]),
                            TinyEditor::make('footer_text_en')->profile('simple')->columnSpanFull(),
                            TinyEditor::make('footer_text_uk')->profile('simple')->columnSpanFull(),
                            TextInput::make('footer_email')->email()->maxLength(255),
                            TextInput::make('footer_phone')->maxLength(255),
                            Grid::make(2)->schema([
                                TextInput::make('footer_address_en')->maxLength(255),
                                TextInput::make('footer_address_uk')->maxLength(255),
                            ]),
                        ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    private static function renderPreview(?string $url): HtmlString
    {
        if (! $url) {
            return new HtmlString('<span style="opacity:.7">No image uploaded</span>');
        }

        $escapedUrl = e($url);

        return new HtmlString("<img src=\"{$escapedUrl}\" alt=\"Logo preview\" style=\"display:block; width:auto !important; max-width:320px; max-height:80px; height:auto; border-radius:8px;\" />");
    }
}
