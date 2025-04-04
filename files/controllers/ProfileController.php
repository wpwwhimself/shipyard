<?php

namespace App\Http\Controllers\Shipyard;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function myProfile(): View
    {
        $survey_texts = Auth::user()->answered_all_questions
            ? [
                "title" => "Ankieta",
                "text" => "Ankieta została już wypełniona. Możesz przejrzeć i poprawić Twoje odpowiedzi poniżej.",
                "button_text" => "Przejrzyj ankietę",
            ] : [
                "title" => "Mamy do Ciebie kilka pytań",
                "text" => "Wypełnij ankietę i daj nam znać, czego potrzebujesz od naszej aplikacji.",
                "button_text" => "Wypełnij ankietę",
            ];

        return view('pages.profile.view', compact(
            "survey_texts",
        ));
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
