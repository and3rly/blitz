<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Orden extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model(["compra/Compra_model"]);
		$this->output->set_content_type('application/json');
	}

	public function index()
	{
		$this->output->set_status_header('404');
	}

	public function buscar()
	{
		$data = [
			"lista" => $this->Compra_model->_buscar($_GET)
		];

		$this->output->set_output(json_encode($data));
	}

	public function get_datos()
	{
		$data = [
			"cat" => [
				"pago"   => $this->catalogo->ver_tipo_pago(),
				"estado" => $this->catalogo->ver_compra_estado(),
				"proveedor" => $this->catalogo->ver_proveedor(),
				"establecimiento" => $this->catalogo->ver_establecimiento(),
				"moneda" => $this->catalogo->ver_moneda()
			]
		];

		$this->output->set_output(json_encode($data));
	}

	public function guardar($id="")
	{
		$data = ["exito" => 0];

		if ($this->input->method() === "post") {
			$datos = json_decode(file_get_contents("php://input"));

			if (verPropiedad($datos, "referencia") &&
				verPropiedad($datos, "tipo_pago_id") &&
				verPropiedad($datos, "proveedor_id") &&
				verPropiedad($datos, "establecimiento_id") &&
				verPropiedad($datos, "compra_estado_id")) {

				$compra = new Compra_model($id);

				if (empty($id))	 {
					$compra->set_correlativo();
				}

				if ($compra->guardar($datos)) {
					$data["exito"] = 1;

					$texto = empty($id) ? "creada": "actualizada";
					$data["mensaje"] = "Orden de {$texto} creada con éxito.";
					$data["linea"] = $compra->_buscar([
						"id" => $compra->getPK(), 
						"uno" => true
					]);
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

/* End of file Orden.php */
/* Location: ./application/controllers/Orden.php */