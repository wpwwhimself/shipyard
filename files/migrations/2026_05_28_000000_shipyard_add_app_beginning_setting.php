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
        Setting::create([
            "name" => "app_beginning",
            "type" => "number",
            "value" => date('Y'),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Setting::find("app_beginning")->delete();
    }
};
