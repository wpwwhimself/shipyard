<?php

namespace Wpwwhimself\Shipyard\Console;

use App\Models\Shipyard\User;
use App\Models\Shipyard\NavItem;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class PrybarCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'shipyard:prybar {mode : copy_roles}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Installation fixer for moving roles from role_user to users.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info("🔨 Copying roles");

        switch ($this->argument("mode")) {
            case "copy_roles":
                User::all()->each(function (User $user) {
                    $user->update([
                        "roles" => DB::table("role_user")->where("user_id", $user->id)->get()->pluck("role_name")->implode(","),
                    ]);
                });

                NavItem::all()->each(function (NavItem $item) {
                    $item->update([
                        "roles" => DB::table("nav_item_role")->where("nav_item_id", $item->id)->get()->pluck("role_name")->implode(","),
                    ]);
                });
                break;

            default:
                $this->error("Unknown mode");
                return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
