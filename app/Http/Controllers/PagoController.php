<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Pago;
use App\Proveedor;
use App\PagoDetalle;
use App\detalle_almacen;
use App\codigo_producto;
use DB;
use App\movimientos_detalle_almacen;
use App\Inventario;
use App\Movimiento;
use App\Almacenes;
use App\TipoDocumento;
use App\Productos;
use App\User;
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
        $user=User::where('id',$id)->get()->last();
        if($user['superad']==true){
            $pagos=DB::table('pagos')
            ->join('proveedors','pagos.id_proveedor','=','proveedors.id')
            ->where('pagos.estado','=',true)
            ->get();
            return response()->json($pagos);
        }else{
            $pagos=DB::table('pagos')
            ->join('proveedors','pagos.id_proveedor','=','proveedors.id')
            ->join('tipo_documentos','pagos.id_documento','=','tipo_documentos.id')
            ->join('almacenes','pagos.id_almacen','=','almacenes.id')
            ->join('sucursals','almacenes.id','=','sucursals.id_almacen')
            ->join('detalle_usuarios','sucursals.id','=','detalle_usuarios.id_sucursal')
            ->select('pagos.id','pagos.code','pagos.id_proveedor','proveedors.nombre_proveedor','tipo_documentos.documento','pagos.nroBoleta','almacenes.nombre','pagos.tipoPago','pagos.subtotal','pagos.igv','pagos.exonerados','pagos.gravados','pagos.otro','pagos.fecha')
            ->where('detalle_usuarios.id_user','=',$id)
            ->where('detalle_usuarios.permiso','=',true)
            ->where('pagos.estado','=',true)
            ->get();
            return response()->json($pagos);
        }
      
    }
