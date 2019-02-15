<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PagoDetalle;
use App\Pago;
use App\detalle_almacen;
use App\Inventario;
use App\Movimiento;
use App\Almacenes;
use App\Productos;
use App\TipoDocumento;
use App\movimientos_detalle_almacen;

class PagoDetalleController extends Controller
{
    public function addPagoDetalle(Request $request){
        $create=PagoDetalle::create($request->all());
      	return response()->json($create);
    }
    public function DetalleAlmacen(Request $request){
		$m_d_almacen=new movimientos_detalle_almacen();
    	$pago=Pago::get()->last(); //recupera el ultimo pago realizado
		$pago_d=PagoDetalle::get()->last();  //recupera el ultimo detalle
		$mov=Movimiento::get()->last();   //recupera el ultimo registro guiardado en movimientos
    	$id=$pago['id_almacen'];
    	$cantidad=$pago_d['cantidad'];
        $vendible=$request->vendible;
    	$id_pro=$request->id_producto;
    	$pre_comp=$request->precio_compra;
		$pre_vent=$request->precio_venta;
    	$d_almace=detalle_almacen::where('id_almacen','=',$id)
								   ->where('id_producto','=',$id_pro)
								   ->where('vendible','=',$vendible)
                                   ->first();
    	if(@count($d_almace)>=1){
//actualizacion de precio de detalle_almacen---------------------------------------------------------------------
			$stockactual=$d_almace['stock'];
        	$precioctual=$d_almace['precio_compra'];
			$costoactualizado=(($stockactual *$precioctual)+($cantidad*$pre_comp))/($stockactual+$cantidad);
			$costoguardado=round($costoactualizado,2);
			
			
//-------------guardar en movimiento_detalle_almacen----------------------------------------
			
			if($costoguardado!=$d_almace['precio_compra']){
				$m_d_a_ultimo=movimientos_detalle_almacen::where('id_detalle_almacen',$d_almace['id'])->get()->last();
				$m_d_almacen->id_usuario=$mov['id_usuario'];
				$m_d_almacen->id_detalle_almacen=$d_almace['id'];
				$m_d_almacen->descuento_anterior=$m_d_a_ultimo['descuento_anterior'];
				$m_d_almacen->descuento_actual=$m_d_a_ultimo['descuento_actual'];
				$m_d_almacen->precio_anterior=$m_d_a_ultimo['precio_actual'];
				$m_d_almacen->precio_actual=$m_d_a_ultimo['precio_actual'];
				$m_d_almacen->precio_compra_actual=$costoguardado;
				$m_d_almacen->precio_compra_anterior=$d_almace['precio_compra'];
				$m_d_almacen->save();
			}
//---------------------------------------------------------------------------------------------------------
			
			$d_almace->precio_compra=$costoguardado;
//-----------------------------------------------------------------------------------------------------------

			$d_almace->stock=$d_almace['stock']+$cantidad;
			$d_almace->update();
			
			$data =array(
				'status'=>'succes',
				'code'=>200,
				'mensage'=>'actualizado',
			);
			return response()->json($data,200);
    	}else{
    		$detalle_almacen= new detalle_almacen();
	    	$detalle_almacen->id_almacen=$id;
            $detalle_almacen->vendible=$vendible;
	    	$detalle_almacen->id_producto=$id_pro;
	    	$detalle_almacen->stock=$cantidad ;
	    	$detalle_almacen->precio_compra=$pre_comp;
	    	$detalle_almacen->precio_venta=$pre_vent;
			$detalle_almacen->save();

			$detalleactual=detalle_almacen::where('id_almacen','=',$id)
				->where('id_producto','=',$id_pro)
				->where('vendible','=',$vendible)
				->first();
//-------------guardar en movimiento_detalle_almacen----------------------------------------
				$m_d_almacen->id_detalle_almacen=$detalleactual['id'];
				$m_d_almacen->id_usuario=$mov['id_usuario'];
				$m_d_almacen->descuento_anterior=0;
				$m_d_almacen->descuento_actual=0;
				$m_d_almacen->precio_anterior=0;
				$m_d_almacen->precio_actual=0;
				$m_d_almacen->precio_compra_actual=$pre_comp;
				$m_d_almacen->precio_compra_anterior=0;
				$m_d_almacen->save();
//---------------------------------------------------------------------------------------------------------
				$data =array(
					'status'=>'succes',
					'code'=>200,
					'mensage'=>'guardado',
				);
				return response()->json($data,200);
		}


	}
	//guardarmovimiento_detalle_almacen

	public function redondeo($cantidad){
		$resultado=round($cantidad * 100) / 100; 
		return $resultado;
	}
}
