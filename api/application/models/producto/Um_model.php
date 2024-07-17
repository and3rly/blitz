<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Um_model extends General_model {

	public $codigo;
	public $nombre;
	public $activo = 1;

	public function __construct($id="")
	{
		parent::__construct();
		$this->setTabla("unidad_medida");
		$this->setLlave("id");

		if (!empty($id)) {
			$this->cargar($id);
		}	
	}

	public function _buscar($args=[])
	{
		if (elemento($args, "id")) {
			$this->db->where("id", $args["id"]);
		} 

		$tmp = $this->db->get("$this->_tabla");

		return verConsulta($tmp, $args);
	}
}

/* End of file Um_model.php */
/* Location: ./application/models/Um_model.php */