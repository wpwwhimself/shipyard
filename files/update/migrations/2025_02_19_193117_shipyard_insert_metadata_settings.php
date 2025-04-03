<?php

use App\Models\Setting;
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
            ["name" => "metadata_title", "value" => ""],
            ["name" => "metadata_author", "value" => ""],
            ["name" => "metadata_description", "value" => ""],
            ["name" => "metadata_keywords", "value" => ""],
            ["name" => "metadata_image", "value" => ""],
            ["name" => "metadata_google_tag_code", "value" => ""],
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
