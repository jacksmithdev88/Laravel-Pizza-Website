<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\userController;
use App\Http\Controllers\favouriteController;
use App\Http\Controllers\orderController;
use \App\Http\Controllers\redirectController;

Route::get('/', function () {
    return view('main');
});

Route::post('/Login', [userController::class, 'login']);
Route::post('/addPizza', [orderController::class, 'addPizza']);
Route::post('/logout', [userController::class, 'logout']);
Route::post('/addFav', [favouriteController::class, 'addFav']);
Route::post('/clear', [orderController::class, 'clear']);
Route::post('/subOrder', [orderController::class, 'submit']);
Route::post('/orderFav', [favouriteController::class, 'orderFav']);
Route::post('/showFav', [favouriteController::class, 'showFav']);
Route::post('/checkDeals', [orderController::class, 'checkDeals']);
Route::post('/removeFav', [favouriteController::class, 'removeFav']);

Route::get('/Login',  [redirectController::class, 'redirect']);
Route::get('/addPizza', [redirectController::class, 'redirect']);
Route::get('/logout', [redirectController::class, 'redirect']);
Route::get('/addFav', [redirectController::class, 'redirect']);
Route::get('/clear', [redirectController::class, 'redirect']);
Route::get('/subOrder', [redirectController::class, 'redirect']);
Route::get('/orderFav', [redirectController::class, 'redirect']);
Route::get('/showFav', [redirectController::class, 'redirect']);
Route::get('/checkDeals', [redirectController::class, 'redirect']);
Route::get('/removeFav', [redirectController::class, 'redirect']);

