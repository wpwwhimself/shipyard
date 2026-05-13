<?php

use App\Models\Shipyard\Role;
use App\Models\Shipyard\Setting;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Role::create([
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

        Role::where("name", "mediator")->delete();
    }
};
