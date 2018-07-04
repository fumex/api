<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EmpresaController extends Controller
{
    public function addEmpresa(Request $request){
         $file = $request->photo;
        
        if ($request->hasFile('photo')) {
            $file = $request->photo;
            $image=$request->file('photo');
            $path=$image->getClientOriginalExtension();
            \Storage::disk('empresa')->put($request->dni.".".$path,\File::get($image));
            //$path = $request->photo->store('images');
            return response()->json($request->dni);
        } 
        else{
        	return response()->json('No  hay imagen');
        }   	
    }
    public function add(Request $request){
    	$create=Empresa::create()->all();
    }
}
