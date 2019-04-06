<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ubigeo;
use DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Facades\Excel;

class ubigeoController extends Controller
{
    public function upubigeoexel(Request $request){    
        $ubi=new ubigeo();
        $ubiareglo=array();
        $i=0;   
        if ($request->hasFile('exel')) {
            $file = $request->exel;
            $archivo=$request->file('exel');
            $path=$archivo->getClientOriginalExtension();

            \Storage::disk('entidades')->put("ubigeo.".$path,\File::get($archivo));
             /** El método load permite cargar el archivo definido como primer parámetro */
            Excel::load('storage/app/public/exel/datos_entidades_gubernamentales/ubigeo.xlsx', function ($reader) {
                
                 //$reader->get() nos permite obtener todas las filas de nuestro archivo
                
                foreach ($reader->get() as $key => $row) {
                    
                    $ubigeo = [
                        'ubigeo' => $row['ubigeo'],
                        'distrito' => $row['distrito'],
                        'provincia' => $row['provincia'],
                        'departamento' => $row['departamento'],
                    ];
                    //Una vez obtenido los datos de la fila procedemos a registrarlos 
                    $buscar=ubigeo::where('ubigeo',$ubigeo['ubigeo'])->get()->last();
                    if (!empty($ubigeo)) {
                        if(@count($buscar)>0){
                            echo $buscar['distrito'].'-hallado';
                            echo '<hr>';
                        }else{
                            DB::table('ubigeos')->insert($ubigeo);
                        }
					   
                        /*echo $ubigeo['ubigeo'].'ingresado';
                        echo '<hr>';*/
                       
                    }else{
                        return "error";
                    }
                }
            });
            return $ubiareglo;
        } 
        else{
        	return response()->json('No  hay exel');
        }   	
    }
    public function vertodo(){
        $distrito=ubigeo::get();
        return $distrito;
    }
    public function verdepartamento(){
        $departamento=ubigeo::orderby('departamento')->select('ubigeos.departamento')->distinct('ubigeos.nombre')->get();
        return $departamento;
    }
    public function verprovincias(){
        $provincias=ubigeo::orderby('departamento')->select('ubigeos.departamento','ubigeos.provincia')->distinct('ubigeos.nombre')->get();
        return $provincias;
    }
}
