<?php

namespace App\Http\Controllers\Shipyard;

use App\Http\Controllers\Controller;
use App\Models\Shipyard\StandardPage;
use App\Models\Shipyard\User;
use App\Notifications\ContactFormMsgNotification;
use App\Notifications\ContactFormSentNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\View\View;
use Illuminate\Support\Str;

class FrontController extends Controller
{
    public function index()
    {
        return view("welcome_to_shipyard");
    }

    public function standardPage(string $slug): View
    {
        $page = StandardPage::visible()->get()
            ->firstWhere(fn ($page) => $page->slug == $slug);
        if (!$page) abort(404);

        return view("pages.shipyard.standard-page", compact("page"));
    }
}
