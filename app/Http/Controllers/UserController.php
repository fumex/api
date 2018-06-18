<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;
use Hash;
use DB;

class UserController extends Controller
{
    public function ver(){
        return $listar=User::where('estado','habilitado')->get();
    }
    public function modificarcontra($id,Request $request){
        /*if (! $user = $this->jwtAuth->parseToken()->authenticate()) {
			return response()->json(['error'=>'user_not_found'], 404);
		}*/
		$json=$request->input('json',null);
		$params=json_decode($json);
		
        $password=(!is_null($json) && isset($params->password)) ? $params->password : null;
        $email=(!is_null($json) && isset($params->email)) ? $params->email : null;
        $nuevo=(!is_null($json) && isset($params->nuevo)) ? $params->nuevo : null;

        $pas=User::where('id', $id)->get()->last();
        if(!is_null($password)  && !is_null($nuevo) ){
            if(Hash::check($password, $pas['password'])){
                $User= User::where('id',$id)->update(['password'=>bcrypt($nuevo)]);
                $data =array(
                    'status'=>'succes',
                    'code'=>200,
                    'mensage'=>'sew incerto'
                );
            }else{
                $data =array(
                    'status'=>'error',
                    'code'=>408,
                    'mensage'=>'contraseña incorrecta',
                );
            };
        }else{
            $data =array(
                'status'=>'error',
                'code'=>404,
                'mensage'=>'faltan datos',
            );
        }
        return response()->json($data,200);
	 }

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
                $d_user->id_documento=$id_documento;
                $d_user->numero_documento= $numero_documento;
                $d_user->direccion= $direccion;
                $d_user->telefono= $telefono;
                $d_user->nacimiento= $nacimiento;
                $d_user->rol=$rol;
                $d_user->email=$email;
                $d_user->password=bcrypt($password);
<<<<<<< HEAD
                $d_user->estado='hablitado';
                if($d_user->rol='admin'){
                    $d_user->strd=1305;
                    $d_user->save();
                }
                if($d_user->rol='empleado'){
                    $d_user->strd=20;
                    $d_user->save();
                }
=======
                $d_user->estado='habilitado';
                $d_user->save();
>>>>>>> aa4eb9c140d5f85e8f0cbb7761cab169db6dccc4
                $data =array(
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
        $params=json_decode($json);
        
        $name=(!is_null($json) && isset($params->name)) ? $params->name : null;
        $apellidos=(!is_null($json) && isset($params->apellidos)) ? $params->apellidos : null;
        $id_documento=(!is_null($json) && isset($params->id_documento)) ? $params->id_documento : null;
        $numero_documento=(!is_null($json) && isset($params->numero_documento)) ? $params->numero_documento : null;
        $direccion=(!is_null($json) && isset($params->direccion)) ? $params->direccion : null;
        $telefono=(!is_null($json) && isset($params->telefono)) ? $params->telefono : null;
        $nacimiento=(!is_null($json) && isset($params->nacimiento)) ? $params->nacimiento : null;
        $rol=(!is_null($json) && isset($params->rol)) ? $params->rol : null;

          //guardar
            $User= User::where('id',$id)->update(['name'=>$name,
            'apellidos'=>$apellidos,
            'id_documento'=>$id_documento,
            'numero_documento'=>$numero_documento,
            'direccion'=>$direccion,
            'telefono'=>$telefono,
            'nacimiento'=>$nacimiento,
            'rol'=>$rol,]);
            

            $data =array(
                'status'=>'succes',
                'code'=>200,
                'mensage'=>'registrado'
            );
            
        return response()->json($data,200);

       }
<<<<<<< HEAD

    public function rol($id){
        $rol=User::where('id','=',$id)
                ->select('rol')
                ->get();

        return response()->json($rol);
=======
    public function getusuario($id){
        $User=User::find($id);
        
        return response()->json($User);
    }
    public function delete($id){
        $cambio='desabilitado';
        $User=User::where('id',$id)->update(['estado'=>$cambio]);
    	return $User;

>>>>>>> aa4eb9c140d5f85e8f0cbb7761cab169db6dccc4
    }
}
