<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class CmsSpecialization extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'title_en',
        'title_uk',
        'description_en',
        'description_uk',
        'image_path',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'bool',
        ];
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')->singleFile();
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->getFirstMediaUrl('image') ?: $this->resolveLegacyPath($this->getRawOriginal('image_path'));
    }

    public function getImagePathAttribute(?string $value): ?string
    {
        if ($value && ! str_starts_with($value, 'http://') && ! str_starts_with($value, 'https://') && Storage::disk('public')->exists($value)) {
            return $value;
        }

        return $this->getFirstMedia('image')?->getPathRelativeToRoot();
    }

    public function syncImageCollectionFromPathColumn(): void
    {
        $path = $this->getRawOriginal('image_path');

        if (! $path) {
            $this->clearMediaCollection('image');

            return;
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return;
        }

        if (! Storage::disk('public')->exists($path)) {
            return;
        }

        $media = $this->getFirstMedia('image');

        if ($media && $media->getPathRelativeToRoot() === $path) {
            return;
        }

        $this->addMediaFromDisk($path, 'public')->toMediaCollection('image', 'public');
    }

    private function resolveLegacyPath(?string $path): ?string
    {
        if (! $path) {
            return null;
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        return url('/storage/'.$path);
    }
}
