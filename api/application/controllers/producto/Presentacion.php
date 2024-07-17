<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Presentacion extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->model(["producto/Presentacion_model"]);
		$this->output->set_content_type('application/json');
	}

	public function index()
	{
		$this->output->set_status_header('404');
	}

	public function buscar()
	{
		$data = [
			"lista" => $this->Presentacion_model->_buscar($_GET)
		];

		$this->output->set_output(json_encode($data));
	}

	public function guardar($id="")
	{
		$data = ["exito" => 0];

		if ($this->input->method() === "post") {
			$datos = json_decode(file_get_contents("php://input"));

			if (verPropiedad($datos, "producto_id") && 
				verPropiedad($datos, "codigo") && 
				verPropiedad($datos, "nombre") &&
				verPropiedad($datos, "factor")) {
				
				$pres = new Presentacion_model($id);

				if ($pres->existePresentacion([
					"nombre" => $datos->nombre, 
					"factor" => $datos->factor,
					"producto" => $datos->producto_id
				])) {
					
					$data["mensaje"] = "Ya una presentación con el mismo nombre y factor para este producto.";
				} else {
					if ($pres->guardar($datos)) {
						$data["exito"] = 1;
						$texto = empty($id) ? "guardada":"actualizada";

						$data["mensaje"] = "Presentación {$texto} con éxito.";
						$data["linea"] = $pres->_buscar([
							"id" => $pres->getPK(), 
							"uno" => true
						]);

					} else {
						$data["mensaje"] = $pres->getMensaje();
					}
				}
			} else {	
				$data["mensaje"] = "Complete los campos marcados con *";
			}
		}

		$this->output->set_output(json_encode($data));
	}
}

/* End of file Presentacion.php */
/* Location: ./application/controllers/Presentacion.php */