<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class SucursalController extends Controller
{
    public function ver(){
        
    }

    public function addSucursal(Request $request){
        $create=Sucursal::create($request->all());
        return response()->json($create);
    }
       
    public function updateSucursal($id,Request $request){
        $edit=Sucursal::find($id)->update($request->all());
    }

   public function seleccionar($id){
   
   }

    public function eliminar($id){
        
    }
}
