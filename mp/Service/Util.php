<?php 

namespace Mp\Service;

use Greenter\Data\StoreTrait;
use Greenter\Model\DocumentInterface;
use Greenter\Model\Response\CdrResponse;
use Greenter\Report\HtmlReport;
use Greenter\Report\PdfReport;
use Greenter\See;
use Greenter\Validator\XmlErrorCodeProvider;

final  class Util
{
	use StoreTrait;

	private static $current;

	function __construct()
	{
		# code...
	}

	public static function getInstance(){
		if (!self::$current instanceof self) {
            self::$current = new self();
        }

        return self::$current;
	}

	public static function generator($item, $count)
    {
        $items = [];

        for ($i = 0; $i < $count; $i++) {
            $items[] = $item;
        }

        return $items;
    }

    public function getSee($endpoint){
    	$cert=\Storage::disk('PEM')->get('cert_key.pem');
        $cache=storage_path('app/public/document/cache');
    	$see= new See();
    	$see->setService($endpoint);
    	$see->setCertificate($cert);
    	$see->setCredentials('20000000001MODDATOS', 'moddatos');
    	$see->setCachePath($cache);

    	return $see;
    }

    public function writeXml(DocumentInterface $document, $xml){
    	$this->writeFile($document->getName().'.xml', $xml);
    }

    public function writeFile($filenam, $content){
        $file_ruta=storage_path('app/public/document/file/'); //Ruta de los archivos
    	if (getenv('GREENTER_NO_FILES')) {
            return;
        }
        \Storage::disk('file')->put($filenam,$content);
        //file_put_contents($file_ruta.$filenam, $content);
    }

    public function writeCdr(DocumentInterface $document, $zip){
    	$this->writeFile('R-'.$document->getName().'.zip', $zip);
    }

    public function getResponseFromCdr(CdrResponse $cdr){
    	$result = <<<HTML
        <h2>Respuesta SUNAT:</h2><br>
        <b>ID:</b> {$cdr->getId()}<br>
        <b>CODE:</b>{$cdr->getCode()}<br>
        <b>DESCRIPTION:</b>{$cdr->getDescription()}<br>
HTML;

        return $result;

    }
}