//-------------------------------------------------Pago detalles--------------------------------------------
    public function getPagoDetalle($code){
        $pago_d=DB::table('pago_detalles')
                    ->join('pagos','pago_detalles.id_pago','=','pagos.code')
                    ->join('proveedors','pagos.id_proveedor','=','proveedors.id')
                    ->join('productos','pago_detalles.id_producto','=','productos.id')
                    ->join('unidades','productos.id_unidad','=','unidades.id')
                    ->join('almacenes','pagos.id_almacen','=','almacenes.id')
                    ->select('pago_detalles.id','pago_detalles.id_pago','pago_detalles.id_producto','productos.nombre_producto','pagos.id_almacen','almacenes.nombre','pago_detalles.cantidad','pago_detalles.precio_unitario','unidades.unidad','productos.marca','productos.modelo','productos.observaciones')
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

    public function getdetallepagos($id){
        return $pago_d=PagoDetalle::where('id_pago',$id)->select('id')->get();
    }

    public function deletePagoDetalle($id){
        $pago_d=PagoDetalle::find($id);
        $cod=$pago_d['id_pago'];
        $pago_d->estado=false;
        $pago_d->save();
        $id_almacen=$pago['id_almacen'];
        $editarcodigos=codigo_producto::where('id_detalle_pago',$id)->update(['estado'=>false,'accion'=>'Devolucion']);

        $data =array(
            'status'=>'succes',
            'code'=>200,
            'mensage'=>'se inhabilito'
        );
        
        return response()->json($data,200);

    }
    public function borrardealmacen($id){
        $pago_d=PagoDetalle::find($id);
       
        $cod=$pago_d['id_pago'];
        $id_producto=$pago_d['id_producto'];

        $pago=Pago::where('code','=',$cod)->first();
        $getmov=Movimiento::where('id_tabla',$pago['id'])->where('tabla_nombre','Pagos')->get()->last();
        $usuario=$getmov['id_usuario'];

        $cantidad=$pago_d['cantidad'];

        $codigos=codigo_producto::where('id_detalle_pago',$id)->get()->last();
        $almacen_d=detalle_almacen::where('id','=',$codigos['id_detalle_almacen'])->first();
        
        $stockactual=$almacen_d['stock'];
        $precioctual=$almacen_d['precio_compra'];

        $m_d_a_ultimo=movimientos_detalle_almacen::where('id_detalle_almacen',$almacen_d['id'])->where('created_at','<',$pago['created_at'])->get()->last();
        $pre_comp=$pago_d['precio_unitario'];
        //echo $stockactual.'-'.$precioctual.'-'.$cantidad.'-'.$pre_comp;
        $costoactualizado=(($stockactual *$precioctual)-($cantidad*$pre_comp))/($stockactual-$cantidad);
        
        $costoguardado=round($costoactualizado,2);
        if($costoguardado!=$almacen_d['precio_compra']){
            
            $m_d_almacen=new movimientos_detalle_almacen();
            $m_d_almacen->id_detalle_almacen=$almacen_d['id'];
            $m_d_almacen->descuento_anterior=$m_d_a_ultimo['descuento_anterior'];
            $m_d_almacen->descuento_actual=$m_d_a_ultimo['descuento_actual'];
            $m_d_almacen->precio_anterior=$m_d_a_ultimo['precio_anterior'];
            $m_d_almacen->precio_actual=$m_d_a_ultimo['precio_actual'];
            $m_d_almacen->precio_compra_actual=$costoguardado;
            $m_d_almacen->precio_compra_anterior=$almacen_d['precio_compra'];
            $m_d_almacen->id_usuario=$usuario;
            $m_d_almacen->save();
        }
        $almacen_d->stock=$almacen_d['stock']-$cantidad;
        $almacen_d->precio_compra=$costoguardado;  
        $almacen_d->save();
        $data =array(
            'status'=>'succes',
            'code'=>200,
            'mensage'=>'se modifico'
        );
        
        return response()->json($data,200);
    }

    public function insertarmodeinven($id){
       
        $pago_detalle=PagoDetalle::find($id);
        $codigos=codigo_producto::where('id_detalle_pago',$id)->get()->last();
        $pago=Pago::where('code',$pago_detalle['id_pago'])->get()->last();
        $getmov=Movimiento::where('id_tabla',$pago['id'])->where('tabla_nombre','Pagos')->get()->last();
        
        $id_producto=$pago_detalle['id_producto'];
        $cantidad=$pago_detalle['cantidad'];
        $precio=$pago_detalle['precio_unitario'];
        $usuario=$getmov['id_usuario'];

        $tipodocumento=$pago['id_documento'];
        $nombretipodocumento=TipoDocumento::where('id','=',$tipodocumento)->value('documento');
        $Inventario=new Inventario();
        $Inventario->id_almacen=$pago['id_almacen'];
        $Inventario->id_producto=$id_producto;
        $Inventario->descripcion="Devolución :Compra ".$nombretipodocumento." ".$pago['nroBoleta'];
        $Inventario->tipo_movimiento=1;
        $Inventario->cantidad=$cantidad;
        $Inventario->precio=$precio; 
        $Inventario->save();

        $d_almace=detalle_almacen::where('id','=',$codigos['id_detalle_almacen'])->first();
        $id_tabla=Inventario::get()->last();
        $almacen_nombre=Almacenes::where('id','=',$pago['id_almacen'])->get()->first();
        $cantmovimiento=Movimiento::where('productos_id',$id_producto)->get()->last();
        $productos_nombre=Productos::where('id','=',$id_producto)->get()->first();
        
        $movimiento=new Movimiento();
        $movimiento->tabla_nombre='Pagos';
        $movimiento->id_tabla=$pago['id'];
        $movimiento->almacen_nombre=$almacen_nombre['nombre'];
        $movimiento->productos_nombre=$productos_nombre['nombre_producto'];
        $movimiento->productos_id=$id_producto;
        $movimiento->id_usuario=$usuario;
        $movimiento->valor=$cantmovimiento['valor']-$cantidad;
        $movimiento->valor_antiguo=$cantmovimiento['valor'];
        $movimiento->save();
        
        $data =array(
            'status'=>'succes',
            'code'=>200,
            'mensage'=>'se inserto'
        );
        
        return response()->json($data,200);
    }
   public function getpagosconusuario($id){
       return $pago=Pago::join('movimientos','pagos.id','=','movimientos.id_tabla')
       ->join('users','movimientos.id_usuario','=','users.id')
       ->where('tabla_nombre','Pagos')
       ->select('users.id','users.name','users.apellidos')
       ->get();
   }
   
}
