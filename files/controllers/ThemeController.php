<?php

namespace App\Http\Controllers\Shipyard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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

        file_put_contents(public_path("css/shipyard_theme_cache.css"), $rq->get("css"));

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

    public static function _reset() {
        unlink(public_path("css/shipyard_theme_cache.css"));
    }
}
