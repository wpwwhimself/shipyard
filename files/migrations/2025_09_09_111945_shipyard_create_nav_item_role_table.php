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
        Schema::create("nav_item_role", function (Blueprint $table) {
            $table->id();
            $table->foreignId("nav_item_id")->cascadeOnDelete()->cascadeOnUpdate();
            $table->string("role_name");
                $table->foreign("role_name")->references("name")->on("roles")->cascadeOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("nav_item_role");
    }
};
