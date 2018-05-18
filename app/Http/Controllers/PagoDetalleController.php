<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PagoDetalle;

class PagoDetalleController extends Controller
{
    public function addPagoDetalle(Request $request){
        $create=PagoDetalle::create($request->all());
        return response()->json($create);
    }
}
