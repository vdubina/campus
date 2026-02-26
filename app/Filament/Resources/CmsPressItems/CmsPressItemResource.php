<?php

namespace App\Filament\Resources\CmsPressItems;

use App\Filament\Resources\Concerns\HasPermissionAccess;
use App\Filament\Resources\CmsPressItems\Pages\CreateCmsPressItem;
use App\Filament\Resources\CmsPressItems\Pages\EditCmsPressItem;
use App\Filament\Resources\CmsPressItems\Pages\ListCmsPressItems;
use App\Filament\Resources\CmsPressItems\Schemas\CmsPressItemForm;
use App\Filament\Resources\CmsPressItems\Tables\CmsPressItemsTable;
use App\Models\CmsPressItem;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CmsPressItemResource extends Resource
{
    use HasPermissionAccess;

    protected static ?string $model = CmsPressItem::class;

    protected static string $permissionPrefix = 'cms_press_item';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedNewspaper;

    protected static ?int $navigationSort = 40;

    public static function form(Schema $schema): Schema
    {
        return CmsPressItemForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CmsPressItemsTable::configure($table);
    }

    public static function getNavigationLabel(): string
    {
        return __('resources.cms_press_items.navigation');
    }

    public static function getModelLabel(): string
    {
        return __('resources.cms_press_items.singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('resources.cms_press_items.plural');
    }

    public static function getNavigationGroup(): string|\UnitEnum|null
    {
        return __('resources.groups.cms');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCmsPressItems::route('/'),
            'create' => CreateCmsPressItem::route('/create'),
            'edit' => EditCmsPressItem::route('/{record}/edit'),
        ];
    }
}
