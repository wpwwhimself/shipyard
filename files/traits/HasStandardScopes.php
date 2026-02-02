<?php

namespace App\Traits\Shipyard;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

trait HasStandardScopes
{
    public function scopeSortAndFilter($query, $sort = null, $filters = null)
    {
        // setup
        $filterData = self::getFilters();
        $sortData = !empty($sort)
            ? self::getSorts()[Str::after($sort, "-")]
            : null;

        // pre-get filters for db filtering
        foreach ($filters ?? [] as $filter_name => $filter_value) {
            if ($filterData[$filter_name]["compare-using"] != "field") continue;

            $query = $query->where(
                $filterData[$filter_name]["discr"],
                $filterData[$filter_name]["operator"] ?? "=",
                $filter_value
            );
        }
        // pre-get filters for allowNulls
        collect($filterData)->filter(fn ($fd, $filter_name) =>
            $fd["compare-using"] == "field"
            && ($fd["allowNulls"] ?? false) == true
            && !in_array($filter_name, array_keys($filters ?? []))
        )
            ->each(fn ($fd) => $query = $query->whereNull($fd["discr"]));

        // pre-get sort for db sorting
        if (!$sort) {
            if (Schema::hasColumn($this->getTable(), "order")) $query = $query->orderBy("order");
            if (Schema::hasColumn($this->getTable(), "name")) $query = $query->orderBy("name");
        }

        if ($sortData && $sortData["compare-using"] == "field") {
            $query = $query->orderBy(
                $sortData["discr"],
                $sort[0] == "-" ? "desc" : "asc",
            );
        }

        $data = $query->get();

        // post-get filters for model filtering
        //todo obsłużyć filtrowanie po funkcji z różnymi operatorami
        foreach ($filters ?? [] as $filter_name => $filter_value) {
            if ($filterData[$filter_name]["compare-using"] != "function") continue;

            $data = $data->filter(
                fn ($i) => $i->{$filterData[$filter_name]["discr"]} == $filter_value,
            );
        }

        // post-get sort for model sorting
        if ($sortData && $sortData["compare-using"] == "function") {
            $data = $data->{$sort[0] == "-" ? "sortByDesc" : "sortBy"}(fn ($i) => $i->{$sortData["discr"]});
        }

        return $data;
    }

    public function scopeForAdminList($query, $sort = null, $filters = null)
    {
        $page = request("page", 1);
        $perPage = request("prpg", 25);

        $data = $query->sortAndFilter($sort, $filters);
        $data = new LengthAwarePaginator(
            $data->slice($perPage * ($page - 1), $perPage),
            $data->count(),
            $perPage,
            $page,
            [
                "path" => request()->url(),
                "query" => request()->query(),
            ],
        );

        return $data;
    }

    public function scopeVisible($query, bool $sort = true)
    {
        if (
            !Schema::hasColumn($query->getModel()->getTable(), "visible")
            || $sort && (
                !Schema::hasColumn($query->getModel()->getTable(), "name")
                || !Schema::hasColumn($query->getModel()->getTable(), "order")
            )
        ) throw new \Error("⚓ Model ".$query->getModel()." cannot retrieve visible list due to missing column `visible`, `name` or `order`. Redeclare `scopeVisible`.");

        $query = $query->where("visible", ">", 1 - Auth::check());
        if ($sort) $query = $query
            ->orderByRaw("0 - `order` desc")
            ->orderBy("name");
        return $query;
    }

    public function scopeRecent($query, ?string $except_id = null)
    {
        if (
            !Schema::hasColumn($query->getModel()->getTable(), "id")
            || !Schema::hasColumn($query->getModel()->getTable(), "updated_at")
        ) throw new \Error("⚓ Model ".$query->getModel()." cannot retrieve recent list due to missing column `id` or `updated_at`. Redeclare `scopeRecent`.");

        return $query->visible()
            ->orderByDesc("updated_at")
            ->where("id", "!=", $except_id)
            ->limit(3);
    }

    public function scopeForConnection($query)
    {
        if (
            !Schema::hasColumn($query->getModel()->getTable(), "name")
        ) throw new \Error("⚓ ".$query->getModel()::class."'s connection cannot retrieve options list due to missing column `name`. Redeclare `scopeForConnection`.");

        return $query->visible()
            ->orderBy("name");
    }

    public function scopeClasses($query, string $field)
    {
        $data = $query->select($field)->get()
            ->pluck($field)
            ->flatten()
            ->sort()
            ->unique()
            ->filter();

        return $data->combine($data);
    }
}
