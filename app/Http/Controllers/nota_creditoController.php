<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\nota_credito;
use App\Venta;

class nota_creditoController extends Controller
{
    public function insertar(Request $request){
        $json=$request->input('json',null);
        $params=json_decode($json);
        

        $tipo_nota=(!is_null($json) && isset($params->tipo_nota)) ? $params->tipo_nota : null;
		$id_venta=(!is_null($json) && isset($params->id_venta)) ? $params->id_venta : null;
        $motivo=(!is_null($json) && isset($params->motivo)) ? $params->motivo : null;
        $correccion_ruc=(!is_null($json) && isset($params->correccion_ruc)) ? $params->correccion_ruc : null;
        $id_usuario=(!is_null($json) && isset($params->id_usuario)) ? $params->id_usuario : null;
        $serie_nota=(!is_null($json) && isset($params->serie_nota)) ? $params->serie_nota : null;

        $n_credito=new nota_credito();

        $n_credito->tipo_nota=$tipo_nota;
        $n_credito->id_venta=$id_venta;
        $n_credito->motivo=$motivo;
        $n_credito->correccion_ruc=$correccion_ruc;
        $n_credito->id_usuario=$id_usuario;
        $n_credito->serie_nota=$serie_nota;

        $n_credito->save();
        
        $data =array(
            'status'=>'succes',
            'code'=>200,
            'mensage'=>'guardado'
        );
        return response()->json($data,200);

    }
    public function generarserienota($id){
        $nota_credito=nota_credito::where('serie_nota','like','%'.$id.'%')->get();
        $ulñtimanota_credito=nota_credito::where('serie_nota','like','%'.$id.'%')->get()->last();
        $data =array(
            'cantidad'=>@count($nota_credito),
            'ultimo'=>$ulñtimanota_credito['serie_nota']
        );
        return response()->json($data,200);
    }
}
