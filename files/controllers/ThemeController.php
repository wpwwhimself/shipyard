<?php

namespace App\Http\Controllers\Shipyard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class ThemeController extends Controller
{
    /**
     * Creates a cache file with given contents
     */
    public function cache(Request $rq)
    {
        if (file_exists(public_path("css/shipyard_theme_cache.css"))) {
            return response()->json([
                "message" => "Cache file already exists",
            ], 409);
        }

        Artisan::call("shipyard:cache-theme");

        return response()->json([
            "message" => "Cache file created",
        ]);
    }

    public function reset(Request $rq)
    {
        Artisan::call("shipyard:cache-theme");

        return response()->json([
            "message" => "Cache file deleted",
        ]);
    }

    #region test pages
    public function testTheme() {
        return view("pages.shipyard.test.theme");
    }

    public function testThemeToast(string $type) {
        return redirect()->route("theme.test")->with("toast", [$type, "Jestem tostem!"]);
    }
    #endregion
}
