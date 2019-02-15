<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\detalle_caja_usuario;
use App\movimiento_vendedores;
use App\cajas;
use Illuminate\Support\Facades\DB;

class Detalle_caja_usuarioController extends Controller
{
    public function insertar(Request $request){
        $json=$request->input('json',null);
        $params=json_decode($json);
        
     
        $id_usuario=(!is_null($json) && isset($params->id_usuario)) ? $params->id_usuario : null;
        $id_vendedor=(!is_null($json) && isset($params->id_vendedor)) ? $params->id_vendedor : null;

        $id_caja=cajas::get()->last();


            $detalle_caja_usuario=new detalle_caja_usuario();

            $detalle_caja_usuario->id_vendedor=$id_vendedor;
            $detalle_caja_usuario->id_caja=$id_caja['id'];
                //guardar
            $detalle_caja_usuario->save();
        
        $movimiento_vendedores=new movimiento_vendedores();
        $movimiento_vendedores->id_vendedor=$id_vendedor;
        $movimiento_vendedores->id_usuario=$id_usuario;
        $movimiento_vendedores->id_caja=$id_caja['id'];
        $movimiento_vendedores->estado="habilitado";
            $movimiento_vendedores->save();

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
        $id_vendedor=(!is_null($json) && isset($params->id_vendedor)) ? $params->id_vendedor : null;

        $movimiento_vendedores=new movimiento_vendedores();
        $movimiento_vendedores->id_vendedor=$id_vendedor;
        $movimiento_vendedores->id_usuario=$id_usuario;
        $movimiento_vendedores->id_caja=$id_caja;
        
        $validar=true;

        $indice=detalle_caja_usuario::where('id_vendedor','=',$id_vendedor)->where('id_caja','=',$id_caja)->first();
        if(@count($indice)==0){
            if($estado==true){
                $detalle_caja_usuario=new detalle_caja_usuario();

                $detalle_caja_usuario->id_vendedor=$id_vendedor;
                $detalle_caja_usuario->id_caja=$id_caja;
                    //guardar
                $detalle_caja_usuario->save();
           
                $movimiento_vendedores->estado="habilitado";
               
                $data =array(
                    'status'=>'succes',
                    'code'=>200,
                    'mensage'=>'guardado'
                );
            }else{
                $validar=false;
            }
           
        }else{
            /*$detalle_caja_usuario= detalle_caja_usuario::where('id_usuario','=',$id_usuario)->where('id_caja','=',$id_caja)
            ->update(['estado'=>$estado]);*/
            if($estado==false){
                $detalle_caja_usuario= detalle_caja_usuario::where('id_vendedor','=',$id_vendedor)->where('id_caja','=',$id_caja)->delete();
                $movimiento_vendedores->estado="deshabilitado";
                $data =array(
                    'status'=>'succes',
                    'code'=>200,
                    'mensage'=>'actualizado'
                );
            }
        }
        if($validar=true){ 
            $movimiento_vendedores->save();
        }
        return response()->json($data,200);

    }
    //para la edicion en la parte de cajas
    public function getusuariosporcaja($id){
        $detalle_caja_usuario=detalle_caja_usuario::where('id_caja',$id)
        //->where('estado',true)
        ->orderBy('id_vendedor')
        ->get();
        
        return response()->json($detalle_caja_usuario);
    }
    //para la parte de evntas
    public function getcajasporusuario($id){
        $detalle_caja_usuario=detalle_caja_usuario::join('cajas','detalle_caja_usuarios.id_caja','=','cajas.id')
        ->join('sucursals','cajas.id_sucursal','=','sucursals.id')
        ->where('cajas.estado',true)
        ->where('detalle_caja_usuarios.id_vendedor',$id)
        ->select('cajas.id','cajas.nombre','cajas.descripcion','sucursals.nombre_sucursal')
        ->get();
        
        return response()->json($detalle_caja_usuario);
    }
    public function eliminartodacaja($id){
        $movimiento_vendedores=new movimiento_vendedores();
        $movimiento=DB::table('detalle_caja_usuarios')->where('id_caja',$id)->get();
        foreach ($movimiento as $m) { 
            $movimiento_vendedores->id_usuario=$m->id_usuario;
            $movimiento_vendedores->id_caja=$id;
            $movimiento_vendedores->estado="deshabilitado";
            $movimiento_vendedores->save();
        } 
        
        $borrar=detalle_caja_usuario::where('id_caja','=',$id)->delete();
        $data =array(
            'status'=>'eliminado',
            'code'=>200,
            'mensage'=>$id
        );
        return response()->json($data,200);
    }
}
