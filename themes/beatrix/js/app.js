(function($) {
	"use strict";

	$(".tp-menu-bar").on("click", function () {
		$(".tpoffcanvas").addClass("opened");
		$(".body-overlay").addClass("apply");
	});
	$(".close-btn").on("click", function () {
		$(".tpoffcanvas").removeClass("opened");
		$(".body-overlay").removeClass("apply");
	});
	$(".body-overlay").on("click", function () {
		$(".tpoffcanvas").removeClass("opened");
		$(".body-overlay").removeClass("apply");
	});

	$(function () {
		const boxInner = '#theme-serach-box_Inner';
		$(boxInner).removeClass('toggled');
		$('.theme-search-custom-iconn').on('click', e => {
			e.stopPropagation();
			$(boxInner).toggleClass('toggled');
			$("#popup-search").focus();
		});
		$(boxInner + ' input').on('click', e => e.stopPropagation());
		$(boxInner + ', body').on('click', () => $(boxInner).removeClass('toggled'));
	});
	
	$(window).ready(() => $("#preloader").fadeOut());

	 /*----------------------------------------
		  Scroll to top
	 ----------------------------------------*/

	 function BackToTop() {
		$('.scrolltotop').on('click', () => {
			$('html, body').animate({scrollTop: 0}, 800);
			return false;
	 });

	 $(document).on('scroll', () => {
		var y = $(document).scrollTop();
		if (y > 600) $('.scrolltotop').fadeIn();
		else $('.scrolltotop').fadeOut();
	});
}
BackToTop();

var siteMenuClone = function () {
	$('.theme-navigation-wrap').each(function () {
		var $this = $(this);
		$this.clone().attr('class', 'site-nav-wrap').appendTo('.canvas-nav-menu-wrapper');
	});
	setTimeout(function () {
		var counter = 0;
		$('.mobile-canvas-content .has-children').each(function () {
			var $this = $(this);
			$this.prepend('<span class="arrow-collapse collapsed">');
			$this.find('.arrow-collapse').attr({
				'data-bs-toggle': 'collapse',
				'data-bs-target': '#collapseItem' + counter,
			});
			$this.find('> ul').attr({
				'class': 'collapse',
				'id': 'collapseItem' + counter,
			});
			counter++;
		});
	}, 1000);
};
siteMenuClone();

})(jQuery);