{php}
	function mpaginacion(){
		//Variables principales
		$c_max_posts = db_exec('fetch_assoc', db_exec(array(__FILE__, __LINE__), 'query', 'SELECT c_max_posts FROM w_configuracion'));
		$datos = db_exec('fetch_assoc', db_exec(array(__FILE__, __LINE__), 'query', 'SELECT stats_posts FROM w_stats'));
		$num_rows = $datos['stats_posts'];
		$post_pp = $c_max_posts['c_max_posts'];
		$lastpage = ceil($num_rows / $post_pp);
		//Obtenemos el valor de la pagina actual
		if(!$_GET["page"]){
			$page=1;
		}else{
			$page = $_GET["page"];
		}
		//Creamos el array de la paginación
		for($i=0;$i<=$lastpage;$i++){
			$mpag[$i]=$i;
		}
		//Calculamos cuantas pestañas mostrar
		$v = $page + 9;
		for($c=9;$v>$lastpage; $c--){
			$v=$page+$c;
		}
		//Enlace a pagina anterior
		if($page>1){
			$anterior = $page - 1;
			echo "<a href=\"pagina".$anterior."\" title=\"Página anterior\">Ant.</a>";
		}
		//Mostramos los enlaces de la paginación
		for($i=$page; $i<=$v; $i++){
			echo "<a href=\"pagina".$mpag[$i]."\">".$mpag[$i]."</a>";
		}
		//Enlace a pagina siguiente
		if($page< $lastpage){
			$siguiente = $page + 1;
			echo "<a href=\"pagina".$siguiente."\" title=\"Página siguiente\">Sig.</a>";
		}
	}
	
	//Mostramos la paginacion
	if(!$_GET["cat"]){
		mpaginacion();
	}
{/php}