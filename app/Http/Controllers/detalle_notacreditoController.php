<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\detalle_nota_credito;

class detalle_notacreditoController extends Controller
{
    public function insertar(Request $request){
        $json=$request->input('json',null);
        $params=json_decode($json);
        

        $id_nota_credito=(!is_null($json) && isset($params->id_nota_credito)) ? $params->id_nota_credito : null;
		$id_detalle_venta=(!is_null($json) && isset($params->id_detalle_venta)) ? $params->id_detalle_venta : null;
        $cantidad=(!is_null($json) && isset($params->cantidad)) ? $params->cantidad : null;

        $d_n_credito=new detalle_nota_credito();

        $d_n_credito->id_nota_credito=$id_nota_credito;
        $d_n_credito->id_venta=$id_venta;
        $d_n_credito->motivo=$motivo;

        $d_n_credito->save();
        
        $data =array(
            'status'=>'succes',
            'code'=>200,
            'mensage'=>'guardado'
        );

    }
}
