<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\detalle_nota_credito;
use App\nota_credito;
use App\Venta;
use App\detalle_Ventas;
use App\Inventario;
use App\Movimiento;
use App\Almacenes;
use App\detalle_almacen;
use App\Productos;
use App\movimientos_detalle_almacen;

class detalle_notacreditoController extends Controller
{
    public function insertar(Request $request){
        $json=$request->input('json',null);
        $params=json_decode($json);
         
		$id_detalle_venta=(!is_null($json) && isset($params->id_detalle_venta)) ? $params->id_detalle_venta : null;
        $cantidad=(!is_null($json) && isset($params->cantidad)) ? $params->cantidad : null;
        $correccion=(!is_null($json) && isset($params->correccion)) ? $params->correccion : null;
        $igv=(!is_null($json) && isset($params->igv)) ? $params->igv : null;
        $isc=(!is_null($json) && isset($params->isc)) ? $params->isc : null;
        $otro=(!is_null($json) && isset($params->otro)) ? $params->otro : null;


        $id_nota_credito=nota_credito::get()->last();

        $d_n_credito=new detalle_nota_credito();

        $d_n_credito->id_nota_credito=$id_nota_credito['id'];
        $d_n_credito->id_detalle_venta=$id_detalle_venta;
        $d_n_credito->cantidad=$cantidad;
        $d_n_credito->correccion=$correccion;
        $d_n_credito->igv=$igv;
        $d_n_credito->isc=$isc;
        $d_n_credito->otro=$otro;

        $d_n_credito->save();
        
        $data =array(
            'status'=>'succes',
            'code'=>200,
            'mensage'=>'guardado'
        );
        return response()->json($data,200);

    }
    public function movimientoseinventarionota(Request $request){
        $json=$request->input('json',null);
        $params=json_decode($json);

        $id_detalle_venta=(!is_null($json) && isset($params->id_detalle_venta)) ? $params->id_detalle_venta : null;

        $detalle_venta=detalle_Ventas::where('id',$id_detalle_venta)->get()->last();

        $nota_credito=nota_credito::get()->last();

        $id_producto=$detalle_venta['id_producto'];
        $cantidad=$detalle_venta['cantidad'];
        $precio=$detalle_venta['precio_unitario'];
        $usuario=$nota_credito['id_usuario'];

        $id_venta=Venta::join('cajas','ventas.id_caja','=','cajas.id')
        ->join('sucursals','cajas.id_sucursal','=','sucursals.id')
        ->join('detalle_almacen','sucursals.id_almacen','=','detalle_almacen.id_almacen')
        ->where('ventas.id',$nota_credito['id_venta'])
        ->get()
        ->last(); 

        $tipodocumento=$id_venta['serie_venta'];
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
		$Inventario->id_almacen=$id_venta['id_almacen'];
		$Inventario->id_producto=$id_producto;
		$Inventario->descripcion="Nota de Credito N° ".$nota_credito['serie_nota'];
		$Inventario->tipo_movimiento=2;
		$Inventario->cantidad=$cantidad;
        $Inventario->precio=$precio;
        $Inventario->save();

        $d_almace=detalle_almacen::where('id_almacen','=',$id_venta['id_almacen'])->where('id_producto','=',$id_producto)->first();

        $id_tabla=Inventario::get()->last();

		$almacen_nombre=Almacenes::where('id','=',$id_venta['id_almacen'])->get()->first();
        $productos_nombre=Productos::where('id','=',$id_producto)->get()->first();
        //$cantmovimiento=Movimiento::where('productos_id',$id_producto)->get()->last();
        $movimiento=new Movimiento();
        
		$movimiento->tabla_nombre='Nota de Credito';
		$movimiento->id_tabla=$nota_credito['id'];
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
    public function anulacionesydevoluciones(Request $request){
        $json=$request->input('json',null);
        $params=json_decode($json);

        $id_detalle_venta=(!is_null($json) && isset($params->id_detalle_venta)) ? $params->id_detalle_venta : null;

        $detalle_venta=detalle_Ventas::where('id',$id_detalle_venta)->get()->last();
        $nota_credito=nota_credito::get()->last();
        $m_d_almacen=new movimientos_detalle_almacen();
        $id_producto=$detalle_venta['id_producto'];
        $cantidad=$detalle_venta['cantidad'];
        $precio=$detalle_venta['precio_unitario'];
        $usuario=$nota_credito['id_usuario'];

        $id_venta=Venta::join('cajas','ventas.id_caja','=','cajas.id')
        ->join('sucursals','cajas.id_sucursal','=','sucursals.id')
        ->join('detalle_almacen','sucursals.id_almacen','=','detalle_almacen.id_almacen')
        ->where('ventas.id',$nota_credito['id_venta'])
        ->select('ventas.created_at','detalle_almacen.id_almacen')
        ->get()
        ->last(); 

        $modificard_eventa=detalle_Ventas::where('id',$id_detalle_venta)->update(['estado'=>false]);
       

        $d_almace=detalle_almacen::where('id_almacen','=',$id_venta['id_almacen'])->where('id_producto','=',$id_producto)->first();
        $total=$d_almace['stock']+$detalle_venta['cantidad'];
        $precioactual=movimientos_detalle_almacen::where('created_at','<',$id_venta['created_at'])->where('id_detalle_almacen',$d_almace['id'])->get()->last();

        $promedio = (($d_almace['stock']*$d_almace['precio_compra'])+($cantidad*$precioactual['precio_compra_actual']))/($d_almace['stock']+$cantidad);

        $actulizard_almacen=detalle_almacen::where('id',$d_almace['id'])->update(['stock'=>$d_almace['stock']+$cantidad]);
        if(round( $promedio,2)!=$d_almace['precio_compra']){
            $actulizard_almacen=detalle_almacen::where('id',$d_almace['id'])->update(['precio_compra'=>round($promedio,2)]);
            $m_d_a_ultimo=movimientos_detalle_almacen::where('id_detalle_almacen',$d_almace['id'])->get()->last();
			$m_d_almacen->id_usuario=$usuario;
			$m_d_almacen->id_detalle_almacen=$d_almace['id'];
			$m_d_almacen->descuento_anterior=$m_d_a_ultimo['descuento_actual'];
			$m_d_almacen->descuento_actual=$m_d_a_ultimo['descuento_actual'];
			$m_d_almacen->precio_anterior=$m_d_a_ultimo['precio_actual'];
			$m_d_almacen->precio_actual=$m_d_a_ultimo['precio_actual'];
			$m_d_almacen->precio_compra_actual=round($promedio,2);
			$m_d_almacen->precio_compra_anterior=$m_d_a_ultimo['precio_compra_actual'];
			$m_d_almacen->save();
        }
        $data =array(
            'status'=>'succes',
            'code'=>200,
            'mensage'=>'actualizo'
        );
        
        return response()->json($data,200);

    }
    public function prub($id){

        $detalle_venta=detalle_Ventas::where('id',$id)->get()->last();
        $nota_credito=nota_credito::orderby('id')->get()->last();
        $m_d_almacen=new movimientos_detalle_almacen();
        $id_producto=$detalle_venta['id_producto'];
        $cantidad=$detalle_venta['cantidad'];
        $precio=$detalle_venta['precio_unitario'];
        $usuario=$nota_credito['id_usuario'];

         $id_venta=Venta::join('cajas','ventas.id_caja','=','cajas.id')
        ->join('sucursals','cajas.id_sucursal','=','sucursals.id')
        ->join('detalle_almacen','sucursals.id_almacen','=','detalle_almacen.id_almacen')
        ->where('ventas.id',$nota_credito['id_venta'])
        ->select('ventas.created_at','detalle_almacen.id_almacen')
        ->get()
        ->last(); 


         $d_almace=detalle_almacen::where('id_almacen','=',1)->where('id_producto','=',$id_producto)->first();
        //$total=$d_almace['stock']+$detalle_venta['cantidad'];
        $precioactual=movimientos_detalle_almacen::where('created_at','<',$id_venta['created_at'])->where('id_detalle_almacen',$d_almace['id'])->get()->last();
         $d_almace['stock'].'-'.$d_almace['precio_compra'].'-'.$cantidad.'-'.$precioactual['precio_compra_actual'];
        return $promedio = (($d_almace['stock']*$d_almace['precio_compra'])+($cantidad*$precioactual['precio_compra_actual']))/($d_almace['stock']+$cantidad);
        
    }
}
