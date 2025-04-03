<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DocsController extends Controller
{
    public function index()
    {

        return view('docs.index');
    }

    public function view(string $slug)
    {
        $doc = base_path("docs/{$slug}.md");
        if (!file_exists($doc)) abort(404);

        $doc = file_get_contents($doc);
        return view('docs.view', compact(
            "doc",
        ));
    }
}
