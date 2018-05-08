<?php

namespace App\Http\Controllers;

use App\TipoProveedor;
use Illuminate\Http\Request;

class TipoProveedorController extends Controller
{
    public function getAll(){
        $tipos=TipoProveedor::all();
        return $tipos;
    }
    public function add(Request $request ){
        $tipo=TipoProveedor::create($request->all());
        return $tipo;
    }
    public function get($id){
        $tipo=TipoProveedor::find($id);
        return $tipo;
    }    
}
