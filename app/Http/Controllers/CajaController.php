<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\cajas;


class CajaController extends Controller
{
    public function ver(){
        return $listar=cajas::join('sucursals','cajas.id_sucursal','=','sucursals.id')
        ->join('users','cajas.responsable','=','users.id')
        ->select('cajas.id','cajas.nombre','users.name','sucursals.nombre_sucursal','cajas.descripcion')
        ->where('cajas.estado',true)
        ->orderBy('cajas.id')
        ->get();
    }

    /*public function cajausuario($id){
 
        $almacen=Almacenes::join('sucursals','almacenes.id','=','sucursals.id_almacen')
        ->join('detalle_usuarios','sucursals.id','=',  'detalle_usuarios.id_sucursal')
        ->where('detalle_usuarios.id_user','=',$id)
        ->where('permiso','=',true)
        ->where('almacenes.estado',true)
        ->select('almacenes.id','almacenes.nombre','almacenes.descripcion','almacenes.direccion','almacenes.telefono','almacenes.id_user')
        ->distinct('almacenes.nombre')
        ->get();

        return $almacen;
    }  */




    public function insertar(Request $request){
        $json=$request->input('json',null);
        $params=json_decode($json);
        
        $nombre	=(!is_null($json) && isset($params->nombre)) ? $params->nombre : null;
        $descripcion=(!is_null($json) && isset($params->descripcion)) ? $params->descripcion : null;
        $id_sucursal=(!is_null($json) && isset($params->id_sucursal)) ? $params->id_sucursal : null;
        $responsable=(!is_null($json) && isset($params->responsable)) ? $params->responsable : null;
        $id_user=(!is_null($json) && isset($params->id_user)) ? $params->id_user : null;

        if(!is_null($nombre)){
            $isset_caj=cajas::where('nombre','=',$nombre)->where('estado',true)->first();
            if(@count($isset_caj)==0){
                $cajas=new cajas();

                $cajas->nombre=$nombre;
                $cajas->descripcion=$descripcion;
                $cajas->id_sucursal=$id_sucursal;
                $cajas->responsable=$responsable;
                $cajas->id_user=$id_user;
                $cajas->estado=true;
    
                $cajas->save();
                $data =array(
                    'status'=>'succes',
                    'code'=>200,
                    'mensage'=>'registrado'
                );    
            }else{
                $data =array(
                    'status'=>'error',
                    'code'=>300,
                    'mensage'=>'ya existe',
                );
            }
           
        }else{
            $data =array(
                'status'=>'error',
                'code'=>400,
                'mensage'=>'faltan datos'
            );
        }
        return response()->json($data,200);
        /*$categoria=Categoria::create($request->all());
        return $categoria;*/
    }
       
    public function modificar($id,Request $request){
        $json=$request->input('json',null);
        $params=json_decode($json);
        
        $nombre	=(!is_null($json) && isset($params->nombre)) ? $params->nombre : null;
        $descripcion=(!is_null($json) && isset($params->descripcion)) ? $params->descripcion : null;
        $id_sucursal=(!is_null($json) && isset($params->id_sucursal)) ? $params->id_sucursal : null;
        $responsable=(!is_null($json) && isset($params->responsable)) ? $params->responsable : null;
        $id_user=(!is_null($json) && isset($params->id_user)) ? $params->id_user : null;

        $isset_caj=cajas::whereNotIn('id',[$id])->where('nombre','=',$nombre)->where('estado',true)->first();
            if(@count($isset_caj)==0){
                $cajas= cajas::where('id',$id)->update(['nombre'=>$nombre,
                'descripcion'=>$descripcion,
                'id_sucursal'=>$id_sucursal,
                'responsable'=>$responsable,
                'id_user'=>$id_user]);

                $data =array(
                    'status'=>'succes',
                    'code'=>200,
                    'mensage'=>'registrado'
                );
            }else{
                $data =array(
                    'status'=>'error',
                    'code'=>300,
                    'mensage'=>'ya existe',
                );
            }
                
            
        return response()->json($data,200);

       }

       public function seleccionar($id){
        $cajas=cajas::find($id);
        return $cajas;
       }

    public function eliminar($id){
        $cambio=false;
        $cajas=cajas::where('id',$id)->update(['estado'=>$cambio]);
    	return $cajas;
       }

}
