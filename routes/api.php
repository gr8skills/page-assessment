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

Route::group(['prefix'=>'v1'], function (){
    Route::post('/seed/transactions/{qty}', 'WalletController@seedTransactions');
    Route::post('/more/wallets', 'WalletController@attachMultipleWalletsToUser');
    Route::get('/users', 'WalletController@allUsers');
    Route::get('/user/{id}', 'WalletController@singleUser');
    Route::get('/wallets', 'WalletController@allWallets');
    Route::get('/wallet/{nuban}', 'WalletController@singleWallet');
    Route::get('/stats', 'WalletController@getStatistics');
    Route::post('/send-money', 'WalletController@transact');
});
