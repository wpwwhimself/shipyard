<?php

namespace App\Http\Controllers\Shipyard;

use App\Models\AdvertSetting;
use App\Models\Role;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    #region constants and helpers
    public const SCOPES = [
        "users" => [
            "model" => \App\Models\User::class,
            "role" => "technical",
        ],
        "user-survey-questions" => [
            "model" => \App\Models\UserSurveyQuestion::class,
            "role" => "blogger",
        ],
        "standard-pages" => [
            "model" => \App\Models\StandardPage::class,
            "role" => "technical",
        ],
        "social-media" => [
            "model" => \App\Models\SocialMedium::class,
            "role" => "blogger",
        ],
        "blog-articles" => [
            "model" => \App\Models\BlogArticle::class,
            "role" => "blogger",
        ],
        "courses" => [
            "model" => \App\Models\Course::class,
            "role" => "course-master|course-manager",
        ],
        "industries" => [
            "model" => \App\Models\Industry::class,
            "role" => "course-master",
            "disabled" => true,
        ],
        "review-criteria" => [
            "model" => \App\Models\ReviewCriterion::class,
            "role" => "technical",
        ],
        "newsletter-subscribers" => [
            "model" => \App\Models\NewsletterSubscriber::class,
            "role" => "technical",
        ],
        "universities" => [
            "model" => \App\Models\University::class,
            "role" => "university-master",
        ],
        "films" => [
            "model" => \App\Models\Film::class,
            "role" => "film-master",
        ],
    ];

    public const SCOPE_GROUPS = [
        "Użytkownicy" => [
            "icon" => "account",
            "scopes" => ["users", "user-survey-questions", "industries", "review-criteria", "newsletter-subscribers",],
        ],
        "Treści" => [
            "icon" => "text",
            "scopes" => ["standard-pages", "courses", "universities", "films", "blog-articles", "social-media",],
        ],
    ];

    public const VISIBILITIES = [
        0 => "nikt",
        1 => "zalogowani",
        2 => "wszyscy",
    ];

    private function getModelName(string $scope): string
    {
        return "App\\Models\\".(Str::of($scope)->singular()->studly()->toString());
    }

    private function getFields(string $scope): array
    {
        $modelName = $this->getModelName($scope);
        return array_merge(array_filter([
            "name" => in_array($scope, ["newsletter-subscribers"]) ? null : [
                "type" => "text",
                "label" => "Nazwa",
                "hint" => "Tytuł wpisu, wyświetlany jako pierwszy.",
                "icon" => "card-text",
                "required" => true,
            ],
            "visible" => in_array($scope, ["users", "industries", "newsletter-subscribers"]) ? null : [
                "type" => "select", "options" => self::VISIBILITIES,
                "label" => "Widoczny dla",
                "icon" => "eye",
                "role" => "course-master",
            ],
            "order" => in_array($scope, ["users", "industries", "newsletter-subscribers"]) ? null : [
                "type" => "number",
                "label" => "Wymuś kolejność",
                "icon" => "order-numeric-ascending",
                "role" => "course-master",
            ],
        ]), $modelName::FIELDS);
    }

    private function getConnections(string $scope): array
    {
        $modelName = $this->getModelName($scope);
        return array_filter(array_merge(
            defined($modelName."::CONNECTIONS") ? $modelName::CONNECTIONS : [],
        ));
    }

    private function getActions(string $scope, string $showOn): array
    {
        $modelName = $this->getModelName($scope);
        return array_filter(array_merge(
            defined($modelName."::ACTIONS")
                ? array_filter($modelName::ACTIONS, fn ($a) => ($a["show-on"] ?? "list") == $showOn)
                : [],
        ));
    }
    #endregion

    #region general settings
    public function settings(): View
    {
        $setting = Setting::class;

        return view("admin.settings", compact(
            "setting",
        ));
    }

    public function processSettings(Request $rq): RedirectResponse
    {
        foreach (Setting::all() as $setting) {
            $value = in_array($setting->name, [])
                ? $rq->has($setting->name)
                : $rq->get($setting->name);
            $setting->update(["value" => $value]);
        }

        return redirect()->route("admin-settings")->with("success", "Zapisano");
    }

    public function advertSettings(): View
    {
        $setting = AdvertSetting::class;

        return view("admin.advert-settings", compact(
            "setting",
        ));
    }

    public function processAdvertSettings(Request $rq): RedirectResponse
    {
        foreach (AdvertSetting::all() as $setting) {
            $namecode = "$setting->ad_type%$setting->name";
            $value = in_array($setting->name, ["white_text"])
                ? $rq->has($namecode)
                : $rq->get($namecode);
            $setting->update(["value" => $value]);
        }

        return redirect()->route("admin-advert-settings")->with("success", "Zapisano");
    }
    #endregion

    #region automatic model editors
    public function listModel(string $scope): View
    {
        if (!User::hasRole(self::SCOPES[$scope]["role"])) abort(403);

        $modelName = $this->getModelName($scope);
        $meta = array_merge(self::SCOPES[$scope], $modelName::META);
        $data = $modelName::forAdminList()
            ->paginate(25);
        $actions = $this->getActions($scope, "list");

        return view("admin.list-model", compact("data", "meta", "scope", "actions"));
    }

    public function editModel(string $scope, ?int $id = null): View
    {
        if (
            !User::hasRole(self::SCOPES[$scope]["role"])
            && !($scope == "users" && Auth::id() == $id) // user can edit themself
        ) abort(403);

        $modelName = $this->getModelName($scope);
        $meta = array_merge(self::SCOPES[$scope], $modelName::META);
        $data = $modelName::find($id);
        $fields = $this->getFields($scope);
        $connections = $this->getConnections($scope);
        $actions = $this->getActions($scope, "edit");

        if ($data && $scope == "courses") {
            if (User::hasRole("course-manager", true) && $data->created_by != Auth::id()) {
                abort(403);
            }
        }

        return view("admin.edit-model", compact("data", "meta", "scope", "fields", "connections", "actions"));
    }

    public function processEditModel(Request $rq, string $scope): RedirectResponse
    {
        if (
            !User::hasRole(self::SCOPES[$scope]["role"])
            && !($scope == "users" && Auth::id() == $rq->id) // user can edit themself
        ) abort(403);

        $modelName = $this->getModelName($scope);
        $fields = $this->getFields($scope);
        $data = $rq->except("_token", "_connections", "method");
        foreach ($fields as $name => $fdata) {
            switch ($fdata["type"]) {
                case "checkbox": $data[$name] ??= false; break;
                case "JSON": $data[$name] = json_decode($data[$name], count($fdata["column-types"]) == 2); break;
            }
            if ($fdata["type"] == "checkbox") $data[$name] ??= false;
            if (($fdata["required"] ?? false) && !$data[$name]) return back()->with("error", "Pole $fdata[label] jest wymagane");
        }

        if ($scope == "courses" && User::hasRole("course-manager")) {
            $data["trainer_organization"] ??= Auth::user()->company_data["Nazwa firmy"];
            $data["visibility"] ??= 2;
        }

        if ($rq->input("method") == "save") {
            $model = $modelName::updateOrCreate(
                ["id" => $rq->id],
                $data,
            );

            if ($rq->has("_connections")) {
                foreach ($rq->get("_connections") as $connection) {
                    switch ($modelName::CONNECTIONS[$connection]["mode"]) {
                        case "many":
                            $model->{$connection}()->sync($rq->get($connection));
                            break;
                    }
                }
            }

            collect(Role::MANAGER_NOTIFICATIONS)
                ->filter(fn ($an) =>
                    User::hasRole($an["role"], true)
                    && in_array($scope, explode("|", $an["scope"]))
                )
                ->each(fn ($an) =>
                    User::mailableAdmins($an["message"]["admins-with-role"] ?? null)
                        ->each(fn ($u) => $u->notify(
                            new ("App\\Notifications\\" . $an["message"]["notification"])([
                                "model_name" => $scope,
                                "id" => $model->id,
                            ])
                        ))
                );

            return redirect()->route("admin-edit-model", ["model" => $scope, "id" => $model->id])
                ->with("success", "Zapisano");
        } else if ($rq->input("method") == "delete") {
            $modelName::destroy($rq->id);
            return redirect()->route("admin-list-model", ["model" => $scope])
                ->with("success", "Usunięto");
        }

        return back()->with("error", "Nieprawidłowa operacja");
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

        return view("admin.files.list", compact(
            "files",
            "directories",
        ));
    }

    public function filesUpload(Request $rq)
    {
        foreach ($rq->file("files") as $file) {
            $file->storePubliclyAs(
                $rq->path,
                $file->getClientOriginalName(),
                "public",
            );
        }

        return back()->with("success", "Dodano");
    }

    public function filesDownload(Request $rq)
    {
        return Storage::download("public/".$rq->file);
    }

    public function filesDelete(Request $rq)
    {
        Storage::disk("public")->delete($rq->file);
        return back()->with("success", "Usunięto");
    }

    public function folderCreate(Request $rq)
    {
        $path = request("path") ?? "";
        Storage::disk("public")->makeDirectory($path . "/" . $rq->name);
        return redirect()->route("files-list", ["path" => $path])->with("success", "Folder utworzony");
    }

    public function folderDelete(Request $rq)
    {
        $path = request("path") ?? "";
        Storage::disk("public")->deleteDirectory($path);
        return redirect()->route("files-list", ["path" => Str::contains($path, '/') ? Str::beforeLast($path, '/') : null])->with("success", "Folder usunięty");
    }
    #endregion

    #region api
    private static function apiResponse(int $code, string $status, array $data = []) {
        return response()->json(["status" => $status, ...$data], $code);
    }

    public function apiGetModel(string $scope, ?int $id = null)
    {
        if (!User::hasRole(self::SCOPES[$scope]["role"])) abort(403);

        $modelName = $this->getModelName($scope);
        $data = ($id)
            ? $modelName::find($id)
            : $modelName::all();

        if (!$data) return self::apiResponse(404, Str::of($scope)->singular() . " not found");

        return self::apiResponse(200, "listing $scope", ["count" => count($data), "data" => $data]);
    }
    #endregion
}
