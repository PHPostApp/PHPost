<?php if ( ! defined('TS_HEADER')) exit('No se permite el acceso directo al script');

/**
 * Modelo para el control del registro de usuarios
 *
 * @name    c.registro.php
 * @author  PHPost Team
 */

include_once TS_EXTRA . "reCaptcha.php";

class tsRegistro extends reCaptcha {

	/**
    * @name strstr($string)
    * @access public
    * @param string
    * @return string
   */
	private function strstr($haystack, $before_needle = true) {
	   global $tsCore;
	   return empty($haystack) ? '' : $tsCore->setSecure(strstr($haystack, '@', $before_needle));
	}

   /**
    * @name checkUserEmail($pid)
    * @access public
    * @param
    * @return string
   */
	public function checkUserEmail(){
		global $tsCore;
		// Variables
		$username = htmlspecialchars($_POST['nick']);
		$email = strtolower($_POST['email']);
      $which = empty($username) ? 'email' : 'nick';
      // MENSAJE
		$valid = "1: El $which est&aacute; disponible.";	// DEFAULT
		//
		if (!empty($username) AND ctype_digit($username)) return "3: T&uacute; nick no pueder solo n&uacute;meros.";

		if(!empty($email)) {
      	$permitidos = 'gmail.com|hotmail.com|yahoo.com|live.com';
			$msg = "3: Tu proveedor no est&aacute; permitido.";
			preg_match_all('/@(' . $permitidos . ')$/i', $email, $matches);

			if(empty($matches[0][0])) return $msg;
			$decode = substr($matches[0][0], 1);
			if(!in_array($decode, explode('|', $permitidos))) return $msg;
		}
		//
		if(!empty($username) || !empty($email)) {
			$username = $tsCore->setSecure($username);
			$email = $tsCore->setSecure($email);
			$q = !empty($username) ? "user_name = '$username'" : "LOWER(user_email) = '$email'";
			$query = db_exec([__FILE__, __LINE__], 'query', "SELECT `user_id` FROM `u_miembros` WHERE $q LIMIT 1");
			if(db_exec('num_rows', $query) > 0) $valid = '0: El '.$which.' ya se encuentra registrado.';	// EXISTE
         if(db_exec('num_rows', db_exec([__FILE__, __LINE__], 'query', 
         	"SELECT id FROM w_blacklist WHERE (type = 3 && value = '{$this->strstr($email)}') || (type = 4 && value = '{$this->strstr($email, true)}') || (type = 4 && value = '$username') LIMIT 1"))) $valid = '0: Parte del '.$which.' no est&aacute; permitida';
		} else $valid = '0: Faltan datos y no se puede procesar tu solicitud.';
		// retornar valor
		return $valid;
	}
    /**
     * @name registerUser()
     * @access public
     * @param
     * @return string
     */
	function registerUser(){
		global $tsCore, $tsUser;
		// DATOS NECESARIOS
		$nac = explode('-', $_POST['nacimiento']);
		$tsData = array(
			'user_nick' => $tsCore->parseBadWords($_POST['nick']),
			'user_password' => $tsCore->parseBadWords($_POST['password']),
			'user_email' => $tsCore->setSecure($_POST['email']),
			'user_dia' => (int)$nac[2],
			'user_mes' => (int)$nac[1],
			'user_anio' => (int)$nac[0],
			'user_terminos' => $_POST['terminos'],
			'user_captcha' => $_POST['response'],
			'user_registro' => time(),
		);
		// ERRORS
		$errors = array(
			'default' => 'El campo es requerido',
			'nick' =>'El nombre de usuario ya se encuentra registrado.',
			'password' => 'La contrase&ntilde;a tiene que ser distinta que el nick',
			'email' => 'El formato es incorrecto',
			'email_2' => 'El email ya est&aacute; en uso',
			'captcha' => 'Validaci&oacute;n incorrecta',
		);
		// COMPROBAR VACIOS
		foreach($tsData as $key => $val){
			if($val == '') return str_replace('user_', '', $key) . ": El campo es requerido";
		}
		// Verificando el captcha
      $response = parent::verify($tsData['user_captcha']);
      if (!$response) return 'recaptcha: No hemos podido validar tu humanidad';
      // COMPROBAR QUE EL NOMBRE DE USUARIO SEA VÁLIDO
      if(!preg_match("/^[a-zA-Z0-9_-]{4,16}$/", $tsData['user_nick'])) die('nick: Nombre de usuario inv&aacute;lido');

		// COMPROBAR NUEVAMENTE QUE EL USUARIO O EMAIL NO SE ENCUENTREN REGISTRADOS
		$query = db_exec([__FILE__, __LINE__], 'query', "SELECT user_name, user_email FROM u_miembros WHERE user_name = '{$tsData['user_nick']}' OR LOWER(user_email) = '{$tsData['user_email']}' LIMIT 1");
		if(db_exec('num_rows', $query) > 0 || !filter_var($tsData['user_email'], FILTER_VALIDATE_EMAIL) || $tsCore->settings['c_reg_active'] === 0) die('0: Hubo problemas al intentar registrarle, hay campos vac&iacute;os, inv&aacute;lidos o no se le permite el registro.');

		// PASAMOS BIEN... AHORA INSERTAR DATOS
		$key = $tsCore->createPassword($tsData['user_nick'], $tsData['user_password']);
		$rango = empty($tsCore->settings['c_reg_rango']) ? 3 : (int)$tsCore->settings['c_reg_rango'];
		$active = (int)$tsCore->settings['c_reg_active'];
		//
		if(db_exec([__FILE__, __LINE__], 'query', "INSERT INTO `u_miembros` (`user_name`, `user_password`, `user_email`, `user_rango`, `user_registro`, `user_activo`) VALUES ('{$tsData['user_nick']}', '$key', '{$tsData['user_email']}', $rango, {$tsData['user_registro']}, $active)")){
         $tsData['user_id'] = db_exec('insert_id');
         $id = (int)$tsData['user_id'];
         // CREAMOS EL AVATAR CON LAS INICIALES DEL USUARIO
         $avatar = "https://ui-avatars.com/api/?name={$tsData['user_nick']}&background=random&color=fff&size=$1&font-size=0.50&bold=false&length=2";
         $sizes = [50, 120];
         foreach ($sizes as $k => $v) copy(str_replace('$1', $v, $avatar), TS_AVATAR . "{$id}_$v.jpg");
         // INSERTAMOS EL PERFIL
			db_exec([__FILE__, __LINE__], 'query', "INSERT INTO `u_perfil` (`user_id`, `user_dia`, `user_mes`, `user_ano`, `p_avatar`) VALUES ($id, {$tsData['user_dia']}, {$tsData['user_mes']}, {$tsData['user_anio']}, 1)");
         db_exec([__FILE__, __LINE__], 'query', "INSERT INTO `u_portal` (`user_id`) VALUES ($id)");
			
			// MENSAJE PARA DAR LA BIENVENIDA BIENVENIDA
			$send_welcome = $tsCore->settings['c_met_welcome'];
			if($send_welcome > 0 && $send_welcome < 4) {
				$sexo = 'Bienvenid' . ($tsData['user_sexo'] == 1 ? 'o' : 'a'); 
            $msg_bienvenida = str_replace(
            	['[usuario]', '[welcome]', '[web]'], 
            	[$tsData['user_nick'], $sexo, $tsCore->settings['titulo']], 
	            $tsCore->parseBBCode($tsCore->settings['c_message_welcome'])
	         );
            //
            $time = time();
	         switch($send_welcome) {
	            case 1:
						db_exec([__FILE__, __LINE__], 'query', "INSERT INTO `u_muro` (p_user, p_user_pub, p_date, p_body, p_type) VALUES ($id, 1, $time, '$msg_bienvenida', 1)"); 
			         $m_id = db_exec('insert_id');
						db_exec([__FILE__, __LINE__], 'query', "INSERT INTO `u_monitor` (user_id,obj_user,obj_uno, not_type,not_total,not_menubar,not_monitor) VALUES ($id, 1, $m_id, 12, 1, 1, 1)");
					break;
	            case 2:
						$preview = substr($msg_bienvenida, 0, 75); 
						if(db_exec([__FILE__, __LINE__], 'query', "INSERT INTO `u_mensajes` (`mp_to`, `mp_from`, `mp_subject`, `mp_preview`, `mp_date`) VALUES ($id, 1, '$sexo a {$tsCore->settings['titulo']}', '$preview', $time)")) {
			            $mp_id = db_exec('insert_id');
			            db_exec([__FILE__, __LINE__], 'query', "INSERT INTO `u_respuestas` (mp_id, mr_from, mr_body, mr_ip, mr_date) VALUES ($mp_id, 1, '$msg_bienvenida', '{$_SERVER['REMOTE_ADDR']}', $time)"); 
			         }
					break;
		    		case 3:
						db_exec([__FILE__, __LINE__], 'query', "INSERT INTO `u_avisos` (`user_id`, `av_subject`, `av_body`, `av_date`, `av_type`) VALUES ($id, '$sexo a {$tsCore->settings['titulo']}', '$msg_bienvenida', $time, 4)");			
            	break;
				}
			}
			// ENVIAMOS EL EMAIL
			if((int)$tsCore->settings['c_reg_activate'] == 0) {
			
				# Enviamos código de 6 dígitos
				$key = substr(number_format(time() * rand(), 0, '',''), 0, 6);
				$time = time();
				$to = $tsData['user_email'];
				$keyEncode = base64_encode($key);
				$encode = $keyEncode .'@'. base64_encode($to);

				if(db_exec([__FILE__, __LINE__], 'query', "INSERT INTO w_contacts (user_id, user_email, time, type, hash) VALUES ($id, '{$tsData['user_email']}', $time, 2, '$key')")) {	
					include_once TS_CLASS . "c.emails.php";
					$tsEmail = new tsEmail('activar', 'registro'); 
					$subject = 'Active su cuenta';
					$body = '<div style="background:#FFF;padding:10px;font-size:14px"><h2 style="font-family:Arial, Helvetica,sans-serif;color:#000;font-size:22px">Hola '.$tsData['user_nick'].'</h2><p style="font-family:Arial, Helvetica,sans-serif;color:#000">&iexcl;Te damos la bienvenida a '.$tsCore->settings['titulo'].'!</p><p>Para finalizar con el proceso de registro, confirma tu direcci&oacute;n de email ingresando el siguiente c&oacute;digo:<span style="width:70%;margin:14px auto;background-color: #EEE5;display: block;padding: 20px 0;font-size:32pt;letter-spacing: 8px;font-weight: 700;text-align: center;font-family:Tahoma;color:#3AA0BA">'.$key.'</span><span style="display: block;text-align: center;"><a href="'.$tsCore->settings['url'].'/validar/'.$encode.'?utm_campaign=Verficacion+email" style="display:inline-block;background-color: #3AA0BA;color:#FFF;text-decoration: none;font-weight: 600;padding: 12px;border-radius: 6px;">Verificar c&oacute;digo ahora</a></span></p> <br /><p>Posteriormente podr&aacute; acceder con las siguientes credenciales:</p><p>Usuario: '.$tsData['user_nick'].' <br /> Contrase&ntilde;a: '.$tsData['user_password'].'</p><br /><p>Antes de empezar a interactuar con la comunidad, te recomendamos que visites el <a target="_blank" href="'.$tsCore->settings['url'].'/pages/protocolo/">Protocolo</a> del sitio.</p><p>Esperamos que disfrutes enormemente tu visita.</p><p>&iexcl;Te damos la bienvenida a Muchas gracias!</p></div>';
						// <--
						$tsEmail->emailTo = $to;
						$tsEmail->emailSubject = $subject;
						$tsEmail->emailBody = $body;
						$tsEmail->sendEmail()  or die('0: Hubo un error al intentar procesar lo solicitado');
						return '1: <div class="box">Te hemos enviado un correo a <b>'.$to.'</b> con los &uacute;ltimos pasos para finalizar con el registro.<br><br>Si en los pr&oacute;ximos minutos no lo encuentras en tu bandeja de entrada, por favor, revisa tu carpeta de correo no deseado, es posible que se haya filtrado.<br><br>&iexcl;Muchas gracias!</div>';	
				} else {
					return '0: <div class="box">Ocurri&oacute; un error, int&eacute;ntelo de nuevo.</div>';				
				}
			} else {
				$tsUser->userActivate($tsData['user_id'], md5($tsData['user_registro']));
				$tsUser->loginUser($tsData['user_nick'], $tsData['user_password'], true);
				return '2: <div class="box">Bienvenido a <b>'.$tsCore->settings['titulo'].'</b>, Ahora estas registrado y tu cuenta ha sido activada, podr&aacute;s disfrutar de esta comunidad inmediatamente.<br><br>&iexcl;Muchas gracias! :)</div>';
			}
		} else return '0: Ocurrio un error, intentalo ma&aacute;s tarde.';
	}
}