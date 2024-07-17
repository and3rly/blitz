<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Moneda extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model(["mnt/Moneda_model"]);
		$this->output->set_content_type('application/json');
	}

	public function index()
	{
		$this->output->set_status_header('404');
	}

	public function buscar()
	{	
		$data = [
			"lista" => $this->Moneda_model->_buscar($_GET)
		];

		$this->output->set_output(json_encode($data));
	}

	public function guardar($id="")
	{
		$data = ["exito" => 0];

		if ($this->input->method() === "post") {
			$datos = json_decode(file_get_contents("php://input"));

			if (verPropiedad($datos, "nombre") && 
				verPropiedad($datos, "simbolo")) {
				$moneda = new Moneda_model($id);

				if ($moneda->guardar($datos)) {
					$texto = empty($id) ? "guardada":"actualizada";
					
					$data["exito"] = 1;
					$data["mensaje"] = "Moneda {$texto} con éxito.";
					$data["linea"] = $moneda->_buscar([
						"id" => $moneda->getPK(), 
						"uno" => true
					]);

				} else {
					$data["mensaje"] = $moneda->getMensaje();
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

/* End of file Moneda.php */
/* Location: ./application/controllers/Moneda.php */