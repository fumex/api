<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\entidad_financiera;

class entidad_finacieraController extends Controller
{
    public function getentidad(){
    	$entidad_financiera=entidad_financiera::where('estado',true)->orderBy('id')->get();;
    	return $entidad_financiera;
	}
    public function insertar(Request $request){
        $json=$request->input('json',null);
        $params=json_decode($json);
        
		$descripcion=(!is_null($json) && isset($params->descripcion)) ? $params->descripcion : null;
		$codigo_sunat=(!is_null($json) && isset($params->codigo_sunat)) ? $params->codigo_sunat : null;
        $id_user=(!is_null($json) && isset($params->id_user)) ? $params->id_user : null;

        if(!is_null($descripcion)){
            $entidad=new entidad_financiera();
            
            $entidad->descripcion=$descripcion;
            $entidad->codigo_sunat=$codigo_sunat;
            $entidad->id_user=$id_user;
            $entidad->estado=true;

            $isset_enti=entidad_financiera::where('codigo_sunat','=',$codigo_sunat)->first();
            if(@count($isset_cate)==0){
                $entidad->save();

                $data =array(
                    'status'=>'succes',
                    'code'=>200,
                    'mensage'=>'registrado',
                );
            }else{
                $data =array(
                    'status'=>'error',
                    'code'=>300,
                    'mensage'=>'ya existe',
                    'seleccionado'=>$isset_enti['descripcion']
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
        
		$descripcion=(!is_null($json) && isset($params->descripcion)) ? $params->descripcion : null;
		$codigo_sunat=(!is_null($json) && isset($params->codigo_sunat)) ? $params->codigo_sunat : null;
     
        if(!is_null($descripcion) && !is_null($codigo_sunat)){
            $isset_enti=entidad_financiera::whereNotIn('id',[$id])->where('codigo_sunat','=',$codigo_sunat)->first();
            if(@count($isset_enti)==0){
                $entidad_financiera= entidad_financiera::where('id','=',$id)->update(['descripcion'=>$descripcion,
                'codigo_sunat'=>$codigo_sunat]);

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
                    'seleccionado'=>$isset_cate['unidad']
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
    }
    public function eliminar($id){
        $cambio=false;
        $entidad_financiera= entidad_financiera::where('id','=',$id)->update(['estado'=>$cambio]);
        return $entidad_financiera;
    }
    public function seleccionar($id){
        $entidad_financiera=entidad_financiera::find($id);
        return $entidad_financiera;
   }
}
