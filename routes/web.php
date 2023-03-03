<?php

use App\Http\Controllers\EventsController;
use Illuminate\Support\Facades\Route;

Route::post('/events', [EventsController::class,'store']);


