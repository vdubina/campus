<?php

namespace Database\Seeders;

use App\Models\CmsPage;
use Illuminate\Database\Seeder;

class CmsPageSeeder extends Seeder
{
    public function run(): void
    {
        $htmlPath = base_path('spa/home/index.html');
        $html = is_file($htmlPath) ? file_get_contents($htmlPath) : null;

        CmsPage::query()->updateOrCreate(
            ['slug' => 'home'],
            [
                'title' => 'Home',
                'meta_title' => 'Центр підготовки операторів БПЛА “КРУК”',
                'meta_description' => 'Готуємо операторів мультикоптерів та БПЛА типу крило в максимально стислі терміни',
                'html_content' => $html,
                'custom_css' => null,
                'custom_js' => null,
                'is_active' => true,
            ]
        );
    }
}
