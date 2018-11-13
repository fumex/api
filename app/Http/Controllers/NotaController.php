<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Greenter\Model\Client\Client;
use Greenter\Model\Sale\Invoice;
use Greenter\Model\Sale\SaleDetail;
use Greenter\Model\Sale\Legend;
use Greenter\Model\Sale\Note;

use Greenter\Model\Company\Address;
use Greenter\Model\Company\Company;
use Greenter\Model\Sale\Document;

use Greenter\Ws\Services\SunatEndpoints;
use Mp\Service\Util;
use DateTime;

use App\Cliente;
use App\Empresa;
use App\Venta;

use DB;

class NotaController extends Controller
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

    public function notaCredito(Request $request){
    	$vnt=json_decode(Venta::find($request->id));

		$util = Util::getInstance();
		//cliente
		$client=$this->client($vnt->{'id_cliente'});
		
		//Emisor
		$company=$this->company(1); //falta traer desde aqui
    	
		$note = new Note();
		$note
		    ->setTipDocAfectado('01')
		    ->setNumDocfectado('F001-111')
		    ->setCodMotivo('07')
		    ->setDesMotivo('DEVOLUCION POR ITEM')
		    ->setTipoDoc('07')
		    ->setSerie('FF01')
		    ->setFechaEmision(new DateTime())
		    ->setCorrelativo('123')
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
		    ->setMtoImpVenta(236)
		    ->setCompany($company);

		$detail1 = new SaleDetail();
		$detail1->setCodProducto('C023')
		    ->setUnidad('NIU')
		    ->setCantidad(2)
		    ->setDescripcion('PROD 1')
		    ->setIgv(18)
		    ->setTipAfeIgv('10')
		    ->setMtoValorVenta(100)
		    ->setMtoValorUnitario(50)
		    ->setMtoPrecioUnitario(56);

		$detail2 = new SaleDetail();
		$detail2->setCodProducto('C02')
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

		$note->setDetails([$detail1, $detail2])
		    ->setLegends([$legend]);

		// Envio a SUNAT.
		$see = $util->getSee(SunatEndpoints::FE_BETA);

		$res = $see->send($note);
		$util->writeXml($note, $see->getFactory()->getLastXml());

		if ($res->isSuccess()) {
    	/**@var $res \Greenter\Model\Response\BillResult*/
		   $cdr = $res->getCdrResponse();
		    $util->writeCdr($note, $res->getCdrZip());

		    echo $util->getResponseFromCdr($cdr);
		} else {
		    var_dump($res->getError());
		}

    }

    public function notaDebito(Request $request){
    	$vnt=json_decode(Venta::find($request->id));

		$util = Util::getInstance();
		//cliente
		$client=$this->client($vnt->{'id_cliente'});
		
		//Emisor
		$company=$this->company(1); //falta traer desde aqui

		$note = new Note();
		$note
		    ->setTipDocAfectado('01')
		    ->setNumDocfectado('F001-111')
		    ->setCodMotivo('02')
		    ->setDesMotivo('AUMENTO EN EL VALOR')
		    ->setTipoDoc('08')
		    ->setSerie('FF01')
		    ->setFechaEmision(new DateTime())
		    ->setCorrelativo('123')
		    ->setTipoMoneda('PEN')
		    ->setClient($client)
		    ->setMtoOperGravadas(200)
		    ->setMtoOperExoneradas(0)
		    ->setMtoOperInafectas(0)
		    ->setMtoIGV(36)
		    ->setMtoImpVenta(236)
		    ->setCompany($company);

		$detail1 = new SaleDetail();
		$detail1->setCodProducto('C023')
		    ->setUnidad('NIU')
		    ->setCantidad(2)
		    ->setDescripcion('PROD 1')
		    ->setIgv(18)
		    ->setTipAfeIgv('10')
		    ->setMtoValorVenta(100)
		    ->setMtoValorUnitario(50)
		    ->setMtoPrecioUnitario(56);

		$detail2 = new SaleDetail();
		$detail2->setCodProducto('C02')
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

		$note->setDetails([$detail1, $detail2])
		    ->setLegends([$legend]);

		// Envio a SUNAT.
		$see = $util->getSee(SunatEndpoints::FE_BETA);

		$res = $see->send($note);
		$util->writeXml($note, $see->getFactory()->getLastXml());

		if ($res->isSuccess()) {
    	/**@var $res \Greenter\Model\Response\BillResult*/
		   $cdr = $res->getCdrResponse();
		    $util->writeCdr($note, $res->getCdrZip());

		    echo $util->getResponseFromCdr($cdr);
		} else {
		    var_dump($res->getError());
		}

    }
}
