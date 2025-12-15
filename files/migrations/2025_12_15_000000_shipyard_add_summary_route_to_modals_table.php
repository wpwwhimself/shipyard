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
        Schema::table("modals", function (Blueprint $table) {
            $table->string("summary_route")->nullable()->after("target_route");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table("modals", function (Blueprint $table) {
            $table->dropColumn("summary_route");
        });
    }
};
