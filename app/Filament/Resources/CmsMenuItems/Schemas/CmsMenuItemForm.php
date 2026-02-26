<?php

namespace App\Filament\Resources\CmsMenuItems\Schemas;

use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class CmsMenuItemForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Grid::make(2)->schema([
                TextInput::make('label_en')->required()->maxLength(255),
                TextInput::make('label_uk')->required()->maxLength(255),
            ]),
            TextInput::make('url')->required()->maxLength(255),
            Toggle::make('is_active')->default(true),
        ]);
    }
}
