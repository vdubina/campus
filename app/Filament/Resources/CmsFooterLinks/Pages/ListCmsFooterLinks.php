<?php

namespace App\Filament\Resources\CmsFooterLinks\Pages;

use App\Filament\Resources\CmsFooterLinks\CmsFooterLinkResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCmsFooterLinks extends ListRecords
{
    protected static string $resource = CmsFooterLinkResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
