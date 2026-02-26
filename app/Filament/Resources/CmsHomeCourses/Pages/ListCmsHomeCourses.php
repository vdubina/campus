<?php

namespace App\Filament\Resources\CmsHomeCourses\Pages;

use App\Filament\Resources\CmsHomeCourses\CmsHomeCourseResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCmsHomeCourses extends ListRecords
{
    protected static string $resource = CmsHomeCourseResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
