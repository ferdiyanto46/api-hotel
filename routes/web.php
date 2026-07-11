<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

// === Rute Autentikasi (Publik) ===
$router->group(['prefix' => 'auth'], function () use ($router) {
    $router->post('/register', 'AuthController@register'); // Registrasi customer
    $router->post('/login', 'AuthController@login');

    // Hanya Super Admin yang bisa mendaftarkan Admin baru
    $router->post('/register-admin', [
        'middleware' => ['auth', 'role:super-admin'],
        'uses'       => 'AuthController@registerAdmin',
    ]);
});

$router->group(['middleware' => 'auth'], function () use ($router){

    // route khusus Admin dan Super Admin
    $router->group(['middleware' => 'role:admin,super-admin'],function () use ($router){
        $router->put('/hotels/{id}', 'HotelController@update');
        $router->delete('/hotels/{id}', 'HotelController@destroy');


        $router->post('/rooms', 'RoomController@store');
        $router->put('/rooms/{id}', 'RoomController@update');
        $router->delete('/rooms/{id}', 'RoomController@destroy');

        $router->post('/room-types', 'RoomTypeController@store');
        $router->put('/room-types/{id}', 'RoomTypeController@update');
        $router->post('/rooms/{id}', 'RoomController@update'); 
        $router->delete('/room-types/{id}', 'RoomTypeController@destroy');
    });
    // route khusus Super Admin
    $router->group(['middleware' => 'role:super-admin'], function () use ($router){
        $router->post('/hotels', 'HotelController@store');
        $router->get('/hotels/overview', 'HotelController@overview');
    });

    // route khusus Customer
    $router->post('/bookings/checkout', 'BookingController@checkout');
    $router->post('/bookings/{id}/pay', 'BookingController@retryPayment'); // Bayar ulang booking pending
    $router->get('/bookings', 'BookingController@index');
    $router->get('/bookings/{id}', 'BookingController@show');
});

$router->get('/hotels', 'HotelController@index');            // Daftar + filter: ?search=...&city=...
$router->get('/hotels/{id}', 'HotelController@showById');
$router->get('/rooms', 'RoomController@index');              // Filter: ?status=available&room_type_id=1
$router->get('/rooms/{id}', 'RoomController@show');
$router->get('/room-types', 'RoomTypeController@index');
$router->get('/room-types/{id}', 'RoomTypeController@show');

$router->post('/midtrans/notification', 'BookingController@handleNotification');