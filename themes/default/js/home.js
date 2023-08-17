function actualizar_comentarios(cat, nov){
   $('#loading').fadeIn(250);
	$('#ult_comm, #ult_comm > ol').slideUp(150);
	$.get(global_data.url + '/posts-last-comentarios.php', { cat, nov }, h => {
		$('#ult_comm').html(h);
		$('#ult_comm > ol').hide();
		$('#ult_comm, #ult_comm > ol:first').slideDown( 1500, 'easeInOutElastic');
      $('#loading').fadeOut(350);
	}).fail(() => {
		$('#ult_comm, #ult_comm > ol:first').slideDown({duration: 1000, easing: 'easeOutBounce'});
      $('#loading').fadeOut(350);
	});
}
function TopsTabs(parent, tab) {
		if($('.box_cuerpo ol.filterBy#filterBy'+tab).css('display') == 'block') return;
		$('#'+parent+' > .box_cuerpo div.filterBy a').removeClass('here');
		$('.box_cuerpo div.filterBy a#'+tab).addClass('here');
		$('#'+parent+' > .box_cuerpo ol').fadeOut();
		$('#'+parent+' > .box_cuerpo ol#filterBy'+tab).fadeIn();
}
$(document).ready(() => {
	$('div.new-search > div.bar-options > ul > li > a').on('click', () => {
		var at = $(this).parent('li').attr('class').split('-')[0];
		$('div.new-search > div.bar-options > ul > li.selected').removeClass('selected');
		$(this).parent('li').addClass('selected');
		$('div.new-search').attr('class', 'new-search '+at);
      at = (at == 'web') ? 'google' : 'web';
      $('input[name="e"]').val(at);
      // GOOGLE ID
      var gid = $('form[name="search"]').attr('gid');
      //Muestro/oculto los input google
		if(at == 'google'){ 
			$('form[name="search"]').append('<input type="hidden" name="cx" value="' + gid + '" /><input type="hidden" name="cof" value="FORID:10" /><input type="hidden" name="ie" value="ISO-8859-1" />');
            $('#search-home-cat-filter, #sh_options').hide();
		} else {
			$('input[name="cx"],input[name="cof"],input[name="ie"]').remove();
         $('#search-home-cat-filter, #sh_options').css('display','');
		}
	});
});