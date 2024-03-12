<?php 

include realpath(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'header.php';
include TS_EXTRA . 'geodata.php';

$c_acceso = 1;
$c_permisos = 3;

$last = (int)db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT MAX(c_id) AS last FROM c_comunidades"))['last'];

for($i = 1; $i <= $last; $i++) {
	$resultado = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT c_categoria, c_seo, c_img FROM c_comunidades LEFT JOIN c_categorias ON c_categoria = cid WHERE c_id = $i"));
	$origen = TS_PUBLIC . 'images' . TS_PATH. 'comunidades' . TS_PATH . $resultado['c_img'];
	if(file_exists($origen)) {
		copy($origen, TS_UPLOADS . 'c_' . $i . '.jpg');
	}
}



//;

/*c_id, c_autor, c_nombre, c_nombre_corto, c_categoria, c_sub_categoria, c_pais, c_descripcion, c_acceso, c_permisos, c_estado, c_fecha, c_ip, 


$last = (int)db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT MAX(c_id) AS last FROM c_comunidades"))['last'];

$users = (int)db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT MAX(user_id) AS last FROM u_miembros"))['last'];


$posts = (array)$tsCore->getUrlContent("https://dummyjson.com/posts");
foreach($posts as $post) {
	$decode = json_decode($post, true);
	foreach($decode['posts'] as $p) {
		$data = [
			't_id' => $p['id'],
			't_titulo' => $p['title'],
			't_cuerpo' => str_replace("'", "-", $p['body']),
			't_comunidad' => rand(1, $last),
			't_autor' => rand(1, $users),
			't_cerrado' => 0,
			't_estado' => 0,
			't_ip' => '::1'
		];
		foreach ($data as $dk => $d) {
			$data[$dk] = (is_numeric($d) ? (int)$d : '"'.$d.'"') ;
		}
		$j[] =  '(' . join(',', $data) . ')';
	}
	var_dump("INSERT INTO c_temas(t_id, t_titulo, t_cuerpo, t_comunidad, t_autor, t_cerrado, t_estado, t_ip) VALUES".join(',', $j));
}


$comunidades = [
	'Pradera de la Ilusión',
	'Terraza del Crepúsculo',
	'Barrio Nebuloso',
	'Urbanización Etérea',
	'Isla de las Ilusiones',
	'Campos de la Fantasía',
	'Rincón del Arcoíris',
	'Refugio de Estrellas',
	'Manantial Misterioso',
	'Escondite Encantado'
];
$descripciones = [
	'Pradera de la Ilusión: Un tranquilo vecindario rodeado de un paisaje onírico y mágico, donde los sueños se entrelazan con la realidad.',
	'Terraza del Crepúsculo: Una comunidad ubicada en lo alto de una colina, ofreciendo vistas espectaculares de los atardeceres, donde el cielo se tiñe de tonos dorados y rosados al final del día.',
	'Barrio Nebuloso: Un vecindario envuelto en una atmósfera misteriosa y enigmática, donde la neblina parece fundir la realidad y la fantasía.',
	'Urbanización Etérea: Un conjunto residencial que parece sacado de un cuento de hadas, con arquitectura etérea y jardines llenos de encanto y magia.',
	'Isla de las Ilusiones: Una comunidad situada en una isla paradisíaca, donde la imaginación y la fantasía son el motor de cada día, creando un ambiente único y sorprendente.',
	'Campos de la Fantasía: Un área residencial rodeada de extensos campos verdes y praderas floridas, que inspiran a los habitantes a dejar volar su imaginación y creatividad.',
	'Rincón del Arcoíris: Un acogedor vecindario donde la diversidad y la alegría son celebradas, reflejadas en los vibrantes colores del arcoíris que parecen iluminar cada rincón.',
	'Refugio de Estrellas: Una comunidad tranquila y serena, alejada del bullicio de la ciudad, donde las noches despejadas permiten contemplar el brillo de las estrellas en todo su esplendor.',
	'Manantial Misterioso: Un lugar rodeado de naturaleza exuberante y un aire de misterio, donde un manantial oculto guarda secretos ancestrales y energías rejuvenecedoras.',
	'Escondite Encantado: Un pequeño y encantador refugio, escondido entre árboles centenarios y senderos ocultos, donde la magia y la tranquilidad son el sello distintivo de la comunidad.'
];

//c_temas
/*$last = (int)db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT MAX(c_id) AS last FROM c_comunidades"))['last'];

$users = (int)db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT MAX(user_id) AS last FROM u_miembros"))['last'];

$cats = (int)db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT MAX(cid) AS last FROM c_categorias"))['last'];


foreach ($comunidades as $key => $comunidad) {
	$user = rand(1, $users);
	$cat = rand(1, $cats);
	$subcats = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT MIN(sid) AS min, MAX(sid) AS max FROM c_subcategorias WHERE s_type = $cat"));
	$subcat = rand($subcats['min'], $subcats['max']);
	
	$data = [
		'c_id' => ++$last,
		'c_autor' => $user,
		'c_nombre' => $comunidad,
		'c_nombre_corto' => strtolower($tsCore->setSEO($comunidad)),
		'c_categoria' => $cat,
		'c_sub_categoria' => $subcat,
		'c_pais' => rand(1, $cat*10),
		'c_descripcion' => $descripciones[$key],
		'c_acceso' => $c_acceso,
		'c_permisos' => $c_permisos,
		'c_estado' => 0,
		'c_fecha' => time(),
		'c_ip' => '::1'
	];
	foreach ($data as $dk => $d) {
		$data[$dk] = (is_numeric($d) ? (int)$d : "'$d'") ;
	}
	$j[] =  '(' . join(',', $data) . ')';

}

if(db_exec([__FILE__, __LINE__], 'query', "INSERT INTO c_comunidades(c_id, c_autor, c_nombre, c_nombre_corto, c_categoria, c_sub_categoria, c_pais, c_descripcion, c_acceso, c_permisos, c_estado, c_fecha, c_ip) VALUES".join(',', $j))) {
	echo 'Listo';
}*/