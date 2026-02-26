<?php

namespace App\Filament\Resources\CmsPartnerItems\Pages;

use App\Filament\Resources\CmsPartnerItems\CmsPartnerItemResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCmsPartnerItems extends ListRecords
{
    protected static string $resource = CmsPartnerItemResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
