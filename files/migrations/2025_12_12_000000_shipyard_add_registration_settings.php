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
        Setting::create([
            "name" => "users_terms_and_conditions_page_url",
            "type" => "url",
            "value" => null,
        ]);
        Setting::create([
            "name" => "users_recaptcha_site_key",
            "type" => "text",
            "value" => null,
        ]);
        Setting::create([
            "name" => "users_recaptcha_secret_key",
            "type" => "text",
            "value" => null,
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Setting::whereIn("name", [
            "users_terms_and_conditions_page_url",
            "users_recaptcha_site_key",
            "users_recaptcha_secret_key",
        ])->delete();
    }
};
