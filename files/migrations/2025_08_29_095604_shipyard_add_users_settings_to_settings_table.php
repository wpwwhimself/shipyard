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
            [
                "name" => "users_login_is",
                "type" => "select",
                "value" => "name",
            ],
            [
                "name" => "users_self_register_enabled",
                "type" => "checkbox",
                "value" => false,
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        
    }
};
