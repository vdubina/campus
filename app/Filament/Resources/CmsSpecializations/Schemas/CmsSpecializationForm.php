<?php

namespace App\Filament\Resources\CmsSpecializations\Schemas;

use AmidEsfahani\FilamentTinyEditor\TinyEditor;
use Filament\Forms\Components\BaseFileUpload;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Storage;

class CmsSpecializationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Grid::make(2)->schema([
                TextInput::make('title_en')->required()->maxLength(255),
                TextInput::make('title_uk')->required()->maxLength(255),
            ]),
            TinyEditor::make('description_en')->profile('simple')->columnSpanFull(),
            TinyEditor::make('description_uk')->profile('simple')->columnSpanFull(),
            FileUpload::make('image_path')
                ->formatStateUsing(static fn (?string $state): ?string => self::normalizeStoredPath($state))
                ->getUploadedFileUsing(static fn (BaseFileUpload $component, string $file): ?array => self::resolveUploadMeta($component, $file))
                ->disk('public')
                ->image()
                ->columnSpanFull(),
            Toggle::make('is_active')->default(true),
        ]);
    }

    private static function normalizeStoredPath(?string $state): ?string
    {
        if (! $state) {
            return null;
        }

        if (! str_starts_with($state, 'http://') && ! str_starts_with($state, 'https://')) {
            return $state;
        }

        $appUrl = rtrim((string) config('app.url'), '/');
        $storagePrefix = $appUrl.'/storage/';

        if ($appUrl !== '' && str_starts_with($state, $storagePrefix)) {
            return substr($state, strlen($storagePrefix));
        }

        return null;
    }

    private static function resolveUploadMeta(BaseFileUpload $component, string $file): ?array
    {
        $storage = Storage::disk($component->getDiskName());

        if (! $storage->exists($file)) {
            return null;
        }

        return [
            'name' => basename($file),
            'size' => $storage->size($file),
            'type' => $storage->mimeType($file),
            'url' => $storage->url($file),
        ];
    }
}
