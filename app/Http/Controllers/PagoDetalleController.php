<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PagoDetalle;
use App\Pago;
use App\detalle_almacen;
use App\Inventario;
use App\Movimiento;
use App\Almacenes;
use App\Productos;
use App\TipoDocumento;
class PagoDetalleController extends Controller
{
    public function addPagoDetalle(Request $request){
        $create=PagoDetalle::create($request->all());
      	return response()->json($create);
    }
    public function DetalleAlmacen(Request $request){
    	$pago=Pago::get()->last(); //recupera el ultimo pago realizado
    	$pago_d=PagoDetalle::get()->last();  //recupera el ultimo detalle
    	$id=$pago['id_almacen'];
    	$cantidad=$pago_d['cantidad'];
        $codigo=$request->codigo;
        $vendible=$request->vendible;
    	$id_pro=$request->id_producto;
    	$pre_comp=$request->precio_compra;
		$pre_vent=$request->precio_venta;
    	$d_almace=detalle_almacen::where('id_almacen','=',$id)
                                   ->where('id_producto','=',$id_pro)
                                   ->where('vendible','=',$vendible)
                                   ->first();
    	if(@count($d_almace)>=1){
//actualizacion de precio de detalle_almacen---------------------------------------------------------------------
			$stockactual=$d_almace['stock'];
        	$precioctual=$d_almace['precio_compra'];
			$costoactualizado=(($stockactual *$precioctual)+($cantidad*$pre_comp))/($stockactual+$cantidad);
			$d_almace->precio_compra=$costoactualizado;
//-----------------------------------------------------------------------------------------------------------

			$d_almace->stock=$d_almace['stock']+$cantidad;
			$d_almace->update();

			return response()->json($d_almace);
    	}
    	else{
    		$detalle_almacen= new detalle_almacen();
	    	$detalle_almacen->id_almacen=$id;
            $detalle_almacen->codigo=$codigo;
            $detalle_almacen->vendible=$vendible;
	    	$detalle_almacen->id_producto=$id_pro;
	    	$detalle_almacen->stock=$cantidad ;
	    	$detalle_almacen->precio_compra=$pre_comp;
	    	$detalle_almacen->precio_venta=$pre_vent;
			$detalle_almacen->save();
	    	return response()->json($detalle_almacen);
		}


    }	
}
