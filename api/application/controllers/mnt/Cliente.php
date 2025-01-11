<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cliente extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model(["mnt/Cliente_model"]);
		
		$this->output->set_content_type('application/json');
	}

	public function index()
	{
		$this->output->set_status_header('404');
	}

	public function buscar()
	{	
		$data = [
			"lista" => $this->Cliente_model->_buscar($_GET)
		];

		$this->output->set_output(json_encode($data));
	}

	public function get_datos()
	{
		$data = [
			"cat" => [
				"departamentos" => $this->catalogo->ver_departamento(),
				"municipios"    => $this->catalogo->ver_municipio(),
				"tdocumentos"   => $this->catalogo->ver_tipo_documento()
			]
		];

		$this->output->set_output(json_encode($data));
	}

	public function guardar($id="")
	{
		$data = ["exito" => 0];

		if ($this->input->method() === "post") {
			$datos = json_decode(file_get_contents("php://input"));

			if (verPropiedad($datos, "nombre") && verPropiedad($datos, "razon_social")) {
				$cliente = new Cliente_model($id);

				if ($cliente->existe(["nombre" => $datos->nombre, "identificacion" => $datos->identificacion])) {
					$data["mensaje"] = "El cliente ya está registrado.";
				} else {

					if ($cliente->guardar($datos)) {
						$data["exito"]   = 1;
						$texto = empty($id) ? "creado" : "actualizado";
						$data["mensaje"] = "Cliente {$texto} con éxito.";
						
						$data["linea"]   = $cliente->_buscar([
							"id" => $cliente->getPK(), 
							"uno" => true
						]);
					} else {
						$data["mensaje"] = $cliente->getMensaje();
					}
				}
			} else {
				$data["mensaje"] = "Complete todos los campos marcados con *.";
			}

		} else {
			$this->output->set_status_header('405');
		}

		$this->output->set_output(json_encode($data));
	}

}

/* End of file Cliente.php */
/* Location: ./application/controllers/Cliente.php */