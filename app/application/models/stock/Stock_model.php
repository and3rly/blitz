<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Stock_model extends General_model {

	public $cantidad;
	public $lote = null;
	public $fecha_vence = null;
	public $recepcion_id;
	public $recepcion_detalle_id;
	public $producto_id;
	public $producto_precio_id;
	public $presentacion_id;
	public $usuario_id;
	
	public function __construct($id="")
	{
		parent::__construct();
		if (!empty($id)) {
			$this->cargar($id);
		}
	}

}

/* End of file Stock_model.php */
/* Location: ./application/models/Stock_model.php */