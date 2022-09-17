<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\FoodController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

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

Route::post('auth/login', [AuthController::class, 'login'])->middleware('guest');
Route::get('auth/login', function () {
    return view('login');
})->middleware('guest');
Route::redirect('/', '/admin/home');
Route::post('auth/logout', [AuthController::class, 'logout']);
Route::group(['middleware' => 'auth'], function () {
    Route::get('/admin/home', [HomeController::class, 'index']);
    Route::get('/admin/categories', [CategoryController::class, 'index']);
    Route::get('/admin/categories/get', [CategoryController::class, 'getListCategory']);
    Route::post('/admin/categories/save', [CategoryController::class, 'save']);
    Route::delete('/admin/categories/delete', [CategoryController::class, 'delete']);
    Route::get('/admin/foods', [FoodController::class, 'index']);
});
