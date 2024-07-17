<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Producto_model extends General_model {

	public $codigo = null;
	public $nombre;
	public $descripcion = null;
	public $imagen_key = null;
	public $imagen_enlace = null;
	public $costo = null;
	public $precio = null;
	public $activo = 1;
	public $tipo;
	public $marca_id;
	public $categoria_id;
	public $unidad_medida_id;

	public function __construct($id="")
	{
		parent::__construct();
		if (!empty($id)) {
			$this->cargar($id);
		}
	}

	public function _buscar($args=[])
	{	
		if (elemento($args, "id")) {
			$this->db->where("a.id", $args["id"]);
		} else {
			if (elemento($args, 'termino')) {
				$termino = strtolower(trim($args["termino"]));

				$this->db->where("(
					a.nombre like '%{$termino}%' or
					b.nombre like '%{$termino}%'
				)", null, false);
			}
		}

		$tmp = $this->db
		->select("a.*,
			b.nombre as nombre_marca,
			c.nombre as nombre_categoria,
			c.color,
			d.nombre as nombre_um")
		->join("marca b","b.id = a.marca_id")
		->join("categoria c","c.id = a.categoria_id")
		->join("unidad_medida d","d.id = a.unidad_medida_id")
		->get("$this->_tabla a");

		return verConsulta($tmp, $args);
	}

}

/* End of file Producto_model.php */
/* Location: ./application/models/Producto_model.php */