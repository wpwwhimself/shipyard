<?php

namespace App\Traits\Shipyard;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

trait HasStandardScopes
{
    public function scopeForAdminList($query, $sort = null, $filters = null)
    {
        // setup
        $page = request("page", 1);
        $perPage = request("prpg", 25);
        $filterData = self::getFilters();
        $sortData = !empty($sort)
            ? self::getSorts()[Str::after($sort, "-")]
            : null;

        if (Schema::hasColumn($this->getTable(), "order")) $query = $query->orderBy("order");
        if (Schema::hasColumn($this->getTable(), "name")) $query = $query->orderBy("name");

        // pre-get filters for db filtering
        foreach ($filters ?? [] as $filter_name => $filter_value) {
            if ($filterData[$filter_name]["compare-using"] != "field") continue;

            $query = $query->where(
                $filterData[$filter_name]["discr"],
                $filterData[$filter_name]["operator"] ?? "=",
                $filter_value
            );
        }

        // pre-get sort for db sorting
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
        $query = $query->where("visible", ">", 1 - Auth::check());
        if ($sort) $query = $query
            ->orderBy("order")
            ->orderBy("name");
        return $query;
    }

    public function scopeRecent($query, ?string $except_id = null)
    {
        return $query->visible()
            ->orderByDesc("updated_at")
            ->where("id", "!=", $except_id)
            ->limit(3);
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
