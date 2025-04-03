<?php

namespace Wpwwhimself\Shipyard\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'shipyard:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Installs Shipyard files';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info("⚓ Shipyard will now be installed...");

        $this->comment("Preparing routes...");
        (new Filesystem)->copyDirectory(__DIR__.'/../../files/install/routes', base_path("routes"));

        $this->comment("Adding gitignores...");
        foreach ([
            base_path("app/Http/Controllers/.gitignore"),
            base_path("routes/.gitignore"),
            base_path("database/migrations/.gitignore"),
        ] as $path) {
            (new Filesystem)->copy(__DIR__.'/../../files/install/.gitignore.example', $path);
        }

        $this->call("shipyard:update");

        return Command::SUCCESS;
    }
}
