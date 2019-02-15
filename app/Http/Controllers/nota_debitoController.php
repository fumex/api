<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\nota_debito;
use App\Venta;
use App\detalle_Ventas;
use App\Cliente;

class nota_debitoController extends Controller
{
    public function insertar(Request $request){
        $json=$request->input('json',null);
        $params=json_decode($json); 
        

        $tipo_nota=(!is_null($json) && isset($params->tipo_nota)) ? $params->tipo_nota : null;
        $serie_nota=(!is_null($json) && isset($params->serie_nota)) ? $params->serie_nota : null;
		$id_venta=(!is_null($json) && isset($params->id_venta)) ? $params->id_venta : null;
        $motivo=(!is_null($json) && isset($params->motivo)) ? $params->motivo : null;
        $email=(!is_null($json) && isset($params->email)) ? $params->email : null;
        $id_usuario=(!is_null($json) && isset($params->id_usuario)) ? $params->id_usuario : null;
        $letrado=(!is_null($json) && isset($params->letrado)) ? $params->letrado : null;

        $n_debito=new nota_debito();
        $n_debito->tipo_nota=$tipo_nota;
        $n_debito->serie_nota=$serie_nota;
        $n_debito->id_venta=$id_venta;
        $n_debito->motivo=$motivo;
        $n_debito->email=$email;
        $n_debito->id_usuario=$id_usuario;
        $n_debito->letrado=$letrado;

        if(!is_null($motivo)){
            $n_debito->save();
            $data =array(
                'status'=>'succes',
                'code'=>200,
                'mensage'=>'guardado'
            );
            return response()->json($data,200);
        }else{
            $data =array(
                'status'=>'error',
                'code'=>300,
                'mensage'=>$request
            );
            return response()->json($data,200);

        }
    }
    public function generarserienota($id){
        $nota_devito=nota_debito::where('serie_nota','like','%'.$id.'%')->get();
        $ultimanotadebito=nota_debito::where('serie_nota','like','%'.$id.'%')->get()->last();
        $data =array(
            'cantidad'=>@count($nota_credito),
            'ultimo'=>$ultimanotadebito['serie_nota']
        );
        return response()->json($data,200);
    }
}
