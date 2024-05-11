<?php

use Illuminate\Support\Facades\Route;
use Auth as Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
// working ..
Route::get('/', function () {
    return view('welcome');
})->name('main');
// After Login ..
Route::namespace('App\Http\Controllers')->middleware('auth')->group(function(){
    // Dashboard ..
    Route::get('/dashboard','pagesController@dashboard')->name('admin.dashboard');
    Route::get('/customers','pagesController@customer')->name('admin.customer');
});


// Authentication ..
Auth::routes();
// home ..
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/logout',function(){
    Auth::logout();
    return redirect(route('main'));
})->name('admin.logout');
