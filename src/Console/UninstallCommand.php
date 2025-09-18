<?php

namespace Wpwwhimself\Shipyard\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class UninstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'shipyard:uninstall';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Removes Shipyard files from project';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info("⚓ Shipyard will now be removed. Hang tight...");

        #region removing
        $this->info("🔥 Removing...");

        $this->comment("- middleware...");
        $this->tryDelete(base_path("app/Http/Middleware/Shipyard"));

        $this->comment("- routes...");
        $this->tryDelete(base_path("routes/Shipyard"));

        $this->comment("- traits...");
        $this->tryDelete(base_path("app/Traits/Shipyard"));

        $this->comment("- mails...");
        $this->tryDelete(base_path("app/Mail/Shipyard"));

        $this->comment("- models...");
        $this->tryDelete(base_path("app/Models/Shipyard"));

        $this->comment("- migrations...");
        $migrations = collect(glob(base_path("database/migrations/*shipyard*")))
            ->sortDesc();
        foreach ($migrations as $migration) {
            //run down migration
            // $migration_local_path = Str::replace(base_path(), '', $migration);
            // $this->call('migrate:rollback', ['--path' => $migration_local_path]);
            $this->tryDelete($migration);
        }

        $this->comment("- controllers...");
        $this->tryDelete(base_path("app/Http/Controllers/Shipyard"));

        $this->comment("- stubs...");
        $this->tryDelete(base_path("stubs"));

        $this->comment("- styles...");
        $this->tryDelete(base_path("public/css/Shipyard"));
        $this->tryDelete(base_path("public/css/identity.css"));

        $this->comment("- scripts...");
        $this->tryDelete(base_path("public/js/Shipyard"));

        $this->comment("- views...");
        $this->tryDelete(base_path("resources/views/layouts/shipyard"));
        $this->tryDelete(base_path("resources/views/components/shipyard"));
        $this->tryDelete(base_path("resources/views/mail/shipyard"));
        $this->tryDelete(base_path("resources/views/pages/shipyard"));
        $this->tryDelete(base_path("resources/views/errors"));
        $this->tryDelete(base_path("resources/views/welcome_to_shipyard.blade.php"));

        $this->comment("- media...");
        $this->tryDelete(base_path("public/media/Shipyard"));

        $this->comment("- configs...");
        $this->tryDelete(base_path("config/popper.php"));
        $this->tryDelete(base_path("config/blade-icons.php"));

        $this->comment("- docs...");
        $this->tryDelete(base_path("docs/Shipyard"));
        #endregion

        $this->info("✅ Shipyard is gone now!");

        $this->comment("Things to do now:");
        $this->comment("> in your `routes/web.php` remove the following: \n\t if (file_exists(__DIR__.'/Shipyard/shipyard.php')) require __DIR__.'/Shipyard/shipyard.php';");
        $this->comment("> in your `routes/console.php` remove the following: \n\t if (file_exists(__DIR__.'/Shipyard/shipyard_schedule.php')) require __DIR__.'/Shipyard/shipyard_schedule.php';");

        return Command::SUCCESS;
    }

    private function tryDelete($path) {
        // broken links
        if (!realpath($path)) {
            unlink($path);
            return;
        }

        if (is_dir($path)) {
            (new Filesystem())->deleteDirectory($path);
            return;
        }

        (new Filesystem())->delete($path);
    }
}
