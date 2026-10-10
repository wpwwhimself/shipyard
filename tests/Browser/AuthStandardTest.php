<?php

namespace Tests\Browser;

use App\Models\Setting;
use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class AuthStandardTest extends DuskTestCase
{
    #region setup
    public function setUp(): void
    {
        parent::setUp();
        Setting::find("users_login_is")->update(["value" => "name"]);
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

    public function test_user_can_login_via_name(): void
    {
        $this->browse(function (Browser $browser) {
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

    // public function test_user_can_login_via_password(): void
    // {
    //     $this->browse(function (Browser $browser) {
    //         Setting::find("users_login_is")->update(["value" => "none"]);
    //         $user = "testtest";

    //         $browser->visit("/")
    //             ->click(".button[data-label*='Logowanie']")
    //             ->waitFor("#modal-card")
    //             ->with("#modal-card", fn ($modal) => $modal
    //                 ->type("password", $user)
    //             )
    //             ->clickAndWaitForReload(".button[data-label*='Zatwierdź']")->pause(1e3)
    //             ->assertSee("Mój profil")
    //             ->assertSee($user);
    //     });
    // }
}
