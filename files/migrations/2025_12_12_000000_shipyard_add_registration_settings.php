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
            "name" => "users_turing_question",
            "type" => "text",
            "value" => "Ile jest sylab w słowie 'dżdżownica'?",
        ]);
        Setting::create([
            "name" => "users_turing_answer",
            "type" => "number",
            "value" => 3,
        ]);
        Setting::create([
            "name" => "users_default_roles[]",
            "type" => "select-multiple",
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
            "users_turing_question",
            "users_turing_answer",
            "users_default_roles[]",
        ])->delete();
    }
};
