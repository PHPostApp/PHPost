/**
 * Con estas funciones "sameModal()" y "sameFn()"
 * y de esta forma simplificamos
*/
sameModal = (sametitle, samebody, sameaction) => {
	mydialog.faster({
   	title: sametitle,
   	body: samebody,
   	buttons: {
   		ok: { text: 'S&iacute;', action: sameaction },
   		fail: { text: 'No', action: 'close' }
   	}
   });
}
sameFn = (page, params, element) => {
   $('#loading').fadeIn(250);
   mydialog.procesando_inicio();
	$.post(`${global_data.url}/${page}.php`, params, a => {
   	mydialog.procesando_fin();
   	mydialog.alert((a.charAt(0) == '0' ? 'Opps!' : 'Hecho'), a.substring(3), false);
   	mydialog.center();
   	if(a.charAt(0) == '1') $(element).fadeOut(); 
   	$('#loading').fadeOut(350);
   });
}
var admin = {
	//
	updated: (gew) => {
		if(!gew)
         sameModal('Actualizar', '&#191;Quieres Actualizar los archivos?', `admin.updated(true)`)
      else {
		   mydialog.procesando_inicio();
		   mydialog.title('Este proceso puede llevar varios minutos');
		   const update_now = true;
			$.post(`${global_data.url}/admin-updated.php`, { update_now }, response => {
				mydialog.title('Actualizaci&oacute;n:');
		   	mydialog.body(response);
		   	mydialog.buttons(true, true, 'Aceptar', 'close', true, true, false);
		   	mydialog.center();
		   	mydialog.procesando_fin();
		   });
      }
	},
	// AFILIADOS
	afs: {
	   borrar: (afid, gew) => {
         if(!gew){
         	sameModal('Borrar Afiliado', '&#191;Quiere borrar este afiliado?', `admin.afs.borrar(${afid}, 1)`)
	      } else sameFn('afiliado-borrar', { afid }, `#few_${afid}`);
   	},
   	accion: (aid) => {
   		$('#loading').fadeIn(250);
   		$.post(global_data.url +'/afiliado-setactive.php', { aid }, h => {
   			let number = parseInt(h.charAt(0));
				if(number === 0) mydialog.alert('Error', h.substring(3));
				let color = (number === 1) ? 'green' : 'purple';
				let text = (number === 1) ? 'A' : 'Ina';
				$('#status_afiliado_' + aid).html(`<font color="${color}">${text}ctivo</font>`);
		      $('#loading').fadeOut(250);
			});
		}, 
	},
	// NOTICIAS
	news: {
 		accion: nid => {
		   $('#loading').fadeIn(250);
		   $.post(global_data.url +'/admin-noticias-setInActive.php', { nid }, h => {
   			let number = parseInt(h.charAt(0));
				if(number === 0) mydialog.alert('Error', h.substring(3));
				let color = (number === 1) ? 'green' : 'purple';
				let text = (number === 1) ? 'A' : 'Ina';
				$('#status_noticia_' + aid).html(`<font color="${color}">${text}ctiva</font>`);
		      $('#loading').fadeOut(250);
		   })
		}
	},
	// NICKS
	nicks: {
	  	accion: (nid, accion, gew) => {
	    	if(!gew){
	    		apd = (accion == 'aprobar') ? 'Aprobar' : 'Denegar';
         	sameModal(apd + ' Cambio', '&#191;Quiere ' + apd.toLowerCase() + ' el cambio?', `admin.nicks.accion(${nid}, '${accion}', true)`);
	      } else sameFn('admin-nicks-change', { nid, accion }, `#nick_${nid}`);
	  	}
	},
	// SESIONES
	sesiones: {
	   borrar: (sid, gew) => {
         if(!gew){
         	sameModal('Cerrar sesi&oacute;n', '&#191;Quiere cerrar la sesi&oacute;n de este usuario/visitante? Se borrar&aacute; la sesi&oacute;n', `admin.sesiones.borrar(${sid}, true)`);
        	} else sameFn('posts-sesiones-borrar', `sesion_id=${sid}`, `#sesion_${sid}`);
      }
	},
	// TODOS LOS POSTS
	posts: {
	   borrar: (postid, gew) => {
         if(!gew){
         	sameModal('Borrar Post', '&#191;Quiere borrar este post permanentemente?', `admin.posts.borrar(${postid}, 1)`);		
        	} else sameFn('posts-admin-borrar', { postid }, `#post_${postid}`);
      }
	},
	// LISTA NEGRA
	blacklist: {
	   borrar: (bid, gew) => {
         if(!gew) {
         	sameModal('Retirar Bloqueo', '&#191;Quiere retirar este bloqueo?', `admin.blacklist.borrar(${bid}, true)`);
        	} else sameFn('admin-blacklist-delete', { bid }, `#block_${bid}`)
   	}
	},
	// CENSURAS
	badwords: {
	   borrar: (wid, gew) => {
         if(!gew){
         	sameModal('Retirar Filtro', '&#191;Quiere retirar este filtro?', `admin.badwords.borrar(${wid}, true)`);
         } else sameFn('admin-badwords-delete', { wid }, `#wid_${wid}`)
	   }
	},
	// TODAS LAS FOTOS
	fotos: {
	   borrar: (foto_id, gew) => {
         if(!gew){
         	sameModal('Borrar Foto', '&#191;Quiere borrar esta foto permanentemente?', `admin.badwords.borrar(${foto_id}, true)`);
         } else sameFn('admin-foto-borrar', { foto_id }, `#foto_${foto_id}`)
	   },
	   // Cerramos o Abrimos los comentario en foto
	   setOpenClosed: (fid) => {
	   	$('#loading').fadeIn(250);
         $.post(global_data.url +'/admin-foto-setOpenClosed.php', { fid }, h => {
         	let number = parseInt(h.charAt(0));
         	if(number === 0) mydialog.alert('Error', h.substring(3));
         	let color = number ? 'red' : 'green';
         	let text = number ? 'Cerrados' : 'Abiertos';
         	$('#comments_foto_' + fid).html(`<font color="${color}">${text}</font>`);
         	$('#loading').fadeOut(350);
         });
      },
      // Ocultamos | Mostramos la foto
      setShowHide: (fid) => {
         $('#loading').fadeIn(250);
         $.post(global_data.url +'/admin-foto-setShowHide.php', { fid }, h => {
         	let number = parseInt(h.charAt(0));
         	if(number === 0) mydialog.alert('Error', h.substring(3));
         	let color = number ? 'purple' : 'green';
         	let text = number ? 'Oculta' : 'Visible';
         	$('#status_foto_' + fid).html(`<font color="${color}">${text}</font>`);
         	$('#loading').fadeOut(350);
         });
      }
	},
	// TODAS LAS MEDALLAS
	medallas : {
	   borrar: (medal_id, gew) => {
	   	if(!gew) {
	   		sameModal('Borrar Medalla', '&#191;Quiere borrar esta medalla?', `admin.medallas.borrar(${medal_id}, 2)`);
		  	} else if(gew == '2') {
	   		sameModal('Borrar Medalla', 'Si borra la medalla, los usuarios que tengan esta medalla la perder&aacute;n, &#191;seguro que quiere continuar?', `admin.medallas.borrar(${medal_id}, 3)`);
	   	} else sameFn('admin-medalla-borrar', { medal_id }, `#medal_id_${medal_id}`)
   	},   
   	borrar_asignacion: (aid, medal_id, gew) => {
         if(!gew) {
	   		sameModal('Borrar Asignacion', '&#191;Quiere continuar borrando esta asignaci&oacute;n?', `admin.medallas.borrar_asignacion(${aid}, ${medal_id}, true)`);
       	} else sameFn('admin-medallas-borrar-asignacion', { aid, medal_id }, `#assign_id_${medal_id}`)
      },
	   asignar: (medal_id, gew) => {
	   	if(!gew){
	   		var form = `<div id="AFormInputs">
	   			<div class="form-line">
	   				<label for="m_usuario">Al usuario (nombre):</label>
	   				<input name="m_usuario" id="m_usuario"/><br />
	   				<label for="m_post">Al post (id):</label>
	   				<input name="m_post" id="m_post"/><br />
	   				<label for="m_foto">A la foto (id):</label>
	   				<input name="m_foto" id="m_foto"/>
	   			</div>
	   		</div>`;
	   		sameModal('Asignar medalla', form, `admin.medallas.asignar(${medal_id}, true)`);
		 	} else {
				$('#loading').fadeIn(250);
				var params = [
					'mid=' + medal_id,
					'm_usuario=' + $('#m_usuario').val(),
					'pid=' + $('#m_post').val(),
					'fid=' + $('#m_foto').val()
				].join('&');
				$.post(global_data.url + '/admin-medalla-asignar.php', params, c => {
					mydialog.alert((c.charAt(0) == '0' ? 'Opps!' : 'Hecho'), c.substring(3), false);
			   	if(c.charAt(0) != '0') {
						var nmeds = parseInt($('#total_med_assig_' + medal_id).text());
						$('#total_med_assig_' + medal_id).text(nmeds + 1);
	               $('#loading').fadeOut(350);
					}
					mydialog.center();
				});
			}
	   }
   },
   // TODOS LOS USUARIOS
   users: {
		setInActive: (uid) => {
			$('#loading').fadeIn(250);
			$.post(global_data.url +'/admin-users-InActivo.php', { uid }, h => {
   			let number = parseInt(h.charAt(0));
				if(number === 0) mydialog.alert('Error', h.substring(3));
				let color = (number === 1) ? 'green' : 'purple';
				let text = (number === 1) ? 'A' : 'Ina';
				$('#status_user_' + uid).html(`<font color="${color}">${text}ctivo</font>`);
		      $('#loading').fadeOut(250);
		      $('#loading').fadeOut(350);
			});
		}
   }
}

/* AFILIADOS */
var ad_afiliado = {
   cache: {},
   detalles: (aid) => {
   	$.post(global_data.url + '/afiliado-detalles.php', 'ref=' + aid, response => {
   		mydialog.faster({
   			title: 'Detalles del Afiliado',
   			body: response,
   			buttons: {
   				ok: { text: 'Aceptar', action: 'close' }
   			}
   		})
   	}); 
   }
}