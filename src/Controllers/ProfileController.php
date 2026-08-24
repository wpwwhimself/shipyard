<?php

namespace Wpwwhimself\Shipyard\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function myProfile(): View
    {
        return view('shipyard::pages.auth.profile');
    }

    public function p13n(): View
    {
        $data = Auth::user()->p13n ?? collect();

        return view('shipyard::pages.admin.p13n', compact(
            "data",
        ));
    }

    public function processP13n(Request $rq): RedirectResponse
    {
        $data = $rq->except("_token");
        $data = collect($data)
            ->filter(fn ($v) => $v !== null);
        if ($data->isEmpty()) $data = null;

        Auth::user()->update([
            "p13n" => $data,
        ]);

        return back()->with("toast", ["success", "Zapisano"]);
    }
}
