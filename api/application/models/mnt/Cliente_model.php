<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cliente_model extends General_model {

	public $codigo = null;
	public $nombre;
	public $razon_social;
	public $identificacion;
	public $telefono;
	public $direccion;
	public $correo;
	public $activo = 1;
	public $departamento_id;
	public $municipio_id;
	public $tipo_documento_id;

	public function __construct($id="")
	{
		parent::__construct();
		if (!empty($id)) {
			$this->cargar($id);
		}
	}

	public function existe($args=[])
	{	
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
			c.nombre as nombre_municipio,
			d.nombre as tipo_documento")
		->join("departamento b","b.id = a.departamento_id","left")
		->join("municipio c","c.id = a.municipio_id","left")
		->join("tipo_documento d","d.id = a.tipo_documento_id", "left")
		->order_by("a.fecha", "desc")
		->get("$this->_tabla a");

		return verConsulta($tmp, $args);
	}
}

/* End of file Cliente_model.php */
/* Location: ./application/models/Cliente_model.php */