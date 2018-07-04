<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Moneda;

class MonedaController extends Controller
{
    public function addMoneda(Request $request){
    	$create=Moneda::create($request->all());
    	return response()->json($create);
    }

    public function getMonedas(){
    	$monedas=Moneda::where('estado','=',true)->get();
    	return  response()->json($monedas);
    }

    public function deleteMoneda($id){
    	$moneda=Moneda::where('id','=',$id)->first();
        if(@count($moneda)>=1){
            $moneda->estado=false;
            $moneda->save();
            return $moneda;
        }
    }
    public function updateMoneda($id,Request $request){
    	$edit=Moneda::find($id)->update($request->all());
    	return response()->json($edit);
    }

    public function getMoneda($id){
    	$moneda=Moneda::find($id);
    	return response()->json($moneda);
    }
}
