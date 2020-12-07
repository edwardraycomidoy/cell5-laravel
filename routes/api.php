<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\LogoutController;

use App\Http\Controllers\Api\ApiMembersController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*
Route::middleware('auth:sanctum')->get('/user', function(Request $request) {
	$user = $request->user();
	return [
		'id' => $user->id,
		'first_name' => $user->first_name,
		'last_name' => $user->last_name,
		'email' => $user->email
	];
});
*/

Route::post('/login', [LoginController::class, 'store']);

Route::middleware('auth:sanctum')->group(function() {

	Route::get('/user', function(Request $request) {
		$user = $request->user();
		return [
			'id' => $user->id,
			'first_name' => $user->first_name,
			'last_name' => $user->last_name,
			'email' => $user->email
		];
	});

	Route::delete('/logout', [LogoutController::class, 'destroy']);

	Route::get('/members/search', [ApiMembersController::class, 'search']);
	Route::resource('members', ApiMembersController::class);
	//Route::resource('collections', CollectionsController::class);
	//Route::resource('payments', PaymentsController::class)->only(['store', 'destroy']);
});
