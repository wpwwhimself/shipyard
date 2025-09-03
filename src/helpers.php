<?php

use App\Models\Shipyard\Setting;
use Illuminate\Support\Str;

/**
 * returns app lifetime dates for footer
 */
function app_lifetime(): string
{
    $init = date('Y', filemtime(base_path('composer.json')));
    $now = date('Y');

    return ($init != $now) ? "$init – $now" : $now;
}

/**
 * returns value if condition is true, otherwise returns null
 */
function nullif(string $value, bool $condition): string|null
{
    return $condition ? $value : null;
}

/**
 * retrieve setting
 */
function setting(string $key, $default = null): ?string
{
    return Setting::get($key, $default);
}

#region model info
/**
 * returns model class from scope
 */
function model(string $scope): string
{
    $model_name = Str::of($scope)->singular()->studly()->toString();
    $shipyard_models = array_map(
        fn ($file) => Str::of(basename($file))->replace(".php", "")->toString(),
        glob(app_path("Models/Shipyard/*.php"))
    );
    $is_shipyard_model = in_array($model_name, $shipyard_models);
    return "App\\Models\\".($is_shipyard_model ? "Shipyard\\" : "").$model_name;
}

/**
 * checks if model is shipyard model
 */
function is_shipyard_model(string $model): bool
{
    return Str::of($model)->contains("Shipyard");
}

/**
 * returns model icon from scope
 */
function model_icon(string $scope): string
{
    return model($scope)::META['icon'];
}

/**
 * extracts scope name from model class
 */
function scope(string $model): string
{
    return Str::of($model)->afterLast("\\")->replace("::class", "")->plural()->kebab()->toString();
}

function similar_models(string $scope): array
{
    $model_dir = app_path(implode("/", array_filter([
        "Models",
        is_shipyard_model(model($scope)) ? "Shipyard" : null,
        "*.php",
    ])));
    return collect(glob($model_dir))
        ->map(fn ($file) => scope(Str::of(basename($file))->replace(".php", "")))
        ->map(fn ($scope) => [
            "icon" => model_icon($scope),
            "label" => model($scope)::META["label"],
            "scope" => $scope,
        ])
        ->toArray();
}
#endregion
