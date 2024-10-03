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
use App\Http\Controllers\API\Admin\BookingamenitiesController;
use App\Http\Controllers\API\Admin\VisitorsController;
use App\Http\Controllers\API\Admin\MaintanceBillController;
use App\Http\Controllers\API\User\EventtController;
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
});

Route::post('/flatcreate',[FlatController::class,'create']);
Route::post('/houselist',[AllotmentController::class,'houselist']);
Route::get('/blocklist',[AllotmentController::class,'block_list']);
Route::get('/userlist',[AllotmentController::class,'userlist']);
Route::post('/alltoment',[AllotmentController::class,'store']);
Route::post('/import',[AuthController::class,'import']);
Route::post('/noticecreate',[NoticeController::class,'create']);
Route::get('/noticedisplay',[NoticeController::class,'display']);
Route::post('/eventcreate',[EventController::class,'create']);
Route::get('/eventdisplay',[EventController::class,'display']);
Route::post('/amenitiescreate',[AmenitiesController::class,'create']);
Route::get('/amenitiesdisplay',[AmenitiesController::class,'display']);
Route::post('/pollcreate',[PollController::class,'create']);
Route::get('/polldisplay',[PollController::class,'display']);
Route::get('/bookedaemnites',[BookingamenitiesController::class,'display']);
Route::post('/changestatusbookaementies',[BookingamenitiesController::class,'changestatusbookaementies']);
Route::get('/prebookingrequestlist',[VisitorsController::class,'prebookingrequestlist']);
Route::get('/visitorlist',[VisitorsController::class,'visitorlist']);
Route::post('/approvalprebooking',[VisitorsController::class,'approvalprebooking']);
Route::post('/maintancebillcreate',[MaintanceBillController::class,'store']);
Route::post('/sendnotification',[NotificationController::class,'send']);
Route::post('/vistorentry',[visitorentry::class,'visitorentry']);
Route::get('/visitorentrydetails',[visitorentry::class,'visitorentrydetails']);
Route::get('/previsitorlist',[visitorentry::class,'previsitorlist']);
Route::post('/eventfeedback',[EventtController::class,'eventfeedback']);












