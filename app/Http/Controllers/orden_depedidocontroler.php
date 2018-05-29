<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\orden_depedido;
use App\detalle_orden_depedido;

class orden_depedidocontroler extends Controller
{
    public function ver(){
        $listar2=orden_depedido::join('almacenes','orden_depedidos.id_almacen','=','almacenes.id')
        ->select('orden_depedidos.id','almacenes.nombre','orden_depedidos.fecha','orden_depedidos.fecha_estimada_entrega')
        ->get();
        return $listar2;

       }

    public function insertar(Request $request){
        $json=$request->input('json',null);
        $params=json_decode($json);

        $id_proveedor=(!is_null($json) && isset($params->id_proveedor)) ? $params->id_proveedor : null;
        $id_almacen	=(!is_null($json) && isset($params->id_almacen)) ? $params->id_almacen : null;
        $fecha_estimada_entrega=(!is_null($json) && isset($params->fecha_estimada_entrega)) ? $params->fecha_estimada_entrega : null;

        if(!is_null($id_almacen)){
            $orden_depedido=new orden_depedido();

            $orden_depedido->id_almacen=$id_almacen;
            $orden_depedido->id_proveedor=$id_proveedor;
            $orden_depedido->fecha_estimada_entrega=$fecha_estimada_entrega;

            $orden_depedido->save();
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
        
        $id_almacen	=(!is_null($json) && isset($params->id_almacen)) ? $params->id_almacen : null;
        $fecha_estimada_entrega=(!is_null($json) && isset($params->fecha_estimada_entrega)) ? $params->fecha_estimada_entrega : null;
        
              //guardar
                $orden_depedido= orden_depedido::where('id',$id)->update(['id_almacen'=> $id_almacen,
                'fecha_estimada_entrega'=>$fecha_estimada_entrega
                ]);

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
    public function fecha(){
        $now = new \DateTime();
        $data =array(
            'status'=>'succes',
            'code'=>200,
            'mensage'=>'registrado',
            'res'=>$now->format('Y-m-d')
        );
        return response()->json($data,200);
    }
}
