<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Precio extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->model(["producto/Precio_model"]);
		$this->output->set_content_type('application/json');
	}

	public function index()
	{
		$this->output->set_status_header('404');
	}

	public function buscar()
	{
		$data = [
			"lista" => $this->Precio_model->_buscar($_GET)
		];

		$this->output->set_output(json_encode($data));
	}

	public function get_datos()
	{
		$data = [
			"cat" => [
				"proveedores" => $this->catalogo->ver_proveedor()
			]
		];

		$this->output->set_output(json_encode($data));
	}

	public function guardar($id="")
	{
		$data = ["exito" => 0];

		if ($this->input->method() === "post") {
			$datos = json_decode(file_get_contents("php://input"));

			if (verPropiedad($datos, "producto_id") && 
				verPropiedad($datos, "proveedor_id") && 
				verPropiedad($datos, "costo") &&
				verPropiedad($datos, "precio")) {
				
				$precio = new Precio_model($id);

				if ($precio->existe_precio([
					"producto_id" => $datos->producto_id, 
					"proveedor_id" => $datos->proveedor_id
				])) {
					
					$data["mensaje"] = "Ya existe un precio para este producto.";
				} else {

					$datos->costo = round($datos->costo, 2);
					if ($precio->guardar($datos)) {
						$data["exito"] = 1;
						$texto = empty($id) ? "guardado":"actualizado";

						$data["mensaje"] = "Precio {$texto} con éxito.";
						$data["linea"] = $precio->_buscar([
							"id" => $precio->getPK(), 
							"uno" => true
						]);

					} else {
						$data["mensaje"] = $precio->getMensaje();
					}
				}
			} else {	
				$data["mensaje"] = "Complete los campos marcados con *";
			}
		}

		$this->output->set_output(json_encode($data));
	}
}

/* End of file Precio.php */
/* Location: ./application/controllers/Precio.php */