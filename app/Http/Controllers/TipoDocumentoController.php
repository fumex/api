<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TipoDocumento;
class TipoDocumentoController extends Controller
{
    public function addDocumento(Request $request){
    	$create=TipoDocumento::create($request->all());
    	return response()->json($create);
    }

    public function getDocumentos(){
    	$documentos=TipoDocumento::where('estado','=',true)->get();
    	return  response()->json($documentos);
    }
    public function getDocumenPersona(){
        $persona=TipoDocumento::where('operacion','=','Personal')
                                ->where('estado','=',true)
                                ->get();
        return response()->json($persona);
    }
    public function getDocumenComprobante(){
        $comprobante=TipoDocumento::where('operacion','=','Comprobante')
                                    ->where('estado','=',true)
                                    ->get();
        return response()->json($comprobante);
    }

    public function deleteDocumento($id){
    	$documento=TipoDocumento::where('id','=',$id)->first();
        if(@count($documento)>=1){
            $documento->estado=false;
            $documento->save();
            return $documento;
        }
    }
    public function updateDocumento($id,Request $request){
    	$edit=TipoDocumento::find($id)->update($request->all());
    	return response()->json($edit);
    }

    public function getDocumento($id){
    	$documento=TipoDocumento::find($id);
    	return response()->json($documento);
    }

}
