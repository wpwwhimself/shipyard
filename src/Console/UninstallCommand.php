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
    protected $signature = 'shipyard:uninstall {--soft}';

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

        $this->comment("- traits...");
        $this->tryDelete(base_path("app/Traits/Shipyard"));

        $this->comment("- mails...");
        $this->tryDelete(base_path("app/Mail/Shipyard"));

        $this->comment("- models...");
        $this->tryDelete(base_path("app/Models/Shipyard"));

        $this->comment("- controllers...");
        $this->tryDelete(base_path("app/Http/Controllers/Shipyard"));

        $this->comment("- stubs...");
        $this->tryDelete(base_path("stubs"));

        $this->comment("- styles...");
        $this->tryDelete(base_path("public/css/Shipyard"));

        $this->comment("- theme...");
        $this->tryDelete(base_path("app/Theme/Shipyard"));

        $this->comment("- scripts...");
        $this->tryDelete(base_path("public/js/Shipyard"));

        $this->comment("- views...");
        $this->tryDelete(base_path("resources/views/errors"));
        $this->tryDelete(base_path("resources/views/welcome_to_shipyard.blade.php"));

        $this->comment("- media...");
        $this->tryDelete(base_path("public/media/Shipyard"));

        $this->comment("- docs...");
        $this->tryDelete(base_path("docs/Shipyard"));

        if (!$this->option("soft")) {
            $this->info("🔥 Removing configs...");
            $this->tryDelete(base_path("app/ShipyardTheme.php"));
            $this->tryDelete(base_path("config/popper.php"));
            $this->tryDelete(base_path("config/blade-icons.php"));
        }
        #endregion

        $this->info("✅ Shipyard is gone now!");

        return Command::SUCCESS;
    }

    private function tryDelete($path) {
        // broken links
        if (!realpath($path)) {
            @unlink($path);
            return;
        }

        if (is_dir($path)) {
            (new Filesystem())->deleteDirectory($path);
            return;
        }

        (new Filesystem())->delete($path);
    }
}
