<?php

namespace App\Filament\Resources\CmsSpecializations\Pages;

use App\Filament\Resources\CmsSpecializations\CmsSpecializationResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCmsSpecializations extends ListRecords
{
    protected static string $resource = CmsSpecializationResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
