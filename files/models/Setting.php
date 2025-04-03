<?php

namespace App\Models\Shipyard;

use App\Traits\Shipyard\CanBeStringified;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use CanBeStringified;

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
        "value",
    ];

    #region helpers
    public static function get(string $key, $default = null): ?string
    {
        return Setting::find($key)->value ?? $default;
    }
    #endregion
}
