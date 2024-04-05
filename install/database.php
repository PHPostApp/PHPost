<?php
/**
 * @name database.php
 * @author Miguel92 & PHPost.es
 * @copyright 2011-2023
 * Actualizada y optimizada!
*/

$phpost_sql[] = "CREATE TABLE IF NOT EXISTS `f_comentarios` (
  `cid` int(11) NOT NULL AUTO_INCREMENT,
  `c_foto_id` int(11) NOT NULL DEFAULT 0,
  `c_user` int(11) NOT NULL DEFAULT 0,
  `c_date` int(10) NOT NULL DEFAULT 0,
  `c_body` text NULL,
  `c_ip` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`cid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 ;";

$phpost_sql[] = "CREATE TABLE IF NOT EXISTS `f_favoritos` (
  `fid` int(11) NOT NULL AUTO_INCREMENT,
  `f_foto_id` int(11) NOT NULL DEFAULT 0,
  `f_user` int(11) NOT NULL DEFAULT 0,
  `f_date` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`fid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 ;";

$phpost_sql[] = "CREATE TABLE IF NOT EXISTS `f_fotos` (
  `foto_id` int(11) NOT NULL AUTO_INCREMENT,
  `f_album` int(11) NOT NULL DEFAULT 0,
  `f_title` varchar(40) NOT NULL DEFAULT '',
  `f_date` int(10) NOT NULL DEFAULT 0,
  `f_description` text NULL,
  `f_url` varchar(200) NOT NULL DEFAULT '',
  `f_user` int(11) NOT NULL DEFAULT 0,
  `f_closed` int(1) NOT NULL DEFAULT 0,
  `f_visitas` int(1) NOT NULL DEFAULT 0,
  `f_votos_pos` int(3) NOT NULL DEFAULT 0,
  `f_votos_neg` int(3) NOT NULL DEFAULT 0,
  `f_status` int(1) NOT NULL DEFAULT 0,
  `f_last` int(1) NOT NULL DEFAULT 0,
  `f_hits` int(11) NOT NULL DEFAULT 0,
  `f_ip` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`foto_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 ;";

$phpost_sql[] = "CREATE TABLE IF NOT EXISTS `f_votos` (
  `vid` int(11) NOT NULL AUTO_INCREMENT,
  `v_foto_id` int(11) NOT NULL DEFAULT 0,
  `v_user` int(11) NOT NULL DEFAULT 0,
  `v_type` int(1) NOT NULL DEFAULT 0,
  `v_date` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`vid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 ;";

$phpost_sql[] = "CREATE TABLE IF NOT EXISTS `f_album` (
  `aid` int(11) NOT NULL AUTO_INCREMENT,
  `a_name` varchar(30) NOT NULL DEFAULT '',
  `a_cover` tinytext NULL,
  `a_description` tinytext NULL,
  `a_status` int(1) NOT NULL DEFAULT 0,
  `a_date` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`aid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 ;";

$phpost_sql[] = "CREATE TABLE IF NOT EXISTS `p_borradores` (
  `bid` int(11) NOT NULL AUTO_INCREMENT,
  `b_user` int(11) NOT NULL DEFAULT 0,
  `b_date` int(10) NOT NULL DEFAULT 0,
  `b_title` varchar(120) NOT NULL DEFAULT '',
  `b_portada` tinytext NULL,
  `b_body` text NULL,
  `b_tags` varchar(128) DEFAULT NULL,
  `b_category` int(4) NOT NULL DEFAULT 0,
  `b_private` int(1) NOT NULL DEFAULT 0,
  `b_block_comments` int(1) NOT NULL DEFAULT 0,
  `b_sponsored` int(1) NOT NULL DEFAULT 0,
  `b_sticky` int(1) NOT NULL DEFAULT 0,
  `b_smileys` int(1) NOT NULL DEFAULT 0,
  `b_visitantes` int(1) NOT NULL DEFAULT 0,
  `b_post_id` int(11) NOT NULL DEFAULT 0,
  `b_status` int(1) NOT NULL DEFAULT 1,
  `b_causa` varchar(128) NOT NULL DEFAULT '',
  PRIMARY KEY (`bid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 ;";

