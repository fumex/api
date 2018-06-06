<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Servicio;
use DB;
class ServicioController extends Controller
{
     public function addServicio(Request $request){
        $create=Servicio::create($request->all());
        return response()->json($create);
    }
    
    public function getServicios(){
        $proveedores=DB::table('proveedors')
                         ->join('tipo_proveedors','proveedors.tipo_proveedor','=','tipo_proveedors.id')
                         ->select('proveedors.id','proveedors.nombre_proveedor','proveedors.ruc','proveedors.direccion','proveedors.telefono','proveedors.email','tipo_proveedors.tipo')
                         ->where('proveedors.estado','=',true)->where('tipo_proveedors.operacion','=','Servicio')
                         ->get();
        return response()->json($proveedores);
    }
    public function code(){
    	$max = Servicio::count();
        if ($max > 0) {
            $row = explode('-',Servicio::max('code'), 2);
            $cod = $row[1];
            $sig = $cod+1;
            $Strsig = (string)$sig;
            $formato = "S-".str_pad($Strsig, "5", "0", STR_PAD_LEFT);
            
        } 
        else {
            $sig = 1;
            $Strsig = (string)$sig;
            $formato = "S-".str_pad($Strsig,"5","0",STR_PAD_LEFT);
        }
        
        return response()->json($formato);
    }
    public function listServicios(){
        $servicios=DB::table('servicios')
                      ->join('tipo_documentos','servicios.id_documento','tipo_documentos.id')
                      ->join('proveedors','servicios.id_proveedor','=','proveedors.id')
                      ->join('tipo_proveedors','proveedors.tipo_proveedor','=','tipo_proveedors.id')
                      ->select('servicios.id','servicios.code','tipo_documentos.documento','servicios.nroBoleta','servicios.tipo_pago','proveedors.nombre_proveedor','servicios.descripcion','servicios.subtotal','servicios.igv','servicios.created_at')
                      ->where('servicios.estado','=',true)
                      ->get();
        return response()->json($servicios);
    }
    public function deleteServicio($id){
        $servicio=Servicio::where('id','=',$id)->first();
        if(@count($servicio)>=1){
            $servicio->estado=false;
            $servicio->save();
            return $servicio;
        }
    }
}
