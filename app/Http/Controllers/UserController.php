<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Detalle_usuario;
use App\Sucursal;
use Illuminate\Support\Facades\Auth;
use Hash;
use DB;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;


class UserController extends Controller
{
    public function ver(){
        return $listar=User::where('estado',true)->get();
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
                $User= User::where('id',$id)->update(['password'=> bcrypt($nuevo)]);
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
        $imagen=(!is_null($json) && isset($params->imagen)) ? $params->imagen : null;

        $isset_user=User::where('email','=',$email)->where('estado',true)->first();
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
                $d_user->imagen=$imagen;
                
                $d_user->estado=true;
                
                if($d_user->rol=='admin'){
                    $d_user->strd=1305;
                    $d_user->save();
                    }
                if($d_user->rol=='empleado'){
                    $d_user->strd=20;
                    $d_user->save();
                }
                if($d_user->rol=='vendedor'){
                    $d_user->strd=1524;
                    $d_user->save();
                }
                if($d_user->rol=='Encargado de Almacen'){
                    $d_user->strd=8450;
                    $d_user->save();
                }
                $data =array(
                'status'=>'succes',
                    'code'=>200,
                    'mensage'=>'sew incerto'
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


    public function rol($id){
        $rol=User::where('id','=',$id)
                ->select('rol')
                ->get();

        return response()->json($rol);
    }

    public function getusuario($id){
        $User=User::find($id);
        
        return response()->json($User);
    }
    public function delete($id){
        $cambio=false;
        $User=User::where('id',$id)->update(['estado'=>$cambio]);
    	return $User;


    }
    public function getimages($name){
        
        $file =Storage::disk('usuarios')->get($name);
        return new Response($file,200);
    }
    public function upimagenes(Request $request){

        if ($request->hasFile('photo')) {
            $file = $request->photo;
            $image=$request->file('photo');
            $path=$image->getClientOriginalExtension();

            $now = new \DateTime();
            \Storage::disk('usuarios')->put($now->format('d_m_Y_H_i_s').$request->dni.".".$path,\File::get($image));
            //$path = $request->photo->store('images');
            $data =array(
                'status'=>'succes',
                'code'=>200,
                'mensage'=>'registrado',
                'name'=>$now->format('d_m_Y_H_i_s'),
                'extencion'=>$path
            );
            return response()->json($data,200);

        } 
        $data =array(
            'status'=>'error',
            'code'=>404,
            'mensage'=>'no se guardo la imagen',
        );
        return response()->json($data,200);
    }
   public function updateimages($id, Request $request){
        $json=$request->input('json',null);
        $params=json_decode($json);

        $imagen=(!is_null($json) && isset($params->imagen)) ? $params->imagen : null;
        $User= User::where('id',$id)->update(['imagen'=>$imagen,]);
        $data =array(
            'status'=>'se guardo',
            'code'=>200,
            'mensage'=>'Actualizado'
        );
        
        return response()->json($data,200);
   }
   public function getusersporsucursal($id){
        $usuarios=array();
        $consulta="";
        /*return $users=Detalle_usuario::join('sucursals','detalle_usuarios.id_sucursal','=','sucursals.id')
        ->join('users','users.id','detalle_usuarios.id_user')
        //->join('tipo_documentos','users.id_documento','tipo_documentos.id')
        ->where('users.id',$id)
        ->select('sucursals.id','users.name')
        //->select('users.id','users.name','users.apellidos','tipo_documentos.documento','users.numero_documento','users.telefono','users.direccion','users.imagen')
        ->get();*/

        $sucursal=Detalle_usuario::where('id_user',$id)->where('permiso',true)->get();
       
        foreach ($sucursal as $s) { 
            $consulta=Detalle_usuario::join('users','detalle_usuarios.id_user','=','users.id')
            ->join('tipo_documentos','users.id_documento','tipo_documentos.id')
            ->where('detalle_usuarios.id_sucursal',$s->id_sucursal)
            ->where('detalle_usuarios.permiso',true)
            ->select('users.id','users.name','users.apellidos','tipo_documentos.documento','users.numero_documento','users.telefono','users.direccion','users.imagen')
            ->get();
            array_push($usuarios,$consulta);
        } 
        return response()->json($usuarios,200);
   }

}
