<?php

namespace App\Filament\Resources\CmsFooterLinks;

use App\Filament\Resources\Concerns\HasPermissionAccess;
use App\Filament\Resources\CmsFooterLinks\Pages\CreateCmsFooterLink;
use App\Filament\Resources\CmsFooterLinks\Pages\EditCmsFooterLink;
use App\Filament\Resources\CmsFooterLinks\Pages\ListCmsFooterLinks;
use App\Filament\Resources\CmsFooterLinks\Schemas\CmsFooterLinkForm;
use App\Filament\Resources\CmsFooterLinks\Tables\CmsFooterLinksTable;
use App\Models\CmsFooterLink;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CmsFooterLinkResource extends Resource
{
    use HasPermissionAccess;

    protected static ?string $model = CmsFooterLink::class;

    protected static string $permissionPrefix = 'cms_footer_link';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedLink;

    protected static ?int $navigationSort = 60;

    public static function form(Schema $schema): Schema
    {
        return CmsFooterLinkForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CmsFooterLinksTable::configure($table);
    }

    public static function getNavigationLabel(): string
    {
        return __('resources.cms_footer_links.navigation');
    }

    public static function getModelLabel(): string
    {
        return __('resources.cms_footer_links.singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('resources.cms_footer_links.plural');
    }

    public static function getNavigationGroup(): string|\UnitEnum|null
    {
        return __('resources.groups.cms');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCmsFooterLinks::route('/'),
            'create' => CreateCmsFooterLink::route('/create'),
            'edit' => EditCmsFooterLink::route('/{record}/edit'),
        ];
    }
}
