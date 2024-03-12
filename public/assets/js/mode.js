const toggleMode = (() => {
	'use strict';
	// Iconos
	const icon = {
		dark: '<iconify-icon icon="solar:moon-fog-bold-duotone"></iconify-icon>',
		light: '<iconify-icon icon="solar:sun-fog-bold-duotone"></iconify-icon>'
	}
	//
	const selected = localStorage.getItem('modo');
	const userHasDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
	const getCurrent = () => $('html').attr('data-bs-theme') !== 'dark' ? 'dark' : 'light';
	// bsTheme
	function bsTheme(changeColor) {
	  	localStorage.setItem('modo', changeColor)
		$('html').attr('data-bs-theme', changeColor)
		actualizarIcono()
	}
	//
	if (!empty(selected)) $('html').attr({ 'data-bs-theme': selected })
	else {
		if (userHasDark) $('html').attr('data-bs-theme', 'dark')
	}
	$('#mode_change').on('click', () => bsTheme(getCurrent()))
	function actualizarIcono() {
		let icono = (getCurrent() === 'light') ? icon.light : icon.dark;
	   $('#mode_change').html(icono)
	}
	document.onkeydown = tecla => {
		// ----------- SHIFT + D -----------
		if (tecla.shiftKey) {
		   if (tecla.keyCode == 68 || event.keyCode == 100) bsTheme(getCurrent())
		}
	}
	return {
    	actualizarIcono: actualizarIcono
  	};
})();

$(document).ready(() => toggleMode.actualizarIcono());