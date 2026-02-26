<?php

namespace App\Filament\Resources\CmsSpecializations;

use App\Filament\Resources\Concerns\HasPermissionAccess;
use App\Filament\Resources\CmsSpecializations\Pages\CreateCmsSpecialization;
use App\Filament\Resources\CmsSpecializations\Pages\EditCmsSpecialization;
use App\Filament\Resources\CmsSpecializations\Pages\ListCmsSpecializations;
use App\Filament\Resources\CmsSpecializations\Schemas\CmsSpecializationForm;
use App\Filament\Resources\CmsSpecializations\Tables\CmsSpecializationsTable;
use App\Models\CmsSpecialization;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CmsSpecializationResource extends Resource
{
    use HasPermissionAccess;

    protected static ?string $model = CmsSpecialization::class;

    protected static string $permissionPrefix = 'cms_specialization';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSparkles;

    protected static ?int $navigationSort = 30;

    public static function form(Schema $schema): Schema
    {
        return CmsSpecializationForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CmsSpecializationsTable::configure($table);
    }

    public static function getNavigationLabel(): string
    {
        return __('resources.cms_specializations.navigation');
    }

    public static function getModelLabel(): string
    {
        return __('resources.cms_specializations.singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('resources.cms_specializations.plural');
    }

    public static function getNavigationGroup(): string|\UnitEnum|null
    {
        return __('resources.groups.cms');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCmsSpecializations::route('/'),
            'create' => CreateCmsSpecialization::route('/create'),
            'edit' => EditCmsSpecialization::route('/{record}/edit'),
        ];
    }
}
