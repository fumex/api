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

class BoletaController extends Controller
{
	public function client($id){
		$cli=json_decode(Cliente::find($id));
		$clien = new client(); 
		return $clien->setTipoDoc('1') //averiguar varia segun el documento
				->setNumDoc('20203030')
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
    
    public function boleta(Request $request){
    	$vnt=json_decode(Venta::find($request->id));

		$util = Util::getInstance();
		//cliente
		$client=$this->client($vnt->{'id_cliente'});
		
		//Emisor
		$company=$this->company(1); //falta traer desde aqui

		//venta
		$invoice = new Invoice();
		$invoice->setTipoDoc('03')
		    ->setSerie('B001')
		    ->setCorrelativo('2')
		    ->setFechaEmision(new DateTime())
		    ->setTipoMoneda('PEN')
		    ->setClient($client)
		    ->setMtoOperGravadas(200)
		    ->setMtoOperExoneradas(0)
		    ->setMtoOperInafectas(0)
		    ->setMtoIGV(36)
		    ->setMtoImpVenta(100)
		    ->setCompany($company);
		$item1 = new SaleDetail();
		$item1->setCodProducto('C023')
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

		$invoice->setDetails([$item1])
    		->setLegends([$legend]);

    	// Envio a SUNAT.
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
