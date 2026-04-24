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
        Setting::updateOrCreate([
            "name" => "animations_mode",
        ], [
            "type" => "select",
            "value" => 2,
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Setting::find("animations_mode")?->delete();
    }
};
