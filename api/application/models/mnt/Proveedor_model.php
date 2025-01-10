<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Proveedor_model extends General_model {

	public $codigo = null;
	public $nombre;
	public $identificacion = null;
	public $telefono = null;
	public $direccion = null;
	public $correo = null;
	public $activo = 1;
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
		->where("identificacion", $args["identificacion"])
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
					a.identificacion like '%{$termino}%' or
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

/* End of file Proveedor_model.php */
/* Location: ./application/models/mnt/Proveedor_model.php */