<?php

namespace App\Filament\Resources\CmsHomeCourses\Pages;

use App\Filament\Resources\CmsHomeCourses\CmsHomeCourseResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCmsHomeCourse extends EditRecord
{
    protected static string $resource = CmsHomeCourseResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (($data['remove_image'] ?? false) === true) {
            $data['image_path'] = null;
        }

        unset($data['remove_image']);

        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }
}
