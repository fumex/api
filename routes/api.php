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

Route::post('auth/login','AuthenticateController@login');
Route::post('auth/refresh','AuthenticateController@refresh');
Route::get('auth/logout','AuthenticateController@logout');

Route::middleware(['jwt.auth'])->group(function(){
	Route::get('auth/me','AuthenticateController@me');
	Route::get('auth/tasks','TaskController@getAll');
	Route::post('auth/tasks','TaskController@add');
	Route::get('auth/tasks/{id}','TaskController@get');
	Route::post('auth/tasks/{id}','TaskController@edit');
	Route::get('auth/tasks/delete/{id}','TaskController@delete');
	//Proveedor
	Route::get('auth/proveedores','ProveedorController@getProveedores');
	Route::post('auth/proveedores-add','ProveedorController@addProveedores');
	Route::put('auth/proveedores-update/{id}','ProveedorController@updateProveedores');
	Route::get('auth/proveedores-delete/{id}','ProveedorController@deleteProveedores');
	//tipo Proveedor
	Route::get('auth/tipo','TipoProveedorController@getAll');
	Route::post('auth/tipo-add','TipoProveedorController@addTipo');
	//Route::get('tipo/{id}','TipoProveedorController@get');
	
	//***Pagos****
	Route::post('auth/pagos-add','PagoController@addPago');
	Route::get('auth/pagos-code','PagoController@code');
	Route::get('auth/proveedor-list','PagoController@getProveedores');
	//***Detalles Pago*****
	Route::post('auth/pago-detalle-add','PagoDetalleController@addPagoDetalle');

	//***Unidades de producto*******
	Route::get('auth/unidades','UnidadController@getUnidad');
	Route::post('auth/unidad','UnidadController@addUnidad');
});



