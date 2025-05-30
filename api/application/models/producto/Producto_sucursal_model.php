<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Producto_sucursal_model extends General_model {

	public $producto_id;
	public $sucursal_id;
	public $activo = 1;

	public function __construct($id="")
	{
		parent::__construct();
		if (!empty($id)) {
			$this->cargar($id);
		}
	}

	public function _buscar($args=[])
	{
		if (elemento($args, "sucursal")) {
			$this->db->where("a.sucursal_id", $args["sucursal"]);
		}

		$tmp = $this->db
		->select("
			a.*,
			a.id as producto_sucursal_id,
			b.nombre as nombre_producto,
			c.nombre as nombre_marca,
			d.nombre as nombre_categoria,
			d.etiqueta
		")
		->join("producto b","b.id = a.producto_id")
		->join("marca c","c.id = b.marca_id")
		->join("categoria d","d.id = b.categoria_id")
		->join("unidad_medida e","e.id = b.unidad_medida_id")
		->where("a.activo", 1)
		->get("{$this->_tabla} a");

		return verConsulta($tmp, $args);
	}
}

/* End of file Producto_sucursal_model.php */
/* Location: ./application/models/Producto_sucursal_model.php */