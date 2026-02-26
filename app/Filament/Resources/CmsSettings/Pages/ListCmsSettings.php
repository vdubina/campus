<?php

namespace App\Filament\Resources\CmsSettings\Pages;

use App\Filament\Resources\CmsSettings\CmsSettingResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCmsSettings extends ListRecords
{
    protected static string $resource = CmsSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->visible(fn (): bool => static::$resource::canCreate()),
        ];
    }
}
