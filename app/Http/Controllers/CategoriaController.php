<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Categoria;

class CategoriaController extends Controller
{
    public function ver(){
        return $listar=Categoria::all();
       }

    public function insertar(Request $request){
        $json=$request->input('json',null);
        $params=json_decode($json);
        
        $nombre	=(!is_null($json) && isset($params->nombre)) ? $params->nombre : null;

        if(!is_null($nombre)){
            $categorias=new Categoria();
            $categorias->nombre=$nombre;

            $isset_cate=Categoria::where('nombre','=',$nombre)->first();
            if(@count($isset_cate)==0){
                //guardar
                $categorias->save();

                $data =array(
                    'status'=>'succes',
                    'code'=>200,
                    'mensage'=>'registrado'
                );
            }else{
                //no guiardar
                $data =array(
                    'status'=>'error',
                    'code'=>300,
                    'mensage'=>'ya existe'
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
              //guardar
        if(!is_null($nombre)){
            $categorias= Categoria::where('id','=',$id)->update(['nombre'=>$nombre]);

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
       }

       public function seleccionar($id){
        $categoria=Categoria::find($id);
        return $categoria;
       }

    public function eliminar($id){
        $categoria=Categoria::find($id);
    	$categoria->delete();
    	return $categoria;
       }
}
