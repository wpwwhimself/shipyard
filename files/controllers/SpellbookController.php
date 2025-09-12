<?php

namespace App\Http\Controllers\Shipyard;

use App\Http\Controllers\Controller;
use App\Models\Shipyard\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SpellbookController extends Controller
{
    public const SPELLS = [
        "become" => "become/{user}",
    ];

    public function become(User $user)
    {
        Auth::login($user);
        return back()->with("toast", ["success", "Jesteś teraz: $user->name"]);
    }
}
