<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Admin\AuthController;
use App\Http\Controllers\API\Admin\FlatController;
use App\Http\Controllers\API\Admin\AllotmentController;
use App\Http\Controllers\API\Admin\NoticeController;
use App\Http\Controllers\API\Admin\EventController;
use App\Http\Controllers\API\Admin\AmenitiesController;
use App\Http\Controllers\API\Admin\PollController;
use App\Http\Controllers\API\Admin\MaintancerequestController;
use App\Http\Controllers\API\Admin\BookingamenitiesController;
use App\Http\Controllers\API\Admin\VisitorsController;
use App\Http\Controllers\API\Admin\MaintanceBillController;
use App\Http\Controllers\API\Admin\ReportController;
use App\Http\Controllers\API\User\EventtController;
use App\Http\Controllers\API\User\PollController as pollcon;
use App\Http\Controllers\API\staff\VistiorController as visitorentry;
use App\Http\Controllers\NotificationController;
Route::get('/user', function (Request $request) 
{
    return $request->user();
})->middleware('auth:sanctum');

Route::controller(AuthController::class)->group(function () 
{   
    Route::post('login', 'login');
    Route::post('register_rtw', 'register_rtw');
    Route::post('changepassword', 'changepassword');
    Route::post('securitypin', 'securitypin');
    Route::post('checksecuritypin', 'checksecuritypin');
    Route::post('edituser', 'edituser');
});
// Flat Routes
Route::prefix('flat')->group(function () {
    Route::post('/create', [FlatController::class, 'create']);
});

// Allotment Routes
Route::prefix('allotment')->group(function () {
    Route::post('/houselist', [AllotmentController::class, 'houselist']);
    Route::get('/blocklist', [AllotmentController::class, 'block_list']);
    Route::get('/userlist', [AllotmentController::class, 'userlist']);
    Route::post('/store', [AllotmentController::class, 'store']);
});

// Auth Routes
Route::prefix('auth')->group(function () {
    Route::post('/import', [AuthController::class, 'import']);
});

// Notice Routes
Route::prefix('notice')->group(function () {
    Route::post('/create', [NoticeController::class, 'create']);
    Route::get('/display', [NoticeController::class, 'display']);
    Route::get('/schedulenoticedisplay', [NoticeController::class, 'schedulenoticedisplay']);
    Route::post('/update', [NoticeController::class, 'noticeupdate']);
});

// Event Routes
Route::prefix('event')->group(function () {
    Route::post('/create', [EventController::class, 'create']);
    Route::post('/edit', [EventController::class, 'edit']);
    Route::post('/delete', [EventController::class, 'delete']);
    Route::post('/feedbacklist', [EventController::class, 'eventfeedbacklist']);
    Route::get('/display', [EventController::class, 'display']);
});

// Amenities Routes
Route::prefix('amenities')->group(function () {
    Route::post('/create', [AmenitiesController::class, 'create']);
    Route::get('/display', [AmenitiesController::class, 'display']);
    Route::post('/edit', [AmenitiesController::class, 'edit']);
    Route::post('/delete', [AmenitiesController::class, 'delete']);
});

// Poll Routes
Route::prefix('poll')->group(function () {
    Route::post('/create', [PollController::class, 'create']);
    Route::get('/display', [PollController::class, 'display']);
    Route::post('/details', [PollController::class, 'polldetails']);
    Route::post('/submit', [pollcon::class, 'submitpoll']);
});

// Booking Amenities Routes
Route::prefix('booking')->group(function () {
    Route::get('/bookedaemnites', [BookingamenitiesController::class, 'display']);
    Route::post('/changestatus', [BookingamenitiesController::class, 'changestatusbookaementies']);
});

// Visitor Routes
Route::prefix('visitor')->group(function () {
    Route::get('/prebookingrequestlist', [VisitorsController::class, 'prebookingrequestlist']);
    Route::get('/list', [VisitorsController::class, 'visitorlist']);
    Route::post('/approvalprebooking', [VisitorsController::class, 'approvalprebooking']);
});

// Maintenance Bill Routes
Route::prefix('maintance')->group(function () {
    Route::post('/bill/create', [MaintanceBillController::class, 'store']);
    Route::post('/bill/display', [MaintanceBillController::class, 'maintancebilldisplay']);
    Route::post('/bill/pay', [MaintanceBillController::class, 'paymaintance']);
});

