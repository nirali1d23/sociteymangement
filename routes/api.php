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
use App\Http\Controllers\API\User\MaintanceController as maintancon;
use App\Http\Controllers\API\User\PollController as pollcon;
use App\Http\Controllers\API\User\VistiorController as vistiorcon;
use App\Http\Controllers\API\User\NoticeController as noticecon;
use App\Http\Controllers\API\User\AmenitiesController as amenticon;
use App\Http\Controllers\API\User\MaintanceBillController as mainbill;
use App\Http\Controllers\API\staff\VistiorController as visitorentry;
use App\Http\Controllers\API\staff\MaintancestController as maincon;
use App\Http\Controllers\NotificationController;
Route::get('/user', function (Request $request) 
{
    return $request->user();
})->middleware('auth:sanctum');

Route::controller(AuthController::class)->group(function() 
{   
    Route::post('login', 'login');
    Route::post('register_rtw', 'register_rtw');
    Route::post('import', 'import');
    Route::post('changepassword', 'changepassword');
    Route::post('securitypin', 'securitypin');
    Route::post('checksecuritypin', 'checksecuritypin');
    Route::post('edituser', 'edituser');
    Route::post('deleteuser', 'deleteuser');
});

// Flat Routes
Route::post('/flatcrete', [FlatController::class, 'create']);

// Allotment Routes
Route::controller(AllotmentController::class)->group(function () 
{
    Route::post('/houselist', 'houselist');
    Route::get('/blocklist', 'block_list');
    Route::get('/userlist', 'userlist');
    Route::post('/alltoment', 'store');
    Route::post('/base64Image', 'base64Image');
});

// Auth Routes
Route::post('/import', [AuthController::class, 'import']);

// Notice Routes
Route::controller(NoticeController::class)->group(function () {
    Route::post('/noticecreate', 'create');
    Route::get('/noticedisplay', 'display');
    Route::get('/schedulenoticedisplay', 'schedulenoticedisplay');
    Route::post('/noticeupdate', 'noticeupdate');
    Route::post('/commentlist', 'commentlist');
    Route::post('/noticedelete', 'noticedelete');
});

// Event Routes
Route::controller(EventController::class)->group(function () {
    Route::post('/eventcreate', 'create');
    Route::post('/eventedit', 'edit');
    Route::post('/eventdelete', 'delete');
    Route::post('/eventfeedbacklist', 'eventfeedbacklist');
    Route::get('/eventdisplay', 'display');
});

// Amenities Routes
Route::controller(AmenitiesController::class)->group(function () {
    Route::post('/amenitiescreate', 'create');
    Route::post('/amenitiesdisplay', 'display');
    Route::post('/amenitiesedit', 'edit');
    Route::post('/amenitiesdelete', 'delete');
    Route::post('/generateTimeSlots', 'generateTimeSlots');
});

// Poll Routes
Route::controller(PollController::class)->group(function () {
    Route::post('/pollcreate', 'create');
    Route::post('/polldisplay', 'display');
    Route::post('/polldetails', 'polldetails');
});

// Booking Amenities Routes
Route::controller(BookingamenitiesController::class)->group(function () {
    Route::get('/bookedaemnites', 'display');
    Route::post('/changestatusbookaementies', 'changestatusbookaementies');
});



// Visitor Routes
Route::controller(VisitorsController::class)->group(function () {
    Route::get('/prebookingrequestlist', 'prebookingrequestlist');
    Route::get('/visitorlist', 'visitorlist');
    Route::post('/approvalprebooking', 'approvalprebooking');
});

// Maintenance Bill Routes
Route::controller(MaintanceBillController::class)->group(function () {
    Route::post('/maintancebillcreate', 'store');
    Route::post('/maintancebilldisplay', 'maintancebilldisplay');
    Route::post('/paymaintance', 'paymaintance');
    Route::post('/maitnacebilldropdown', 'maitnacebilldropdown');
});

// Notification Routes
Route::post('/sendnotification', [NotificationController::class, 'send']);
Route::post('/maintancebilllist', [mainbill::class, 'maintancebilllist']);

// Visitor Entry Routes
Route::controller(visitorentry::class)->group(function () {
    Route::post('/vistorentry', 'visitorentry');
    Route::get('/visitorentrydetails', 'visitorentrydetails');
    Route::get('/previsitorlist', 'previsitorlist');
    Route::post('/updateprevisitor', 'updateprevisitor');
    Route::post('/updatevisitor', 'updatevisitor');
});
Route::controller(vistiorcon::class)->group(function () {
    Route::post('/prebookvistior', 'prebookvistior');
    Route::post('/uservisitorlist', 'visitorlist');
    Route::post('/pendingvistorlist', 'pendingvistorlist');
    Route::post('/approvevistiorr', 'approvevistior');
});
Route::controller(maintancon::class)->group(function () {
    Route::post('/requestmaitnace', 'requestmaitnace');
});
Route::controller(maincon::class)->group(function () {
    Route::post('/updatemaintance', 'updatemaintance');
    Route::post('/maintancelist', 'maintancelist');
});

Route::controller(noticecon::class)->group(function () {
    Route::post('/notice_comment', 'notice_comment');
 
});

// Event Feedback Routes
Route::post('/eventfeedback', [EventtController::class, 'eventfeedback']);

// Maintenance Request Routes
Route::controller(MaintancerequestController::class)->group(function () {
    Route::get('/displaymaintancerequest', 'displaymaintancerequest');
    Route::post('/assigntostaff', 'assigntostaff');
    Route::get('/stafflist', 'stafflist');
    Route::post('/maintancestatus', 'maintancestatus');
});

// Report Routes
Route::post('/report', [ReportController::class, 'report']);
Route::get('/popuplist', [ReportController::class, 'popuplistadmin']);
Route::post('/popupliststaff', [ReportController::class, 'popupliststaff']);
Route::post('/popuplistuser', [ReportController::class, 'popuplistuser']);
Route::post('/requestamenitiesbooking', [amenticon::class, 'requestamenitiesbooking']);
Route::post('/cancelbooking', [amenticon::class, 'cancelbooking']);
Route::post('/submitpoll',[pollcon::class,'submitpoll']);


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
// Route::post('/report',[ReportController::class,'report']);















