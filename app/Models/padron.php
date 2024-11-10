<?php
	namespace App\Models;

	use Illuminate\Database\Eloquent\Model;
	
	use App\Classes\curl;
	use App\Classes\obj;

	class padron extends Model
	{
		public $curl = null;
		public function __construct($config = array())
		{
			$this->curl = new curl();

			if (isset($config["proxy"])) {
				$use  = (isset($config["proxy"]["use"])) ? $config["proxy"]["use"] : false;
				$host = (isset($config["proxy"]["host"])) ? $config["proxy"]["host"] : null;
				$port = (isset($config["proxy"]["port"])) ? $config["proxy"]["port"] : null;
				$type = (isset($config["proxy"]["type"])) ? $config["proxy"]["type"] : null;
				$user = (isset($config["proxy"]["user"])) ? $config["proxy"]["user"] : null;
				$pass = (isset($config["proxy"]["pass"])) ? $config["proxy"]["user"] : null;
				if ($use != false) {
					$this->curl->setProxy($host, $port, $user, $pass);
					$this->curl->setProxyType($type);
				}
			}
			if (isset($config["cookie"])) {
				$file = (isset($config["cookie"]["file"])) ? $config["cookie"]["file"] : sys_get_temp_dir() . '/cookie.txt';

				$this->curl->setCookieFile($file);
				$this->curl->setCookieJar($file);
			}
		}

		private function digit_control($dni)
		{
			if (strlen($dni) == 8 && is_numeric($dni)) {
				$suma = 0;
				$hash = array(5, 4, 3, 2, 7, 6, 5, 4, 3, 2);
				$suma = 5; // 10[NRO_DNI]X (1*5)+(0*4)
				for ($i = 2; $i < 10; $i++) {
					$suma += ($dni[$i - 2] * $hash[$i]); //3,2,7,6,5,4,3,2
				}
				$entero = (int) ($suma / 11);

				$digito = 11 - ($suma - $entero * 11);

				if ($digito == 10) {
					$digito = 0;
				} else if ($digito == 11) {
					$digito = 1;
				}
				return $digito;
			}
			return NULL;
		}

		/**
		 * consulta
		 *
		 * @param mixed $dni
		 * @return object
		 */
		public function consulta($dni)
		{
			if (strlen($dni) != 8 || !is_numeric($dni)) {
				$return = new obj(array(
					'success' => false,
					'message' => 'DNI debe tener 8 digitos numericos.',
				));
				return $return;
			}

			/*$post = array(
				"hTipo"   => "2",
				"hDni"    => $dni,
				"hApPat"  => "",
				"hApMat"  => "",
				"hNombre" => "",
			);*/
			//$url = "http://clientes.reniec.gob.pe/padronElectoral2012/consulta.htm";
		
			$url = "https://dniruc.apisperu.com/api/v1/dni/".$dni."?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJlbWFpbCI6ImFsZWphbmRyby5kZWxhZ2FsYUB1Y3NwLmVkdS5wZSJ9.SOPat5vsHUGG2UaIcwFQT6tS2GzUwsx3AO3Bc_QVOo8";

			$response = $this->curl->send($url);
			if ($this->curl->getHttpStatus() == 200 && $response != '') {
				/*libxml_use_internal_errors(true);

				$doc                      = new \DOMDocument();
				$doc->strictErrorChecking = false;
				$doc->loadHTML($response);
				libxml_use_internal_errors(false);

				$xml    = simplexml_import_dom($doc);
				$result = $xml->xpath("//table");*/
				$result = json_decode($response);
				if (isset($result)) {
					/*$result = $result[4];
					$return = new obj(array(
						'success' => true,
						'result'  => array(
							"dni"            => trim((string) $dni),
							"digito_control" => $this->digit_control((string) $dni),
							"nombres"        => trim(explode(",", (string) $result->tr[0]->td[1])[1]),
							"apellidos"      => trim(explode(",", (string) $result->tr[0]->td[1])[0]),
							"gvotacion"      => trim((string) $result->tr[2]->td[1]),
							"distrito"       => trim((string) $result->tr[3]->td[1]),
							"provincia"      => trim((string) $result->tr[4]->td[1]),
							"departamento"   => trim((string) $result->tr[5]->td[1]),
						),
					));*/					
					$return = new obj(array(
						'success' => true,
						'result'  => array(
							"dni"            	=> (string) $result->dni,
							"nombres"        	=> (string) $result->nombres,
							"apellidoPaterno"   => (string) $result->apellidoPaterno,
							"apellidoMaterno"   => (string) $result->apellidoMaterno,
						),
					));
					return $return;
				} else {
					$return = new obj(array(
						'success' => false,
						'message' => 'No se ha podido obtener los datos.',
					));
					return $return;
				}
			}

			$return = new obj(array(
				'success' => false,
				'message' => 'Fallo de conexión',
			));
			return $return;
		}
	}
