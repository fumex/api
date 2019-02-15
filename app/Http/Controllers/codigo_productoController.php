<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\codigo_producto;
use App\detalle_almacen;
use App\PagoDetalle;

class codigo_productoController extends Controller
{
    public function insertarvendible(Request $request){
        $json=$request->input('json',null);
        $params=json_decode($json);
        $nombre_producto=(!is_null($json) && isset($params->nombre_producto)) ? $params->nombre_producto : null;
        $id_producto=(!is_null($json) && isset($params->id_producto)) ? $params->id_producto : null;
        $numero_de_serie=(!is_null($json) && isset($params->numero_de_serie)) ? $params->numero_de_serie : null;
        $codigo_interno=(!is_null($json) && isset($params->codigo_interno)) ? $params->codigo_interno : null;
        //$id_pago=(!is_null($json) && isset($params->id_pago)) ? $params->id_pago : null;
        $vendible=(!is_null($json) && isset($params->vendible)) ? $params->vendible : null;
        $fecha_vencimiento=(!is_null($json) && isset($params->fecha_vencimiento)) ? $params->fecha_vencimiento : null;
        $id_almacen=(!is_null($json) && isset($params->id_almacen)) ? $params->id_almacen : null;
        $id_usuario=(!is_null($json) && isset($params->id_usuario)) ? $params->id_usuario : null;
        
        $id_detalle_pago=PagoDetalle::get()->last();
        $prefigocodigo=null;
        $numeromedio=0;
        $busquedaprefijo=null;
        $codigo_automatico=null;
        $arregloprefijo=null;
        $i=0;
        $terminal=null;
        
        if(!is_null($id_producto) || !is_null($id_almacen) || !is_null($id_usuario) || !is_null($vendible)){
            if($vendible==true){
                $terminal="T";
                $buscardetallealmacen=detalle_almacen::where('id_producto',$id_producto)->where('id_almacen',$id_almacen)->where('vendible',true)->get()->last();
                $id_detalle_almacen=$buscardetallealmacen->id;
                $buscarentablacodigo=codigo_producto::where('id_detalle_almacen',$id_detalle_almacen)->where('vendible',true)->orderby('id')->get()->last();
            }else{
                $terminal="F";
                $buscardetallealmacen=detalle_almacen::where('id_producto',$id_producto)->where('id_almacen',$id_almacen)->where('vendible',false)->get()->last();
                $id_detalle_almacen=$buscardetallealmacen->id;
                $buscarentablacodigo=codigo_producto::where('id_detalle_almacen',$id_detalle_almacen)->where('vendible',false)->orderby('id')->get()->last();
            }
            
            if(@count($buscarentablacodigo)==0){
                $arregloproduto = str_split($nombre_producto);
                $numeromedio=round((count($arregloproduto)-1)/2) ;
                
                do {
                    
                    $prefigocodigo=$arregloproduto[0].$arregloproduto[($numeromedio)].$id_almacen.$arregloproduto[(count($arregloproduto)-1)].$terminal; 
                    $busquedaprefijo=codigo_producto::where('codigo_automatico', 'like', '%' .strtoupper($prefigocodigo). '%')->get();
                    
                    if($numeromedio==count($arregloproduto)-1){
                        $numeromedio=0;
                    }
                    $numeromedio++;
                }while(@count($busquedaprefijo)>0 || $arregloproduto[($numeromedio-1)]==" ");

                $codigo_automatico=strtoupper($prefigocodigo).'0000001';
            }else{
                $busquedaprefijo=$buscarentablacodigo['codigo_automatico'];
                $arregloprefijo=str_split($busquedaprefijo,5);
                $numero=(int)($arregloprefijo[1].$arregloprefijo[2])*1+1;

                
                $codigo_automatico=$arregloprefijo[0];

                while($i<=6-(strlen($numero))){
                    $codigo_automatico.="0";
                    $i++;
                };
                $codigo_automatico.=$numero;    
            }
            $cod_pro=new codigo_producto();
            $cod_pro->id_detalle_almacen=$id_detalle_almacen;    
            $cod_pro->numero_de_serie=$numero_de_serie;
            $cod_pro->codigo_interno=$codigo_interno;
            $cod_pro->codigo_automatico=$codigo_automatico;
            $cod_pro->id_usuario=$id_usuario;
            $cod_pro->id_detalle_pago=$id_detalle_pago['id'];
            $cod_pro->vendible=$vendible;
            $cod_pro->fecha_vencimiento=$fecha_vencimiento;
            $cod_pro->estado=true;

            $cod_pro->save();
            $data =array(
                'status'=>'succes',
                'code'=>200,
                'mensage'=>'registrado',
            );
            return response()->json($data,200);

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
    
    public function seleccionarcodigoporcajas($id){
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
