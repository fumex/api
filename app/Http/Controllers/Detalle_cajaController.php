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

    public function montoapertura($id){
        //$idapetura=detalle_caja::where('id_caja',$id)->where('abierta',true)->get()->last();
        //$codigo=$idapetura['monto_apertura'];
        $arraytipo=array();
        $arraycantidad=array();
        $separador="";  
        $validador="";
        $cantidadfinal=0;
        $j=0;
        $arraymonto=str_split($id);
        for ($i = 0; $i < count($arraymonto); $i++) {
            if($arraymonto[$i]==='|'){
                $j++;
                if(($j % 2)==0){
                    array_push($arraycantidad,$separador);
                }else{
                    array_push($arraytipo,$separador);
                }
                
                $separador="";
            }else{
                $separador.=$arraymonto[$i];  
            }
           
        }
        for ($j = 0; $j < count($arraytipo); $j++){
            $validador=$arraytipo[$j];
            switch ($validador) {
                case "c10":
                $cantidadfinal+=$arraycantidad[$j]*0.1;
                    break;
                case "c20":
                $cantidadfinal+=$arraycantidad[$j]*0.2;
                    break;
                case "c50":
                $cantidadfinal+=$arraycantidad[$j]*0.5;
                    break;
                case "m01":
                $cantidadfinal+=$arraycantidad[$j]*1;
                    break;
                case "m02":
                $cantidadfinal+=$arraycantidad[$j]*2;
                    break;
                case "m05":
                $cantidadfinal+=$arraycantidad[$j]*5;
                    break;
                case "b10":
                $cantidadfinal+=$arraycantidad[$j]*10;
                    break;
                case "b20":
                $cantidadfinal+=$arraycantidad[$j]*20;
                    break;
                case "b50":
                $cantidadfinal+=$arraycantidad[$j]*50;
                    break;
                case "c01":
                $cantidadfinal+=$arraycantidad[$j]*100;
                    break;
                case "c02":
                $cantidadfinal+=$arraycantidad[$j]*200;
                    break;
            }
        }
        $data =array(
            'tipo'=>$arraytipo,
            'code'=>200,
            'monto'=>$arraycantidad,
            'total'=>$cantidadfinal 
        );
        return response()->json($data,200);

    }
    public function cierre(Request $request){
        $json=$request->input('json',null);
        $params=json_decode($json);
        $id_caja=(!is_null($json) && isset($params->id_caja)) ? $params->id_caja : null;
        $id_usuario=(!is_null($json) && isset($params->id_usuario)) ? $params->id_usuario : null;
        $monto_cierre=(!is_null($json) && isset($params->monto_cierre)) ? $params->monto_cierre : null;
        $monto_actual=(!is_null($json) && isset($params->monto_actual)) ? $params->monto_actual : null;
        
            if(is_null($monto_cierre)){
                $monto_cierre=0;
            }
        $idapetura=detalle_caja::where('id_caja',$id_caja)->where('id_usuario',$id_usuario)->get()->last();
            
            $modificar=detalle_caja::where('id',$idapetura['id'])->update([
            'monto_cierre'=> $monto_cierre,
            'monto_actual'=>$monto_actual,
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

}
