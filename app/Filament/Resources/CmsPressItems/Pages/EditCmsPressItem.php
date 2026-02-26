<?php

namespace App\Filament\Resources\CmsPressItems\Pages;

use App\Filament\Resources\CmsPressItems\CmsPressItemResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCmsPressItem extends EditRecord
{
    protected static string $resource = CmsPressItemResource::class;

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
