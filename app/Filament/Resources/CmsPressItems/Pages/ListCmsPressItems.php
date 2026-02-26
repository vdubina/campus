<?php

namespace App\Filament\Resources\CmsPressItems\Pages;

use App\Filament\Resources\CmsPressItems\CmsPressItemResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCmsPressItems extends ListRecords
{
    protected static string $resource = CmsPressItemResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
