<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Permisos_roles;
use App\User;

class PermisosController extends Controller
{   
    public function insertar(Request $request){
        $json=$request->input('json',null);
        $params=json_decode($json);
        
        
        $url=(!is_null($json) && isset($params->url)) ? $params->url : null;
        $tipo_permiso=(!is_null($json) && isset($params->tipo_permiso)) ? $params->tipo_permiso : null;
        $estado=(!is_null($json) && isset($params->estado)) ? $params->estado : null;

        $id_user=User::where('estado',true)->get()->last();

        $count_per=Permisos_roles::where('id_user',$id_user)->where('url','=',$url)->where('tipo_permiso','pagina')->where('estado',true)->first();
        if(@count($count_per)==0){
            $permisos=new Permisos_roles();
            $permisos->id_user=$id_user['id'];
            $permisos->url=$url;
            $permisos->tipo_permiso='pagina';
            $permisos->estado=true;
    
            $permisos->save();
            $data =array(
                'status'=>'succes',
                'code'=>200,
                'mensage'=>'registrado'
            );    
        }
        $count_per=Permisos_roles::where('id_user',$id_user)->where('url','=',$url)->where('tipo_permiso',$tipo_permiso)->where('estado',true)->first();
        if(@count($count_per)==0 && !is_null($tipo_permiso)){
            $permisos=new Permisos_roles();
            $permisos->id_user=$id_user['id'];
            $permisos->url=$url;
            $permisos->tipo_permiso=$tipo_permiso;
            $permisos->estado=true;
    
            $permisos->save();
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
        /*$categoria=Categoria::create($request->all());
        return $categoria;*/
    }
    public function revisar(Request $request){
        $json=$request->input('json',null);
        $params=json_decode($json);
        
        $id=(!is_null($json) && isset($params->id)) ? $params->id : null;
        $url=(!is_null($json) && isset($params->url)) ? $params->url : null;

        //return $request;
        $permisos=Permisos_roles::where('url','=',$url)->where('id_user',$id)->where('estado',true)->get();
        $permisospagina=Permisos_roles::where('url','=',$url)->where('id_user',$id)->where('tipo_permiso','pagina')->where('estado',true)->get();
        if(@count($permisospagina)==0){
            return $data =array(
                'mensaje'=>false,
            );
        }else{
            return $permisos;
        }
        
       
    }     
}
