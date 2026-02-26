<?php

namespace App\Filament\Resources\CmsFooterLinks\Pages;

use App\Filament\Resources\CmsFooterLinks\CmsFooterLinkResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCmsFooterLink extends EditRecord
{
    protected static string $resource = CmsFooterLinkResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }
}
