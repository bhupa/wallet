<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
   
});

Route::group([
    'middleware' => 'auth:api'
  ], function() {
      Route::get('logout', 'Api\AuthController@logout');
      Route::get('/user', 'Api\ProfileController@index');
      Route::post('/balance-transfer', 'Api\TransactionController@store');
      Route::get('qrcode', 'Api\QRController@generateQrCode');
  });


Route::post('/register','Api\AuthController@register');
Route::post('/login','Api\AuthController@login');


Route::get('email/verify/{id}', 'Api\VerificationController@verify')->name('verification.verify'); // Make sure to keep this as your route name

Route::get('email/resend', 'Api\VerificationController@resend')->name('verification.resend');
