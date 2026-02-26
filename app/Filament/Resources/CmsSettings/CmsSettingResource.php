<?php

namespace App\Filament\Resources\CmsSettings;

use App\Filament\Resources\CmsSettings\Pages\CreateCmsSetting;
use App\Filament\Resources\CmsSettings\Pages\EditCmsSetting;
use App\Filament\Resources\CmsSettings\Pages\ListCmsSettings;
use App\Filament\Resources\CmsSettings\Schemas\CmsSettingForm;
use App\Filament\Resources\CmsSettings\Tables\CmsSettingsTable;
use App\Filament\Resources\Concerns\HasPermissionAccess;
use App\Models\CmsSetting;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CmsSettingResource extends Resource
{
    use HasPermissionAccess;

    protected static ?string $model = CmsSetting::class;

    protected static string $permissionPrefix = 'cms_setting';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static ?int $navigationSort = 10;

    public static function form(Schema $schema): Schema
    {
        return CmsSettingForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CmsSettingsTable::configure($table);
    }

    public static function getNavigationLabel(): string
    {
        return __('resources.cms_settings.navigation');
    }

    public static function getModelLabel(): string
    {
        return __('resources.cms_settings.singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('resources.cms_settings.plural');
    }

    public static function getNavigationGroup(): string|\UnitEnum|null
    {
        return __('resources.groups.cms');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCmsSettings::route('/'),
            'create' => CreateCmsSetting::route('/create'),
            'edit' => EditCmsSetting::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        if (! parent::canCreate()) {
            return false;
        }

        return CmsSetting::query()->count() === 0;
    }
}
