<?php

namespace App\Models\Shipyard;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Mattiverse\Userstamps\Traits\Userstamps;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as ContractsAuditable;

class Setting extends Model implements ContractsAuditable
{
    use Userstamps, Auditable;

    public $incrementing = false;
    protected $primaryKey = "name";
    protected $keyType = "string";
    public $timestamps = false;

    public const META = [
        "label" => "Ustawienia",
        "icon" => "cog",
        "description" => "Ustawienia pozwalają na zarządzanie podstawowymi parametrami całego systemu. Zmiany tutaj mają wpływ na różne obszary aplikacji.",
    ];

    protected $fillable = [
        "name",
        "type",
        "value",
    ];

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

    #region helpers
    public static function get(string $key, $default = null): ?string
    {
        return Setting::find($key)->value ?? $default;
    }
    #endregion
}
