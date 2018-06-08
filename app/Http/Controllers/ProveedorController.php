<?php

namespace App\Http\Controllers;
use App\Proveedor;
use Illuminate\Http\Request;
use DB;
class ProveedorController extends Controller
{
    public function getProveedores(){
    	$proveedores=DB::table('proveedors')
                        ->join('tipo_proveedors','proveedors.tipo_proveedor','=','tipo_proveedors.id')
                        ->select('proveedors.id','proveedors.nombre_proveedor','proveedors.ruc','proveedors.direccion','proveedors.telefono','proveedors.email','tipo_proveedors.tipo')
                        ->where('proveedors.estado','=',true)->where('tipo_proveedors.operacion','=','Proveedor')
                        ->get();
    	return $proveedores;
    }

    public function deleteProveedores($id)
    {
        $proveedor=Proveedor::where('id','=',$id)->first();
        if(@count($proveedor)>=1){
            $proveedor->estado=false;
            $proveedor->save();
            return $proveedor;
        }
    }

    public function getProveedor($id){
        $proveedor=Proveedor::find($id);
        
        return response()->json($proveedor);
    }

    public function updateProveedores($id,Request $request)
    {
       $edit=Proveedor::find($id)->update($request->all());
       return response()->json($edit); 
    }
    public function addProveedores(Request $request){
       $create=Proveedor::create($request->all());
        return response()->json($create);
    } 
}
