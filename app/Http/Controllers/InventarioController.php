<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Inventario;
use App\detalle_almacen;

class InventarioController extends Controller
{
    public function ver(){
        $listar2=Inventario::join('almacenes','inventarios.id_almacen','=','almacenes.id')
        ->join('productos','inventarios.id_producto','=','productos.id')
        ->select('inventarios.id','inventarios.fecha','almacenes.nombre','productos.nombre_producto','inventarios.descripcion','tipo_movimiento','cantidad')
        ->get();
        return $listar2;
       }

    public function insertar(Request $request){
        $json=$request->input('json',null);
        $params=json_decode($json);

        $id_almacen	=(!is_null($json) && isset($params->id_almacen)) ? $params->id_almacen : null;
        $descripcion=(!is_null($json) && isset($params->descripcion)) ? $params->descripcion : null;
        $id_producto=(!is_null($json) && isset($params->id_producto)) ? $params->id_producto : null;
        $tipo_movimiento=(!is_null($json) && isset($params->tipo_movimiento)) ? $params->tipo_movimiento : null;
        $cantidad=(!is_null($json) && isset($params->cantidad)) ? $params->cantidad : null;
        $opciones=(!is_null($json) && isset($params->opciones)) ? $params->opciones : null;
        $escoja=(!is_null($json) && isset($params->escoja)) ? $params->escoja : null;

       $isset_dettalle=detalle_almacen::where('id_almacen','=',$id_almacen)->where("id_producto",'=',$id_producto)->first();
        if(@count($isset_dettalle)==0){
            $d_almacen=new detalle_almacen();
            $d_almacen->id_almacen=$id_almacen;
            $d_almacen->id_producto=$id_producto;
            $d_almacen->stock=0;
            $d_almacen->precio_compra=0;
            $d_almacen->precio_venta=0;
            $d_almacen->save();
            $data2 =array(
                'status'=>'echo'
            );
        }
                

        if(!is_null($id_almacen) && !is_null($cantidad) && !is_null($id_producto)){

            $Inventario=new Inventario();
            $Inventario->id_almacen=$id_almacen;
            $Inventario->id_producto=$id_producto;
            $Inventario->tipo_movimiento=$tipo_movimiento;
            $Inventario->cantidad=$cantidad;

            if(!is_null($opciones)) {
                $Inventario->descripcion=$opciones .$descripcion ;
            }else{
                $Inventario->descripcion=$descripcion;
            }

            $Inventario->save();
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
        $numero=detalle_almacen::where('id_almacen','=',$id_almacen)->where("id_producto",'=',$id_producto)->value('stock');
        if($tipo_movimiento!='1')
        {
            $resultado=$numero+$cantidad;
            
        }else{
            $resultado=$numero-$cantidad;
        }
        
         if(!is_null($escoja) && $opciones=='tranferencia entre almacenes' ){
            $otronumero=detalle_almacen::where('id_almacen','=',$id_almacen)->where("id_producto",'=',$id_producto)->value('stock');
            $otroinventario=new Inventario();
            $otroinventario->id_almacen=$escoja;
            $otroinventario->id_producto=$id_producto;
            $otroinventario->cantidad=$cantidad;
            if(!is_null($opciones)) {
                $otroinventario->descripcion=$opciones .$descripcion ;
            }else{
                $otroinventario->descripcion=$descripcion;
            }
            if($tipo_movimiento!='1'){
                $otroresultado=$otronumero-$cantidad;
                $otro_movimiento=1;
            }else{
                $otroresultado=$otronumero+$cantidad;
                $otro_movimiento=2;
            }
            $otroinventario->tipo_movimiento=$otro_movimiento;
            $otroinventario->save();
            $data =array(
                'status'=>'succes',
                'code'=>200,
                'mensage'=>'se guardo el otro'
            );
            $detalle=detalle_almacen::where('id_almacen','=',$id_almacen)->where("id_producto",'=',$id_producto)->update(['stock'=>$otroresultado]);
        }
        $detalle=detalle_almacen::where('id_almacen','=',$id_almacen)->where("id_producto",'=',$id_producto)->update(['stock'=>$resultado]);
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
    public function prueba(){
        $isset_dettalle=detalle_almacen::where('id_almacen','=',1)->where("id_producto",'=',1)->first();
        
       return $isset_dettalle;

    	
       }

}
