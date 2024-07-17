<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Detalle extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->model(["compra/Detalle_model", "producto/Precio_model","compra/Compra_model"]);
		$this->output->set_content_type('application/json');
	}

	public function index()
	{
		$this->output->set_status_header('404');
	}

	public function buscar()
	{
		$data = [
			"lista" => $this->Detalle_model->_buscar($_GET)
		];

		$this->output->set_output(json_encode($data));
	}

	public function get_datos()
	{
		$data = [
			"cat" => [
				"productos" => $this->catalogo->ver_producto_precio($_GET),
				"presentaciones" => $this->catalogo->ver_presentacion()
			]
		];

		$this->output->set_output(json_encode($data));
	}

	public function guardar($id="")
	{
		$data = ["exito" => 0];

		if ($this->input->method() === "post") {
			$datos = json_decode(file_get_contents("php://input"));

			if (verPropiedad($datos, "cantidad") &&
				verPropiedad($datos, "precio") &&
				verPropiedad($datos, "costo") &&
				verPropiedad($datos, "compra_id") && 
				verPropiedad($datos, "producto_precio_id")) {

				$det = new Detalle_model($id);

				if ($det->guardar($datos)) {
					$data["exito"] = 1;

					$det->actualizaTotalOc(["compra" => $datos->compra_id]);
					
					$texto = empty($id) ? "guardado": "actualizado";
					
					$data["tcompra"] = $det->getCompra()->total;
					$data["mensaje"] = "Detalle {$texto} con éxito.";
					$data["linea"] = $det->_buscar([
						"id" => $det->getPK(), 
						"uno" => true
					]);

				} else {
					$data["mensaje"] = $det->getMensaje();
				}
			} else {
				$data["mensaje"] = "Complete todos los campos marcados con *.";
			}
		} else {
			$this->output->set_status_header('405');
		}

		$this->output->set_output(json_encode($data));
	}

	public function eliminar_producto($id)
	{
		$data = ["exito" => 0];

		if ($this->input->method() === "post") { 

			$det = new Detalle_model($id);
			$datos = ["anulado" => 1];

			if ($det->guardar($datos)) {
				$data["exito"] = 1;
				$data["mensaje"] = "Producto anulado con éxito.";
			} else {
				$data["mensaje"] = $det->getMensaje();
			}
		} else {
			$this->output->set_status_header('405');
		}

		$this->output->set_output(json_encode($data));
	}
}

/* End of file Detalle.php */
/* Location: ./application/controllers/Detalle.php */