<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Marca extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->model(['producto/Marca_model']);
		$this->output->set_content_type('application/json');
	}

	public function index()
	{
		$this->output->set_status_header('404');
	}

	public function buscar()
	{	
		$data = [
			"lista" => $this->Marca_model->_buscar($_GET)
		];

		$this->output->set_output(json_encode($data));
	}

	public function guardar($id="")
	{
		$data = ["exito" => 0];

		if ($this->input->method() === "post") {
			$datos = json_decode(file_get_contents("php://input"));

			if (verPropiedad($datos, "nombre")) {
				$marca = new Marca_model($id);

				if ($marca->guardar($datos)) {
					$texto = empty($id) ? "guardada":"actualizada";
					
					$data["exito"] = 1;
					$data["mensaje"] = "Marca {$texto} con éxito.";
					$data["linea"] = $marca->_buscar([
						"id" => $marca->getPK(), 
						"uno" => true
					]);

				} else {
					$data["mensaje"] = $marca->getMensaje();
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

/* End of file Marca.php */
/* Location: ./application/controllers/Marca.php */