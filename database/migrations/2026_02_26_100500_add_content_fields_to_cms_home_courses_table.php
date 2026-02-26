<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cms_home_courses', function (Blueprint $table): void {
            $table->text('description_en')->nullable()->after('course_id');
            $table->text('description_uk')->nullable()->after('description_en');
            $table->string('image_path')->nullable()->after('description_uk');
        });
    }

    public function down(): void
    {
        Schema::table('cms_home_courses', function (Blueprint $table): void {
            $table->dropColumn([
                'description_en',
                'description_uk',
                'image_path',
            ]);
        });
    }
};

