<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Usuario_model extends General_model {

	public $nombre;
	public $apellido;
	public $usuario;
	public $clave;
	public $activo = 1;
	public $rol_id;

	public function __construct($id="")
	{
		parent::__construct();
		if (!empty($id)) {
			$this->cargar($id);
		}
	}

	public function iniciarSesion($args=[])
	{
		$clave = $args["clave"];

		$tmp = $this->db
		->where('usuario', $args['usuario'])
		->where('clave', "md5('{$clave}')",false)
		->where('activo', 1)
		->get('usuario');

		if ($tmp->num_rows() > 0) {
			$tmp = $tmp->row();

			$this->cargar($tmp->id);
			return true;
		}

		return false;	
	}

}

/* End of file Usuario_model.php */
/* Location: ./application/models/Usuario_model.php */