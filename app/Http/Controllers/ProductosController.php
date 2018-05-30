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
        ->select('productos.id','nombre_producto','categorias.nombre','descripcion','productos.cantidad','unidad_de_medida')
        ->where('habilitado','habilitado')
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
        $unidadmedida=(!is_null($json) && isset($params->unidadmedida)) ? $params->unidadmedida : null;
        $cantidad=(!is_null($json) && isset($params->cantidad)) ? $params->cantidad : null;

        if(!is_null($nombre_producto)  && !is_null($cantidad) && !is_null($id_categoria)){
            $Productos=new Productos();
            $habilitado='habilitado';
           
            $Productos->nombre_producto=$nombre_producto;
            $Productos->id_categoria=$id_categoria;
            $Productos->descripcion=$descripcion;
            $Productos->unidad_de_medida=$unidadmedida;
            $Productos->cantidad=$cantidad;
            $Productos->habilitado=$habilitado;

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
       
    public function modificar($iden,Request $request){
        $json=$request->input('json',null);
        $params=json_decode($json);

        $id=(!is_null($json) && isset($params->id)) ? $params->id : null;
        $nombre_producto=(!is_null($json) && isset($params->nombre_producto)) ? $params->nombre_producto : null;
        $id_categoria=(!is_null($json) && isset($params->id_categoria)) ? $params->id_categoria : null;
        $descripcion=(!is_null($json) && isset($params->descripcion)) ? $params->descripcion : null;
        $unidad_de_medida=(!is_null($json) && isset($params->unidad_de_medida)) ? $params->unidad_de_medida : null;
        $cantidad=(!is_null($json) && isset($params->cantidad)) ? $params->cantidad : null;
           
              //guardar
                $Productos= Productos::where('id',$iden)->update(['nombre_producto'=>$nombre_producto,
                    'id_categoria'=>$id_categoria,
                    'descripcion'=>$descripcion,
                    'unidad_de_medida'=>$unidad_de_medida,
                    'cantidad'=>$cantidad]);

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
        $cambio='desabilitado';
        $Productos=Productos::where('id',$id)->update(['habilitado'=>$cambio]);
    	return $Productos;
       }

    public function buscar($name){
    
        $Productos=Productos::where('nombre','like',$name.'%')->first();
        return $Productos;
    }

    public function getProductos(){
        $productos=Productos::all();
        return $productos;
    }
}
