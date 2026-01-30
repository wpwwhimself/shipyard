<?php

namespace App\Http\Controllers\Shipyard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
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
                ...$this->extractMetaFromDoc($doc["path"]),
            ])
            ->filter(fn ($doc) => Auth::user()?->hasRole($doc["role"] ?? null))
            ->sortBy("full_basename");

        return $docs;
    }

    private function extractMetaFromDoc(string $path, bool $output_file_instead = false)
    {
        $doc = file_get_contents($path);
        $lines = explode("\n", $doc);
        $meta = [];

        if (($lines[0][0] ?? "") === "{") {
            while (($lines[0][0] ?? "") !== "}") {
                $meta[] = array_shift($lines);
            }
            $meta[] = array_shift($lines);
            $meta = implode("\n", $meta);
            $meta = json_decode($meta, true);
        }

        if ($meta === null) {
            throw new \Error("⚓ Invalid meta in $path");
        }

        return $output_file_instead
            ? implode("\n", $lines)
            : $meta;
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
        $doc = $this->extractMetaFromDoc($doc["path"], true);

        return view("pages.shipyard.docs.view", compact(
            "docs",
            "title",
            "doc",
        ));
    }

    public function spellbook()
    {
        $docs = $this->prepareDocs();

        $spells = SpellbookController::SPELLS;

        return view("pages.shipyard.docs.spellbook", compact(
            "docs",
            "spells",
        ));
    }
}
