<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Sucursal;

class SucursalController extends Controller
{
    public function getSucursales(){
        $sucursales=Sucursal::where('habilitado','=','habilitado')->get();
        return $sucursales;
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
}
