<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Usuario_model extends General_model {

	public $nombre;
	public $apellido;
	public $usuario;
	public $clave;
	public $activo = 1;
	public $correo = null;
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

		$usuario = $this->db
		->where('usuario', $args['usuario'])
		->where('activo', 1)
		->get('usuario')
		->row();

		if ($usuario && password_verify($args['clave'], $usuario->clave)) {
		    $this->cargar($usuario->id);
		    return true;
		}

		return false;	
	}

	public function existe($args=[])
	{	
		if ($this->getPK()) {
			$this->db->where("id <> ", $this->getPK());
		}

		$tmp = $this->db
		->where("nombre", $args->nombre)
		->where("apellido", $args->apellido)
		->where("usuario", $args->usuario)
		->where("rol_id", $args->rol_id)
		->where("activo", 1)
		->get("$this->_tabla");

		return $tmp->num_rows() > 0;
	}

	public function setClave()
	{
		$codigo = generarCodigo(10);
		$this->clave = password_hash($codigo, PASSWORD_DEFAULT);
	}

	public function _buscar($args=[])
	{	
		if (elemento($args, "id")) {
			$this->db->where("a.id", $args["id"]);
		}

		$tmp = $this->db
		->select("a.*,
				b.nombre as nombre_rol")
		->join("rol b","b.id = a.rol_id")
		->where("a.activo", 1)
		->order_by("a.fecha", "desc")
		->get("usuario a");

		return verConsulta($tmp, $args);
	}
}

/* End of file Usuario_model.php */
/* Location: ./application/models/Usuario_model.php */