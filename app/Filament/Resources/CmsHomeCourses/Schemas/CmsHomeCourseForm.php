<?php

namespace App\Filament\Resources\CmsHomeCourses\Schemas;

use AmidEsfahani\FilamentTinyEditor\TinyEditor;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;

class CmsHomeCourseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('course_id')
                ->relationship('course', 'title')
                ->searchable()
                ->preload()
                ->required(),
            Grid::make(2)->schema([
                TextInput::make('title_en')
                    ->label('Title (EN)')
                    ->maxLength(255),
                TextInput::make('title_uk')
                    ->label('Title (UK)')
                    ->maxLength(255),
            ]),
            Placeholder::make('image_preview')
                ->label('Current image')
                ->content(fn ($record): HtmlString => self::renderPreview($record?->image_url))
                ->columnSpanFull(),
            FileUpload::make('image_path')
                ->disk('public')
                ->directory('cms')
                ->afterStateHydrated(static fn (FileUpload $component): mixed => $component->state(null))
                ->dehydrated(static fn (mixed $state): bool => filled($state))
                ->image()
                ->columnSpanFull(),
            Toggle::make('remove_image')
                ->label('Remove image')
                ->dehydrated(false)
                ->default(false),
            Grid::make(2)->schema([
                TinyEditor::make('description_en')->profile('simple')->columnSpanFull(),
                TinyEditor::make('description_uk')->profile('simple')->columnSpanFull(),
            ]),
            Toggle::make('is_active')->default(true),
        ]);
    }

    private static function renderPreview(?string $url): HtmlString
    {
        if (! $url) {
            return new HtmlString('<span style="opacity:.7">No image uploaded</span>');
        }

        $escapedUrl = e($url);

        return new HtmlString("<img src=\"{$escapedUrl}\" alt=\"Image preview\" style=\"display:block; width:auto !important; max-width:420px; max-height:220px; height:auto; border-radius:8px;\" />");
    }
}
