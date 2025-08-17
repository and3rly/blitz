<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Compra_model extends General_model {

	public $correlativo;
	public $documento;
	public $total = 0;
	public $observacion = null;
	public $referencia = null;
	public $proveedor_id;
	public $sucursal_id;
	public $usuario_id;
	public $estado_orden_compra_id;
	public $fecha_modificacion = null;
	public $usuario_modifica = null;
	public $moneda_id;
	public $tipo_pago_id;

	public function __construct($id="")
	{
		parent::__construct();
		$this->setTabla("orden_compra");
		$this->setLlave("id");

		if (!empty($id)) {
			$this->cargar($id);
		}
	}

	public function get_correlativo()
	{
	    $tmp = $this->db
	        ->select("IFNULL(MAX(correlativo), 0) + 1 AS correlativo", false)
	        ->get("{$this->_tabla}")
	        ->row()
	        ->correlativo;

	    $ctmp = str_pad($tmp, 10, "0", STR_PAD_LEFT);
	    $cfinal = "OC" . $ctmp;

	    return (object) [
	        'correlativo' => $tmp,
	        'documento'   => $cfinal
	    ];
	}

	public function _buscar($args=[])
	{
		if (elemento($args, "id")) {
			$this->db->where("a.id", $args["id"]);
		} 

		$tmp = $this->db
		->select("
			a.*,
			b.nombre as nombre_proveedor,
			c.nombre as nombre_sucursal,
			d.nombre as nombre_moneda,
			d.simbolo,
			e.nombre as nombre_pago,
			f.nombre as nombre_estado,
			f.color
		")
		->join("proveedor b","b.id = a.proveedor_id")
		->join("sucursal c","c.id = a.sucursal_id")
		->join("moneda d","d.id = a.moneda_id")
		->join("tipo_pago e","e.id = a.tipo_pago_id")
		->join("estado_orden_compra f","f.id = a.estado_orden_compra_id")
		->order_by("a.fecha", "desc")
		->get("{$this->_tabla} a");

		return verConsulta($tmp, $args);
	}
}

/* End of file Compra_model.php */
/* Location: ./application/models/Compra_model.php */