<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::post('/booking', [\App\Http\Controllers\Api\BookingController::class, 'create'])->name('booking.agent.create');


Route::post('/login/transport', [\App\Http\Controllers\Api\Transport\LoginController::class, 'login']);

Route::post('/login/transport/logout', [\App\Http\Controllers\Api\Transport\LoginController::class, 'logout']);


// login travel agent

Route::post('/login/travelagent', [\App\Http\Controllers\Api\TravelAgent\AuthController::class, 'login']);
Route::post('/register/agent', [\App\Http\Controllers\Api\TravelAgent\AuthController::class, 'agentstore']);
Route::post('/travelagent/forgetpassword', [\App\Http\Controllers\Api\TravelAgent\AuthController::class, 'forgotpassword']);
 
Route::group(['middleware' => 'auth.agent_transport'], function () {

    Route::get('/user', [\App\Http\Controllers\Api\Transport\LoginController::class, 'user']);
    
    Route::post('/bankaccount/{id}', [\App\Http\Controllers\Api\Transport\LoginController::class, 'bankaccount']);

    Route::get('/bankaccountlist/{id}', [\App\Http\Controllers\Api\Transport\LoginController::class, 'bankaccountlist']);

    Route::get('/userlogin/{id}', [\App\Http\Controllers\Api\Transport\LoginController::class, 'userlogin']);

    Route::post('/profileupdate/{id}', [\App\Http\Controllers\Api\Transport\LoginController::class, 'profileupdate']);
    
    Route::get('/country', [\App\Http\Controllers\Api\Country\CoutryController::class, 'index']);

    Route::get('/reportordertransport', [\App\Http\Controllers\Api\Transport\ReportController::class, 'index']);
    
    Route::get('/reportordertransport/{id}', [\App\Http\Controllers\Api\Transport\ReportController::class, 'detail']);
    
    Route::get('/widraw/{id}', [\App\Http\Controllers\Api\Transport\ReportController::class, 'widraw']);
    
    Route::get('/destination', [\App\Http\Controllers\Api\Transport\PackageController::class, 'destination']);

    Route::get('/packagecar', [\App\Http\Controllers\Api\Transport\PackageController::class, 'index']);

    Route::post('/packagecar/store', [\App\Http\Controllers\Api\Transport\PackageController::class, 'store']);
    
    Route::post('/packagecar/pickup', [\App\Http\Controllers\Api\Transport\PackageController::class, 'pickup']);

    Route::post('/packagecar/checkin/{id}', [\App\Http\Controllers\Api\Transport\PackageController::class, 'checkin']);
    
    Route::get('/packagecar/transportpickup/{id}', [\App\Http\Controllers\Api\Transport\PackageController::class, 'transportpickup']);

    Route::get('/packagecar/show/{id}', [\App\Http\Controllers\Api\Transport\PackageController::class, 'show']);

    Route::post('/packagecar/update/{id}', [\App\Http\Controllers\Api\Transport\PackageController::class, 'update']);

    Route::get('/packagecar/destroy/{id}', [\App\Http\Controllers\Api\Transport\PackageController::class, 'destroy']);
});


Route::middleware(['auth:api'])->group(function () {
    Route::get('/user/agent', [\App\Http\Controllers\Api\TravelAgent\AuthController::class, 'user']);
    Route::get('/agent/hotel', [\App\Http\Controllers\Api\TravelAgent\HomepageController::class, 'hotel']);
    Route::get('/agent/hoteldetail/{id}', [\App\Http\Controllers\Api\TravelAgent\HomepageController::class, 'hoteldetail']);

});