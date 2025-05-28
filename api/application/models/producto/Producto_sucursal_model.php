<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Producto_sucursal_model extends General_model {

	public $producto_id;
	public $sucursal_id;
	public $activo = 1;

	public function __construct()
	{
		parent::__construct();
		
	}

}

/* End of file Producto_sucursal_model.php */
/* Location: ./application/models/Producto_sucursal_model.php */