<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\detalle_Ventas;
use App\Venta;
use App\detalle_almacen;
use App\detalle_caja;    
use App\Inventario;
use App\Movimiento;
use App\Almacenes;
use App\Productos;
use App\movimientos_detalle_almacen; 

class DetalleVentaController extends Controller
{
    public function insertar(Request $request){
        $json=$request->input('json',null);
        $params=json_decode($json);
        

        $cantidad=(!is_null($json) && isset($params->cantidad)) ? $params->cantidad : null;
		$precio_unitario=(!is_null($json) && isset($params->precio_unitario)) ? $params->precio_unitario : null;
        $id_producto=(!is_null($json) && isset($params->id_producto)) ? $params->id_producto : null;
        $igv=(!is_null($json) && isset($params->igv)) ? $params->igv : null;
        $isc=(!is_null($json) && isset($params->isc)) ? $params->isc : null;
        $otro=(!is_null($json) && isset($params->otro)) ? $params->otro : null;
        $descuento=(!is_null($json) && isset($params->descuento)) ? $params->descuento : null;
        $igv_id=(!is_null($json) && isset($params->igv_id)) ? $params->igv_id : null;
        $isc_id=(!is_null($json) && isset($params->isc_id)) ? $params->isc_id : null;
        $otro_id=(!is_null($json) && isset($params->otro_id)) ? $params->otro_id : null;
        $igv_porcentage=(!is_null($json) && isset($params->igv_porcentage)) ? $params->igv_porcentage : null;
        $isc_porcentage=(!is_null($json) && isset($params->isc_porcentage)) ? $params->isc_porcentage : null;
        $otro_porcentage=(!is_null($json) && isset($params->otro_porcentage)) ? $params->otro_porcentage : null;
        $id_venta=Venta::get()->last();

        $detalle_Ventas=new detalle_Ventas();
            
        $detalle_Ventas->id_venta=$id_venta['id'];
        $detalle_Ventas->cantidad=$cantidad;
        $detalle_Ventas->precio_unitario=$precio_unitario;
        $detalle_Ventas->id_producto=$id_producto;
        $detalle_Ventas->igv=$igv;
        $detalle_Ventas->isc=$isc;
        $detalle_Ventas->otro=$otro;
        $detalle_Ventas->descuento=$descuento;
        $detalle_Ventas->igv_id=$igv_id;
        $detalle_Ventas->isc_id=$isc_id;
        $detalle_Ventas->otro_id=$otro_id;
        $detalle_Ventas->igv_porcentage=$igv_porcentage;
        $detalle_Ventas->isc_porcentage=$isc_porcentage;
        $detalle_Ventas->otro_porcentage=$otro_porcentage;
        $detalle_Ventas->estado=true;
        $detalle_Ventas->save(); 

        //--------------------actualizacion de detalle_almacen --------------------------------------
        $tock=0;
        $total=0;
        $detalle_almacen=0;
        $detalle_caja=0;
        $tock=Venta::join('cajas','ventas.id_caja','=','cajas.id')
        ->join('sucursals','cajas.id_sucursal','=','sucursals.id')
        ->join('detalle_almacen','sucursals.id_almacen','=','detalle_almacen.id_almacen')
        ->where('detalle_almacen.id_producto','=',$id_producto)->get()->last();
            
        $total=$tock['stock']-$cantidad;
        
        $costoactual=(($tock['stock'] *$tock['precio_compra'])-($cantidad*$tock['precio_compra']))/($tock['stock']-$cantidad);
        $costoactualredondeado=round($costoactual*100)/100;
        $m_d_a_ultimo=movimientos_detalle_almacen::where('id_detalle_almacen',$detalle_almacen['id'])->get()->last();
        if($costoactualredondeado!=$tock['precio_compra']){
            $m_d_almacen=new movimientos_detalle_almacen();
            $m_d_almacen->id_detalle_almacen=$tock['id'];
            $m_d_almacen->descuento_anterior=$m_d_a_ultimo['descuento_anterior'];
            $m_d_almacen->descuento_actual=$m_d_a_ultimo['descuento_actual'];
            $m_d_almacen->precio_anterior=$m_d_a_ultimo['precio_anterior'];
            $m_d_almacen->precio_actual=$m_d_a_ultimo['precio_actual'];
            $m_d_almacen->precio_compra_actual=$costoactualredondeado;
            $m_d_almacen->precio_compra_anterior=$tock['precio_compra'];
            $m_d_almacen->save();
        }
        $detalle_almacen=detalle_almacen::where('id','=',$tock['id'])->update(['stock'=>$total,'precio_compra'=>$costoactualredondeado]);
    
        //-----------------------------------------------------------------------------------------------
        //-------------------------actualizacionb monto caja actual---------------------------
        
        //-------------------------------------------------------------------------------------
        $data =array(
            'status'=>'succes',
            'code'=>200,
            'mensage'=>'registrado',
            'stock'=>$tock
        );

        return response()->json($data,200);
    }

