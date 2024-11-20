<?php

Route::prefix('cda')->middleware('web')->controller(ClientController::class)->group(function () {
  Route::get('/{key}', 'show');
});
