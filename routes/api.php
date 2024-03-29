<?php

use Illuminate\Http\Request;

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

    Route::get('/equipments', 'Api\EquipmentController@index');
    Route::get('/heroes', 'Api\HeroController@index');
    Route::get('/pieces', 'Api\PieceController@index');
    Route::get('/categories', 'Api\CategoryController@index');
    Route::get('/combos', 'Api\ComboController@index');
    Route::get('/news', 'Api\NewsController@index');
    Route::get('/news/{id}/show', 'Api\NewsController@show');
    Route::get('/heroes/{id}/show', 'Api\HeroController@show');
    Route::get('/chess', 'Api\HeroController@chess');




    Route::get('/search/equipments', 'Api\EquipmentController@search');