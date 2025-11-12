<?php

namespace App\Http\Controllers\Shipyard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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

        self::_cache();

        return response()->json([
            "message" => "Cache file created",
        ]);
    }

    public function reset(Request $rq)
    {
        if (!file_exists(public_path("css/shipyard_theme_cache.css"))) {
            return response()->json([
                "message" => "Cache file not found",
            ], 404);
        }

        self::_reset();

        return response()->json([
            "message" => "Cache file deleted",
        ]);
    }

    #region helpers
    public static function _cache()
    {
        $styles = implode("\n", [
            file_get_contents(public_path("css/Shipyard/_base.scss")),
            file_get_contents(public_path("css/Shipyard/".\App\ShipyardTheme::getTheme().".scss")),
        ]);

        $ready_css = Http::timeout(120)
            ->post("https://sasscompiler.wpww.pl/compile-scss", [
                "scss" => $styles,
            ])
            ->throwUnlessStatus(200)
            ->body();

        file_put_contents(public_path("css/shipyard_theme_cache.css"), $ready_css);

        return 0;
    }

    public static function _reset() {
        @unlink(public_path("css/shipyard_theme_cache.css"));
        self::_cache();
    }
    #endregion

    #region test pages
    public function testTheme() {
        return view("pages.shipyard.test.theme");
    }
    #endregion
}
