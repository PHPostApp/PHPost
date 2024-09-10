/**
 * Plugins globales que utilizará el script.
 * Los plugins: (fueron obtenidos desde https://locutus.io/php/)
 *  # Empty 
 *  # Htmlspecialchars_decode 
 *  # Number_format 
 *  # Base64_encode
*/
empty = n => {let e,r,t;const f=[undefined,null,!1,0,"","0"];for(r=0,t=f.length;r<t;r++)if(n===f[r])return!0;if("object"==typeof n){for(e in n)if(n.hasOwnProperty(e))return!1;return!0}return!1}
htmlspecialchars_decode = (e,E) => {let T=0,_=0,t=!1;void 0===E&&(E=2),e=e.toString().replace(/&lt;/g,"<").replace(/&gt;/g,">");const c={ENT_NOQUOTES:0,ENT_HTML_QUOTE_SINGLE:1,ENT_HTML_QUOTE_DOUBLE:2,ENT_COMPAT:2,ENT_QUOTES:3,ENT_IGNORE:4};if(0===E&&(t=!0),"number"!=typeof E){for(E=[].concat(E),_=0;_<E.length;_++)0===c[E[_]]?t=!0:c[E[_]]&&(T|=c[E[_]]);E=T}return E&c.ENT_HTML_QUOTE_SINGLE&&(e=e.replace(/&#0*39;/g,"'")),t||(e=e.replace(/&quot;/g,'"')),e=e.replace(/&amp;/g,"&")}
number_format = (e,t,n,i) => {e=(e+"").replace(/[^0-9+\-Ee.]/g,"");const r=isFinite(+e)?+e:0,o=isFinite(+t)?Math.abs(t):0,a=void 0===i?",":i,d=void 0===n?".":n;let l="";return l=(o?function(e,t){if(-1===(""+e).indexOf("e"))return+(Math.round(e+"e+"+t)+"e-"+t);{const n=(""+e).split("e");let i="";return+n[1]+t>0&&(i="+"),(+(Math.round(+n[0]+"e"+i+(+n[1]+t))+"e-"+t)).toFixed(t)}}(r,o).toString():""+Math.round(r)).split("."),l[0].length>3&&(l[0]=l[0].replace(/\B(?=(?:\d{3})+(?!\d))/g,a)),(l[1]||"").length<o&&(l[1]=l[1]||"",l[1]+=new Array(o-l[1].length+1).join("0")),l.join(d)}
base64_encode = a => {const b=function(a){return encodeURIComponent(a).replace(/%([0-9A-F]{2})/g,function(a,b){return String.fromCharCode("0x"+b)})};if(!("undefined"!=typeof window))return new Buffer(a).toString("base64");else if("undefined"!=typeof window.btoa)return window.btoa(b(a));const c="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";let d,e,f,g,h,j,k,l,m=0,n=0,o="";const p=[];if(!a)return a;a=b(a);do d=a.charCodeAt(m++),e=a.charCodeAt(m++),f=a.charCodeAt(m++),l=d<<16|e<<8|f,g=63&l>>18,h=63&l>>12,j=63&l>>6,k=63&l,p[n++]=c.charAt(g)+c.charAt(h)+c.charAt(j)+c.charAt(k);while(m<a.length);o=p.join("");const q=a.length%3;return(q?o.slice(0,q-3):o)+"===".slice(q||3)}
rawurlencode = str => encodeURIComponent(str).replace(/!/g,'%21').replace(/'/g,'%27').replace(/\(/g,'%28').replace(/\)/g,'%29').replace(/\*/g,'%2A')

/* scrollTo 1.4.2 by Ariel Flesler */
;(function(d){var k=d.scrollTo=function(a,i,e){d(window).scrollTo(a,i,e)};k.defaults={axis:'xy',duration:parseFloat(d.fn.jquery)>=1.3?0:1};k.window=function(a){return d(window)._scrollable()};d.fn._scrollable=function(){return this.map(function(){var a=this,i=!a.nodeName||d.inArray(a.nodeName.toLowerCase(),['iframe','#document','html','body'])!=-1;if(!i)return a;var e=(a.contentWindow||a).document||a.ownerDocument||a;return d.browser.safari||e.compatMode=='BackCompat'?e.body:e.documentElement})};d.fn.scrollTo=function(n,j,b){if(typeof j=='object'){b=j;j=0}if(typeof b=='function')b={onAfter:b};if(n=='max')n=9e9;b=d.extend({},k.defaults,b);j=j||b.speed||b.duration;b.queue=b.queue&&b.axis.length>1;if(b.queue)j/=2;b.offset=p(b.offset);b.over=p(b.over);return this._scrollable().each(function(){var q=this,r=d(q),f=n,s,g={},u=r.is('html,body');switch(typeof f){case'number':case'string':if(/^([+-]=)?\d+(\.\d+)?(px|%)?$/.test(f)){f=p(f);break}f=d(f,this);case'object':if(f.is||f.style)s=(f=d(f)).offset()}d.each(b.axis.split(''),function(a,i){var e=i=='x'?'Left':'Top',h=e.toLowerCase(),c='scroll'+e,l=q[c],m=k.max(q,i);if(s){g[c]=s[h]+(u?0:l-r.offset()[h]);if(b.margin){g[c]-=parseInt(f.css('margin'+e))||0;g[c]-=parseInt(f.css('border'+e+'Width'))||0}g[c]+=b.offset[h]||0;if(b.over[h])g[c]+=f[i=='x'?'width':'height']()*b.over[h]}else{var o=f[h];g[c]=o.slice&&o.slice(-1)=='%'?parseFloat(o)/100*m:o}if(/^\d+$/.test(g[c]))g[c]=g[c]<=0?0:Math.min(g[c],m);if(!a&&b.queue){if(l!=g[c])t(b.onAfterFirst);delete g[c]}});t(b.onAfter);function t(a){r.animate(g,j,b.easing,a&&function(){a.call(this,n,b)})}}).end()};k.max=function(a,i){var e=i=='x'?'Width':'Height',h='scroll'+e;if(!d(a).is('html,body'))return a[h]-d(a)[e.toLowerCase()]();var c='client'+e,l=a.ownerDocument.documentElement,m=a.ownerDocument.body;return Math.max(l[h],m[h])-Math.min(l[c],m[c])};function p(a){return typeof a=='object'?a:{top:a,left:a}}})(jQuery);

/* Easing 1.3 */
jQuery.extend( jQuery.easing,{def: 'easeOutQuad',swing: function (x, t, b, c, d) {return jQuery.easing[jQuery.easing.def](x, t, b, c, d);},easeInQuad: function (x, t, b, c, d) {return c*(t/=d)*t + b;},easeOutQuad: function (x, t, b, c, d) {return -c *(t/=d)*(t-2) + b;},easeInOutQuad: function (x, t, b, c, d) {if ((t/=d/2) < 1) return c/2*t*t + b;return -c/2 * ((--t)*(t-2) - 1) + b;},easeInCubic: function (x, t, b, c, d) {return c*(t/=d)*t*t + b;},easeOutCubic: function (x, t, b, c, d) {return c*((t=t/d-1)*t*t + 1) + b;},easeInOutCubic: function (x, t, b, c, d) {if ((t/=d/2) < 1) return c/2*t*t*t + b;return c/2*((t-=2)*t*t + 2) + b;},easeInQuart: function (x, t, b, c, d) {return c*(t/=d)*t*t*t + b;},easeOutQuart: function (x, t, b, c, d) {return -c * ((t=t/d-1)*t*t*t - 1) + b;},easeInOutQuart: function (x, t, b, c, d) {if ((t/=d/2) < 1) return c/2*t*t*t*t + b;return -c/2 * ((t-=2)*t*t*t - 2) + b;},easeInQuint: function (x, t, b, c, d) {return c*(t/=d)*t*t*t*t + b;},easeOutQuint: function (x, t, b, c, d) {return c*((t=t/d-1)*t*t*t*t + 1) + b;},easeInOutQuint: function (x, t, b, c, d) {if ((t/=d/2) < 1) return c/2*t*t*t*t*t + b;return c/2*((t-=2)*t*t*t*t + 2) + b;},easeInSine: function (x, t, b, c, d) {return -c * Math.cos(t/d * (Math.PI/2)) + c + b;},easeOutSine: function (x, t, b, c, d) {return c * Math.sin(t/d * (Math.PI/2)) + b;},easeInOutSine: function (x, t, b, c, d) {return -c/2 * (Math.cos(Math.PI*t/d) - 1) + b;},easeInExpo: function (x, t, b, c, d) {return (t==0) ? b : c * Math.pow(2, 10 * (t/d - 1)) + b;},easeOutExpo: function (x, t, b, c, d) {return (t==d) ? b+c : c * (-Math.pow(2, -10 * t/d) + 1) + b;},easeInOutExpo: function (x, t, b, c, d) {if (t==0) return b;if (t==d) return b+c;if ((t/=d/2) < 1) return c/2 * Math.pow(2, 10 * (t - 1)) + b;return c/2 * (-Math.pow(2, -10 * --t) + 2) + b;},easeInCirc: function (x, t, b, c, d) {return -c * (Math.sqrt(1 - (t/=d)*t) - 1) + b;},easeOutCirc: function (x, t, b, c, d) {return c * Math.sqrt(1 - (t=t/d-1)*t) + b;},easeInOutCirc: function (x, t, b, c, d) {if ((t/=d/2) < 1) return -c/2 * (Math.sqrt(1 - t*t) - 1) + b;return c/2 * (Math.sqrt(1 - (t-=2)*t) + 1) + b;},easeInElastic: function (x, t, b, c, d) {var s=1.70158;var p=0;var a=c;if (t==0) return b;  if ((t/=d)==1) return b+c;  if (!p) p=d*.3;if (a < Math.abs(c)) { a=c; var s=p/4; }else var s = p/(2*Math.PI) * Math.asin (c/a);return -(a*Math.pow(2,10*(t-=1)) * Math.sin( (t*d-s)*(2*Math.PI)/p )) + b;},easeOutElastic: function (x, t, b, c, d) {var s=1.70158;var p=0;var a=c;if (t==0) return b;  if ((t/=d)==1) return b+c;  if (!p) p=d*.3;if (a < Math.abs(c)) { a=c; var s=p/4; }else var s = p/(2*Math.PI) * Math.asin (c/a);return a*Math.pow(2,-10*t) * Math.sin( (t*d-s)*(2*Math.PI)/p ) + c + b;},easeInOutElastic: function (x, t, b, c, d) {var s=1.70158;var p=0;var a=c;if (t==0) return b;  if ((t/=d/2)==2) return b+c;  if (!p) p=d*(.3*1.5);if (a < Math.abs(c)) { a=c; var s=p/4; }else var s = p/(2*Math.PI) * Math.asin (c/a);if (t < 1) return -.5*(a*Math.pow(2,10*(t-=1)) * Math.sin( (t*d-s)*(2*Math.PI)/p )) + b;return a*Math.pow(2,-10*(t-=1)) * Math.sin( (t*d-s)*(2*Math.PI)/p )*.5 + c + b;},easeInBack: function (x, t, b, c, d, s) {if (s == undefined) s = 1.70158;return c*(t/=d)*t*((s+1)*t - s) + b;},easeOutBack: function (x, t, b, c, d, s) {if (s == undefined) s = 1.70158;return c*((t=t/d-1)*t*((s+1)*t + s) + 1) + b;},easeInOutBack: function (x, t, b, c, d, s) {if (s == undefined) s = 1.70158; if ((t/=d/2) < 1) return c/2*(t*t*(((s*=(1.525))+1)*t - s)) + b;return c/2*((t-=2)*t*(((s*=(1.525))+1)*t + s) + 2) + b;},easeInBounce: function (x, t, b, c, d) {return c - jQuery.easing.easeOutBounce (x, d-t, 0, c, d) + b;},easeOutBounce: function (x, t, b, c, d) {if ((t/=d) < (1/2.75)) {return c*(7.5625*t*t) + b;} else if (t < (2/2.75)) {return c*(7.5625*(t-=(1.5/2.75))*t + .75) + b;} else if (t < (2.5/2.75)) {return c*(7.5625*(t-=(2.25/2.75))*t + .9375) + b;} else {return c*(7.5625*(t-=(2.625/2.75))*t + .984375) + b;}},easeInOutBounce: function (x, t, b, c, d) {if (t < d/2) return jQuery.easing.easeInBounce (x, t*2, 0, c, d) * .5 + b;return jQuery.easing.easeOutBounce (x, t*2-d, 0, c, d) * .5 + c*.5 + b;}});


isYoutube = linkVideo =>{ 
	var regExp = /^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#\&\?]*).*/; 
	var match = linkVideo.match(regExp); 
	return (match && match[7].length === 11) ? match[7] : false;
}
/**
 * A lightweight youtube embed. Still should feel the same to the user, just MUCH faster to initialize and paint.
 *
 * Thx to these as the inspiration
 *   https://storage.googleapis.com/amp-vs-non-amp/youtube-lazy.html
 *   https://autoplay-youtube-player.glitch.me/
 *
 * Once built it, I also found these:
 *   https://github.com/ampproject/amphtml/blob/master/extensions/amp-youtube (👍👍)
 *   https://github.com/Daugilas/lazyYT
 *   https://github.com/vb/lazyframe
*/
class LiteYTEmbed extends HTMLElement {
   connectedCallback() {
      this.videoId = this.getAttribute('videoid');
      let playBtnEl = this.querySelector('.lty-playbtn');
      // A label for the button takes priority over a [playlabel] attribute on the custom-element
      this.playLabel = (playBtnEl && playBtnEl.textContent.trim()) || this.getAttribute('playlabel') || 'Play';
      if (!this.style.backgroundImage) {
        this.style.backgroundImage = `url("https://i.ytimg.com/vi/${this.videoId}/maxresdefault.jpg")`;
      }
      if (!playBtnEl) {
         playBtnEl = document.createElement('button');
         playBtnEl.type = 'button';
         playBtnEl.classList.add('lty-playbtn');
         this.append(playBtnEl);
      }
      if (!playBtnEl.textContent) {
         const playBtnLabelEl = document.createElement('span');
         playBtnLabelEl.className = 'lyt-visually-hidden';
         playBtnLabelEl.textContent = this.playLabel;
         playBtnEl.append(playBtnLabelEl);
      }
      this.addEventListener('pointerover', LiteYTEmbed.warmConnections, {once: true});
      this.addEventListener('click', this.addIframe);
  	}
  	static addPrefetch(kind, url, as) {
      const linkEl = document.createElement('link');
      linkEl.rel = kind;
      linkEl.href = url;
      if (as) linkEl.as = as;
      document.head.append(linkEl);
   }

   static warmConnections() {
      if (LiteYTEmbed.preconnected) return;
      LiteYTEmbed.addPrefetch('preconnect', 'https://www.youtube-nocookie.com');
      //LiteYTEmbed.addPrefetch('preconnect', 'https://www.google.com');
      LiteYTEmbed.addPrefetch('preconnect', 'https://googleads.g.doubleclick.net');
      LiteYTEmbed.addPrefetch('preconnect', 'https://static.doubleclick.net');
      LiteYTEmbed.preconnected = true;
   }
   addIframe(e) {
      if (this.classList.contains('lyt-activated')) return;
      e.preventDefault();
      this.classList.add('lyt-activated');
      //
      const params = new URLSearchParams(this.getAttribute('params') || []);
      params.append('autoplay', '1');
      //
      const iframeEl = document.createElement('iframe');
      iframeEl.width = 560 - 60;
      iframeEl.height = 315 - 60;
      // No encoding necessary as [title] is safe. https://cheatsheetseries.owasp.org/cheatsheets/Cross_Site_Scripting_Prevention_Cheat_Sheet.html#:~:text=Safe%20HTML%20Attributes%20include
      iframeEl.title = this.playLabel;
      iframeEl.allow = 'accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture';
      iframeEl.allowFullscreen = true;
      // https://stackoverflow.com/q/64959723/89484
      iframeEl.src = `https://www.youtube-nocookie.com/embed/${encodeURIComponent(this.videoId)}?${params.toString()}`;
      this.append(iframeEl);

      // Set focus for a11y
      iframeEl.focus();
   }
}
// Register custom element
customElements.define('lite-youtube', LiteYTEmbed);

/**
 * MyDialog v2
 * @autor Miguel92
*/
var mydialog = {
	theme: 'default',
	is_show: false,
	class_aux: '',
	size: 'normal', // small | normal | big
	mask_close: true,
	close_button: false,
	template: `<div id="dialog">
		<div id="header_dialog">
			<div id="title"></div>
			<div id="close"></div>
		</div>
		<div id="cuerpo">
			<div id="procesando">
				<div id="mensaje"></div>
			</div>
			<div id="modalBody"></div>
		</div>
		<div id="buttons"></div>
	</div>`,
	show: function(activeClass){
		if(this.is_show) return;
		else this.is_show = true;
		if($('#mydialog').html() == '') $('#mydialog').html(this.template);
		$('#mydialog').before('<div id="mask"></div>')
		// Para los tamaños del modal
		$('#mydialog').addClass(this.size);
		// Añadimos clase auxiliar si existe
		if(activeClass) $('#mydialog').addClass(this.class_aux);
		else if(this.class_aux != ''){
			$('#mydialog').removeClass(this.class_aux);
			this.class_aux = '';
		}
		// Cerramos modal con la mascará
		if(this.mask_close) $('#mask').on('click', () => mydialog.close());
		// Añadimos el botón para cerrar el modal
		if(this.close_button)
			$('#mydialog #dialog #close').html('<span onclick="mydialog.close()" class="close_dialog">&times;</span>');

		$('#mydialog #dialog').css({ position: 'absolute' }).fadeIn('fast');
		$(window).on('resize', mydialog.center);
	},
	close: function(){
		//Vuelve todos los parametros por default
		this.class_aux = '';
		this.mask_close = true;
		this.close_button = false;
		this.size = 'normal';

		this.is_show = false;
		$('#mask').remove();
		$('#mydialog #dialog').fadeOut('fast', () => $(this).remove());
		this.procesando_fin();
	},
	center: function() {
      var modal = $('#mydialog #dialog');
      modal.css({
         'position': 'fixed',
         'top': '50%',
         'left': '50%',
         'transform': 'translate(-50%, -50%)'
      });
	},
	title: title => $('#mydialog #title').html(title),
	body: body => $('#mydialog #cuerpo #modalBody').html(body),
	buttons: (...args) => {
		if(args.length === 1) {
			$('#mydialog #buttons').hide();
			return;
		}
		const obj = {
			ok:{action:args[3], text:args[2], active:args[1], focus:args[5]},
			fail:{action:args[8], text:args[7], active:args[6], focus:args[10]} 
		};
		if(args.length <= 7) delete obj.fail;
		mydialog.buttons_action(args[0], obj);
	},
	buttons_theme: () => {
		const buttonsTheme = {
			default: {
				done: 'mBtn btnOk',
				fail: 'mBtn btnCancel'
			}, 
			cerberus: {
				done: 'button button-ok',
				fail: 'button button-error'
			}, 
			bootstrap: {
				done: 'btn btn-primary',
				fail: 'btn btn-danger'
			}
		}
		return buttonsTheme[mydialog.theme]
	},
	buttons_action: (remBtn, dataObject) => {	
		let is_html = '';	
		let { done, fail } = mydialog.buttons_theme();
		if(!dataObject.ok && !dataObject.fail && remBtn) $('#mydialog #buttons').hide();

		// Si existe "OK"
		if(dataObject.ok) {
			// Si tiene accion definido
			if(dataObject.ok.action === 'close' || !dataObject.ok.action) dataObject.ok.action = 'mydialog.close()';
			let classdisabled = dataObject.ok.active ? '' : ' disabled';
			is_html += `<input type="button" class="${done}${classdisabled}" style="display:inline-block!important;" onclick="${dataObject.ok.action}" value="${dataObject.ok.text}"${classdisabled} />`;
		}
		// Si existe "fail"
		if(dataObject.fail) {
			// Si tiene accion definido
			if(dataObject.fail.action === 'close' || !dataObject.fail.action) dataObject.ok.action = 'mydialog.close()';
			let classdisabled = dataObject.fail.active ? '' : ' disabled';
			is_html += `<input type="button" class="${fail}${classdisabled}" style="display:inline-block!important;" onclick="${dataObject.ok.action}" value="${dataObject.fail.text}"${classdisabled} />`;
		}
		// Por que si se ejecuta 2 veces y el 1ro tiene mydialog.buttons(false)
		// El 2do ya no se visualizará ya que no existe en el DOM #buttons
		$('#mydialog #buttons').show().html(is_html)
		
		if(!dataObject.ok && !dataObject.fail) {
			done = '.' + done.replace('.', ' ');
			fail = '.' + fail.replace('.', ' ');
			if(dataObject.ok.focus) $(`#mydialog #buttons ${done}`).focus();
			else if(dataObject.fail.focus) $(`#mydialog #buttons ${fail}`).focus();
		}
	},
	alert: function(title, body, reload){
		this.show();
		this.title(title);
		this.body(body);
		this.buttons(true, true, 'Aceptar', 'mydialog.close();' + (reload ? 'location.reload();' : 'close'), true, true, false);
		this.center();
	},
	error_500: function(fun_reintentar){
		setTimeout(function(){
			mydialog.procesando_fin();
			mydialog.show();
			mydialog.title('Error');
			mydialog.body('Error al intentar procesar lo solicitado');
			mydialog.buttons(true, true, 'Reintentar', 'mydialog.close();'+fun_reintentar, true, true, true, 'Cancelar', 'close', true, false);
			mydialog.center();
		}, 200);
	},
	procesando_inicio: function(value, title){
		if(!this.is_show){
			this.show();
			this.title(title);
			this.body('');
			this.buttons(false, false);
			this.center();
		}
		$('#mydialog #procesando #mensaje').html('<img src="'+global_data.tema_images+'/loading_bar.gif" />');
		$('#mydialog #procesando').fadeIn('fast');
	},
	procesando_fin: function(){
		$('#mydialog #procesando').fadeOut('fast');
	},
	faster: obj => {
		const { class_aux, addClass, close_button, mask_button, size, title, body, buttons } = obj
		if(!empty(class_aux)) mydialog.class_aux = addClass;
		mydialog.close_button = (close_button);
		mydialog.mask_button = (mask_button);
		mydialog.size = (size) ? size : 'normal'; // small | normal | big
		mydialog.show(true);
		mydialog.title(title);
		mydialog.body(body);
		if(typeof buttons === 'boolean') {
			mydialog.buttons(false)
		} else if(buttons.fail !== undefined) {
			mydialog.buttons(true, true, buttons.ok.text, buttons.ok.action, true, true, true, buttons.fail.text, buttons.fail.action, true, false);
		} else {
			mydialog.buttons(true, true, buttons.ok.text, buttons.ok.action, true, true, false);
		}
		mydialog.center();
	}

};

document.onkeydown = e => {
	if(e.keyCode === 27 || e.which === 27) mydialog.close();
};

const verifyInput = (selector, errorMessage) => {
   const input = $(selector);
   const helpText = input.next('.help');

   if (input.val().trim() === '') {
      input.parent().addClass('was-error');
      helpText.text(errorMessage);
      input.focus();
      return false;
   } else {
      input.parent().removeClass('was-error');
      helpText.text('');
      return true;
   }
};

$('input').on('keyup', function() {
	$(this).parent().removeClass('was-error').find('.help').html('')
});

$(() => {
	// Ejecutamos LazyLoad - by Miguel92
   let LazyLoadClass = ['img[data-src]', '[data-bg]', '.iframe']
   LazyLoadClass.map( lazyload => {
   	let NewOptions = {
         elements_selector: lazyload,
         use_native: true,
         class_loading: 'lazy-loading',
         callback_error: callback => {
			   callback.setAttribute("srcset", global_data.public_images + "/500-error.png");
			   callback.setAttribute("src", global_data.public_images + "/500-error.png");
			}
      }
      if(lazyload === '[data-bg]') {
         // Agregamos
         NewOptions = Object.assign(NewOptions, {class_loaded: 'lazy-loaded'})
         // Quitamos -> use_native: true
         delete NewOptions.use_native
      }
      new LazyLoad(NewOptions)
   });
})