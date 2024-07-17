<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Modulo_model extends General_model {

	public $nombre;
	public $url;
	public $icono;
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
		if (elemento($args, 'id')) {
			$this->db->where("id", $args['id']);
		}
		
		$tmp = $this->db
		->where("activo", 1)
		->get("modulo");

		return verConsulta($tmp, $args);
	}

}

/* End of file Modulo_model.php */
/* Location: ./application/models/Modulo_model.php */