<?php

use Illuminate\Support\Facades\Route;
use Vinkas\Cda\Http\Controllers\ClientController;

Route::prefix('cda')->middleware('web')->controller(ClientController::class)->group(function () {
  Route::get('/{key}', 'auth');
});
