<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Almacenes;

class AlmacenesController extends Controller
{
    public function ver(){
        return $listar=Almacenes::all();
       }

    public function veralmacen($id){

        return $listar=Almacenes::whereNotIn('id',[$id])->get();
        
    }
    public function insertar(Request $request){
        $json=$request->input('json',null);
        $params=json_decode($json);
        
        $nombre	=(!is_null($json) && isset($params->nombre)) ? $params->nombre : null;
        $descripcion=(!is_null($json) && isset($params->descripcion)) ? $params->descripcion : null;
        $direccion=(!is_null($json) && isset($params->direccion)) ? $params->direccion : null;
        $telefono=(!is_null($json) && isset($params->telefono)) ? $params->telefono : null;


        if(!is_null($nombre)){
            $Almacenes=new Almacenes();
            $Almacenes->nombre=$nombre;
            $Almacenes->descripcion=$descripcion;
            $Almacenes->direccion=$direccion;
            $Almacenes->telefono=$telefono;
            

            $isset_cate=Almacenes::where('nombre','=',$nombre)->first();
            if(@count($isset_cate)==0){
                //guardar
                $Almacenes->save();

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
        $params=json_decode($json,true);
        
        $nombre	=(!is_null($json) && isset($params->nombre)) ? $params->nombre : null;
        $descripcion	=(!is_null($json) && isset($params->descripcion)) ? $params->descripcion : null;
        $direccion	=(!is_null($json) && isset($params->direccion)) ? $params->direccion : null;
        $telefono	=(!is_null($json) && isset($params->telefono)) ? $params->telefono : null;

              //guardar
                $Almacenes= Almacenes::where('id',$id)->update($params);

                $data =array(
                    'status'=>'succes',
                    'code'=>200,
                    'mensage'=>'registrado'
                );
            
        return response()->json($data,200);

       }

       public function seleccionar($id){
       
        $Almacenes=Almacenes::find($id);
        return $Almacenes;
       }

    public function eliminar($id){
        $Almacenes=Almacenes::find($id);
    	$Almacenes->delete();
    	return $Almacenes;
       }


}
