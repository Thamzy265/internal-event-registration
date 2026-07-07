<?php

use App\Http\Controllers\Api\EventController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('events', EventController::class);
Route::post('events/{event}/register', [EventController::class, 'register']);
Route::post('events/{event}/cancel', [EventController::class, 'cancel']);
