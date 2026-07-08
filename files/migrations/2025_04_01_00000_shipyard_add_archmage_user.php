<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
            "email" => env("MAIL_FROM_ADDRESS", "test@test.test"),
            "password" => Hash::make("archmage"),
        ]);

        DB::table("role_user")->insert([
            "user_id" => $archmage->id,
            "role_name" => "archmage",
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        User::where("name", "archmage")->delete();
    }
};
