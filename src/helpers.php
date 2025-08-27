<?php

use App\Models\Shipyard\Setting;
use Illuminate\Support\Str;

function setting(string $key, $default = null): ?string
{
    return Setting::get($key, $default);
}

#region model info
function model(string $scope): string
{
    $scope = Str::of($scope)->singular()->studly()->toString();
    $shipyard_models = array_map(
        fn ($file) => Str::of(basename($file))->replace(".php", "")->toString(),
        glob(app_path("Models/Shipyard/*.php"))
    );
    $is_shipyard_model = in_array($scope, $shipyard_models);
    return "App\\Models\\".($is_shipyard_model ? "Shipyard\\" : "").(Str::of($scope)->singular()->studly()->toString());
}

function model_icon(string $scope): string
{
    return model($scope)::META['icon'];
}
#endregion
