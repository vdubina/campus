<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class CmsSetting extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'site_name',
        'logo_light_path',
        'logo_dark_path',
        'hero_title_en',
        'hero_title_uk',
        'hero_subtitle_en',
        'hero_subtitle_uk',
        'hero_cta_label_en',
        'hero_cta_label_uk',
        'hero_cta_url',
        'hero_stats_label_en',
        'hero_stats_label_uk',
        'who_title_en',
        'who_title_uk',
        'who_text_en',
        'who_text_uk',
        'mission_title_en',
        'mission_title_uk',
        'mission_text_en',
        'mission_text_uk',
        'specializations_title_en',
        'specializations_title_uk',
        'press_title_en',
        'press_title_uk',
        'partners_title_en',
        'partners_title_uk',
        'footer_title_en',
        'footer_title_uk',
        'footer_text_en',
        'footer_text_uk',
        'footer_email',
        'footer_phone',
        'footer_address_en',
        'footer_address_uk',
    ];

    public static function singleton(): self
    {
        return self::query()->firstOrCreate([], [
            'site_name' => 'Campus CRM',
        ]);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('logo_light')->singleFile();
        $this->addMediaCollection('logo_dark')->singleFile();
    }

    public function getLogoLightUrlAttribute(): ?string
    {
        return $this->resolveLegacyPath($this->getRawOriginal('logo_light_path'))
            ?: $this->getFirstMediaUrl('logo_light');
    }

    public function getLogoDarkUrlAttribute(): ?string
    {
        return $this->resolveLegacyPath($this->getRawOriginal('logo_dark_path'))
            ?: $this->getFirstMediaUrl('logo_dark');
    }

    public function getLogoLightPathAttribute(?string $value): ?string
    {
        return $this->resolveEditablePath($value);
    }

    public function getLogoDarkPathAttribute(?string $value): ?string
    {
        return $this->resolveEditablePath($value);
    }

    private function resolveLegacyPath(?string $path): ?string
    {
        if (! $path) {
            return null;
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        if (! Storage::disk('public')->exists($path)) {
            return null;
        }

        return url('/storage/'.$path);
    }

    private function resolveEditablePath(?string $path): ?string
    {
        if ($path && ! str_starts_with($path, 'http://') && ! str_starts_with($path, 'https://') && Storage::disk('public')->exists($path)) {
            return $path;
        }

        return null;
    }
}
