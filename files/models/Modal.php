<?php

namespace App\Models\Shipyard;

use App\Traits\Shipyard\HasStandardAttributes;
use App\Traits\Shipyard\HasStandardFields;
use App\Traits\Shipyard\HasStandardScopes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
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
            "column-types" => [ // for JSON
                "Nazwa" => "text",
                "Typ" => "select",
                "Etykieta" => "text",
                "Ikona" => "icon",
                "Wymagane" => "checkbox",
                "Pozostałe (jako JSON)" => "TEXT",
            ],
            "label" => "Pola",
            "icon" => "pencil",
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
            "fields" => "json",
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
                ->map(fn ($f) => view("components.shipyard.ui.input", [
                    "type" => $f[1],
                    "name" => $f[0],
                    "label" => $f[2],
                    "icon" => $f[3],
                    "required" => $f[4],
                    ...(json_decode($f[5], true) ?? []),
                ])->render())
                ->join(""),
        );
    }
    #endregion

    #region relations
    #endregion

    #region helpers
    #endregion
}
