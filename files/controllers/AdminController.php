<?php

namespace App\Http\Controllers\Shipyard;

use App\Http\Controllers\Controller;
use App\Models\Setting as LocalSetting;
use App\Models\Shipyard\Role;
use App\Models\Shipyard\Setting;
use App\Models\Shipyard\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    #region system settings
    public function settings(): View
    {
        /**
         * * hierarchical structure of the page *
         * grouped by sections (title, subtitle, icon, identifier)
         * each section contains fields (name, label, hint, icon)
         */
        $fields = [
            [
                "title" => "Tożsamość strony",
                "subtitle" => "Podstawowe informacje wyróżniające aplikację",
                "icon" => "card-account-details",
                "id" => "basic",
                "fields" => [
                    [
                        "name" => "app_name",
                        "label" => "Nazwa systemu",
                        "icon" => "card-account-details",
                        "hint" => "Nazwa aplikacji wyświetlana w tytule strony.",
                    ],
                    [
                        "name" => "app_logo_path",
                        "label" => "Logo",
                        "icon" => "image",
                        "hint" => "Link do logo aplikacji. Po dodaniu wyświetla się w nagłówku strony oraz, jeśli nie podano favicona, na karcie przeglądarki.",
                    ],
                    [
                        "name" => "app_favicon_path",
                        "label" => "Favicon",
                        "icon" => "image-text",
                        "hint" => "Link do favicona aplikacji – małej ikony wyświetlanej na karcie przeglądarki.",
                    ],
                ],
            ],
            [
                "title" => "Wygląd",
                "subtitle" => "Style i kolory",
                "icon" => "palette",
                "id" => "theme",
                "fields" => [
                    [
                        "name" => "app_adaptive_dark_mode",
                        "label" => "Automatyczny tryb ciemny",
                        "icon" => "theme-light-dark",
                        "hint" => "Automatycznie ustawia tryb ciemny aplikacji w zależności od ustawień przeglądarki/systemu. Jeśli opcja jest wyłączona, tryb ciemny może zostać włączony ręcznie za pomocą odpowiedniego przycisku na dole strony.",
                    ],
                ],
            ],
            [
                "title" => "SEO",
                "subtitle" => "Metadane na potrzeby wyszukiwarek",
                "icon" => "earth",
                "id" => "seo",
                "fields" => [
                    [
                        "name" => "metadata_title",
                        "label" => "Tytuł",
                        "icon" => "card-account-details",
                    ],
                    [
                        "name" => "metadata_author",
                        "label" => "Autor",
                        "icon" => "account-edit",
                    ],
                    [
                        "name" => "metadata_description",
                        "label" => "Opis",
                        "icon" => "text",
                    ],
                    [
                        "name" => "metadata_image",
                        "label" => "Baner",
                        "icon" => "image",
                    ],
                    [
                        "name" => "metadata_keywords",
                        "label" => "Słowa kluczowe",
                        "icon" => "tag-multiple",
                    ],
                    [
                        "name" => "metadata_google_tag_code",
                        "label" => "Kod śledzący Google Analytics",
                        "icon" => "magnify",
                    ],
                ],
            ],
            [
                "title" => "Użytkownicy",
                "icon" => "account-multiple",
                "id" => "users",
                "fields" => [
                    [
                        "name" => "users_login_is",
                        "label" => "Loginem jest",
                        "icon" => "badge-account",
                        "hint" => "Pole wykorzystywane do procesu logowania.",
                        "selectData" => [
                            "options" => [
                                ["label" => "Nazwa użytkownika", "value" => "name",],
                                ["label" => "Adres email", "value" => "email",],
                                ["label" => "Nic (logowanie tylko hasłem)", "value" => "none",],
                            ],
                        ],
                    ],
                    [
                        "name" => "users_self_register_enabled",
                        "label" => "Zezwól na rejestrację",
                        "icon" => "account-plus",
                        "hint" => "Jeśli ta opcja jest włączona, użytkownicy mogą sami zakładać własne konta. W przeciwnym wypadku tworzenie kont jest możliwe tylko przez administratora.",
                    ],
                ]
            ],
        ];
        $fields = array_merge($fields, LocalSetting::fields());
        $settings = Setting::all();

        return view("pages.shipyard.admin.settings", compact(
            "fields",
            "settings",
        ));
    }

    public function processSettings(Request $rq): RedirectResponse
    {
        foreach (Setting::all() as $setting) {
            $value = ($setting->type == "checkbox")
                ? $rq->has($setting->name)
                : $rq->get($setting->name);
            $setting->update(["value" => $value]);
        }

        return redirect()->route("admin.system-settings")->with("toast", ["success", "Zapisano"]);
    }
    #endregion

    #region automatic model editors
    public function models(): View
    {
        $model_groups = [
            [
                "id" => "local",
                "label" => "Modele aplikacji",
                "icon" => "database",
                "models" => similar_models(),
            ],
            [
                "id" => "system",
                "label" => "Modele systemowe",
                "icon" => "anchor",
                "models" => similar_models("users"),
            ],
        ];

        return view("pages.shipyard.admin.model.index", compact("model_groups"));
    }

    public function listModel(string $scope): View
    {
        if (!Auth::user()?->hasRole(model($scope)::META["role"] ?? null)) abort(403);

        $meta = model($scope)::META;
        $data = model($scope)::forAdminList(request("sort"), request("fltr"));
        $actions = model($scope)::getActions("list");
        $sorts = model($scope)::getSorts();
        $filters = model($scope)::getFilters();

        return view("pages.shipyard.admin.model.list", compact("data", "meta", "scope", "actions", "sorts", "filters"));
    }

    public function filterListModel(Request $rq, string $scope): RedirectResponse
    {
        $filters = collect($rq->except("_token"))
            ->filter(fn ($v, $k) => !empty($v));

        return redirect()->route("admin.model.list", [
            "model" => $scope,
            ...$filters,
        ]);
    }

    public function editModel(string $scope, int|string|null $id = null): View|RedirectResponse
    {
        if (
            !Auth::user()?->hasRole(model($scope)::META["role"] ?? null)
            && !($scope == "users" && Auth::id() == $id) // user can edit themself
        ) abort(403);

        $meta = model($scope)::META;
        $data = model($scope)::find($id);
        $fields = model($scope)::getFields();
        $connections = model($scope)::getConnections();
        $sections = array_merge(
            [["icon" => $meta["icon"], "title" => "Dane podstawowe", "id" => "basic"]],
            collect($connections)->map(fn ($con, $con_scope) => [
                "icon" => $con["field_icon"] ?? (collect($con["model"])->count() > 1 ? "link" : collect($con["model"])->first()::META['icon']),
                "title" => $con["field_label"] ?? collect($con["model"])->map(fn ($m) => $m::META['label'])->join("/"),
                "id" => "connections_$con_scope",
                "show" => Auth::user()?->hasRole($con["role"] ?? null),
            ])
                ->filter(fn ($con) => $con["show"])
                ->toArray(),
        );
        $actions = model($scope)::getActions("edit");

        if ($data?->is_uneditable && !Auth::user()?->hasRole("archmage")) {
            return redirect()->route("admin.model.list", ["model" => $scope])->with("toast", ["error", "Tego modelu nie można edytować"]);
        }

        return view("pages.shipyard.admin.model.edit", compact("data", "meta", "scope", "fields", "connections", "sections", "actions"));
    }

    public function processEditModel(Request $rq, string $scope): RedirectResponse
    {
        if (
            !Auth::user()?->hasRole(model($scope)::META["role"] ?? null)
            && !($scope == "users" && Auth::id() == $rq->id) // user can edit themself
        ) abort(403);

        $fields = model($scope)::getFields();
        $data = $rq->except("_token", "_connections", "method");
        foreach ($fields as $name => $fdata) {
            switch ($fdata["type"]) {
                case "checkbox": $data[$name] ??= false; break;
                case "JSON": $data[$name] = json_decode($data[$name], count($fdata["columnTypes"]) == 2); break;
            }
            if ($fdata["type"] == "checkbox") $data[$name] ??= false;
            if (($fdata["required"] ?? false) && ($data[$name] == "" || $data[$name] == null)) return back()->with("toast", ["error", "Pole $fdata[label] jest wymagane"]);
        }

        // update morphing connections
        $available_connections = model($scope)::getConnections();
        foreach ($rq->get("_connections") ?? [] as $connection_name) {
            if ($available_connections[$connection_name]["mode"] != "one") continue;

            $connection_value = $data[$available_connections[$connection_name]["field_name"] ?? $connection_name."_id"];
            if (Str::contains($connection_value, ":")) {
                $data[$connection_name."_type"] = Str::before($connection_value, ":");
                $data[$connection_name."_id"] = Str::after($connection_value, ":");
            }
        }

        if ($scope == "users") {
            $data["password"] = Hash::make(Str::random(16));
        }

        if ($rq->input("method") == "save") {
            $model_name = model($scope);
            $keyName = (new $model_name())->getKeyName();
            $model = model($scope)::updateOrCreate(
                [$keyName => $rq->id],
                $data,
            );

            if ($rq->has("_connections")) {
                foreach ($rq->get("_connections") as $connection) {
                    if ($available_connections[$connection]["readonly"] ?? false) continue;

                    switch ($available_connections[$connection]["mode"]) {
                        case "many":
                            $model->{$connection}()->sync($rq->get($connection));
                            break;
                    }
                }
            }

            return redirect()->route("admin.model.edit", ["model" => $scope, "id" => $model->getKey()])
                ->with("toast", ["success", "Zapisano"]);
        } else if ($rq->input("method") == "delete") {
            model($scope)::destroy($rq->id);
            return redirect()->route("admin.model.list", ["model" => $scope])
                ->with("toast", ["success", "Usunięto"]);
        }

        return back()->with("toast", ["error", "Nieprawidłowa operacja"]);
    }
    #endregion

    #region files
    public function files()
    {
        $path = request("path") ?? "";

        $directories = Storage::disk("public")->directories($path);
        $files = collect(Storage::disk("public")->files($path))
            ->filter(fn ($file) => !Str::contains($file, ".git"))
            // ->sortByDesc(fn ($file) => Storage::lastModified($file) ?? 0)
        ;

        $sections = [
            "files-list" => [
                "icon" => "folder-open",
                "label" => "Zawartość folderu",
            ],
            "files-upload" => [
                "icon" => "upload",
                "label" => "Wgrywanie plików",
            ],
            "folder-mgmt" => [
                "icon" => "folder-edit",
                "label" => "Zarządzanie folderem",
            ],
        ];

        return view("pages.shipyard.admin.files.list", compact(
            "files",
            "directories",
            "sections",
        ));
    }

    public function filesUpload(Request $rq)
    {
        foreach ($rq->file("files") as $file) {
            $file->storePubliclyAs(
                $rq->path,
                $rq->get("force_file_name") ?: $file->getClientOriginalName(),
                "public",
            );
        }

        return back()->with("toast", ["success", "Dodano"]);
    }

    public function filesDownload(Request $rq)
    {
        return Storage::download("public/".$rq->file);
    }

    public function filesDelete(Request $rq)
    {
        Storage::disk("public")->delete($rq->file);
        return back()->with("toast", ["success", "Usunięto"]);
    }

    public function filesSearch()
    {
        $files = collect(Storage::disk("public")->allFiles())
            ->filter(fn($file) => Str::contains($file, request("q")));

        return view("pages.shipyard.admin.files.search", compact(
            "files",
        ));
    }

    public function folderCreate(Request $rq)
    {
        $path = request("path") ?? "";
        Storage::disk("public")->makeDirectory($path . "/" . $rq->name);
        return redirect()->route("files", ["path" => $path])->with("toast", ["success", "Folder utworzony"]);
    }

    public function folderDelete(Request $rq)
    {
        $path = request("path") ?? "";
        Storage::disk("public")->deleteDirectory($path);
        return redirect()->route("files", ["path" => Str::contains($path, '/') ? Str::beforeLast($path, '/') : null])->with("toast", ["success", "Folder usunięty"]);
    }
    #endregion

    #region api
    private static function apiResponse(int $code, string $status, array $data = []) {
        return response()->json(["status" => $status, ...$data], $code);
    }

    public function apiGetModel(string $scope, ?int $id = null)
    {
        if (!Auth::user()?->hasRole(model($scope)::META["role"] ?? null)) abort(403);

        $data = ($id)
            ? model($scope)::find($id)
            : model($scope)::visible();

        if (!$data) return self::apiResponse(404, Str::of($scope)->singular() . " not found");

        return self::apiResponse(200, "listing $scope", ["count" => count($data), "data" => $data]);
    }
    #endregion
}
