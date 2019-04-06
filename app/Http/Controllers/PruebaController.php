<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Greenter\Model\Client\Client;
use Greenter\Model\Sale\Invoice;
use Greenter\Model\Sale\SaleDetail;
use Greenter\Model\Sale\Legend;

use Greenter\Model\Company\Address;
use Greenter\Model\Company\Company;
use Greenter\Model\Sale\Document;

use Greenter\Ws\Services\SunatEndpoints;
use Mp\Service\Util;
use DateTime;

use App\Http\Controllers\ClienteController;
use App\Http\Controllers\VentaController;

use App\Cliente;
use App\Empresa;
use App\Venta;

use DB;
class PruebaController extends Controller
{

	public function client($id){
		$cli=json_decode(Cliente::find($id));
		$clien = new client(); 
		return $clien->setTipoDoc('6') //averiguar
				->setNumDoc('20000000001')
				//->setNumDoc($cli->{'nro_documento'})
				->setRznSocial($cli->{'nombre'});
	}

	public function company($id){
		$emp = json_decode(Empresa::find($id));
		$address = new Address();
		$address -> setUbigueo('080101') //averiguar
				 -> setDepartamento($emp->{'departamento'})
				 ->setProvincia($emp->{'provincia'})
				 ->setDistrito($emp->{'distrito'})
				 ->setUrbanizacion('NONE')
				 ->setDireccion($emp->{'direccion'});

		$comp = new Company();
		return $comp->setRuc('20000000001')
				 //->setRuc($emp->{'ruc'})
				 ->setRazonSocial('NASA SAC') //implmentar
				 ->setNombreComercial($emp->{'nombre'})
				 ->setAddress($address);
	}

	public function detailItem($id){
		$ven=detalle_Ventas::find($id);
	}

	public function getClientes(){
    	$clientes=DB::table('clientes')
                        ->join('tipo_documentos','clientes.id_documento','=','tipo_documentos.id')
                        ->select('clientes.id','clientes.nombre','tipo_documentos.documento','clientes.nro_documento','clientes.direccion','clientes.email','clientes.telefono','clientes.telefono2')
                        ->where('clientes.estado','=',true)
                        ->get();
        return response()->json($clientes);
    }

	public function factura(Request $request){
		$vnt=json_decode(Venta::find($request->id));
		
		$util = Util::getInstance();
		//cliente
		$client=$this->client('16');
		
		//Emisor
		$company=$this->company(1); //falta traer desde aqui
		
		//Venta
		$invoice = new Invoice();
		$invoice ->setFecVencimiento(new DateTime())
			    ->setTipoDoc('01')
			    ->setSerie('F001')
			    ->setCorrelativo('127')
			    ->setFechaEmision(new DateTime())
			    ->setTipoMoneda('PEN')
			    ->setGuias([
			        (new Document())
			        ->setTipoDoc('09')
			        ->setNroDoc('001-213')
			    ])
			    ->setClient($client)
			    ->setMtoOperGravadas(200)
			    ->setMtoOperExoneradas(0)
			    ->setMtoOperInafectas(0)
			    ->setMtoIGV(36)
			    ->setMtoImpVenta(2236.43)
			    ->setCompany($company);

		$item1 = new SaleDetail();
		$item1->setCodProducto('C023')
		    ->setUnidad('NIU')
		    ->setCantidad(2)
		    ->setDescripcion('PROD 1')
		    ->setDescuento(1)
		    ->setIgv(18)
		    ->setTipAfeIgv('10')
		    ->setMtoValorVenta(100)
		    ->setMtoValorUnitario(50)
		    ->setMtoPrecioUnitario(56);

		$item2 = new SaleDetail();
		$item2->setCodProducto('C02')
		    ->setCodProdSunat('P21')
		    ->setUnidad('NIU')
		    ->setCantidad(2)
		    ->setDescripcion('PROD 1')
		    ->setIgv(18)
		    ->setTipAfeIgv('10')
		    ->setMtoValorVenta(100)
		    ->setMtoValorUnitario(50)
		    ->setMtoPrecioUnitario(56);

		$legend = new Legend();
		$legend->setCode('1000')
			   ->setValue('SON CIEN CON 00/100 SOLES');

		$invoice->setDetails([$item1, $item2])
				->setLegends([$legend]);
		//Envio Sunat
		$see = $util->getSee(SunatEndpoints::FE_BETA);
		
		$res = $see->send($invoice);
		$util->writeXml($invoice, $see->getFactory()->getLastXml());

		if ($res->isSuccess()) {
    	/**@var $res \Greenter\Model\Response\BillResult*/
		   $cdr = $res->getCdrResponse();
		    $util->writeCdr($invoice, $res->getCdrZip());

		    echo $util->getResponseFromCdr($cdr);
		} else {
		    var_dump($res->getError());
		}
   	}
}
