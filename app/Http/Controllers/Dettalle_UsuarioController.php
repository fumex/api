<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Detalle_usuario;
use App\detalle_caja_usuario;
use App\User;

class Dettalle_UsuarioController extends Controller
{
    
    public function insertar(Request $request){
        $json=$request->input('json',null);
        $params=json_decode($json);
        
        $id_sucursal=(!is_null($json) && isset($params->id_sucursal)) ? $params->id_sucursal : null;
        $permiso=(!is_null($json) && isset($params->permiso)) ? $params->permiso : null;
        $id_user=User::get()->last();
            $Detalle_usuario=new Detalle_usuario();

            $Detalle_usuario->id_sucursal=$id_sucursal;
            $Detalle_usuario->id_user=$id_user['id'];
            $Detalle_usuario->permiso=true;
                //guardar
            $Detalle_usuario->save();
            $data =array(
                'status'=>'succes',
                'code'=>200,
                'mensage'=>'registrado'
            );
        return response()->json($data,200);
    }
       
    public function modificar(Request $request){
        $json=$request->input('json',null);
        $params=json_decode($json);
        
        $id_sucursal=(!is_null($json) && isset($params->id_sucursal)) ? $params->id_sucursal : null;
        $id_user=(!is_null($json) && isset($params->id_user)) ? $params->id_user : null;
        $permiso=(!is_null($json) && isset($params->permiso)) ? $params->permiso : null;
        $indice=Detalle_usuario::where('id_user','=',$id_user)->where('id_sucursal','=',$id_sucursal)->first();
        if(@count($indice)==0){
            $Detalle_usuario=new Detalle_usuario();

            $Detalle_usuario->id_sucursal=$id_sucursal;
            $Detalle_usuario->id_user=$id_user;
            $Detalle_usuario->permiso=$permiso;
                //guardar
            $Detalle_usuario->save();
            $data =array(
                'status'=>'succes',
                'code'=>200,
                'mensage'=>'guardado'
            );
        }else{
            $Detalle_usuario= Detalle_usuario::where('id_user','=',$id_user)->where('id_sucursal','=',$id_sucursal)->update(['permiso'=>$permiso]);
            $data =array(
                'status'=>'succes',
                'code'=>200,
                'mensage'=>'actualizado'
            );
        }
        return response()->json($data,200);

    }
    public function getdetalleudsuario($id){
        $Detalle_usuario=Detalle_usuario::where('id_user',$id)->get();
        
        return response()->json($Detalle_usuario);
    }
    public function getdetalleudsuariosucursal($id){
        //$detalle=detalle_caja_usuario::where('estado',true)->get();
        $Detalle_usuario=Detalle_usuario::join('users','detalle_usuarios.id_user','=','users.id')
        ->leftjoin('detalle_caja_usuarios','detalle_caja_usuarios.id_usuario','=','detalle_usuarios.id_user')
        ->where('detalle_caja_usuarios.id_usuario',null)
        //->whereNotIn('users.id',[$detalle['id_usuario']])
        //->where('detalle_caja_usuarios.estado',true)
        ->where('id_sucursal',$id)
        ->where('detalle_usuarios.permiso',true)
        ->select('users.id','users.name','users.apellidos','users.rol','users.numero_documento')
        ->get();
        
        return response()->json($Detalle_usuario);
    }
    public function getdetalleudsuarioactual($id){
        //$detalle=detalle_caja_usuario::where('estado',true)->get();
        $Detalle_usuario=Detalle_usuario::join('users','detalle_usuarios.id_user','=','users.id')
        ->join('detalle_caja_usuarios','detalle_caja_usuarios.id_usuario','=','detalle_usuarios.id_user')
        ->where('detalle_caja_usuarios.id_caja',$id)
        //->whereNotIn('users.id',[$detalle['id_usuario']])
        //->where('detalle_caja_usuarios.estado',true)
        ->where('detalle_usuarios.permiso',true)
        ->select('users.id','users.name','users.apellidos','users.rol','users.numero_documento')
        ->get();
        
        return response()->json($Detalle_usuario);
    }
}
