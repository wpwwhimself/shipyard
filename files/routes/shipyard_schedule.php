<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command("auth:clear-resets")->everyFifteenMinutes();

Schedule::command("backup:clean")->at("01:00");
Schedule::command("backup:run")->at("01:15");
