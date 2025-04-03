<?php

namespace App\Traits\Shipyard;

use Illuminate\Support\Facades\Log;

trait CanLog
{
    private static function log($message, $lvl = "info", $indent = 0) {
        $icon = (defined(static::class."::ICON") ? self::ICON : "🔈");

        Log::{$lvl}($icon . " | " . str_repeat("• ", $indent) . $message);
    }
}
