<?php

namespace App\Http\Controllers\Shipyard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class ThemeController extends Controller
{
    #region test pages
    public function testTheme() {
        return view("pages.shipyard.test.theme");
    }

    public function testThemeToast(string $type) {
        return redirect()->route("theme.test")->with("toast", [$type, "Jestem tostem!"]);
    }
    #endregion
}
