<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cms_home_courses', function (Blueprint $table): void {
            $table->string('title_en')->nullable()->after('course_id');
            $table->string('title_uk')->nullable()->after('title_en');
        });

        DB::table('cms_home_courses')
            ->join('courses', 'courses.id', '=', 'cms_home_courses.course_id')
            ->whereNull('cms_home_courses.title_uk')
            ->update([
                'cms_home_courses.title_uk' => DB::raw('courses.title'),
            ]);

        DB::table('cms_home_courses')
            ->join('courses', 'courses.id', '=', 'cms_home_courses.course_id')
            ->whereNull('cms_home_courses.title_en')
            ->update([
                'cms_home_courses.title_en' => DB::raw('courses.title'),
            ]);
    }

    public function down(): void
    {
        Schema::table('cms_home_courses', function (Blueprint $table): void {
            $table->dropColumn(['title_en', 'title_uk']);
        });
    }
};
