<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TipoPago;
class TipoPagoController extends Controller
{
    public function addTipoPago(Request $request){
    	$create=TipoPago::create($request->all());
    	return response()->json($create);
    }

    public function getTipoPagos(){
    	$tipo_pagos=TipoPago::where('estado','=',true)->get();
    	return  response()->json($tipo_pagos);
    }

    public function deleteTipoPago($id){
    	$tipo_pago=TipoPago::where('id','=',$id)->first();
        if(@count($tipo_pago)>=1){
            $tipo_pago->estado=false;
            $tipo_pago->save();
            return $moneda;
        }
    }
    public function updateTipoPago($id,Request $request){
    	$edit=TipoPago::find($id)->update($request->all());
    	return response()->json($edit);
    }

    public function getTipoPago($id){
    	$tipo_pago=TipoPago::find($id);
    	return response()->json($tipo_pago);
    }
}
