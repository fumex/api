<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Productos;
use App\Categoria;
use App\Unidad;
use DB;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class ProductosController extends Controller
{
    public function ver(){
        $listar2=productos::join('categorias','productos.id_categoria','=','categorias.id')
        ->join('unidades','productos.id_unidad','=','unidades.id')
        ->select('productos.id','nombre_producto','categorias.nombre','descripcion','productos.cantidad','unidades.abreviacion')
        ->where('estado',true)
        ->orderBy('productos.id')
        ->get();
        //return $listar=Productos::all();
        return $listar2;
       }

    public function insertar(Request $request){
        $json=$request->input('json',null);
        $params=json_decode($json);
        
        $nombre_producto=(!is_null($json) && isset($params->nombre_producto)) ? $params->nombre_producto : null;
        $id_categoria=(!is_null($json) && isset($params->id_categoria)) ? $params->id_categoria : null;
        $descripcion=(!is_null($json) && isset($params->descripcion)) ? $params->descripcion : null;
        $id_unidad=(!is_null($json) && isset($params->id_unidad)) ? $params->id_unidad : null;
        $cantidad=(!is_null($json) && isset($params->cantidad)) ? $params->cantidad : null;
        $id_user=(!is_null($json) && isset($params->id_user)) ? $params->id_user : null;
        $imagen=(!is_null($json) && isset($params->imagen)) ? $params->imagen : null;

        if(!is_null($nombre_producto)  && !is_null($cantidad) && !is_null($id_categoria)){
            $isset_pro=Productos::where('nombre_producto','=',$nombre_producto)->where('estado',true)->first();
            if(@count($isset_pro)==0){
                $Productos=new Productos();
                $Productos->nombre_producto=$nombre_producto;
                $Productos->id_categoria=$id_categoria;
                $Productos->descripcion=$descripcion;
                $Productos->id_unidad=$id_unidad;
                $Productos->cantidad=$cantidad;
                $Productos->estado=true;
                $Productos->imagen=$imagen;
                $Productos->id_user=$id_user;

            
                    //guardar
                $Productos->save();

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
        //$Productos=Productos::create($request->json()->all());
        //return $Productos;
    }
       
    public function modificar($iden,Request $request){
        $json=$request->input('json',null);
        $params=json_decode($json);

        $id=(!is_null($json) && isset($params->id)) ? $params->id : null;
        $nombre_producto=(!is_null($json) && isset($params->nombre_producto)) ? $params->nombre_producto : null;
        $id_categoria=(!is_null($json) && isset($params->id_categoria)) ? $params->id_categoria : null;
        $descripcion=(!is_null($json) && isset($params->descripcion)) ? $params->descripcion : null;
        $id_unidad=(!is_null($json) && isset($params->id_unidad)) ? $params->id_unidad : null;
        $cantidad=(!is_null($json) && isset($params->cantidad)) ? $params->cantidad : null;
        $id_user=(!is_null($json) && isset($params->id_user)) ? $params->id_user : null;
        $imagen=(!is_null($json) && isset($params->imagen)) ? $params->imagen : null;  
        
        $isset_pro=Productos::whereNotIn('id',[$id])->where('nombre_producto','=',$nombre_producto)->where('estado',true)->first();
            if(@count($isset_pro)==0){
                $Productos= Productos::where('id',$iden)->update(['nombre_producto'=>$nombre_producto,
                    'id_categoria'=>$id_categoria,
                    'descripcion'=>$descripcion,
                    'id_unidad'=>$id_unidad,
                    'cantidad'=>$cantidad,
                    'id_user'=>$id_user,
                    'imagen'=>$imagen]);

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
        /*$Productos=Productos::find($id);
    	$Productos->fill($request->all())->save();
    	return $Productos;*/
       }

       public function seleccionar($id){
       
        $Productos=Productos::find($id);
        return $Productos;
       }

    public function eliminar($id){
        $cambio=false;
        $Productos=Productos::where('id',$id)->update(['estado'=>$cambio]);
    	return $Productos;
       }

    public function buscar($name){
    
        $Productos=Productos::where('nombre','like',$name.'%')->first();
        return $Productos;
    }

    public function getProductos(){
        $productos=Productos::all();
        return $productos;
    }
    public function listaProductos(){
        $productos=DB::table('productos')
                      ->join('categorias','productos.id_categoria','=','categorias.id')
                      ->join('unidades','productos.id_unidad','=','unidades.id')
                      ->select('productos.id','categorias.nombre','unidades.unidad','productos.nombre_producto','productos.descripcion','productos.imagen')
                      ->get();
        return response()->json($productos);

    }


    public function getimages($name){
        
        $file =Storage::disk('productos')->get($name);
        return new Response($file,200);
    }
    public function upimagenes(Request $request){
        
        if ($request->hasFile('photo')) {
            $file = $request->photo;
            $image=$request->file('photo');
            $path=$image->getClientOriginalExtension();
            $now = new \DateTime();
            \Storage::disk('productos')->put($now->format('d_m_Y_H_i_s').".".$path,\File::get($image));
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
   
}
