<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FirmaController extends Controller
{
    public function upCertificado(Request $request){
    	if($request->hasFile('arch')){
    		$file = $request->arch;
    		$archivo=$request->file('arch');
    		$path=$archivo->getClientOriginalExtension(); 

    		\Storage::disk('certificado')->put($request->ruc.".".$path,\File::get($archivo));

    		if($this->archivoPEM($request->ruc.".".$path,$request->clave)){
    			return response()->json('RUC'.$request->ruc);
    		}else{
    			return response()->json('Subio el archivo pero No fue capaz generar el PEM');
    		}
    		
    	}else{
    		return response()->json('No Archivo');
    	}
    }

	public function archivoPEM($name ,$clave ){

		$pkcs12 =\Storage::disk('certificado')->get($name);
		$certificados=array();
		$respuesta= openssl_pkcs12_read($pkcs12, $certificados, $clave);

		if($respuesta){

			$publicKeyPem  = $certificados['cert']; //Archivo público
			$privateKeyPem = $certificados['pkey']; //Archivo privado
				//guardo la clave publica y privada 
				\Storage::disk('PEM')->put('cert_key.pem',$privateKeyPem.$publicKeyPem);
				//\Storage::disk('PEM')->put('public_key.pem',$publicKeyPem);

	        // permisos para la escritura y lectura de las llaves
	        chmod(storage_path('app/public/document/PEM/cert_key.pem'), 0777);
	        //chmod(storage_path('app/public/PEM/public_key.pem'), 0777);

			return true;

		}else{
			return false;
		}
	}

}
