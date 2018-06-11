<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\sucursal;

class SucursalController extends Controller
{
    public function ver(){
        return $listar=sucursal::join('almacenes','sucursals.id_almacen','=','almacenes.id')
        ->where('sucursals.habilitado','habilitado')
        ->select('sucursals.id','almacenes.nombre','sucursals.nombre_sucursal','sucursals.direccion','sucursals.descripcion','sucursals.telefono')
        ->get();
    }

    public function insertar(Request $request){
        $json=$request->input('json',null);
        $params=json_decode($json);
        
        $nombre	=(!is_null($json) && isset($params->nombre)) ? $params->nombre : null;
        $descripcion=(!is_null($json) && isset($params->descripcion)) ? $params->descripcion : null;
        $direccion=(!is_null($json) && isset($params->direccion)) ? $params->direccion : null;
        $telefono=(!is_null($json) && isset($params->telefono)) ? $params->telefono : null;
        $id_almacen=(!is_null($json) && isset($params->id_almacen)) ? $params->id_almacen : null;
        $id_user=(!is_null($json) && isset($params->id_user)) ? $params->id_user : null;

        if(!is_null($nombre)){
            $habilitado='habilitado';
            $sucursal=new sucursal();

            $sucursal->nombre=$nombre;
            $sucursal->descripcion=$descripcion;
            $sucursal->direccion=$direccion;
            $sucursal->telefono=$telefono;
            $sucursal->id_user=$id_user;
            $sucursal->id_almacen=$id_almacen;
            $sucursal->habilitado=$habilitado;
            

            $isset_cate=sucursal::where('nombre','=',$nombre)->first();
            if(@count($isset_cate)==0){
                //guardar
                $sucursal->save();

                $data =array(
                    'status'=>'succes',
                    'code'=>200,
                    'mensage'=>'registrado'
                );
            }else{
                //no guiardar
                $data =array(
                    'status'=>'error',
                    'code'=>300,
                    'mensage'=>'ya existe'
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
        $direccion=(!is_null($json) && isset($params->direccion)) ? $params->direccion : null;
        $telefono=(!is_null($json) && isset($params->telefono)) ? $params->telefono : null;
        $id_almacen=(!is_null($json) && isset($params->id_almacen)) ? $params->id_almacen : null;
        $id_user=(!is_null($json) && isset($params->id_user)) ? $params->id_user : null;
              //guardar
                $sucursal= sucursal::where('id',$id)->update(['nombre'=>$nombre,
                'descripcion'=>$descripcion,
                'direccion'=>$direccion,
                'telefono'=>$telefono,
                'id_almacen'=>$id_almacen,
                'id_user'=>$id_user]);

                $data =array(
                    'status'=>'succes',
                    'code'=>200,
                    'mensage'=>'registrado'
                );
            
        return response()->json($data,200);

       }

       public function seleccionar($id){
       
        $sucursal=sucursal::find($id);
        return $sucursal;
       }

    public function eliminar($id){
        $cambio='desabilitado';
        $sucursal=sucursal::where('id',$id)->update(['habilitado'=>$cambio]);
    	return $sucursal;
       }
}
