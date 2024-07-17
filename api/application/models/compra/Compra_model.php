<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Compra_model extends General_model {

	public $documento;
	public $referencia;
	public $correlativo;
	public $fecha_documento = null;
	public $fecha_modificacion = null;
	public $total = 0;
	public $anulada = 0;
	public $establecimiento_id;
	public $proveedor_id;
	public $usuario_id;
	public $usuario_modifica = null;
	public $compra_estado_id;
	public $tipo_pago_id;
	public $moneda_id;
	public $recibido = 0;

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

	public function actEstadoOc($args=[])
	{		
		$this->db
		->set("compra_estado_id", 2)
		->set("recibido", 1)
		->where("id", $args["compra"])
		->update("compra");

		return $this->db->affected_rows() > 0;
	}

	public function _buscar($args=[])
	{	
		if (elemento($args, "id")) {
			$this->db->where("a.id", $args["id"]);
		}

		$tmp = $this->db
		->select("a.*,
			b.nombre as nombre_establecimiento,
			c.nombre as nombre_proveedor,
			d.nombre as nombre_estado,
			d.color,
			e.nombre as nombre_pago,
			f.simbolo,
			f.nombre as nombre_moneda")
		->join("establecimiento b","b.id = a.establecimiento_id")
		->join("proveedor c","c.id = a.proveedor_id")
		->join("compra_estado d","d.id = a.compra_estado_id")
		->join("tipo_pago e","e.id = a.tipo_pago_id")
		->join("moneda f","f.id = a.moneda_id")
		->order_by("a.fecha", "desc")
		->get("$this->_tabla a");

		return verConsulta($tmp, $args);
	}

}

/* End of file Compra_model.php */
/* Location: ./application/models/Compra_model.php */