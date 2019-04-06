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


//Route::middleware(['jwt.auth'])->group(function(){
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
	//******************************** Pago *******************************
	//*******Pagos Servicios******
	Route::get('auth/servicio-list','ServicioController@getServicios');//listado de servicios
	Route::get('auth/servicio-code','ServicioController@code');//codigo
	Route::post('auth/servicio-add','ServicioController@addServicio');
	Route::get('auth/servicio-delete/{id}','ServicioController@deleteServicio');
	Route::get('auth/servicios-get','ServicioController@listServicios');
	//*******Pagos Proveedores****
	Route::post('auth/pagos-add','PagoController@addPago');
	Route::get('auth/pagos-code','PagoController@code');
	Route::get('auth/proveedor-list','PagoController@getProveedores');
	Route::get('auth/productos-listas','ProductosController@listaProductos');
	Route::get('auth/producto-detalle/{id}','ProductosController@listDetalleProducto');
	Route::get('auth/pagos-list/{id}','PagoController@listPagos');

	Route::get('auth/getpagostotales','PagoController@getpagosconusuario');
	
	//--------Anulacion de Pagos----------------------------
	Route::get('auth/compra-get/{code}','PagoController@getCompra');
	Route::get('auth/pagos_detalle-list/{code}','PagoController@getPagoDetalle');
	Route::get('auth/pagos-delete/{code}','PagoController@deletePago');

	Route::get('auth/pagos_d/{id}','PagoController@deletePagoDetalle');
	Route::get('getdetallepagosanulacion/{codigo}','PagoController@getdetallepagos');
	Route::get('Anulacion_insertarIyM/{id}','PagoController@insertarmodeinven')->where(['id' => '[0-9]+']);
	Route::get('Anulacion_borradealmacen/{id}','PagoController@borrardealmacen')->where(['id' => '[0-9]+']);
	//***Detalles Pago*****
	Route::post('auth/pago-detalle-add','PagoDetalleController@addPagoDetalle');
	Route::get('auth/get','PagoDetalleController@getAlmacen');
	Route::post('auth/almacen-detalle','PagoDetalleController@DetalleAlmacen');

	//***Unidades de producto*******
	Route::get('auth/unidad','UnidadController@getUnidad');
	//Route::post('auth/unidad','UnidadController@addUnidad');
	//Route::get('auth/unidad','UnidadController@ver' );
	Route::get('auth/unidad/{id}','UnidadController@seleccionar' )->where(['id' => '[0-9]+']);
	Route::post('auth/unidad','UnidadController@insertar');
	Route::post('auth/unidad/{id}','UnidadController@modificar')->where(['id' => '[0-9]+']);
	Route::post('auth/eliminarunidad/{id}','UnidadController@eliminar')->where(['id' => '[0-9]+']);

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
	Route::post('modificarimagenproductos/{id}','ProductosController@updateimages')->where(['id' => '[0-9]+']);

	//***Unidades de almacenes*******
	Route::get('almacenes','AlmacenesController@ver' );
	Route::get('almacenes/{id}','AlmacenesController@seleccionar' )->where(['id' => '[0-9]+']);
	Route::get('almacenes/eliminar/{id}','AlmacenesController@eliminar' )->where(['id' => '[0-9]+']);
	Route::post('almacenes','AlmacenesController@insertar');
	Route::post('almacenes/{id}','AlmacenesController@modificar')->where(['id' => '[0-9]+']);
	Route::get('veralmacen/{id}','AlmacenesController@veralmacen');
	Route::get('mostralamacenusuario/{id}','AlmacenesController@almacenusuario');

	//***inventario*******
	Route::get('inventario','InventarioController@ver' );
	Route::post('inventarioselect','InventarioController@seleccionar' );
	Route::get('inventario/eliminar/{id}','InventarioController@eliminar' )->where(['id' => '[0-9]+']);
	Route::post('inventario','InventarioController@insertar');
	Route::post('inventario/{id}','InventarioController@modificar')->where(['id' => '[0-9]+']);
	Route::get('productosalmacen/{id}','InventarioController@mostrarproductos' )->where(['id' => '[0-9]+']);
	Route::post('inventariopagos','InventarioController@insertardepagos');

	//***detalles_de almacen*******
	Route::get('mostrarlamacen/{id}','DetalleAlmacenController@ver' );
	Route::get('almacen/{id}','DetalleAlmacenController@seleccionar' )->where(['id' => '[0-9]+']);
	Route::get('almacen/eliminar/{id}','DetalleAlmacenController@eliminar' )->where(['id' => '[0-9]+']);
	Route::post('almacen','DetalleAlmacenController@insertar');
	Route::post('almacen/{id}','DetalleAlmacenController@modificar')->where(['id' => '[0-9]+']);
	Route::get('seleccionarporcaja/{id}','DetalleAlmacenController@seleccionarporcaja')->where(['id' => '[0-9]+']);

	//***Orden de pedido */
	Route::get('OrdenPedidos','orden_depedidocontroler@ver' );
	Route::get('OrdenPedidos/{id}','orden_depedidocontroler@seleccionar' )->where(['id' => '[0-9]+']);
	Route::get('OrdenPedidos/eliminar/{id}','orden_depedidocontroler@eliminar' )->where(['id' => '[0-9]+']);
	Route::post('OrdenPedidos','orden_depedidocontroler@insertar');
	Route::post('OrdenPedidos/{id}','orden_depedidocontroler@modificar')->where(['id' => '[0-9]+']);
	Route::get('orden/code','orden_depedidocontroler@code' );
	Route::get('orden-list/{id}','orden_depedidocontroler@getOrdenPedido' );

	//***de talle de Orden de pedido */
	Route::get('detalleordenselect/{id}','detalle_orden_depedidocontroler@ver' );
	Route::get('DetalleOrdenPedidos/{id}','detalle_orden_depedidocontroler@seleccionar' )->where(['id' => '[0-9]+']);
	Route::get('DetalleOrdenPedidos/eliminar/{id}','detalle_orden_depedidocontroler@eliminar' )->where(['id' => '[0-9]+']);
	Route::post('DetalleOrdenPedidos','detalle_orden_depedidocontroler@insertar');
	Route::post('DetalleOrdenPedidos/{id}','detalle_orden_depedidocontroler@modificar')->where(['id' => '[0-9]+']);

	//***DETALLE USUARIOS */
	Route::get('detalleusuario','Dettalle_UsuarioController@ver' );
	Route::post('detalleusuario','Dettalle_UsuarioController@insertar');
	Route::get('user/rol/{id}','UserController@rol');
	Route::post('modificardetalleusuario','Dettalle_UsuarioController@modificar');
	Route::get('detalleusuario/{id}','Dettalle_UsuarioController@getdetalleudsuario')->where(['id' => '[0-9]+']);
	Route::get('detalleusersucursal/{id}','Dettalle_UsuarioController@getdetalleudsuariosucursal')->where(['id' => '[0-9]+']);
	Route::get('detalleuseractual/{id}','Dettalle_UsuarioController@getdetalleudsuarioactual')->where(['id' => '[0-9]+']);
	Route::get('getcajasusuario/{id}','Dettalle_UsuarioController@getcajasusuariosucursal')->where(['id' => '[0-9]+']);
	
	/*-----------------------------------------sucursal ------------------------------------------*/
	Route::get('sucursales','SucursalController@getSucursales' );
	Route::get('sucursal/{id}','SucursalController@getSucursal' );
	Route::get('sucursal-delete/{id}','SucursalController@deleteSucursal' );
	Route::post('sucursal-add','SucursalController@addSucursal');
	Route::post('sucursal-update/{id}','SucursalController@updateSucursal');
	Route::get('sucursales-list','SucursalController@listSucursales');
	Route::get('getsucursalporusuario/{id}','SucursalController@getsucursalporusuario')->where(['id' => '[0-9]+']);

	//---------------------------Usuario ---------------------------------------/
	//***Usuario */
	Route::post('mantenimientousuario','UserController@insertar');
	Route::post('mantenimientousuario/{id}','UserController@modificar')->where(['id' => '[0-9]+']);
	Route::post('modificarpas/{id}','UserController@modificarcontra')->where(['id' => '[0-9]+']);
	Route::post('modificarimagen/{id}','UserController@updateimages')->where(['id' => '[0-9]+']);
	Route::get('getusersporsucursal/{id}','UserController@getusersporsucursal')->where(['id' => '[0-9]+']);

	Route::get('usuario/{id}','UserController@getusuario')->where(['id' => '[0-9]+']);
	Route::get('usuario','UserController@ver');
	Route::get('eliminarusuario/{id}','UserController@delete')->where(['id' => '[0-9]+']);

	Route::get('fecha','orden_depedidocontroler@fecha');
	Route::get('prueba','InventarioController@prueba');

	//--------------------------ubigeo-----------------------
	Route::post('exel-up','ubigeoController@upubigeoexel');
	Route::get('get-all','ubigeoController@vertodo');
	Route::get('get-provincias','ubigeoController@verprovincias');
	Route::get('get-departamentos','ubigeoController@verdepartamento');
	//------------------------------------------------------	
 
	//--------------------------Ventas-------------------------------
	//-----------------------Empresa--------------------------------
	Route::post('empresa-add','EmpresaController@addEmpresa');
	Route::get('empresa/{id}','EmpresaController@getEmpresa');
	Route::get('empresa-data','EmpresaController@dataEmpresa');
	Route::post('empresa-update/{id}','EmpresaController@updateEmpresa');
	Route::get('empresa-delete','EmpresaController@deleteEmpresa');
	Route::get('empresas','EmpresaController@getEmpresas');
	Route::post('imagen-up','EmpresaController@upImagen');
	Route::get('verificarsiexisteempresa','EmpresaController@verificarexist');
	
	//---------------------------------------------------------------
	//     *********   Firma y Certificado Digital   ************
	//---------------------------------------------------------------
	Route::post('certificado-up','FirmaController@upCertificado');
	Route::post('prueba','PruebaController@factura');
	Route::get('prue/{id}','PruebaController@company');
	//***********************************************************************	
	//------------------------Factura---------------------------------------
	//***********************************************************************
	Route::post('factura','FacturaController@factura');
	//factura con persepcion
	Route::post('factura-percepcion','FacturaController@facturaPercepcion');
	//factura Gratuita
	Route::post('factura-gratuita','FacturaController@facturaGratuita');
	//***********************************************************************	
	//------------------------Boleta---------------------------------------
	//***********************************************************************
	Route::post('boleta','BoletaController@boleta');
	//***********************************************************************
	//-----------------nota de credito y debito------------------------------
	//***********************************************************************
	Route::post('nota-credito','NotaController@notaCredito');
	Route::post('nota-debito','NotaController@notaDebito');




	//-------------------------------------------------------------
	//------------clientes-----------------------------------------
	//--------------------------------------------------------------
	Route::get('clientes','ClienteController@getClientes');
	Route::get('cliente/{id}','ClienteController@getCliente');
	Route::post('cliente-add','ClienteController@addCliente');
	Route::post('cliente-update/{id}','ClienteController@updateCliente');
	Route::get('cliente-delete/{id}','ClienteController@deleteCliente');
	//----------------Impuesto-------------------------------------------
	Route::get('impuestos','ImpuestoController@getImpuestos');
	Route::get('impuesto/{id}','ImpuestoController@getImpuesto');
	Route::post('impuesto-add','ImpuestoController@addImpuesto');
	Route::post('impuesto-update/{id}','ImpuestoController@updateImpuesto');
	Route::get('impuesto-delete/{id}','ImpuestoController@deleteImpuesto');

	Route::get('igv','ImpuestoController@getigv');
	Route::get('otro','ImpuestoController@getotro');
	Route::get('isc','ImpuestoController@getisc');

	//------------------------Monedas------------------------------------------------
	Route::get('monedas','MonedaController@getMonedas');
	Route::get('moneda/{id}','MonedaController@getMoneda');
	Route::post('moneda-add','MonedaController@addMoneda');
	Route::post('moneda-update/{id}','MonedaController@updateMoneda');
	Route::get('moneda-delete/{id}','MonedaController@deleteMoneda');
	//------------------------TipoPago----------------------------------------------
	Route::get('tipo_pagos','TipoPagoController@getTipoPagos');
	Route::get('tipo_pago/{id}','TipoPagoController@getTipoPago');
	Route::post('tipo_pago-add','TipoPagoController@addTipoPago');
	Route::post('tipo_pago-update/{id}','TipoPagoController@updateTipoPago');
	Route::get('tipo_pago-delete/{id}','TipoPagoController@deleteTipoPago');
	//--------------------detalle impuestos---------------------------------------
	Route::get('detalleimpuestosigv/{id}','detalle_impuestoController@verigv')->where(['id' => '[0-9]+']);
	Route::get('detalleimpuestosotro/{id}','detalle_impuestoController@verotro')->where(['id' => '[0-9]+']);
	Route::get('detalleimpuestosisc/{id}','detalle_impuestoController@verisc')->where(['id' => '[0-9]+']);
	Route::get('detalleimpuestos/{id}','detalle_impuestoController@verimpuestos')->where(['id' => '[0-9]+']);
	Route::post('editdetalleimpuestosigv','detalle_impuestoController@modificarigv');
	Route::post('editdetalleimpuestosotro','detalle_impuestoController@modificarotro');
	Route::post('detalleimpuestos','detalle_impuestoController@insertar');

	//--------------cajas---------------------------------------------------------------------
	Route::get('cajas','CajaController@ver' );
	Route::get('cajas/{id}','CajaController@seleccionar' )->where(['id' => '[0-9]+']);
	Route::get('cajas/eliminar/{id}','CajaController@eliminar' )->where(['id' => '[0-9]+']);
	Route::get('cajasporsucursal/{id}','CajaController@getcajasporsucursal' )->where(['id' => '[0-9]+']);
	Route::post('cajas','CajaController@insertar');
	Route::post('cajas/{id}','CajaController@modificar')->where(['id' => '[0-9]+']);
	//---------------DETALLE CAJAS-----------------------------------------------------------

	Route::post('apertura','Detalle_cajaController@apertura');
	Route::post('cierre','Detalle_cajaController@cierre');
	Route::get('obtenermontoapertura/{id}','Detalle_cajaController@montoapertura' )->where(['id' => '[0-9]+']);
	Route::get('buscarusuarioencaja/{id}','Detalle_cajaController@inicio')->where(['id' => '[0-9]+']);
	Route::get('buscarventas/{id}','Detalle_cajaController@mostrarventas')->where(['id' => '[0-9]+']);
	Route::get('buscarventasporsucursal/{id}','Detalle_cajaController@mostrarventasporsucursal')->where(['id' => '[0-9]+']);
	Route::get('getdetallecajas/{id}','Detalle_cajaController@getdetallecajas')->where(['id' => '[0-9]+']);
	Route::get('getdetallecaja/{id}','Detalle_cajaController@getdetallecaja')->where(['id' => '[0-9]+']);

	//---------------DETALLECAJAS USUARIOS-----------------------------------------
	
	Route::get('detalle_caja_usuarios/{id}','Detalle_caja_usuarioController@getusuariosporcaja' )->where(['id' => '[0-9]+']);
	Route::get('getcajasporusuario/{id}','Detalle_caja_usuarioController@getcajasporusuario' )->where(['id' => '[0-9]+']);
	Route::post('detalle_caja_usuarios','Detalle_caja_usuarioController@insertar');
	Route::post('editar_detalle_caja_usuarios','Detalle_caja_usuarioController@modificar');
	Route::get('eliminarusuariosporcaja/{id}','Detalle_caja_usuarioController@eliminartodacaja' )->where(['id' => '[0-9]+']);

	//---------------CODIGO DE PRODUCTOS-----------------------------------------
	
	Route::post('insertar_codigo_productos_vendible','codigo_productoController@insertarvendible');
	Route::get('getcodigo_productosporcaja/{id}','codigo_productoController@seleccionarcodigoporcajas' )->where(['id' => '[0-9]+']);
	Route::post('editarcodigo_producto','codigo_productoController@codigovendido');
	
	//-----------------------------------------------------------------
	//----------------Ventas--------------------------------------------------------
	
	Route::get('documentosdeventas','VentaController@getdocumento' );
	Route::get('getventastotales','VentaController@getventasconusuario' );
	Route::post('guardarventa','VentaController@insertar');
	Route::get('anularventa/{id}','VentaController@anular' )->where(['id' => '[0-9]+']);
	Route::get('getventaporserie/{id}','VentaController@getventaporserie' );
	Route::get('getventaporfecha/{fecha}/{id}','VentaController@getventasporfecha' )->where(['id' => '[0-9]+']);
	Route::get('getventaporusuario/{fecha}/{id}','VentaController@getventaporusuario' )->where(['id' => '[0-9]+']);
	Route::get('getproductosvendidos/{fecha}/{ID}','VentaController@getproductosvendidos' )->where(['id' => '[0-9]+']);
	Route::get('getventaporid/{id}','VentaController@getventaporid' )->where(['id' => '[0-9]+']);

	//----------------Detalle de ventas--------------------------------------------------------
	Route::post('guardardetalleventa','DetalleVentaController@insertar');	
	Route::post('guardariym','DetalleVentaController@insertarmoveinv');	
	Route::get('getdetalleventas/{id}','DetalleVentaController@getdetalleventas' )->where(['id' => '[0-9]+']);
	//----------------Nota de Credito--------------------------------------------------------
	Route::get('generarserienota/{id}','nota_creditoController@generarserienota');	
	Route::post('guardarnotacredito','nota_creditoController@insertar');
	Route::get('id_nota_creditos','nota_creditoController@getidnota');
	//----------------Nota de Debito--------------------------------------------------------
	Route::get('generarserien_debito/{id}','nota_debitoController@generarserienota');	
	Route::post('guardarnotadebito','nota_debitoController@insertar');
	//------------------------detalle nota de credito----------------------------------------------	
	Route::post('guardardetallenotacredito','detalle_notacreditoController@insertar');
	Route::get('pruebasnota/{id}','detalle_notacreditoController@prub');	
	Route::post('guardarmoveinvcredito','detalle_notacreditoController@movimientoseinventarionota');
	Route::post('anulacionesydevoluciones','detalle_notacreditoController@anulacionesydevoluciones');
	Route::get('getdetallenotacredito/{id}','detalle_notacreditoController@getdetalleporidnota');	
	
	//------------------------Entidad Financiera----------------------------------------------
	Route::get('Entidades','entidad_finacieraController@getentidad');
	Route::get('getentidad/{id}','entidad_finacieraController@seleccionar')->where(['id' => '[0-9]+']);
	Route::post('addentidad','entidad_finacieraController@insertar');
	Route::post('editentidad/{id}','entidad_finacieraController@modificar')->where(['id' => '[0-9]+']);
	Route::get('deleteentidad/{id}','entidad_finacieraController@eliminar')->where(['id' => '[0-9]+']);

	Route::get('redonde/{cantidad}','PagoDetalleController@redondeo');

//});
	Route::post('imagenes','UserController@upimagenes');
	Route::post('imagenesproductos','ProductosController@upimagenes');
	//-----------Imagenes-----------------------------------------
	//--------------Empresa---------------------------------------
	Route::get('empresa-img/{name}','EmpresaController@getImagen');
	//------------------------------------------------------------
	Route::get('imagenes/{name}','UserController@getimages');
	Route::get('imagenesproductos/{name}','ProductosController@getimages');
