<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Compra_model extends General_model {

	public $correlativo;
	public $documento;
	public $referencia;
	public $total;
	public $observacion = null;
	public $referencia = null;
	public $proveedor_id;
	public $sucursal_id;
	public $usuario_id;
	public $estado_orden_compra_id;
	public $fecha_modificacion = null;
	public $usuario_modifica = null;
	public $moneda_id;

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
		$cfinal = "OC" . $ctmp;

		$this->documento = $cfinal;
	}
}

/* End of file Compra_model.php */
/* Location: ./application/models/Compra_model.php */