<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PagoDetalle;
use App\Pago;
use App\detalle_almacen;
class PagoDetalleController extends Controller
{
    public function addPagoDetalle(Request $request){
        $create=PagoDetalle::create($request->all());
      	return response()->json($create);
    }
    public function DetalleAlmacen(Request $request){
    	$pago=Pago::get()->last(); //recuperael ultimo pago realizado
    	$pago_d=PagoDetalle::get()->last();  //recupera el ultimo detalle
    	$id=$pago['id_almacen'];
    	$cantidad=$pago_d['cantidad'];
    	$id_pro=$request->id_producto;
    	$pre_comp=$request->precio_compra;
    	$pre_vent=$request->precio_venta;
    	$d_almace=detalle_almacen::where('id_almacen','=',$id)->where('id_producto','=',$id_pro)->first();
    	if(@count($d_almace)>=1){
    		$d_almace->stock=$d_almace['stock']+$cantidad;
    		$d_almace->update();
    		return response()->json($d_almace);
    	}
    	else{

    		$detalle_almacen= new detalle_almacen();
	    	$detalle_almacen->id_almacen=$id;
	    	$detalle_almacen->id_producto=$id_pro;
	    	$detalle_almacen->stock =$cantidad ;
	    	$detalle_almacen->precio_compra=$pre_comp;
	    	$detalle_almacen->precio_venta=$pre_vent;
	    	$detalle_almacen->save();
	    	return $detalle_almacen;
    	}
    }	
}
