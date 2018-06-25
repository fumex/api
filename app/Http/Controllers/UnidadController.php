<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Unidad;


class UnidadController extends Controller
{
    public function addUnidad(Request $request){
    	$this->validate($request,[
			'unidad'=>'required',
			'abreviacion'=>'required',
    	]);
    	$create=Unidad::create($request->all());
    	return response()->json($create);
    }
    public function getUnidad(){
    	$unidad=Unidad::all();
    	return $unidad;
	}
    public function insertar(Request $request){
        $json=$request->input('json',null);
        $params=json_decode($json);
        
		$unidad	=(!is_null($json) && isset($params->unidad)) ? $params->unidad : null;
		$abreviacion=(!is_null($json) && isset($params->abreviacion)) ? $params->abreviacion : null;
        $id_user=(!is_null($json) && isset($params->id_user)) ? $params->id_user : null;
        

        if(!is_null($unidad)){
            $Unidad=new Unidad();
            
            $Unidad->unidad=$unidad;
            $Unidad->abreviacion=$abreviacion;
            $Unidad->id_user=$id_user;

            $isset_cate=Unidad::where('abreviacion','=',$abreviacion)->first();
            if(@count($isset_cate)==0){
                $Unidad->save();

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
        /*$categoria=Categoria::create($request->all());
        return $categoria;*/
    }
       
    public function modificar($id,Request $request){
        $json=$request->input('json',null);
        $params=json_decode($json);
        
        $unidad	=(!is_null($json) && isset($params->unidad)) ? $params->unidad : null;
		$abreviacion=(!is_null($json) && isset($params->abreviacion)) ? $params->abreviacion : null;
        $id_user=(!is_null($json) && isset($params->id_user)) ? $params->id_user : null;
     
        if(!is_null($unidad)){
            $isset_cate=Unidad::whereNotIn('id',[$id])->where('abreviacion','=',$abreviacion)->first();
            if(@count($isset_cate)==0){
                $Unidad= Unidad::where('id','=',$id)->update(['unidad'=>$unidad,'abreviacion'=>$abreviacion,'id_user'=>$id_user]);

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

       public function seleccionar($id){
        $Unidad=Unidad::find($id);
        return $Unidad;
       }
}
