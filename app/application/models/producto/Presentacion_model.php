<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Presentacion_model extends General_model {

	public $codigo;
	public $nombre;
	public $factor;
	public $activo = 1;
	public $producto_id;

	public function __construct($id="")
	{
		parent::__construct();
		
		if (!empty($id)) {
			$this->cargar($id);
		}
	}

	public function existePresentacion($args=[])
	{
		if ($this->getPK()) {
			$this->db->where("id <>", $this->getPK());
		}

		$tmp = $this->db
		->where("nombre", $args["nombre"])
		->where("factor", $args["factor"])
		->where("producto_id", $args["producto"])
		->where("activo", 1)
		->get("{$this->_tabla}");

		return $tmp->num_rows() > 0;
	}

	public function _buscar($args=[])
	{	
		if (elemento($args, "id")) {
			$this->db->where("id", $args["id"]);
		} else {
			if (elemento($args, "producto_id")) {
				$this->db->where("producto_id", $args["producto_id"]);
			}
		}

		$tmp = $this->db->get("{$this->_tabla}");

		return verConsulta($tmp, $args);
	}
}

/* End of file Presentacion_model.php */
/* Location: ./application/models/Presentacion_model.php */