<?php

namespace App\Http\Controllers;

use App\Models\User;
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
        return back()->with("success", "Jesteś teraz: $user->name");
    }
}
