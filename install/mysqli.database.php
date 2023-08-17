<?php

/** 
 * Fue creado para simplificar algunas cosas
*/

class DataBase {

	public $db = [];
	public $db_link;

   public function __construct() { 
   }

   public function conn() {
   	try {
   		return new mysqli($this->db['hostname'], $this->db['username'], $this->db['password'], $this->db['database']);
   	} catch (Exception $e) {
			switch ($e->getCode()) {
				case 1045:
					$_SERVER['message'] = "Acceso denegado para el usuario <strong>'{$this->db['username']}'</strong>@'localhost'";
				break;
				case 1049:
					$_SERVER['message'] = "La base de datos <strong>{$this->db['database']}</strong> es desconocida o no existe.";
				break;
				case 2002:
					$_SERVER['message'] = "HOST: <strong>{$this->db['hostname']}</strong> desconocido.";
				break;
			}
   	}
   }


	public function error() { 
		return mysqli_error($this->db_link); 
	}

	public function escape($string) { 
		return mysqli_real_escape_string($this->db_link, $string); 
	}

	public function fetch_assoc($query) { 
		return mysqli_fetch_assoc($this->query($query)); 
	}

	public function fetch_row($query) { 
		return mysqli_fetch_row($this->query($query)); 
	}

	public function num_rows($query) { 
		return mysqli_num_rows($this->query($query));
	}

	public function insert_id() {
		return mysqli_insert_id($this->db_link);
	}

   public function query($sql) {
   	return mysqli_query($this->db_link, $sql);
   }

   public function setNames() {
   	return $this->query("SET NAMES 'UTF8'");
   }

}

$database = new DataBase();