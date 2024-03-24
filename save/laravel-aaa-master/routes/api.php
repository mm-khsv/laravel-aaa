<?php

use dnj\AAA\Http\Controllers\TypesController;
use dnj\AAA\Http\Controllers\UsersController;
use dnj\AAA\Http\Middleware\UpdateOnlineTimeOfUsers;
use Illuminate\Support\Facades\Route;

Route::prefix("v1")->middleware(["api", "auth", UpdateOnlineTimeOfUsers::class])->group(function() {
	Route::apiResource("users", UsersController::class);
	Route::apiResource("types", TypesController::class);
});