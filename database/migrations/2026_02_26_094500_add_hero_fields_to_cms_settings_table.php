<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cms_settings', function (Blueprint $table): void {
            $table->string('hero_title_en')->nullable()->after('logo_dark_path');
            $table->string('hero_title_uk')->nullable()->after('hero_title_en');
            $table->text('hero_subtitle_en')->nullable()->after('hero_title_uk');
            $table->text('hero_subtitle_uk')->nullable()->after('hero_subtitle_en');
            $table->string('hero_cta_label_en')->nullable()->after('hero_subtitle_uk');
            $table->string('hero_cta_label_uk')->nullable()->after('hero_cta_label_en');
            $table->string('hero_cta_url')->nullable()->after('hero_cta_label_uk');
            $table->string('hero_stats_label_en')->nullable()->after('hero_cta_url');
            $table->string('hero_stats_label_uk')->nullable()->after('hero_stats_label_en');
        });
    }

    public function down(): void
    {
        Schema::table('cms_settings', function (Blueprint $table): void {
            $table->dropColumn([
                'hero_title_en',
                'hero_title_uk',
                'hero_subtitle_en',
                'hero_subtitle_uk',
                'hero_cta_label_en',
                'hero_cta_label_uk',
                'hero_cta_url',
                'hero_stats_label_en',
                'hero_stats_label_uk',
            ]);
        });
    }
};

