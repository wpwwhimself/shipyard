<?php

use App\Http\Controllers\Shipyard\AuthController;
use App\Http\Controllers\Shipyard\FrontController;
use App\Http\Controllers\Shipyard\ModalController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(["api", "auth:sanctum"])->prefix("api")->group(function () {

#region auth
Route::controller(AuthController::class)->prefix("auth")->group(function () {
    Route::get("me", fn (Request $rq) => response()->json($rq->user()));

    Route::prefix("tokens")->group(function () {
        Route::withoutMiddleware("auth:sanctum")
            ->post("create", "apiToken");

        Route::get("", "userTokens");
    });

});
#endregion

#region modals
Route::controller(ModalController::class)->withoutMiddleware("auth:sanctum")->prefix("modals")->group(function () {
    Route::get("{name?}", "data")->name("modals.data");
});
#endregion

#region front
Route::controller(FrontController::class)->group(function () {
    Route::prefix("models/{model}")->group(function () {
        Route::patch("{id}", "apiUpdateModel");
        Route::delete("{id}", "apiDeleteModel");
        Route::get("{id}", "apiFindModel");
        Route::post("", "apiCreateModel");
        Route::get("", "apiListModel");
    });
});
#endregion

});
