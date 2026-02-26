<?php

namespace App\Filament\Resources\CmsPressItems\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;

class CmsPressItemForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Grid::make(2)->schema([
                TextInput::make('title_en')->required()->maxLength(255),
                TextInput::make('title_uk')->required()->maxLength(255),
            ]),
            Placeholder::make('logo_preview')
                ->label('Current logo')
                ->content(fn ($record): HtmlString => self::renderPreview($record?->logo_url))
                ->columnSpanFull(),
            FileUpload::make('logo_path')
                ->disk('public')
                ->directory('cms')
                ->afterStateHydrated(static fn (FileUpload $component): mixed => $component->state(null))
                ->image()
                ->columnSpanFull(),
            Toggle::make('remove_logo')
                ->label('Remove logo')
                ->dehydrated(false)
                ->default(false),
            TextInput::make('link_url')->url()->maxLength(255),
            Toggle::make('is_active')->default(true),
        ]);
    }

    private static function renderPreview(?string $url): HtmlString
    {
        if (! $url) {
            return new HtmlString('<span style="opacity:.7">No image uploaded</span>');
        }

        $escapedUrl = e($url);

        return new HtmlString("<div style=\"display:inline-flex; align-items:center; justify-content:center; min-height:96px; padding:12px 16px; background:#111827; border-radius:10px;\"><img src=\"{$escapedUrl}\" alt=\"Logo preview\" style=\"display:block; width:auto !important; max-width:320px; max-height:80px; height:auto; border-radius:6px;\" /></div>");
    }
}
