<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Detalle_model extends General_model {

	public $cantidad;
	public $fecha_vence = null;
	public $lote = null;
	public $compra_detalle_id;
	public $producto_precio_id;
	public $producto_id;
	public $recepcion_id;
	public $presentacion_id = null;
	
	public function __construct()
	{
		parent::__construct();
		$this->setTabla("recepcion_detalle");
		$this->setLlave("id");

		if (!empty($id)) {
			$this->cargar($id);
		}	
		
	}

}

/* End of file Detalle_model.php */
/* Location: ./application/models/Detalle_model.php */