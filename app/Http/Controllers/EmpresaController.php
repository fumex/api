<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Empresa;
class EmpresaController extends Controller
{
    public function addEmpresa(Request $request){
    	$nombre=$request->nombre;
    	$ruc=$request->ruc;
    	$direccion=$request->direccion;
    	$departamento=$request->departamento;
    	$provincia=$request->provincia;
    	$distrito=$request->distrito;
    	$telefono=$request->telefono;
    	$web=$request->web;
    	$telefono=$request->telefono;
    	$web=$request->web;
    	$email=$request->email;
    	if($request->hasFile('logo')){
    		$file=$request->logo;
    		$name=time().$file->getClientOriginalName();
    		$file->move(public_path().'/logo/',$name);
    	
			$empresa = new Empresa();
			$empresa->nombre=$nombre;
			$empresa->ruc=$ruc;
			$empresa->direccion=$direccion;
			$empresa->departamento=$departamento;
			$empresa->provincia=$provincia;
			$empresa->distrito=$distrito;
			$empresa->telefono=$telefono;
			$empresa->web=$web;
			$empresa->telefono=$telefono;
			$empresa->email=$email;
			$empresa->logo=$name;
			
			$empresa->save();
			return response()->json($empresa);
    	}
    }
}
