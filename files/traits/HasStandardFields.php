<?php

namespace App\Traits\Shipyard;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

trait HasStandardFields
{
    public const VISIBILITIES = [
        ["label" => "Nikt nie widzi", "value" => 0],
        ["label" => "Tylko dla zalogowanych", "value" => 1],
        ["label" => "Wszyscy widzą", "value" => 2],
    ];

    /**
     * Get all fields from the model: those default ones (if they exist) and the custom ones from FIELDS constant.
     */
    public static function getFields(): array
    {
        $table = (new self())->getTable();

        return array_merge(array_filter([
            "id" => (!Schema::hasColumn($table, "id")) ? null : [
                "type" => "text",
                "label" => "ID",
                "icon" => "database",
                "disabled" => true,
            ],
            "name" => (!Schema::hasColumn($table, "name")) ? null : [
                "type" => "text",
                "label" => "Nazwa",
                "hint" => "Tytuł wpisu, wyświetlany jako pierwszy.",
                "icon" => "badge-account",
                "required" => true,
            ],
            "visible" => (!Schema::hasColumn($table, "visible")) ? null : [
                "type" => "select",
                "selectData" => [
                    "options" => [
                        ["value" => 0, "label" => "nikt"],
                        ["value" => 1, "label" => "zalogowani"],
                        ["value" => 2, "label" => "wszyscy"],
                    ],
                ],
                "label" => "Widoczny dla",
                "icon" => "eye",
            ],
            "order" => (!Schema::hasColumn($table, "order")) ? null : [
                "type" => "number",
                "label" => "Wymuś kolejność",
                "icon" => "sort",
            ],
        ]), self::FIELDS);
    }

    /**
     * Get all connections from the model from CONNECTIONS constant.
     */
    public static function getConnections(): array
    {
        return array_filter(array_merge(
            defined(self::class."::CONNECTIONS")
                ? self::CONNECTIONS
                : [],
        ));
    }

    /**
     * Get all actions from the model from ACTIONS constant.
     * @param string $showOn Name of the action (e.g. "list") for which all actions should be limited to
     */
    public static function getActions(string $showOn): array
    {
        return array_filter(array_merge(
            defined(self::class."::ACTIONS")
                ? array_filter(self::ACTIONS, fn ($a) => ($a["show-on"] ?? "list") == $showOn)
                : [],
        ));
    }

    /**
     * Get all sorts from the model from SORTS constant.
     */
    public static function getSorts($defaultSort = null): array
    {
        if (!defined(self::class."::SORTS")) return [];

        $sortData = self::SORTS;
        $defaultSortData = [
            "label" => "domyślnie",
            "direction" => "asc",
        ];

        if (!empty($defaultSort)) {
            $direction = $defaultSort[0] == "-" ? "desc" : "asc";

            if (!in_array(Str::after($defaultSort, "-"), array_keys($sortData))) {
                throw new \Error("⚓ Model ".self::class." has no sort `".Str::after($defaultSort, "-")."` to be marked as default.");
            }

            $defaultSortData = array_merge(
                $defaultSortData,
                $sortData[Str::after($defaultSort, "-")]
            );
            $defaultSortData["label"] .= " " . implode(" ", [
                $direction == "asc" ? "rosnąco" : "malejąco",
                "(domyślnie)",
            ]);
            $defaultSortData["direction"] = $direction;
        }

        return array_filter(array_merge(
            [
                null => $defaultSortData,
            ],
            $sortData,
        ));
    }

    /**
     * Get all filters from the model from FILTERS constant.
     */
    public static function getFilters($defaultFltr = null): array
    {
        return array_filter(array_merge(
            defined(self::class."::FILTERS")
                ? self::FILTERS
                : [],
        ));
    }

    /**
     * Get all extra sections from the model from EXTRA_SECTIONS constant.
     */
    public static function getExtraSections(): array
    {
        return array_filter(array_merge(
            defined(self::class."::EXTRA_SECTIONS")
                ? self::EXTRA_SECTIONS
                : [],
        ));
    }
}
