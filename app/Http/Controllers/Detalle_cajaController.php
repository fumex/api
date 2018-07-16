<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\detalle_caja;

class Detalle_cajaController extends Controller
{
    
    public function apertura(Request $request){
        $json=$request->input('json',null);
        $params=json_decode($json);
        $id_caja=(!is_null($json) && isset($params->id_caja)) ? $params->id_caja : null;
        $id_usuario=(!is_null($json) && isset($params->id_usuario)) ? $params->id_usuario : null;
        $monto_apertura=(!is_null($json) && isset($params->monto_apertura)) ? $params->monto_apertura : null;
            if(is_null($monto_apertura)){
                $monto_apertura=0;
            }
        $detalle_caja=new detalle_caja();
        $detalle_caja->id_caja=$id_caja;
        $detalle_caja->id_usuario=$id_usuario;
        $detalle_caja->monto_apertura=$monto_apertura;
        $detalle_caja->abierta=true;
        $detalle_caja->save();
        $data =array(
            'status'=>'succes',
            'code'=>200,
            'mensage'=>'abierto'
        );
        return response()->json($data,200);
    }
    public function cierre(Request $request){
        $json=$request->input('json',null);
        $params=json_decode($json);
        $id_caja=(!is_null($json) && isset($params->id_caja)) ? $params->id_caja : null;
        $id_usuario=(!is_null($json) && isset($params->id_usuario)) ? $params->id_usuario : null;
        $monto_cierre=(!is_null($json) && isset($params->monto_cierre)) ? $params->monto_cierre : null;
            if(is_null($monto_cierre)){
                $monto_cierre=0;
            }
        $idapetura=detalle_caja::where('id_caja',$id_caja)->where('id_usuario',$id_usuario)->get()->last();
            
            $modificar=detalle_caja::where('id',$idapetura['id'])->update([
            'monto_cierre'=> $monto_cierre,
            'abierta'=>false
            ]);

            $data =array(
                'status'=>'succes',
                'code'=>200,
                'mensage'=>'cerrado'
            );
        return response()->json($data,200);
    }
   

}
