<?php

namespace App\Http\Controllers\Shipyard;

use App\Models\StandardPage;
use App\Models\User;
use App\Notifications\ContactFormMsgNotification;
use App\Notifications\ContactFormSentNotification;
use App\Notifications\ErrorReportNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Notification;
use Illuminate\View\View;
use Illuminate\Support\Str;

class FrontController extends Controller
{
    public function index()
    {
        return view("main");
    }

    public function standardPage(string $slug): View
    {
        $page = StandardPage::visible()->get()
            ->firstWhere(fn ($page) => $page->slug == $slug);
        if (!$page) abort(404);

        return view("standard-page", compact("page"));
    }

    #region contact form
    public function contactForm(): View
    {
        return view("pages.contact.form");
    }

    public function processContactForm(Request $rq): RedirectResponse
    {
        if ($rq->proof != 3) return back()->with("error", "Nie wierzymy, że nie jesteś robotem");

        // send message to eligible admins
        User::mailableAdmins()
            ->each(fn ($u) => $u->notify(new ContactFormMsgNotification($rq->except(["_token"]))));

        // send backfire to sender
        Notification::route("mail", $rq->email)
            ->notify(new ContactFormSentNotification($rq->except(["_token"])));

        return redirect()->route("main")->with("success", "Dziękujemy za wiadomość, odpowiemy wkrótce");
    }
    #endregion

    #region list & search
    public function list(string $model_name, Request $rq): View
    {
        $model = "App\\Models\\" . Str::of($model_name)->studly()->singular();
        $data = $model::visible(false)
            ->where(function ($q) use ($model, $rq) {
                // search query
                foreach ($model::queryableFields() as $field) {
                    $q = $q->orWhereRaw("lower($field) like lower('%{$rq->q}%')");
                }
                return $q;
            })
            ->get();

        // filtering
        foreach ($rq->except(["q", "sort", "page"]) as $filter => $value) {
            $flt_data = $model::FILTERS[$filter];

            $data = $data->filter(function ($item) use ($flt_data, $value) {
                switch ($flt_data["operator"] ?? "=") {
                    case ">=": return $item->discr($flt_data) >= $value;
                    case ">": return $item->discr($flt_data) > $value;
                    case "any": return count(array_intersect(collect($item->discr($flt_data))->toArray(), $value)) > 0;
                    case "all": return count(array_intersect(collect($item->discr($flt_data))->toArray(), $value)) == count($value);
                    default: return $item->discr($flt_data) == $value;
                }
            });
        }
        if ($model_name == "courses") {
            // hide past courses
            $data = $data->filter(fn ($c) => !$c->isExpired());
        }

        // sorting
        $default_sort = "-updated_at";
        $sort_direction = ($rq->get("sort", $default_sort)[0] == "-") ? "desc" : "asc";
        $sort_field = Str::after($rq->get("sort", $default_sort), "-");

        $data = $data->sort(fn ($a, $b) => ($sort_direction == "asc")
            ? $a->discr($model::getSorts()[$sort_field]) <=> $b->discr($model::getSorts()[$sort_field])
            : $b->discr($model::getSorts()[$sort_field]) <=> $a->discr($model::getSorts()[$sort_field])
        );

        $data = new LengthAwarePaginator(
            $data->slice(($rq->get("page", 1) - 1) * 25, 25),
            $data->count(),
            25,
            $rq->get("page", 1)
        );
        $data->withPath($model_name)
            ->appends($rq->all());

        return view("pages.$model_name.list", compact(
            "data",
        ));
    }
    #endregion

    #region view models
    public function view(string $model_name, int $id): View
    {
        $model = "App\\Models\\" . Str::of($model_name)->studly()->singular();
        $data = $model::find($id);

        return view("pages.".Str::of($model_name)->plural().".view", compact(
            "data",
        ));
    }
    #endregion

    #region error reporting
    public function viewErrorReport(string $model_name, int $id): View
    {
        $model = "App\\Models\\" . Str::of($model_name)->studly()->singular();
        $entity = $model::find($id);

        return view("errors.error-report", compact(
            "model_name", "id", "model",
            "entity",
        ));
    }

    public function processErrorReport(Request $rq): RedirectResponse
    {
        User::mailableAdmins("course-master")
            ->each(fn ($u) => $u->notify(new ErrorReportNotification($rq->except(["_token"]))));

        return redirect()->route("front-view", ["model_name" => $rq->model_name, "id" => $rq->id])->with("success", "Zgłoszenie wysłane, dziękujemy");
    }
    #endregion
}
