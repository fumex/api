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
    	$json=$request->input('json',null);
    	$params=json_decode($json);

    	$tipo=(!is_null($json)&& isset($params->tipo))?$params->tipo:null;

    	if(!is_null($tipo)){
    		$tipoProveedor = new TipoProveedor(); 

    		$tipoProveedor->tipo=$tipo;
    		$isset_tipo=TipoProveedor::where('tipo','=',$tipoProveedor->tipo)->first();
    		if(@count($isset_tipo)===0){
    			$tipoProveedor->save();

    			$data=array(
    				'status'=>'success',
    				'code'=>200,
    				'mensage'=>'registrado'
    			);
    		}
    		else{
    			$data= array(
    				'status'=>'error',
    				'code'=>300,
    				'mensage'=>'ya existe'

    			);
    		}
    	}
    	else{
    		$data=array(
    			'status'=>'error',
    			'code'=>400,
    			'mensage'=>'faltan datos'
    		);
    	}
    }
}
