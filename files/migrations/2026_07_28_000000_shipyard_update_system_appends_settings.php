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
        foreach ([
            "system_prepends",
            "system_appends",
        ] as $name) {
            Setting::create([
                "name" => $name,
                "type" => "TEXT",
            ]);
        }
        Setting::find("metadata_google_tag_code")?->delete();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        foreach ([
            "system_prepends",
            "system_appends",
        ] as $name) {
            Setting::find($name)?->delete();
        }
        Setting::create([
            "name" => "metadata_google_tag_code",
            "type" => "TEXT",
        ]);
    }
};
