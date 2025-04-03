<?php

namespace App\Traits\Shipyard;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

trait HasStandardScopes
{
    public function scopeForAdminList($query)
    {
        if (Schema::hasColumn($this->getTable(), "order")) $query = $query->orderBy("order");
        if (Schema::hasColumn($this->getTable(), "name")) $query = $query->orderBy("name");
        return $query;
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
        return $query->where("visible", ">", 1 - Auth::check())
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
