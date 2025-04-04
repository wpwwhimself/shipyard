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

        $this->comment("Updating helpers...");
        (new Filesystem)->copyDirectory(__DIR__.'/../../files/helpers', base_path("app/Helpers/Shipyard"));

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

        $this->comment("Updating styles...");
        (new Filesystem)->copyDirectory(__DIR__.'/../../files/css', base_path("resources/css/Shipyard"));
        if (!file_exists(base_path("resources/css/identity.scss"))) {
            (new Filesystem)->copy(__DIR__.'/../../files/css/identity.scss', base_path("resources/css/identity.scss"));
        }

        $this->comment("Updating views...");
        (new Filesystem)->copyDirectory(__DIR__.'/../../files/views', base_path("resources/views"));
        (new Filesystem)->copyDirectory(__DIR__.'/../../files/js/Components', base_path("resources/js/Components/Shipyard"));
        (new Filesystem)->copyDirectory(__DIR__.'/../../files/js/Layouts', base_path("resources/js/Layouts/Shipyard"));
        (new Filesystem)->copyDirectory(__DIR__.'/../../files/js/Pages', base_path("resources/js/Pages/Shipyard"));
        (new Filesystem)->move(base_path("resources/js/Pages/Shipyard/Welcome.vue"), base_path("resources/js/Partials/Welcome.vue"));

        $this->comment("Updating .gitignore files...");
        foreach ([
            base_path("app/Http/Middleware/Shipyard/.gitignore"),
            base_path("app/Helpers/Shipyard/.gitignore"),
            base_path("routes/Shipyard/.gitignore"),
            base_path("app/Traits/Shipyard/.gitignore"),
            base_path("app/Models/Shipyard/.gitignore"),
            base_path("app/Http/Controllers/Shipyard/.gitignore"),
            base_path("stubs/.gitignore"),
            base_path("resources/css/Shipyard/.gitignore"),
            base_path("resources/views/.gitignore"),
            base_path("resources/js/Components/Shipyard/.gitignore"),
            base_path("resources/js/Layouts/Shipyard/.gitignore"),
            base_path("resources/js/Pages/Shipyard/.gitignore"),
        ] as $path) {
            (new Filesystem)->copy(__DIR__.'/../../files/.gitignore.all.example', $path);
        }
        foreach ([
            base_path("database/migrations/.gitignore"),
        ] as $path) {
            (new Filesystem)->copy(__DIR__.'/../../files/.gitignore.nametagged.example', $path);
        }

        $this->info("✅ Shipyard is ready!");

        $this->comment("Things to do now:");
        $this->comment("> make sure your `routes/web.php` file contains `require __DIR__.'/Shipyard/shipyard.php';`");
        $this->comment("> make sure your `resources/css/app.css` file is clean - it may overwrite themes");

        return Command::SUCCESS;
    }
}
