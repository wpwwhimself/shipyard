<?php

namespace App\Http\Controllers\Shipyard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class DocsController extends Controller
{
    private function prepareDocs(): Collection
    {
        //find all md files in docs, regardless of subfolders
        $docs = collect(glob(base_path("docs/*.md")))
            ->merge(glob(base_path("docs/**/*.md")))
            ->map(fn ($path) => [
                "path" => $path,
                "full_basename" => basename($path, ".md"),
                ])
            ->map(fn ($doc) => [
                ...$doc,
                "title" => preg_replace("/^\d{1,3}-/", "", $doc["full_basename"]),
            ])
            ->map(fn ($doc) => [
                ...$doc,
                "slug" => Str::slug($doc["title"]),
            ])
            ->sortBy("full_basename");

        return $docs;
    }

    public function index()
    {
        $docs = $this->prepareDocs();

        return view("pages.shipyard.docs.index", compact(
            "docs",
        ));
    }

    public function view(string $slug)
    {
        $docs = $this->prepareDocs();
        
        $doc = $docs->firstWhere("slug", $slug);
        if (!$doc) abort(404);
        $title = $doc["title"];
        $doc = file_get_contents($doc["path"]);

        return view("pages.shipyard.docs.view", compact(
            "docs",
            "title",
            "doc",
        ));
    }
}
