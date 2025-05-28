<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Producto_sucursal extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model(["producto/Producto_model"]);
		$this->output->set_content_type('application/json');
	}

	public function index()
	{
		$this->output->set_status_header("404");
	}

	public function get_datos()
	{
		$data = [
			"cat" => [
				"sucursales" => $this->catalogo->ver_sucursal(),
				"productos"  => $this->Producto_model->_buscar()
			]
		];

		$this->output->set_output(json_encode($data));
	}
}

/* End of file Producto_sucursal.php */
/* Location: ./application/controllers/Producto_sucursal.php */