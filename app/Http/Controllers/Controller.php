<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use Greenter\See;
use Greenter\Ws\Services\SunatEndpoints;
use Greenter\Model\Client\Client;
use Greenter\Model\Company\Company;
use Greenter\Model\Company\Address;
use Greenter\XMLSecLibs\Certificate\X509Certificate;
use Greenter\XMLSecLibs\Certificate\X509ContentType;
use Greenter\Ws\Reader\XmlReader;
use Greenter\Ws\Services\ConsultCdrService;
use Greenter\Ws\Services\SoapClient;

use App\Models\Cliente;
use Helper;


class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function convertToPEM()
    {
        $certificateName = 'certificate_name';
        $password = 'password';

        $certificate = new X509Certificate(file_get_contents(base_path().'/'.$certificateName.'.pfx'), $password);
        $pem = $certificate->export(X509ContentType::PEM);
        file_put_contents(base_path().'/'.$certificateName.'.pem', $pem);
    }

    public function convertToCER()
    {
        $certificateName = 'certificate_name';
        $password = 'password';

        $certificate = new X509Certificate(file_get_contents(base_path().'/'.$certificateName.'.pfx'), $password);
        $cer = $certificate->export(X509ContentType::CER);
        file_put_contents(base_path().'/'.$certificateName.'.cer', $cer);
    }

    public function configInvoiceNoteGreen() {

		$see = new See();
		$see->setService(SunatEndpoints::FE_PRODUCCION);
		$see->setCertificate(file_get_contents(base_path().'/C21090148948.pem'));
		$see->setClaveSOL('20600819667', 'FACTURA1', 'Ollatiara1');

		return $see;
    }

    public function configInvoiceNoteGreenTest() {
	$see = new See();
        $see->setService(SunatEndpoints::FE_BETA);
        $see->setCertificate(file_get_contents(base_path().'/certificate.pem'));
        $see->setClaveSOL('20000000001', 'MODDATOS', 'moddatos');

        return $see;
        
    }

    public function configDespatchGreen() {

        $see = new See();
        $see->setService(SunatEndpoints::GUIA_PRODUCCION);
        $see->setCertificate(file_get_contents(base_path().'/C21090148948.pem'));
        $see->setClaveSOL('20600819667', 'FACTURA1', 'Ollatiara1');

        return $see;
    }

    public function configDespatchGreenTest() {
	$see = new See();
        $see->setService(SunatEndpoints::GUIA_BETA);
        $see->setCertificate(file_get_contents(base_path().'/certificate.pem'));
        $see->setClaveSOL('20000000001', 'MODDATOS', 'moddatos');

        return $see;
        
    }

    public function getClientGreen($idcliente) {

    	$cliente = Cliente::find($idcliente);
        $dir = $cliente->direccion . ', ';
        $dir .= ($cliente->departamento ? $cliente->departamento . ' - ' : '');
        $dir .= ($cliente->provincia ? $cliente->provincia . ' - ' : '');
        $dir .= $cliente->distrito;

        $address = (new Address())
            ->setDireccion(strtoupper($dir));

    	$client = (new Client())
        	->setTipoDoc(strlen($cliente->ruc_dni) == 8 ? '1' : '6')
            ->setNumDoc($cliente->ruc_dni)
            ->setRznSocial($cliente->razon_social)
            ->setAddress($address);

        return $client;
    }

    public function getCompanyGreen() {

    	$companyData = Helper::getCompanyData();

    	$address = (new Address())
        	->setUbigueo($companyData['ubigeo'])
            ->setDepartamento($companyData['departamento'])
            ->setProvincia($companyData['provincia'])
            ->setDistrito($companyData['distrito'])
            ->setUrbanizacion('-')
            ->setDireccion(strtoupper($companyData['direccion']))
            ->setCodLocal('0000'); // Codigo de establecimiento asignado por SUNAT, 0000 de lo contrario.

        $company = (new Company())
        	->setRuc($companyData['ruc'])
            ->setRazonSocial($companyData['razon_social'])
            ->setNombreComercial($companyData['nombre_comercial'])
            ->setAddress($address);

    	return $company;
    }

    public function getDataCompanyHeader()
    {

        $companyData = Helper::getCompanyData();

        $dataHeader = $companyData['ubicacion'].'<br>';
        $dataHeader .= 'Cel.: <b>'.$companyData['celular'].'</b><br>';
        $dataHeader .= 'e-mail: '.$companyData['email'];

        return $dataHeader;
    }


    public function checkCDR( $rucEmisor,$tipoDocumento,$serie,$correlativo)
    {

        // URL CDR de Producción
        $wsdlUrl = 'https://e-factura.sunat.gob.pe/ol-it-wsconscpegem/billConsultService?wsdl';
        $soap = new SoapClient($wsdlUrl);
        $soap->setCredentials('20600819667FACTURA1', 'Ollatiara1');

        $service = new ConsultCdrService();
        $service->setClient($soap);

        $result = $service->getStatusCdr($rucEmisor, $tipoDocumento, $serie, $correlativo);
        return $result;
    }



    public function getLeyendDoc()
    {
        return  [   
                    ['name' => 'PLAZO DE ENTREGA'         , 'value' => '1 día posterior a su orden de compra, salvo variación de stock y cantidad.'],
                    ['name' => 'CONDICION DE PAGO', 'value' => 'A tratar. Nro de Cta. Ahorros BCP: 215-32428316-0-70 . CCI: 002-21513242831607026 . A nombre de: Soluciones OGGK SAC. Ruc: 20600819667'     ],
                    ['name' => 'VALIDEZ DE LA OFERTA'         , 'value' => '15 días calendario.'],
                ];
    }

    public function getHashXml($path)
    {
        $parser = new XmlReader();
        $archivoXml = file_get_contents($path);
        $documento = $parser->getDocument($archivoXml);
        $hash = $documento->getElementsByTagName('DigestValue')->item(0)->nodeValue;
        return $hash;
    }

    public function getPwdWkhtml()
    {
        return Helper::pwdwkhtmltopdf();
    }
}
