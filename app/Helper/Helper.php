<?php
namespace App\Helpers;

class Helper
{
	public static function getCompanyData() {
		return 	[
					'ruc' => '20600819667',
					'razon_social' => 'SOLUCIONES OGGK S.A.C.',
					'nombre_comercial' => 'SOLUCIONES OGGK S.A.C.',
					'ubigeo' => '040103',
					'departamento' => 'AREQUIPA',
					'provincia' => 'AREQUIPA',
					'distrito' => 'CAYMA',
					'direccion' => 'PSJ. LA RONDA 107',
					'direccion2' => 'PSJ. LA RONDA 107 - CAYMA',
					'ubicacion' => 'AREQUIPA - AREQUIPA - CAYMA',
					'celular' => '999295220',
					'email' => 'O.GUTIERREZ@SOLUCIONESOGGK.COM'
				];
	}

	public static function status_doc_ov() {
		return [
					0 => '<button class="btn-sm btn btn-secondary">ANULADO</button>',
					1 => '<button class="btn-sm btn btn-warning">GUIA P</button>',
					2 => '<button class="btn-sm btn btn-warning">FACTURA P</button>',
					3 => '<button class="btn-sm btn btn-success">FACTURADA</button>',
					4 => '<button class="btn-sm btn btn-danger">NCT</button>'
			   ];
	}

	public static function status_ent_ov()
	{
		return [
					1 => '<button class="btn-sm btn btn-warning">ENTREGA P</button>',
					2 => '<button class="btn-sm btn btn-warning">E PARCIAL</button>',
					3 => '<button class="btn-sm btn btn-success">ENTREGADO</button>',
			   ];
	}

	public static function status_cob_ov() {
		return [
					1 => '<button class="btn-sm btn btn-warning">COBRO P</button>',
					2 => '<button class="btn-sm btn btn-warning">C PARCIAL</button>',
					3 => '<button class="btn-sm btn btn-success">FACTURAS PAGADAS</button>'
			   ];
	}

	public static function status_ent_gr()
	{
		return [
					0 => '<button class="btn-sm btn btn-secondary">ANULADO</button>',
					1 => '<button class="btn-sm btn btn-warning">PENDIENTE ENTREGA</button>',
					2 => '<button class="btn-sm btn btn-warning">ENTREGA PARCIAL</button>',
					3 => '<button class="btn-sm btn btn-success">ENTREGADO</button>',
					4 => '<button class="btn-sm btn btn-danger">NCT</button>'
			   ];
	} 

	public static function status_cob_fc()
	{
		return [
					0 => '<button class="btn-sm btn btn-secondary">ANULADO</button>',
					1 => '<button class="btn-sm btn btn-warning">PENDIENTE DE COBRO</button>',
					2 => '<button class="btn-sm btn btn-warning">COBRADO PARCIAL</button>',
					3 => '<button class="btn-sm btn btn-success">PAGADO</button>',
					4 => '<button class="btn-sm btn btn-danger">NCT</button>'
			   ];
	} 

	public static function pwdwkhtmltopdf()
	{
		//return '/home/julio/oggkerp/wkhtmltopdf';
		return '/home1/solucjc0/public_html/erp/wkhtmltopdf';
	}
}
