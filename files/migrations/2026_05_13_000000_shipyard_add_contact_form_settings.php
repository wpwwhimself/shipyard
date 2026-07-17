<?php

use Wpwwhimself\Shipyard\Models\Setting;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table("roles")->insert([
            "name" => "mediator",
            "icon" => "account-voice",
            "description" => "Otrzymuje wiadomości z formularza kontaktowego",
        ]);

        Setting::create([
            "name" => "contact_form_enabled",
            "type" => "checkbox",
            "value" => false,
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Setting::whereIn("name", [
            "contact_form_enabled",
        ])->delete();

        DB::table("roles")->where("name", "mediator")->delete();
    }
};
