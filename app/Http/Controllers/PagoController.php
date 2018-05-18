<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Pago;
use App\Proveedor;
class PagoController extends Controller
{
    public function addPago(Request $request){
    	$create=Pago::create($request->all());
        return response()->json($create);
    }

    public function code(){
    	$max = Pago::count();
        if ($max > 0) {
            $row = explode('-',Pago::max('code'), 2);
            $cod = $row[1];
            $sig = $cod+1;
            $Strsig = (string)$sig;
            $formato = "P-".str_pad($Strsig, "5", "0", STR_PAD_LEFT);
            
        } 
        else {
            $sig = 1;
            $Strsig = (string)$sig;
            $formato = "P-".str_pad($Strsig,"5","0",STR_PAD_LEFT);
        }
        
        return response()->json($formato);
    }

    public function getProveedores(){
    	$proveedores=Proveedor::orderBy('id','desc')->get();
    	return response()->json($proveedores);
    }
}
