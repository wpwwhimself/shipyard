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
    protected $description = 'Installs/updates Shipyard files';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info("⚓ Shipyard will now be installed. Hang tight...");

        $this->comment("Updating middleware...");
        (new Filesystem)->copyDirectory(__DIR__.'/../../files/middleware', base_path("app/Http/Middleware/Shipyard"));

        $this->comment("Updating routes...");
        (new Filesystem)->copyDirectory(__DIR__.'/../../files/routes', base_path("routes/Shipyard"));

        $this->comment("Updating traits...");
        (new Filesystem)->copyDirectory(__DIR__.'/../../files/traits', base_path("app/Traits/Shipyard"));

        $this->comment("Updating models...");
        (new Filesystem)->copyDirectory(__DIR__.'/../../files/models', base_path("app/Models/Shipyard"));

        $this->comment("Updating migrations...");
        (new Filesystem)->copyDirectory(__DIR__.'/../../files/migrations', base_path("database/migrations"));
        $this->call("migrate");

        $this->comment("Updating controllers...");
        (new Filesystem)->copyDirectory(__DIR__.'/../../files/controllers', base_path("app/Http/Controllers/Shipyard"));

        $this->comment("Updating stubs...");
        (new Filesystem)->copyDirectory(__DIR__.'/../../files/stubs', base_path("stubs"));

        $this->comment("Updating .gitignore files...");
        foreach ([
            base_path("routes/Shipyard/.gitignore"),
            base_path("app/Traits/Shipyard/.gitignore"),
            base_path("app/Models/Shipyard/.gitignore"),
            base_path("app/Http/Controllers/Shipyard/.gitignore"),
        ] as $path) {
            (new Filesystem)->copy(__DIR__.'/../../files/.gitignore.all.example', $path);
        }
        foreach ([
            base_path("database/migrations/.gitignore"),
            base_path("stubs/.gitignore"),
        ] as $path) {
            (new Filesystem)->copy(__DIR__.'/../../files/.gitignore.nametagged.example', $path);
        }

        $this->info("✅ Shipyard is ready!");

        $this->comment("Things to do now:");
        $this->comment("> make sure your `routes/web.php` file contains `require __DIR__.'/Shipyard/shipyard.php';`");

        return Command::SUCCESS;
    }
}
