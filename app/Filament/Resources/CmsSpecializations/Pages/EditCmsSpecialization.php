<?php

namespace App\Filament\Resources\CmsSpecializations\Pages;

use App\Filament\Resources\CmsSpecializations\CmsSpecializationResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCmsSpecialization extends EditRecord
{
    protected static string $resource = CmsSpecializationResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }

    protected function afterSave(): void
    {
        $this->record->syncImageCollectionFromPathColumn();
    }
}
