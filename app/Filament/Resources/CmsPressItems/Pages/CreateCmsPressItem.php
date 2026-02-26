<?php

namespace App\Filament\Resources\CmsPressItems\Pages;

use App\Filament\Resources\CmsPressItems\CmsPressItemResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCmsPressItem extends CreateRecord
{
    protected static string $resource = CmsPressItemResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        unset($data['remove_logo']);

        return $data;
    }

    protected function afterCreate(): void
    {
        $this->record->syncLogoCollectionFromPathColumn();
    }
}
