<?php

namespace App\Filament\Resources\CmsPartnerItems\Pages;

use App\Filament\Resources\CmsPartnerItems\CmsPartnerItemResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCmsPartnerItem extends CreateRecord
{
    protected static string $resource = CmsPartnerItemResource::class;

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
