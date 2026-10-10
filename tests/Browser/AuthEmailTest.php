<?php

namespace Tests\Browser;

use App\Models\Setting;
use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class AuthEmailTest extends DuskTestCase
{
    #region setup
    public function setUp(): void
    {
        parent::setUp();
        Setting::find("users_login_is")->update(["value" => "email"]);
    }

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

    public function test_user_can_login_via_email(): void
    {
        $this->browse(function (Browser $browser) {
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
}
