<?php

namespace App\Models\Shipyard;

use App\Traits\Shipyard\HasStandardAttributes;
use App\Traits\Shipyard\HasStandardFields;
use App\Traits\Shipyard\HasStandardScopes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Mattiverse\Userstamps\Traits\Userstamps;

class Role extends Model
{
    use SoftDeletes, Userstamps;

    public $incrementing = false;
    protected $primaryKey = "name";
    protected $keyType = "string";
    public $timestamps = false;

    public const META = [
        "label" => "Role",
        "icon" => "key-chain",
        "description" => "Lista ról użytkowników w systemie. Rola pozwala użytkownikowi na dostęp do określonych funkcjonalności.",
        "role" => "technical",
        "uneditable" => [
            "archmage",
            "technical",
            "content-manager",
            "spellcaster",
        ],
    ];

    protected $fillable = [
        "name",
        "icon",
        "description",
    ];

    public function __toString(): string
    {
        return $this->name;
    }

    public function optionLabel(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->name . " (" . $this->description . ")",
        );
    }

    #region fields
    use HasStandardFields;

    public const FIELDS = [
        "icon" => [
            "type" => "icon",
            "label" => "Ikona",
            "icon" => "image",
        ],
        "description" => [
            "type" => "TEXT",
            "label" => "Opis",
            "hint" => "Opis uprawnień, jakie rola daje użytkownikowi.",
            "placeholder" => "Ma dostęp do...",
            "icon" => "information",
        ],
    ];
    #endregion

    #region scopes
    use HasStandardScopes;
    #endregion

    #region attributes
    use HasStandardAttributes;
    #endregion

    #region relations
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
    #endregion

    #region helpers
    #endregion
}
