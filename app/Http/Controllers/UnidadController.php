<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Unidad;
class UnidadController extends Controller
{
    public function addUnidad(Request $request){
    	$this->validate($request,[
    		'unidad'=>'required',
    	]);
    	$create=Unidad::create($request->all());
    	return response()->json($create);
    }
    public function getUnidad(){
    	$unidad=Unidad::all();
    	return $unidad;
    }
}
