<?php

use App\Models\Shipyard\Setting;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Setting::whereIn("name", [
            "app_theme",
            "app_accent_color_1_light",
            "app_accent_color_1_dark",
            "app_accent_color_2_light",
            "app_accent_color_2_dark",
            "app_accent_color_3_light",
            "app_accent_color_3_dark",
        ])->delete();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
