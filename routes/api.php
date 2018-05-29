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
	Route::post('auth/proveedores-update/{id}','ProveedorController@updateProveedores');
	Route::get('auth/proveedores-delete/{id}','ProveedorController@deleteProveedores');
	Route::get('auth/proveedor/{id}','ProveedorController@getProveedor');
	//tipo de documento
	Route::get('auth/documentos','TipoDocumentoController@getDocumentos');
	Route::get('auth/documento/{id}','TipoDocumentoController@getDocumento');
	Route::get('auth/documento-delete/{id}','TipoDocumentoController@deleteDocumento');
	Route::post('auth/documento-add','TipoDocumentoController@addDocumento');
	Route::post('auth/documento-update/{id}','TipoDocumentoController@updateDocumento');
	Route::get('auth/documentos-persona','TipoDocumentoController@getDocumenPersona');
	Route::get('auth/documentos-comprobante','TipoDocumentoController@getDocumenComprobante');
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
	Route::get('auth/get','PagoDetalleController@getAlmacen');
	Route::post('auth/almacen-detalle','PagoDetalleController@DetalleAlmacen');
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
	Route::post('/productos/{iden}','ProductosController@modificar')->where(['id' => '[0-9]+']);
	Route::get('/productos/buscar/{name}','ProductosController@buscar');
	Route::get('/productos-get','ProductosController@getProductos');

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

	//***Orden de pedido */
	Route::get('OrdenPedidos','orden_depedidocontroler@ver' );
	Route::get('OrdenPedidos/{id}','orden_depedidocontroler@seleccionar' )->where(['id' => '[0-9]+']);
	Route::get('OrdenPedidos/eliminar/{id}','orden_depedidocontroler@eliminar' )->where(['id' => '[0-9]+']);
	Route::post('OrdenPedidos','orden_depedidocontroler@insertar');
	Route::post('OrdenPedidos/{id}','orden_depedidocontroler@modificar')->where(['id' => '[0-9]+']);

	//***de talle de Orden de pedido */
	Route::get('DetalleOrdenPedidos','detalle_orden_depedidocontroler@ver' );
	Route::get('DetalleOrdenPedidos/{id}','detalle_orden_depedidocontroler@seleccionar' )->where(['id' => '[0-9]+']);
	Route::get('DetalleOrdenPedidos/eliminar/{id}','detalle_orden_depedidocontroler@eliminar' )->where(['id' => '[0-9]+']);
	Route::post('DetalleOrdenPedidos','detalle_orden_depedidocontroler@insertar');
	Route::post('DetalleOrdenPedidos/{id}','detalle_orden_depedidocontroler@modificar')->where(['id' => '[0-9]+']);

	Route::get('fecha','orden_depedidocontroler@fecha');

	Route::post('prueba','InventarioController@prueba');
});



