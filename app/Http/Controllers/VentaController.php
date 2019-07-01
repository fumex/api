<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Venta;
use App\productos;
use App\detalle_caja; 
use App\detalle_almacen; 
use App\detalle_Ventas;
use App\Detalle_usuario; 
use App\movimientos_detalle_almacen; 
use App\Sucursal;
use App\Cliente;
use App\Moneda;

class VentaController extends Controller
{
    public function getdocumento(){
        $i=5;
        $j=0;
        $k=1;
        $resb="";
        $resf="";
        $total=0;
        $boleta=Venta::where('serie_venta', 'like', '%' . 'B' . '%')->orderby('id')->get()->last();
        $factura=Venta::where('serie_venta', 'like', '%' . 'F' . '%')->orderby('id')->get()->last();
       
        
        $nombreboleta=$boleta['serie_venta'];
        $nombrefactura=$factura['serie_venta'];
        $boletarr=str_split($nombreboleta);
        $facturarr=str_split($nombrefactura);
        $boletanueva="";
        $facturanueva="";
        $serie="";
        if(@count($boleta) < 1){
            $resb="B001-1";
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
                /*while($j < (strlen($boletanueva)-strlen($total))){
                    $resb.="0";
                    $j++;
                }*/
                $resb=$resb.($total);
            }
            
        }
        $i=5;
        $j=0;
        $k=1;
        $serie="";
        if(@count($factura) < 1){
            $resf="F001-1";
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
                /*while($j < (strlen($facturanueva)-strlen($total))){
                    $resf.="0";
                    $j++;
                }*/
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
        $id_moneda=(!is_null($json) && isset($params->id_moneda)) ? $params->id_moneda : null;
        $igv=(!is_null($json) && isset($params->igv)) ? $params->igv : null;
        $isc=(!is_null($json) && isset($params->isc)) ? $params->isc : null;
        $otro=(!is_null($json) && isset($params->otro)) ? $params->otro : null;
        $letrado=(!is_null($json) && isset($params->letrado)) ? $params->letrado : null;
        $email=(!is_null($json) && isset($params->email)) ? $params->email : null;

        $cliente_nro=Cliente::where('id',$id_cliente)->value('nro_documento');
        $moneda_cod=(string)Moneda::where('id',$id_moneda)->value('codigo_sunat');
        $Venta=new Venta();
        
        $Venta->nro_documento=$cliente_nro;
        $Venta->descuento_global=0;
        $Venta->email=0;
        $Venta->codigo_moneda=$moneda_cod;
        $Venta->serie_venta=$serie_venta;
        $Venta->tarjeta=$tarjeta;
        $Venta->id_caja=$id_caja;
        $Venta->id_cliente=$id_cliente;
        $Venta->total=$total;
        $Venta->pago_efectivo=$pago_efectivo;
        $Venta->pago_tarjeta=$pago_tarjeta;
        $Venta->estado=true;
        $Venta->id_usuario=$id_usuario;
        $Venta->id_moneda=$id_moneda;
        $Venta->igv=$igv;
        $Venta->isc=$isc;
        $Venta->otro=$otro;
        $Venta->letrado=$letrado;

        $Venta->save();

        $detalle_caja=detalle_caja::where('id_caja',$id_caja)->where('abierta',true)->get()->last();
        $total=$detalle_caja['monto_actual']+$total;
        $actualizar_caja=detalle_caja::where('id',$detalle_caja['id'])->update(['monto_actual'=>$total]);

        $idventa=Venta::orderby('id')->get()->last();

        $data =array(
            'status'=>'succes',
            'code'=>200,
            'mensage'=>'registrado',
            'serie'=>$idventa['serie_venta'],
            'fecha'=>$idventa['created_at'],
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




    public function getventasporfecha($fecha,$id){
        $fechainicial=$fecha.' 00:00:00';
        $fechafinal=$fecha.' 23:59:59';
        return $venta=Sucursal::join('detalle_usuarios','sucursals.id','detalle_usuarios.id_sucursal')
        ->join('cajas','sucursals.id','cajas.id_sucursal')
        ->join('ventas','cajas.id','ventas.id_caja')
        ->join('clientes','ventas.id_cliente','=','clientes.id')
        ->join('users','ventas.id_usuario','=','users.id')
        ->where('detalle_usuarios.id_user',$id)
        ->where('detalle_usuarios.permiso',true)
        ->whereBetween('ventas.created_at',[$fechainicial,$fechafinal])
        ->select('ventas.id','users.apellidos','ventas.serie_venta','ventas.id_caja','clientes.nombre','ventas.total','ventas.pago_efectivo','pago_tarjeta','ventas.created_at','users.name','ventas.id_usuario')
        ->get();
        /*return $venta=Venta::join('clientes','ventas.id_cliente','=','clientes.id')
        ->join('detalle_usuarios','ventas.id_usuario','detalle_usuarios.id_user')
        ->join('users','detalle_usuarios.id_user','=','users.id')
        ->join('sucursals','detalle_usuarios.id_sucursal','sucursals.id')
        ->where('users.id',$id)
       
        ->select('ventas.id','users.apellidos','ventas.serie_venta','ventas.id_caja','clientes.nombre','ventas.total','ventas.pago_efectivo','pago_tarjeta','ventas.created_at','users.name','ventas.id_usuario')
        ->get();*/
    }

    public function getventasconusuario(){
        return $ventas=Venta::join('users','ventas.id_usuario','=','users.id')
        ->select('users.id','users.name','users.apellidos')
        ->get();
    }
    public function getventaporusuario($fecha,$id){
        $fechainicial=$fecha.' 00:00:00';
        $fechafinal=$fecha.' 23:59:59';
        return $venta=Venta::join('clientes','ventas.id_cliente','=','clientes.id')
        ->join('users','ventas.id_usuario','=','users.id')
        ->where('ventas.id_usuario',$id)
        ->whereBetween('ventas.created_at',[$fechainicial,$fechafinal])
        ->select('ventas.id','users.apellidos','ventas.serie_venta','ventas.id_caja','clientes.nombre','ventas.total','ventas.pago_efectivo','pago_tarjeta','ventas.created_at','users.name','ventas.id_usuario')
        ->get();
    }

    public function getventasdiarias(){
        $now = new \DateTime();
        $fechainicial=$now->format('d_m_Y').' 00:00:00';
        $fechafinal=$now->format('d_m_Y').' 23:59:59';
        return $venta=Venta::join('clientes','ventas.id_cliente','=','clientes.id')
        ->join('users','ventas.id_usuario','=','users.id')
        ->join('cajas','ventas.id_caja','=','cajas.id')
        ->join('sucursals','cajas.id_sucursal','sucursals.id')
        ->whereBetween('ventas.created_at',[$fechainicial,$fechafinal])
        ->select('ventas.id','users.apellidos','ventas.serie_venta','ventas.id_caja','clientes.nombre','ventas.total','ventas.pago_efectivo','pago_tarjeta','ventas.created_at','users.name','ventas.id_usuario')
        ->get();
    }
    public function getventastotales(){

        return $venta=Venta::join('clientes','ventas.id_cliente','=','clientes.id')
        ->join('users','ventas.id_usuario','=','users.id')
        ->join('cajas','ventas.id_caja','=','cajas.id')
        ->join('sucursals','cajas.id_sucursal','sucursals.id')
        ->select('ventas.id','users.apellidos','ventas.serie_venta','ventas.id_caja','cajas.nombre AS namecaja','clientes.nombre','ventas.total','ventas.pago_efectivo','pago_tarjeta','ventas.created_at','users.name','ventas.id_usuario')
        ->orderby('created_at')
        ->get();
    }

    public function getventaporsucursal($id){
        $now = new \DateTime();
        $fechainicial=$now->format('d_m_Y').' 00:00:00';
        $fechafinal=$now->format('d_m_Y').' 23:59:59';
        return $venta=Venta::join('clientes','ventas.id_cliente','=','clientes.id')
        ->join('users','ventas.id_usuario','=','users.id')
        ->join('cajas','ventas.id_caja','=','cajas.id')
        ->join('sucursals','cajas.id_sucursal','sucursals.id')
        ->where('sucursals.id',$id)
        ->whereBetween('ventas.created_at',[$fechainicial,$fechafinal])
        ->select('ventas.id','users.apellidos','ventas.serie_venta','ventas.id_caja','clientes.nombre','ventas.total','ventas.pago_efectivo','pago_tarjeta','ventas.created_at','users.name','ventas.id_usuario')
        ->get();
    }

    public function getproductosvendidos($fecha,$id){
        $fechainicial=$fecha.' 00:00:00';
        $fechafinal=$fecha.' 23:59:59';
        return $venta=Sucursal::join('detalle_usuarios','sucursals.id','detalle_usuarios.id_sucursal')
        ->join('cajas','sucursals.id','cajas.id_sucursal')
        ->join('ventas','cajas.id','ventas.id_caja')
        ->join('detalle_ventas','ventas.id','detalle_ventas.id_venta')
        ->join('productos','detalle_ventas.id_producto','=','productos.id')
        ->join('categorias','productos.id_categoria','=','categorias.id')
        ->join('unidades','productos.id_unidad','=','unidades.id')
        ->join('monedas','ventas.id_moneda','monedas.id')
        ->join('clientes','ventas.id_cliente','=','clientes.id')
        ->join('users','ventas.id_usuario','=','users.id')
        ->where('detalle_usuarios.id_user',$id)
        ->where('detalle_usuarios.permiso',true)
        ->where('detalle_ventas.estado',true)
        ->where('productos.estado',true)
        ->where('unidades.estado',true)
        ->whereBetween('ventas.created_at',[$fechainicial,$fechafinal])
        ->select('monedas.moneda','detalle_ventas.id','categorias.nombre AS nombre_categoria','productos.nombre_producto','productos.descripcion','unidades.unidad','productos.marca','productos.modelo','productos.observaciones'
        ,'users.apellidos','ventas.serie_venta','cajas.nombre AS nombre_caja','clientes.nombre AS nombre_cliente','ventas.total','ventas.pago_efectivo','ventas.pago_tarjeta','ventas.created_at','users.name','clientes.nro_documento','clientes.direccion'
        ,'detalle_ventas.igv','detalle_ventas.isc','detalle_ventas.otro','detalle_ventas.precio_unitario','detalle_ventas.cantidad','detalle_ventas.descuento'
        ,'detalle_ventas.igv_id','detalle_ventas.isc_id','detalle_ventas.otro_id','detalle_ventas.igv_porcentage','detalle_ventas.isc_porcentage','detalle_ventas.otro_porcentage')
        ->get();
    }
    public function getventaporserie($id){
        $ventas=Venta::where('serie_venta',$id)->get()->last();
        if(@count($ventas) > 0){
            return $detalle_Ventas=detalle_Ventas::join('ventas','detalle_ventas.id_venta','ventas.id')
            ->join('clientes','ventas.id_cliente','=','clientes.id')
            ->join('monedas','ventas.id_moneda','monedas.id')
            ->join('users','ventas.id_usuario','=','users.id')
            ->join('productos','detalle_ventas.id_producto','=','productos.id')
            ->join('categorias','productos.id_categoria','=','categorias.id')
            ->join('unidades','productos.id_unidad','=','unidades.id')
            ->where('detalle_ventas.id_venta',$ventas['id'])
            ->select('monedas.moneda','ventas.id AS id_venta','detalle_ventas.id','categorias.nombre AS nombre_categoria','productos.nombre_producto'
            ,'productos.descripcion','unidades.unidad','productos.marca','productos.modelo','productos.observaciones'
            ,'users.apellidos','ventas.serie_venta','clientes.nombre AS nombre_cliente','ventas.total','ventas.pago_efectivo'
            ,'ventas.pago_tarjeta','ventas.created_at','users.name','clientes.nro_documento','clientes.direccion'
            ,'detalle_ventas.igv','detalle_ventas.isc','detalle_ventas.otro','detalle_ventas.precio_unitario'
            ,'detalle_ventas.cantidad','detalle_ventas.descuento' ,'detalle_ventas.igv_id','detalle_ventas.isc_id'
            ,'detalle_ventas.otro_id','detalle_ventas.igv_porcentage','detalle_ventas.isc_porcentage','detalle_ventas.otro_porcentage')
            ->get();
        }else{
            $data =array(
                'result'=>false
            );
            return response()->json($data,200);
        }
    }
    public function getventaporid($id){
        return $Ventas=Venta::join('clientes','ventas.id_cliente','=','clientes.id')
        ->join('users','ventas.id_usuario','=','users.id')
        ->join('monedas','ventas.id_moneda','monedas.id')
        ->where('ventas.id',$id)
        ->select('ventas.serie_venta','monedas.moneda','ventas.id','users.apellidos','ventas.serie_venta','clientes.nombre AS nombre_cliente','ventas.total','ventas.pago_efectivo','ventas.pago_tarjeta','ventas.created_at','users.name','clientes.nro_documento','clientes.direccion')
        ->get();
    }
    
    public function getVenta($id){
        $venta=Venta::find($id);
        return response()->json($venta);   
   }
}
