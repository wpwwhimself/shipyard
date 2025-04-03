<?php

namespace App\Traits\Shipyard;

use Illuminate\Database\Eloquent\Casts\Attribute;

trait HasIconedAttributes
{
    private function iconedAttribute(
        ?string $text = null,
        ?string $field = null,
        ?string $icon_name = null,
        ?string $icon_hint = null
    ): Attribute
    {
        if ($field) {
            $icon_name ??= self::FIELDS[$field]["icon"];
            $icon_hint ??= self::FIELDS[$field]["label"];
        }

        return Attribute::make(
            get: fn () => view("components.icon", [
                "name" => $icon_name,
                "hint" => $icon_hint,
            ])->render() . $text,
        );
    }

    public function pretty(string $field): string
    {
        return view("components.icon", [
            "name" => self::FIELDS[$field]["icon"],
            "hint" => self::FIELDS[$field]["label"],
        ])->render() . $this->{$field};
    }
}
