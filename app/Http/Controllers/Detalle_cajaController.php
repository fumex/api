<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\detalle_caja;
use App\Venta;

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
        $idapetura=detalle_caja::where('id_usuario',$id_usuario)->where('abierta',true)->get();
        $cajaabierta=detalle_caja::where('id_caja',$id_caja)->where('abierta',true)->get();
        if(@count($idapetura)>0){
            $data =array(
                'status'=>'error',
                'code'=>300,
                'mensage'=>'el  usuario ya tiene una caja abierta'
            );
        }else{
            if(@count($cajaabierta)>0){
                $data =array(
                    'status'=>'error',
                    'code'=>400,
                    'mensage'=>'la caja ya esta abierta'
                );
            }else{
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
            }
            
        }
        
      
        return response()->json($data,200);
    }

    public function cierre(Request $request){
        $json=$request->input('json',null);
        $params=json_decode($json);
        $id_caja=(!is_null($json) && isset($params->id_caja)) ? $params->id_caja : null;
        $id_usuario=(!is_null($json) && isset($params->id_usuario)) ? $params->id_usuario : null;
        $monto_cierre_efectivo=(!is_null($json) && isset($params->monto_cierre_efectivo)) ? $params->monto_cierre_efectivo : null;
        $monto_cierre_tarjeta=(!is_null($json) && isset($params->monto_cierre_tarjeta)) ? $params->monto_cierre_tarjeta : null;
        
            if(is_null($monto_cierre_efectivo)){
                $monto_cierre_efectivo=0;
            }
        $idapetura=detalle_caja::where('id_caja',$id_caja)->where('id_usuario',$id_usuario)->where('abierta',true)->get()->last();
            $modificar=detalle_caja::where('id',$idapetura['id'])->update([
            'monto_cierre_efectivo'=> $monto_cierre_efectivo,
            'monto_cierre_tarjeta'=>$monto_cierre_tarjeta,
            'abierta'=>false
            ]);

            $data =array(
                'status'=>'succes',
                'code'=>200,
                'mensage'=>'cerrado'
            );
        return response()->json($data,200);
    }
    public function inicio($id){
        $idapetura=detalle_caja::where('id_usuario',$id)->where('abierta',true)->get()->last();
        if(@count($idapetura)>0){
            $data =array(
                'status'=>'abierto',
                'code'=>300,
                'mensage'=>$idapetura['id_caja']
            );
        }else{
            $data =array(
                'status'=>'is ok',
                'code'=>200,
            );
        }
        return response()->json($data,200);
    }

    public function mostrarventas($id){
        $i=0;
        $ultventas=array();
        $consulta="";
        $idapetura=detalle_caja::where('id',$id)->where('abierta',true)->get()->last();
        
        $ventas=Venta::join('clientes','ventas.id_cliente','clientes.id')
        ->whereBetween('ventas.created_at',[$idapetura['created_at'],$idapetura['updated_at']])
        ->where('id_caja',$idapetura['id_caja'])
        ->select('ventas.id','ventas.serie_venta','ventas.id_caja','clientes.nombre','ventas.total','ventas.pago_efectivo','pago_tarjeta','ventas.created_at')
        ->get();
        /*while ( $i < 2){
            echo $ventas['id']-$i;                                                                                      
            $consulta=Venta::where('id',$ventas['id']-$i)->get();
            array_push($ultventas,$consulta);
            $i++;
        }*/
        return $ventas;
    }

    public function mostrarventasporsucursal($id){
        $i=0;
        $ultventas=array();
        $consulta="";
        $ventas=Venta::join('cajas','ventas.id_caja','cajas.id')
        ->join('sucursals','cajas.id_sucursal','sucursals.id')
        ->join('clientes','ventas.id_cliente','clientes.id')
        ->where('sucursals.id',$id)
        ->select('ventas.id','ventas.serie_venta','ventas.id_caja','clientes.nombre','ventas.total','ventas.pago_efectivo','pago_tarjeta','ventas.created_at')
        ->orderBy('ventas.created_at','DESC')
        ->get();
        /*while ( $i < 2){
            echo $ventas['id']-$i;                                                                                      
            $consulta=Venta::where('id',$ventas['id']-$i)->get();
            array_push($ultventas,$consulta);
            $i++;
        }*/
        return $ventas;
    }
    public function getdetallecajas($id){
        return $consulta=detalle_caja::join('cajas','detalle_cajas.id_caja','cajas.id')
        ->where('detalle_cajas.id',$id)
        ->select('detalle_cajas.id','detalle_cajas.id_caja','detalle_cajas.abierta','detalle_cajas.created_at','detalle_cajas.id_usuario','detalle_cajas.monto_actual','detalle_cajas.monto_apertura','detalle_cajas.monto_cierre_efectivo','detalle_cajas.monto_cierre_tarjeta','detalle_cajas.updated_at','cajas.nombre')
        ->get();
    }
}
