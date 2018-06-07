<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Inventario;
use App\Movimiento;
use App\Almacenes;
use App\detalle_almacen;
use App\Productos;
use Illuminate\Support\Facades\DB;

class InventarioController extends Controller
{
    public function ver(){
        $listar2=Inventario::join('almacenes','inventarios.id_almacen','=','almacenes.id')
        ->join('productos','inventarios.id_producto','=','productos.id')
        ->select('inventarios.id','inventarios.fecha','almacenes.nombre','productos.nombre_producto','inventarios.descripcion','inventarios.precio','tipo_movimiento','inventarios.cantidad')
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
        $usuario=(!is_null($json) && isset($params->usuario)) ? $params->usuario : null;

        $presio=detalle_almacen::where('id_almacen','=',$id_almacen)->where("id_producto",'=',$id_producto)->get()->last();
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
            
            $Inventario->precio=$presio['precio_compra'];
            if(!is_null($opciones) && !is_null($descripcion) ) {
                $Inventario->descripcion=$opciones." : ".$descripcion ;
            }else{
                if(!is_null($opciones))
                {
                    $Inventario->descripcion=$descripcion;
                }else{
                    $Inventario->descripcion=$opciones;
                }
                
            }

            $Inventario->save();

        $numero=detalle_almacen::where('id_almacen','=',$id_almacen)->where("id_producto",'=',$id_producto)->value('stock');
        $stockactual=detalle_almacen::where('id_almacen','=',$id_almacen)->where("id_producto",'=',$id_producto)->value('stock');
        $precioctual=detalle_almacen::where('id_almacen','=',$id_almacen)->where("id_producto",'=',$id_producto)->value('precio_compra');
        
        if($tipo_movimiento!='1')
        {
            $resultado=$numero+$cantidad;
            $costoactualizado=(($stockactual *$precioctual)+($cantidad*$presio['precio_compra']))/($stockactual+$cantidad);
                       
        }else{
            $resultado=$numero-$cantidad;
            $costoactualizado=(($stockactual *$precioctual)-($cantidad*$presio['precio_compra']))/($stockactual-$cantidad);
            
        }
        $detalle=detalle_almacen::where('id_almacen','=',$id_almacen)->where("id_producto",'=',$id_producto)->update(['precio_compra'=>$costoactualizado]);
        $detalle=detalle_almacen::where('id_almacen','=',$id_almacen)->where("id_producto",'=',$id_producto)->update(['stock'=>$resultado]);


//----------movimientos--------------------------------//-----------------------------------------------------------------------------------------------
//----------movimientos--------------------------------//-----------------------------------------------------------------------------------------------
        $id_tabla=Inventario::get()->last();
        $almacen_nombre=Almacenes::where('id','=',$id_almacen)->get()->first();
        $productos_nombre=Productos::where('id','=',$id_producto)->get()->first();
        $movimiento=new Movimiento();
        $movimiento->tabla_nombre='inventarios';
        $movimiento->id_tabla=$id_tabla['id'];
        $movimiento->almacen_nombre=$almacen_nombre['nombre'];
        $movimiento->productos_nombre=$productos_nombre['nombre_producto'];
        $movimiento->id_usuario=$usuario;
        $movimiento->valor=$resultado;
        $movimiento->valor_antiguo=$stockactual;
        $movimiento->save();
//----------movimientos--------------------------------//-----------------------------------------------------------------------------------------------
//----------movimientos--------------------------------//-----------------------------------------------------------------------------------------------

            $data =array(
                    'resultado'=>$costoactualizado.' : (('.$stockactual.'*'.$precioctual.')-('.$cantidad.'*'.$presio['precio_compra'].'))/('.$stockactual.'-'.$cantidad.')',
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
         if(!is_null($escoja) && $opciones=='tranferencia entre almacenes' ){
            $otro_movimiento=2;
            $otronumero=0;
            $isset_dettalle=detalle_almacen::where('id_almacen','=',$escoja)->where("id_producto",'=',$id_producto)->first();
                if(@count($isset_dettalle)==0){
                    $d_almacen=new detalle_almacen();

                    $d_almacen->id_almacen=$escoja;
                    $d_almacen->id_producto=$id_producto;
                    $d_almacen->stock=$cantidad;
                    $d_almacen->codigo="aun no ay";
                    $d_almacen->precio_compra=$presio['precio_compra'];
                    $d_almacen->precio_venta=0;
                    $d_almacen->save();
                    $data2 =array(
                        'status'=>'echo'
                     );
                }else{
                    $otronumero=detalle_almacen::where('id_almacen','=',$escoja)->where("id_producto",'=',$id_producto)->value('stock');
                    $otrostockactual=detalle_almacen::where('id_almacen','=',$escoja)->where("id_producto",'=',$id_producto)->value('stock');
                    $otroprecioctual=detalle_almacen::where('id_almacen','=',$escoja)->where("id_producto",'=',$id_producto)->value('precio_compra');
                    $presio2=detalle_almacen::where('id_almacen','=',$escoja)->where("id_producto",'=',$id_producto)->get()->last();
                    
                    if($tipo_movimiento!='1'){
                        $otroresultado=$otronumero-$cantidad;
                        $otrocostoactualizado=(($otrostockactual *$otroprecioctual)-($cantidad*$presio['precio_compra']))/($otrostockactual-$cantidad);
                        $otro_movimiento=1;
                    }else{
                        $otroresultado=$otronumero+$cantidad;
                        $otrocostoactualizado=($otrostockactual *$otroprecioctual+$cantidad*$presio['precio_compra'])/($otrostockactual+$cantidad);
                        $otro_movimiento=2;
                    }
                    $detalle=detalle_almacen::where('id_almacen','=',$escoja)->where("id_producto",'=',$id_producto)->update(['precio_compra'=>$otrocostoactualizado]);
                    $detalle=detalle_almacen::where('id_almacen','=',$escoja)->where("id_producto",'=',$id_producto)->update(['stock'=>$otroresultado]);
                }
            $otroinventario=new Inventario();
            $otroinventario->id_almacen=$escoja;
            $otroinventario->id_producto=$id_producto;
            $otroinventario->cantidad=$cantidad;
            $otroinventario->precio=$presio['precio_compra'];

            if(!is_null($opciones) && !is_null($descripcion)) {
                $otroinventario->descripcion=$opciones.' : ' .$descripcion ;
            }else{
                if(!is_null($opciones))
                {
                    $otroinventario->descripcion=$descripcion;
                }else{
                    $otroinventario->descripcion=$opciones;
                }
                
            }
            $otroinventario->tipo_movimiento=$otro_movimiento;
            $otroinventario->save();


//----------------------------movimiento si ay tranferencia entyre almacnes---------------------------------------------------------------------------
//----------------------------movimiento si ay tranferencia entyre almacnes---------------------------------------------------------------------------
            $id_otratabla=Inventario::get()->last();
            $otro_almacen=Almacenes::where('id','=',$escoja)->get()->first();
            $otromovimiento=new Movimiento();
            $otromovimiento->tabla_nombre='inventarios';
            $otromovimiento->id_tabla=$id_otratabla['id'];
            $otromovimiento->almacen_nombre=$otro_almacen['nombre'];
            $otromovimiento->productos_nombre=$productos_nombre['nombre_producto'];
            $otromovimiento->id_usuario=$usuario;
            $otromovimiento->tipo_movimiento=$otro_movimiento;
            $otromovimiento->valor=$otroresultado;
            $otromovimiento->valor_antiguo=$otronumero;
            $otromovimiento->save();
//----------------------------movimiento si ay tranferencia entyre almacnes---------------------------------------------------------------------------
//----------------------------movimiento si ay tranferencia entyre almacnes---------------------------------------------------------------------------


            $data =array(
                'status'=>'succes',
                'code'=>200,
                'mensage'=>'se guardo el otro'
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

       public function seleccionar(Request $request){
        $json=$request->input('json');
        $params=json_decode($json);

        $id_almacen	=(!is_null($json) && isset($params->id_almacen)) ? $params->id_almacen : null;
        $id_producto=(!is_null($json) && isset($params->id_producto)) ? $params->id_producto : null;

        $listar2=Inventario::join('almacenes','inventarios.id_almacen','=','almacenes.id')
        ->join('productos','inventarios.id_producto','=','productos.id')
        ->select('inventarios.id','inventarios.fecha','almacenes.nombre','productos.nombre_producto','inventarios.descripcion','inventarios.precio','tipo_movimiento','inventarios.cantidad')
        ->where("id_producto",'=',$id_producto)
        ->where('id_almacen','=',$id_almacen)
        ->get();
        return $listar2;
       }

    public function eliminar($id){
        $Inventario=Inventario::find($id);
    	$Inventario->delete();
    	return $Inventario;
       }
    public function mostrarproductos($id){
        $listar2=detalle_almacen::join('productos','detalle_almacen.id_producto','=','productos.id')
        ->join('categorias','productos.id_categoria','=','categorias.id')
        ->join('unidades','productos.id_unidad','=','unidades.id')
        ->select('productos.id','productos.nombre_producto','productos.descripcion','unidades.unidad','productos.cantidad','categorias.nombre','stock')
        ->where('detalle_almacen.id_almacen',$id)
        ->get();
        return $listar2;

    }
    
    public function prueba(){
        $i= Inventario::get()->last();
        return  $i['id'];
       }

}
