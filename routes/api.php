<?php

declare(strict_types=1);

use App\Http\Controllers\OrganisationController;
use App\Http\Controllers\ResourceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Gate::define('viewApiDocs', fn () => true);

Route::middleware('auth:sanctum')
    ->prefix('/v1')
    ->group(function () {
        Route::get('/organisations', OrganisationController::class);
        Route::get('/resources', ResourceController::class);
    });

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
