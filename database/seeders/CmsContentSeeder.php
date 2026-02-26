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

class CmsContentSeeder extends Seeder
{
    public function run(): void
    {
        CmsSetting::query()->firstOrCreate([], [
            'site_name' => 'Campus CRM',
            'who_title_en' => 'Who we are',
            'who_title_uk' => 'Хто ми',
            'who_text_en' => 'Training center for modern UAV operators.',
            'who_text_uk' => 'Центр підготовки сучасних операторів БПЛА.',
            'mission_title_en' => 'Our mission',
            'mission_title_uk' => 'Наша місія',
            'mission_text_en' => 'Provide practical and accessible learning for defense and civil use.',
            'mission_text_uk' => 'Надавати практичне та доступне навчання для оборонних і цивільних задач.',
            'specializations_title_en' => 'Specializations',
            'specializations_title_uk' => 'СПЕЦІАЛІЗАЦІЇ',
            'press_title_en' => 'Press',
            'press_title_uk' => 'ПРЕСА',
            'partners_title_en' => 'Partners',
            'partners_title_uk' => 'ПАРТНЕРИ',
            'footer_title_en' => 'Contacts',
            'footer_title_uk' => 'Контакти',
        ]);

        $menu = [
            ['label_en' => 'Who we are', 'label_uk' => 'Хто ми', 'url' => '#who', 'sort_order' => 10],
            ['label_en' => 'Mission', 'label_uk' => 'Місія', 'url' => '#mission', 'sort_order' => 20],
            ['label_en' => 'Courses', 'label_uk' => 'Курси', 'url' => '#courses', 'sort_order' => 30],
            ['label_en' => 'Specializations', 'label_uk' => 'Спеціалізації', 'url' => '#specializations', 'sort_order' => 40],
            ['label_en' => 'Press', 'label_uk' => 'Преса', 'url' => '#press', 'sort_order' => 50],
            ['label_en' => 'Partners', 'label_uk' => 'Партнери', 'url' => '#partners', 'sort_order' => 60],
        ];

        foreach ($menu as $item) {
            CmsMenuItem::query()->firstOrCreate(['url' => $item['url']], $item + ['is_active' => true]);
        }

        CmsSpecialization::query()->firstOrCreate(['title_uk' => 'Керування БПЛА'], [
            'title_en' => 'UAV operation',
            'description_en' => 'Multirotor and wing operation training.',
            'description_uk' => 'Підготовка з керування мультикоптерами та крилом.',
            'sort_order' => 10,
            'is_active' => true,
        ]);

        CmsSpecialization::query()->firstOrCreate(['title_uk' => 'Кібербезпека'], [
            'title_en' => 'Cybersecurity',
            'description_en' => 'Operational digital safety and OSINT basics.',
            'description_uk' => 'Операційна цифрова безпека та основи OSINT.',
            'sort_order' => 20,
            'is_active' => true,
        ]);

        CmsFooterLink::query()->firstOrCreate(['url' => '/courses'], [
            'label_en' => 'Student Portal',
            'label_uk' => 'Портал студента',
            'sort_order' => 10,
            'is_active' => true,
        ]);

        $courses = Course::query()->orderBy('id')->limit(6)->get(['id', 'title']);
        foreach ($courses as $idx => $course) {
            CmsHomeCourse::query()->firstOrCreate(
                ['course_id' => $course->id],
                [
                    'title_en' => $course->title,
                    'title_uk' => $course->title,
                    'sort_order' => ($idx + 1) * 10,
                    'is_active' => true,
                ]
            );
        }

        CmsPressItem::query()->firstOrCreate(['title_uk' => 'Campus CRM'], [
            'title_en' => 'Campus CRM',
            'link_url' => 'https://example.com',
            'sort_order' => 10,
            'is_active' => true,
        ]);

        CmsPartnerItem::query()->firstOrCreate(['title_uk' => 'Campus CRM'], [
            'title_en' => 'Campus CRM',
            'link_url' => 'https://example.com',
            'sort_order' => 10,
            'is_active' => true,
        ]);
    }
}
