<?php

namespace App\Http\Controllers\Shipyard;

use App\Http\Controllers\Controller;
use App\Mail\Shipyard\ReportError;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class ErrorController extends Controller
{
    public function report(Request $rq): RedirectResponse
    {
        $recipients = User::whereHas("roles", fn ($q) => $q->where("name", "archmage"))->get();

        Mail::to($recipients)->send(new ReportError(
            Auth::user(),
            $rq->url,
            $rq->user_email,
            $rq->actions_description,
        ));

        return redirect("/")->with("toast", ["success", "Błąd zgłoszony. Dziękujemy za czujność i pomoc."]);
    }
}
