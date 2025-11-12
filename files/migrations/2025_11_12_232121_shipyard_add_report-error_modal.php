<?php

use App\Models\Shipyard\Modal;
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
        Modal::create([
            "name" => "report-error",
            "visible" => 2,
            "heading" => "Zgłoś błąd",
            "fields" => [
                [
                    "user_email",
                    "email",
                    "E-mail kontaktowy",
                    "at",
                    false,
                    [
                        "hint" => "Potrzebny w przypadku pytań doprecyzowujących.",
                    ],
                ],
                [
                    "actions_description",
                    "TEXT",
                    "Lista czynności tuż przed wystąpieniem błędu",
                    "text",
                    false,
                    [
                        "hint" => "Potrzebna do odtworzenia problemu.",
                    ],
                ],
            ],
            "target_route" => "error.report",
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Modal::where("name", "report-error")->delete();
    }
};
