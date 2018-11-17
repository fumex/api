<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\nota_credito;
use App\Venta;
use App\detalle_Ventas;
use App\Cliente;

class nota_creditoController extends Controller
{
    public function insertar(Request $request){
        $json=$request->input('json',null);
        $params=json_decode($json);
        

        $tipo_nota=(!is_null($json) && isset($params->tipo_nota)) ? $params->tipo_nota : null;
		$id_venta=(!is_null($json) && isset($params->id_venta)) ? $params->id_venta : null;
        $motivo=(!is_null($json) && isset($params->motivo)) ? $params->motivo : null;
        $correccion_ruc=(!is_null($json) && isset($params->correccion_ruc)) ? $params->correccion_ruc : null;
        $id_usuario=(!is_null($json) && isset($params->id_usuario)) ? $params->id_usuario : null;
        $serie_nota=(!is_null($json) && isset($params->serie_nota)) ? $params->serie_nota : null;
        $descuento=(!is_null($json) && isset($params->descuento)) ? $params->descuento : null;
        $serie_venta_remplazo=(!is_null($json) && isset($params->serie_venta_remplazo)) ? $params->serie_venta_remplazo : null;
        $letrado=(!is_null($json) && isset($params->letrado)) ? $params->letrado : null;

        if($tipo_nota=="01" || $tipo_nota=="02" || $tipo_nota=="06" ){
            $modificarventa=Venta::where('id',$id_venta)->update(['estado'=>false]);
        }
        $id_venta_new=null;
        if($tipo_nota=='02'){
            $oldventa=Venta::where('id',$id_venta)->get()->first();
            $old_cliente=Cliente::where('id',$oldventa['id_cliente'])->get()->last();   
            if($old_cliente['nro_documento']==$correccion_ruc){
                $data =array(
                    'status'=>'error',
                    'code'=>600,
                    'mensage'=>$old_cliente
                );
                return response()->json($data,200);
            }else{
                $newventa=new Venta();
                $new_cliente=new Cliente();
                $new_detalle_ventas=new detalle_Ventas();
                $old_detalle_venta=detalle_Ventas::where('id_venta',$oldventa['id'])->get();
                $old_cliente=Cliente::where('id',$oldventa['id_cliente'])->get()->last();   
                $buscar_cliente=Cliente::where('nro_documento',$correccion_ruc)->get();
    
                if(@count($buscar_cliente)==0){
                    $newventa->id_cliente=$oldventa['id_cliente'];
                }else{
                    $update=Cliente::where('id',$old_cliente['id'])->update(['estado'=>false]);
                    $new_cliente->nombre=$old_cliente['nombre'];
                    $new_cliente->id_documento=$old_cliente['id_documento'];
                    $new_cliente->nro_documento=$correccion_ruc;
                    $new_cliente->direccion=$old_cliente['direccion'];
                    $new_cliente->email=$old_cliente['email'];
                    $new_cliente->telefono=$old_cliente['telefono'];
                    $new_cliente->telefono2=$old_cliente['telefono2'];
                    $new_cliente->id_user=$id_usuario;
                    $new_cliente->estado=true;
                    $new_cliente->save();
                    $id_cliente_new=Cliente::orderby('id')->get()->last();
                    $newventa->id_cliente=$id_cliente_new['id'];
                }
                
                $newventa->serie_venta=$serie_venta_remplazo;
                $newventa->tarjeta=$oldventa['tarjeta'];
                $newventa->id_caja=$oldventa['id_caja'];
                $newventa->total=$oldventa['total'];
                $newventa->pago_efectivo=$oldventa['pago_efectivo'];
                $newventa->pago_tarjeta=$oldventa['pago_efectivo'];
                $newventa->estado=true;
                $newventa->id_usuario=$id_usuario;
                $newventa->id_moneda=$oldventa['id_moneda'];
                $newventa->save();
                $id_venta_new=Venta::orderby('id')->get()->last();
                foreach ($old_detalle_venta as $odv) { 
                    $new_detalle_ventas->id_venta=$id_venta_new['id'];
                    $new_detalle_ventas->cantidad=$odv->cantidad;
                    $new_detalle_ventas->precio_unitario=$odv->precio_unitario;
                    $new_detalle_ventas->id_producto=$odv->id_producto;
                    $new_detalle_ventas->igv=$odv->igv;
                    $new_detalle_ventas->isc=$odv->isc;
                    $new_detalle_ventas->otro=$odv->otro;
                    $new_detalle_ventas->descuento=$odv->descuento;
                    $new_detalle_ventas->igv_id=$odv->igv_id;
                    $new_detalle_ventas->isc_id=$odv->isc_id;
                    $new_detalle_ventas->otro_id=$odv->otro_id;
                    $new_detalle_ventas->igv_porcentage=$odv->igv_porcentage;
                    $new_detalle_ventas->isc_porcentage=$odv->isc_porcentage;
                    $new_detalle_ventas->otro_porcentage=$odv->otro_porcentage;
                    $new_detalle_ventas->estado=true;
                    $new_detalle_ventas->save(); 
                } 
                
                
            }
          
        }

        $n_credito=new nota_credito();
        $n_credito->tipo_nota=$tipo_nota;
        $n_credito->id_venta=$id_venta;
        $n_credito->motivo=$motivo;
        $n_credito->correccion_ruc=$correccion_ruc;
        $n_credito->id_usuario=$id_usuario;
        $n_credito->serie_nota=$serie_nota;
        $n_credito->descuento=$descuento;
        $n_credito->id_venta_nueva=$id_venta_new['id'];
        //$n_credito->letrado=$letrado;

        $seriesdelanota=nota_credito::where('serie_nota',$serie_nota)->first();
        if($tipo_nota!="02" || !is_null($correccion_ruc) ){
            if(@count($isset_alm)==0){
                $n_credito->save();
                if($tipo_nota=='02'){

                }
                $data =array(
                    'status'=>'succes',
                    'code'=>200,
                    'mensage'=>'guardado'
                );
                return response()->json($data,200);
            }else{
                $data =array(
                    'status'=>'error',
                    'code'=>400,
                    'mensage'=>'ya existe'
                );
                return response()->json($data,200);
            }
            
        }else{
            $data =array(
                'status'=>'error',
                'code'=>300,
                'mensage'=>$request
            );
            return response()->json($data,200);

        }
    }
    public function generarserienota($id){
        $nota_credito=nota_credito::where('serie_nota','like','%'.$id.'%')->get();
        $ulñtimanota_credito=nota_credito::where('serie_nota','like','%'.$id.'%')->get()->last();
        $data =array(
            'cantidad'=>@count($nota_credito),
            'ultimo'=>$ulñtimanota_credito['serie_nota']
        );
        return response()->json($data,200);
    }
    public function getidnota(){
        $id=nota_credito::orderby('id')->get()->last();
        return $id['id'];
    }
}
