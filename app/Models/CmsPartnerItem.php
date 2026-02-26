<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class CmsPartnerItem extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'title_en',
        'title_uk',
        'logo_path',
        'link_url',
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
        $this->addMediaCollection('logo')->singleFile();
    }

    public function getLogoUrlAttribute(): ?string
    {
        return $this->getFirstMediaUrl('logo') ?: $this->resolveLegacyPath($this->getRawOriginal('logo_path'));
    }

    public function getLogoPathAttribute(?string $value): ?string
    {
        if ($value && ! str_starts_with($value, 'http://') && ! str_starts_with($value, 'https://') && Storage::disk('public')->exists($value)) {
            return $value;
        }

        return $this->getFirstMedia('logo')?->getPathRelativeToRoot();
    }

    public function syncLogoCollectionFromPathColumn(): void
    {
        $path = $this->getRawOriginal('logo_path');

        if (! $path) {
            $this->clearMediaCollection('logo');

            return;
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return;
        }

        if (! Storage::disk('public')->exists($path)) {
            return;
        }

        $media = $this->getFirstMedia('logo');

        if ($media && $media->getPathRelativeToRoot() === $path) {
            return;
        }

        $this->addMediaFromDisk($path, 'public')->toMediaCollection('logo', 'public');
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
