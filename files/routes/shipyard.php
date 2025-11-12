<?php

use App\Http\Controllers\Shipyard\AdminController;
use App\Http\Controllers\Shipyard\AuthController;
use App\Http\Controllers\Shipyard\DocsController;
use App\Http\Controllers\Shipyard\FrontController;
use App\Http\Controllers\Shipyard\ModalController;
use App\Http\Controllers\Shipyard\ProfileController;
use App\Http\Controllers\Shipyard\SpellbookController;
use App\Http\Controllers\Shipyard\ThemeController;
use App\Http\Middleware\Shipyard\EnsureUserHasRole;
use Illuminate\Support\Facades\Route;

#region auth
Route::controller(AuthController::class)->prefix("auth")->group(function () {
    Route::get("/login", "login")->name("login");
    Route::post("/login", "processLogin")->name("login.process");

    Route::get("/register", "register")->name("register");
    Route::post("/register", "processRegister")->name("register.process");

    Route::middleware("auth")->group(function () {
        Route::get("/change-password", "changePassword")->name("change-password");
        Route::post("/change-password", "processChangePassword")->name("change-password.process");

        Route::get("/logout", "logout")->name("logout");
    });

    Route::prefix("password")->group(function () {
        Route::get("/set", "setPassword")->name("password.set");
        Route::post("/set", "processSetPassword")->name("password.set.process");
        Route::get("/reset/{token?}", "resetPassword")->name("password.reset");
        Route::post("/reset", "processResetPassword")->name("password.reset.process");
    });
});
#endregion

#region admin
Route::middleware("auth")->group(function () {
    Route::controller(ProfileController::class)->prefix("profile")->group(function () {
        Route::get("/", "myProfile")->name("profile");
    });

    Route::controller(AdminController::class)->prefix("admin")->group(function () {
        Route::prefix("settings")->middleware(EnsureUserHasRole::class.":technical")->group(function () {
            Route::get("", "settings")->name("admin.system-settings");
            Route::post("", "processSettings")->name("admin.system-settings.process");
        });

        Route::prefix("files")->middleware(EnsureUserHasRole::class.":content-manager")->group(function () {
            Route::get("", "files")->name("files");
            Route::get("download", "filesDownload")->name("files.download");
            Route::post("upload", "filesUpload")->name("files.upload");
            Route::get("delete", "filesDelete")->name("files.delete");

            Route::get("search", "filesSearch")->name("files.search");

            Route::prefix("folder")->group(function () {
                Route::get("new", "folderNew")->name("files.folder.new");
                Route::post("create", "folderCreate")->name("files.folder.create");
                Route::get("delete", "folderDelete")->name("files.folder.delete");
            });
        });

        Route::prefix("models")->group(function () {
            Route::get("", "models")->name("admin.models");
            Route::redirect("settings", "/admin/settings"); // settings model is not editable
            Route::prefix("{model}")->group(function () {
                Route::get("", "listModel")->name("admin.model.list");
                Route::post("filter", "filterListModel")->name("admin.model.list.filter");
                Route::get("edit/{id?}", "editModel")->name("admin.model.edit");
                Route::post("edit", "processEditModel")->name("admin.model.edit.process");
            });
        });
    });
});
#endregion

#region front
Route::controller(FrontController::class)->group(function () {
    Route::get("/", "index")->name("main");
    Route::get("/pages/{slug}", "standardPage")->name("standard-page");

    Route::get("contact", "contactForm")->name("contact-form");
    Route::post("contact", "processContactForm")->name("contact-form.process");
});
#endregion

#region modals
Route::controller(ModalController::class)->prefix("api/modals")->group(function () {
    Route::get("{name?}", "data")->name("modals.data");
});
#endregion

#region docs
Route::controller(DocsController::class)->prefix("docs")->group(function () {
    Route::get("spells", "spellbook")->middleware(EnsureUserHasRole::class.":spellcaster")->name("docs.spellbook");
    Route::get("{slug}", "view")->where("slug", "[a-zA-Z0-9-/]+")->name("docs.view");
    Route::get("", "index")->name("docs.index");
});
#endregion

#region spellbook
Route::controller(SpellbookController::class)->middleware(EnsureUserHasRole::class.":spellcaster")->group(function () {
    foreach (SpellbookController::SPELLS as $name => $params) {
        Route::get($params["route"], $name);
    }
});
#endregion

#region styles
Route::controller(ThemeController::class)->group(function () {
    Route::prefix("theme")->middleware(EnsureUserHasRole::class.":spellcaster")->group(function () {
        Route::get("test", "testTheme");
    });
    
    Route::prefix("api/theme")->group(function () {
        Route::post("cache", "cache");
        Route::post("reset", "reset");
    });
});
#endregion
