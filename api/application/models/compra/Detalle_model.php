<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Detalle_model extends General_model {

	public $cantidad;
	public $precio;
	public $costo;
	public $total;
	public $anulado = 0;
	public $compra_id;
	public $producto_precio_id;
	public $producto_id;
	public $presentacion_id = null;

	public function __construct($id="")
	{
		parent::__construct();
		$this->setTabla("compra_detalle");
		$this->setLlave("id");

		if (!empty($id)) {
			$this->cargar($id);
		}		
	}

	public function actualizaTotalOc($args=[])
	{
		$tmp = $this->db
		->select("sum(IFNULL(total,0)) as total")
		->where("compra_id", $args["compra"])
		->where("anulado", 0)
		->get("{$this->_tabla}")
		->row();
		
		$this->db
		->set("total", $tmp->total)
		->where("id", $args["compra"])
		->update("compra");

		return $this->db->affected_rows() > 0;
	}

	public function _buscar($args=[])
	{	
		if (elemento($args, "id")) {
			$this->db->where("a.id", $args["id"]);
		} else {

			if (elemento($args, "compra")) {
				$this->db->where("a.compra_id", $args["compra"]);
			}
		}

		$tmp = $this->db
		->select("a.*, 
			c.nombre as nombre_producto,
			d.nombre as nombre_categoria,
			d.color,
			e.codigo,
			e.nombre,
			e.factor")
		->join("producto_precio b","b.id = a.producto_precio_id")
		->join("producto c","c.id = b.producto_id")
		->join("categoria d","d.id = c.categoria_id")
		->join("presentacion e","e.id = a.presentacion_id", "left")
		->where("a.anulado", 0)
		->get("{$this->_tabla} a");

		return verConsulta($tmp, $args);
	}

	public function getCompra($args=[])
	{
		return new Compra_model($this->compra_id);
	}
}

/* End of file Detalle_model.php */
/* Location: ./application/models/Detalle_model.php */