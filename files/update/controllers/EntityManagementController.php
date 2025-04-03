<?php

namespace App\Http\Controllers\Shipyard;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EntityManagementController extends Controller
{
    #region constants and helpers
    public const SCOPES = [
        "users" => [
            "model" => \App\Models\User::class,
            "role" => "technical",
        ],
        "courses" => [
            "model" => \App\Models\Course::class,
            "role" => "course-master",
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

    private function getModelName(string $scope): string
    {
        return "App\\Models\\".(Str::of($scope)->singular()->studly()->toString());
    }
    #endregion

    #region general
    public function listModel(string $scope): View
    {
        if (!User::hasRole(self::SCOPES[$scope]["role"])) abort(403);

        $modelName = $this->getModelName($scope);
        $meta = array_merge(self::SCOPES[$scope], $modelName::META);
        $data = $modelName::forAdminList()
            ->get();

        return view("admin.entity-management.list", compact("data", "meta", "scope", "modelName"));
    }
    #endregion

    #region autofill
    public function listCategories(string $model_name): JsonResponse
    {
        $model = "App\\Models\\" . Str::of($model_name)->studly()->singular();
        $categories = $model::classes("categories")->values();
        return response()->json($categories);
    }
    #endregion
}
