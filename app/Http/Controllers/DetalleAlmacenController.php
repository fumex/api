<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\detalle_almacen;

class DetalleAlmacenController extends Controller
{
    public function ver(){
        $listar2=detalle_almacen::join('almacenes','detalle_almacen.id_almacen','=','almacenes.id')
        ->join('productos','detalle_almacen.id_producto','=','productos.id')
        ->select('detalle_almacen.id','almacenes.nombre','productos.nombre_producto','stock','precio_compra','precio_venta')
        ->get();
        return $listar2;

       }

    public function insertar(Request $request){
        $json=$request->input('json',null);
        $params=json_decode($json);

        $id_almacen	=(!is_null($json) && isset($params->id_almacen)) ? $params->id_almacen : null;
        $id_producto=(!is_null($json) && isset($params->id_producto)) ? $params->id_producto : null;
        $stock=(!is_null($json) && isset($params->stock)) ? $params->stock : null;
        $precio_compra=(!is_null($json) && isset($params->precio_compra)) ? $params->precio_compra : null;
        $precio_venta=(!is_null($json) && isset($params->precio_venta)) ? $params->precio_venta : null;

        if(!is_null($id_almacen) && !is_null($id_producto)){
            $detalle_almacen=new detalle_almacen();

            $detalle_almacen->id_almacen=$id_almacen;
            $detalle_almacen->id_producto=$id_producto;
            $detalle_almacen->stock=$stock;
            $detalle_almacen->precio_compra=$precio_compra;
            $detalle_almacen->precio_venta=$precio_venta;

            $detalle_almacen->save();
            $data =array(
                'status'=>'succes',
                'code'=>200,
                'mensage'=>'registrado'
            );
        }else{
            $data =array(
                'status'=>'error',
                'code'=>400,
                'mensage'=>'faltan datos'
            );
        }
        return response()->json($data,200);
        /*$categoria=Categoria::create($request->all());
        return $categoria;*/
    }
       
    public function modificar($id,Request $request){
        $json=$request->input('json',null);
        $params=json_decode($json,true);
        
        $id_almacen	=(!is_null($json) && isset($params->id_almacen)) ? $params->id_almacen : null;
        $id_pago=(!is_null($json) && isset($params->id_pago)) ? $params->id_pago : null;
        $id_producto=(!is_null($json) && isset($params->id_producto)) ? $params->id_producto : null;
        $tipo_movimiento=(!is_null($json) && isset($params->tipo_movimiento)) ? $params->tipo_movimiento : null;
        $cantidad=(!is_null($json) && isset($params->cantidad)) ? $params->cantidad : null;

              //guardar
                $Inventario= Inventario::where('id',$id)->update($params);

                $data =array(
                    'status'=>'succes',
                    'code'=>200,
                    'mensage'=>'registrado'
                );
            
        return response()->json($data,200);

       }

       public function seleccionar($id){
       
        $Inventario=Inventario::find($id);
        return $Inventario;
       }

    public function eliminar($id){
        $Inventario=Inventario::find($id);
    	$Inventario->delete();
    	return $Inventario;
       }

}
