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
            $table->text("value")->nullable();
        });

        DB::table("settings")->insert([
            ["name" => "app_name", "value" => "Shipyard"],
            ["name" => "app_logo_path", "value" => null],
            ["name" => "app_favicon_path", "value" => null],
            ["name" => "app_adaptive_dark_mode", "value" => true],
            ["name" => "app_theme", "value" => "origin"],
            ["name" => "app_accent_color_1_light", "value" => "#09f"],
            ["name" => "app_accent_color_1_dark", "value" => "#09f"],
            ["name" => "app_accent_color_2_light", "value" => "#f90"],
            ["name" => "app_accent_color_2_dark", "value" => "#f90"],
            ["name" => "app_accent_color_3_light", "value" => "#84f"],
            ["name" => "app_accent_color_3_dark", "value" => "#84f"],
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
