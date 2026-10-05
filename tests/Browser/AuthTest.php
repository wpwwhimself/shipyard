<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Tests\XPathHelpers;

class AuthTest extends DuskTestCase
{
    use XPathHelpers;

    public function test_login_is_possible(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->assertSee('Logowanie')
                ->clickAtXPath(self::x("class", "button", "Logowanie"))
                ->waitFor("#modal-card")
                ->with("#modal-card", fn ($modal) => $modal
                    ->assertSee("Logowanie")
                    ->assertSee("Zatwierdź")
                );
        });
    }
}
