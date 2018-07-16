<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Empresa;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;
class EmpresaController extends Controller
{
    public function addEmpresa(Request $request){
        $create = Empresa::create($request->all());
        return response()->json($create);
    } 
    public function getEmpresa($id){
        $empresa=Empresa::find($id);
        return response()->json($empresa);
    }
    public function updateEmpresa($id,Request $request){
        $edit=Empresa::find($id)->update($request->all());
        return response()->json($edit);
    }
    public function deleteEmpresa($id){
        $empresa=Empresa::where('id','=',$id)->first();
        if(@count($empresa)>=1){
            $empresa->estado=false;
            $empresa->save();
            return response()->json($empresa);
        }
    }
    public function getEmpresas(){
        $empresa=Empresa::where('estado','=',true)->get();
        return response()->json($empresa);
    }
    public function upImagen(Request $request){        
        if ($request->hasFile('photo')) {
            $file = $request->photo;
            $image=$request->file('photo');
            $path=$image->getClientOriginalExtension();

            $now =new \DateTime();
            \Storage::disk('empresa')->put($request->ruc.".".$path,\File::get($image));
            return response()->json($request->ruc);
        } 
        else{
        	return response()->json('No  hay imagen');
        }   	
    }
    public function getImagen($name){
        $file=Storage::disk('empresa')->get($name);
        return new Response($file,200);
    }
    public function dataEmpresa(){
        $empresa=Empresa::get()->last();
        return response()->json($empresa);
    }
}
