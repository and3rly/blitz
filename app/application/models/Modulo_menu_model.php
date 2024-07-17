<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Modulo_menu_model extends General_model {

	public $nombre;
	public $icono;
	public $url;
	public $activo = 1;
	public $modulo_id;

	public function __construct($id="")
	{
		parent::__construct();
		if (!empty($id)) {
			$this->cargar($id);
		}
		
	}

	public function _buscar($args=[])
	{
		if (elemento($args, 'modulo')) {
			$this->db->where('modulo_id', $args['modulo']);
		}
		
		$tmp = $this->db
		->where("activo", 1)
		->get("modulo_menu");

		return verConsulta($tmp, $args);
	}

}

/* End of file Modulo_menu_model.php */
/* Location: ./application/models/Modulo_menu_model.php */