<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RedircetController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/homepage/hotel', [\App\Http\Controllers\LandingPage\HomeController::class, 'hotel'])->name('hotel.homepage');
Route::get('/homepage/about', [\App\Http\Controllers\LandingPage\HomeController::class, 'about'])->name('about.homepage');
Route::get('/homepage/contact', [\App\Http\Controllers\LandingPage\HomeController::class, 'contact'])->name('contact.homepage');
Route::get('/homepage/hotel/{id}', [\App\Http\Controllers\LandingPage\HomeController::class, 'hoteldetail'])->name('hoteldetail.homepage');
Route::get('/agenthomepage', [\App\Http\Controllers\LandingPage\HomeController::class, 'index'])->name('agent.homepage.home');
Route::get('/', [\App\Http\Controllers\LandingPage\HomeController::class, 'index'])->name('auth.homepage.home');
Route::get('/auth/google/callback',[\App\Http\Controllers\Auth\GoogleController::class, 'handleCallback']);
// Route::get('/account/nonactive', [\App\Http\Controllers\LandingPage\HomeController::class, 'accountnonactive'])->name('accountnonactive.homepage');

//  jika user belum login
Route::group(['middleware' => 'guest'], function() {
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::get('/login/agent', [AuthController::class, 'login'])->name('login.agent');
    Route::get('/login/hotel', [AuthController::class, 'login'])->name('login.hotel');
    Route::get('/regiteragent', [AuthController::class, 'registeragent'])->name('agentregist');
    Route::get('/regitervendor', [AuthController::class, 'registvendor'])->name('vendorregist');
    Route::get('/forgetpasssword/user', [AuthController::class, 'forgetpassword'])->name('forgetpassword.user');
    Route::post('/regiteragent/store', [AuthController::class, 'agentstore'])->name('agentregist.store');
    Route::post('/regitervendor/store', [AuthController::class, 'vendorstore'])->name('vendorregist.store');
    Route::post('/login', [AuthController::class, 'dologin']);
    Route::post('/forgotpassword', [AuthController::class, 'sendEmail'])->name('forgotpassword');
});

Route::group(['prefix' => 'laravel-filemanager', 'middleware' => ['web', 'auth']], function () {
    \UniSharp\LaravelFilemanager\Lfm::routes();
});

// untuk superadmin dan agent dan vendor
Route::group(['middleware' => ['auth', 'checkrole:1,2,3']], function() {
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/redirect', [RedircetController::class, 'cek']);
});


