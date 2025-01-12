<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Usuario extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model(["Usuario_model"]);
		$this->output->set_content_type('application/json');
	}

	public function index()
	{
		$this->output->set_status_header('404');
	}

	public function buscar()
	{
		$data =  [
			'lista' => $this->Usuario_model->_buscar($_GET)
		];

		$this->output->set_output(json_encode($data));
	}

	public function get_datos()
	{
		$data = [
			"cat" => [
				"roles" => $this->catalogo->ver_rol()
			]
		];

		$this->output->set_output(json_encode($data));
	}

	public function guardar($id="")
	{
		$data = ["exito" => 0];

		if ($this->input->method() === "post") {
			$datos = json_decode(file_get_contents("php://input"));

			if (verPropiedad($datos, "nombre") && 
				verPropiedad($datos, "apellido") && 
				verPropiedad($datos, "usuario") && 
				verPropiedad($datos, "rol_id") && 
				verPropiedad($datos, "clave")) {

				$us = new Usuario_model($id);

				if (empty($id)) {
					$datos->clave = password_hash($datos->clave, PASSWORD_DEFAULT);		
				}	
				
				if ($us->existe($datos)) {
					$data["mensaje"] = "El usuario ya está registrado";
				} else {
					if ($us->guardar($datos)) {
						$data["exito"]   = 1;
						$texto = empty($id) ? "creado" : "actualizado";
						$data["mensaje"] = "Usuario {$texto} con éxito.";
						$data["linea"]   = $us->_buscar(["id" => $us->getPK(), "uno" => true]);
					} else {
						$data["mensaje"] = $us->getMensaje();
					}
				}
				
			} else {
				$data["mensaje"] = "Complete los campos marcados con *.";
			}
		} else {
			$this->output->set_status_header('405');
		}

		$this->output->set_output(json_encode($data));
	}
}

/* End of file Usuario.php */
/* Location: ./application/controllers/Usuario.php */