<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Detalle_model extends General_model {

	public $orden_compra_id;
	public $producto_sucursal_id;
	public $producto_presentacion_id = null;
	public $cantidad = 1;
	public $cantidad_recibida = 0;
	public $anulado = 0;
	public $total = 0;
	public $precio = 0;
	public $usuario_id;
	public $usuario_modifico = null;
	public $fecha_modificacion = null;

	public function __construct($id="")
	{
		parent::__construct();
		$this->setTabla("orden_compra_detalle");
		$this->setLlave("id");

		if (!empty($id)) {
			$this->cargar($id);
		}		
	}

	public function actualizaTotalOc($args=[])
	{
		$tmp = $this->db
		->select("sum(IFNULL(total,0)) as total")
		->where("orden_compra_id", $args["compra"])
		->where("anulado", 0)
		->get("{$this->_tabla}")
		->row();
		
		$this->db
		->set("total", $tmp->total)
		->where("id", $args["compra"])
		->update("orden_compra");

		return $this->db->affected_rows() > 0;
	}

	public function _buscar($args=[])
	{	
		if (elemento($args, "orden_compra_id")) {
			$this->db->where("a.orden_compra_id", $args["orden_compra_id"]);
		}

		$tmp = $this->db
		->select("
			a.*, 
			c.id as producto_id,
			c.nombre as nombre_producto,
			c.imagen_key"
		)
		->join("producto_sucursal b","b.id = a.producto_sucursal_id")
		->join("producto c","c.id = b.producto_id")
		->get("{$this->_tabla} a");

		return verConsulta($tmp, $args);
	}

	public function getCompra($args=[])
	{
		return new Compra_model($this->orden_compra_id);
	}
}

/* End of file Detalle_model.php */
/* Location: ./application/models/Detalle_model.php */