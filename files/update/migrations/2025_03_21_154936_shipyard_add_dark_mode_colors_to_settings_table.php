<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table("settings")->insert([
            ["name" => "color_primary_dark", "value" => "#e30715"],
            ["name" => "color_secondary_dark", "value" => "#e3dd07"],
            ["name" => "color_tertiary_dark", "value" => "#0796e3"],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table("settings")->whereIn("name", [
            "color_primary_dark",
            "color_secondary_dark",
            "color_tertiary_dark",
        ])->delete();
    }
};
