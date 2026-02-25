<?php

namespace App\Filament\Resources\Topics\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class TopicForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                Select::make('course_id')
                    ->relationship('course', 'title')
                    ->required()
                    ->searchable()
                    ->preload(),
                Toggle::make('is_published')
                    ->default(false),
                TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                TextInput::make('position')
                    ->required()
                    ->numeric()
                    ->minValue(1)
                    ->default(1),
                Textarea::make('description')
                    ->columnSpanFull(),
            ]);
    }
}
