<?php

namespace App\Models\Shipyard;

use App\Traits\Shipyard\CanBeStringified;
use App\Traits\Shipyard\HasStandardScopes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class StandardPage extends Model
{
    use CanBeStringified, SoftDeletes;

    public const META = [
        "label" => "Strony standardowe",
        "icon" => "script-text",
        "description" => "Podstrony aplikacji, stanowiące dodatkową treść portalu. Ich pełna lista wyświetla się w stopce strony.",
    ];

    protected $fillable = [
        "name", "content",
        "visible", "order",
    ];

    public const FIELDS = [
        "content" => [
            "type" => "HTML",
            "label" => "Treść",
            "icon" => "pencil",
        ],
    ];

    #region scopes
    use HasStandardScopes;
    #endregion

    #region attributes
    /**
     * checks if current user can see this page
     */
    public function canBeSeen(): bool
    {
        return $this->visible > 1 - Auth::check();
    }

    public function slug(): Attribute
    {
        return Attribute::make(
            get: fn () => Str::slug($this->name),
        );
    }
    #endregion
}
