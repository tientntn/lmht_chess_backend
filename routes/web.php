<?php

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

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/config', function (){
    $user = App\Models\User::where('username', '=', 'admin')->first();
    if (!$user) {
        $user = new App\Models\User;
        $user->username = 'admin';
        $user->password = bcrypt('123456');
        $user->email = 'admin@gmail.com';
        $user->role_id = 1;
        $user->status = 1;
        $user->save();
    }
    echo 'config xong. vui long nhap lieu';
});


Route::group(array('middleware' => 'https', 'language'), function()
{
    Route::get('/login', function (){
      if (Auth::check()) {
        return redirect('/');
      } else {
        return view('users.login');
      }
    });
    Route::post('login', 'Auth\LoginController@postLogin');
    Route::get('/logout', 'Auth\LoginController@logout');
});

Route::group(array('middleware' => ['https','auth_manage', 'language']), function()
{

    Route::get('/', 'HomeController@index');
    Route::get('/home', 'HomeController@index');
    Route::get('/manage', 'HomeController@index');

     Route::group(array('middleware' => 'admin'), function()
    {
        Route::get('/equipments/search', 'EquipmentController@search');
        Route::post('/equipments/{id}/destroy', 'EquipmentController@destroy');
        Route::resource('equipments', 'EquipmentController');

        Route::get('/pieces/search', 'PieceController@search');
        Route::post('/pieces/{id}/destroy', 'PieceController@destroy');
        Route::resource('pieces', 'PieceController');

        Route::post('/users/search', 'UserController@search');
        Route::get('/users/search', 'UserController@search');
        Route::post('/users/{id}/updateStatus', 'UserController@updateStatus');
        Route::post('/users/{id}/destroy', 'UserController@destroy');
        Route::get('/users/exportExcel', 'UserController@exportExcel');
        Route::get('/users/export-csv', 'UserController@exportCSV');
        Route::post('/users/downloadExcel/{file}', 'UserController@downloadExcel');
        Route::post('/users/deleteMulti', 'UserController@deleteMulti');
        Route::get('/users/{id}/dashboard', 'UserController@dashboardUser');
        Route::get('/users/change-password', 'UserController@getChangePassword');
        Route::post('/users/change-password', 'UserController@postChangePassword');
        Route::resource('users', 'UserController');

    });
});
