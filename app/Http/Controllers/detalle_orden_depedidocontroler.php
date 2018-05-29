<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\orden_depedido;
use App\detalle_orden_depedido;
use App\Productos;

class detalle_orden_depedidocontroler extends Controller
{
    public function ver(){
        $listar2=detalle_orden_depedido::join('productos','detalle_orden_depedidos.id_producto','=','productos.id')
        ->select('detalle_orden_depedidos.id','detalle_orden_depedidos.id_orden_pedido','productos.nombre_producto','detalle_orden_depedidos.cantidad')
        ->get();
        return $listar2;

       }

    public function insertar(Request $request){
        $json=$request->input('json',null);
        $params=json_decode($json);

        $id_producto=(!is_null($json) && isset($params->id_producto)) ? $params->id_producto : null;
        $cantidad=(!is_null($json) && isset($params->cantidad)) ? $params->cantidad : null;
        
        if(!is_null($id_producto) && !is_null($cantidad)){
            $detalle_orden=new detalle_orden_depedido();
            $producto_id=Productos::where('nombre_producto',$id_producto)->value('id');
            $idorden_pedido=orden_depedido::get()->last();
            $detalle_orden->id_orden_pedido= $idorden_pedido['id'];
            $detalle_orden->id_producto=$producto_id;
            $detalle_orden->cantidad=$cantidad;

            $detalle_orden->save();
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
        
        $id_producto=(!is_null($json) && isset($params->id_producto)) ? $params->id_producto : null;
        $cantidad=(!is_null($json) && isset($params->cantidad)) ? $params->cantidad : null;
        
              //guardar
                $orden_depedido= orden_depedido::where('id',$id)->update(['id_producto'=> $id_producto,
                'cantidad'=>$cantidad]);

                $data =array(
                    'status'=>'succes',
                    'code'=>200,
                    'mensage'=>'registrado'
                );
            
        return response()->json($data,200);

       }

    public function seleccionar($id){   
        $orden_depedido=orden_depedido::find($id);
        return $orden_depedido;
    }

    public function eliminar($id){
        
    }
}
