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


Route::get('/user', function (Request $request) 
{
    return $request->user();
})->middleware('auth:sanctum');

Route::controller(AuthController::class)->group(function () 
{
    Route::post('login', 'login');
    Route::post('register_rtw', 'register_rtw');



});
Route::post('/flatcreate',[FlatController::class,'create']);
Route::post('/houselist',[AllotmentController::class,'houselist']);
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











