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
            "name" => "users_password_reset_mode",
            "type" => "select",
            "value" => "email",
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Setting::find("users_password_reset_mode")->delete();
    }
};
