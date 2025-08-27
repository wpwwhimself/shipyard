<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->string("name")->primary();
            $table->string("type");
            $table->text("value")->nullable();
        });

        DB::table("settings")->insert([
            ["name" => "app_name", "type" => "text", "value" => "Shipyard"],
            ["name" => "app_logo_path", "type" => "url", "value" => null],
            ["name" => "app_favicon_path", "type" => "url", "value" => null],
            ["name" => "app_adaptive_dark_mode", "type" => "checkbox", "value" => true],
            ["name" => "app_theme", "type" => "select", "value" => "origin"],
            ["name" => "app_accent_color_1_light", "type" => "color", "value" => "#09f"],
            ["name" => "app_accent_color_1_dark", "type" => "color", "value" => "#09f"],
            ["name" => "app_accent_color_2_light", "type" => "color", "value" => "#f90"],
            ["name" => "app_accent_color_2_dark", "type" => "color", "value" => "#f90"],
            ["name" => "app_accent_color_3_light", "type" => "color", "value" => "#84f"],
            ["name" => "app_accent_color_3_dark", "type" => "color", "value" => "#84f"],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
