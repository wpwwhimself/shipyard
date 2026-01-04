<?php

namespace Wpwwhimself\Shipyard\Console;

use App\Http\Controllers\Shipyard\ThemeController;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class CacheThemeCommand extends Command
{
    private const PACKAGE_INFO_PATH = "storage/framework/cache/shipyard.json";

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'shipyard:cache-theme';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Caches Shipyard theme for dev needs.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if (env("APP_ENV") !== "local") {
            $this->info("💄 Theme cache is unnecessary. Skipping...");
            return Command::SUCCESS;
        }

        $this->info("💄 Caching theme...");

        $this->reset();
        $this->cache();

        return Command::SUCCESS;
    }

    private function reset() {
        $this->line("Dropping existing cache...");

        @unlink(public_path("css/shipyard_theme_cache.css"));
    }

    private function cache() {
        $this->line("Stitching file...");

        $styles = implode("\n", [
            file_get_contents(base_path("vendor/wpwwhimself/shipyard/files/scss/_base.scss")),
            file_get_contents(base_path("vendor/wpwwhimself/shipyard/files/scss/".\App\ShipyardTheme::getTheme().".scss")),
        ]);
        file_put_contents(public_path("css/shipyard_theme_cache.scss"), $styles);

        $this->line("Processing file...");

        try {
            exec(implode(" ", [
                "sass",
                public_path("css/shipyard_theme_cache.scss"),
                public_path("css/shipyard_theme_cache.css"),
            ]));
            @unlink(public_path("css/shipyard_theme_cache.scss"));
        } catch (\Throwable $th) {
            $this->error("🚨 Theme cache failed. Sasscompiler is probably unavailable.");
            throw $th;
        }

        $this->info("💄 Theme cached");
    }
}
