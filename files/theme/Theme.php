<?php

namespace App\Theme\Shipyard;

trait Theme
{
    #region theme
    public static function getTheme(): string
    {
        return self::THEME;
    }
    #endregion

    #region colors
    public static function getColors(): string
    {
        return collect(self::COLORS)
            ->map(fn ($color, $name) => "--$name: $color;")
            ->join("\n");
    }

    public static function getGhostColors(): string
    {
        return collect(self::COLORS)
            ->map(fn ($color) => $color . "77")
            ->map(fn ($color, $name) => "--$name-ghost: $color;")
            ->join("\n");
    }
    #endregion

    #region fonts
    public static function getFonts(): string
    {
        return collect(self::FONTS)
            ->map(fn ($fonts) => implode(", ", array_map(
                fn ($f) => in_array($f, ["serif", "sans-serif", "monospace", "cursive"])
                    ? $f
                    : "\"$f\"",
                $fonts
            )))
            ->map(fn ($fonts, $type) => "--$type-font: $fonts;")
            ->join("\n");
    }

    public static function getFontImportUrl(): string
    {
        if (self::FONT_IMPORT_URL) {
            return "@import url(\"" . self::FONT_IMPORT_URL . "\");";
        }
    }
    #endregion
}
