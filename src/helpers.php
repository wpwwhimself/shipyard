<?php

use App\Models\Setting;
use Illuminate\Support\Str;
use Wpwwhimself\Shipyard\Console\InstallCommand;

/**
 * returns app lifetime dates for footer
 */
function app_lifetime(): string
{
    $init = setting("app_beginning");
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

function shipyard_version(): string
{
    $data = json_decode(
        @file_get_contents(base_path(InstallCommand::PACKAGE_INFO_PATH)),
        true
    ) ?? [];
    return $data["version"];
}

#region files
/**
 * Is it a picture?
 */
function isPicture(string $path): bool
{
    return Str::of($path)
        ->beforeLast("?")
        ->endsWith([".jpg", ".jpeg", ".png", ".gif", ".svg"]);
}
#endregion

#region model info
/**
 * returns model class from scope
 */
function model(string $scope): string
{
    $model_name = Str::of($scope)->singular()->studly()->toString();
    $shipyard_models = array_map(
        fn ($file) => Str::of(basename($file))->replace(".php", "")->toString(),
        glob(base_path("vendor/wpwwhimself/shipyard/src/Models/*.php"))
    );
    $is_shipyard_model = ($model_name == "User" && file_exists(app_path("Models/User.php")))
        ? false
        : in_array($model_name, $shipyard_models);
    return ($is_shipyard_model)
        ? "Wpwwhimself\\Shipyard\\Models\\".$model_name
        : "App\\Models\\".($is_shipyard_model ? "Shipyard\\" : "").$model_name;
}

/**
 * checks if model is shipyard model
 */
function is_shipyard_model(string $model): bool
{
    return Str::of($model)->contains("Shipyard")
        || defined($model."::FROM_SHIPYARD");
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

/**
 * list similar models to the one given in scope
 * if scope is null, returns all local models
 */
function similar_models(?string $scope = null): array
{
    $is_base_model_shipyard = ($scope ? is_shipyard_model(model($scope)) : false);
    $model_dir = ($is_base_model_shipyard)
        ? base_path("vendor/wpwwhimself/shipyard/src/Models/*.php")
        : app_path("Models/*.php");
    return collect(glob($model_dir))
        ->map(fn ($file) => scope(Str::of(basename($file))->replace(".php", "")))
        ->filter(fn ($scope) => !Str::of($scope)->contains("settings"))
        ->filter(fn ($scope) => is_shipyard_model(model($scope)) === $is_base_model_shipyard)
        ->sortBy(fn ($scope) => model($scope)::META["ordering"] ?? 999)
        ->map(fn ($scope) => [
            "icon" => model_icon($scope),
            "label" => model($scope)::META["label"],
            "scope" => $scope,
        ])
        ->toArray();
}

function model_field_label(string $scope, string $field): ?string
{
    return model($scope)::getFields()[$field]["label"] ?? null;
}

function model_field_icon(string $scope, string $field): ?string
{
    return model($scope)::getFields()[$field]["icon"] ?? null;
}

function model_field_modal_data(string $scope, string $field): ?array
{
    $model_fields = model($scope)::getFields();
    $extras = [];
    foreach ([
        "selectData",
        "min",
        "max",
        "step",
    ] as $extra) {
        if (isset($model_fields[$field][$extra])) {
            $extras[$extra] = $model_fields[$field][$extra];
        }
    }

    return [
        "name" => $field,
        "type" => $model_fields[$field]["type"],
        "label" => $model_fields[$field]["label"],
        "icon" => $model_fields[$field]["icon"],
        "required" => $model_fields[$field]["required"] ?? false,
        "extra" => $extras,
    ];
}
#endregion
