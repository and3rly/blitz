<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sucursal_model extends General_model {

	public $nombre;
	public $razon_social = null;
	public $direccion = null;
	public $telefono = null;
	public $activo = 1;
	public $empresa_id;
	public $departamento_id;
	public $municipio_id;

	public function __construct($id="")
	{
		parent::__construct();

		if (!empty($id)) {
			$this->cargar($id);
		}
		
	}

	public function existe($args=[])
	{	
		$ses = $this->session->userdata("usuario");

		if ($this->getPK()) {
			$this->db->where("id <> ", $this->getPK());
		}

		$tmp = $this->db
		->where("nombre", $args["nombre"])
		->where("empresa_id", $ses["empresa_id"])
		->where("activo", 1)
		->get("$this->_tabla");

		return $tmp->num_rows() > 0;
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
					a.razon_social like '%{$termino}%' or
					a.direccion like '%{$termino}%' or
					b.nombre like '%{$termino}%' or
					c.nombre like '%{$termino}%'
				)", null, false);
			}
		}

		$tmp = $this->db
		->select("a.*, 
			b.nombre as nombre_departamento, 
			c.nombre as nombre_municipio")
		->join("departamento b","b.id = a.departamento_id","left")
		->join("municipio c","c.id = a.municipio_id","left")
		->get("$this->_tabla a");

		return verConsulta($tmp, $args);
	}

}

/* End of file Establecimiento_model.php */
/* Location: ./application/models/Establecimiento_model.php */