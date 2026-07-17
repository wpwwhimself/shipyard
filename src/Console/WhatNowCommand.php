<?php

namespace Wpwwhimself\Shipyard\Console;

use Illuminate\Console\Command;

class WhatNowCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'shipyard:what-now';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Shows steps to do after installing Shipyard for the first time.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info("Things to do now:");
        $this->comment("> prepare extended model classes to add new properties in `app/Models/User.php`:
<?php

namespace App\Models;

use Wpwwhimself\Shipyard\Models\User as ShipyardUser;

class User extends ShipyardUser
{
    public const FROM_SHIPYARD = true;

}"
        );
        $this->comment("> clear your `resources/css/app.css` file - it may overwrite themes");

        return Command::SUCCESS;
    }
}
