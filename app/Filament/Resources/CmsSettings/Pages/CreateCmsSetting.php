<?php

namespace App\Filament\Resources\CmsSettings\Pages;

use App\Filament\Resources\CmsSettings\CmsSettingResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCmsSetting extends CreateRecord
{
    protected static string $resource = CmsSettingResource::class;
}