// untuk superadmin
Route::group(['middleware' => ['auth', 'checkrole:1']], function() {
    Route::get('/admin', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard.index');
    //agent
    Route::get('/admin/agent', [\App\Http\Controllers\Admin\Agent\AgentController::class, 'index'])->name('dashboard.agent');
    Route::get('/admin/agent/create', [\App\Http\Controllers\Admin\Agent\AgentController::class, 'create'])->name('dashboard.agent.create');
    Route::get('/admin/agent/edit/{id}', [\App\Http\Controllers\Admin\Agent\AgentController::class, 'edit'])->name('dashboard.agent.edit');
    Route::post('/admin/agent/store', [\App\Http\Controllers\Admin\Agent\AgentController::class, 'store'])->name('dashboard.agent.store');
    Route::put('/admin/agent/update/{id}', [\App\Http\Controllers\Admin\Agent\AgentController::class, 'update'])->name('dashboard.agent.update');
    Route::delete('/admin/agent/delete/{id}', [\App\Http\Controllers\Admin\Agent\AgentController::class, 'destroy'])->name('dashboard.agent.delete');

    Route::get('/admin/agent/active/{id}', [\App\Http\Controllers\Admin\Agent\AgentController::class, 'active'])->name('dashboard.agent.active');
    Route::get('/admin/agent/unactive/{id}', [\App\Http\Controllers\Admin\Agent\AgentController::class, 'unactive'])->name('dashboard.agent.unactive');
    Route::post('/admin/agent/markup/{id}', [\App\Http\Controllers\Admin\Agent\AgentController::class, 'markup'])->name('dashboard.agent.markup');

    // Route::resource('/admin/agent', \App\Http\Controllers\Admin\Agent\AgentController::class);

    //agent excel
    Route::get('/admin/agent/template', [\App\Http\Controllers\Admin\Agent\ExcelController::class, 'downloadTemplate'])->name('dashboard.agent.template');
    Route::post('/admin/agent/importexcel', [\App\Http\Controllers\Admin\Agent\ExcelController::class, 'import'])->name('dashboard.agent.import');

    //user
    Route::get('/admin/user', [\App\Http\Controllers\Admin\User\UserController::class, 'index'])->name('dashboard.user');
    Route::get('/admin/user/create', [\App\Http\Controllers\Admin\User\UserController::class, 'create'])->name('dashboard.user.create');
    Route::get('/admin/user/edit/{id}', [\App\Http\Controllers\Admin\User\UserController::class, 'edit'])->name('dashboard.user.edit');
    Route::post('/admin/user/store', [\App\Http\Controllers\Admin\User\UserController::class, 'store'])->name('dashboard.user.store');
    Route::put('/admin/user/update/{id}', [\App\Http\Controllers\Admin\User\UserController::class, 'update'])->name('dashboard.user.update');
    Route::delete('/admin/user/delete/{id}', [\App\Http\Controllers\Admin\User\UserController::class, 'destroy'])->name('dashboard.user.delete');

    //hotel
    Route::get('/admin/hotel', [\App\Http\Controllers\Admin\Hotel\HotelController::class, 'index'])->name('dashboard.hotel');
    Route::get('/admin/hotel/create', [\App\Http\Controllers\Admin\Hotel\HotelController::class, 'create'])->name('dashboard.hotel.create');
    Route::get('/admin/hotel/edit/{id}', [\App\Http\Controllers\Admin\Hotel\HotelController::class, 'edit'])->name('dashboard.hotel.edit');
    Route::post('/admin/hotel/store', [\App\Http\Controllers\Admin\Hotel\HotelController::class, 'store'])->name('dashboard.hotel.store');
    Route::put('/admin/hotel/update/{id}', [\App\Http\Controllers\Admin\Hotel\HotelController::class, 'update'])->name('dashboard.hotel.update');
    Route::delete('/admin/hotel/delete/{id}', [\App\Http\Controllers\Admin\Hotel\HotelController::class, 'destroy'])->name('dashboard.hotel.delete');

    // room type
    Route::get('/admin/roomtype', [\App\Http\Controllers\Admin\Hotel\RoomtypeController::class, 'index'])->name('dashboard.roomtype');
    Route::get('/admin/roomtype/create', [\App\Http\Controllers\Admin\Hotel\RoomtypeController::class, 'create'])->name('dashboard.roomtype.create');
    Route::get('/admin/roomtype/edit/{id}', [\App\Http\Controllers\Admin\Hotel\RoomtypeController::class, 'edit'])->name('dashboard.roomtype.edit');
    Route::post('/admin/roomtype/store', [\App\Http\Controllers\Admin\Hotel\RoomtypeController::class, 'store'])->name('dashboard.roomtype.store');
    Route::put('/admin/roomtype/update/{id}', [\App\Http\Controllers\Admin\Hotel\RoomtypeController::class, 'update'])->name('dashboard.roomtype.update');
    Route::delete('/admin/roomtype/delete/{id}', [\App\Http\Controllers\Admin\Hotel\RoomtypeController::class, 'destroy'])->name('dashboard.roomtype.delete');

    // attribute
    Route::get('/admin/attribute', [\App\Http\Controllers\Admin\Hotel\AttributeController::class, 'index'])->name('dashboard.attribute');
    Route::get('/admin/attribute/create', [\App\Http\Controllers\Admin\Hotel\AttributeController::class, 'create'])->name('dashboard.attribute.create');
    Route::get('/admin/attribute/edit/{id}', [\App\Http\Controllers\Admin\Hotel\AttributeController::class, 'edit'])->name('dashboard.attribute.edit');
    Route::post('/admin/attribute/store', [\App\Http\Controllers\Admin\Hotel\AttributeController::class, 'store'])->name('dashboard.attribute.store');
    Route::put('/admin/attribute/update/{id}', [\App\Http\Controllers\Admin\Hotel\AttributeController::class, 'update'])->name('dashboard.attribute.update');
    Route::delete('/admin/attribute/delete/{id}', [\App\Http\Controllers\Admin\Hotel\AttributeController::class, 'destroy'])->name('dashboard.attribute.delete');

    // report
    Route::get('/admin/report', [\App\Http\Controllers\Admin\Report\ReportController::class, 'index'])->name('dashboard.report');

    //booking
    Route::get('/admin/booking', [\App\Http\Controllers\Admin\Report\BookingController::class, 'index'])->name('dashboard.admin.booking');
    Route::get('/admin/booking/confirmation/{id}', [\App\Http\Controllers\Admin\Report\BookingController::class, 'confirmation'])->name('admin.booking.confirmation');
    Route::get('/admin/booking/sendconfirmationtohotel/{id}', [\App\Http\Controllers\Admin\Report\BookingController::class, 'sendconfirmationtohotel'])->name('admin.booking.sendconfirmationtohotel');
    Route::get('/admin/booking/sendconfirmationtoagent/{id}', [\App\Http\Controllers\Admin\Report\BookingController::class, 'sendconfirmationtoagent'])->name('admin.booking.sendconfirmationtoagent');


    // setting
    Route::get('/admin/setting', [\App\Http\Controllers\Admin\Setting\SettingController::class, 'index'])->name('dashboard.setting');
    Route::post('/admin/setting/store', [\App\Http\Controllers\Admin\Setting\SettingController::class, 'store'])->name('dashboard.setting.store');
    Route::put('/admin/setting/update/{id}', [\App\Http\Controllers\Admin\Setting\SettingController::class, 'update'])->name('dashboard.setting.update');

    Route::post('/admin/storeslider/',[\App\Http\Controllers\Admin\Setting\SettingController::class,'storeslider'])->name('dashboard.setting.storeslider');
    Route::delete('/admin/destroyslider/{id}',[\App\Http\Controllers\Admin\Setting\SettingController::class,'destroyslider'])->name('dashboard.setting.destroyslider');

    //payment admin to hotel
    Route::get('/admin/paymenthotel', [\App\Http\Controllers\Admin\Hotel\PaymentController::class, 'index'])->name('dashboard.paymenttohotel.index');
    Route::get('/admin/paymenthotel/edit/{id}', [\App\Http\Controllers\Admin\Hotel\PaymentController::class, 'edit'])->name('dashboard.paymenttohotel.edit');
    Route::post('/admin/paymenthotel/update/{id}', [\App\Http\Controllers\Admin\Hotel\PaymentController::class, 'update'])->name('dashboard.paymenttohotel.update');
    Route::get('/admin/paymenthotel/destroy/{id}', [\App\Http\Controllers\Admin\Hotel\PaymentController::class, 'destroy'])->name('dashboard.paymenttohotel.destroy');

});

// untuk vendor
Route::group(['middleware' => ['auth', 'checkrole:2']], function() {
    Route::get('/vendordashboard', [\App\Http\Controllers\Vendor\DashboardController::class, 'index']);

    //booking history
    Route::get('/bookinghistory',[\App\Http\Controllers\Vendor\Booking\BookingHistoryController::class, 'index']);
    Route::get('/bookinghistory/detail/{id}',[\App\Http\Controllers\Vendor\Booking\BookingHistoryController::class, 'show'])->name('bookinghistory.details.vendor');

    //booking report
    Route::get('/bookingreport',[\App\Http\Controllers\Vendor\Booking\BookingReportController::class, 'index']);

    //enquiry report
    Route::get('/enquiryreport',[\App\Http\Controllers\Vendor\Enquiry\EnquiryReportController::class, 'index']);

    //manage news
    Route::get('/managenews',[\App\Http\Controllers\Vendor\News\NewsController::class, 'index']);

    //verifications
    Route::get('/verifications',[\App\Http\Controllers\Vendor\Verification\VerificationController::class, 'index']);

    //payouts
    Route::get('/payouts',[\App\Http\Controllers\Vendor\Payout\PayoutController::class, 'index'])->name('vendors.payouts.index');
    Route::post('/payouts/store',[\App\Http\Controllers\Vendor\Payout\PayoutController::class, 'store'])->name('vendors.payouts.store');

    //my profile
    Route::get('/myprofile',[\App\Http\Controllers\Vendor\MyProfile\MyProfileController::class, 'index'])->name('vendor.myprofile');
    Route::post('/myprofile/update',[\App\Http\Controllers\Vendor\MyProfile\MyProfileController::class, 'update']);
    Route::post('/myprofile/slider/store',[\App\Http\Controllers\Vendor\MyProfile\MyProfileController::class, 'addbanner']);
    Route::get('/myprofile/slider/delete/{id}',[\App\Http\Controllers\Vendor\MyProfile\MyProfileController::class, 'destroybanner']);

    Route::get('/vendor-profile/changepassword',[\App\Http\Controllers\Vendor\MyProfile\MyProfileController::class, 'passwordchange']);
    Route::post('/vendor-profile/updatepassword',[\App\Http\Controllers\Vendor\MyProfile\MyProfileController::class, 'updatepassword']);

    // room in hotel
    Route::get('/room/index',[\App\Http\Controllers\Vendor\Hotel\Room\IndexController::class, 'index'])->name('vendor.room');
    Route::get('/room/create',[\App\Http\Controllers\Vendor\Hotel\Room\IndexController::class, 'create']);
    Route::post('/room/store',[\App\Http\Controllers\Vendor\Hotel\Room\IndexController::class, 'store']);
    Route::post('/room/update/{id}',[\App\Http\Controllers\Vendor\Hotel\Room\IndexController::class, 'update']);
    Route::get('/room/edit/{id}',[\App\Http\Controllers\Vendor\Hotel\Room\IndexController::class, 'edit'])->name('vendor.room.edit');
    Route::get('/room/destroy/{id}',[\App\Http\Controllers\Vendor\Hotel\Room\IndexController::class, 'destroy'])->name('vendor.room.destroy');

    //price set up
    Route::get('/room/markup',[\App\Http\Controllers\Vendor\Hotel\Agent\MarkupController::class, 'index'])->name('roomagent.price');
    Route::get('/room/markup/create',[\App\Http\Controllers\Vendor\Hotel\Agent\MarkupController::class, 'create'])->name('roomagent.create');
    Route::get('/room/markup/edit/{id}',[\App\Http\Controllers\Vendor\Hotel\Agent\MarkupController::class, 'edit'])->name('roomagent.edit');
    Route::get('/room/markup/updateprice/{price}',[\App\Http\Controllers\Vendor\Hotel\Agent\MarkupController::class, 'update'])->name('roomagent.updateprice');


    Route::post('/room/agent/price',[\App\Http\Controllers\Vendor\Hotel\Agent\MarkupController::class, 'store']);
    Route::get('/room/markup/addblack',[\App\Http\Controllers\Vendor\Hotel\Agent\MarkupController::class, 'addblack'])->name('roomagent.addblack');
    Route::get('/room/markup/editblack/{id}',[\App\Http\Controllers\Vendor\Hotel\Agent\MarkupController::class, 'editblack'])->name('roomagent.editblack');
    Route::post('/room/markup/storeblack',[\App\Http\Controllers\Vendor\Hotel\Agent\MarkupController::class, 'storeblack'])->name('roomagent.storeblack');
    Route::post('/room/markup/updateblack/{id}',[\App\Http\Controllers\Vendor\Hotel\Agent\MarkupController::class, 'updateblack'])->name('roomagent.updateblack');
    Route::get('/room/markup/destroyblack/{id}',[\App\Http\Controllers\Vendor\Hotel\Agent\MarkupController::class, 'destroyblack'])->name('roomagent.destroyblack');

    Route::get('/room/markup/addsurchage',[\App\Http\Controllers\Vendor\Hotel\Agent\MarkupController::class, 'addsurchage'])->name('roomagent.addsurchage');
    Route::get('/room/markup/editsurchage/{id}',[\App\Http\Controllers\Vendor\Hotel\Agent\MarkupController::class, 'editsurchage'])->name('roomagent.editsurchage');
    Route::post('/room/markup/storesurchage',[\App\Http\Controllers\Vendor\Hotel\Agent\MarkupController::class, 'storesurchage'])->name('roomagent.storesurchage');
    Route::post('/room/markup/updatesurchage/{id}',[\App\Http\Controllers\Vendor\Hotel\Agent\MarkupController::class, 'updatesurchage'])->name('roomagent.updatesurchage');
    Route::get('/room/markup/destroysurchage/{id}',[\App\Http\Controllers\Vendor\Hotel\Agent\MarkupController::class, 'destroysurchage'])->name('roomagent.destroysurchage');


    //Bar Information
    Route::get('/room/barcode/index',[\App\Http\Controllers\Vendor\Hotel\Agent\BarController::class, 'index'])->name('barroom.index');
    Route::get('/room/barcode/create',[\App\Http\Controllers\Vendor\Hotel\Agent\BarController::class, 'create'])->name('barroom.create');
    Route::post('/room/barcode/store',[\App\Http\Controllers\Vendor\Hotel\Agent\BarController::class, 'store'])->name('barroom.store');
    Route::get('/room/barcode/edit/{id}',[\App\Http\Controllers\Vendor\Hotel\Agent\BarController::class, 'edit'])->name('barroom.edit');
    Route::post('/room/barcode/update/{id}',[\App\Http\Controllers\Vendor\Hotel\Agent\BarController::class, 'update'])->name('barroom.update');
    Route::post('/room/barcodeprice/update/{id}',[\App\Http\Controllers\Vendor\Hotel\Agent\BarController::class, 'barupdate'])->name('barprice.update');
    Route::get('/room/barcodeprice/cekroom/{id}',[\App\Http\Controllers\Vendor\Hotel\Agent\BarController::class, 'cekroom'])->name('barprice.cekroom');

    Route::get('/room/barcodeprice/destroy/{id}',[\App\Http\Controllers\Vendor\Hotel\Agent\BarController::class, 'bardestroy'])->name('barprice.destroy');
    Route::get('/room/barcode/destroy/{id}',[\App\Http\Controllers\Vendor\Hotel\Agent\BarController::class, 'destroy'])->name('barroom.destroy');

    //contract controller
    Route::get('/room/contract/index',[\App\Http\Controllers\Vendor\Hotel\Agent\ContractController::class, 'index'])->name('contract.index');
    Route::get('/room/contract/create',[\App\Http\Controllers\Vendor\Hotel\Agent\ContractController::class, 'create'])->name('contract.create');
    Route::post('/room/contract/store',[\App\Http\Controllers\Vendor\Hotel\Agent\ContractController::class, 'store'])->name('contract.store');
    Route::get('/room/contract/edit/{id}',[\App\Http\Controllers\Vendor\Hotel\Agent\ContractController::class, 'edit'])->name('contract.edit');
    Route::get('/room/contract/destroy/{id}',[\App\Http\Controllers\Vendor\Hotel\Agent\ContractController::class, 'destroy'])->name('contract.destroy');
    Route::post('/room/contract/update/{id}',[\App\Http\Controllers\Vendor\Hotel\Agent\ContractController::class, 'update'])->name('contract.update');
    Route::get('/room/contract/addcontractprice/{id}/{cont}',[\App\Http\Controllers\Vendor\Hotel\Agent\ContractController::class, 'addcontractprice'])->name('contract.addcontractprice');
    Route::get('/room/contract/updatecontractprice/{id}/{price}/{recom}',[\App\Http\Controllers\Vendor\Hotel\Agent\ContractController::class, 'updatecontractprice'])->name('contract.updatecontractprice');
    Route::get('/room/contract/destroycontractprice/{id}',[\App\Http\Controllers\Vendor\Hotel\Agent\ContractController::class, 'destroycontractprice'])->name('contract.destroycontractprice');
    Route::get('/room/contract/addallcontractprice/{cont}',[\App\Http\Controllers\Vendor\Hotel\Agent\ContractController::class, 'addallcontractprice'])->name('contract.addallcontractprice');

    //surcharge controller
    Route::get('/room/surcharge/index',[\App\Http\Controllers\Vendor\Hotel\Agent\SurchargeController::class, 'index'])->name('surcharge.index');
    Route::get('/room/surcharge/{hotel_room_id}/load-dates',[\App\Http\Controllers\Vendor\Hotel\Agent\SurchargeController::class, 'load_dates'])->name('surcharge.load_dates');
    Route::post('/room/surcharge/store',[\App\Http\Controllers\Vendor\Hotel\Agent\SurchargeController::class, 'store'])->name('surcharge.store');

    //advance purchase
    Route::post('/contract/advancepurchase/{id}',[\App\Http\Controllers\Vendor\Hotel\Agent\ContractController::class, 'updateadvance'])->name('advancepurchase.contract');
    Route::get('/contract/destroyadvanceprice/{id}',[\App\Http\Controllers\Vendor\Hotel\Agent\ContractController::class, 'destroyadvanceprice'])->name('destroyadvanceprice.contract');
    Route::get('/room/contract/updateadvancetprice/{id}/{price}',[\App\Http\Controllers\Vendor\Hotel\Agent\ContractController::class, 'updateadvancetprice'])->name('contract.updateadvancetprice');
    Route::get('/advance/updateadvancetstatus/{id}/{isactive}',[\App\Http\Controllers\Vendor\Hotel\Agent\ContractController::class, 'updateadvancetstatus'])->name('advance.updateadvancetstatus');

    //promo price
    Route::get('/room/promo/index/{id}',[\App\Http\Controllers\Vendor\Hotel\Agent\PromoPriceController::class, 'index'])->name('promo.index');
    Route::get('/room/promo/store/{id}',[\App\Http\Controllers\Vendor\Hotel\Agent\PromoPriceController::class, 'store'])->name('promo.store');


    //atttribute room
    Route::get('/room/attribute',[\App\Http\Controllers\Vendor\Hotel\Room\AttributeController::class, 'index'])->name('room.attribute.index');
    Route::get('/room/attribute/create',[\App\Http\Controllers\Vendor\Hotel\Room\AttributeController::class, 'create']);
    Route::post('/room/attribute/store',[\App\Http\Controllers\Vendor\Hotel\Room\AttributeController::class, 'store']);
    Route::get('/room/attribute/edit/{id}',[\App\Http\Controllers\Vendor\Hotel\Room\AttributeController::class, 'edit']);
    Route::post('/room/attribute/update/{id}',[\App\Http\Controllers\Vendor\Hotel\Room\AttributeController::class, 'update']);
    Route::get('/room/attribute/destroy/{id}',[\App\Http\Controllers\Vendor\Hotel\Room\AttributeController::class, 'destroy']);

});

// untuk agent
Route::group(['middleware' => ['auth', 'checkrole:3']], function() {
    Route::get('/agentdashboard', [\App\Http\Controllers\Agent\DashboardController::class, 'index']);

   //my profile
   Route::get('/agent-profile',[\App\Http\Controllers\Agent\MyProfile\MyProfileController::class, 'index'])->name('agent.myprofile');
   Route::post('/agent-profile/update',[\App\Http\Controllers\Agent\MyProfile\MyProfileController::class, 'update']);
   Route::get('/agent-profile/contact/create',[\App\Http\Controllers\Agent\MyProfile\MyProfileController::class, 'contactcreate'])->name('agent.contact.create');
   Route::post('/agent-profile/contact/store',[\App\Http\Controllers\Agent\MyProfile\MyProfileController::class, 'contactstore'])->name('agent.contact.store');
   Route::get('/agent-profile/contact/edit/{id}',[\App\Http\Controllers\Agent\MyProfile\MyProfileController::class, 'contactedit'])->name('agent.contact.edit');
   Route::post('/agent-profile/contact/update/{id}',[\App\Http\Controllers\Agent\MyProfile\MyProfileController::class, 'contactupdate'])->name('agent.contact.update');
   Route::post('/agent-profile/contact/destroy/{id}',[\App\Http\Controllers\Agent\MyProfile\MyProfileController::class, 'contactdestroy'])->name('agent.contact.destroy');
   Route::get('/agent-profile/password',[\App\Http\Controllers\Agent\MyProfile\MyProfileController::class, 'passwordchange'])->name('agent.password.change');
   Route::post('/agent-profile/contact/updatepassword',[\App\Http\Controllers\Agent\MyProfile\MyProfileController::class, 'updatepassword'])->name('agent.password.updatechange');

   //booking history
   Route::get('/agent/bookinghistory',[\App\Http\Controllers\Agent\Booking\BookingHistoryController::class, 'index'])->name('agent.booking.history');
   Route::get('/agent/bookinghistory/detail/{id}',[\App\Http\Controllers\Agent\Booking\BookingHistoryController::class, 'detail'])->name('agent.booking.detail');
   Route::get('/agent/bookinghistory/invoice/{id}',[\App\Http\Controllers\Agent\Booking\BookingHistoryController::class, 'invoice'])->name('agent.booking.invoice');


   //booking report
   Route::get('/agent-bookingreport',[\App\Http\Controllers\Agent\Booking\BookingReportController::class, 'index']);

   //enquiry report
   Route::get('/agent-enquiryreport',[\App\Http\Controllers\Agent\Enquiry\EnquiryReportController::class, 'index']);

    //booking page
    Route::post('/agent/booking',[\App\Http\Controllers\Agent\Booking\BookingController::class, 'create'])->name('booking.agent.create');
    Route::get('/agent/booking/detail/{id}',[\App\Http\Controllers\Agent\Booking\BookingController::class, 'detail'])->name('booking.agent.detail');
    Route::post('/agent/booking/store/{id}',[\App\Http\Controllers\Agent\Booking\BookingController::class,'bookingstore'])->name('booking.agent.store');

    //notify-callback ipaymu
    Route::get('/paymentsuccess',[\App\Http\Controllers\Agent\Booking\BookingController::class, 'paymentsuccess'])->name('payment.success');
    Route::get('/notify',[\App\Http\Controllers\Agent\Booking\BookingController::class, 'notify'])->name('payment.notify');

    //transfer bank
    Route::get('/paymentbookingpage/{id}',[\App\Http\Controllers\Agent\Booking\BookingController::class, 'paymentbookingpage'])->name('payment.paymentbookingpage');

    Route::post('/upbanktransfer',[\App\Http\Controllers\Agent\Booking\BookingController::class, 'upbanktransfer'])->name('payment.upbanktransfer');
});
