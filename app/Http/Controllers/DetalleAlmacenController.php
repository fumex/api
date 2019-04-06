<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;            
use App\detalle_almacen;
use App\Detalle_usuario;
use Illuminate\Support\Facades\DB;
use App\movimientos_detalle_almacen;
use App\codigo_producto;

class DetalleAlmacenController extends Controller
{
    public function ver($id){
        $detalle_almacen=detalle_almacen::join('almacenes','detalle_almacen.id_almacen','almacenes.id')
        ->join('productos','detalle_almacen.id_producto','productos.id')
        ->where('id_almacen',$id)
        ->select('detalle_almacen.id','detalle_almacen.precio_compra','detalle_almacen.precio_venta','detalle_almacen.stock','almacenes.nombre','productos.nombre_producto','detalle_almacen.descuento_maximo')
        ->get();
        return $detalle_almacen;
    }

    public function insertar(Request $request){
        $json=$request->input('json',null);
        $params=json_decode($json);

        $id_almacen	=(!is_null($json) && isset($params->id_almacen)) ? $params->id_almacen : null;
        $codigo=(!is_null($json) && isset($params->codigo)) ? $params->codigo : null;
        $id_producto=(!is_null($json) && isset($params->id_producto)) ? $params->id_producto : null;
        $stock=(!is_null($json) && isset($params->stock)) ? $params->stock : null;
        $precio_compra=(!is_null($json) && isset($params->precio_compra)) ? $params->precio_compra : null;
        $precio_venta=(!is_null($json) && isset($params->precio_venta)) ? $params->precio_venta : null;

        if(is_null($precio_venta)){
            $precio_venta=0;
        }

        if(!is_null($id_almacen) && !is_null($id_producto)){
            $detalle_almacen=new detalle_almacen();
            $detalle_almacen->id_almacen=$id_almacen;
            $detalle_almacen->codigo=$codigo;
            $detalle_almacen->id_producto=$id_producto;
            $detalle_almacen->stock=$stock;
            $detalle_almacen->precio_compra=$precio_compra;
            $detalle_almacen->precio_venta=$precio_venta;
            $detalle_almacen->descuento_maximo=0;


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
        $params=json_decode($json);
        $descuento_maximo=(!is_null($json) && isset($params->descuento_maximo)) ? $params->descuento_maximo : null;
        $precio_venta=(!is_null($json) && isset($params->precio_venta)) ? $params->precio_venta : null;
        $id_producto=(!is_null($json) && isset($params->id_producto)) ? $params->id_producto : null;

        $movimiento=detalle_almacen::where('id',$id)->get()->last();
        $verultimomov=movimientos_detalle_almacen::where('id_detalle_almacen',$id)->get()->last();
        
        $mantenimiento=new movimientos_detalle_almacen();
        $mantenimiento->id_detalle_almacen=$id;
        $mantenimiento->id_usuario=$id_producto;
        if($movimiento['descuento_maximo']==null){
            $mantenimiento->descuento_anterior=0;
            $mantenimiento->descuento_actual=$descuento_maximo*1;
        }else{
            $mantenimiento->descuento_anterior=$movimiento['descuento_maximo'];
            $mantenimiento->descuento_actual=$descuento_maximo*1;
        }
        if($movimiento['precio_venta']==null){  
            $mantenimiento->precio_anterior=0;
            $mantenimiento->precio_actual=$precio_venta;
        }else{
            $mantenimiento->precio_anterior=$movimiento['precio_venta'];
            $mantenimiento->precio_actual=$precio_venta;
        }
        $mantenimiento->precio_compra_actual=$verultimomov['precio_compra_actual'];
        $mantenimiento->precio_compra_anterior=$verultimomov['precio_compra_actual'];
        if($descuento_maximo*1!=$movimiento['descuento_maximo']||$precio_venta!=$movimiento['precio_venta']){
            $mantenimiento->save();
        }
        

              //guardar
            $detalle_almacen= detalle_almacen::where('id',$id)->update(['precio_venta'=> $precio_venta,'descuento_maximo'=>$descuento_maximo]);

                $data =array(
                    'status'=>'succes',
                    'code'=>200,
                    'mensage'=>'registrado'
                );
            
        return response()->json($data,200);

    }

    public function seleccionar($id){   
        $detalle_almacen=detalle_almacen::find($id);
        return $detalle_almacen;
    }

    public function seleccionarporcaja($id){ 
        $productos=array();
        $i=0;
        $j=0;
        $valida=-1;
        $consulta="";
        $codigo_producto=codigo_producto::join('detalle_almacen','codigo_productos.id_detalle_almacen','detalle_almacen.id')
        ->join('almacenes','detalle_almacen.id_almacen','almacenes.id')
        ->join('productos','detalle_almacen.id_producto','productos.id')
        ->join('unidades','productos.id_unidad','=','unidades.id')
        ->join('categorias','productos.id_categoria','=','categorias.id')
        ->join('sucursals','almacenes.id','=','sucursals.id_almacen')
        ->join('cajas','sucursals.id','=','cajas.id_sucursal')
        ->where('cajas.id',$id)
        ->where('detalle_almacen.vendible',true)
        ->where('detalle_almacen.precio_venta','>',0)
        ->where('detalle_almacen.precio_venta','<>',null)
        ->where('detalle_almacen.stock','>',0)
        ->where('codigo_productos.estado',true)
        ->orderby('codigo_productos.id')
        ->select('codigo_productos.id AS id_codigo','productos.id','detalle_almacen.id AS id_detalle_almacen'
        ,'productos.descripcion','unidades.abreviacion','categorias.nombre','productos.nombre_producto'
        ,'productos.imagen','detalle_almacen.precio_venta','productos.codigo','detalle_almacen.descuento_maximo'
        ,'codigo_productos.id_detalle_almacen','codigo_productos.numero_de_serie','codigo_productos.codigo_interno'
        ,'codigo_productos.fecha_vencimiento','detalle_almacen.stock')
        ->get();

        while($i<count($codigo_producto)){
            if(count($productos)==0){
                $codigo_producto[$i]->stock=1;
                array_push($productos,$codigo_producto[$i]);
            }else{
                while($j<count($productos)){
                    if($codigo_producto[$i]->codigo_interno==$productos[$j]->codigo_interno && $codigo_producto[$i]->numero_de_serie==$productos[$j]->numero_de_serie && $codigo_producto[$i]->id_detalle_almacen==$productos[$j]->id_detalle_almacen){
                        $valida=$j;
                    }
                    $j++;
                }
                
                if($valida==-1){
                    $codigo_producto[$i]->stock=1;
                    array_push($productos,$codigo_producto[$i]);
                }else{
                    $productos[$valida]->stock+=1;
                    //array_push($productos,$codigo_producto[$i]);
                }
            }
            $valida=-1;
            $j=0;
            $i++;
        }
        return $productos;
    }

}
