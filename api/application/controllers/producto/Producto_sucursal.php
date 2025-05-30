<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Producto_sucursal extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model([
			"producto/Producto_model", 
			"producto/Producto_sucursal_model"
		]);
		$this->output->set_content_type('application/json');
	}

	public function index()
	{
		$this->output->set_status_header("404");
	}

	public function get_datos()
	{
		$data = [
			"cat" => [
				"sucursales" => $this->catalogo->ver_sucursal(),
				"productos"  => $this->Producto_model->_buscar()
			]
		];

		$this->output->set_output(json_encode($data));
	}

	public function buscar() 
	{
		$data = [
			"lista" => $this->Producto_sucursal_model->_buscar($_GET)
		];

		$this->output->set_output(json_encode($data));
	}

	public function guardar($id="")
	{	
		$data = ["exito" => 0, "mensaje" => []];

		if ($this->input->method() === "post") {
			$datos = json_decode(file_get_contents("php://input"));

			if (verPropiedad($datos, "producto") && 
				verPropiedad($datos, "sucursal")) {

				$registros = 0;
				$sucursal = $datos->sucursal;

				foreach ($datos->producto as $row) {
					if (verPropiedad($row, "producto_sucursal_id")) {
						$ps = new Producto_sucursal_model($row->id);

						$ps->guardar(["activo" => 0]);
						$registros++;
						$data["mensaje"][] = "{$row->nombre_producto} desasignado de la sucursal con éxito.";
            			continue;
					}

					$producto = $row->id ?? null;
					
					if (!$producto) {
						continue;
					}

					$ps = new Producto_sucursal_model();
					$asignado = $ps->buscar([
			            "producto_id" => $producto,
			            "sucursal_id" => $sucursal,
			            "_uno"        => true
			        ]);

			        if (!$asignado) {
			        	$ps->guardar([
			                "producto_id" => $producto,
			                "sucursal_id" => $sucursal
			            ]);
			            $data["mensaje"][] = "{$row->nombre} asignado a la sucursal.";
			            $registros++;
			        } else {
			        	$ps = new Producto_sucursal_model($asignado->id);

			            if ($asignado->activo == 0) {
			            	$registros++;
			                $ps->guardar(["activo" => 1]);
			                $data["mensaje"][] = "{$row->nombre} reasignado a la sucursal.";
			            } else {
			                $data["mensaje"][] = "{$row->nombre} ya estaba asignado.";
			            }
			        }
				}

				if ($registros > 0) {
					$data["exito"] = 1;
				}

				$data["lista"] = $this->Producto_sucursal_model->_buscar([
					"sucursal" => $sucursal, 
					"activo" => 1
				]);

				if (!empty($data["mensaje"])) {
				    $data["mensaje"] = 
				    "<ul style='margin:0;padding-left:20px'>" .
				        implode('', array_map(fn($m) => "<li>$m</li>", $data["mensaje"])) .
				    "</ul>";
				}
			}
		} else {
			$this->output->set_status_header('405');
		}

		$this->output->set_output(json_encode($data));
	}
}

/* End of file Producto_sucursal.php */
/* Location: ./application/controllers/Producto_sucursal.php */