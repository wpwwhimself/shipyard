<?php

namespace Tests\Browser;

use App\Models\Setting;
use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class AuthTest extends DuskTestCase
{
    #region setup
    public function tearDown(): void
    {
        session()->flush();
        parent::tearDown();
        $this->browse(function (Browser $browser) {
            $browser->driver->manage()->deleteAllCookies();
        });
    }
    #endregion

    public function test_login_is_possible(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->assertSee('Logowanie')
                ->click(".button[data-label*='Logowanie']")
                ->waitFor("#modal-card")
                ->with("#modal-card", fn ($modal) => $modal
                    ->assertSee("Logowanie")
                    ->assertSee("Zatwierdź")
                );
        });
    }

    public function test_user_can_login_via_name(): void
    {
        $this->browse(function (Browser $browser) {
            Setting::find("users_login_is")->update(["value" => "name"]);
            $user = "archmage";

            $browser->visit("/")
                ->click(".button[data-label*='Logowanie']")
                ->waitFor("#modal-card")
                ->with("#modal-card", fn ($modal) => $modal
                    ->type("name", $user)
                    ->type("password", $user)
                )
                ->clickAndWaitForReload(".button[data-label*='Zatwierdź']")->pause(1e3)
                ->assertSee("Mój profil")
                ->assertSee($user);
        });
    }

    public function test_user_can_login_via_email(): void
    {
        $this->browse(function (Browser $browser) {
            Setting::find("users_login_is")->update(["value" => "email"]);
            $user = "archmage";
            $email = "hello@example.com";

            $browser->visit("/")
                ->click(".button[data-label*='Logowanie']")
                ->waitFor("#modal-card")
                ->with("#modal-card", fn ($modal) => $modal
                    ->type("email", $email)
                    ->type("password", $user)
                )
                ->clickAndWaitForReload(".button[data-label*='Zatwierdź']")->pause(1e3)
                ->assertSee("Mój profil")
                ->assertSee($user);
        });
    }

    public function test_user_can_register(): void
    {
        $this->browse(function (Browser $browser) {
            Setting::find("users_self_register_enabled")->update(["value" => true]);
            User::where("name", "test")->first()?->forceDelete();

            $browser->visit("/")
                ->click(".button[data-label*='Logowanie']")
                ->waitFor("#modal-card")
                ->clickAndWaitForReload("#modal-card .button[data-label*='Rejestracja']")->pause(1e3)
                ->assertSee("Rejestracja")
                ->type("name", "test")
                ->type("email", "test@test.test")
                ->type("password", "testttest")
                ->type("password_confirmation", "testttest")
                ->type("test", 3)
                ->clickAndWaitForReload(".button[data-label*='Zarejestruj się']")->pause(1e3)
                ->assertSee("Mój profil")
                ->assertSee("test");
        });
    }

    public function test_user_cannot_register_if_setting_is_disabled(): void
    {
        $this->browse(function (Browser $browser) {
            Setting::find("users_self_register_enabled")->update(["value" => false]);

            $browser->visit("/")
                ->click(".button[data-label*='Logowanie']")
                ->waitFor("#modal-card")
                ->with("#modal-card", fn ($modal) => $modal
                    ->assertDontSee("Rejestracja")
                );
            $browser->visit("/auth/register")
                ->assertUrlIs("/");
        });
    }

    public function test_user_can_login_via_password(): void
    {
        $this->browse(function (Browser $browser) {
            Setting::find("users_login_is")->update(["value" => "none"]);
            $user = "testtest";

            $browser->visit("/")
                ->click(".button[data-label*='Logowanie']")
                ->waitFor("#modal-card")
                ->with("#modal-card", fn ($modal) => $modal
                    ->type("password", $user)
                )
                ->clickAndWaitForReload(".button[data-label*='Zatwierdź']")->pause(1e3)
                ->assertSee("Mój profil")
                ->assertSee($user);
        });
    }
}
