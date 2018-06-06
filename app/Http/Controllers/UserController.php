<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class UserController extends Controller
{
    public function insertar(Request $request){
        $json=$request->input('json',null);
        $params=json_decode($json);

        $name=(!is_null($json) && isset($params->name)) ? $params->name : null;
        $apellidos=(!is_null($json) && isset($params->apellidos)) ? $params->apellidos : null;
        $id_documento=(!is_null($json) && isset($params->id_documento)) ? $params->id_documento : null;
        $numero_documento=(!is_null($json) && isset($params->numero_documento)) ? $params->numero_documento : null;
        $direccion=(!is_null($json) && isset($params->direccion)) ? $params->direccion : null;
        $telefono=(!is_null($json) && isset($params->telefono)) ? $params->telefono : null;
        $nacimiento=(!is_null($json) && isset($params->nacimiento)) ? $params->nacimiento : null;
        $rol=(!is_null($json) && isset($params->rol)) ? $params->rol : null;
        $email=(!is_null($json) && isset($params->email)) ? $params->email : null;
        $password=(!is_null($json) && isset($params->password)) ? $params->password : null;
        
        //$estado=(!is_null($json) && isset($params->estado)) ? $params->estado : null;
 
       $isset_user=User::where('email','=',$email)->first();
        if(@count($isset_user)==0){

                $d_user=new User();
                $d_user->name=$name;
                $d_user->apellidos=$apellidos;
                $d_user->id_documento=1;
                $d_user->numero_documento=12457;
                $d_user->direccion='calleja';
                $d_user->telefono=15487542;
                $d_user->nacimiento='2018/12/12';
                $d_user->rol=$rol;
                $d_user->email=$email;
                $d_user->password=bcrypt($password);
                $d_user->save();
                $data2 =array(
                    'status'=>'succes',
                    'code'=>200,
                    'mensage'=>'sew incerto'
                );
            
        
        }else{
            $data =array(
                'status'=>'error',
                'code'=>408,
                'mensage'=>'ya existe'
            );
        }
        return response()->json($data,200);
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
}
