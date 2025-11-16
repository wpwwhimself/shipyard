<?php

namespace App\Http\Controllers\Shipyard;

use App\Http\Controllers\Controller;
use App\Models\Shipyard\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SpellbookController extends Controller
{
    #region descriptions
    /**
     * list of castable spells:
     * name => [route, description]
     */
    public const SPELLS = [
        "become" => [
            "route" => "become/{user}",
            "description" => <<<DESC
                Pozwala na zalogowanie się do systemu jako użytkownik o podanym ID.
            DESC,
        ],
        "invokeBook" => [
            "route" => "invoke/book",
            "description" => <<<DESC
                Pobiera aktualną bazę danych SQLite. Przydatne do testów.
            DESC,
        ],
        "obliterate" => [
            "route" => "obliterate/{scope}/{id}",
            "description" => <<<DESC
                Usuwa model o podanym ID z systemu.
            DESC,
        ],
        "obliviate" => [
            "route" => "obliviate/{scope?}",
            "description" => <<<DESC
                Czyści pamięć podręczną systemu. Argument pozwala na wyczyszczenie:
                - `theme` - cache motywu,
                - brak argumentu - ogólnej pamięci podręcznej.
            DESC,
        ],
        "transmute" => [
            "route" => "transmute/{scope}/{id}/{field}/{value?}",
            "description" => <<<DESC
                Modyfikuje wartość pola w modelu o podanym ID.
            DESC,
        ]
    ];
    #endregion

    #region definitions
    public function become(User $user) {
        Auth::login($user);
        return back()->with("toast", ["success", "Jesteś teraz: $user->name"]);
    }

    public function invokeBook() {
        if (env("DB_CONNECTION") !== "sqlite") {
            return back()->with("toast", ["error", "Tylko bazę SQLite można pobrać"]);
        }

        $db = base_path("database/database.sqlite");
        return response()->download($db);
    }

    public function obliterate(string $scope, mixed $id) {
        model($scope)::find($id)->delete();
        return redirect()->route("admin.model.list", ["model" => $scope])->with("toast", ["success", "Model usunięty"]);
    }

    public function obliviate(?string $scope = null) {
        switch ($scope) {
            case "theme":
                ThemeController::_reset();
                return back()->with("toast", ["success", "Pamięć podręczna motywu odświeżona"]);

            default:
                shell_exec("php artisan optimize:clear");
                return back()->with("toast", ["success", "Pamięć podręczna wyczyszczona"]);
        }
    }

    public function transmute(string $scope, mixed $id, string $field, ?string $value = null) {
        model($scope)::find($id)->update([$field => $value]);
        return back()->with("toast", ["success", "Model zaktualizowany"]);
    }
    #endregion
}
