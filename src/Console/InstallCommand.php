<?php

namespace Wpwwhimself\Shipyard\Console;

use Illuminate\Console\Command;

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
        echo "Hello world!";
        return Command::SUCCESS;
    }
}
