<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Unidad_medida extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->model(["mnt/Um_model"]);
		$this->output->set_content_type("application/json");
	}

	public function index()
	{
		$this->output->set_status_header("404");
	}

	public function buscar()
	{	
		$data = [
			"lista" => $this->Um_model->_buscar($_GET)
		];

		$this->output->set_output(json_encode($data));
	}
	
	public function guardar($id='')
	{
		$data = ["exito" => 0];

		if ($this->input->method() === "post") {
			$datos = json_decode(file_get_contents("php://input"));

			if (verPropiedad($datos, "codigo") &&
				verPropiedad($datos, "nombre")) {
				$um = new Um_model($id);

				if ($um->guardar($datos)) {
					$texto = empty($id) ? "guardada":"actualizada";
					
					$data["exito"] = 1;
					$data["mensaje"] = "Unidad de medida {$texto} con éxito.";
					$data["linea"] = $um->_buscar([
						"id" => $um->getPK(), 
						"uno" => true
					]);

				} else {
					$data["mensaje"] = $um->getMensaje();
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

/* End of file Unidad_medida.php */
/* Location: ./application/controllers/Unidad_medida.php */