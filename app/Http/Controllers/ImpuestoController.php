<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Impuesto;
class ImpuestoController extends Controller
{
    public function addImpuesto(Request $request){
    	$create=Impuesto::create($request->all());
        return response()->json($create);
    }
    public function getImpuesto($id){
    	$impuesto=Impuesto::find($id);
        return response()->json($impuesto);
    }
    public function updateImpuesto($id,Request $request)
    {
       $edit=Impuesto::find($id)->update($request->all());
       return response()->json($edit); 
    }
     public function deleteImpuesto($id)
    {
        $impuesto=Impuesto::where('id','=',$id)->first();
        if(@count($impuesto)>=1){
            $impuesto->estado=false;
            $impuesto->save();
            return response()->json($impuesto);
        }
    }
    public function getImpuestos(){
    	$impuestos=Impuesto::where('estado','=',true)->get();
    	return  response()->json($impuestos);
    }
    public function getigv(){
    	$impuesto=Impuesto::all()->where('tipo','IGV')->where('estado','=',true);
        return response()->json($impuesto);
    }
    public function getotro(){
        $impuesto=Impuesto::where('tipo','OTRO')->where('estado','=',true)->get();
        return response()->json($impuesto);
    }
}
