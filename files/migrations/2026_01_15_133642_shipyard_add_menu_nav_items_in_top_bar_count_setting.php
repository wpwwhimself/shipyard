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
        Setting::updateOrCreate([
            "name" => "menu_nav_items_in_top_bar_count",
        ], [
            "name" => "menu_nav_items_in_top_bar_count",
            "type" => "number",
            "value" => 3,
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Setting::where("name", "menu_nav_items_in_top_bar_count")->delete();
    }
};
