<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use JWTAuth;

use App\Models\Transacciones;
use Dingo\Api\Routing\Helpers;
use Carbon\Carbon;
use App\Models\Categoria;
use App\Models\Producto;
use App\Models\Lote;
use App\User;
use App\Models\CajaH;
use App\Models\CajaD;
use App\Models\OrdenVentaH;
use App\Models\OrdenVentaD;
use App\Models\CajaGuia;
use App\Models\GuiaRemisionH;
use App\Models\GuiaRemisionD;
use App\Models\PagoRecibido;
use App\Models\CajaBaja;
use Auth;
use DB;

use Greenter\Model\Client\Client;
use Greenter\Model\Company\Company;
use Greenter\Model\Company\Address;
use Greenter\Model\Voided\Voided;
use Greenter\Model\Voided\VoidedDetail;
use Greenter\Report\HtmlReport;
use Greenter\Report\PdfReport;
use Greenter\See;
use DateTime;


class CajaBajaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $caja_bajas = DB::table('caja_baja')
                ->select('caja_baja.*')
                ->get();

        return view('caja_baja/listado_comunicacion_baja', ['bajas' => $caja_bajas]);
    }

    public function com_baja(Request $request)
    {
        $msg = '';
        DB::beginTransaction(); // <-- first line  

        try {

            $caja = CajaH::find($request['idcajah']);
            $caja->nulled_at = date('Y-m-d H:i:s');
            $caja->estado_doc = 3;
            $caja->status_cob = 0;
            $caja->save();   

            $id_ov = $caja->id_orden_ventah;
            $min_status_fact = DB::table('cajah')
                                    ->where('id_orden_ventah', $id_ov)
                                    ->where('status_cob', '!=', 0)
                                    ->where('status_cob', '!=', $request['idcajah'])
                                    ->min('status_cob');

            $ov = OrdenVentaH::find($id_ov);
            if (intval($min_status_fact) == 0) {
                $min_status_fact = -1;
                $ov->status_doc = 2;
            }
            $ov->status_cob = $min_status_fact;
            $ov->save();

            $guias_select=CajaGuia::where('idcaja', $request['idcajah'])->get();
            for ($i = 0; $i < count($guias_select); $i++) {
                $gr_state = GuiaRemisionH::find($guias_select[$i]->idguia);
                if($gr_state->estado_doc == 1)
                    $gr_state->estado_doc = 0;
                $gr_state->save();
            }
            CajaGuia::where('idcaja', $request['idcajah'])->delete(); 


            $request['tipo'] = $caja->tipo;
            $request['correlativoG'] = $caja->correlativoG;
            $request['fechaNB'] = $caja->fechaNB;

            $ticket = '';
            $xml_file = '';
            $cdr_file = '';
            $pdf_file = '';
            $codeG = '';
            $descriptionG = '';
            $correlativoG = 0;

            $resp = $this->generateVoidedGreen($request);

            if ($resp['created'] == 501) {
                $msg = $resp['msg'];
                $ticket = $resp['ticket'];
                $xml_file = $resp['xml_file'];
                //$cdr_file = $resp['cdr_file'];
                $pdf_file = $resp['pdf_file'];
                $correlativoG = $resp['correlativoG'];
                $codeG = $resp['codeG'];
                $descriptionG = $resp['descriptionG'];
                //return json_encode([$resp]);
            } else {
                $msg = $resp['msg'];
                $ticket = $resp['ticket'];
                $xml_file = $resp['xml_file'];
                $cdr_file = $resp['cdr_file'];
                $pdf_file = $resp['pdf_file'];
                $correlativoG = $resp['correlativoG'];
                $codeG = $resp['codeG'];
                $descriptionG = $resp['descriptionG'];
            }

            $cajabaja = new CajaBaja;
            $cajabaja->idcajah = $request['idcajah'];
            $cajabaja->ticket = $ticket;
            $cajabaja->documento = $caja->codigoNB;
            $cajabaja->motivo = $request['motivo'];
            $cajabaja->correlativoG = $correlativoG;
            $cajabaja->xml_file = $xml_file;
            $cajabaja->cdr_file = $cdr_file;
            $cajabaja->pdf_file = $pdf_file;
            $cajabaja->codeG = $codeG;
            $cajabaja->descriptionG = $descriptionG;
            $cajabaja->save();

            $childModelSaved = true;

        } catch (Exception $e) {
            $childModelSaved = false;
        }

        if ($childModelSaved) {
            DB::commit(); // YES --> finalize it 
            $respuesta = array();
            $respuesta[] = ['created' => 200];
            $respuesta[] = ['id' => $cajabaja->id];
            $respuesta[] = ['msg' => $msg];
            $respuesta[] = ['pdf' =>$cajabaja->pdf_file];


            return json_encode($respuesta);
        } else {
            DB::rollBack(); // NO --> error de lotes
            $respuesta = array();
            $respuesta[] = ['created' => 500];
            $respuesta[] = ['id' => 9999];

            return json_encode($respuesta);
        }
    }

    public function generateVoidedGreen(Request $request) {

        $see = parent::configInvoiceNoteGreen();
        $dirGreenter = 'greenter/';

        // Emisor
        $company = parent::getCompanyGreen();

        $maxCorrelativo = DB::table('caja_baja')
                    ->where('created_at', '>=', Carbon::today())
                    ->max('correlativoG');
        $nextCorrelativo = intval($maxCorrelativo) + 1;

        $tipoDoc = '01';
        $serie = 'F001';
        if ($request['tipo'] == '1' or $request['tipo'] == 1) {
            $tipoDoc = '03';
            $serie = 'B001';
        }
        $correlativoG = $request['correlativoG'];
        $motivo = $request['motivo'];
        $correlativo = strval($nextCorrelativo);
        $fechaEmision = $request['fechaNB'];

        $detail = (new VoidedDetail())
            ->setTipoDoc($tipoDoc)
            ->setSerie($serie)
            ->setCorrelativo($correlativoG)
            ->setDesMotivoBaja($motivo);

        $voided = (new Voided())
            ->setCorrelativo($correlativo)
            ->setFecGeneracion(new DateTime($fechaEmision.' 12:00:00-05:00'))
            ->setFecComunicacion(new DateTime())
            ->setCompany($company)
            ->setDetails([$detail]);

        // Envío a SUNAT
        $result = $see->send($voided);

        // Guardar XML firmado digitalmente.
        $xmlFileName = $voided->getName().'.xml';
        file_put_contents($dirGreenter.$xmlFileName, $see->getFactory()->getLastXml());

        $htmlReport = new HtmlReport();
        $htmlReport->setTemplate('voided.html.twig');
        $report = new PdfReport($htmlReport);

        $report->setOptions( [
            'no-outline',
            'viewport-size' => '1280x1024',
            'page-width' => '21cm',
            'page-height' => '29.7cm',
        ]);
        $report->setBinPath(parent::getPwdWkhtml());
        $pdfFileName = $voided->getName().'.pdf';

        $params = [
            'system' => [
                'logo' => file_get_contents('images/logo_docs.png'), // Logo de Empresa
                'hash' => parent::getHashXml($dirGreenter.$xmlFileName) // Valor Resumen 
            ],
            'user' => [
                'header'     => parent::getDataCompanyHeader(), // Texto que se ubica debajo de la dirección de empresa
                'extras'     => parent::getLeyendDoc(),
                'link'       => url($dirGreenter.$pdfFileName)
                //'footer' => '<p>Nro Resolucion: <b>3232323</b></p>'
            ]
        ];

        $pdf = $report->render($voided, $params);
        if ($pdf === null) {
            $error = $report->getExporter()->getError();
            echo 'Error: '.$error;
            return;
        }

        file_put_contents($dirGreenter.$pdfFileName, $pdf);
        $ticket = $result->getTicket();

        if (!$result->isSuccess()) {
            // Mostrar error al conectarse a SUNAT.
            $code=(int)$result->getError()->getCode();
            return ['created' => 501, 'msg' => $result->getError()->getCode().' - '.$result->getError()->getMessage(), 'ticket' => $ticket, 'correlativoG' => $correlativo, 'xml_file' => $xmlFileName, 'pdf_file' => $pdfFileName, 'codeG' => strval($code), 'descriptionG' =>$result->getError()->getCode().' - '.$result->getError()->getMessage()];

            //echo 'Codigo Error: '.$result->getError()->getCode();
            //echo 'Mensaje Error: '.$result->getError()->getMessage();
            exit();
        }

        $result = $see->getStatus($ticket);

        // Guardamos el CDR
        $cdrFileName = 'R-'.$voided->getName().'.zip';
        file_put_contents($dirGreenter.$cdrFileName, $result->getCdrZip());

        // CDR Resultado
        $cdr = $result->getCdrResponse();
        $code = -1;
        /*try {
            $code = (int)$cdr->getCode();
        } catch (Exception $e) {
            return ['created' => 501, 'msg' => 'Intentelo mas tarde, servicio no disponible'];
        }*/

        $msg = 'Ticket: '.$ticket;
        /*if ($code === 0) {
            $msg = 'ESTADO: ACEPTADA';
            //echo 'ESTADO: ACEPTADA'.PHP_EOL;
        } else if ($code >= 4000) {
            $msg = 'ESTADO: ACEPTADA CON OBSERVACIONES: ';
            ob_start();
            var_dump($cdr->getNotes());
            $msg .= ob_get_clean();
            //echo 'ESTADO: ACEPTADA CON OBSERVACIONES:'.PHP_EOL;
            //var_dump($cdr->getNotes());
        } else if ($code >= 2000 && $code <= 3999) {
            $msg = 'ESTADO: RECHAZADA';
            //echo 'ESTADO: RECHAZADA'.PHP_EOL;
        } else {
            // Esto no debería darse
            // code: 0100 a 1999
            $msg = 'Excepción';
            //echo 'Excepción';
        }*/

        //$msg .= ' '.$cdr->getDescription();

       

        return ['created' => 200, 'msg' => $msg, 'ticket' => $ticket, 'correlativoG' => $correlativo, 'xml_file' => $xmlFileName, 'cdr_file' => $cdrFileName, 'pdf_file' => $pdfFileName, 'codeG' => strval($code), 'descriptionG' => $msg];
    }

    /* testing greenter */

    public function testGreen() {

        $see = parent::configInvoiceNoteGreenTest();
        $dirGreenter = 'greenter/';

        //$util = Util::getInstance();

        // Emisor
        $address = new Address();
        $address->setUbigueo('150101')
            ->setDepartamento('LIMA')
            ->setProvincia('LIMA')
            ->setDistrito('LIMA')
            ->setUrbanizacion('-')
            ->setDireccion('Av. Villa Nueva 221')
            ->setCodLocal('0000'); // Codigo de establecimiento asignado por SUNAT, 0000 de lo contrario.

        $company = new Company();
        $company->setRuc('20123456789')
            ->setRazonSocial('GREEN SAC')
            ->setNombreComercial('GREEN')
            ->setAddress($address);

        $detail1 = new VoidedDetail();
        $detail1->setTipoDoc('01')
            ->setSerie('F001')
            ->setCorrelativo('02132132')
            ->setDesMotivoBaja('ERROR EN CÁLCULOS');

        $detail2 = new VoidedDetail();
        $detail2->setTipoDoc('07')
            ->setSerie('FC01')
            ->setCorrelativo('222')
            ->setDesMotivoBaja('ERROR DE RUC');

        $voided = new Voided();
        $voided->setCorrelativo('00111')
            // Fecha Generacion menor que Fecha comunicacion
            ->setFecGeneracion(new DateTime('-3days'))
            ->setFecComunicacion(new DateTime())
            ->setCompany($company)
            ->setDetails([$detail1, $detail2]);


        $res = $see->send($voided);

        // Guardar XML firmado digitalmente.
        file_put_contents($dirGreenter.$voided->getName().'.xml',
            $see->getFactory()->getLastXml());

        //$util->writeXml($voided, $see->getFactory()->getLastXml());

        if (!$res->isSuccess()) {
            // Mostrar error al conectarse a SUNAT.
            echo 'Codigo Error: '.$res->getError()->getCode();
            echo 'Mensaje Error: '.$res->getError()->getMessage();
            exit();
        }

        /*if (!$res->isSuccess()) {
            echo $util->getErrorResponse($res->getError());
            return;
        }*/

        /**@var SummaryResult $res */
        $ticket = $res->getTicket();
        echo 'Ticket :<strong>' . $ticket .'</strong>';

        $res = $see->getStatus($ticket);
        /*if (!$res->isSuccess()) {
            echo $util->getErrorResponse($res->getError());
            return;
        }*/

        /*if (!$res->isSuccess()) {
            // Mostrar error al conectarse a SUNAT.
            echo 'Codigo Error: '.$res->getError()->getCode();
            echo 'Mensaje Error: '.$res->getError()->getMessage();
            //var_dump($res->getError());
            exit();
        }*/

        //$cdr = $res->getCdrResponse();
        //$util->writeCdr($voided, $res->getCdrZip());

        //$util->showResponse($voided, $cdr);

        // Guardamos el CDR
        file_put_contents($dirGreenter.'R-'.$voided->getName().'.zip', $res->getCdrZip());

        // CDR Resultado
        $cdr = $res->getCdrResponse();

        /*$code = (int)$cdr->getCode();

        if ($code === 0) {
            echo 'ESTADO: ACEPTADA'.PHP_EOL;
        } else if ($code >= 4000) {
            echo 'ESTADO: ACEPTADA CON OBSERVACIONES:'.PHP_EOL;
            var_dump($cdr->getNotes());
        } else if ($code >= 2000 && $code <= 3999) {
            echo 'ESTADO: RECHAZADA'.PHP_EOL;
        } else {
            // Esto no debería darse 
            //code: 0100 a 1999 
            echo 'Excepción';
        }*/

        //echo $cdr->getDescription().PHP_EOL;

        $htmlReport = new HtmlReport();
        $htmlReport->setTemplate('voided.html.twig');
        $report = new PdfReport($htmlReport);

        $report->setOptions( [
            'no-outline',
            'viewport-size' => '1280x1024',
            'page-width' => '21cm',
            'page-height' => '29.7cm',
        ]);
        $report->setBinPath(parent::getPwdWkhtml());

        $params = [
            'system' => [
                'logo' => file_get_contents('images/logo_docs.png')//, // Logo de Empresa
                //'hash' => 'qqnr2dN4p/HmaEA/CJuVGo7dv5g=', // Valor Resumen 
            ],
            'user' => [
                'header'     => parent::getDataCompanyHeader(), // Texto que se ubica debajo de la dirección de empresa
                'extras'     => parent::getLeyendDoc()//,
                //'footer' => '<p>Nro Resolucion: <b>3232323</b></p>'
            ]
        ];

        $pdf = $report->render($voided, $params);
        if ($pdf === null) {
            $error = $report->getExporter()->getError();
            echo 'Error: '.$error;
            return;
        }

        $pdfFileName = $voided->getName().'.pdf';
        file_put_contents($dirGreenter.$pdfFileName, $pdf);
    }

}