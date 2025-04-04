<?php

namespace App\Helpers\Shipyard;

use App\Models\Shipyard\Setting;

function setting(string $key, $default = null): ?string
{
    return Setting::get($key, $default);
}
