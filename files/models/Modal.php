<?php

namespace App\Models\Shipyard;

use App\Traits\Shipyard\HasStandardAttributes;
use App\Traits\Shipyard\HasStandardFields;
use App\Traits\Shipyard\HasStandardScopes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\View\ComponentAttributeBag;
use Mattiverse\Userstamps\Traits\Userstamps;

class Modal extends Model
{
    //

    public const META = [
        "label" => "Modale",
        "icon" => "dock-window",
        "description" => "Formularze w postaci wyskakujących okien. Pozwalają na wykonywanie akcji z określaniem większych ilości danych.",
        "role" => "technical",
    ];

    use SoftDeletes, Userstamps;

    protected $fillable = [
        "name",
        "visible",
        "heading",
        "fields",
        "target_route",
    ];

    #region presentation
    public function __toString(): string
    {
        return $this->name;
    }

    public function optionLabel(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->name,
        );
    }

    public function displayTitle(): Attribute
    {
        return Attribute::make(
            get: fn () => view("components.shipyard.app.h", [
                "lvl" => 3,
                "icon" => $this->icon ?? self::META["icon"],
                "attributes" => new ComponentAttributeBag([
                    "role" => "card-title",
                ]),
                "slot" => $this->heading,
            ])->render(),
        );
    }

    public function displaySubtitle(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->name,
        );
    }

    public function displayMiddlePart(): Attribute
    {
        return Attribute::make(
            get: fn () => view("components.shipyard.app.model.connections-preview", [
                "connections" => self::getConnections(),
                "model" => $this,
            ])->render(),
        );
    }
    #endregion

    #region fields
    use HasStandardFields;

    public const FIELDS = [
        "heading" => [
            "type" => "text",
            "label" => "Nagłówek",
            "icon" => "text-short",
            "required" => true,
        ],
        "fields" => [
            "type" => "JSON",
            "columnTypes" => [ // for JSON
                "Nazwa" => "text",
                "Typ" => "select",
                "Etykieta" => "text",
                "Ikona" => "icon",
                "Wymagane" => "checkbox",
                "Pozostałe (jako JSON)" => "JSON",
            ],
            "label" => "Pola",
            "icon" => "pencil",
            "hint" => "Pola formularza. Korzystają z tych samych ustawień, co zwykłe pola (inputy). Użyj typu **heading**, żeby zamiast inputa utworzyć nagłówek.",
        ],
        "target_route" => [
            "type" => "text",
            "label" => "Route docelowy",
            "hint" => "Route POST, do którego trafią dane z formularza.",
            "icon" => "target",
            "required" => true,
        ],
    ];

    public const CONNECTIONS = [
        // "<name>" => [
        //     "model" => ,
        //     "mode" => "<one|many>",
        // ],
    ];

    public const ACTIONS = [
        // [
        //     "icon" => "",
        //     "label" => "",
        //     "show-on" => "<list|edit>",
        //     "route" => "",
        //     "role" => "",
        //     "dangerous" => true,
        // ],
    ];
    #endregion

    public const SORTS = [
        // "<name>" => [
        //     "label" => "",
        //     "compare-using" => "function|field",
        //     "discr" => "<function_name|field_name>",
        // ],
    ];

    public const FILTERS = [
        // "<name>" => [
        //     "label" => "",
        //     "icon" => "",
        //     "compare-using" => "function|field",
        //     "discr" => "<function_name|field_name>",
        //     "mode" => "<one|many>",
        //     "operator" => "",
        //     "options" => [
        //         "<label>" => <value>,
        //     ],
        // ],
    ];

    #region scopes
    use HasStandardScopes;
    #endregion

    #region attributes
    protected function casts(): array
    {
        return [
            //
        ];
    }

    protected $appends = [
        "full_target_route",
        "rendered_fields",
    ];

    use HasStandardAttributes;

    // public function badges(): Attribute
    // {
    //     return Attribute::make(
    //         get: fn () => [
    //             [
    //                 "label" => "",
    //                 "icon" => "",
    //                 "class" => "",
    //                 "condition" => "",
    //             ],
    //         ],
    //     );
    // }

    protected function fields(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => collect(json_decode($value, true))
                ->map(fn ($fld) => collect($fld)->map(fn ($f, $i) =>
                    ($i == 5 && gettype($f) == "string") ? json_decode($f, true) : $f)->toArray()
                ),
            set: fn ($value) => json_encode($value),
        );
    }

    public function fullTargetRoute(): Attribute
    {
        return Attribute::make(
            get: fn () => route($this->target_route),
        );
    }

    public function renderedFields(): Attribute
    {
        return Attribute::make(
            get: fn () => collect($this->fields)
                ->map(function ($f) {
                    switch ($f[1]) {
                        case "heading":
                            return view("components.shipyard.app.h", [
                                "lvl" => 3,
                                "icon" => $f[3],
                                "slot" => $f[2],
                            ])->render();

                        default:
                            return view("components.shipyard.ui.input", [
                                "type" => $f[1],
                                "name" => $f[0],
                                "label" => $f[2],
                                "icon" => $f[3],
                                "attributes" => new ComponentAttributeBag([
                                    "required" => $f[4],
                                    ...($f[5] ?? []),
                                ]),
                            ])->render();
                    }
                })
                ->join(""),
        );
    }
    #endregion

    #region relations
    #endregion

    #region helpers
    #endregion
}
