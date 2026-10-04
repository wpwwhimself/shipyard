<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Tests\XPathHelpers;

class HomepageTest extends DuskTestCase
{
    use XPathHelpers;

    public function test_homepage_is_loading(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->assertSee('Shipyard')
                ->assertTitleContains("Shipyard");
        });
    }
}
