<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Detalle_usuario;

class Dettalle_UsuarioController extends Controller
{
    
    public function insertar(Request $request){
        $json=$request->input('json',null);
        $params=json_decode($json);
        
        $id_sucursal=(!is_null($json) && isset($params->id_sucursal)) ? $params->id_sucursal : null;
        $id_user=(!is_null($json) && isset($params->id_user)) ? $params->id_user : null;
        $permiso=(!is_null($json) && isset($params->permiso)) ? $params->permiso : null;

            $Detalle_usuario=new Detalle_usuario();

            $Detalle_usuario->id_sucursal=$id_sucursal;
            $Detalle_usuario->id_user=$id_user;
            $Detalle_usuario->permiso=$permiso;
                //guardar
            $Detalle_usuario->save();
            $data =array(
                'status'=>'succes',
                'code'=>200,
                'mensage'=>'registrado'
            );
        return response()->json($data,200);
    }
       
    public function modificar($id,Request $request){
        $json=$request->input('json',null);
        $params=json_decode($json);
        
        $id_sucursal=(!is_null($json) && isset($params->id_sucursal)) ? $params->id_sucursal : null;
        $id_user=(!is_null($json) && isset($params->id_user)) ? $params->id_user : null;
        $permiso=(!is_null($json) && isset($params->permiso)) ? $params->permiso : null;
              //guardar
            $Detalle_usuario= Detalle_usuario::where('id',$id)->update(['id_sucursal'=>$id_sucursal,
            'id_user'=>$id_user,
            'permiso'=>$permiso]);

            $data =array(
                'status'=>'succes',
                'code'=>200,
                'mensage'=>'registrado'
            );
            
        return response()->json($data,200);

    }
}
