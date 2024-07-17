<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Establecimiento extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->model(["mnt/Establecimiento_model"]);
		$this->output->set_content_type('application/json');
	}

	public function index()
	{
		$this->output->set_status_header('404');
	}

	public function buscar()
	{	
		$data = [
			"lista" => $this->Establecimiento_model->_buscar($_GET)
		];

		$this->output->set_output(json_encode($data));
	}

	public function get_datos()
	{
		$data = [
			"cat" => [
				"departamentos" => $this->catalogo->ver_departamento(),
				"municipios"    => $this->catalogo->ver_municipio()
			]
		];

		$this->output->set_output(json_encode($data));
	}

	public function guardar($id="")
	{
		$data = ["exito" => 0];

		if ($this->input->method() === "post") {
			$datos = json_decode(file_get_contents("php://input"));

			if (verPropiedad($datos, "nombre")) {
				$est = new Establecimiento_model($id);

				if ($est->existe(["nombre" => $datos->nombre])) {
					$data["mensaje"] = "El establecmiento ya está registrado.";
				} else {

					if ($est->guardar($datos)) {
						$data["exito"] = 1;
						$data["mensaje"] = "Establecmiento registrado con éxito.";
						$data["linea"] = $est->_buscar(["id" => $est->getPK(), "uno" => true]);
					} else {
						$data["mensaje"] = $est->getMensaje();
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

/* End of file Establecimiento.php */
/* Location: ./application/controllers/Establecimiento.php */