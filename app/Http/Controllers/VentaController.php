<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Venta;
use App\productos;

class VentaController extends Controller
{
    public function getdocumento(){
        $i=5;
        $j=0;
        $k=1;
        $resb="";
        $resf="";
        $boleta=Venta::where('serie_venta', 'like', '%' . 'B' . '%')->get()->last();
        $factura=Venta::where('serie_venta', 'like', '%' . 'F' . '%')->get()->last();
       
        
        $nombreboleta=$boleta['serie_venta'];
        $nombrefactura=$factura['serie_venta'];
        $boletarr=str_split($nombreboleta);
        $facturarr=str_split($nombrefactura);
        $boletanueva="";
        $facturanueva="";
        $serie="";
        if(@count($boleta) < 1){
            $resb="B001-000001";
        }else{
            
            while ($i <= count($boletarr)) {
                $boletanueva=$boletanueva.$boletarr[$i];
                $i++; 
            }
            while($k<=3){
                $serie.=$boletarr[$i];
                $k++;
            }

            if($boletanueva=="999999"){
                $resb=intval($serie)+1;
            }else{
                $resb=$serie;
            }
            $resb.="-";
            $total=intval($boletanueva)+1;
            while($j < (strlen($boletanueva)-strlen($total))){
                $resb.="0";
                $j++;
            }
            $resb=$resb.($total);
        }
        $i=5;
        $j=0;
        $k=1;
        $serie="";
        if(@count($factura) < 1){
            $resf="F001-000001";
        }else{
            while ($i < count($facturarr)) {
                $facturanueva=$facturanueva.$facturarr[$i];
                $i++; 
            }
            while($k<=3){
                $serie.=$facturarr[$k];
                $resf= $resf.$facturarr[$k-1];
                $k++;
            }

            if($facturanueva=="999999"){
                $resf="F00".(intval($serie)+1);
                $resF=$resf."-000001";
            }else{
                $resf=$resf.$facturarr[3]."-";
                $total=intval($facturanueva)+1;
                while($j < (strlen($facturanueva)-strlen($total))){
                    $resf=$resf."0";
                    $j++;
                }
                $resf=$resf.($total);
            }
        }
        $data =array(
            'boleta'=>@count($boleta),
            'factura'=>@count($factura),
            'b'=> $resb,
            'f'=> $resf,
            'x'=>strlen($boletanueva)-strlen($total)
        );
        return response()->json($data,200);
        /*$i=0;
        $productos=productos::where('nombre_producto', 'like', '%' . '0' . '%')->get()->last();
        $np=$productos['nombre_producto'];
        $ap=str_split($np);
        $palabra="";
        while($i < count($ap)){
            $palabra=$palabra.$ap[$i];
            $i++;
        }
        $total=126;
        return strlen($total);*/
        
	}
    public function insertar(Request $request){
        $json=$request->input('json',null);
        $params=json_decode($json);
        
        $serie_venta=(!is_null($json) && isset($params->serie_venta)) ? $params->serie_venta : null;
		$tarjeta=(!is_null($json) && isset($params->tarjeta)) ? $params->tarjeta : null;
		$id_caja=(!is_null($json) && isset($params->id_caja)) ? $params->id_caja : null;
        $id_cliente=(!is_null($json) && isset($params->id_cliente)) ? $params->id_cliente : null;
        $total=(!is_null($json) && isset($params->total)) ? $params->total : null;
		$pago_efectivo=(!is_null($json) && isset($params->pago_efectivo)) ? $params->pago_efectivo : null;
        $pago_tarjeta=(!is_null($json) && isset($params->pago_tarjeta)) ? $params->pago_tarjeta : null;

        $Venta=new Venta();
            
        $Venta->serie_venta=$serie_venta;
        $Venta->tarjeta=$tarjeta;
        $Venta->id_caja=$id_caja;
        $Venta->id_cliente=$id_cliente;
        $Venta->total=$total;
        $Venta->pago_efectivo=$pago_efectivo;
        $Venta->pago_tarjeta=$pago_tarjeta;
        $Venta->estado=true;
        $Venta->save();

        
        $data =array(
            'status'=>'succes',
            'code'=>200,
            'mensage'=>'registrado',
        );

        return response()->json($data,200);
    }
}
