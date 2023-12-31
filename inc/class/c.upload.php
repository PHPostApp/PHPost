<?php if ( ! defined('TS_HEADER')) exit('No se permite el acceso directo al script');
/**
 * Modelo para subir im�genes
 *
 * @name    c.upload.php
 * @author  PHPost Team
 */
class tsUpload {

	public $type = 1;  // TIPO DE SUBIDA

	public $max_size = 2097152;    // 2MB 

	public $allow_types = ['png','gif','jpeg','webp','svg']; // ARCHIVOS PERMITIDOS

	public $found = 0; // VARIABLE BANDERA 

	public $file_url = ''; // URL

	public $file_size = []; // TAMA�O DEL ARCHIVO REMOTO

	public $image_size_min = ['w' => 570, 'h' => 450];

	public $image_size_max = ['w' => 920, 'h' => 720];

	public $image_scale = false;

	public $servers = [];

	public $server = 'imgur';  // DEFAULT IMGUR

	public $for = 'usuario'; // usuario | portada

	// CONSTRUCTOR
	public function __construct(){
		$this->servers = ['imgur' => 'https://api.imgur.com/3/image.json'];
	}
	/*
	 * newUpload($type) :: $type => URL o ARCHIVO
	*/
	public function newUpload(int $type = 1){
		$this->type = (int)$type;
		// ARCHIVOS
		if($this->type == 1){
			foreach($_FILES as $file) $fReturn[] = $this->uploadFile($file);
		// DESDE URL
		} elseif($this->type == 2) $fReturn[] = $this->uploadUrl();
		// CROP
		elseif($this->type == 3){
			if(empty($this->file_url)) {
				foreach($_FILES as $file) $fReturn = $this->uploadFile($file);
				if(empty($fReturn['msg'])) return ['error' => $fReturn[1]];
			} else {
				$file = [
					'name' => substr($this->file_url, -4),
					'type' => 'image/url',
					'tmp_name' => $this->file_url,
					'error' => 0,
					'size' => 0
				];
				//
				$fReturn = $this->uploadFile($file, 'url');
				if(empty($fReturn['msg'])) return ['error' => $fReturn[1]];
			}
		}
		return ($this->found == 0) ? ['error' => 'No se ha seleccionado archivo alguno.'] : $fReturn;
	}
	/*
	 * uploadFiles()
	*/
	public function uploadFile($file, string $type = 'file'){
		// VALIDAR
		$error = $this->validFile($file, $type);
		if(!empty($error)) return [0, $error];
		else {
			$type = explode('/',$file['type']);
			$ext = ($type[1] == 'jpeg' || $type[1] == 'url') ? 'jpg' : $type[1]; // EXTENSION
			$key = date('dmy') . substr(md5('PHPost'.rand(0, 1000)), 0, 10);
			$newName = "PHPost$key.$ext";
			// IMAGEN
			return ($this->type == 1) ? [1, $this->sendFile($file, $newName), $type[1]] : [
				'msg' => $this->createImage($file, $newName), 
				'error' => '', 
				'key' => $key, 
				'ext' => $ext
			];
			//
		}
	}
	/*
	 * uploadUrl()
	*/
	public function uploadUrl(){
		$error = $this->validFile(null, 'url');
		return (!empty($error)) ? [0, $error] : [1, urldecode($this->file_url)];
	}
	/*
	 *  validFile()
	*/
	public function validFile($file, string $type = 'file'){
		// ARCHIVO
		if($type == 'file'){
			// SE ENCONTRO EL ARCHIVO
			if(empty($file['name'])) return 'No Found';
			else $this->found = $this->found + 1;
			//
			$type = explode('/',$file['type']);
			if($file['size'] > $this->max_size) 
				return '#'.$this->found.' pesa mas de 1 MB.';
			elseif(!in_array($type[1], $this->allow_types)) 
				return '#'.$this->found.' no es una imagen.';
		} elseif($type == 'url') {
			$this->file_size = getimagesize($this->file_url);
			// TAMA�O MINIMO
			$min_w = 180;
			$min_h = 120;
			// MAX PARA EVITAR CARGA LENTA
			$max_w = 1024;
			$max_h = $max_w;
			$this->found = 1;
			//
			if(empty($this->file_size[0])) 
				return 'La url ingresada no existe o no es una imagen v&aacute;lida.';
			elseif($this->file_size[0] < $min_w || $this->file_size[1] < $min_h) 
				return "Tu foto debe tener un tama&ntilde;o superior a {$min_w}x{$min_h} pixeles.";
			elseif($this->file_size[0] > $max_w || $this->file_size[1] > $max_h) 
				return "Tu foto debe tener un tama&ntilde;o menor a {$max_w}x{$max_w} pixeles.";
		}
		// TODO BIEN
		return false;
	}
	/*
	 * sendFile($file,$name)
	*/
	public function sendFile($file, string $name = ''){
		//
		$url = $this->createImage($file,$name);
		// SUBIMOS...
		$new_img = $this->getImagenUrl($this->uploadImagen($this->setParams($url)));
		// BORRAR
		$this->deleteFile($name);
		// REGRESAMOS
		return $new_img;
	}
	/*
	 * copyFile($file, $name)
	*/
	public function copyFile($file, string $name = ''){
		global $tsCore;
		// COPIAMOS
		copy($file['tmp_name'], TS_UPLOADS . $name);
		// REGRESAMOS LA URL
		return $tsCore->settings['url'].'/files/uploads/'.$name;
	}
	/*
	 * createImage()
	*/
	public function createImage($file, string $name = ''){
		global $tsCore;
		// TAMAÑO
		$size = empty($this->file_size) ? getimagesize($file['tmp_name']) : $this->file_size;
		if(empty($size)) die('0: Intentando subir un archivo que no es válido.');
		$width = $size[0];
		$height = $size[1];
		// ESCALAR SOLO SI LA IMAGEN EXEDE EL TAMAÑO Y SE DEBE ESCALAR
		if($this->image_scale == true && ($width > $this->image_size_min['w'] || $height > $this->image_size_min['h'])){
			// OBTENEMOS ESCALA
			if($width > $height){
				$_height = ($height * $this->image_size_min['w']) / $width;
				$_width = $this->image_size_min['w'];
			} else {
				$_width = ($width * $this->image_size_min['h']) / $height;
				$_height = $this->image_size_min['h'];
			}
			// TIPO
			switch($file['type']){
				case 'image/url':
					$img = imagecreatefromstring($tsCore->getUrlContent($file['tmp_name']));
				break;
				case 'image/jpeg':
				case 'image/jpg':
					$img = imagecreatefromjpeg($file['tmp_name']);
				break;
				case 'image/gif':
					$img = imagecreatefromgif($file['tmp_name']);
				break;
				case 'image/png':
						$img = imagecreatefrompng($file['tmp_name']);
				break;
			}
			// ESCALAMOS NUEVA IMAGEN
			$newimg = imagecreatetruecolor($_width, $_height); 
			imagecopyresampled($newimg, $img, 0, 0, 0, 0, $_width, $_height, $width, $height);
			// COPIAMOS
			$root = TS_UPLOADS . $name;
			//
			imagejpeg($newimg, $root, 100);
			imagedestroy($newimg);
			imagedestroy($img);
			// RETORNAMOS
			return "{$tsCore->settings['uploads']}/$name";
		// MANTENEMOS LAS DIMENCIONES Y SOLO COPIAMOS LA IMAGEN
		} else return $this->copyFile($file, $name);
	}
	/**
	 * @name cropAvatar()
	 * @uses Creamos el avatar a partir de las coordenadas resibidas
	 * @access public
	 * @param int
	 * @return array
	*/
	public function cropAvatar(string $key = ''){
		$source = TS_UPLOADS . "PHPost{$_POST['key']}.{$_POST['ext']}";
		$size = getimagesize($source);
		// COORDENADAS
		$x = $_POST['x'];
		$y = $_POST['y'];
		$w = $_POST['w'];
		$h = $_POST['h'];
		// TAMA�OS
		$_w = $_h = 180;
		$_tw = $_th = 50;
		// CREAMOS LA IMAGEN DEPENDIENDO EL TIPO
		switch($size['mime']){
			case 'image/jpeg':
			case 'image/jpg':
				$img = imagecreatefromjpeg($source);
			break;
			case 'image/gif':
				$img = imagecreatefromgif($source);
			break;
			case 'image/png':
				$img = imagecreatefrompng($source);
			break;
		}
		if(!$img) return array('error' => 'No pudimos crear tu avatar...');
		//
		$width = imagesx($img);
		$height = imagesy($img);
		// AVATAR
		$avatar = imagecreatetruecolor($_w, $_h);
		imagecopyresampled($avatar, $img, 0, 0, $x, $y, $_w, $_h, $w, $h);
		// AVATAR THUMB
		$thumb = imagecreatetruecolor($_tw, $_th);
		imagecopyresampled($thumb, $img, 0, 0, $x, $y, $_tw, $_th, $w, $h);
		// GUARDAMOS...
		$root = TS_AVATAR . $key;
		imagejpeg($avatar, $root.'_120.jpg', 90);
		imagejpeg($thumb, $root.'_50.jpg', 90);
		// CLEAR
		imagedestroy($img);
		imagedestroy($avatar);
		imagedestroy($thumb);
		// BORRAMOS LA ORIGINAL
		unlink($source);
		//
		return ['error' => 'success'];
	}
	/*
	 * deleteFile()
	*/
	public function deleteFile(string $file = ''){
		unlink(TS_UPLOADS . $file);
		return true;
	}
	/*
	 * uploadImagen()
	*/
	public function uploadImagen($params){
		// User agent
		$useragent = $_SERVER['HTTP_USER_AGENT'];
		// SERVIDOR
		$servidor = $this->servers[$this->server];
		// Autorizar conexión
		$headers = ['Authorization: Client-ID 318cdea21b8f8c0'];
		// Configurar opciones de cURL
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
		curl_setopt($ch, CURLOPT_URL, $servidor);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);		
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);	
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);		
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		// RESULTADO
		$result = curl_exec($ch);
		curl_close($ch);
		return $result;
	}
	/*
	 * setParams()
	*/
	public function setParams($url){
		switch($this->server){
			case 'imgur':
				return ['image' => base64_encode(file_get_contents($url))];
			break;
		}
	}
	/**
	 * @name getImagenUrl($html)
	 * @access public
	 * @param string
	 * @return string
	 * @version 1.1
	*/
	public function getImagenUrl($code){
		//
		switch($this->server){
			case 'imgur':
				global $tsCore;
				$image_data = json_decode($code);
				$src = $image_data->data->link;
				return $src;
			break;
		}
	}

	# ===================================================
	# PORTADA
	# * SubirPortada() :: Subimos la portada
	# * cropAvatarPortada() :: Cortamos la imagen
	# ===================================================
	public function SubirPortada() {
		$this->for = 'portada';
		$this->type = 3;
		return $this->newUpload(3);
	}
	public function cropAvatarPortada($key = NULL){
		$source = TS_UPLOADS . "PHPost{$_POST['key']}.{$_POST['ext']}";
		$size = getimagesize($source);
		// COORDENADAS
		$mn = $this->image_size_min;
		$mx = $this->image_size_max;
		// TAMANOS
		$_tw = $mn['w']; 
		$_th = $mn['h'];
		$_w = $mx['w']; 
		$_h = $mx['h'];
		// CREAMOS LA IMAGEN DEPENDIENDO EL TIPO
		switch($size['mime']){
			case 'image/jpeg':
			case 'image/jpg':
				$img = imagecreatefromjpeg($source);
			break;
			case 'image/gif':
				$img = imagecreatefromgif($source);
			break;
			case 'image/png':
				$img = imagecreatefrompng($source);
			break;
		}
		if(!$img) return array('error' => 'No pudimos crear tu portada...');
		//
		$width = imagesx($img);
		$height = imagesy($img);
		$arr = [
			'big' => [1200, 600],
			'portada' => [356, 244],
			'thumb' => [178, 122]
		];
		$lvl = 0;
		foreach ($arr as $key => $data) {
			$nuevoAncho = ($width > $height) ? $data[0] : ($data[1] / $height) * $width;
			$nuevoAlto = ($width > $height) ? ($data[0] / $width) * $height : $data[1];
			$i[$key] = imagecreatetruecolor($nuevoAncho, $nuevoAlto);
			imagecopyresampled($i[$key], $img, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $width, $height);
			// GUARDAMOS...		
			$lvl += 1;
			$root = TS_PORTADAS . "/c0v3rlvl{$lvl}_{$_POST['key']}.{$_POST['ext']}";
			imagejpeg($i[$key], $root, 80);
		}
		// CLEAR*/
		imagedestroy($img);
		imagedestroy($i['big']);
		imagedestroy($i['portada']);
		imagedestroy($i['thumb']);
		// BORRAMOS LA ORIGINAL
		unlink($source);
		//
		return ['error' => 'success'];
	}
}