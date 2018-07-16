<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\detalle_caja_usuario;
use App\cajas;

class Detalle_caja_usuarioController extends Controller
{
    public function insertar(Request $request){
        $json=$request->input('json',null);
        $params=json_decode($json);
        
     
        $id_usuario=(!is_null($json) && isset($params->id_usuario)) ? $params->id_usuario : null;
        

        $id_caja=cajas::get()->last();
            $detalle_caja_usuario=new detalle_caja_usuario();

            $detalle_caja_usuario->id_usuario=$id_usuario;
            $detalle_caja_usuario->id_caja=$id_caja['id'];
            $detalle_caja_usuario->estado=true;
                //guardar
            $detalle_caja_usuario->save();
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
        
        $id_usuario=(!is_null($json) && isset($params->id_usuario)) ? $params->id_usuario : null;
        $id_caja=(!is_null($json) && isset($params->id_caja)) ? $params->id_caja : null;
        $estado=(!is_null($json) && isset($params->estado)) ? $params->estado : null;
        $indice=detalle_caja_usuario::where('id_usuario','=',$id_usuario)->where('id_caja','=',$id_caja)->first();
        if(@count($indice)==0){
            $detalle_caja_usuario=new detalle_caja_usuario();

            $detalle_caja_usuario->id_usuario=$id_usuario;
            $detalle_caja_usuario->id_caja=$id_caja;
            $detalle_caja_usuario->estado=$estado;
                //guardar
            $detalle_caja_usuario->save();
            $data =array(
                'status'=>'succes',
                'code'=>200,
                'mensage'=>'guardado'
            );
        }else{
            $detalle_caja_usuario= detalle_caja_usuario::where('id_usuario','=',$id_usuario)->where('id_caja','=',$id_caja)
            ->update(['estado'=>$estado]);
            $data =array(
                'status'=>'succes',
                'code'=>200,
                'mensage'=>'actualizado'
            );
        }
        return response()->json($data,200);

    }
    //para la edicion en la parte de cajas
    public function getusuariosporcaja($id){
        $detalle_caja_usuario=detalle_caja_usuario::where('id_caja',$id)
        ->where('estado',true)
        ->orderBy('id_usuario')
        ->get();
        
        return response()->json($detalle_caja_usuario);
    }
    //para la parte de evntas
    public function getcajasporusuario($id){
        $detalle_caja_usuario=detalle_caja_usuario::join('cajas','detalle_caja_usuarios.id_caja','=','cajas.id')
        ->join('sucursals','cajas.id_sucursal','=','sucursals.id')
        ->where('detalle_caja_usuarios.estado',true)
        ->where('cajas.estado',true)
        ->where('detalle_caja_usuarios.id_usuario',$id)
        ->select('cajas.id','cajas.nombre','cajas.descripcion','sucursals.nombre_sucursal')
        ->get();
        
        return response()->json($detalle_caja_usuario);
    }
}
