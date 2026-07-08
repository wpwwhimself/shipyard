<?php

use App\Models\NavItem;
use App\Models\User;
use App\Models\Shipyard\Setting;
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
        Schema::table("users", function (Blueprint $table) {
            $table->text("roles")->after("password")->nullable();
        });
        User::all()->each(function (User $user) {
            $user->update([
                "roles" => DB::table("role_user")->where("user_id", $user->id)->get()->pluck("role_name")->implode(","),
            ]);
        });

        Schema::table("nav_items", function (Blueprint $table) {
            $table->text("roles")->after("icon")->nullable();
        });
        NavItem::all()->each(function (NavItem $item) {
            $item->update([
                "roles" => DB::table("nav_item_role")->where("nav_item_id", $item->id)->get()->pluck("role_name")->implode(","),
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table("users", function (Blueprint $table) {
            $table->dropColumn("roles");
        });

        Schema::table("nav_items", function (Blueprint $table) {
            $table->dropColumn("roles");
        });
    }
};
