<?php

namespace App\Http\Controllers\Shipyard;

use App\Http\Controllers\Controller;
use App\Models\Shipyard\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SpellbookController extends Controller
{
    /**
     * list of castable spells:
     * - keys are used to find a method
     * - values are urls
     */
    public const SPELLS = [
        "become" => "become/{user}",
        "invokeBook" => "invoke/book",
    ];

    public function become(User $user)
    {
        Auth::login($user);
        return back()->with("toast", ["success", "Jesteś teraz: $user->name"]);
    }

    public function invokeBook()
    {
        if (env("DB_CONNECTION") !== "sqlite") {
            return back()->with("toast", ["error", "Tylko bazę SQLite można pobrać"]);
        }

        $db = base_path("database/database.sqlite");
        return response()->download($db);
    }
}
