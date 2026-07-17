<?php

namespace Wpwwhimself\Shipyard\Console;

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


        $this->comment("- scaffolds and templates...");
        $this->tryCreateDirectory(base_path("app/Scaffolds"));
        $this->tryCopy(__DIR__.'/../../files/templates/api.php', base_path("routes/api.php"), true);
        $this->tryCopy(__DIR__.'/../../files/templates/Modal.php', base_path("app/Scaffolds/Modal.php"), true);
        $this->tryCopy(__DIR__.'/../../files/templates/NavItem.php', base_path("app/Models/NavItem.php"), true);
        $this->tryCopy(__DIR__.'/../../files/templates/Role.php', base_path("app/Scaffolds/Role.php"), true);
        $this->tryCopy(__DIR__.'/../../files/templates/Setting.php', base_path("app/Models/Setting.php"), true);
        $this->tryCopy(__DIR__.'/../../files/templates/StandardPage.php', base_path("app/Models/StandardPage.php"), true);
        $this->tryCopy(__DIR__.'/../../files/templates/User.php', base_path("app/Models/User.php"), true);
        $this->tryCopy(__DIR__.'/../../files/ShipyardTheme.php', base_path("app/ShipyardTheme.php"), true);

        $this->comment("- stubs...");
        $this->tryCopyDirectory(__DIR__.'/../../files/stubs', base_path("stubs"));

        $this->comment("- assets...");
        $this->tryLink(__DIR__.'/../../files/css', base_path("public/css/Shipyard")); // assets cannot be exposed without publishing, so they must remain as symlinks
        $this->tryCreateEmptyFile(base_path("public/css/app.css"));
        $this->tryLink(__DIR__.'/../../files/js', base_path("public/js/Shipyard")); // assets cannot be exposed without publishing, so they must remain as symlinks
        $this->tryCreateEmptyFile(base_path("public/js/earlies.js"));
        $this->tryCreateEmptyFile(base_path("public/js/app.js"));
        $this->comment("- media...");
        $this->tryLink(__DIR__.'/../../files/media', base_path("public/media/Shipyard")); // assets cannot be exposed without publishing, so they must remain as symlinks

        $this->comment("- views...");
        $this->tryCopyDirectory(__DIR__.'/../../files/views/errors', base_path("resources/views/errors"));
        $this->tryCopy(__DIR__.'/../../files/views/welcome.blade.php', base_path("resources/views/welcome.blade.php"));

        $this->comment("- configs...");
        $this->tryCopy(__DIR__.'/../../files/configs/app.php', base_path("bootstrap/app.php"));
        $this->tryCopy(__DIR__.'/../../files/configs/popper.php', base_path("config/popper.php")); // this one doesn't work by merge, so "install" still must copy it

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
            base_path("resources/.gitignore"),
            base_path("public/.gitignore"),
        ] as $path) {
            $this->tryCopy(__DIR__.'/../../files/.gitignore.directory.example', $path);
        }
        foreach ([
            base_path("docs/.gitignore"),
        ] as $path) {
            $this->tryCopy(__DIR__.'/../../files/.gitignore.nametagged.example', $path);
        }
        foreach ([
            [base_path("config/.gitignore"), "popper.php"],
            [base_path("public/css/.gitignore"), "shipyard_theme_cache*"],
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

        if ($old_version === null) {
            $this->call("shipyard:what-now");
        }

        return Command::SUCCESS;
    }

    private function tryLink($from, $to) {
        if (file_exists($to)) {
            return;
        }

        // unlink broken symlinks (happens with windows/linux merger)
        if (is_link($to) && readlink($to) && !file_exists(readlink($to))) {
            unlink($to);
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

    private function tryCreateDirectory($path) {
        if (file_exists($path)) {
            return;
        }
        (new Filesystem())->makeDirectory($path, recursive: true);
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
