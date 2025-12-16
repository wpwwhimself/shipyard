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
                (self::META["uneditableField"] ?? false)
                    ? $this->{self::META["uneditableField"]}
                    : $this->getKey(),
                self::META["uneditable"] ?? []
            ),
        );
    }
}
