<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Catalogo_model extends General_model {


	public function __construct()
	{
		parent::__construct();
		
	}

	public function ver_usuario_sucursal($args=[])
	{
		$tmp  = $this->db
		->where("usuario_id", $args["usuario"])
		->where("activo", 1)
		->get("usuario_sucursal");

		return verConsulta($tmp, $args);
	}

	public function ver_departamento($args=[])
	{
		$tmp  = $this->db
		->where("activo", 1)
		->get("departamento");

		return verConsulta($tmp, $args);
	}

	public function ver_municipio($args=[])
	{
		$tmp  = $this->db
		->where("activo", 1)
		->get("municipio");

		return verConsulta($tmp, $args);
	}

	public function ver_marca($args=[])
	{
		$tmp  = $this->db
		->where("activo", 1)
		->get("marca");

		return verConsulta($tmp, $args);
	}

	public function ver_categoria($args=[])
	{
		$tmp  = $this->db
		->where("activo", 1)
		->get("categoria");

		return verConsulta($tmp, $args);
	}

	public function ver_um($args=[])
	{
		$tmp  = $this->db
		->where("activo", 1)
		->get("unidad_medida");

		return verConsulta($tmp, $args);
	}

	public function ver_proveedor($args=[])
	{
		$tmp  = $this->db
		->where("activo", 1)
		->get("proveedor");

		return verConsulta($tmp, $args);
	}

	public function ver_establecimiento($args=[])
	{
		$tmp  = $this->db
		->where("activo", 1)
		->get("establecimiento");

		return verConsulta($tmp, $args);
	}

	public function ver_sucursal($args=[])
	{
		$tmp  = $this->db
		->where("activo", 1)
		->get("sucursal");

		return verConsulta($tmp, $args);
	}

	public function ver_tipo_pago($args=[])
	{
		$tmp  = $this->db
		->where("activo", 1)
		->get("tipo_pago");

		return verConsulta($tmp, $args);
	}

	public function ver_compra_estado($args=[])
	{
		$tmp  = $this->db
		->where("activo", 1)
		->get("estado_orden_compra");

		return verConsulta($tmp, $args);
	}

	public function ver_producto_precio($args=[])
	{	
		if (elemento($args, "proveedor")) {
			$this->db->where("b.proveedor_id", $args["proveedor"]);
		}

		$tmp = $this->db
		->select("a.*,
			b.id as producto_precio_id,
			b.costo,
			b.precio,
			c.nombre as nombre_proveedor,
			d.nombre as nombre_um, 
			e.nombre as nombre_categoria, 
			e.color,
			f.nombre as nombre_marca")
		->join("producto_precio b","b.producto_id = a.id")
		->join("proveedor c","c.id = b.proveedor_id")
		->join("unidad_medida d","d.id = a.unidad_medida_id")
		->join("categoria e","e.id = a.categoria_id")
		->join("marca f","f.id = a.marca_id")
		->get("producto a");

		return verConsulta($tmp, $args);
	}

	public function ver_moneda($args=[])
	{
		$tmp  = $this->db
		->where("activo", 1)
		->get("moneda");

		return verConsulta($tmp, $args);
	}

	public function ver_presentacion($args=[])
	{
		$this->load->model("producto/Presentacion_model");

		return $this->Presentacion_model->_buscar($args);
	}

	public function ver_tipo_documento($args=[])
	{
		$tmp  = $this->db
		->where("activo", 1)
		->get("tipo_documento");

		return verConsulta($tmp, $args);
	}

	public function ver_rol($args=[])
	{
		$tmp  = $this->db
		->where("activo", 1)
		->get("rol");

		return verConsulta($tmp, $args);
	}

	public function ver_productos($args=[])
	{	
		if (elemento($args, "sucursal")) {
			$this->db->where("a.sucursal_id", $args["sucursal"]);
		} else {
			$this->db->where("a.sucursal_id", $_SESSION["sucursal_id"]);
		}

		$tmp = $this->db
	    ->select("a.*, 
	        b.nombre as nombre_producto,
	        b.tipo as tipo_producto, 
	        c.nombre as nombre_categoria, 
	        c.etiqueta,
	        d.nombre as nombre_unidad, 
	        e.nombre as nombre_marca,
	        1 as cantidad,
	        b.imagen_key")
	    ->join("producto b", "b.id = a.producto_id")
	    ->join("categoria c", "c.id = b.categoria_id")
	    ->join("unidad_medida d", "d.id = b.unidad_medida_id")
	    ->join("marca e", "e.id = b.marca_id")
	    ->where("a.activo", 1)
	    ->get("producto_sucursal a");

		return verConsulta($tmp, $args);
	}
}

/* End of file Catalogo_model.php */
/* Location: ./application/models/Catalogo_model.php */