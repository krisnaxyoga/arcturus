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


Route::get('/country/travelagent', [\App\Http\Controllers\Api\Country\CoutryController::class, 'index']);
Route::post('/login/transport', [\App\Http\Controllers\Api\Transport\LoginController::class, 'login']);

Route::post('/login/transport/logout', [\App\Http\Controllers\Api\Transport\LoginController::class, 'logout']);


// login travel agent

Route::post('/login/travelagent', [\App\Http\Controllers\Api\TravelAgent\AuthController::class, 'login']);
Route::post('/logout/travelagent', [\App\Http\Controllers\Api\TravelAgent\AuthController::class, 'logout']);
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
   

    // TRAVEL AGENT API
    Route::get('/user/agent', [\App\Http\Controllers\Api\TravelAgent\AuthController::class, 'user']);
    Route::get('/agent/index', [\App\Http\Controllers\Api\TravelAgent\HomepageController::class, 'index']);
    Route::get('/agent/hotel', [\App\Http\Controllers\Api\TravelAgent\HomepageController::class, 'hotel']);
    Route::get('/agent/hoteldetail/{id}', [\App\Http\Controllers\Api\TravelAgent\HomepageController::class, 'hoteldetail']);

    // Dahboard
    Route::get('/dashboard/agent', [\App\Http\Controllers\Api\TravelAgent\DashboardController::class, 'index']);

    // Booking API
    Route::get('/agent/databooking', [\App\Http\Controllers\Api\TravelAgent\BookingController::class, 'index']);
    Route::get('/agent/databookingdetail/{id}', [\App\Http\Controllers\Api\TravelAgent\BookingController::class, 'detailbookinginvoice']);
    Route::post('/agent/createbooking', [\App\Http\Controllers\Api\TravelAgent\BookingController::class, 'createbooking']);
    Route::get('/agent/detailbooking/{id}', [\App\Http\Controllers\Api\TravelAgent\BookingController::class, 'detailbooking']);
    Route::post('/agent/bookingstore/{id}', [\App\Http\Controllers\Api\TravelAgent\BookingController::class, 'bookingstore']);
    Route::get('/agent/paymentbookingpage/{id}', [\App\Http\Controllers\Api\TravelAgent\BookingController::class, 'paymentbookingpage']);

    // wallet
    Route::get('/agent/wallet', [\App\Http\Controllers\Api\TravelAgent\WalletController::class, 'index']);
    Route::get('/agent/wallet/pay/{id}', [\App\Http\Controllers\Api\TravelAgent\WalletController::class, 'pay']);
    Route::post('/agent/wallet/topup', [\App\Http\Controllers\Api\TravelAgent\WalletController::class, 'store']);

    // myprofile
    Route::get('/agent/myprofile', [\App\Http\Controllers\Api\TravelAgent\MyProfileController::class, 'index']);
    Route::post('/agent/myprofile/update', [\App\Http\Controllers\Api\TravelAgent\MyProfileController::class, 'update']);
    Route::get('/agent/myprofile/contactcreate', [\App\Http\Controllers\Api\TravelAgent\MyProfileController::class, 'contactcreate']);
    Route::post('/agent/myprofile/contactstore', [\App\Http\Controllers\Api\TravelAgent\MyProfileController::class, 'contactstore']);
    Route::get('/agent/myprofile/contactedit/{id}', [\App\Http\Controllers\Api\TravelAgent\MyProfileController::class, 'contactedit']);
    Route::get('/agent/myprofile/passwordchange', [\App\Http\Controllers\Api\TravelAgent\MyProfileController::class, 'passwordchange']);
    Route::post('/agent/myprofile/updatepassword', [\App\Http\Controllers\Api\TravelAgent\MyProfileController::class, 'updatepassword']);
    Route::post('/agent/myprofile/contactupdate/{id}', [\App\Http\Controllers\Api\TravelAgent\MyProfileController::class, 'contactupdate']);
    Route::get('/agent/myprofile/contactdelete/{id}', [\App\Http\Controllers\Api\TravelAgent\MyProfileController::class, 'contactdestroy']);

    // TRAVEL AGENT API
});