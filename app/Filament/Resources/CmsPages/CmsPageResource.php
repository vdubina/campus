<?php

namespace App\Filament\Resources\CmsPages;

use App\Filament\Resources\CmsPages\Pages\CreateCmsPage;
use App\Filament\Resources\CmsPages\Pages\EditCmsPage;
use App\Filament\Resources\CmsPages\Pages\ListCmsPages;
use App\Filament\Resources\CmsPages\Schemas\CmsPageForm;
use App\Filament\Resources\CmsPages\Tables\CmsPagesTable;
use App\Models\CmsPage;
use App\Models\User;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CmsPageResource extends Resource
{
    protected static ?string $model = CmsPage::class;

    protected static bool $shouldRegisterNavigation = false;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    protected static ?int $navigationSort = 90;

    public static function form(Schema $schema): Schema
    {
        return CmsPageForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CmsPagesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
        ];
    }

    public static function getNavigationLabel(): string
    {
        return __('resources.cms_pages.navigation');
    }

    public static function getModelLabel(): string
    {
        return __('resources.cms_pages.singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('resources.cms_pages.plural');
    }

    public static function getNavigationGroup(): string|\UnitEnum|null
    {
        return __('resources.groups.cms');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCmsPages::route('/'),
            'create' => CreateCmsPage::route('/create'),
            'edit' => EditCmsPage::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        return self::userHasPermission('cms_page.view');
    }

    public static function canCreate(): bool
    {
        return self::userHasPermission('cms_page.create');
    }

    public static function canEdit($record): bool
    {
        return self::userHasPermission('cms_page.update');
    }

    public static function canDelete($record): bool
    {
        return self::userHasPermission('cms_page.delete');
    }

    private static function userHasPermission(string $permission): bool
    {
        /** @var User|null $user */
        $user = auth()->user();

        return (bool) $user?->getAllPermissions()->contains('name', $permission);
    }
}
