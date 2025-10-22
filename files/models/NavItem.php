<?php

namespace App\Models\Shipyard;

use App\Traits\Shipyard\HasStandardAttributes;
use App\Traits\Shipyard\HasStandardFields;
use App\Traits\Shipyard\HasStandardScopes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Illuminate\View\ComponentAttributeBag;
use Mattiverse\Userstamps\Traits\Userstamps;
use Throwable;

class NavItem extends Model
{
    //

    public const META = [
        "label" => "Pozycje menu",
        "icon" => "navigation",
        "description" => "Przyciski wyświetlane na pasku nawigacji. Mogą prowadzić do stron standardowych lub innych ścieżek.",
        "role" => "technical",
    ];

    use SoftDeletes, Userstamps;

    protected $fillable = [
        "name",
        "visible",
        "order",
        "icon",
        "target_type",
        "target_name",
        "target_params",
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
                "slot" => $this->name,
            ])->render(),
        );
    }

    public function displaySubtitle(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->target_name,
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
        "icon" => [
            "type" => "icon",
            "label" => "Ikona",
            "icon" => "image",
        ],
        "target_type" => [
            "type" => "select",
            "selectData" => [
                "options" => [
                    ["value" => 0, "label" => "Strona standardowa"],
                    ["value" => 1, "label" => "Link wewnętrzny"],
                    ["value" => 2, "label" => "Link zewnętrzny"],
                ],
            ],
            "label" => "Cel",
            "icon" => "target",
            "required" => true,
        ],
        "target_name" => [
            "type" => "text",
            "label" => "Nazwa celu",
            "icon" => "label",
            "hint" => "Strona standardowa: nazwa strony<br>Link wewnętrzny: nazwa route'a<br>Link zewnętrzny: URL",
            "required" => true,
        ],
        "target_params" => [
            "type" => "JSON",
            "columnTypes" => [
                "Nazwa" => "string",
                "Wartość" => "string",
            ],
            "label" => "Parametry celu",
            "hint" => "Link wewnętrzny: parametry route'a.",
            "icon" => "abacus",
        ],
    ];

    public const CONNECTIONS = [
        "roles" => [
            "model" => Role::class,
            "mode" => "many",
        ],
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

    // use CanBeSorted;
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
            "target_params" => "json",
        ];
    }

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

    public function action(): Attribute
    {
        return Attribute::make(
            get: function () {
                try {
                    switch ($this->target_type) {
                        case 0: return route("standard-page", ["slug" => Str::slug($this->target_name)]);
                        case 1: return route($this->target_name, $this->target_params);
                        case 2: return $this->target_name;
                    }
                } catch (Throwable $e) {
                    return null;
                }
            },
        );
    }
    #endregion

    #region relations
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }
    #endregion

    #region helpers
    #endregion
}
