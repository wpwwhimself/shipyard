<?php

namespace App\Scaffolds\Shipyard;

use App\Scaffolds\Shipyard\Scaffold;
use Illuminate\Support\Collection;
use Illuminate\View\ComponentAttributeBag;

abstract class Modal
{
    public const META = [
        "label" => "Modale",
        "icon" => "dock-window",
        "description" => "Formularze w postaci wyskakujących okien. Pozwalają na wykonywanie akcji z określaniem większych ilości danych.",
    ];

    protected static function defaultItems(): array
    {
        return [
            "report-error" => [
                "heading" => "Zgłoś błąd",
                "target_route" => "error.report",
                "fields" => [
                    [
                        "name" => "user_email",
                        "type" => "email",
                        "label" => "E-mail kontaktowy",
                        "icon" => "at",
                        "extra" => [
                            "hint" => "Potrzebny w przypadku pytań doprecyzowujących.",
                        ],
                    ],
                    [
                        "name" => "actions_description",
                        "type" => "TEXT",
                        "label" => "Lista czynności tuż przed wystąpieniem błędu",
                        "icon" => "text",
                        "extra" => [
                            "hint" => "Potrzebna do odtworzenia problemu.",
                        ],
                    ]
                ],
            ],
            // "<modal_name>" => [
            //     "heading" => "",
            //     "target_route" => "",
            //     "summary_route" => "",
            //     "fields" => [
            //         [
            //             "name" => "",
            //             "type" => "",
            //             "label" => "",
            //             "icon" => "",
            //             "required" => false,
            //             "extra" => [
            //                 ...
            //             ],
            //         ],
            //     ],
            // ],
        ];
    }
    abstract protected static function items(): array;

    public static function get(string $name, ?array $overrides = null): Collection
    {
        $all_items = array_merge(self::defaultItems(), static::items());

        if (!isset($all_items[$name])) {
            throw new \Error("⚓ Modal `$name` not found.");
        }

        $ret = collect($all_items[$name]);

        // additional fields
        $ret->put("full_target_route", route($ret["target_route"]));
        if ($ret->has("summary_route")) {
            $ret->put("full_summary_route", route($ret["summary_route"]));
        }
        $ret->put("rendered_fields", collect($ret["fields"])->map(function ($f) use ($overrides) {
            // overrides
            foreach (["icon", "label", "required", "extra"] as $overridable) {
                if (!isset($f["name"])) continue;

                if (isset($overrides[$f["name"]][$overridable])) {
                    $f[$overridable] = $overrides[$f["name"]][$overridable];
                }

                if ($overrides[$f["name"]]["hide"] ?? false) return "";
            }

            switch ($f["type"]) {
                case "heading":
                    return view("components.shipyard.app.h", [
                        "lvl" => 3,
                        "icon" => $f["icon"] ?? null,
                        "slot" => $f["label"],
                        "attributes" => new ComponentAttributeBag([
                            ...($f["extra"] ?? []),
                        ]),
                    ])->render();

                case "paragraph":
                    return view("components.shipyard.app.icon-label-value", [
                        "icon" => $f["icon"] ?? null,
                        "slot" => $f["label"],
                        "attributes" => new ComponentAttributeBag([
                            ...($f["extra"] ?? []),
                        ]),
                    ])->render();

                default:
                    return view("components.shipyard.ui.input", [
                        "type" => $f["type"],
                        "name" => $f["name"],
                        "label" => $f["label"],
                        "icon" => $overrides[$f["name"]]["icon"] ?? $f["icon"] ?? null,
                        "attributes" => new ComponentAttributeBag([
                            "required" => $f["required"] ?? false,
                            ...($f["extra"] ?? []),
                        ]),
                    ])->render();
            }
        })->join(""));

        return $ret;
    }
}
