<?php

namespace App\Traits\Shipyard;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

trait HasStandardAttributes
{
    public function canBeSeen(): Attribute
    {
        return Attribute::make(
            fn () => $this->visible > 1 - Auth::check(),
        );
    }

    public function isUneditable(): Attribute
    {
        return Attribute::make(
            fn () => in_array(
                (static::META["uneditableField"] ?? false)
                    ? $this->{static::META["uneditableField"]}
                    : $this->getKey(),
                static::META["uneditable"] ?? []
            ),
        );
    }

    public function visiblePretty(): Attribute
    {
        return Attribute::make(
            get: fn ($v, $attrs) => isset($attrs["visible"])
                ? collect(static::VISIBILITIES)->firstWhere(fn ($vv) => $vv["value"] == $attrs["visible"])["label"]
                : "—",
        );
    }
}
