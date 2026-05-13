<?php

namespace App\Http\Controllers\Shipyard;

use App\Http\Controllers\Controller;
use App\Mail\Shipyard\ContactFormQuery;
use App\Models\Shipyard\StandardPage;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
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

    #region contact form
    public function processContactForm(Request $rq): RedirectResponse
    {
        if ($rq->test != setting("users_turing_answer")) return back()->with("toast", ["error", "Nie wierzymy, że nie jesteś robotem. Odpowiedz poprawnie na pytanie z formularza."]);

        $recipients = User::whereHas("roles", fn ($q) => $q->where("name", "mediator"))->get();

        Mail::to($recipients)->send(new ContactFormQuery(
            [
                "name" => $rq->user_name,
                "email" => $rq->user_email,
                "phone" => $rq->user_phone,
            ],
            $rq->contents,
        ));

        return back()->with("toast", ["success", "Wiadomość została wysłana. Dziękujemy."]);
    }
    #endregion

    #region fetching components
    public function icon(string $icon) {
        return view("components.shipyard.app.icon", ["name" => $icon])->render();
    }
    #endregion
}
