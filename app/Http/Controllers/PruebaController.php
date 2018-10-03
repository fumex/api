<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Greenter\Model\Client\Client;
use Greenter\Model\Sale\Invoice;
use Greenter\Model\Sale\SaleDetail;
use Greenter\Model\Sale\Legend;
use Greenter\Ws\Services\SunatEndpoints;
use Mp\Service\Util;
use DateTime;

use App\Http\Controllers\ClienteController;

class PruebaController extends Controller
{
    //Prueba de boleta
	public function bole(Request $request){
		$util = Util::getInstance();
		$client=$this->cliente($request->id);
		// Venta
		$invoice = new Invoice();
		$invoice->setTipoDoc('03')
		    ->setSerie('B001')
		    ->setCorrelativo('3')
		    ->setFechaEmision(new DateTime())
		    ->setTipoMoneda('PEN')
		    ->setClient($client)
		    ->setMtoOperGravadas(200)
		    ->setMtoOperExoneradas(0)
		    ->setMtoOperInafectas(0)
		    ->setMtoIGV(36)
		    ->setMtoImpVenta(100)
		    ->setCompany($util->getCompany());

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
		$items = Util::generator($item1, 14);

		$legend = new Legend();
		$legend->setCode('1000')
		    ->setValue('SON CIEN CON 00/100 SOLES');

		$invoice->setDetails($items)
		    ->setLegends([$legend]);

		//Envio a SUNAT
		$see = $util->getSee(SunatEndpoints::FE_BETA);//*
		$res=$see->send($invoice);
		$util->writeXml($invoice, $see->getFactory()->getLastXml());

		//Verificar si esta el cdr
		if($res->isSuccess()){

			$cdr = $res->getCdrResponse();
   			$util->writeCdr($invoice, $res->getCdrZip());

   			return $util->getResponseFromCdr($cdr);
 		} else{
 			return $res->getError();
 		}
	}

	public function cliente($id){
		$_id=$id;
		$cli = $this->lisCliente($_id);
		$params=json_decode($cli,true);
		$nombre=$params['nombre'];
		$documento=$params['nro_documento'];
		$client = new Client();
		return 	$client->setTipoDoc('1')
	    		->setNumDoc($documento)
	    		->setRznSocial($nombre);
	}

	public function lisCliente($id){
		$cli= new ClienteController();
		return $cli->getCliente($id);
	}

	public function getFactura($id){
		
	}
}
