<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CmsFooterLink;
use App\Models\CmsHomeCourse;
use App\Models\CmsMenuItem;
use App\Models\CmsPartnerItem;
use App\Models\CmsPressItem;
use App\Models\CmsSetting;
use App\Models\CmsSpecialization;
use App\Models\Certification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HomeCmsController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $locale = $request->get('locale', app()->getLocale());
        $isUk = $locale === 'uk';

        $settings = CmsSetting::singleton();
        $issuedCertificationsCount = Certification::query()
            ->where('status', 'issued')
            ->count();

        $menu = CmsMenuItem::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get()
            ->map(fn (CmsMenuItem $item): array => [
                'id' => $item->id,
                'label' => $isUk ? $item->label_uk : $item->label_en,
                'label_en' => $item->label_en,
                'label_uk' => $item->label_uk,
                'url' => $item->url,
            ])
            ->values();

        $courses = CmsHomeCourse::query()
            ->where('is_active', true)
            ->with('course')
            ->orderBy('sort_order')
            ->get()
            ->map(fn (CmsHomeCourse $item): array => [
                'id' => $item->course?->id,
                'title' => $isUk
                    ? ($item->title_uk ?: $item->course?->title)
                    : ($item->title_en ?: $item->title_uk ?: $item->course?->title),
                'title_en' => $item->title_en,
                'title_uk' => $item->title_uk,
                'description' => ($isUk ? $item->description_uk : $item->description_en) ?: $item->course?->description,
                'image_url' => $item->image_url,
            ])
            ->filter(fn (array $item): bool => ! empty($item['id']))
            ->values();

        $specializations = CmsSpecialization::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get()
            ->map(fn (CmsSpecialization $item): array => [
                'id' => $item->id,
                'title' => $isUk ? $item->title_uk : $item->title_en,
                'title_en' => $item->title_en,
                'title_uk' => $item->title_uk,
                'description' => $isUk ? $item->description_uk : $item->description_en,
                'image_url' => $item->image_url,
            ])
            ->values();

        $press = CmsPressItem::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get()
            ->map(fn (CmsPressItem $item): array => [
                'id' => $item->id,
                'title' => $isUk ? $item->title_uk : $item->title_en,
                'title_en' => $item->title_en,
                'title_uk' => $item->title_uk,
                'logo_url' => $item->logo_url,
                'link_url' => $item->link_url,
            ])
            ->values();

        $partners = CmsPartnerItem::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get()
            ->map(fn (CmsPartnerItem $item): array => [
                'id' => $item->id,
                'title' => $isUk ? $item->title_uk : $item->title_en,
                'title_en' => $item->title_en,
                'title_uk' => $item->title_uk,
                'logo_url' => $item->logo_url,
                'link_url' => $item->link_url,
            ])
            ->values();

        $footerLinks = CmsFooterLink::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get()
            ->map(fn (CmsFooterLink $item): array => [
                'id' => $item->id,
                'label' => $isUk ? $item->label_uk : $item->label_en,
                'label_en' => $item->label_en,
                'label_uk' => $item->label_uk,
                'url' => $item->url,
            ])
            ->values();

        return response()->json([
            'site_name' => $settings->site_name,
            'logo_light_url' => $settings->logo_light_url,
            'logo_dark_url' => $settings->logo_dark_url,
            'hero_section' => [
                'title' => $isUk ? $settings->hero_title_uk : $settings->hero_title_en,
                'subtitle' => $isUk ? $settings->hero_subtitle_uk : $settings->hero_subtitle_en,
                'cta_label' => $isUk ? $settings->hero_cta_label_uk : $settings->hero_cta_label_en,
                'cta_url' => $settings->hero_cta_url,
                'stats_count' => $issuedCertificationsCount,
                'stats_label' => $isUk ? $settings->hero_stats_label_uk : $settings->hero_stats_label_en,
            ],
            'menu' => $menu,
            'who' => [
                'title' => $isUk ? $settings->who_title_uk : $settings->who_title_en,
                'text' => $isUk ? $settings->who_text_uk : $settings->who_text_en,
            ],
            'mission' => [
                'title' => $isUk ? $settings->mission_title_uk : $settings->mission_title_en,
                'text' => $isUk ? $settings->mission_text_uk : $settings->mission_text_en,
            ],
            'courses_section' => [
                'items' => $courses,
            ],
            'specializations_section' => [
                'title' => $isUk ? $settings->specializations_title_uk : $settings->specializations_title_en,
                'items' => $specializations,
            ],
            'press_section' => [
                'title' => $isUk ? $settings->press_title_uk : $settings->press_title_en,
                'items' => $press,
            ],
            'partners_section' => [
                'title' => $isUk ? $settings->partners_title_uk : $settings->partners_title_en,
                'items' => $partners,
            ],
            'footer' => [
                'title' => $isUk ? $settings->footer_title_uk : $settings->footer_title_en,
                'text' => $isUk ? $settings->footer_text_uk : $settings->footer_text_en,
                'email' => $settings->footer_email,
                'phone' => $settings->footer_phone,
                'address' => $isUk ? $settings->footer_address_uk : $settings->footer_address_en,
                'links' => $footerLinks,
            ],
        ]);
    }
}
