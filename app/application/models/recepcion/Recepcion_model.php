<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Recepcion_model extends General_model {

	public $correlativo;
	public $documento;
	public $descripcion = null;
	public $fecha_recepcion = null;
	public $activa = 1;
	public $usuario_id;
	public $compra_id;

	public function __construct($id="")
	{
		parent::__construct();
		if (!empty($id)) {
			$this->cargar($id);
		}
	}

	public function set_correlativo()
	{
		$tmp = $this->db
		->select("IFNULL(max(correlativo),0)+1 as correlativo", false)
		->get("{$this->_tabla}")
		->row()->correlativo;

		$this->correlativo = $tmp;

		$ctmp = str_pad($tmp, 10, "0", STR_PAD_LEFT);
		$cfinal = "RC" . $ctmp;

		$this->documento = $cfinal;
	}
}

/* End of file Recepcion_model.php */
/* Location: ./application/models/Recepcion_model.php */