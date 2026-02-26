<?php

namespace App\Filament\Resources\CmsPages\Schemas;

use AmidEsfahani\FilamentTinyEditor\TinyEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class CmsPageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('slug')
                    ->required()
                    ->maxLength(120)
                    ->unique(ignoreRecord: true),
                TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Toggle::make('is_active')
                    ->label(__('validation.attributes.active'))
                    ->default(true),
                TextInput::make('meta_title')
                    ->maxLength(255)
                    ->columnSpanFull(),
                TinyEditor::make('meta_description')
                    ->profile('simple')
                    ->columnSpanFull(),
                TinyEditor::make('html_content')
                    ->profile('full')
                    ->columnSpanFull()
                    ->helperText('HTML content served for this page slug.'),
                TinyEditor::make('custom_css')
                    ->profile('minimal')
                    ->columnSpanFull(),
                TinyEditor::make('custom_js')
                    ->profile('minimal')
                    ->columnSpanFull(),
            ]);
    }
}
