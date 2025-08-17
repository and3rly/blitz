<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Presentacion_model extends General_model {

	public $nombre;
	public $producto_id;
	public $activo = 1;

	public function __construct($id="")
	{
		parent::__construct();
		$this->setTabla("producto_presentacion");
		$this->setLlave("id");

		if (!empty($id)) {
			$this->cargar($id);
		}
	}

	public function _buscar($args=[])
	{
		$tmp = $this->db->get("$this->_tabla");

		return verConsulta($tmp, $args);
	}
}

/* End of file Presentacion_model.php */
/* Location: ./application/models/Presentacion_model.php */