$phpost_sql[] = "CREATE TABLE IF NOT EXISTS `p_categorias` (
  `cid` int(11) NOT NULL AUTO_INCREMENT,
  `c_orden` int(11) NOT NULL DEFAULT 0,
  `c_nombre` varchar(32) NOT NULL DEFAULT '',
  `c_seo` varchar(32) NOT NULL DEFAULT '',
  `c_img` varchar(32) NOT NULL DEFAULT 'comments.png',
  PRIMARY KEY (`cid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 ;";

$phpost_sql[] = "INSERT INTO `p_categorias` (`cid`, `c_orden`, `c_nombre`, `c_seo`, `c_img`) VALUES
(1, 1, 'Animaciones', 'animaciones', 'flash.png'),
(2, 2, 'Apuntes y Monografías', 'apuntesymonografias', 'report.png'),
(3, 3, 'Arte', 'arte', 'palette.png'),
(4, 4, 'Autos y Motos', 'autosymotos', 'car.png'),
(5, 5, 'Celulares', 'celulares', 'phone.png'),
(6, 6, 'Ciencia y Educación', 'cienciayeducacion', 'lab.png'),
(7, 7, 'Comics', 'comics', 'comic.png'),
(8, 8, 'Deportes', 'deportes', 'sport.png'),
(9, 9, 'Downloads', 'downloads', 'disk.png'),
(10, 10, 'E-books y Tutoriales', 'ebooksytutoriales', 'ebook.png'),
(11, 11, 'Ecología', 'ecologia', 'nature.png'),
(12, 12, 'Economía y Negocios', 'economiaynegocios', 'economy.png'),
(13, 13, 'Femme', 'femme', 'female.png'),
(14, 14, 'Hazlo tu mismo', 'hazlotumismo', 'escuadra.png'),
(15, 15, 'Humor', 'humor', 'humor.png'),
(16, 16, 'Imágenes', 'imagenes', 'photo.png'),
(17, 17, 'Info', 'info', 'book.png'),
(18, 18, 'Juegos', 'juegos', 'controller.png'),
(19, 19, 'Links', 'links', 'link.png'),
(20, 20, 'Linux', 'linux', 'tux.png'),
(21, 21, 'Mac', 'mac', 'mac.png'),
(22, 22, 'Manga y Anime', 'mangayanime', 'manga.png'),
(23, 23, 'Mascotas', 'mascotas', 'pet.png'),
(24, 24, 'Música', 'musica', 'music.png'),
(25, 25, 'Noticias', 'noticias', 'newspaper.png'),
(26, 26, 'Off Topic', 'offtopic', 'comments.png'),
(27, 27, 'Recetas y Cocina', 'recetasycocina', 'cake.png'),
(28, 28, 'Salud y Bienestar', 'saludybienestar', 'heart.png'),
(29, 29, 'Solidaridad', 'solidaridad', 'salva.png'),
(30, 30, 'xxxxxxxxxx', 'xxxxxxxxxx', 'tscript.png'),
(31, 31, 'Turismo', 'turismo', 'brujula.png'),
(32, 32, 'TV, Peliculas y series', 'tvpeliculasyseries', 'tv.png'),
(33, 33, 'Videos On-line', 'videosonline', 'film.png');";

$phpost_sql[] = "CREATE TABLE IF NOT EXISTS `p_comentarios` (
  `cid` int(11) NOT NULL AUTO_INCREMENT,
  `c_post_id` int(11) NOT NULL DEFAULT 0,
  `c_user` int(11) NOT NULL DEFAULT 0,
  `c_date` int(10) NOT NULL DEFAULT 0,
  `c_body` text NULL,
  `c_votos` int(3) NOT NULL DEFAULT 0,
  `c_status` int(1) NOT NULL DEFAULT 0,
  `c_ip` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`cid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 ;";

$phpost_sql[] = "CREATE TABLE IF NOT EXISTS `p_favoritos` (
  `fav_id` int(11) NOT NULL AUTO_INCREMENT,
  `fav_user` int(11) NOT NULL DEFAULT 0,
  `fav_post_id` int(38) NOT NULL DEFAULT 0,
  `fav_date` int(10) NOT NULL DEFAULT 0,
  PRIMARY KEY (`fav_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 ;";

$phpost_sql[] = "CREATE TABLE IF NOT EXISTS `p_posts` (
  `post_id` int(11) NOT NULL AUTO_INCREMENT,
  `post_user` int(11) NOT NULL DEFAULT 0,
  `post_category` int(4) NOT NULL DEFAULT 0,
  `post_title` varchar(120) NOT NULL DEFAULT '',
  `post_portada` tinytext NULL,
  `post_body` text NULL,
  `post_date` int(10) NOT NULL DEFAULT 0,
  `post_tags` varchar(128) NOT NULL DEFAULT '',
  `post_puntos` int(11) unsigned NOT NULL DEFAULT 0,
  `post_comments` int(11) NOT NULL DEFAULT 0,
  `post_seguidores` int(11) NOT NULL DEFAULT 0,
  `post_shared` int(11) NOT NULL DEFAULT 0,
  `post_favoritos` int(11) NOT NULL DEFAULT 0,
  `post_cache` int(10) NOT NULL DEFAULT 0,
  `post_hits` int(11) NOT NULL DEFAULT 0,
  `post_ip` varchar(50) NOT NULL DEFAULT '',
  `post_private` int(1) NOT NULL DEFAULT 0,
  `post_block_comments` int(1) NOT NULL DEFAULT 0,
  `post_sponsored` int(1) NOT NULL DEFAULT 0,
  `post_sticky` int(1) NOT NULL DEFAULT 0,
  `post_smileys` int(1) NOT NULL DEFAULT 0,
  `post_visitantes` int(1) NOT NULL DEFAULT 0,
  `post_status` int(1) NOT NULL DEFAULT 0,
  FULLTEXT (post_title, post_tags, post_body),
  PRIMARY KEY (`post_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 ;";

$phpost_sql[] = "INSERT INTO `p_posts` (`post_id`, `post_user`, `post_category`, `post_title`, `post_body`, `post_date`, `post_tags`) VALUES (1, 1, 30, 'Bienvenido a $version_title', '[align=center][size=18]Este es el primer post de los miles que tendrá tu web  ;) \r\n\r\nGracias por elegir a [url=https://www.phpost.net/foro/]PHPost[/url] como tu Link Sharing System.[/size][/align]\r\n\r\nCon la versión de [b]{$version_title}[/b] actualizada:
[ol][li]Smarty 4.3.x[/li][li]jQuery 3.7.x[/li][li]Plugins para jQuery actualizado y mejorado[/li][li]Modal modificado y con una nueva función[/li][li]Actualización al crear/editar post[/li][/ol]', 0, 'PHPost, Risus, actualizado, smarty, php');";

$phpost_sql[] = "CREATE TABLE IF NOT EXISTS `p_votos` (
  `voto_id` int(11) NOT NULL AUTO_INCREMENT,
  `tid` int(11) NOT NULL DEFAULT 0,
  `tuser` int(11) NOT NULL DEFAULT 0,
  `cant` int(11) NOT NULL DEFAULT 0,
  `type` int(1) NOT NULL DEFAULT 1,
  `date` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`voto_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 ;";

$phpost_sql[] = "CREATE TABLE IF NOT EXISTS `u_actividad` (
  `ac_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT 0,
  `obj_uno` int(11) NOT NULL DEFAULT 0,
  `obj_dos` int(11) NOT NULL DEFAULT 0,
  `ac_type` int(2) NOT NULL DEFAULT 0,
  `ac_date` int(10) NOT NULL DEFAULT 0,
  PRIMARY KEY (`ac_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 ;";

$phpost_sql[] = "CREATE TABLE IF NOT EXISTS `u_avisos` (
  `av_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT 0,
  `av_subject` varchar(24) NOT NULL DEFAULT '',
  `av_body` text NULL,
  `av_date` int(10) NOT NULL DEFAULT 0,
  `av_read` int(1) NOT NULL DEFAULT 0,
  `av_type` int(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`av_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 ;";

$phpost_sql[] = "CREATE TABLE IF NOT EXISTS `u_bloqueos` (
  `bid` int(11) NOT NULL AUTO_INCREMENT,
  `b_user` int(11) NOT NULL DEFAULT 0,
  `b_auser` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`bid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 ;";

$phpost_sql[] = "CREATE TABLE IF NOT EXISTS `u_follows` (
  `follow_id` int(11) NOT NULL AUTO_INCREMENT,
  `f_user` int(11) NOT NULL DEFAULT 0,
  `f_id` int(11) NOT NULL DEFAULT 0,
  `f_type` int(1) NOT NULL DEFAULT 0,
  `f_date` int(10) NOT NULL DEFAULT 0,
  PRIMARY KEY (`follow_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 ;";

$phpost_sql[] = "CREATE TABLE IF NOT EXISTS `u_mensajes` (
  `mp_id` int(11) NOT NULL AUTO_INCREMENT,
  `mp_to` int(11) NOT NULL DEFAULT 0,
  `mp_from` int(11) NOT NULL DEFAULT 0,
  `mp_answer` int(1) NOT NULL DEFAULT 0,
  `mp_read_to` int(1) NOT NULL DEFAULT 0,
  `mp_read_from` int(1) NOT NULL DEFAULT 1,
  `mp_read_mon_to` int(1) NOT NULL DEFAULT 0,
  `mp_read_mon_from` int(1) NOT NULL DEFAULT 1,
  `mp_del_to` int(1) NOT NULL DEFAULT 0,
  `mp_del_from` int(1) NOT NULL DEFAULT 0,
  `mp_subject` varchar(50) NOT NULL DEFAULT '',
  `mp_preview` varchar(75) NOT NULL DEFAULT '',
  `mp_date` int(10) NOT NULL DEFAULT 0,
  PRIMARY KEY (`mp_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 ;";

$phpost_sql[] = "CREATE TABLE IF NOT EXISTS `u_miembros` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(16) NOT NULL DEFAULT '',
  `user_password` varchar(66) NOT NULL DEFAULT '',
  `user_email` varchar(35) NOT NULL DEFAULT '',
  `user_avatares` tinytext NULL,
  `user_socials` tinytext NULL,
  `user_cover` tinytext NULL,
  `user_chat` int(12) NOT NULL DEFAULT 0,
  `user_rango` int(3) NOT NULL DEFAULT 3,
  `user_puntos` int(6) unsigned NOT NULL DEFAULT 0,
  `user_posts` int(11) NOT NULL DEFAULT 0,
  `user_comentarios` int(11) NOT NULL DEFAULT 0,
  `user_seguidores` int(11) NOT NULL DEFAULT 0,
  `user_seguidos` int(11) NOT NULL DEFAULT 0,
  `user_amigos` int(11) NOT NULL DEFAULT 0,
  `user_cache` int(10) NOT NULL DEFAULT 0,
  `user_puntosxdar` int(2) unsigned NOT NULL DEFAULT 0,
  `user_bad_hits` int(2) unsigned NOT NULL DEFAULT 0,
  `user_nextpuntos` int(10) NOT NULL DEFAULT 0,
  `user_registro` int(10) NOT NULL DEFAULT 0,
  `user_lastlogin` int(10) NOT NULL DEFAULT 0,
  `user_lastactive` int(10) NOT NULL DEFAULT 0,
  `user_lastpost` int(10) NOT NULL DEFAULT 0,
  `user_last_ip` varchar(38) NOT NULL DEFAULT 0,
  `user_name_changes` int(11) NOT NULL DEFAULT 3,
  `user_activo` int(1) NOT NULL DEFAULT 0,
  `user_baneado` int(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 ;";

$phpost_sql[] = "CREATE TABLE IF NOT EXISTS `u_nicks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT 0,
  `user_email` varchar(50) NOT NULL DEFAULT '',
  `name_1` varchar(15) NOT NULL DEFAULT '',
  `name_2` varchar(15) NOT NULL DEFAULT '',
  `hash` varchar(66) NOT NULL DEFAULT '',
  `time` int(11) NOT NULL DEFAULT 0,
  `ip` varchar(50) NOT NULL DEFAULT '',
  `estado` int(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";

$phpost_sql[] = "CREATE TABLE IF NOT EXISTS `u_monitor` (
  `not_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT 0,
  `obj_user` int(11) NOT NULL DEFAULT 0,
  `obj_uno` int(11) NOT NULL DEFAULT 0,
  `obj_dos` int(11) NOT NULL DEFAULT 0,
  `obj_tres` int(11) NOT NULL DEFAULT 0,
  `not_type` int(2) NOT NULL DEFAULT 0,
  `not_date` int(10) NOT NULL DEFAULT 0,
  `not_total` int(2) NOT NULL DEFAULT 1,
  `not_menubar` int(1) NOT NULL DEFAULT 2,
  `not_monitor` int(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`not_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 ;";

$phpost_sql[] = "CREATE TABLE IF NOT EXISTS `u_muro` (
  `pub_id` int(11) NOT NULL AUTO_INCREMENT,
  `p_user` int(11) NOT NULL DEFAULT 0,
  `p_user_pub` int(11) NOT NULL DEFAULT 0,
  `p_date` int(10) NOT NULL DEFAULT 0,
  `p_comments` int(4) NOT NULL DEFAULT 0,
  `p_body` text NULL,
  `p_likes` int(4) NOT NULL DEFAULT 0,
  `p_type` int(1) NOT NULL DEFAULT 0,
  `p_ip` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`pub_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 ;";

$phpost_sql[] = "CREATE TABLE IF NOT EXISTS `u_muro_adjuntos` (
  `adj_id` int(11) NOT NULL AUTO_INCREMENT,
  `pub_id` int(11) NOT NULL DEFAULT 0,
  `a_title` varchar(100) NOT NULL DEFAULT '',
  `a_url` text NULL,
  `a_img` text NULL,
  `a_desc` text NULL,
  PRIMARY KEY (`adj_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 ;";

$phpost_sql[] = "CREATE TABLE IF NOT EXISTS `u_muro_comentarios` (
  `cid` int(11) NOT NULL AUTO_INCREMENT,
  `pub_id` int(11) NOT NULL DEFAULT 0,
  `c_user` int(11) NOT NULL DEFAULT 0,
  `c_date` int(10) NOT NULL DEFAULT 0,
  `c_body` text NULL,
  `c_likes` int(4) NOT NULL DEFAULT 0,
  `c_ip` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`cid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 ;";

$phpost_sql[] = "CREATE TABLE IF NOT EXISTS `u_muro_likes` (
  `like_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT 0,
  `obj_id` int(11) NOT NULL DEFAULT 0,
  `obj_type` int(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`like_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 ;";

$phpost_sql[] = "CREATE TABLE IF NOT EXISTS `u_perfil` (
  `user_id` int(11) NOT NULL DEFAULT 0,
  `user_dia` int(2) NOT NULL DEFAULT 0,
  `user_mes` int(2) NOT NULL DEFAULT 0,
  `user_ano` int(4) NOT NULL DEFAULT 0,
  `user_pais` varchar(2) NOT NULL DEFAULT '',
  `user_estado` int(2) NOT NULL DEFAULT 1,
  `user_sexo` int(1) NOT NULL DEFAULT 1,
  `user_firma` text NULL,
  `user_portada` tinytext NULL,
  `p_nombre` varchar(32) NOT NULL DEFAULT '',
  `p_avatar` int(1) NOT NULL DEFAULT 0,
  `p_mensaje` varchar(60) NOT NULL DEFAULT '',
  `p_sitio` varchar(60) NOT NULL DEFAULT '',
  `p_socials` text NULL,
  `p_gustos` varchar(71) NOT NULL DEFAULT 'a:5:{i:0;i:0;i:1;i:0;i:2;i:0;i:3;i:0;i:4;i:0;}',
  `p_estado` int(1) NOT NULL DEFAULT 0,
  `p_hijos` int(1) NOT NULL DEFAULT 0,
  `p_vivo` int(1) NOT NULL DEFAULT 0,
  `p_altura` int(3) NOT NULL DEFAULT 0,
  `p_peso` int(3) NOT NULL DEFAULT 0,
  `p_pelo` int(1) NOT NULL DEFAULT 0,
  `p_ojos` int(1) NOT NULL DEFAULT 0,
  `p_fisico` int(1) NOT NULL DEFAULT 0,
  `p_dieta` int(1) NOT NULL DEFAULT 0,
  `p_tengo` varchar(60) NOT NULL DEFAULT 'a:2:{i:0;i:0;i:1;i:0;}',
  `p_fumo` int(1) NOT NULL DEFAULT 0,
  `p_tomo` int(1) NOT NULL DEFAULT 0,
  `p_estudios` int(1) NOT NULL DEFAULT 0,
  `p_idiomas` varchar(102) NOT NULL DEFAULT 'a:7:{i:0;i:0;i:1;i:0;i:2;i:0;i:3;i:0;i:4;i:0;i:5;i:0;i:6;i:0;}',
  `p_profesion` varchar(32) NOT NULL DEFAULT '',
  `p_empresa` varchar(32) NOT NULL DEFAULT '',
  `p_sector` int(2) NOT NULL DEFAULT 0,
  `p_ingresos` int(1) NOT NULL DEFAULT 0,
  `p_int_prof` text NULL,
  `p_hab_prof` text NULL,
  `p_intereses` text NULL,
  `p_hobbies` text NULL,
  `p_tv` text NULL,
  `p_musica` text NULL,
  `p_deportes` text NULL,
  `p_libros` text NULL,
  `p_peliculas` text NULL,
  `p_comida` text NULL,
  `p_heroes` text NULL,
  `p_configs` varchar(100) NOT NULL DEFAULT 'a:3:{s:1:\"m\";s:1:\"5\";s:2:\"mf\";i:5;s:3:\"rmp\";s:1:\"5\";}',
  `p_total` varchar(54) NOT NULL DEFAULT 'a:6:{i:0;i:5;i:1;i:0;i:2;i:0;i:3;i:0;i:4;i:0;i:5;i:0;}',
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;";

$phpost_sql[] = "CREATE TABLE IF NOT EXISTS `u_portal` (
  `user_id` int(11) NOT NULL DEFAULT 0,
  `last_posts_visited` text NULL,
  `last_posts_shared` text NULL,
  `last_posts_cats` text NULL,
  `c_monitor` text NOT NULL DEFAULT 'f1,f2,f3,f8,f9,f4,f5,f10,f6,f7,f11,f12,f13,f14',
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;";

$phpost_sql[] = "CREATE TABLE IF NOT EXISTS `u_rangos` (
  `rango_id` int(3) NOT NULL AUTO_INCREMENT,
  `r_name` varchar(32) NOT NULL DEFAULT '',
  `r_color` varchar(6) NOT NULL DEFAULT 171717,
  `r_image` varchar(32) NOT NULL DEFAULT 'new.png',
  `r_cant` int(5) NOT NULL DEFAULT 0,
  `r_allows` varchar(1000) NOT NULL DEFAULT '',
  `r_type` int(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`rango_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=8 ;";

$phpost_sql[] = "INSERT INTO `u_rangos` (`rango_id`, `r_name`, `r_color`, `r_image`, `r_cant`, `r_allows`, `r_type`) VALUES
(1, 'Administrador', 'ff0033', 'rosette', 0, 'a:4:{s:4:\"suad\";s:2:\"on\";s:4:\"goaf\";s:1:\"5\";s:5:\"gopfp\";s:2:\"20\";s:5:\"gopfd\";s:2:\"50\";}', 0),
(2, 'Moderador', 'ff9900', 'shield', 0, 'a:4:{s:4:\"sumo\";s:2:\"on\";s:4:\"goaf\";s:2:\"15\";s:5:\"gopfp\";s:2:\"18\";s:5:\"gopfd\";s:2:\"30\";}', 0),
(3, 'Novato', 171717, 'new', 0, 'a:12:{s:4:\"godp\";s:2:\"on\";s:4:\"gopp\";s:2:\"on\";s:5:\"gopcp\";s:2:\"on\";s:5:\"govpp\";s:2:\"on\";s:5:\"govpn\";s:2:\"on\";s:5:\"goepc\";s:2:\"on\";s:5:\"godpc\";s:2:\"on\";s:4:\"gopf\";s:2:\"on\";s:5:\"gopcf\";s:2:\"on\";s:4:\"goaf\";s:2:\"20\";s:5:\"gopfp\";s:1:\"5\";s:5:\"gopfd\";s:1:\"5\";}', 0),
(4, 'New Full User', '0198E7', 'star_bronze_3', 50, 'a:12:{s:4:\"godp\";s:2:\"on\";s:4:\"gopp\";s:2:\"on\";s:5:\"gopcp\";s:2:\"on\";s:5:\"govpp\";s:2:\"on\";s:5:\"govpn\";s:2:\"on\";s:5:\"goepc\";s:2:\"on\";s:5:\"godpc\";s:2:\"on\";s:4:\"gopf\";s:2:\"on\";s:5:\"gopcf\";s:2:\"on\";s:4:\"goaf\";s:2:\"20\";s:5:\"gopfp\";s:2:\"10\";s:5:\"gopfd\";s:2:\"10\";}', 1),
(5, 'Full User', '00ccff', 'star_silver_3', 70, 'a:12:{s:4:\"godp\";s:2:\"on\";s:4:\"gopp\";s:2:\"on\";s:5:\"gopcp\";s:2:\"on\";s:5:\"govpp\";s:2:\"on\";s:5:\"govpn\";s:2:\"on\";s:5:\"goepc\";s:2:\"on\";s:5:\"godpc\";s:2:\"on\";s:4:\"gopf\";s:2:\"on\";s:5:\"gopcf\";s:2:\"on\";s:4:\"goaf\";s:2:\"20\";s:5:\"gopfp\";s:2:\"12\";s:5:\"gopfd\";s:2:\"20\";}', 1),
(6, 'Great User', '01A021', 'star_gold_3', 0, 'a:12:{s:4:\"godp\";s:2:\"on\";s:4:\"gopp\";s:2:\"on\";s:5:\"gopcp\";s:2:\"on\";s:5:\"govpp\";s:2:\"on\";s:5:\"govpn\";s:2:\"on\";s:5:\"goepc\";s:2:\"on\";s:5:\"godpc\";s:2:\"on\";s:4:\"gopf\";s:2:\"on\";s:5:\"gopcf\";s:2:\"on\";s:4:\"goaf\";s:2:\"20\";s:5:\"gopfp\";s:2:\"11\";s:5:\"gopfd\";s:2:\"15\";}', 0),
(7, 'Gold User', 'cc6600', 'asterisk_yellow', 120, 'a:12:{s:4:\"godp\";s:2:\"on\";s:4:\"gopp\";s:2:\"on\";s:5:\"gopcp\";s:2:\"on\";s:5:\"govpp\";s:2:\"on\";s:5:\"govpn\";s:2:\"on\";s:5:\"goepc\";s:2:\"on\";s:5:\"godpc\";s:2:\"on\";s:4:\"gopf\";s:2:\"on\";s:5:\"gopcf\";s:2:\"on\";s:4:\"goaf\";s:2:\"20\";s:5:\"gopfp\";s:2:\"12\";s:5:\"gopfd\";s:2:\"25\";}', 1);";

$phpost_sql[] = "CREATE TABLE IF NOT EXISTS `u_respuestas` (
  `mr_id` int(11) NOT NULL AUTO_INCREMENT,
  `mp_id` int(11) NOT NULL DEFAULT 0,
  `mr_from` int(11) NOT NULL DEFAULT 0,
  `mr_body` text NULL,
  `mr_ip` varchar(50) NOT NULL DEFAULT '',
  `mr_date` int(10) NOT NULL DEFAULT 0,
  PRIMARY KEY (`mr_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 ;";

$phpost_sql[] = "CREATE TABLE IF NOT EXISTS `u_sessions` (
  `session_id` varchar(32) NOT NULL DEFAULT '',
  `session_user_id` int(11) unsigned NOT NULL DEFAULT 0,
  `session_ip` varchar(50) NOT NULL DEFAULT '',
  `session_time` int(10) unsigned NOT NULL DEFAULT 0,
  `session_autologin` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`session_id`),
  KEY `session_user_id` (`session_user_id`),
  KEY `session_time` (`session_time`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;";

$phpost_sql[] = "CREATE TABLE IF NOT EXISTS `u_suspension` (
  `susp_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT 0,
  `susp_causa` text NULL,
  `susp_date` int(10) NOT NULL DEFAULT 0,
  `susp_termina` int(10) NOT NULL DEFAULT 0,
  `susp_mod` int(11) NOT NULL DEFAULT 0,
  `susp_ip` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`susp_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 ;";

$phpost_sql[] = "CREATE TABLE IF NOT EXISTS `w_afiliados` (
  `aid` int(11) NOT NULL AUTO_INCREMENT,
  `a_titulo` varchar(35) NOT NULL DEFAULT '',
  `a_url` varchar(40) NOT NULL DEFAULT '',
  `a_banner` varchar(100) NOT NULL DEFAULT '',
  `a_descripcion` varchar(200) NOT NULL DEFAULT '',
  `a_sid` int(11) NOT NULL DEFAULT 0,
  `a_hits_in` int(11) NOT NULL DEFAULT 0,
  `a_hits_out` int(11) NOT NULL DEFAULT 0,
  `a_date` int(10) NOT NULL DEFAULT 0,
  `a_active` int(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`aid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 ;";

$phpost_sql[] = "CREATE TABLE IF NOT EXISTS `w_configuracion` (
  `tscript_id` int(11) NOT NULL DEFAULT 0,
  `titulo` varchar(24) NOT NULL DEFAULT '',
  `slogan` varchar(32) NOT NULL DEFAULT '',
  `url` tinytext NULL,
  `email` varchar(60) NOT NULL DEFAULT '',
  `banner` varchar(100) NOT NULL DEFAULT '',
  `tema_id` int(11) NOT NULL DEFAULT 1,
  `updated` int(1) NOT NULL DEFAULT 1,
  `ads_300` text NULL,
  `ads_468` text NULL,
  `ads_160` text NULL,
  `ads_728` text NULL,
  `ads_search` varchar(50) NOT NULL DEFAULT '',
  `c_last_active` int(2) NOT NULL DEFAULT 15,
  `c_upperkey` int(1) NOT NULL DEFAULT 1,
  `c_allow_sess_ip` int(1) NOT NULL DEFAULT 1,
  `c_count_guests` int(1) NOT NULL DEFAULT 0,
  `c_reg_active` int(1) NOT NULL DEFAULT 1,
  `c_reg_activate` int(1) NOT NULL DEFAULT 1,
  `c_reg_rango` int(5) NOT NULL DEFAULT 3,
  `c_met_welcome` int(1) NOT NULL DEFAULT 0,
  `c_message_welcome` varchar(500) NOT NULL DEFAULT 'Hola [usuario], [welcome] a [b][web][/b].',
  `c_fotos_private` int(11) NOT NULL DEFAULT 0,
  `c_hits_guest` int(1) NOT NULL DEFAULT 0,
  `c_keep_points` int(1) NOT NULL DEFAULT 0,
  `c_allow_points` int(11) NOT NULL DEFAULT 0,
  `c_allow_edad` int(11) NOT NULL DEFAULT 16,
  `c_max_posts` int(2) NOT NULL DEFAULT 50,
  `c_max_com` int(3) NOT NULL DEFAULT 50,
  `c_max_nots` int(3) NOT NULL DEFAULT 99,
  `c_max_acts` int(3) NOT NULL DEFAULT 99,
  `c_newr_type` int(11) NOT NULL DEFAULT 0,
  `c_allow_sump` int(11) NOT NULL DEFAULT 0,
  `c_allow_firma` int(1) NOT NULL DEFAULT 1,
  `c_allow_upload` int(1) NOT NULL DEFAULT 0,
  `c_allow_portal` int(1) NOT NULL DEFAULT 1,
  `c_allow_live` int(1) NOT NULL DEFAULT 1,
  `c_see_mod` int(1) NOT NULL DEFAULT 0,
  `c_stats_cache` int(7) NOT NULL DEFAULT 15,
  `c_desapprove_post` int(1) NOT NULL DEFAULT 0,
  `offline` int(1) NOT NULL DEFAULT 0,
  `offline_message` varchar(255) NOT NULL DEFAULT 'Estamos en mantenimiento',
  `pkey` varchar(55) NOT NULL DEFAULT '',
  `skey` varchar(55) NOT NULL DEFAULT '',
  `version` varchar(22) NOT NULL DEFAULT '',
  `version_code` varchar(22) NOT NULL DEFAULT '',
  PRIMARY KEY (`tscript_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;";

$phpost_sql[] = "INSERT INTO `w_configuracion` (`tscript_id`) VALUES (1);";

$phpost_sql[] = "CREATE TABLE IF NOT EXISTS `w_denuncias` (
  `did` int(11) NOT NULL AUTO_INCREMENT,
  `obj_id` int(11) NOT NULL DEFAULT 0,
  `d_user` int(11) NOT NULL DEFAULT 0,
  `d_razon` int(2) NOT NULL DEFAULT 0,
  `d_extra` text NULL,
  `d_total` int(1) NOT NULL DEFAULT 1,
  `d_type` int(1) NOT NULL DEFAULT 0,
  `d_date` int(10) NOT NULL DEFAULT 0,
  PRIMARY KEY (`did`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 ;";

$phpost_sql[] = "CREATE TABLE IF NOT EXISTS `w_contacts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT 0,
  `user_email` varchar(50) NOT NULL DEFAULT '',
  `time` int(15) NOT NULL DEFAULT 0,
  `type` int(1) NOT NULL DEFAULT 0,
  `hash` varchar(66) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";

$phpost_sql[] = "CREATE TABLE IF NOT EXISTS `w_medallas` (
  `medal_id` int(11) NOT NULL AUTO_INCREMENT,
  `m_autor` int(11) NOT NULL DEFAULT 0,
  `m_title` varchar(25) NOT NULL DEFAULT '',
  `m_description` varchar(120) NOT NULL DEFAULT '',
  `m_image` varchar(120) NOT NULL DEFAULT '',
  `m_cant` int(11) NOT NULL DEFAULT 0,
  `m_type` int(1) NOT NULL DEFAULT 0,
  `m_cond_user` int(11) NOT NULL DEFAULT 0,
  `m_cond_user_rango` int(11) NOT NULL DEFAULT 0,
  `m_cond_post` int(11) NOT NULL DEFAULT 0,
  `m_cond_foto` int(11) NOT NULL DEFAULT 0,
  `m_date` int(11) NOT NULL DEFAULT 0,
  `m_total` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`medal_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";

$phpost_sql[] = "CREATE TABLE IF NOT EXISTS `w_medallas_assign` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `medal_id` int(11) NOT NULL DEFAULT 0,
  `medal_for` int(11) NOT NULL DEFAULT 0,
  `medal_date` int(11) NOT NULL DEFAULT 0,
  `medal_ip` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";

$phpost_sql[] = "CREATE TABLE IF NOT EXISTS `w_historial` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pofid` int(11) NOT NULL DEFAULT 0,
  `type` int(1) NOT NULL DEFAULT 0,
  `action` int(1) NOT NULL DEFAULT 0,
  `mod` int(11) NOT NULL DEFAULT 0,
  `reason` text NULL,
  `date` int(11) NOT NULL DEFAULT 0,
  `mod_ip` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 ;";

$phpost_sql[] = "CREATE TABLE IF NOT EXISTS `w_noticias` (
  `not_id` int(11) NOT NULL AUTO_INCREMENT,
  `not_body` text NULL,
  `not_autor` INT( 11 ) NOT NULL,
  `not_date` int(10) NOT NULL DEFAULT 0,
  `not_active` int(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`not_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 ;";

$phpost_sql[] = "CREATE TABLE IF NOT EXISTS `w_blacklist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` int(1) NOT NULL DEFAULT 0,
  `value` varchar(50) NOT NULL DEFAULT '',
  `reason` varchar(120) NOT NULL DEFAULT '',
  `author` int(11) NOT NULL DEFAULT 0,
  `date` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;";

$phpost_sql[] = "CREATE TABLE IF NOT EXISTS `w_badwords` (
  `wid` int(11) NOT NULL AUTO_INCREMENT,
  `word` varchar(250) NOT NULL DEFAULT '',
  `swop` varchar(250) NOT NULL DEFAULT '',
  `method` int(1) NOT NULL DEFAULT 0,
  `type` int(1) NOT NULL DEFAULT 0,
  `author` int(11) NOT NULL DEFAULT 0,
  `reason` varchar(255) NOT NULL DEFAULT '',
  `date` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`wid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;";

$phpost_sql[] = "CREATE TABLE IF NOT EXISTS `w_stats` (
  `stats_no` int(1) NOT NULL DEFAULT 0,
  `stats_max_online` int(11) NOT NULL DEFAULT 0,
  `stats_max_time` int(10) NOT NULL DEFAULT 0,
  `stats_time` int(10) NOT NULL DEFAULT 0,
  `stats_time_cache` int(10) NOT NULL DEFAULT 0,
  `stats_time_foundation` int(11) NOT NULL DEFAULT 0,
  `stats_time_upgrade` int(11) NOT NULL DEFAULT 0,
  `stats_miembros` int(11) NOT NULL DEFAULT 0,
  `stats_posts` int(11) NOT NULL DEFAULT 0,
  `stats_fotos` int(11) NOT NULL DEFAULT 0,
  `stats_comments` int(11) NOT NULL DEFAULT 0,
  `stats_foto_comments` int(11) NOT NULL DEFAULT 0,
  `stats_comunidades` int(11) NOT NULL DEFAULT 0,
  `stats_temas` int(11) NOT NULL DEFAULT 0,
  `stats_respuestas` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`stats_no`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;";

$phpost_sql[] = "INSERT INTO `w_stats` (`stats_no`, `stats_max_online`) VALUES (1, 0);";

$phpost_sql[] = "CREATE TABLE IF NOT EXISTS `w_temas` (
  `tid` int(11) NOT NULL AUTO_INCREMENT,
  `t_name` tinytext NULL,
  `t_url` tinytext NULL,
  `t_path` tinytext NULL,
  `t_copy` tinytext NULL,
  PRIMARY KEY (`tid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1;";

$phpost_sql[] = "CREATE TABLE IF NOT EXISTS `w_visitas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(11) NOT NULL DEFAULT 0,
  `for` int(11) NOT NULL DEFAULT 0,
  `type` int(1) NOT NULL DEFAULT 0,
  `date` int(11) NOT NULL DEFAULT 0,
  `ip` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  INDEX (`for`, `type`, `user`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";

$phpost_sql[] = "CREATE TABLE IF NOT EXISTS `w_social` (
  `social_id` int(11) NOT NULL AUTO_INCREMENT,
  `social_name` varchar(22) NOT NULL DEFAULT '',
  `social_client_id` tinytext NULL,
  `social_client_secret` tinytext NULL,
  `social_redirect_uri` tinytext NULL,
  `social_scope` tinytext NULL,
  `social_state` tinytext NULL,
  PRIMARY KEY (`social_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 ;";

$phpost_sql[] = "CREATE TABLE IF NOT EXISTS `w_site_seo` (
  `seo_id` int(11) NOT NULL  DEFAULT 0,
  `seo_titulo` varchar(60) NOT NULL DEFAULT '',
  `seo_descripcion` varchar(160) NOT NULL DEFAULT '',
  `seo_portada` tinytext NULL,
  `seo_favicon` tinytext NULL,
  `seo_keywords` text NULL,
  `seo_images` text NULL,
  `seo_robots_data` text NULL DEFAULT '',
  `seo_robots` int(1) NULL DEFAULT 0,
  `seo_sitemap` int(1) NULL DEFAULT 0,
  `seo_facebook_app` varchar(120) NULL DEFAULT '',
  `seo_twitter_user` varchar(120) NULL DEFAULT '',
  PRIMARY KEY (`seo_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 ;";

$phpost_sql[] = "INSERT INTO `w_site_seo` (`seo_id`) VALUES (1);";

$phpost_sql[] = "CREATE TABLE c_chat_zones (
  `zone_id` int(12) NOT NULL AUTO_INCREMENT,
  `zone_name` varchar(120) NOT NULL DEFAULT '',
  `zone_perms` text,
  `zone_advertisement` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`zone_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 ;";

$phpost_sql[] = "CREATE TABLE c_chat_messages (
  `msg_id` int(12) NOT NULL AUTO_INCREMENT,
  `msg_user` int(12) NOT NULL DEFAULT 0,
  `msg_zone` int(12) NOT NULL DEFAULT 1,
  `msg_text` text,
  `msg_date` int(12) NOT NULL DEFAULT 0,
  PRIMARY KEY (`msg_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 ;";

$phpost_sql[] = "CREATE TABLE c_chat_private_messages (
  `private_id` int(12) NOT NULL AUTO_INCREMENT,
  `private_user` int(12) NOT NULL DEFAULT 0,
  `private_to_user` int(12) NOT NULL DEFAULT 0,
  `private_text` text,
  `private_date` int(12) NOT NULL DEFAULT 0,
  PRIMARY KEY (`private_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 ;";

$phpost_sql[] = "CREATE TABLE c_chat_blacklist (
`ban_id` int(12) NOT NULL AUTO_INCREMENT,
  `ban_user` int(12) NOT NULL DEFAULT 0,
  `ban_expire` int(12) NOT NULL DEFAULT 0,
  `ban_date` int(12) NOT NULL DEFAULT 0,
  PRIMARY KEY (`ban_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 ;";