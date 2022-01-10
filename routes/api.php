<?php
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MoMo_API;
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


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('/test', function () {
    return 'Hello World';
})->middleware('auth:api');


Route::group(['middleware'=>'auth:api'], function(){


//Call Controller method for making the uuid_v4 equivalent to the api user     
Route::post('/uuidv4User', [MoMo_API::class, 'uuidv4User']);

});









