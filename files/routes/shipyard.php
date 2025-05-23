<?php

use App\Http\Controllers\Shipyard\AdminController;
use App\Http\Controllers\Shipyard\AuthController;
use App\Http\Controllers\Shipyard\DocsController;
use App\Http\Controllers\Shipyard\EntityManagementController;
use App\Http\Controllers\Shipyard\FrontController;
use App\Http\Controllers\Shipyard\ProfileController;
use App\Http\Controllers\Shipyard\SpellbookController;
use App\Http\Middleware\Shipyard\EnsureUserHasRole;
use Illuminate\Support\Facades\Route;

#region auth
Route::controller(AuthController::class)->prefix("auth")->group(function () {
    Route::get("/login", "login")->name("login");
    Route::post("/login", "processLogin")->name("process-login");

    Route::get("/register", "register")->name("register");
    Route::post("/register", "processRegister")->name("process-register");

    Route::middleware("auth")->group(function () {
        Route::get("/change-password", "changePassword")->name("change-password");
        Route::post("/change-password", "processChangePassword")->name("process-change-password");

        Route::get("/logout", "logout")->name("logout");
    });

    Route::get("/forgot-password", "forgotPassword")->name("forgot-password");
    Route::post("/forgot-password", "processForgotPassword")->name("process-forgot-password");
    Route::get("/reset-password/{token}", "resetPassword")->name("password.reset");
    Route::post("/reset-password", "processResetPassword")->name("process-reset-password");
});
#endregion

#region admin
Route::middleware("auth")->group(function () {
    Route::controller(ProfileController::class)->prefix("profile")->group(function () {
        Route::get("/", "myProfile")->name("profile");
    });

    Route::controller(AdminController::class)->prefix("admin")->group(function () {
        Route::prefix("settings")->middleware(EnsureUserHasRole::class.":technical")->group(function () {
            Route::get("", "settings")->name("admin-settings");
            Route::post("", "processSettings")->name("admin-process-settings");
        });

        Route::prefix("files")->group(function () {
            Route::get("", "files")->name("files-list");
            Route::get("download", "filesDownload")->name("files-download");
            Route::middleware(EnsureUserHasRole::class.":blogger")->group(function () {
                Route::post("upload", "filesUpload")->name("files-upload");
                Route::get("delete", "filesDelete")->name("files-delete");
            });

            Route::prefix("folder")->group(function () {
                Route::get("new", "folderNew")->name("folder-new");
                Route::post("create", "folderCreate")->name("folder-create");
                Route::get("delete", "folderDelete")->name("folder-delete");
            });
        });

        Route::prefix("{model}")->group(function () {
            Route::get("", "listModel")->name("admin-list-model");
            Route::get("edit/{id?}", "editModel")->name("admin-edit-model");
            Route::post("edit", "processEditModel")->name("admin-process-edit-model");
        });
    });

    Route::controller(EntityManagementController::class)->prefix("admin/entmgr")->group(function () {
        Route::prefix("{model}")->group(function () {
            Route::get("", "listModel")->name("entmgr-list");
        });
    });
});
#endregion

#region front
Route::controller(FrontController::class)->group(function () {
    Route::get("/", "index")->name("main");
    Route::get("/pages/{slug}", "standardPage")->name("standard-page");

    Route::get("contact", "contactForm")->name("contact-form");
    Route::post("contact", "processContactForm")->name("contact-form-process");
});
#endregion

#region docs
Route::controller(DocsController::class)->prefix("docs")->group(function () {
    Route::get("{slug}", "view")->where("slug", "[a-zA-Z0-9-/]+")->name("docs-view");
    Route::get("", "index")->name("docs-index");
});
#endregion

#region spellbook
Route::controller(SpellbookController::class)->middleware(EnsureUserHasRole::class.":super")->group(function () {
    foreach (SpellbookController::SPELLS as $spell_name => $route) {
        Route::get($route, $spell_name);
    }
});
#endregion
