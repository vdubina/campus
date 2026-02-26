<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cms_settings', function (Blueprint $table): void {
            $table->id();
            $table->string('site_name')->default('Campus CRM');
            $table->string('logo_light_path')->nullable();
            $table->string('logo_dark_path')->nullable();
            $table->string('who_title_en')->nullable();
            $table->string('who_title_uk')->nullable();
            $table->text('who_text_en')->nullable();
            $table->text('who_text_uk')->nullable();
            $table->string('mission_title_en')->nullable();
            $table->string('mission_title_uk')->nullable();
            $table->text('mission_text_en')->nullable();
            $table->text('mission_text_uk')->nullable();
            $table->string('specializations_title_en')->nullable();
            $table->string('specializations_title_uk')->nullable();
            $table->string('press_title_en')->nullable();
            $table->string('press_title_uk')->nullable();
            $table->string('partners_title_en')->nullable();
            $table->string('partners_title_uk')->nullable();
            $table->string('footer_title_en')->nullable();
            $table->string('footer_title_uk')->nullable();
            $table->text('footer_text_en')->nullable();
            $table->text('footer_text_uk')->nullable();
            $table->string('footer_email')->nullable();
            $table->string('footer_phone')->nullable();
            $table->string('footer_address_en')->nullable();
            $table->string('footer_address_uk')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cms_settings');
    }
};
