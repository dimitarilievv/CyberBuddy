<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SwaggerTestController;

Route::get('/test-swagger', [SwaggerTestController::class, 'index']);

