<?php 

if(!defined('TS_HEADER')) 
	exit('No se permite el acceso directo al script');

/**
 * Clase para el manejo de la extension de c.cuenta.php
 *
 * @name    c.cuenta.extends.php
 * @author  Miguel92
*/

trait tsCuentaExtends {

	public function Message(int $uid = 0, bool $save = false) {
		global $tsCore;
		if($save) {
			$mensaje = $tsCore->setSecure($_POST['mensaje']);
			if(db_exec([__FILE__, __LINE__], 'query', "UPDATE u_perfil SET p_mensaje = '$mensaje' WHERE user_id = $uid")) {
				return true;
			}
			return false;
		} else {
			return db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT p_mensaje FROM u_perfil WHERE user_id = $uid"))['p_mensaje'];
		}
	}

}