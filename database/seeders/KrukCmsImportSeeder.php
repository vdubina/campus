<?php

namespace Database\Seeders;

use App\Models\CmsFooterLink;
use App\Models\CmsHomeCourse;
use App\Models\CmsMenuItem;
use App\Models\CmsPartnerItem;
use App\Models\CmsPressItem;
use App\Models\CmsSetting;
use App\Models\CmsSpecialization;
use App\Models\Course;
use Illuminate\Database\Seeder;

class KrukCmsImportSeeder extends Seeder
{
    public function run(): void
    {
        $payload = json_decode(file_get_contents('/tmp/kruk-content.json'), true, flags: JSON_THROW_ON_ERROR);

        $settings = CmsSetting::singleton();
        $settings->fill([
            'site_name' => 'КРУК',
            'logo_light_path' => 'https://a.storyblok.com/f/174597/256x256/a8fce2a901/logo.png',
            'logo_dark_path' => 'https://a.storyblok.com/f/174597/256x256/a8fce2a901/logo.png',
            'who_title_en' => 'Who we are',
            'who_title_uk' => 'Хто ми',
            'who_text_en' => 'UAV operator training center "KRUK".',
            'who_text_uk' => 'Центр підготовки операторів БПЛА “КРУК”.',
            'mission_title_en' => 'Our mission',
            'mission_title_uk' => 'Наша місія',
            'mission_text_en' => 'We train operators of multirotor and wing-type UAVs according to certified programs.',
            'mission_text_uk' => 'Готуємо операторів мультикоптерів та БПЛА типу крило за сертифікованими програмами.',
            'specializations_title_en' => 'SPECIALIZATIONS',
            'specializations_title_uk' => 'СПЕЦІАЛІЗАЦІЇ',
            'press_title_en' => 'PRESS',
            'press_title_uk' => 'ПРО НАС ПИШУТЬ',
            'partners_title_en' => 'OUR PARTNERS',
            'partners_title_uk' => 'НАШІ ПАРТНЕРИ',
            'footer_title_en' => 'Contacts',
            'footer_title_uk' => 'Контакти',
            'footer_text_en' => 'UAV operator training center KRUK',
            'footer_text_uk' => 'Центр підготовки операторів БПЛА “КРУК”',
            'footer_email' => 'connection@kruk.in.ua',
        ]);
        $settings->save();

        CmsMenuItem::query()->delete();
        $menu = [
            ['label_en' => 'Who we are', 'label_uk' => 'Хто ми', 'url' => '#about', 'sort_order' => 10],
            ['label_en' => 'Training course', 'label_uk' => 'Курс навчання', 'url' => '#courses', 'sort_order' => 20],
            ['label_en' => 'Our mission', 'label_uk' => 'Наша місія', 'url' => '#mission', 'sort_order' => 30],
            ['label_en' => 'Disciplines', 'label_uk' => 'Дисципліни', 'url' => '#specializations', 'sort_order' => 40],
            ['label_en' => 'Team', 'label_uk' => 'Команда', 'url' => '#team', 'sort_order' => 50],
            ['label_en' => 'Partners', 'label_uk' => 'Партнери', 'url' => '#partners', 'sort_order' => 60],
            ['label_en' => 'Support', 'label_uk' => 'Підтримати', 'url' => '#support', 'sort_order' => 70],
            ['label_en' => 'Enroll', 'label_uk' => 'Записатися', 'url' => '#enroll', 'sort_order' => 80],
        ];
        foreach ($menu as $item) {
            CmsMenuItem::query()->create($item + ['is_active' => true]);
        }

        CmsHomeCourse::query()->delete();
        $courses = Course::query()->orderBy('title')->get(['id', 'title']);
        $order = 10;
        foreach ($courses as $course) {
            CmsHomeCourse::query()->create([
                'course_id' => $course->id,
                'title_en' => $course->title,
                'title_uk' => $course->title,
                'sort_order' => $order,
                'is_active' => true,
            ]);
            $order += 10;
        }

        CmsSpecialization::query()->delete();
        $specializations = [
            ['en' => 'Multirotor UAV Operation', 'uk' => 'КЕРУВАННЯ БПЛА МУЛЬТИРОТОР'],
            ['en' => 'Wing UAV Operation', 'uk' => 'КЕРУВАННЯ БПЛА КРИЛО'],
            ['en' => 'FPV Drone Operation', 'uk' => 'КЕРУВАННЯ FPV ДРОНАМИ'],
            ['en' => 'Topography Basics', 'uk' => 'ОСНОВИ ТОПОГРАФІЇ'],
            ['en' => 'Personal Radio Communication', 'uk' => "ОСНОВИ ПЕРСОНАЛЬНОГО РАДІОЗВ'ЯЗКУ"],
            ['en' => 'Satellite Internet Usage', 'uk' => 'ВИКОРИСТАННЯ СУПУТНИКОВОГО ІНТЕРНЕТУ'],
            ['en' => 'Communication Improvement Methods', 'uk' => "МЕТОДИ ПОКРАЩЕННЯ ЗВ'ЯЗКУ"],
            ['en' => 'Cybersecurity and OSINT', 'uk' => 'КІБЕРБЕЗПЕКА ТА OSINT'],
        ];
        $order = 10;
        foreach ($specializations as $spec) {
            CmsSpecialization::query()->create([
                'title_en' => $spec['en'],
                'title_uk' => $spec['uk'],
                'description_en' => null,
                'description_uk' => null,
                'image_path' => null,
                'sort_order' => $order,
                'is_active' => true,
            ]);
            $order += 10;
        }

        CmsPressItem::query()->delete();
        $order = 10;
        foreach ($payload['press'] as $item) {
            CmsPressItem::query()->create([
                'title_en' => $item['alt'] ?: 'Press item',
                'title_uk' => $item['alt'] ?: 'Публікація',
                'logo_path' => $item['img'] ?: null,
                'link_url' => $item['link'] ?: null,
                'sort_order' => $order,
                'is_active' => true,
            ]);
            $order += 10;
        }
        CmsPressItem::query()->create([
            'title_en' => 'frankfurter-rundschau',
            'title_uk' => 'frankfurter-rundschau',
            'logo_path' => 'https://a.storyblok.com/f/174597/1242x174/b5c7ec9f9b/frankfurter-rundschau-logo.png',
            'link_url' => 'https://www.fr.de/politik/drohnen-krieg-ukraine-russland-ausbildung-soldaten-kampfeinsatz-kiew-92187765.html',
            'sort_order' => $order,
            'is_active' => true,
        ]);

        CmsPartnerItem::query()->delete();
        $order = 10;
        foreach ($payload['partners'] as $item) {
            CmsPartnerItem::query()->create([
                'title_en' => $item['alt'] ?: 'Partner',
                'title_uk' => $item['alt'] ?: 'Партнер',
                'logo_path' => $item['img'] ?: null,
                'link_url' => $item['link'] ?: null,
                'sort_order' => $order,
                'is_active' => true,
            ]);
            $order += 10;
        }

        CmsFooterLink::query()->delete();
        $footerLinks = [
            ['label_en' => 'Courses', 'label_uk' => 'Курси', 'url' => '#courses', 'sort_order' => 10],
            ['label_en' => 'Specializations', 'label_uk' => 'Спеціалізації', 'url' => '#specializations', 'sort_order' => 20],
            ['label_en' => 'Press', 'label_uk' => 'Преса', 'url' => '#press', 'sort_order' => 30],
            ['label_en' => 'Partners', 'label_uk' => 'Партнери', 'url' => '#partners', 'sort_order' => 40],
        ];
        foreach ($footerLinks as $link) {
            CmsFooterLink::query()->create($link + ['is_active' => true]);
        }
    }
}
