<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseTruncation;
use Illuminate\Support\Facades\Artisan;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ModelTest extends DuskTestCase
{
    use DatabaseTruncation;

    protected $exceptTables = ["users"];

    public function test_archmage_can_create_new_model(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs(1)
                ->visitRoute("admin.model.list", ["model" => "standard-pages"])
                ->pause(1e3)
                ->assertSee("Podstrony");
            $browser->waitForReload(function (Browser $bbrowser) {
                $bbrowser->click("aside .button.primary[data-tippy='Dodaj']");
            })->pause(0.5e3)
                ->assertSee("Nowy wpis")
                ->assertSee("Administracja | Podstrony");
            $browser->type("name", "Chrup chrup");
            $browser->waitForReload(function (Browser $bbrowser) {
                $bbrowser->click(".button[data-label*='Zapisz zmiany']");
            })->pause(0.5e3)
                ->assertSee("Zapisano")
                ->assertSee("Chrup chrup");
        });
    }
}
