<?php

namespace App\Http\Controllers\Shipyard;

use App\Http\Controllers\Controller;
use App\Models\Setting as LocalSetting;
use App\Models\Shipyard\Role;
use App\Models\Shipyard\Setting;
use App\Models\Shipyard\User;
use Illuminate\Http\JsonResponse;
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
                    [
                        "name" => "menu_nav_items_in_top_bar_count",
                        "label" => "Liczba przypiętych pozycji menu",
                        "icon" => "menu",
                        "hint" => "Pasek nawigacji na górze strony będzie wyświetlać pierwsze kilka pozycji, a pozostałe będą dostępne w rozwijanym menu. Wyczyść wartość, aby nie ukrywać żadnej pozycji.",
                        "min" => 0,
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
                "subtitle" => "Logowanie i rejestracja",
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
                        "name" => "users_password_reset_mode",
                        "label" => "Proces odzyskiwania hasła",
                        "icon" => "key-change",
                        "hint" => "W jaki sposób przebiega resetowanie hasła.",
                        "selectData" => [
                            "options" => [
                                ["label" => "Standardowo (link do resetu wysyłany mailem)", "value" => "email",],
                                ["label" => "Administrator nadaje hasło", "value" => "manual",],
                            ],
                        ],
                    ],
                    [
                        "subsection_title" => "Rejestracja",
                        "subsection_icon" => "account-plus",
                        "fields" => [
                            [
                                "name" => "users_self_register_enabled",
                                "label" => "Zezwól na rejestrację",
                                "icon" => "account-plus",
                                "hint" => "Jeśli ta opcja jest włączona, użytkownicy mogą sami zakładać własne konta. W przeciwnym wypadku tworzenie kont jest możliwe tylko przez administratora.",
                            ],
                            [
                                "name" => "users_terms_and_conditions_page_url",
                                "label" => "Strona z regulaminem",
                                "icon" => "script-text",
                                "hint" => "Link do strony z regulaminem. Możesz ją utworzyć np. jako podstronę. Jeśli to pole jest puste, podczas rejestracji nie zostanie wyświetlone pole do zaznaczenia zgody na regulamin. W przeciwnym wypadku pojawi się tam pole oraz link do strony.",
                            ],
                            [
                                "name" => "users_turing_question",
                                "label" => "Pytanie do testu antyspamowego",
                                "icon" => "robot-confused",
                                "hint" => "Treść pytania wyświetlanego podczas rejestracji.",
                            ],
                            [
                                "name" => "users_turing_answer",
                                "label" => "Odpowiedź do testu antyspamowego",
                                "icon" => "robot-happy",
                                "hint" => "Odpowiedź na pytanie antyspamowe.",
                            ],
                            [
                                "name" => "users_default_roles[]",
                                "label" => "Role dla nowego użytkownika",
                                "icon" => model_icon('roles'),
                                "selectData" => [
                                    "optionsFromScope" => [
                                        Role::class,
                                        "withoutArchmage",
                                        "option_label",
                                        "name",
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
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
            $value = ($setting->type == "checkbox") ? $rq->has($setting->name)
                : ($setting->type == "select-multiple" ? implode(",", $rq->get(Str::replace("[]", "", $setting->name), []))
                : $rq->get($setting->name));
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
        if ($scope === "users" && !Auth::user()?->hasRole("technical")) abort(403); // manual user editing permission, as this is a special case

        $meta = model($scope)::META;
        $actions = model($scope)::getActions("list");
        $sorts = model($scope)::getSorts($meta["defaultSort"] ?? null);
        $filters = model($scope)::getFilters($meta["defaultFltr"] ?? null);
        $extraSections = collect(model($scope)::getExtraSections())->filter(fn ($es) => Str::contains($es["show-on"] ?? "", "list"))->toArray();

        return view("pages.shipyard.admin.model.list", compact("meta", "scope", "actions", "sorts", "filters", "extraSections"));
    }

    public function filterListModel(Request $rq, string $scope): JsonResponse
    {
        $meta = model($scope)::META;
        $listScope = $meta["listScope"] ?? "forAdminList";
        $filters = collect($rq->except("_token"))
            ->map(fn ($v, $k) => is_array($v) ? array_filter($v, fn ($vv) => $vv !== null) : $v)
            ->filter(fn ($v, $k) => $v !== null);
        $data = model($scope)::$listScope(
            $filters["sort"] ?? $meta["defaultSort"] ?? null,
            $filters["fltr"] ?? $meta["defaultFltr"] ?? null
        );

        return response()->json([
            "data" => $data,
            "html" => view("components.shipyard.app.model.list", compact("data", "scope"))->render(),
            "url" => route("admin.model.list", ["model" => $scope, ...$filters]),
        ]);
    }

    public function editModel(string $scope, int|string|null $id = null): View|RedirectResponse
    {
        if (!Auth::user()?->hasRole(model($scope)::META["role"] ?? null)) abort(403);
        if ($scope === "users" && Auth::id() != $id && !Auth::user()?->hasRole("technical")) abort(403); // manual user editing permission, as this is a special case

        $meta = model($scope)::META;
        $data = model($scope)::find($id);
        $fields = model($scope)::getFields();
        $connections = model($scope)::getConnections();
        $actions = model($scope)::getActions("edit");
        $extraSections = collect(model($scope)::getExtraSections())->filter(fn ($es) => Str::contains($es["show-on"] ?? "", "edit"))->toArray();

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
            collect($extraSections)->map(fn ($esData, $esId) => [
                ...$esData,
                "id" => $esId,
                "show" => Auth::user()?->hasRole($esData["role"] ?? null),
            ])
                ->filter(fn ($es) => $es["show"])
                ->toArray(),
        );

        if ($data?->is_uneditable && !Auth::user()?->hasRole("archmage")) {
            return redirect()->route("admin.model.list", ["model" => $scope])->with("toast", ["error", "Tego modelu nie można edytować"]);
        }

        return view("pages.shipyard.admin.model.edit", compact("data", "meta", "scope", "fields", "connections", "extraSections", "sections", "actions"));
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

            if (method_exists($model_name, "validateOnSave")) {
                $validation = $model_name::validateOnSave($data);
                if ($validation["result"] === false) {
                    return back()->with("toast", ["error", $validation["message"]]);
                }
            }

            if (method_exists($model_name, "autofillOnSave")) {
                $data = $model_name::autofillOnSave($data);
            }

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
