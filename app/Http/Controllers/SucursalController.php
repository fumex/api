<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Sucursal;
use DB;
class SucursalController extends Controller
{
    public function getSucursales(){
        $sucursales=Sucursal::where('estado','=',true)->get();
        return $sucursales;
    }
    public function listSucursales(){
        $sucursales=DB::table('sucursals')
                        ->join('almacenes','sucursals.id_almacen','=','almacenes.id')
                        ->select('sucursals.id','sucursals.nombre_sucursal','sucursals.direccion','sucursals.telefono','sucursals.telefono2','almacenes.nombre','sucursals.descripcion')
                        ->where('sucursals.estado','=',true)
                        ->get();
        return response()->json($sucursales);
    }
    public function addSucursal(Request $request){
        $create=Sucursal::create($request->all());
        return response()->json($create);
    }
       
    public function updateSucursal($id,Request $request){
        $edit=Sucursal::find($id)->update($request->all());
        return response()->json($edit);
    }

   public function getSucursal($id){
        $sucursal=Sucursal::find($id);
        return response()->json($sucursal);   
   }

    public function deleteSucursal($id){
        $sucursal=Sucursal::where('id','=',$id)->first();
        if(@count($sucursal)>=1){
            $sucursal->estado=false;
            $sucursal->save();
            return $sucursal;
        }
    }
    public function getsucursalporusuario($id){
        $sucursales=Sucursal::join('detalle_usuarios','sucursals.id','=', 'detalle_usuarios.id_sucursal')
        ->where('detalle_usuarios.id_user','=',$id)
        ->where('detalle_usuarios.permiso',true)
        ->where('sucursals.estado','=',true)
        ->select('sucursals.id','sucursals.nombre_sucursal','direccion','telefono') 
        ->get();
        return $sucursales;
    }
}
