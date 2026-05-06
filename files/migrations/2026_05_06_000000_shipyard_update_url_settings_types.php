<?php

use App\Models\Shipyard\Setting;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Setting::whereIn("name", [
            "app_logo_path",
            "app_favicon_path",
            "metadata_image",
        ])->update([
            "type" => "url-storage",
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Setting::whereIn("name", [
            "app_logo_path",
            "app_favicon_path",
            "metadata_image",
        ])->update([
            "type" => "url",
        ]);
    }
};
