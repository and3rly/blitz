<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Producto extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->model(["producto/Producto_model"]);
		$this->output->set_content_type("application/json");
	}

	public function index()
	{
		$this->output->set_status_header('404');
	}

	public function buscar() 
	{
		$data = [
			'lista' => $this->Producto_model->_buscar($_GET)
		];

		$this->output->set_output(json_encode($data)); 
	}

	public function get_datos($value='')
	{
		$data = [
			"cat" => [
				"marcas" => $this->catalogo->ver_marca(),
				"ums" => $this->catalogo->ver_um(),
				"categorias" => $this->catalogo->ver_categoria(),
				"tipos" => getTiposProductos()
			]
		];

		$this->output->set_output(json_encode($data));
	}

	public function guardar($id="")
	{
		$data = ["exito" => 0];

		if ($this->input->method() === "post") {
			$datos = (object) $_POST;

			$producto = new Producto_model($id);

			if ($producto->existe($datos)) {
				$data["mensaje"] = "El producto que intenta guardar ya existe.";
			} else {
				if (elemento($_FILES, 'imagen') && 
					elemento($_FILES['imagen'], 'tmp_name')) {
					$imagen = subirArchivo([
						'tmp_name' => $_FILES['imagen']['tmp_name'],
						'type'     => $_FILES['imagen']['type'],
						'name'     => $_FILES['imagen']['name'],
						'carpeta'  => 'producto'
					]);

					if ($imagen) {
						$datos->imagen_key  = $imagen->key;
						$datos->imagen_link = $imagen->link;
					}
				}

				if ($producto->guardar($datos)) {
					$accion = $id ? "actualizado" : "guardado";

					$data["exito"]   = 1;
					$data['mensaje'] = "Registro $accion correctamente.";
					$data["linea"]   = $producto->_buscar([
						"id" => $producto->getPK(), 
						"uno" => true
					]);
				} else {
					$data["mensaje"] = $producto->getMensaje();
				}
			}
		} else {
			$this->output->set_status_header("405");
		}

		$this->output->set_output(json_encode($data));
	}
}

/* End of file Producto.php */
/* Location: ./application/controllers/Producto.php */