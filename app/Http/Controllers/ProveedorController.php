<?php

namespace App\Http\Controllers;
use App\Proveedor;
use Illuminate\Http\Request;

class ProveedorController extends Controller
{
    public function getProveedores(){
    	$proveedores=Proveedor::all();
    	return $proveedores;
    }

    public function addProveedores(Request $request){
        $json=$request->input('json',null);
        $params=json_decode($json);

        $nombre_proveedor=(!is_null($json) && isset($params->nombre_proveedor))?$params->nombre_proveedor:null;
        $ruc=(!is_null($json) && isset($params->ruc))?$params->ruc:null;
        $direccion=(!is_null($json) && isset($params->direccion))?$params->direccion:null;
        $telefono=(!is_null($json) && isset($params->telefono))?$params->telefono:null;
        $email=(!is_null($json) && isset($params->email))?$params->email:null;
        $tipo_proveedor=(!is_null($json) && isset($params->tipo_proveedor))?$params->tipo_proveedor:null;

        if(!is_null($nombre_proveedor) && !is_null($ruc) && !is_null($direccion) && !is_null($telefono) && !is_null($tipo_proveedor)){

            $proveedor = new Proveedor ();
            $proveedor->nombre_proveedor=$nombre_proveedor;
            $proveedor->ruc=$ruc;
            $proveedor->direccion=$direccion;
            $proveedor->telefono=$telefono;
            $proveedor->email=$email;
            $proveedor->tipo_proveedor=$tipo_proveedor;

            $isset_proveedor=Proveedor::where('nombre_proveedor','=',$proveedor->nombre_proveedor)->first();
            if(@count($isset_proveedor)===0){
                $proveedor->save();
                $data=array(
                    'status'=>'success',
                    'code'=>200,
                    'mensage'=>'registrado'
                );
            }
            else{
                $data =array(
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
                'mesaje'=>'faltan datos'
            );
        }
        return response()->json($data,200);
    } 
}
