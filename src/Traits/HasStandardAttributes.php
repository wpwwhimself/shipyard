<?php

namespace Wpwwhimself\Shipyard\Traits;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\View\ComponentAttributeBag;

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

    #region default presentation
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

    public function rawTitle(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->name,
        );
    }

    public function displayTitle(): Attribute
    {
        return Attribute::make(
            get: fn () => view("shipyard::components.app.h", [
                "lvl" => 3,
                "icon" => $this->icon ?? static::META["icon"],
                "attributes" => new ComponentAttributeBag([
                    "role" => "card-title",
                ]),
                "slot" => $this->raw_title,
            ])->render(),
        );
    }

    public function displaySubtitle(): Attribute
    {
        return Attribute::make(
            get: fn () => view("shipyard::components.app.model.badges", [
                "badges" => $this->badges,
            ])->render(),
        );
    }

    public function displayMiddlePart(): Attribute
    {
        return Attribute::make(
            get: fn () => view("shipyard::components.app.model.connections-preview", [
                "connections" => self::getConnections(),
                "model" => $this,
            ])->render(),
        );
    }
    #endregion
}
