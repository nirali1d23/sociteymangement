<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminPanel\AuthController;
use App\Http\Controllers\AdminPanel\ResidenetController;
use App\Http\Controllers\AdminPanel\FlatController;
use App\Http\Controllers\AdminPanel\AllotmentController;
use App\Http\Controllers\AdminPanel\EventController;
use App\Http\Controllers\AdminPanel\NoticeController;
use App\Http\Controllers\AdminPanel\VisitorController;
use App\Http\Controllers\AdminPanel\PrevisitorController;
use App\Http\Controllers\AdminPanel\MaintanceController;
use App\Http\Controllers\AdminPanel\AmenitiesController;
use App\Http\Controllers\AdminPanel\BookedamenitiesController;

Route::get('/', function ()
{
    return view('Auth.login');
});
Route::get('/dashboard', function () {
    return view('admin_panel.admin.dashbord');
});
Route::view('/terms-and-conditions', 'pages.terms')->name('terms');

Route::view('/privacy-policy', 'pages.privacy')->name('privacy');

Route::view('/contact-us', 'pages.contact')->name('contact');
Route::post('/contact-us', function (Request $request) {
    return redirect()
        ->route('contact')
        ->with('submitted', true);
})->name('contact.submit');
Route::post('/login',[AuthController::class,'authlogin'])->name('login');
Route::post('/singout',[AuthController::class,'singout'])->name('singout');
Route::get('/add-residene',[ResidenetController::class,'index'])->name('add-residenet');
Route::post('/userstore',[ResidenetController::class,'store'])->name('userstore');
Route::get('/products-ajax-crud/{id}/edit',[ResidenetController::class,'edit'])->name('products-ajax-crud.edit');
Route::delete('/userdelete/{id}',[ResidenetController::class,'delete'])->name('userdelete');
Route::get('/flate',[FlatController::class,'index'])->name('flate');
Route::post('/flatstore',[FlatController::class,'store'])->name('flatstore');
Route::get('/alltoment',[AllotmentController::class,'index'])->name('alltoment');
Route::get('/event',[EventController::class,'index'])->name('event');
Route::get('/notice',[NoticeController::class,'index'])->name('notice');
Route::get('/visitor',[VisitorController::class,'index'])->name('visitor');
Route::get('/previsitor',[PrevisitorController::class,'index'])->name('previsitor');
Route::get('/maintance',[MaintanceController::class,'index'])->name('maintance');
Route::get('/amenities',[AmenitiesController::class,'index'])->name('amenities');
Route::post('/amenitiesstore',[AmenitiesController::class,'store'])->name('amenitiesstore');
Route::get('/bookamenities',[BookedamenitiesController::class,'index'])->name('bookamenities');
Route::post('/update-amenity-status', [BookedamenitiesController::class,'updatestatus'])->name('updateAmenityStatus');

Route::get('/poll', function () 
{
    return view('admin_panel.admin.poll');
})->name('poll');


