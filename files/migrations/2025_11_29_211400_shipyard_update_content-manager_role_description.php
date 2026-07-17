<?php

use Wpwwhimself\Shipyard\Models\Modal;
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
        DB::table("roles")->where("name", "content-manager")->update([
            "description" => "Ma dostęp do repozytorium plików oraz podstron",
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table("roles")->where("name", "content-manager")->update([
            "description" => "Ma dostęp do repozytorium plików",
        ]);
    }
};
