<?php

namespace App\Filament\Resources\CmsMenuItems;

use App\Filament\Resources\CmsMenuItems\Pages\CreateCmsMenuItem;
use App\Filament\Resources\CmsMenuItems\Pages\EditCmsMenuItem;
use App\Filament\Resources\CmsMenuItems\Pages\ListCmsMenuItems;
use App\Filament\Resources\CmsMenuItems\Schemas\CmsMenuItemForm;
use App\Filament\Resources\CmsMenuItems\Tables\CmsMenuItemsTable;
use App\Filament\Resources\Concerns\HasPermissionAccess;
use App\Models\CmsMenuItem;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CmsMenuItemResource extends Resource
{
    use HasPermissionAccess;

    protected static ?string $model = CmsMenuItem::class;

    protected static string $permissionPrefix = 'cms_menu_item';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBars3;

    protected static ?int $navigationSort = 20;

    public static function form(Schema $schema): Schema
    {
        return CmsMenuItemForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CmsMenuItemsTable::configure($table);
    }

    public static function getNavigationLabel(): string
    {
        return __('resources.cms_menu_items.navigation');
    }

    public static function getModelLabel(): string
    {
        return __('resources.cms_menu_items.singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('resources.cms_menu_items.plural');
    }

    public static function getNavigationGroup(): string|\UnitEnum|null
    {
        return __('resources.groups.cms');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCmsMenuItems::route('/'),
            'create' => CreateCmsMenuItem::route('/create'),
            'edit' => EditCmsMenuItem::route('/{record}/edit'),
        ];
    }
}
