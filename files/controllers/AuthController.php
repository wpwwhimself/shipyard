<?php

namespace App\Http\Controllers\Shipyard;

use App\Http\Controllers\Controller;
use App\Models\Shipyard\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public const NOLOGIN_LOGIN_PART_LENGTH = 4;

    #region login
    public function login()
    {
        if (Auth::check()) return redirect()->intended(route("profile"));
        return view("pages.shipyard.auth.login");
    }

    public function processLogin(Request $rq)
    {
        $credentials = $rq->only(["name", "email", "password"]);

        if (setting("users_login_is") == "none") {
            // this form uses one string as both login and password - login is extracted from part of the password
            $user = User::where("name", substr($credentials["password"], 0, self::NOLOGIN_LOGIN_PART_LENGTH))->first();

            if ($user) {
                $credentials = ["name" => $user->name, "email" => $user->email, "password" => $rq->password];
            }
        }

        if (Auth::attempt($credentials, $rq->has("remember"))) {
            $rq->session()->regenerate();
            return redirect()->intended(route("profile"))->with("toast", ["success", "Zalogowano"]);
        }

        return back()->with("toast", ["error", "Nieprawidłowe dane logowania"]);
    }
    #endregion

    #region register
    public function register()
    {
        if (Auth::check()) return redirect()->intended(route("profile"));
        return view("pages.shipyard.auth.register");
    }

    public function processRegister(Request $rq)
    {
        if ($rq->proof != 4) return back()->with("toast", ["error", "Nie wierzymy, że nie jesteś robotem"]);
        if (!$rq->has("confirmed")) return back()->with("toast", ["error", "Zgoda na regulamin jest wymagana"]);

        $validator = Validator::make($rq->all(), [
            'name' => ['required', 'unique:users'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', 'confirmed'],
            'phone' => ['required'],
        ]);
        if ($validator->fails()) return view("auth.shipyard.register")->with("toast", ["error", "Coś poszło nie tak z Twoimi danymi"]);

        $user = User::create([
            "name" => $rq->name,
            "email" => $rq->email,
            "phone" => $rq->phone,
            "password" => Hash::make($rq->password),
            "company_data" => $rq->company_data,
        ]);
        $user->roles()->attach($rq->role);

        Auth::login($user);

        return redirect(route("profile"))->with("toast", ["success", "Konto zostało utworzone"]);
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
    public function setPassword(Request $rq)
    {
        if ($rq->has("id") && Auth::id() != $rq->id) {
            return redirect()->route("password.reset", ["id" => $rq->id]);
        }

        return view("pages.shipyard.auth.password.set");
    }

    public function processSetPassword(Request $rq)
    {
        // reset hasła z linku
        if ($rq->has("token")) {
            $validator = Validator::make($rq->all(), [
                'token' => 'required',
                'email' => 'required|email',
                'password' => 'required|confirmed',
            ]);
            if ($validator->fails()) return back()->with("toast", ["error", "Coś jest nie tak z dostarczonymi danymi"]);

            if (setting("users_login_is") == "none") {
                $user_with_this_password = User::all()->firstWhere(fn ($u) => Hash::check($rq->password, $u->password));
                if ($user_with_this_password) {
                    return back()->with("toast", ["error", "Hasło nie spełnia wymogów bezpieczeństwa"]);
                }
            }

            $status = Password::reset(
                $rq->only('email', 'password', 'password_confirmation', 'token'),
                function (User $user, string $password) {
                    $user->forceFill([
                        'name' => (setting("users_login_is") == "none") ? $user->name : substr($password, 0, self::NOLOGIN_LOGIN_PART_LENGTH),
                        'password' => Hash::make($password)
                    ]);
                    $user->save();
                }
            );

            return $status === Password::PasswordReset
                ? redirect()->route('login')->with("toast", ['success', "Hasło zostało zmienione"])
                : back()->with("toast", ['error', "Coś poszło nie tak podczas resetowania hasła"]);
        }

        // zmiana hasła z formularza
        if ($rq->has("user_id")) {
            $user = User::find($rq->user_id);
            if (!Hash::check($rq->current_password, $user->password)) {
                return back()->with("toast", ["error", "Obecne hasło nie jest poprawne"]);
            }
        }

        $validator = Validator::make($rq->all(), [
            'password' => ['required', 'confirmed'],
        ]);
        if ($validator->fails()) return back()->with("toast", ["error", "Coś jest nie tak z hasłem"]);

        if (setting("users_login_is") == "none") {
            $user_with_similar_password = User::where("name", substr($rq->password, 0, self::NOLOGIN_LOGIN_PART_LENGTH))
                ->where("id", "!=", $user->id)
                ->exists();

            if ($user_with_similar_password) {
                return back()->with("toast", ["error", "Hasło nie spełnia wymogów bezpieczeństwa"]);
            }
        }

        User::findOrFail(Auth::id())->update([
            "name" => (setting("users_login_is") == "none") ? substr($rq->password, 0, self::NOLOGIN_LOGIN_PART_LENGTH) : $user->name,
            "password" => Hash::make($rq->password),
        ]);
        return redirect(route("profile"))->with("toast", ["success", "Hasło zostało zmienione"]);
    }

    public function resetPassword(Request $rq, ?string $token = null)
    {
        if ($token) {
            return view("pages.shipyard.auth.password.set", compact("token"));
        }

        $user = User::find($rq->id);

        return view("pages.shipyard.auth.password.reset", compact("user"));
    }

    public function processResetPassword(Request $rq)
    {
        $status = Password::sendResetLink($rq->only('email'));
        $success = $status == Password::RESET_LINK_SENT;

        return back()->with("toast", $success
            ? ["success", "Link do resetowania hasła został wysłany"]
            : ["error", "Coś poszło nie tak podczas resetowania hasła"]
        );
    }
    #endregion

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect("/")->with("toast", ["success", "Wylogowano"]);
    }
}
