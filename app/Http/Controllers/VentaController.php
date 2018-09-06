<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Venta;
use App\productos;
use App\detalle_caja; 
use App\detalle_almacen; 
use App\detalle_Ventas; 
use App\movimientos_detalle_almacen; 
class VentaController extends Controller
{
    public function getdocumento(){
        $i=5;
        $j=0;
        $k=1;
        $resb="";
        $resf="";
        $total=0;
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
            while ($i < count($boletarr)) {
                $boletanueva=$boletanueva.$boletarr[$i];
                $i++; 
            }
            while($k<=3){
                $serie.=$boletarr[$k];
                $resb=$boletarr[0];
                $k++;
            }
            $k=0;
            while($k<3-strlen(intval($serie)+1)){
                $resb.="0";
                $k++;
            }

            if($boletanueva=="999999"){
                $resb.=intval($serie)+1;
                $resb.="-000001";
            }else{
                $resb.=(intval($serie))."-";
                $total=intval($boletanueva)+1;
                while($j < (strlen($boletanueva)-strlen($total))){
                    $resb.="0";
                    $j++;
                }
                $resb=$resb.($total);
            }
            
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
                $resf= $facturarr[0];
                $k++;
            }
            $k=0;
            while($k<3-strlen(intval($serie)+1)){
                $resf.="0";
                $k++;
            }
            if($facturanueva=="999999"){
                $resf.=(intval($serie)+1);
                $resf.="-000001";
            }else{
                $resf.=(intval($serie))."-";
                $total=intval($facturanueva)+1;
                while($j < (strlen($facturanueva)-strlen($total))){
                    $resf.="0";
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
            'x'=>$serie,
            'code'=>200,
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
        $id_usuario=(!is_null($json) && isset($params->id_usuario)) ? $params->id_usuario : null;
        $id_vendedor=(!is_null($json) && isset($params->id_vendedor)) ? $params->id_vendedor : null;

        $Venta=new Venta();
            
        $Venta->serie_venta=$serie_venta;
        $Venta->tarjeta=$tarjeta;
        $Venta->id_caja=$id_caja;
        $Venta->id_cliente=$id_cliente;
        $Venta->total=$total;
        $Venta->pago_efectivo=$pago_efectivo;
        $Venta->pago_tarjeta=$pago_tarjeta;
        $Venta->estado=true;
        $Venta->id_usuario=$id_usuario;

        $Venta->save();

        $detalle_caja=detalle_caja::where('id_caja',$id_caja)->where('abierta',true)->get()->last();
        $total=$detalle_caja['monto_actual']+$total;
        $actualizar_caja=detalle_caja::where('id',$detalle_caja['id'])->update(['monto_actual'=>$total]);

        $data =array(
            'status'=>'succes',
            'code'=>200,
            'mensage'=>'registrado',
        );

        return response()->json($data,200);
    }
    public function anular($id){
        $i=0;
        $total=0;
        $costoactualizado=0;
        $costoguardado=0;
        $getventa=Venta::where('id',$id)->get()->last();
      
        $detalle_caja=detalle_caja::where('id_caja',$getventa['id_caja'])->where('created_at','<',$getventa['created_at'])->where('abierta',true)->get()->last();
        if(@count($detalle_caja) > 0){
            $getventa->estado=false;
            $getventa->save();
            $total=$detalle_caja['monto_actual']-$getventa['total'];
            $actualizar_caja=detalle_caja::where('id',$detalle_caja['id'])->update(['monto_actual'=>$total]);
            
            $detalle_Ventas=detalle_Ventas::where('id_venta',$id)->get();

            while($i<@count($detalle_Ventas)){
                 $detalle_Ventas[$i];
                
                
    
                $detalle_almacen=Venta::join('cajas','ventas.id_caja','cajas.id')
                ->join('sucursals','cajas.id_sucursal','sucursals.id')
                ->join('almacenes','sucursals.id_almacen','almacenes.id')
                ->join('detalle_almacen','almacenes.id','detalle_almacen.id_almacen')
                ->where('cajas.id',$getventa['id_caja'])
                ->where('detalle_almacen.id_producto',$detalle_Ventas[$i]->id_producto)
                ->select('detalle_almacen.id','detalle_almacen.stock','detalle_almacen.precio_compra','detalle_almacen.id_producto','detalle_almacen.precio_venta')->get()->last();
               
                $m_d_a_ultimo=movimientos_detalle_almacen::where('id_detalle_almacen',$detalle_almacen['id'])->where('created_at','<',$getventa['created_at'])->get()->last();
                $pre_comp=$m_d_a_ultimo['precio_compra_actual'];
                $costoactualizado=(($detalle_almacen['stock'] *  $detalle_almacen['precio_compra'])+($detalle_Ventas[$i]->cantidad*$pre_comp))/($detalle_almacen['stock']-$detalle_Ventas[$i]->cantidad);
                $costoguardado=round($costoactualizado,2);
                $actualizaralmacen=detalle_almacen::where('id',$detalle_almacen['id'])  
                ->update(['stock'=>$detalle_almacen['stock']+$detalle_Ventas[$i]->cantidad,'precio_compra'=>$costoguardado]);
                if($costoguardado!=$detalle_almacen['precio_compra']){
                    $m_d_almacen=new movimientos_detalle_almacen();
                    $m_d_almacen->id_detalle_almacen=$detalle_almacen['id'];
                    $m_d_almacen->id_detalle_almacen=$detalle_almacen['id'];
                    $m_d_almacen->descuento_anterior=$m_d_a_ultimo['descuento_anterior'];
                    $m_d_almacen->descuento_actual=$m_d_a_ultimo['descuento_actual'];
                    $m_d_almacen->precio_anterior=$m_d_a_ultimo['precio_anterior'];
                    $m_d_almacen->precio_actual=$m_d_a_ultimo['precio_actual'];
                    $m_d_almacen->precio_compra_actual=$costoguardado;
                    $m_d_almacen->precio_compra_anterior=$detalle_almacen['precio_compra'];
                    $m_d_almacen->save();
                }
    
                $i++;
            }
        }else{
            
        }
      
        /*return $detalle_almacen=Venta::join('cajas','ventas.id_caja','cajas.id')
        ->join('sucursals','cajas.id_sucursal','sucursals.id')
        ->join('almacenes','sucursals.id_almacen','almacenes.id')
        ->join('detalle_almacen','almacenes.id','detalle_almacen.id_almacen')
        ->where('ventas.id',$id)
        ->select('detalle_almacen.id','detalle_almacen.stock')->get();*/
  
        /*$detalle_caja=detalle_caja::where('id_caja',$getventa['id_caja'])
        ->where('detalle_cajas.updated_at','>=',$getventa['created_at'])
        ->where('detalle_cajas.created_at','<',$getventa['created_at'])->get()->last();
        return $detalle_caja['monto_actual']-$getventa['total'];*/

        //actualizacion  de detalle_cajas
       /*$updatecaja=detalle_caja::where('id_caja',$getventa['id_caja'])
        ->where('detalle_cajas.updated_at','>=',$getventa['created_at'])
        ->where('detalle_cajas.created_at','<',$getventa['created_at'])
        ->update('monto_actual',$detalle_caja['monto_actual']-$getventa['total']);*/
    }
}
