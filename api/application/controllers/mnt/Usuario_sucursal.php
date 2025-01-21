<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Usuario_sucursal extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->output->set_content_type('application/json');
	}

	public function index()
	{
		$this->output->set_status_header("404");
	}

}

/* End of file Usuario_sucursal.php */
/* Location: ./application/controllers/Usuario_sucursal.php */