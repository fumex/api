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
        $id_user =(!is_null($json) && isset($params->id_user)) ? $params->id_user : null;

        if(!is_null($nombre)){
            $categorias=new Categoria();
            $categorias->nombre=$nombre;
            $categorias->id_user=$id_user;
            $categorias->save();

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
        $edit=Categoria::find($id)->update($request->all());
        return response()->json($edit);
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
