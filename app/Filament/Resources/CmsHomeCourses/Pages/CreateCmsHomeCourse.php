<?php

namespace App\Filament\Resources\CmsHomeCourses\Pages;

use App\Filament\Resources\CmsHomeCourses\CmsHomeCourseResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCmsHomeCourse extends CreateRecord
{
    protected static string $resource = CmsHomeCourseResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        unset($data['remove_image']);

        return $data;
    }
}
