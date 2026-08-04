<?php

use Wpwwhimself\Shipyard\Models\Setting;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Setting::create([
            "name" => "app_logo_dark_path",
            "type" => "url-storage",
            "value" => null,
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Setting::find("app_logo_uses_dark_mode")->delete();
    }
};
