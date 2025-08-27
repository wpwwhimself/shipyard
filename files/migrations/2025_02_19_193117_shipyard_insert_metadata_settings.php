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
        Setting::insert([
            ["name" => "metadata_title", "type" => "text", "value" => ""],
            ["name" => "metadata_author", "type" => "text", "value" => ""],
            ["name" => "metadata_description", "type" => "TEXT", "value" => ""],
            ["name" => "metadata_keywords", "type" => "text", "value" => ""],
            ["name" => "metadata_image", "type" => "url", "value" => ""],
            ["name" => "metadata_google_tag_code", "type" => "TEXT", "value" => ""],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Setting::whereIn("name", [
            "metadata_title",
            "metadata_author",
            "metadata_description",
            "metadata_keywords",
            "metadata_image",
            "metadata_google_tag_code",
        ])->delete();
    }
};
