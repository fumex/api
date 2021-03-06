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

    public function addTipo(Request $request){
        $create=TipoProveedor::create($request->all());
        return response()->json($create);
    }
}
