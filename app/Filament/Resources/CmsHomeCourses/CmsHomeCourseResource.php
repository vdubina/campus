<?php

namespace App\Filament\Resources\CmsHomeCourses;

use App\Filament\Resources\Concerns\HasPermissionAccess;
use App\Filament\Resources\CmsHomeCourses\Pages\CreateCmsHomeCourse;
use App\Filament\Resources\CmsHomeCourses\Pages\EditCmsHomeCourse;
use App\Filament\Resources\CmsHomeCourses\Pages\ListCmsHomeCourses;
use App\Filament\Resources\CmsHomeCourses\Schemas\CmsHomeCourseForm;
use App\Filament\Resources\CmsHomeCourses\Tables\CmsHomeCoursesTable;
use App\Models\CmsHomeCourse;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CmsHomeCourseResource extends Resource
{
    use HasPermissionAccess;

    protected static ?string $model = CmsHomeCourse::class;

    protected static string $permissionPrefix = 'cms_home_course';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedAcademicCap;

    protected static ?int $navigationSort = 70;

    public static function form(Schema $schema): Schema
    {
        return CmsHomeCourseForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CmsHomeCoursesTable::configure($table);
    }

    public static function getNavigationLabel(): string
    {
        return __('resources.cms_home_courses.navigation');
    }

    public static function getModelLabel(): string
    {
        return __('resources.cms_home_courses.singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('resources.cms_home_courses.plural');
    }

    public static function getNavigationGroup(): string|\UnitEnum|null
    {
        return __('resources.groups.cms');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCmsHomeCourses::route('/'),
            'create' => CreateCmsHomeCourse::route('/create'),
            'edit' => EditCmsHomeCourse::route('/{record}/edit'),
        ];
    }
}
