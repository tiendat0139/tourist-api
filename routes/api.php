<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CompanyController;
use App\Http\Controllers\API\HotelController;
use App\Models\Company;

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

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum'])->group(function(){
    //Logout
    Route::post('/logout', [AuthController::class,'logout']);
});

Route::middleware(['auth:sanctum', 'isApiCompany'])->prefix('company')->group(function(){
    Route::get('/checkingAuthenticated', function (){
        return response()->json(['status' => 200, 'message' => 'You are in'], 200);
    });
    Route::get('/get-types', [CompanyController::class, 'getTypes']);
    Route::post('/add-tour', [CompanyController::class, 'createTour']);
    Route::get('/total-product', [CompanyController::class, 'totalProduct']);
});

//Hotels
Route::get('/all-city', [HotelController::class, 'getCities']);
Route::get('/get-hotellist/{cityId}', [HotelController::class, 'getHotelList']);
Route::get('/get-hoteldetail/{hotelId}',[HotelController::class, 'getHotelDetail']);


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
