<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\cajas;
use Illuminate\Support\Facades\DB;
use App\detalle_caja;

class CajaController extends Controller
{
    public function ver(){
        return $listar=cajas::join('sucursals','cajas.id_sucursal','=','sucursals.id')
        //->leftJoin('users','cajas.responsable','=','users.id')
        ->select('cajas.id','cajas.nombre'/*,'users.name'*/,'sucursals.nombre_sucursal','cajas.descripcion','sucursals.direccion')
        ->where('cajas.estado',true)
        ->orderBy('cajas.id')
        ->get();
    }

    /*public function cajausuario($id){
 
        $almacen=Almacenes::join('sucursals','almacenes.id','=','sucursals.id_almacen')
        ->join('detalle_usuarios','sucursals.id','=',  'detalle_usuarios.id_sucursal')
        ->where('detalle_usuarios.id_user','=',$id)
        ->where('permiso','=',true)
        ->where('almacenes.estado',true)
        ->select('almacenes.id','almacenes.nombre','almacenes.descripcion','almacenes.direccion','almacenes.telefono','almacenes.id_user')
        ->distinct('almacenes.nombre')
        ->get();

        return $almacen;
    }  */




    public function insertar(Request $request){
        $json=$request->input('json',null);
        $params=json_decode($json);
        
        $nombre	=(!is_null($json) && isset($params->nombre)) ? $params->nombre : null;
        $descripcion=(!is_null($json) && isset($params->descripcion)) ? $params->descripcion : null;
        $id_sucursal=(!is_null($json) && isset($params->id_sucursal)) ? $params->id_sucursal : null;
        $id_user=(!is_null($json) && isset($params->id_user)) ? $params->id_user : null;
        //$responsable=(!is_null($json) && isset($params->responsable)) ? $params->responsable : null;

        if(!is_null($nombre)){
            $isset_caj=cajas::where('nombre','=',$nombre)->where('estado',true)->first();
            if(@count($isset_caj)==0){
                $cajas=new cajas();

                $cajas->nombre=$nombre;
                $cajas->descripcion=$descripcion;
                $cajas->id_sucursal=$id_sucursal;
                $cajas->id_user=$id_user;
                //$cajas->responsable=$responsable;
                
                $cajas->estado=true;
    
                $cajas->save();
                $data =array(
                    'status'=>'succes',
                    'code'=>200,
                    'mensage'=>'registrado'
                );    
            }else{
                $data =array(
                    'status'=>'error',
                    'code'=>300,
                    'mensage'=>'ya existe',
                );
            }
           
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
        
        $nombre	=(!is_null($json) && isset($params->nombre)) ? $params->nombre : null;
        $descripcion=(!is_null($json) && isset($params->descripcion)) ? $params->descripcion : null;
        $id_sucursal=(!is_null($json) && isset($params->id_sucursal)) ? $params->id_sucursal : null;
        $id_user=(!is_null($json) && isset($params->id_user)) ? $params->id_user : null;
        //$responsable=(!is_null($json) && isset($params->responsable)) ? $params->responsable : null;

        $isset_caj=cajas::whereNotIn('id',[$id])->where('nombre','=',$nombre)->where('estado',true)->first();
            if(@count($isset_caj)==0){
                $cajas= cajas::where('id',$id)->update(['nombre'=>$nombre,
                'descripcion'=>$descripcion,
                'id_sucursal'=>$id_sucursal,
                //'responsable'=>$responsable,
                'id_user'=>$id_user]);

                $data =array(
                    'status'=>'succes',
                    'code'=>200,
                    'mensage'=>'registrado'
                );
            }else{
                $data =array(
                    'status'=>'error',
                    'code'=>300,
                    'mensage'=>'ya existe',
                );
            }
                
            
        return response()->json($data,200);

       }

       public function seleccionar($id){
        $cajas=cajas::find($id);
        return $cajas;
       }

    public function eliminar($id){
        $cambio=false;
        $cajas=cajas::where('id',$id)->update(['estado'=>$cambio]);
        $data =array(
            'status'=>'succes',
            'code'=>200,
            'mensage'=>'eliminado'
        );
        return response()->json($data,200);
       }
    public function getcajasporsucursal($id){
        $mostar=array();
        $consulta="";
         $listar=DB::select(DB::raw('Select  t1.id
         From cajas as t Inner join sucursals ON t.id_sucursal = sucursals.id
        Inner Join (Select  Max(id) as id,id_caja
                                      From detalle_cajas Group By id_caja ) as t1
             on (t.id=t1.id_caja)
              where t.estado=true and
               sucursals.id='.$id.' ORDER BY t1.id_caja ASC'))
               ;
        foreach ($listar as $l) { 
            $consulta=detalle_caja::join('cajas','detalle_cajas.id_caja','cajas.id')
            ->join('users','detalle_cajas.id_usuario','=','users.id')
            ->where('detalle_cajas.id',$l->id)
            ->select('users.name','users.apellidos','detalle_cajas.id','detalle_cajas.id_caja','detalle_cajas.abierta','detalle_cajas.created_at','detalle_cajas.id_usuario','detalle_cajas.monto_actual','detalle_cajas.monto_apertura','detalle_cajas.monto_cierre_efectivo','detalle_cajas.monto_cierre_tarjeta','detalle_cajas.updated_at','cajas.nombre')
            ->get();
            array_push($mostar,$consulta);
        } 
         return $mostar;   
    }

}
