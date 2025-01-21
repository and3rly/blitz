<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Usuario_sucursal_model extends General_model {

	public $usuario_id;
	public $sucursal_id;
	public $activo = 1;

	public function __construct($id="")
	{
		parent::__construct();
		if (!empty($id)) {
			$this->cargar($id);
		}		
	}

}

/* End of file Usuario_sucursal_model.php */
/* Location: ./application/models/Usuario_sucursal_model.php */