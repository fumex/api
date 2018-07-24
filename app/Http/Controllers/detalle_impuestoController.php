<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Productos;
use App\detalle_impuesto;

class detalle_impuestoController extends Controller
{
public function verigv($id){
    $listar=detalle_impuesto::join('impuestos','detalle_impuestos.id_impuesto','=','impuestos.id')
    ->where('id_producto',$id)
    ->where('detalle_impuestos.estado',true)
    ->where('impuestos.tipo','IGV')
    ->select('detalle_impuestos.id','detalle_impuestos.id_producto','detalle_impuestos.id_impuesto','detalle_impuestos.estado')
    ->get();
    return response()->json($listar);
}
public function verotro($id){
    $listar=detalle_impuesto::join('impuestos','detalle_impuestos.id_impuesto','=','impuestos.id')
    ->where('id_producto',$id)
    ->where('detalle_impuestos.estado',true)
    ->where('impuestos.tipo','OTRO')
    ->select('detalle_impuestos.id','detalle_impuestos.id_producto','detalle_impuestos.id_impuesto','detalle_impuestos.estado')
    ->get();
    return response()->json($listar);
}   
public function insertar(Request $request){
    $json=$request->input('json',null);
    $params=json_decode($json);

    $id_impuesto=(!is_null($json) && isset($params->id_impuesto)) ? $params->id_impuesto : null;
    $id_prod=Productos::get()->last();
        $d_detalle_impuesto=new detalle_impuesto();
        $d_detalle_impuesto->id_impuesto=$id_impuesto;
        $d_detalle_impuesto->id_producto=$id_prod['id'];
        $d_detalle_impuesto->estado=true;
        $d_detalle_impuesto->save();
        $data =array(
            'status'=>'succes',
            'code'=>200,
            'mensage'=>'sew incerto'
        );
    return response()->json($data,200);
}
       
public function modificarotro(Request $request){
    $json=$request->input('json',null);
    $params=json_decode($json);
        
    $id_impuesto=(!is_null($json) && isset($params->id_impuesto)) ? $params->id_impuesto : null;
    $id_producto=(!is_null($json) && isset($params->id_producto)) ? $params->id_producto : null;
    $estado=(!is_null($json) && isset($params->estado)) ? $params->estado : null;
    $indice=detalle_impuesto::where('id_producto','=',$id_producto)->where('id_impuesto','=',$id_impuesto)->first();
        if(@count($indice)==0){
            $detalle_impuestos=new detalle_impuesto();

            $detalle_impuestos->id_impuesto=$id_impuesto;
            $detalle_impuestos->id_producto=$id_producto;
            $detalle_impuestos->estado=$estado;
                    //guardar
            $detalle_impuestos->save();
            $data =array(
                'status'=>'succes',
                'code'=>200,
                'mensage'=>'guardado'
            );
        }else{
            $detalle_impuestos= detalle_impuesto::where('id_producto','=',$id_producto)
            ->where('id_impuesto','=',$id_impuesto)
            ->update(['estado'=>$estado]);
            $data =array(
                'status'=>'succes',
                'code'=>200,
                'mensage'=>'actualizado'
            );
        }
    return response()->json($data,200);
    }
public function modificarigv(Request $request){
    $json=$request->input('json',null);
    $params=json_decode($json);

    $id_impuesto=(!is_null($json) && isset($params->id_impuesto)) ? $params->id_impuesto : null;
    $id_producto=(!is_null($json) && isset($params->id_producto)) ? $params->id_producto : null;
    $estado=(!is_null($json) && isset($params->estado)) ? $params->estado : null;

    $deshabilitar=detalle_impuesto::join('impuestos','detalle_impuestos.id_impuesto','=','impuestos.id')
    ->where('id_producto','=',$id_producto)
    ->where('impuestos.tipo','IGV')
    ->update(['estado'=>'false']);

    
    $indice=detalle_impuesto::where('id_producto','=',$id_producto)->where('id_impuesto','=',$id_impuesto)->first();
        if(@count($indice)==0){
            $detalle_impuestos=new detalle_impuesto();

            $detalle_impuestos->id_impuesto=$id_impuesto;
            $detalle_impuestos->id_producto=$id_producto;
            $detalle_impuestos->estado=$estado;
                    //guardar
            $detalle_impuestos->save();
            $data =array(
                'status'=>'succes',
                'code'=>200,
                'mensage'=>'guardado'
            );
        }else{
            $detalle_impuestos= detalle_impuesto::where('id_producto','=',$id_producto)->where('id_impuesto','=',$id_impuesto)->update(['estado'=>$estado]);
            $data =array(
                'status'=>'succes',
                'code'=>200,
                'mensage'=>'actualizado'
            );
        }
    return response()->json($data,200);
    }
    public function verimpuestos($id){
        $listar=detalle_impuesto::join('impuestos','detalle_impuestos.id_impuesto','=','impuestos.id')
        ->where('id_producto',$id)
        ->where('detalle_impuestos.estado',true)
        ->whereNotIn('impuestos.tipo',['ISC'])
        ->select('impuestos.nombre','impuestos.tipo','impuestos.porcentaje')
        ->get();
        return response()->json($listar);
    } 
}
