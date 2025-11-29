<?php

use App\Models\Shipyard\Modal;
use App\Models\Shipyard\Role;
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
        Role::find("content-manager")->update([
            "description" => "Ma dostęp do repozytorium plików oraz stron standardowych",
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Role::find("content-manager")->update([
            "description" => "Ma dostęp do repozytorium plików",
        ]);
    }
};
