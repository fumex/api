<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Almacenes;

use App\User;

class AlmacenesController extends Controller
{
    public function ver(){
        return $listar=Almacenes::where('estado',true)->get();
    }
    public function veralmacen($id){

        return $listar=Almacenes::whereNotIn('id',[$id])->where('estado',true)->get();
        
    }
    public function almacenusuario($id){
        $user=User::where('id',$id)->get()->last();
        $alma1=null;
        if($user['superad']==true){
            return $alma1=Almacenes::where('almacenes.estado',true)
            ->select('almacenes.id','almacenes.nombre','almacenes.descripcion','almacenes.direccion','almacenes.telefono','almacenes.id_user')
            ->distinct('almacenes.nombre')
            ->get();
        }else{
            $almacen=Almacenes::join('sucursals','almacenes.id','=','sucursals.id_almacen')
            ->join('detalle_usuarios','sucursals.id','=',  'detalle_usuarios.id_sucursal')
            ->where('detalle_usuarios.id_user','=',$id)
            ->where('detalle_usuarios.permiso',true)
            ->where('permiso','=',true)
            ->where('almacenes.estado',true)
            ->select('almacenes.id','almacenes.nombre','almacenes.descripcion','almacenes.direccion','almacenes.telefono','almacenes.id_user')
            ->distinct('almacenes.nombre')
            ->get();

            return $almacen;
        }
        
    }   
    public function insertar(Request $request){
        $json=$request->input('json',null);
        $params=json_decode($json);
        
        $nombre	=(!is_null($json) && isset($params->nombre)) ? $params->nombre : null;
        $descripcion=(!is_null($json) && isset($params->descripcion)) ? $params->descripcion : null;
        $direccion=(!is_null($json) && isset($params->direccion)) ? $params->direccion : null;
        $telefono=(!is_null($json) && isset($params->telefono)) ? $params->telefono : null;
        $id_user=(!is_null($json) && isset($params->id_user)) ? $params->id_user : null;

        if(!is_null($nombre)){
            $isset_alm=Almacenes::where('nombre','=',$nombre)->where('estado',true)->first();
            if(@count($isset_alm)==0){
                $Almacenes=new Almacenes();

                $Almacenes->nombre=$nombre;
                $Almacenes->descripcion=$descripcion;
                $Almacenes->direccion=$direccion;
                $Almacenes->telefono=$telefono;
                $Almacenes->id_user=$id_user;
                $Almacenes->estado=true;
    
                $Almacenes->save();
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
        $descripcion	=(!is_null($json) && isset($params->descripcion)) ? $params->descripcion : null;
        $direccion	=(!is_null($json) && isset($params->direccion)) ? $params->direccion : null;
        $telefono	=(!is_null($json) && isset($params->telefono)) ? $params->telefono : null;
        $id_user=(!is_null($json) && isset($params->id_user)) ? $params->id_user : null;
        $isset_alm=Almacenes::whereNotIn('id',[$id])->where('nombre','=',$nombre)->where('estado',true)->first();
            if(@count($isset_alm)==0){
                $Almacenes= Almacenes::where('id',$id)->update(['nombre'=>$nombre,
                'descripcion'=>$descripcion,
                'direccion'=>$direccion,
                'telefono'=>$telefono,
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
       
        $Almacenes=Almacenes::find($id);
        return $Almacenes;
       }

    public function eliminar($id){
        $cambio=false;
        $Almacenes=Almacenes::where('id',$id)->update(['estado'=>$cambio]);
    	return $Almacenes;
       }


}
