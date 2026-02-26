<?php

namespace App\Filament\Resources\CmsPartnerItems\Pages;

use App\Filament\Resources\CmsPartnerItems\CmsPartnerItemResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCmsPartnerItem extends EditRecord
{
    protected static string $resource = CmsPartnerItemResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (($data['remove_logo'] ?? false) === true) {
            $data['logo_path'] = null;
        }

        unset($data['remove_logo']);

        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }

    protected function afterSave(): void
    {
        $this->record->syncLogoCollectionFromPathColumn();
    }
}
