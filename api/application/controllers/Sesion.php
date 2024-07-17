<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Sesion extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model([
			"Usuario_model",
			"mnt/Establecimiento_model"
		]);

		$this->output->set_content_type('application/json');
	}

	public function index()
	{
		$this->output->set_status_header('404');
	}

	public function login()
	{
		$data = ["exito" => 0];

		if ($this->input->method() === "post") {
			$headers = array_change_key_case($this->input->request_headers());

			if (elemento($headers, "usuario") && elemento($headers, "clave")) {
				
				$us = new Usuario_model();

				if ($us->iniciarSesion($headers)) {

					$us->id = $us->getPK();
					$establecimiento = $this->catalogo->ver_usuario_establecimiento([
						"usuario" => $us->id, 
						"uno" => true
					]);

					if ($establecimiento) {
						$est = new Establecimiento_model($establecimiento->establecimiento_id);
						$us->establecimiento_id = $est->getPK();
						$us->empresa_id = $est->empresa_id;

						$usuario = var_session($us);
						$this->session->set_userdata(["usuario" => $usuario]);

						$this->load->library("Token");
						$token = new Token();

						$data["exito"] = 1;
						$data["token"]   = $token->set_token(["data" => $us]);														
						$data["usuario"] = $usuario;
						$data["mensaje"] = "Bienvenido {$us->nombre} a Blitz";
					} else {
						$data["mensaje"] = "El usuario no tiene ningun establecimiento asigando.";
					}
				} else {
					$data["mensaje"] = "Usuario o clave incorrecta, intente de nuevo.";
				}
			} else {
				$data["mensaje"] = "Ingrese las credenciales.";
			}
		} else {
			$this->output->set_status_header('405');
		}

		$this->output->set_output(json_encode($data));
	}

	public function logout()
	{
		$data = ['exito' => 0];

		$this->session->sess_destroy();
		$data['exito'] = 1;
		$data['mensaje'] = "Sesión finalizada.";
	
		$this->output->set_output(json_encode($data));
	}

	public function validar_token()
	{
		$datos = json_decode(file_get_contents('php://input'));
		$data  = ['valido' => 0];

		$this->load->library("Token");
		$JWT = new Token();

		if ($JWT->token_valido($datos->token)) {
			$data['valido'] = 1;
			$data['mensaje'] = "Token válido";
		} else {
			http_response_code(401);
			$data['mensaje'] = 'Acceso denegado.';
		}

		$this->output->set_output(json_encode($data));
	}

}

/* End of file Sesion.php */
/* Location: ./application/controllers/Sesion.php */