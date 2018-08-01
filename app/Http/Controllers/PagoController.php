<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Pago;
use App\Proveedor;
use App\PagoDetalle;
use App\detalle_almacen;
use DB;
class PagoController extends Controller
{
    public function addPago(Request $request){
    	$create=Pago::create($request->all());
        return response()->json($create);
    }

    public function code(){
    	$max = Pago::count();
        if ($max > 0) {
            $row = explode('-',Pago::max('code'), 2);
            $cod = $row[1];
            $sig = $cod+1;
            $Strsig = (string)$sig;
            $formato = "P-".str_pad($Strsig, "5", "0", STR_PAD_LEFT);
            
        } 
        else {
            $sig = 1;
            $Strsig = (string)$sig;
            $formato = "P-".str_pad($Strsig,"5","0",STR_PAD_LEFT);
        }
        
        return response()->json($formato);
    }

    public function getProveedores(){
        $proveedores=DB::table('proveedors')
                         ->join('tipo_proveedors','proveedors.tipo_proveedor','=','tipo_proveedors.id')
                         ->select('proveedors.id','proveedors.nombre_proveedor','proveedors.ruc','proveedors.direccion','proveedors.telefono','proveedors.email')
                         ->where('proveedors.estado','=',true)->where('tipo_proveedors.operacion','=','Proveedor')
                         ->get();
        return response()->json($proveedores);
    }
//-------------------------------Lista de  pagos por usuarios----------------------------------------
    public function listPagos($id){
       $pagos=DB::table('pagos')
                    ->join('proveedors','pagos.id_proveedor','=','proveedors.id')
                    ->join('tipo_documentos','pagos.id_documento','=','tipo_documentos.id')
                    ->join('almacenes','pagos.id_almacen','=','almacenes.id')
                    ->join('sucursals','almacenes.id','=','sucursals.id_almacen')
                    ->join('detalle_usuarios','sucursals.id','=','detalle_usuarios.id_sucursal')
                    ->select('pagos.id','pagos.code','pagos.id_proveedor','proveedors.nombre_proveedor','tipo_documentos.documento','pagos.nroBoleta','almacenes.nombre','pagos.tipoPago','pagos.subtotal','pagos.igv','pagos.exonerados','pagos.gravados','pagos.otro','pagos.created_at')
                    ->where('detalle_usuarios.id_user','=',$id)
                    ->where('detalle_usuarios.permiso','=',true)
                    ->where('pagos.estado','=',true)
                    ->get();
        return response()->json($pagos);
    }
//-------------------------------------------------Pago detalles--------------------------------------------
    public function getPagoDetalle($code){
        $pago_d=DB::table('pago_detalles')
                    ->join('pagos','pago_detalles.id_pago','=','pagos.code')
                    ->join('proveedors','pagos.id_proveedor','=','proveedors.id')
                    ->join('productos','pago_detalles.id_producto','=','productos.id')
                    ->join('unidades','productos.id_unidad','=','unidades.id')
                    ->join('almacenes','pagos.id_almacen','=','almacenes.id')
                    ->select('pago_detalles.id','pago_detalles.id_pago','pago_detalles.id_producto','productos.nombre_producto','pagos.id_almacen','almacenes.nombre','pago_detalles.cantidad','pago_detalles.precio_unitario','unidades.unidad')
                    ->where('pago_detalles.id_pago','=',$code)->where('pago_detalles.estado','=',true)
                    ->get();
        return response()->json($pago_d);
    }
//----------------------------------------------------------------------------------------------------------
    public function getCompra($code){
        $compra=Pago::where('code','=',$code)->first();
        $id_pago=$compra['id'];
        return $id_pago;
    }
    public function deletePago($id){
       $pago=Pago::find($id);
       if(@count($pago)>=1){
         $pago->estado=false;
         $pago->save();
         return $pago;
       }
    }
   
   public function deletePagoDetalle($id){
        $pago_d=PagoDetalle::find($id);
        $cantidad=$pago_d['cantidad'];
        $cod=$pago_d['id_pago'];
        $id_producto=$pago_d['id_producto'];
        if(@count($pago_d)>=1){
            $pago_d->estado=false;
            $pago_d->save();
            //return $pago_d;
            $pago=Pago::where('code','=',$cod)->first();
            $id_almacen=$pago['id_almacen'];
            if(@count($pago)>=1){
                $almacen_d=detalle_almacen::where('id_almacen','=',$id_almacen)->where('id_producto','=',$id_producto)->first();
                if(@count($almacen_d)>=1){
                    $almacen_d->stock=$almacen_d['stock']-$cantidad;
                    $almacen_d->save();
                    return response()->json($almacen_d);
                }
            }   
        }
   }
}
