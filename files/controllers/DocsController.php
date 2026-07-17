<?php

namespace App\Http\Controllers\Shipyard;

use App\Http\Controllers\Controller;
use App\Scaffolds\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class DocsController extends Controller
{
    public static function prepareDocs(): Collection
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
                ...self::extractMetaFromDoc($doc["path"]),
            ])
            ->filter(fn ($doc) => Auth::user()?->hasRole($doc["role"] ?? null))
            ->sortBy("full_basename");

        return $docs;
    }

    private static function extractMetaFromDoc(string $path, bool $output_file_instead = false)
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
        $docs = self::prepareDocs();

        return view("shipyard::pages.docs.index", compact(
            "docs",
        ));
    }

    public function view(string $slug)
    {
        $docs = self::prepareDocs();

        $doc = $docs->firstWhere("slug", $slug);
        if (!$doc) abort(404);
        $title = $doc["title"];
        $icon = $doc["icon"] ?? null;
        $models = array_filter(explode(",", $doc["models"] ?? null));
        $doc = $this->extractMetaFromDoc($doc["path"], true);

        $headings_raw = collect(preg_split("/\r?\n/", $doc))
            ->filter(fn ($line) => Str::startsWith($line, "#"))
            ->map(fn ($line) => [
                "heading" => Str::of($line)->replace("#", "")->trim(),
                "lvl" => Str::substrCount($line, "#"),
            ])
            ->values();
        $headings = $headings_raw->map(fn ($hdata, $i) => implode("", [
                str_repeat("    ", $hdata["lvl"] - 1),
                "1. ",
                $hdata["heading"],
            ]))
            ->join("\r\n");

        // docs split by h1s
        $doc = collect(preg_split("/\n# /", $doc))
            ->filter(fn ($chpt) => !preg_match("/^\r?\n?$/", $chpt))
            ->map(function ($chpt) {
                $first_newline = null;
                preg_match("/(\r?\n){2}/", $chpt, $first_newline, PREG_OFFSET_CAPTURE);
                $chpt = substr($chpt, $first_newline[0][1]);
                $chpt = trim($chpt);

                return $chpt;
            });
        $doc = $headings_raw->filter(fn ($hdata) => $hdata["lvl"] === 1)->pluck("heading")->values()
            ->combine($doc);

        return view("shipyard::pages.docs.view", compact(
            "docs",
            "title",
            "icon",
            "doc",
            "headings",
            "models",
        ));
    }

    #region special pages
    public function spellbook()
    {
        $docs = self::prepareDocs();

        $spells = SpellbookController::SPELLS;

        return view("shipyard::pages.docs.spellbook", compact(
            "docs",
            "spells",
        ));
    }

    public function roles()
    {
        $docs = self::prepareDocs();

        $roles = Role::getAll();

        return view("shipyard::pages.docs.roles", compact(
            "docs",
            "roles",
        ));
    }
    #endregion
}
