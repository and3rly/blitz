<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Precio_model extends General_model {

	public $costo;
	public $precio;
	public $activo = 1;
	public $producto_id;
	public $proveedor_id;

	public function __construct($id="")
	{
		parent::__construct();
		$this->setTabla("producto_precio");
		$this->setLlave("id");

		if (!empty($id)) {
			$this->cargar($id);
		}
	}


	public function existe_precio($args=[])
	{
		if ($this->getPK()) {
			$this->db->where("id <>", $this->getPK());
		}

		$tmp = $this->db
		->where("producto_id", $args["producto_id"])
		->where("proveedor_id", $args["proveedor_id"])
		->where("activo", 1)
		->get("{$this->_tabla}");

		return $tmp->num_rows() > 0;
	}

	public function _buscar($args=[])
	{	
		if (elemento($args, "id")) {
			$this->db->where("a.id", $args["id"]);
		} else {
			if (elemento($args, "producto_id")) {
				$this->db->where("a.producto_id", $args["producto_id"]);
			}
		}

		$tmp = $this->db
		->select("a.*,
			b.nombre as nombre_proveedor")
		->join("proveedor b","b.id = a.proveedor_id")
		->get("{$this->_tabla} a");

		return verConsulta($tmp, $args);
	}

}

/* End of file Precio_model.php */
/* Location: ./application/models/Precio_model.php */