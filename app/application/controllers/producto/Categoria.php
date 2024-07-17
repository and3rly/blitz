<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Categoria extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model(['producto/Categoria_model']);

		$this->output->set_content_type('application/json');
	}

	public function index()
	{
		$this->output->set_status_header('404');
	}

	public function buscar()
	{	
		$data = [
			"lista" => $this->Categoria_model->_buscar($_GET)
		];

		$this->output->set_output(json_encode($data));
	}

	public function guardar($id="")
	{
		$data = ["exito" => 0];

		if ($this->input->method() === "post") {
			$datos = json_decode(file_get_contents("php://input"));

			if (verPropiedad($datos, "nombre")) {
				$cat = new Categoria_model($id);

				if ($cat->guardar($datos)) {
					$texto = empty($id) ? "guardada":"actualizada";
					
					$data["exito"] = 1;
					$data["mensaje"] = "Categoría {$texto} con éxito.";
					$data["linea"] = $cat->_buscar([
						"id" => $cat->getPK(), 
						"uno" => true
					]);

				} else {
					$data["mensaje"] = $cat->getMensaje();
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

/* End of file Categoria.php */
/* Location: ./application/controllers/Categoria.php */