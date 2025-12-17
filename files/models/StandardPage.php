<?php

namespace App\Models\Shipyard;

use App\Traits\Shipyard\HasStandardAttributes;
use App\Traits\Shipyard\HasStandardFields;
use App\Traits\Shipyard\HasStandardScopes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\ComponentAttributeBag;
use Mattiverse\Userstamps\Traits\Userstamps;

class StandardPage extends Model
{
    public const META = [
        "label" => "Podstrony",
        "icon" => "script-text",
        "description" => "Podstrony aplikacji, stanowiące dodatkową treść aplikacji.",
        "role" => "content-manager",
    ];

    use SoftDeletes, Userstamps;

    protected $fillable = [
        "name", "content",
        "visible", "order",
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
            get: fn () => null
        );
    }

    public function displayMiddlePart(): Attribute
    {
        return Attribute::make(
            get: fn () => view("components.shipyard.ui.button", [
                "action" => route("standard-page", ["slug" => $this->slug]),
                "icon" => "eye",
                "pop" => "Przejdź do strony",
                "attributes" => new ComponentAttributeBag([
                    "target" => "_blank",
                ]),
            ]),
        );
    }
    #endregion

    #region fields
    use HasStandardFields;

    public const FIELDS = [
        "content" => [
            "type" => "HTML",
            "label" => "Treść",
            "icon" => "pencil",
        ],
    ];

    public const CONNECTIONS = [
        // "<name>" => [
        //     "model" => ,
        //     "mode" => "<one|many>",
        // ],
    ];

    public const ACTIONS = [
        [
            "icon" => "eye",
            "label" => "Przejdź do strony",
            "show-on" => "edit",
            "route" => "standard-page",
            "params" => ["slug" => "slug"],
            // "role" => "",
            // "dangerous" => true,
        ],
    ];
    #endregion

    #region scopes
    use HasStandardScopes;
    #endregion

    #region attributes
    use HasStandardAttributes;

    public function slug(): Attribute
    {
        return Attribute::make(
            get: fn () => Str::slug($this->name),
        );
    }
    #endregion

    #region relations
    #endregion

    #region helpers
    #endregion
}
