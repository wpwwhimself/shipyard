<?php

namespace Wpwwhimself\Shipyard\Console;

use App\Http\Controllers\Shipyard\ThemeController;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class InstallCommand extends Command
{
    public const PACKAGE_INFO_PATH = "storage/framework/cache/shipyard.json";

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'shipyard:install {--force}';

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
        $old_package_info = json_decode(
            @file_get_contents(base_path(self::PACKAGE_INFO_PATH)),
            true
        ) ?? [];
        $old_version = $old_package_info["version"] ?? null;
        try {
            $new_package_info = json_decode(shell_exec(env("COMPOSER_PATH", "composer")." show wpwwhimself/shipyard --format=json"), true);
            $new_version = current($new_package_info["versions"]);
        } catch (\Throwable $th) {
            $this->error("🚨 Cannot establish incoming Shipyard version. Check env: COMPOSER_PATH");
            $new_version = null;
        }

        if ($old_version == $new_version && $this->option("force") === false) {
            $this->info("⚓ Shipyard is already installed and up to date. \n Use --force to force an update.");
            return Command::SUCCESS;
        }

        $this->info("⚓ Shipyard will now be installed. Hang tight...");

        #region version cache
        file_put_contents(
            base_path(self::PACKAGE_INFO_PATH),
            json_encode([
                "version" => $new_version,
            ])
        );
        #endregion

        #region copying
        $this->info("📨 Copying...");

        $this->comment("- middleware...");
        $this->tryLink(__DIR__.'/../../files/middleware', base_path("app/Http/Middleware/Shipyard"));

        $this->comment("- routes...");
        $this->tryLink(__DIR__.'/../../files/routes', base_path("routes/Shipyard"));

        $this->comment("- traits...");
        $this->tryLink(__DIR__.'/../../files/traits', base_path("app/Traits/Shipyard"));

        $this->comment("- mails...");
        $this->tryLink(__DIR__.'/../../files/mails', base_path("app/Mail/Shipyard"));

        $this->comment("- models...");
        $this->tryLink(__DIR__.'/../../files/models', base_path("app/Models/Shipyard"));

        $this->comment("- migrations...");
        $this->tryCopyDirectory(__DIR__.'/../../files/migrations', base_path("database/migrations"));

        $this->comment("- controllers...");
        $this->tryLink(__DIR__.'/../../files/controllers', base_path("app/Http/Controllers/Shipyard"));

        $this->comment("- stubs...");
        $this->tryCopyDirectory(__DIR__.'/../../files/stubs', base_path("stubs"));

        $this->comment("- styles...");
        $this->tryLink(__DIR__.'/../../files/css', base_path("public/css/Shipyard"));
        $this->tryCreateEmptyFile(base_path("public/css/app.css"));

        $this->comment("- theme...");
        $this->tryLink(__DIR__.'/../../files/theme', base_path("app/Theme/Shipyard"));
        $this->tryCopy(__DIR__.'/../../files/ShipyardTheme.php', base_path("app/ShipyardTheme.php"), true);

        $this->comment("- scripts...");
        $this->tryLink(__DIR__.'/../../files/js', base_path("public/js/Shipyard"));
        $this->tryCreateEmptyFile(base_path("public/js/earlies.js"));
        $this->tryCreateEmptyFile(base_path("public/js/app.js"));

        $this->comment("- views...");
        $this->tryLink(__DIR__.'/../../files/views/layouts', base_path("resources/views/layouts/shipyard"));
        $this->tryLink(__DIR__.'/../../files/views/components', base_path("resources/views/components/shipyard"));
        $this->tryLink(__DIR__.'/../../files/views/mail', base_path("resources/views/mail/shipyard"));
        $this->tryLink(__DIR__.'/../../files/views/pages', base_path("resources/views/pages/shipyard"));
        $this->tryCopyDirectory(__DIR__.'/../../files/views/errors', base_path("resources/views/errors"));
        $this->tryCopy(__DIR__.'/../../files/views/welcome_to_shipyard.blade.php', base_path("resources/views/welcome_to_shipyard.blade.php"));

        $this->comment("- media...");
        $this->tryLink(__DIR__.'/../../files/media', base_path("public/media/Shipyard"));

        $this->comment("- configs...");
        $this->tryCopy(__DIR__.'/../../files/configs/popper.php', base_path("config/popper.php"));
        $this->tryCopy(__DIR__.'/../../files/configs/blade-icons.php', base_path("config/blade-icons.php"));
        $this->tryCopy(__DIR__.'/../../files/configs/backup.php', base_path("config/backup.php"));

        $this->comment("- docs...");
        $this->tryLink(__DIR__.'/../../files/docs', base_path("docs/Shipyard"));

        $this->comment("- .gitignore files...");
        foreach ([
            base_path("stubs/.gitignore"),
            base_path("resources/views/errors/.gitignore"),
        ] as $path) {
            $this->tryCopy(__DIR__.'/../../files/.gitignore.all.example', $path);
        }
        foreach ([
            base_path("app/.gitignore"),
            base_path("routes/.gitignore"),
            base_path("resources/.gitignore"),
            base_path("public/.gitignore"),
        ] as $path) {
            $this->tryCopy(__DIR__.'/../../files/.gitignore.directory.example', $path);
        }
        foreach ([
            base_path("database/migrations/.gitignore"),
            base_path("resources/views/.gitignore"),
            base_path("docs/.gitignore"),
        ] as $path) {
            $this->tryCopy(__DIR__.'/../../files/.gitignore.nametagged.example', $path);
        }
        foreach ([
            [base_path("config/.gitignore"), "popper.php\nblade-icons.php\nbackup.php"],
            [base_path("public/css/.gitignore"), "shipyard_theme_cache.css"],
        ] as [$path, $file_name]) {
            $contents = "$file_name\n.gitignore";
            file_put_contents($path, $contents);
        }
        #endregion

        #region installing
        $this->info("📭 Installing...");
        $this->call("migrate", ["--force" => true]);
        #endregion

        if (env("APP_ENV") === "local") {
            $this->call("shipyard:cache-theme");
        }

        $this->info("✅ Shipyard is ready!");

        return Command::SUCCESS;
    }

    private function tryLink($from, $to) {
        if (file_exists($to)) {
            return;
        }

        $to_before = Str::beforeLast($to, '/');
        if (!file_exists($to_before)) {
            (new Filesystem)->makeDirectory($to_before, recursive: true);
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

    private function tryCopy($from, $to, $only_once = false) {
        if (file_exists($to) && $only_once) {
            return;
        }
        (new Filesystem)->copy($from, $to);
    }

    private function tryCreateEmptyFile($path) {
        if (file_exists($path)) {
            return;
        }
        (new Filesystem)->put($path, "");
    }
}
