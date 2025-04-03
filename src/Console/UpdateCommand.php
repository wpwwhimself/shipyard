<?php

namespace Wpwwhimself\Shipyard\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class UpdateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'shipyard:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates Shipyard files';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info("⚓ Shipyard will now be updated...");

        (new Filesystem)->copyDirectory(__DIR__.'/../../files/update/routes', base_path("routes/Shipyard"));
        (new Filesystem)->copyDirectory(__DIR__.'/../../files/update/controllers', base_path("app/Http/Controllers/Shipyard"));

        $this->info("✅ Shipyard is ready!");

        return Command::SUCCESS;
    }
}
