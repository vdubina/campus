<?php

namespace App\Filament\Resources\CmsSettings\Pages;

use App\Filament\Resources\CmsSettings\CmsSettingResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCmsSetting extends EditRecord
{
    protected static string $resource = CmsSettingResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (($data['remove_logo_light'] ?? false) === true) {
            $data['logo_light_path'] = null;
        }

        if (($data['remove_logo_dark'] ?? false) === true) {
            $data['logo_dark_path'] = null;
        }

        unset($data['remove_logo_light'], $data['remove_logo_dark']);

        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
