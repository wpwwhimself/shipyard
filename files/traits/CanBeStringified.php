<?php

namespace App\Traits\Shipyard;

/**
 * allows Eloquent models to be cast to string with an icon
 */
trait CanBeStringified
{
    public function __toString()
    {
        $icon = view("components.icon", ["name" => self::META['icon']])->render();
        return $icon . " " . ($this->stringify_key
            ? $this->{$this->stringify_key}
            : $this->name
        );
    }
}
