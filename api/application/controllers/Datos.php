<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Datos extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
        die();
	}


	public function departamentos()
	{
		$rutaArchivo = 'D:\dep.json';
        $jsonContenido = file_get_contents($rutaArchivo);
        $departamentos = json_decode($jsonContenido, true);

        $contador  = 0;
        foreach ($departamentos as $key => $row) {
        	$this->db->insert("departamento", ["codigo" => $row["iso"], "nombre" => $row["title"]]);
        	$dep =  $this->db->insert_id();

        	foreach ($row["mun"] as $value) {
        		$this->db->insert("municipio", ["nombre" => $value, "departamento_id" => $dep]);
        	}
        	$contador++;
        }

        echo "Registros insertados: " .$contador;
	}
}

/* End of file Datos.php */
/* Location: ./application/controllers/Datos.php */