    public function insertarmoveinv(Request $request){
        $json=$request->input('json',null);
        $params=json_decode($json);

        $id_producto=(!is_null($json) && isset($params->id_producto)) ? $params->id_producto : null;
        $cantidad=(!is_null($json) && isset($params->cantidad)) ? $params->cantidad : null;
        $precio=(!is_null($json) && isset($params->precio)) ? $params->precio : null;
        $codigo=(!is_null($json) && isset($params->codigo)) ? $params->codigo : null;
        $usuario=(!is_null($json) && isset($params->usuario)) ? $params->usuario : null;

        $Venta=Venta::join('cajas','ventas.id_caja','=','cajas.id')
        ->join('sucursals','cajas.id_sucursal','=','sucursals.id')
        ->join('detalle_almacen','sucursals.id_almacen','=','detalle_almacen.id_almacen')
        ->orderBy('ventas.id')
        ->get()
        ->last(); 
        $tipodocumento=$Venta['serie_venta'];
        $tipodeventa='';
        $arregloventa=str_split($tipodocumento);
        switch ($arregloventa[0]) {
            case 'F':
                $tipodeventa="Factura";
                break;
            case 'B':
                $tipodeventa="Boleta";
                break;
        }
        
		$Inventario=new Inventario();
		$Inventario->id_almacen=$Venta['id_almacen'];
		$Inventario->id_producto=$id_producto;
		$Inventario->descripcion="Ventas ".$tipodeventa." N° ".$Venta['serie_venta'];
		$Inventario->tipo_movimiento=1;
		$Inventario->cantidad=$cantidad;
		$Inventario->precio=$precio;
        $Inventario->save();


        $d_almace=0;

        if(!is_null($codigo)){
            $d_almace=detalle_almacen::where('id_almacen','=',$Venta['id_almacen'])->where('codigo','=',$codigo)->first();
        }else{
            $d_almace=detalle_almacen::where('id_almacen','=',$Venta['id_almacen'])->where('id_producto','=',$id_producto)->first();
        }
        
        $id_tabla=Inventario::get()->last();

		$almacen_nombre=Almacenes::where('id','=',$Venta['id_almacen'])->get()->first();
        $productos_nombre=Productos::where('id','=',$id_producto)->get()->first();
        //$cantmovimiento=Movimiento::where('productos_id',$id_producto)->get()->last();
        $movimiento=new Movimiento();
        
		$movimiento->tabla_nombre='Ventas';
		$movimiento->id_tabla=$Venta['id'];
		$movimiento->almacen_nombre=$almacen_nombre['nombre'];
        $movimiento->productos_nombre=$productos_nombre['nombre_producto'];
        //$movimiento->productos_id=$id_producto;
        $movimiento->id_usuario=$usuario;
        /*if(@count($cantmovimiento)==0){
            $movimiento->valor=$cantidad;
		    $movimiento->valor_antiguo=null;
        }else{
            $movimiento->valor=$cantmovimiento['valor']+$cantidad;
		    $movimiento->valor_antiguo=$cantmovimiento['valor'];
        }*/
		$movimiento->valor=$d_almace['stock'];
		$movimiento->valor_antiguo=$d_almace['stock']+$cantidad;
        $movimiento->save();
        
        $data =array(
            'status'=>'succes',
            'code'=>200,
            'mensage'=>'se guardo el inventario y el movimiento'
        );
        
        return response()->json($data,200);
    } 
    public function getdetalleventas($id){
        $detalle_Ventas=detalle_Ventas::join('productos','detalle_ventas.id_producto','productos.id')
        ->where('id_venta','=',$id)
        ->where('detalle_ventas.estado','=',true)
        ->select('detalle_ventas.id','detalle_ventas.cantidad','detalle_ventas.id_venta','detalle_ventas.igv','detalle_ventas.isc','detalle_ventas.otro','detalle_ventas.precio_unitario','productos.nombre_producto','productos.marca','productos.modelo','productos.observaciones')
        ->get();
        return $detalle_Ventas;
    } 
}
