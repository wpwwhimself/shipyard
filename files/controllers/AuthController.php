<?php

namespace App\Http\Controllers\Shipyard;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;
use App\Models\User;
use App\Notifications\NewsletterSubscribedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    #region login
    public function login()
    {
        if (Auth::check()) return to_route("profile.index");
        return view("shipyard.auth.login");
    }

    public function processLogin(Request $rq)
    {
        $credentials = $rq->only(["name", "password"]);

        if (Auth::attempt($credentials, $rq->has("remember_token"))) {
            $rq->session()->regenerate();
            return redirect()->intended(route("profile"))->with("toast", ["success", "Zalogowano"]);
        }

        return back()->with("toast", ["error", "Nieprawidłowe dane logowania"]);
    }
    #endregion

    #region register
    public function register()
    {
        if (Auth::check()) return redirect(route("profile"));
        return view("auth.register");
    }

    public function processRegister(Request $rq)
    {
        if ($rq->proof != 4) return back()->with("error", "Nie wierzymy, że nie jesteś robotem");
        if (!$rq->has("confirmed")) return back()->with("error", "Zgoda na regulamin jest wymagana");

        $validator = Validator::make($rq->all(), [
            'name' => ['required', 'unique:users'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', 'confirmed'],
            'phone' => ['required'],
        ]);
        if ($validator->fails()) return view("auth.register")->with("error", "Coś poszło nie tak z Twoimi danymi");

        $user = User::create([
            "name" => $rq->name,
            "email" => $rq->email,
            "phone" => $rq->phone,
            "password" => Hash::make($rq->password),
            "company_data" => $rq->company_data,
        ]);
        $user->roles()->attach($rq->role);

        Auth::login($user);

        if ($rq->has("add_to_newsletter")) {
            NewsletterSubscriber::updateOrCreate([
                "email" => $rq->email,
            ], [
                "user_id" => $user->id,
            ]);
            $user->notify(new NewsletterSubscribedNotification());
        }

        return redirect(route("profile"))->with("success", "Konto zostało utworzone");
    }
    #endregion

    #region token
    public function apiToken(Request $rq)
    {
        $validator = Validator::make($rq->all(), [
            "email" => "required|email",
            "password" => "required",
        ]);

        $user = User::where("email", $rq->email)->first();

        if ($validator->fails() || !$user || !Hash::check($rq->password, $user->password)) {
            return response()->json([
                "status" => "error",
                "message" => $validator->errors()?->first() ?? "Nieprawidłowe dane logowania",
            ], 401);
        }

        return response()->json([
            "status" => "success",
            "token" => $user->createToken("token")->plainTextToken,
        ]);
    }
    #endregion

    #region password manipulation
    public function changePassword()
    {
        return view("auth.change-password");
    }

    public function processChangePassword(Request $rq)
    {
        $validator = Validator::make($rq->all(), [
            'password' => ['required', 'confirmed'],
        ]);
        if ($validator->fails()) return view("auth.change-password")->with("error", "Coś jest nie tak z hasłem");

        User::findOrFail(Auth::id())->update([
            "password" => Hash::make($rq->password),
        ]);
        return redirect(route("profile"))->with("success", "Hasło zostało zmienione");
    }

    public function forgotPassword()
    {
        return view("auth.forgot-password");
    }

    public function processForgotPassword(Request $rq)
    {
        $status = Password::sendResetLink($rq->only('email'));

        return $status === Password::ResetLinkSent
            ? back()->with("success", "Link do resetowania hasła został wysłany")
            : back()->with("error", "Coś poszło nie tak podczas resetowania hasła");
    }

    public function resetPassword($token)
    {
        return view("auth.change-password", compact("token"));
    }

    public function processResetPassword(Request $rq)
    {
        $validator = Validator::make($rq->all(), [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed',
        ]);
        if ($validator->fails()) return back()->with("error", "Coś jest nie tak z hasłem");

        $status = Password::reset(
            $rq->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ]);
                $user->save();
            }
        );

        return $status === Password::PasswordReset
            ? redirect()->route('login')->with('success', "Hasło zostało zmienione")
            : back()->with('error', "Coś poszło nie tak podczas resetowania hasła");
    }
    #endregion

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect("/")->with("success", "Wylogowano");
    }
}
