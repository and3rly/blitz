<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Principal extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model(['producto/Producto_model']);
		$this->output->set_content_type('application/json');
	}

	public function index()
	{
		$this->output->set_status_header('404');
	}

	public function get_datos()
	{
		$data = [
			"cat" => [
				"marcas"     => $this->catalogo->ver_marca(),
				"categorias" => $this->catalogo->ver_categoria(),
				"ums"        => $this->catalogo->ver_um()
			]
		];

		$this->output->set_output(json_encode($data));
	}

	public function buscar()
	{	
		$data = [
			"lista" => $this->Producto_model->_buscar($_GET)
		];

		$this->output->set_output(json_encode($data));
	}

	public function guardar($id="")
	{
		$data = ["exito" => 0];

		if ($this->input->method() === "post") {
			$datos = json_decode(file_get_contents("php://input"));

			if (verPropiedad($datos, "nombre") &&
				verPropiedad($datos, "marca_id") &&
				verPropiedad($datos, "categoria_id") && 
				verPropiedad($datos, "unidad_medida_id")) {
				
				$producto = new Producto_model($id);

				if ($producto->guardar($datos)) {
					$texto = empty($id) ? "guardado":"actualizado";
					
					$data["exito"] = 1;
					$data["mensaje"] = "Producto {$texto} con éxito.";
					$data["linea"] = $producto->_buscar([
						"id" => $producto->getPK(), 
						"uno" => true
					]);

				} else {
					$data["mensaje"] = $producto->getMensaje();
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

/* End of file Principal.php */
/* Location: ./application/controllers/producto/Principal.php */