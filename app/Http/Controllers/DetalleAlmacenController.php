<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;            
use App\detalle_almacen;
use App\Detalle_usuario;

class DetalleAlmacenController extends Controller
{
    public function ver($id){
        $id_almacen=Detalle_usuario::where('id_almacen',$id)->get();
        
        return $id_almacen;

       }

    public function insertar(Request $request){
        $json=$request->input('json',null);
        $params=json_decode($json);

        $id_almacen	=(!is_null($json) && isset($params->id_almacen)) ? $params->id_almacen : null;
        $codigo=(!is_null($json) && isset($params->codigo)) ? $params->codigo : null;
        $id_producto=(!is_null($json) && isset($params->id_producto)) ? $params->id_producto : null;
        $stock=(!is_null($json) && isset($params->stock)) ? $params->stock : null;
        $precio_compra=(!is_null($json) && isset($params->precio_compra)) ? $params->precio_compra : null;
        $precio_venta=(!is_null($json) && isset($params->precio_venta)) ? $params->precio_venta : null;

        if(!is_null($id_almacen) && !is_null($id_producto)){
            $detalle_almacen=new detalle_almacen();
            $detalle_almacen->id_almacen=$id_almacen;
            $detalle_almacen->codigo=$codigo;
            $detalle_almacen->id_producto=$id_producto;
            $detalle_almacen->stock=$stock;
            $detalle_almacen->precio_compra=$precio_compra;
            $detalle_almacen->precio_venta=$precio_venta;

            $detalle_almacen->save();
            $data =array(
                'status'=>'succes',
                'code'=>200,
                'mensage'=>'registrado'
            );
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
        
        $precio_venta=(!is_null($json) && isset($params->precio_venta)) ? $params->precio_venta : null;

              //guardar
                $detalle_almacen= detalle_almacen::where('id',$id)->update(['precio_venta'=> $precio_venta]);

                $data =array(
                    'status'=>'succes',
                    'code'=>200,
                    'mensage'=>'registrado'
                );
            
        return response()->json($data,200);

       }

    public function seleccionar($id){   
        $detalle_almacen=detalle_almacen::find($id);
        return $detalle_almacen;
    }

    public function eliminar($id){
        
       }

}