// Notification Routes
Route::prefix('notification')->group(function () {
    Route::post('/send', [NotificationController::class, 'send']);
});

// Visitor Entry Routes
Route::prefix('visitor-entry')->group(function () {
    Route::post('/entry', [visitorentry::class, 'visitorentry']);
    Route::get('/entrydetails', [visitorentry::class, 'visitorentrydetails']);
    Route::get('/prelist', [visitorentry::class, 'previsitorlist']);
});

// Event Feedback Routes
Route::post('/eventfeedback', [EventtController::class, 'eventfeedback']);

// Maintenance Request Routes
Route::prefix('maintain-request')->group(function () {
    Route::get('/display', [MaintancerequestController::class, 'displaymaintancerequest']);
    Route::post('/assigntostaff', [MaintancerequestController::class, 'assigntostaff']);
    Route::get('/stafflist', [MaintancerequestController::class, 'stafflist']);
    Route::post('/status', [MaintancerequestController::class, 'maintancestatus']);
});

// Report Routes
Route::post('/report', [ReportController::class, 'report']);


// Route::post('/flatcreate',[FlatController::class,'create']);
// Route::post('/houselist',[AllotmentController::class,'houselist']);
// Route::get('/blocklist',[AllotmentController::class,'block_list']);
// Route::get('/userlist',[AllotmentController::class,'userlist']);
// Route::post('/alltoment',[AllotmentController::class,'store']);
// Route::post('/import',[AuthController::class,'import']);
// Route::post('/noticecreate',[NoticeController::class,'create']);
// Route::get('/noticedisplay',[NoticeController::class,'display']);
// Route::get('/schedulenoticedisplay',[NoticeController::class,'schedulenoticedisplay']);
// Route::post('/noticeupdate',[NoticeController::class,'noticeupdate']);
// Route::post('/eventcreate',[EventController::class,'create']);
// Route::post('/eventedit',[EventController::class,'edit']);
// Route::post('/eventdelete',[EventController::class,'delete']);
// Route::post('/eventfeedbacklist',[EventController::class,'eventfeedbacklist']);
// Route::get('/eventdisplay',[EventController::class,'display']);
// Route::post('/amenitiescreate',[AmenitiesController::class,'create']);
// Route::get('/amenitiesdisplay',[AmenitiesController::class,'display']);
// Route::post('/pollcreate',[PollController::class,'create']);
// Route::get('/polldisplay',[PollController::class,'display']);
// Route::post('/polldetails',[PollController::class,'polldetails']);
// Route::get('/bookedaemnites',[BookingamenitiesController::class,'display']);
// Route::post('/changestatusbookaementies',[BookingamenitiesController::class,'changestatusbookaementies']);
// Route::get('/prebookingrequestlist',[VisitorsController::class,'prebookingrequestlist']);
// Route::get('/visitorlist',[VisitorsController::class,'visitorlist']);
// Route::post('/approvalprebooking',[VisitorsController::class,'approvalprebooking']);
// Route::post('/maintancebillcreate',[MaintanceBillController::class,'store']);
// Route::post('/maintancebilldisplay',[MaintanceBillController::class,'maintancebilldisplay']);
// Route::post('/paymaintance',[MaintanceBillController::class,'paymaintance']);
// Route::post('/sendnotification',[NotificationController::class,'send']);
// Route::post('/vistorentry',[visitorentry::class,'visitorentry']);
// Route::get('/visitorentrydetails',[visitorentry::class,'visitorentrydetails']);
// Route::get('/previsitorlist',[visitorentry::class,'previsitorlist']);
// Route::post('/eventfeedback',[EventtController::class,'eventfeedback']);
// Route::get('/displaymaintancerequest',[MaintancerequestController::class,'displaymaintancerequest']);
// Route::post('/assigntostaff',[MaintancerequestController::class,'assigntostaff']);
// Route::get('/stafflist',[MaintancerequestController::class,'stafflist']);
// Route::post('/maintancestatus',[MaintancerequestController::class,'maintancestatus']);
// Route::post('/submitpoll',[pollcon::class,'submitpoll']);
// Route::post('/report',[ReportController::class,'report']);















