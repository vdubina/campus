<?php

namespace App\Filament\Resources\CmsSpecializations\Pages;

use App\Filament\Resources\CmsSpecializations\CmsSpecializationResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCmsSpecialization extends CreateRecord
{
    protected static string $resource = CmsSpecializationResource::class;

    protected function afterCreate(): void
    {
        $this->record->syncImageCollectionFromPathColumn();
    }
}
