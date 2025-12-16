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
        $this->comment("> in your `routes/web.php` add the following: \n\t if (file_exists(__DIR__.'/Shipyard/shipyard.php')) require __DIR__.'/Shipyard/shipyard.php';");
        $this->comment("> in your `routes/console.php` add the following: \n\t if (file_exists(__DIR__.'/Shipyard/shipyard_schedule.php')) require __DIR__.'/Shipyard/shipyard_schedule.php';");
        $this->comment("> prepare extended class of User and LocalSetting model to add new properties:
        <?php

        namespace App\Models;

        use App\Models\Shipyard\User as ShipyardUser;

        class User extends ShipyardUser
        {
            public const FROM_SHIPYARD = true;

        }

    -----------

        <?php

        namespace App\Models;

        use App\Models\Shipyard\Setting as ShipyardSetting;

        class Setting extends ShipyardSetting
        {
            public const FROM_SHIPYARD = true;

            public static function fields(): array
            {
                /**
                 * * hierarchical structure of the page *
                 * grouped by sections (title, subtitle, icon, identifier)
                 * each section contains fields (name, label, hint, icon)
                 * sections can be nested with 'subsection_title', '_subtitle', '_icon' and 'fields' or 'columns' with the same structure
                 */
                return [

                ];
            }
        }"
        );
        $this->comment("> clear your `resources/css/app.css` file - it may overwrite themes");

        return Command::SUCCESS;
    }
}
