<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Moneda_model extends General_model {

	public $nombre;
	public $simbolo;
	public $cambio = null;
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
		if (elemento($args, "id")) {
			$this->db->where("id", $args["id"]);
		} 

		$tmp = $this->db->get("$this->_tabla");

		return verConsulta($tmp, $args);
	}

}

/* End of file Moneda_model.php */
/* Location: ./application/models/Moneda_model.php */