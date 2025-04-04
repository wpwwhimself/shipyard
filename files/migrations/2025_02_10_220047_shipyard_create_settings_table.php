<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->string("name")->primary();
            $table->text("value")->nullable();
        });

        DB::table("settings")->insert([
            ["name" => "app_name", "value" => "Shipyard"],
            ["name" => "app_logo_path", "value" => null],
            ["name" => "app_favicon_path", "value" => null],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
