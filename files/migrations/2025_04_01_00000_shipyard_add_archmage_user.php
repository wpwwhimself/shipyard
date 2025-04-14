<?php

use App\Models\Shipyard\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $archmage = User::create([
            "name" => "archmage",
            "email" => env("MAIL_FROM_ADDRESS", "qwp@test.test"),
            "password" => Hash::make("archmage"),
        ]);

        $archmage->roles()->sync(["archmage"]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        User::where("name", "archmage")->delete();
    }
};
