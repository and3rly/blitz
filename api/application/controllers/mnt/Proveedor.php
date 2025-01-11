<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Proveedor extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->model(["mnt/Proveedor_model"]);
		$this->output->set_content_type('application/json');
	}

	public function index()
	{
		$this->output->set_status_header('404');
	}

	public function buscar()
	{	
		$data = [
			"lista" => $this->Proveedor_model->_buscar($_GET)
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

			if (verPropiedad($datos, "nombre") && 
				verPropiedad($datos, "departamento_id") && 
				verPropiedad($datos, "municipio_id")) {
				
				$proveedor = new Proveedor_model($id);

				if ($proveedor->existe(["nombre" => $datos->nombre, "identificacion" => $datos->identificacion])) {
					$data["mensaje"] = "El proveedor ya está registrado.";
				} else {

					if ($proveedor->guardar($datos)) {
						$data["exito"]   = 1;
						$data["mensaje"] = "Proveedor registrado con éxito.";
						$data["linea"]   = $proveedor->_buscar(["id" => $proveedor->getPK(), "uno" => true]);
					} else {
						$data["mensaje"] = $proveedor->getMensaje();
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

/* End of file Proveedor.php */
/* Location: ./application/controllers/Proveedor.php */