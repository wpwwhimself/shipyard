<?php

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
        Schema::table("settings", function (Blueprint $table) {
            $table->foreignId("created_by")->nullable()->constrained("users")->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId("updated_by")->nullable()->constrained("users")->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId("deleted_by")->nullable()->constrained("users")->cascadeOnUpdate()->nullOnDelete();
            $table->timestamps();
        });

        Schema::table("roles", function (Blueprint $table) {
            $table->foreignId("created_by")->nullable()->constrained("users")->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId("updated_by")->nullable()->constrained("users")->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId("deleted_by")->nullable()->constrained("users")->cascadeOnUpdate()->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table("users", function (Blueprint $table) {
            $table->foreignId("created_by")->nullable()->constrained("users")->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId("updated_by")->nullable()->constrained("users")->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId("deleted_by")->nullable()->constrained("users")->cascadeOnUpdate()->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table("settings", function (Blueprint $table) {
            $table->dropForeign("settings_created_by_foreign");
            $table->dropForeign("settings_updated_by_foreign");
            $table->dropForeign("settings_deleted_by_foreign");
            $table->dropTimestamps();
        });

        Schema::table("roles", function (Blueprint $table) {
            $table->dropForeign("roles_created_by_foreign");
            $table->dropForeign("roles_updated_by_foreign");
            $table->dropForeign("roles_deleted_by_foreign");
            $table->dropTimestamps();
            $table->dropSoftDeletes();
        });

        Schema::table("users", function (Blueprint $table) {
            $table->dropForeign("users_created_by_foreign");
            $table->dropForeign("users_updated_by_foreign");
            $table->dropForeign("users_deleted_by_foreign");
        });
    }
};
