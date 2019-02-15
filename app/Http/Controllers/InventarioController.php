<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Inventario;
use App\Movimiento;
use App\Almacenes;
use App\detalle_almacen;
use App\Productos;
use App\Pago;
use App\TipoDocumento;
use Illuminate\Support\Facades\DB;
use App\movimientos_detalle_almacen;

class InventarioController extends Controller
{
    public function ver(){
        $listar2=Inventario::join('almacenes','inventarios.id_almacen','=','almacenes.id')
        ->join('productos','inventarios.id_producto','=','productos.id')
        ->select('inventarios.id','almacenes.nombre','productos.nombre_producto','inventarios.descripcion','inventarios.precio','tipo_movimiento','inventarios.cantidad','inventarios.created_at')
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
        $cantmovimiento=Movimiento::where('productos_id',$id_producto)->get()->last();
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
            $precioredonde=round($presio['precio_compra'] * 100) / 100; 
            $Inventario->precio=$precioredonde;
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
        $detalle_almacen=detalle_almacen::where('id_almacen','=',$id_almacen)->where("id_producto",'=',$id_producto)->get()->last();
        if($tipo_movimiento!='1')
        {
            $resultado=$numero+$cantidad;
            $costoactualizado=(($stockactual *$precioctual)+($cantidad*$presio['precio_compra']))/($stockactual+$cantidad);
                       
        }else{
            $resultado=$numero-$cantidad;
            $costoactualizado=(($stockactual *$precioctual)-($cantidad*$presio['precio_compra']))/($stockactual-$cantidad);
            
        }
        $costoredondeado=round($costoactualizado * 100) / 100; 
        $m_d_a_ultimo=movimientos_detalle_almacen::where('id_detalle_almacen',$detalle_almacen['id'])->get()->last();

        if($costoredondeado!=$detalle_almacen['precio_compra']){
            $m_d_almacen=new movimientos_detalle_almacen();
            $m_d_almacen->id_detalle_almacen=$detalle_almacen['id'];
            $m_d_almacen->descuento_anterior=$m_d_a_ultimo['descuento_anterior'];
            $m_d_almacen->descuento_actual=$m_d_a_ultimo['descuento_actual'];
            $m_d_almacen->precio_anterior=$m_d_a_ultimo['precio_anterior'];
            $m_d_almacen->precio_actual=$m_d_a_ultimo['precio_actual'];
            $m_d_almacen->precio_compra_actual=$costoredondeado;
            $m_d_almacen->precio_compra_anterior=$detalle_almacen['precio_compra'];
            $m_d_almacen->save();
        }

        $detalle=detalle_almacen::where('id_almacen','=',$id_almacen)->where("id_producto",'=',$id_producto)->update(['precio_compra'=>$costoredondeado]);
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
        $movimiento->id_usuario=$usuario;
        if(@count($cantmovimiento)==0){
            $movimiento->valor=$cantidad;
            $movimiento->valor_antiguo=null;
        }else{
            $movimiento->valor=$cantmovimiento['valor']+$cantidad;
            $movimiento->valor_antiguo=$cantmovimiento['valor'];
        }
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
        $m_d_a_ultimootro=null;
        if(!is_null($escoja) && $opciones=='tranferencia entre almacenes' ){
            $otro_movimiento=2;
            $otronumero=0;
            $otroresultado=0;
            
            $detalle_almacenotro=detalle_almacen::where('id_almacen','=',$escoja)->where("id_producto",'=',$id_producto)->get()->last();
                if(@count($isset_dettalle)==0){
                    $d_almacen=new detalle_almacen();

                    $d_almacen->id_almacen=$escoja;
                    $d_almacen->id_producto=$id_producto;
                    $d_almacen->stock=$cantidad;
                    $d_almacen->codigo="aun no ay";
                    $d_almacen->precio_compra=$presio['precio_compra'];
                    $d_almacen->precio_venta=0;
                    $d_almacen->save();

                    $detallealmacenactual=detalle_almacen::where('id_almacen','=',$escoja)->where("id_producto",'=',$id_producto)->first();
//------------------------------------------guardar m_detalle_almacen------------------------------------------------------------
                    $m_d_almacen=new movimientos_detalle_almacen();
                    $m_d_almacen->id_detalle_almacen=$detallealmacenactual['id'];
                    $m_d_almacen->descuento_anterior=0;
                    $m_d_almacen->descuento_actual=0;
                    $m_d_almacen->precio_anterior=0;
                    $m_d_almacen->precio_actual=0;
                    $m_d_almacen->precio_compra_actual=$detallealmacenactual['precio_compra'];
                    $m_d_almacen->precio_compra_anterior=0;
                    $m_d_almacen->save();
//---------------------------------------------------------------------------------------------------------------------------------
                    $data2 =array(
                        'status'=>'echo'
                     );
                }else{
                    $otronumero=detalle_almacen::where('id_almacen','=',$escoja)->where("id_producto",'=',$id_producto)->value('stock');
                    $otrostockactual=detalle_almacen::where('id_almacen','=',$escoja)->where("id_producto",'=',$id_producto)->value('stock');
                    $isset_dettalle=detalle_almacen::where('id_almacen','=',$escoja)->where("id_producto",'=',$id_producto)->first();
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
                    $otrocostoredondeado=round($otrocostoactualizado * 100) / 100; 
                    $m_d_a_ultimootro=movimientos_detalle_almacen::where('id_detalle_almacen',$detalle_almacenotro['id'])->get()->last();

                    if($otrocostoredondeado!=$detalle_almacenotro['precio_compra']){
                        $m_d_almacen=new movimientos_detalle_almacen();
                        $m_d_almacen->id_detalle_almacen=$detalle_almacenotro['id'];
                        $m_d_almacen->descuento_anterior=$m_d_a_ultimootro['descuento_anterior'];
                        $m_d_almacen->descuento_actual=$m_d_a_ultimootro['descuento_actual'];
                        $m_d_almacen->precio_anterior=$m_d_a_ultimootro['precio_anterior'];
                        $m_d_almacen->precio_actual=$m_d_a_ultimootro['precio_actual'];
                        $m_d_almacen->precio_compra_actual=$otrocostoredondeado;
                        $m_d_almacen->precio_compra_anterior=$detalle_almacenotro['precio_compra'];
                        $m_d_almacen->save();
                    }

                    $detalle=detalle_almacen::where('id_almacen','=',$escoja)->where("id_producto",'=',$id_producto)->update(['precio_compra'=>$otrocostoredondeado]);
                    $detalle=detalle_almacen::where('id_almacen','=',$escoja)->where("id_producto",'=',$id_producto)->update(['stock'=>$otroresultado]);
                }
            $otroinventario=new Inventario();
            $otroinventario->id_almacen=$escoja;
            $otroinventario->id_producto=$id_producto;
            $otroinventario->cantidad=$cantidad;
            $otropecioredondeado=round($presio['precio_compra'] * 100) / 100; 
            $otroinventario->precio=$otropecioredondeado;

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
            $otromovimiento->productos_id=$id_producto;
            if(@count($cantmovimiento)==0){
                $otromovimiento->valor=$cantidad;
                $otromovimiento->valor_antiguo=null;
            }else{
                $otromovimiento->valor=$cantmovimiento['valor']+$cantidad;
                $otromovimiento->valor_antiguo=$cantmovimiento['valor'];
            }
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
        ->select('inventarios.id','inventarios.created_at','almacenes.nombre','productos.nombre_producto','inventarios.descripcion','inventarios.precio','tipo_movimiento','inventarios.cantidad')
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
        ->select('productos.id','productos.nombre_producto','productos.descripcion','unidades.abreviacion','categorias.nombre','stock','productos.marca','productos.modelo','productos.observaciones')
        ->where('detalle_almacen.id_almacen',$id)
        ->get();
        return $listar2;

    }
    public function insertardepagos(Request $request){
        $json=$request->input('json',null);
        $params=json_decode($json);

        $id_producto=(!is_null($json) && isset($params->id_producto)) ? $params->id_producto : null;
        $cantidad=(!is_null($json) && isset($params->cantidad)) ? $params->cantidad : null;
        $precio=(!is_null($json) && isset($params->precio)) ? $params->precio : null;
        $usuario=(!is_null($json) && isset($params->usuario)) ? $params->usuario : null;

        $pago=Pago::get()->last(); 
        $tipodocumento=$pago['id_documento'];
		$nombretipodocumento=TipoDocumento::where('id','=',$tipodocumento)->value('documento');
		$Inventario=new Inventario();
		$Inventario->id_almacen=$pago['id_almacen'];
		$Inventario->id_producto=$id_producto;
		$Inventario->descripcion="Compras ".$nombretipodocumento." ".$pago['nroBoleta'];
		$Inventario->tipo_movimiento=2;
		$Inventario->cantidad=$cantidad;
		$Inventario->precio=$precio;
        $Inventario->save();

        $d_almace=detalle_almacen::where('id_almacen','=',$pago['id_almacen'])->where('id_producto','=',$id_producto)->first();
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
        if(@count($cantmovimiento)==0){
            $movimiento->valor=$cantidad;
		    $movimiento->valor_antiguo=null;
        }else{
            $movimiento->valor=$cantmovimiento['valor']+$cantidad;
		    $movimiento->valor_antiguo=$cantmovimiento['valor'];
        }
        $movimiento->save();
        
        $data =array(
            'status'=>'succes',
            'code'=>200,
            'mensage'=>'se inserto'
        );
        
        return response()->json($data,200);
    }
    public function prueba(){
        $i= Inventario::get()->last();
        return  $i['id'];
       }

}
