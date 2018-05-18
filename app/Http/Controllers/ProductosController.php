<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Productos;
use App\Categoria;
use DB;

class ProductosController extends Controller
{
    public function ver(){
        $listar2=productos::join('categorias','productos.id_categoria','=','categorias.id')
        ->select('productos.id','nombre_producto','categorias.nombre','descripcion','precio','unidad_de_medida')
        ->get();
        //return $listar=Productos::all();
        return $listar2;
       }

    public function insertar(Request $request){
        $json=$request->input('json',null);
        $params=json_decode($json);
        
        $nombre_producto=(!is_null($json) && isset($params->nombre_producto)) ? $params->nombre_producto : null;
        $id_categoria=(!is_null($json) && isset($params->id_categoria)) ? $params->id_categoria : null;
        $descripcion=(!is_null($json) && isset($params->descripcion)) ? $params->descripcion : null;
        $unidad=(!is_null($json) && isset($params->unidad)) ? $params->unidad : null;
        $precio=(!is_null($json) && isset($params->precio)) ? $params->precio : null;

        if(!is_null($nombre_producto)  && !is_null($precio) && !is_null($id_categoria)){
            $Productos=new Productos();
           
           
            $Productos->nombre_producto=$nombre_producto;
            $Productos->id_categoria=$id_categoria;
            $Productos->descripcion=$descripcion;
            $Productos->unidad_de_medida=$unidad;
            $Productos->precio=$precio;

            $isset_producto=Productos::where('nombre_producto','=',$nombre_producto)->first();
            if(@count($isset_producto)==0){
                

                //guardar
                $Productos->save();

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
        //$Productos=Productos::create($request->json()->all());
        //return $Productos;
    }
       
    public function modificar($id,Request $request){
        $json=$request->input('json',null);
        $params=json_decode($json,true);
        
        $nombre_producto=(!is_null($json) && isset($params->nombre_producto)) ? $params->nombre_producto : null;
        $id_categoria=(!is_null($json) && isset($params->id_categoria)) ? $params->id_categoria : null;
        $descripcion=(!is_null($json) && isset($params->descripcion)) ? $params->descripcion : null;
        $unidad=(!is_null($json) && isset($params->unidad)) ? $params->unidad : null;
        $precio=(!is_null($json) && isset($params->precio)) ? $params->precio : null;
           
              //guardar
                $Productos= Productos::where('id',$id)->update($params);

                $data =array(
                    'status'=>'succes',
                    'code'=>200,
                    'mensage'=>'registrado'
                );
            
        return response()->json($data,200);
        /*$Productos=Productos::find($id);
    	$Productos->fill($request->all())->save();
    	return $Productos;*/
       }

       public function seleccionar($id){
       
        $Productos=Productos::find($id);
        return $Productos;
       }

    public function eliminar($id){
        $Productos=Productos::find($id);
    	$Productos->delete();
    	return $Productos;
       }

    public function buscar($name){
    
        $Productos=Productos::where('nombre','like',$name.'%')->first();
        return $Productos;
    }
}