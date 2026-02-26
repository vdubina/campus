<?php

namespace App\Filament\Resources\CmsPartnerItems;

use App\Filament\Resources\Concerns\HasPermissionAccess;
use App\Filament\Resources\CmsPartnerItems\Pages\CreateCmsPartnerItem;
use App\Filament\Resources\CmsPartnerItems\Pages\EditCmsPartnerItem;
use App\Filament\Resources\CmsPartnerItems\Pages\ListCmsPartnerItems;
use App\Filament\Resources\CmsPartnerItems\Schemas\CmsPartnerItemForm;
use App\Filament\Resources\CmsPartnerItems\Tables\CmsPartnerItemsTable;
use App\Models\CmsPartnerItem;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CmsPartnerItemResource extends Resource
{
    use HasPermissionAccess;

    protected static ?string $model = CmsPartnerItem::class;

    protected static string $permissionPrefix = 'cms_partner_item';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    protected static ?int $navigationSort = 50;

    public static function form(Schema $schema): Schema
    {
        return CmsPartnerItemForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CmsPartnerItemsTable::configure($table);
    }

    public static function getNavigationLabel(): string
    {
        return __('resources.cms_partner_items.navigation');
    }

    public static function getModelLabel(): string
    {
        return __('resources.cms_partner_items.singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('resources.cms_partner_items.plural');
    }

    public static function getNavigationGroup(): string|\UnitEnum|null
    {
        return __('resources.groups.cms');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCmsPartnerItems::route('/'),
            'create' => CreateCmsPartnerItem::route('/create'),
            'edit' => EditCmsPartnerItem::route('/{record}/edit'),
        ];
    }
}
