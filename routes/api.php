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

	//***categorias*******
	Route::get('categorias','CategoriaController@ver' );
	Route::get('categorias/{id}','CategoriaController@seleccionar' )->where(['id' => '[0-9]+']);
	Route::get('categorias/eliminar/{id}','CategoriaController@eliminar' )->where(['id' => '[0-9]+']);
	Route::post('categorias','CategoriaController@insertar');
	Route::post('categorias/{id}','CategoriaController@modificar')->where(['id' => '[0-9]+']);

	//***productos*******
	Route::get('/productos','ProductosController@ver' );
	Route::get('/productos/{id}','ProductosController@seleccionar' )->where(['id' => '[0-9]+']);
	Route::get('/productos/eliminar/{id}','ProductosController@eliminar' )->where(['id' => '[0-9]+']);
	Route::post('/productos','ProductosController@insertar');
	Route::post('/productos/{id}','ProductosController@modificar')->where(['id' => '[0-9]+']);
	Route::get('/productos/buscar/{name}','ProductosController@buscar');

	//***Unidades de almacenes*******
	Route::get('almacenes','AlmacenesController@ver' );
	Route::get('almacenes/{id}','AlmacenesController@seleccionar' )->where(['id' => '[0-9]+']);
	Route::get('almacenes/eliminar/{id}','AlmacenesController@eliminar' )->where(['id' => '[0-9]+']);
	Route::post('almacenes','AlmacenesController@insertar');
	Route::post('almacenes/{id}','AlmacenesController@modificar')->where(['id' => '[0-9]+']);
	Route::get('veralmacen/{id}','AlmacenesController@veralmacen');

	//***inventario*******
	Route::get('inventario','InventarioController@ver' );
	Route::get('inventario/{id}','InventarioController@seleccionar' )->where(['id' => '[0-9]+']);
	Route::get('inventario/eliminar/{id}','InventarioController@eliminar' )->where(['id' => '[0-9]+']);
	Route::post('inventario','InventarioController@insertar');
	Route::post('inventario/{id}','InventarioController@modificar')->where(['id' => '[0-9]+']);

	//***Udetalles_de almacen*******
	Route::get('almacen','DetalleAlmacenController@ver' );
	Route::get('almacen/{id}','DetalleAlmacenController@seleccionar' )->where(['id' => '[0-9]+']);
	Route::get('almacen/eliminar/{id}','DetalleAlmacenController@eliminar' )->where(['id' => '[0-9]+']);
	Route::post('almacen','DetalleAlmacenController@insertar');
	Route::post('almacen/{id}','DetalleAlmacenController@modificar')->where(['id' => '[0-9]+']);

	Route::get('prueba','InventarioController@prueba');
});



