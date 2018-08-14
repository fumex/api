<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\orden_depedido;
use App\detalle_orden_depedido;

class orden_depedidocontroler extends Controller
{
    public function ver(){
        $listar2=orden_depedido::join('almacenes','orden_depedidos.id_almacen','=','almacenes.id')
        ->join('proveedors','orden_depedidos.id_proveedor','=','proveedors.id')
        ->where('orden_depedidos.estado','=',true)
        ->select('orden_depedidos.id','proveedors.nombre_proveedor','almacenes.nombre','orden_depedidos.created_at','orden_depedidos.fecha_estimada_entrega','orden_depedidos.terminos')
        ->orderBy('id')
        ->get();
        return $listar2;

    }

    public function insertar(Request $request){
       $create=orden_depedido::create($request->all());
        return response()->json($create);
    }
       
    public function modificar($id,Request $request){
        $json=$request->input('json',null);
        $params=json_decode($json);
        
        $id_almacen	=(!is_null($json) && isset($params->id_almacen)) ? $params->id_almacen : null;
        $fecha_estimada_entrega=(!is_null($json) && isset($params->fecha_estimada_entrega)) ? $params->fecha_estimada_entrega : null;
        $terminos=(!is_null($json) && isset($params->terminos)) ? $params->terminos : null;
        
              //guardar
                $orden_depedido= orden_depedido::where('id',$id)->update(['id_almacen'=> $id_almacen,
                'fecha_estimada_entrega'=>$fecha_estimada_entrega,
                'terminos'=>$terminos
                ]);

                $data =array(
                    'status'=>'succes',
                    'code'=>200,
                    'mensage'=>'registrado'
                );
            
        return response()->json($data,200);

    }

    public function seleccionar($id){   
        $orden_depedido=orden_depedido::join('proveedors','orden_depedidos.id_proveedor','=','proveedors.id')
        ->select('orden_depedidos.id','proveedors.nombre_proveedor','orden_depedidos.id_almacen','orden_depedidos.created_at','orden_depedidos.fecha_estimada_entrega','orden_depedidos.terminos')
        ->find($id);       
        return $orden_depedido;
    }

    public function eliminar($id){
        return $orden_depedido= orden_depedido::where('id',$id)->update(['estado'=> false]);
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

    public function code(){
        $max = orden_depedido::count();
        if ($max > 0) {
            $row = explode('-',orden_depedido::max('code'), 2);
            $cod = $row[1];
            $sig = $cod+1;
            $Strsig = (string)$sig;
            $formato = "O-".str_pad($Strsig, "5", "0", STR_PAD_LEFT);
            
        } 
        else {
            $sig = 1;
            $Strsig = (string)$sig;
            $formato = "O-".str_pad($Strsig,"5","0",STR_PAD_LEFT);
        }
        
        return response()->json($formato);
    }
    public function getOrdenPedido($id){
        $pedido=orden_depedido::find($id);
        return response()->json($pedido);
    }   
}
