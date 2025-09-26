<?php

namespace App\Models\Shipyard;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Mattiverse\Userstamps\Traits\Userstamps;

class LocalSetting extends Model
{
    use Userstamps;

    public $incrementing = false;
    protected $primaryKey = "name";
    protected $keyType = "string";
    public $timestamps = false;

    public const META = [
        "label" => "Ustawienia lokalne",
        "icon" => "wrench",
        "description" => "Ustawienia pozwalają na zarządzanie specjalnymi parametrami aplikacji. Zmiany tutaj mają wpływ na różne obszary aplikacji.",
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
        return LocalSetting::find($key)->value ?? $default;
    }
    #endregion
}
