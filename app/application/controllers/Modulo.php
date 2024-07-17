<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Modulo extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model([
			"Modulo_model",
			"Modulo_menu_model"
		]);
		$this->output->set_content_type('application/json');
	}

	public function index()
	{
		$this->output->set_status_header('404');
	}

	public function buscar()
	{
		$data =  [
			'lista' => $this->Modulo_model->_buscar($_GET)
		];

		$this->output->set_output(json_encode($data));
	}

	public function get_modulos()
	{
		$user = $this->session->userdata('usuario');
		$modulos = $this->Modulo_model->_buscar();

		if ($modulos) {
			foreach ($modulos as $row) {
				$datos = $this->Modulo_menu_model->_buscar([
					'modulo' => $row->id
				]);

				$row->opciones = $datos ? $datos : false;
			}
		}

		$this->output->set_output(json_encode([
		 	'lista' => $modulos ? $modulos : []
		]));
	}

	public function get_opciones()
	{
		$opciones = $this->Modulo_menu_model->_buscar($_GET);

		$this->output->set_output(json_encode([
		 	'lista' => $opciones ? $opciones : []
		]));
	}
}

/* End of file Menu.php */
/* Location: ./application/controllers/Modulo.php */