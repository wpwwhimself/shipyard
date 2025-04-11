<?php

namespace Wpwwhimself\Shipyard\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

use function PHPUnit\Framework\directoryExists;

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
        $this->tryLink(__DIR__.'/../../files/middleware', base_path("app/Http/Middleware/Shipyard"));

        $this->comment("Updating routes...");
        $this->tryLink(__DIR__.'/../../files/routes', base_path("routes/Shipyard"));

        $this->comment("Updating traits...");
        $this->tryLink(__DIR__.'/../../files/traits', base_path("app/Traits/Shipyard"));

        $this->comment("Updating models...");
        $this->tryLink(__DIR__.'/../../files/models', base_path("app/Models/Shipyard"));

        $this->comment("Updating migrations...");
        $this->tryCopyDirectory(__DIR__.'/../../files/migrations', base_path("database/migrations"));
        $this->call("migrate");

        $this->comment("Updating controllers...");
        $this->tryLink(__DIR__.'/../../files/controllers', base_path("app/Http/Controllers/Shipyard"));

        $this->comment("Updating stubs...");
        $this->tryLink(__DIR__.'/../../files/stubs', base_path("stubs"));

        $this->comment("Updating styles...");
        $this->tryLink(__DIR__.'/../../files/css', base_path("resources/css/Shipyard"));
        $this->tryCopy(__DIR__.'/../../files/css/identity.scss', base_path("resources/css/identity.scss"));

        $this->comment("Updating views...");
        $this->tryCopyDirectory(__DIR__.'/../../files/views', base_path("resources/views"));
        $this->tryLink(__DIR__.'/../../files/js/Components', base_path("resources/js/Components/Shipyard"));
        $this->tryLink(__DIR__.'/../../files/js/Layouts', base_path("resources/js/Layouts/Shipyard"));
        $this->tryLink(__DIR__.'/../../files/js/Pages', base_path("resources/js/Pages/Shipyard"));
        $this->tryCopy(base_path("resources/js/Pages/Shipyard/Welcome.vue"), base_path("resources/js/Pages/Welcome.vue"));

        $this->comment("Updating .gitignore files...");
        foreach ([
            base_path("app/Http/Middleware/Shipyard/.gitignore"),
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
            $this->tryCopy(__DIR__.'/../../files/.gitignore.all.example', $path);
        }
        foreach ([
            base_path("database/migrations/.gitignore"),
        ] as $path) {
            $this->tryCopy(__DIR__.'/../../files/.gitignore.nametagged.example', $path);
        }

        $this->info("✅ Shipyard is ready!");

        $this->comment("Things to do now:");
        $this->comment("| add `require __DIR__.'/Shipyard/shipyard.php';` to `routes/web.php`");
        $this->comment("| clean your `resources/css/app.css` file - it may overwrite themes");
        $this->comment("| install SASS with `npm install -D sass-embedded`");

        return Command::SUCCESS;
    }

    private function tryLink($from, $to) {
        if (file_exists($to)) {
            return;
        }

        $to_before = Str::beforeLast($to, '/');
        if (!file_exists($to_before)) {
            (new Filesystem)->makeDirectory($to_before);
        }

        (new Filesystem)->link($from, $to);
    }

    private function tryCopyDirectory($from, $to) {
        (new Filesystem)->copyDirectory($from, $to);
    }

    private function tryMove($from, $to) {
        if (!file_exists($from)) {
            return;
        }

        (new Filesystem)->move($from, $to);
    }

    private function tryCopy($from, $to) {
        (new Filesystem)->copy($from, $to);
    }
}
