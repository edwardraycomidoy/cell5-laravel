<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\MembersController;
use App\Http\Controllers\CollectionsController;
use App\Http\Controllers\PaymentsController;
use App\Http\Controllers\SpreadsheetController;

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

Route::get('/', function() {
    return view('welcome');
});

Route::resource('members', MembersController::class);
Route::resource('collections', CollectionsController::class);
Route::resource('payments', PaymentsController::class)->only(['store', 'destroy']);

Route::get('/spreadsheet', [SpreadsheetController::class, 'index'])->name('spreadsheet');
