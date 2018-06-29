<?php

namespace App\Http\Controllers;
use App\Cliente;
use Illuminate\Http\Request;
use DB;
class ClienteController extends Controller
{
    public function addCliente(Request $request){
    	$create=Cliente::create($request->all());
        return response()->json($create);
    }
    public function getCliente($id){
    	$cliente=Cliente::find($id);
        return response()->json($cliente);
    }
    public function updateCliente($id,Request $request)
    {
       $edit=Cliente::find($id)->update($request->all());
       return response()->json($edit); 
    }
     public function deleteCliente($id)
    {
        $cliente=Cliente::where('id','=',$id)->first();
        if(@count($cliente)>=1){
            $cliente->estado=false;
            $cliente->save();
            return response()->json($cliente);
        }
    }
    public function getClientes(){
    	$clientes=DB::table('clientes')
                        ->join('tipo_documentos','clientes.id_documento','=','tipo_documentos.id')
                        ->select('clientes.id','clientes.nombre','clientes.apellido','tipo_documentos.documento','clientes.nro_documento','clientes.direccion','clientes.email','clientes.telefono')
                        ->where('clientes.estado','=',true)
                        ->get();
        return response()->json($clientes);
    }
}
