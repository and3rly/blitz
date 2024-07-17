<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Principal extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->model([
			"compra/Compra_model",
			"recepcion/Detalle_model", 
			"recepcion/Recepcion_model",
			"stock/Stock_model"
		]);

		$this->output->set_content_type('application/json');
	}

	public function index()
	{
		$this->output->set_status_header('404');
	}

	public function recibir($id='')
	{
		$data = ["exito" => 0];
		$datos = json_decode(file_get_contents("php://input"));

		if ($this->input->method() === "post") {

			if (verPropiedad($datos, "detalle")) {
				
				$rec = new Recepcion_model();

				if (empty($id))	 {
					$rec->set_correlativo();
				}

				$datosRec = [
					"fecha_recepcion" => Hoy(),
					"compra_id" => $datos->id
				];

				if ($rec->guardar($datosRec)) {
					foreach ($datos->detalle as $row) {

						$detalle = [
							"cantidad" => $row->cantidad,
							"producto_id"  => $row->producto_id,
							"recepcion_id" => $rec->getPK(),
							"compra_detalle_id"  => $row->id,
							"producto_precio_id" => $row->producto_precio_id	
						];

						$det = new Detalle_model();
						$continuar = $det->guardar($detalle);

						if ($continuar) {
							$stock = new Stock_model();

							$dstock = [
								"cantidad" => $row->cantidad,
								"producto_id"  => $row->producto_id,
								"recepcion_id" => $rec->getPK(),
								"recepcion_detalle_id" => $det->getPK(),
								"producto_precio_id" => $row->producto_precio_id
							];

							$stock->guardar($dstock);	
						}
					}

					$compra = new Compra_model();
					$compra->actEstadoOc(["compra" => $datos->id]);

					$data["exito"] = 1;
					$data["compra"] = $compra->_buscar([
						"id"  => $datos->id, 
						"uno" => true
					]);
					
					$data["mensaje"] = "Recepción realizada con éxito.";
				}

			} else {
				$data["mensaje"] = "Falta el encabezado y detalle de la OC";
			}
		} else {
			$this->output->set_status_header('405');
		}

		$this->output->set_output(json_encode($data));
	}

}

/* End of file Principal.php */
/* Location: ./application/controllers/Principal.php */