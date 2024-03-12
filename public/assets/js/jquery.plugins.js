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

/* Tipsy */
!function(t,e,i){function s(t,e){return"function"==typeof t?t.call(e):t}function o(t){for(;t=t.parentNode;)if(t==document)return!0;return!1}function n(t){return"object"==typeof HTMLElement?t instanceof HTMLElement:t&&"object"==typeof t&&1===t.nodeType&&"string"==typeof t.nodeName}function l(){return"tipsyuid"+h++}function a(e,i){this.$element=t(e),this.options=i,this.enabled=!0,this.fixTitle()}var h=0;a.prototype={show:function(){if(o(this.$element[0])&&(!n(this.$element)||this.$element.is(":visible"))){var e;if(this.enabled&&(e=this.getTitle())){var i=this.tip();i.find(".tipsy-inner"+this.options.theme)[this.options.html?"html":"text"](e),i[0].className="tipsy"+this.options.theme,this.options.className&&i.addClass(s(this.options.className,this.$element[0])),i.remove().css({top:0,left:0,visibility:"hidden",display:"block"}).prependTo(document.body);var a=t.extend({},this.$element.offset());a=this.$element.parents("svg").length>0?t.extend(a,this.$element[0].getBBox()):t.extend(a,{width:this.$element[0].offsetWidth||0,height:this.$element[0].offsetHeight||0});var h,f=i[0].offsetWidth,r=i[0].offsetHeight,p=s(this.options.gravity,this.$element[0]);switch(p.charAt(0)){case"n":h={top:a.top+a.height+this.options.offset,left:a.left+a.width/2-f/2};break;case"s":h={top:a.top-r-this.options.offset,left:a.left+a.width/2-f/2};break;case"e":h={top:a.top+a.height/2-r/2,left:a.left-f-this.options.offset};break;case"w":h={top:a.top+a.height/2-r/2,left:a.left+a.width+this.options.offset}}if(2==p.length&&("w"==p.charAt(1)?h.left=a.left+a.width/2-15:h.left=a.left+a.width/2-f+15),i.css(h).addClass("tipsy-"+p+this.options.theme),i.find(".tipsy-arrow"+this.options.theme)[0].className="tipsy-arrow"+this.options.theme+" tipsy-arrow-"+p.charAt(0)+this.options.theme,this.options.fade?(this.options.shadow&&t(".tipsy-inner").css({"box-shadow":"0px 0px "+this.options.shadowBlur+"px "+this.options.shadowSpread+"px rgba(0, 0, 0, "+this.options.shadowOpacity+")","-webkit-box-shadow":"0px 0px "+this.options.shadowBlur+"px "+this.options.shadowSpread+"px rgba(0, 0, 0, "+this.options.shadowOpacity+")"}),i.stop().css({opacity:0,display:"block",visibility:"visible"}).animate({opacity:this.options.opacity},this.options.fadeInTime)):i.css({visibility:"visible",opacity:this.options.opacity}),this.options.aria){var d=l();i.attr("id",d),this.$element.attr("aria-describedby",d)}}}},hide:function(){this.options.fade?this.tip().stop().fadeOut(this.options.fadeOutTime,function(){t(this).remove()}):this.tip().remove(),this.options.aria&&this.$element.removeAttr("aria-describedby")},fixTitle:function(){var t=this.$element,e=s(this.options.id,this.$element[0]);(t.prop("title")||"string"!=typeof t.prop("original-title"))&&(t.prop("original-title",t.prop("title")||"").removeAttr("title"),t.attr("aria-describedby",e),t.attr("tabindex")===i&&t.attr("tabindex",0))},getTitle:function(){var t,e=this.$element,i=this.options;return this.fixTitle(),"string"==typeof i.title?t=e.prop("title"==i.title?"original-title":i.title):"function"==typeof i.title&&(t=i.title.call(e[0])),t=(""+t).replace(/(^\s*|\s*$)/,""),t||i.fallback},tip:function(){var e=s(this.options.id,this.$element[0]);return this.$tip||(this.$tip=t('<div class="tipsy'+this.options.theme+'" id="'+e+'" role="tooltip"></div>').html('<div class="tipsy-arrow'+this.options.theme+'"></div><div class="tipsy-inner'+this.options.theme+'"></div>').attr("role","tooltip"),this.$tip.data("tipsy-pointee",this.$element[0])),this.$tip},validate:function(){this.$element[0].parentNode||(this.hide(),this.$element=null,this.options=null)},enable:function(){this.enabled=!0},disable:function(){this.enabled=!1},toggleEnabled:function(){this.enabled=!this.enabled}},t.fn.tipsy=function(e){function i(i){var s=t.data(i,"tipsy");return s||(s=new a(i,t.fn.tipsy.elementOptions(i,e)),t.data(i,"tipsy",s)),s}function s(){if(t.fn.tipsy.enabled===!0){var s=i(this);s.hoverState="in",0===e.delayIn?s.show():(s.fixTitle(),setTimeout(function(){"in"==s.hoverState&&o(s.$element)&&s.show()},e.delayIn))}}function n(){var t=i(this);t.hoverState="out",0===e.delayOut?t.hide():setTimeout(function(){"out"!=t.hoverState&&t.$element&&t.$element.is(":visible")||t.hide()},e.delayOut)}if(t.fn.tipsy.enable(),e===!0)return this.data("tipsy");if("string"==typeof e){var l=this.data("tipsy");return l&&l[e](),this}if(e=t.extend({},t.fn.tipsy.defaults,e),e.theme=e.theme&&""!==e.theme?"-"+e.theme:"",e.on||this.each(function(){i(this)}),"manual"!=e.trigger)if(e.on&&e.on!==!0)"focus"!=e.trigger&&(t(this).on("mouseenter",e.on,s),t(this).on("mouseleave",e.on,n)),"blur"!=e.trigger&&(t(this).on("focus",e.on,s),t(this).on("blur",e.on,n));else{if(e.on&&!t.on)throw"Since jQuery 1.9, pass selector as live argument. eg. $(document).tipsy({live: 'a.live'});";var h=e.on?"live":"bind";"focus"!=e.trigger&&this[h]("mouseenter",s)[h]("mouseleave",n),"blur"!=e.trigger&&this[h]("focus",s)[h]("blur",n)}return this},t.fn.tipsy.defaults={aria:!1,className:null,id:"tipsy",delayIn:0,delayOut:0,fade:!1,fadeInTime:400,fadeOutTime:400,shadow:!1,shadowBlur:8,shadowOpacity:1,shadowSpread:0,fallback:"",gravity:"n",html:!1,live:!1,offset:0,opacity:1,title:"title",trigger:"interactive",theme:""},t.fn.tipsy.revalidate=function(){t(".tipsy").each(function(){var e=t.data(this,"tipsy-pointee");e&&o(e)||t(this).remove()})},t.fn.tipsy.enable=function(){t.fn.tipsy.enabled=!0},t.fn.tipsy.disable=function(){t.fn.tipsy.enabled=!1},t.fn.tipsy.elementOptions=function(e,i){return t.metadata?t.extend({},i,t(e).metadata()):i},t.fn.tipsy.autoNS=function(){return t(this).offset().top>t(document).scrollTop()+t(e).height()/2?"s":"n"},t.fn.tipsy.autoWE=function(){return t(this).offset().left>t(document).scrollLeft()+t(e).width()/2?"e":"w"},t.fn.tipsy.autoNWNE=function(){return t(this).offset().left>t(document).scrollLeft()+t(e).width()/2?"ne":"nw"},t.fn.tipsy.autoSWSE=function(){return t(this).offset().left>t(document).scrollLeft()+t(e).width()/2?"se":"sw"},t.fn.tipsy.autoBounds=function(i,s,o){return function(){var n={ns:o[0],ew:o.length>1?o[1]:!1},l=t(document).scrollTop()+i,a=t(document).scrollLeft()+s,h=t(this);return h.offset().top<l&&(n.ns="n"),h.offset().left<a&&(n.ew="w"),t(e).width()+t(document).scrollLeft()-h.offset().left<s&&(n.ew="e"),t(e).height()+t(document).scrollTop()-h.offset().top<i&&(n.ns="s"),n.ns+(n.ew?n.ew:"")}},t.fn.tipsy.autoBounds2=function(i,s){return function(){var o={},n=t(document).scrollTop()+i,l=t(document).scrollLeft()+i,a=t(this);return s.length>1?(o.ns=s[0],o.ew=s[1]):"e"==s[0]||"w"==s[0]?o.ew=s[0]:o.ns=s[0],a.offset().top<n&&(o.ns="n"),a.offset().left<l&&(o.ew="w"),t(e).width()+t(document).scrollLeft()-(a.offset().left+a.width())<i&&(o.ew="e"),t(e).height()+t(document).scrollTop()-(a.offset().top+a.height())<i&&(o.ns="s"),o.ns?o.ns+(o.ew?o.ew:""):o.ew}}}(jQuery,window);

/* lazyload */
!function(n,t){"object"==typeof exports&&"undefined"!=typeof module?module.exports=t():"function"==typeof define&&define.amd?define(t):(n="undefined"!=typeof globalThis?globalThis:n||self).LazyLoad=t()}(this,(function(){"use strict";function n(){return n=Object.assign||function(n){for(var t=1;t<arguments.length;t++){var e=arguments[t];for(var i in e)Object.prototype.hasOwnProperty.call(e,i)&&(n[i]=e[i])}return n},n.apply(this,arguments)}var t="undefined"!=typeof window,e=t&&!("onscroll"in window)||"undefined"!=typeof navigator&&/(gle|ing|ro)bot|crawl|spider/i.test(navigator.userAgent),i=t&&"IntersectionObserver"in window,o=t&&"classList"in document.createElement("p"),a=t&&window.devicePixelRatio>1,r={elements_selector:".lazy",container:e||t?document:null,threshold:300,thresholds:null,data_src:"src",data_srcset:"srcset",data_sizes:"sizes",data_bg:"bg",data_bg_hidpi:"bg-hidpi",data_bg_multi:"bg-multi",data_bg_multi_hidpi:"bg-multi-hidpi",data_bg_set:"bg-set",data_poster:"poster",class_applied:"applied",class_loading:"loading",class_loaded:"loaded",class_error:"error",class_entered:"entered",class_exited:"exited",unobserve_completed:!0,unobserve_entered:!1,cancel_on_exit:!0,callback_enter:null,callback_exit:null,callback_applied:null,callback_loading:null,callback_loaded:null,callback_error:null,callback_finish:null,callback_cancel:null,use_native:!1,restore_on_error:!1},c=function(t){return n({},r,t)},l=function(n,t){var e,i="LazyLoad::Initialized",o=new n(t);try{e=new CustomEvent(i,{detail:{instance:o}})}catch(n){(e=document.createEvent("CustomEvent")).initCustomEvent(i,!1,!1,{instance:o})}window.dispatchEvent(e)},u="src",s="srcset",d="sizes",f="poster",_="llOriginalAttrs",g="data",v="loading",b="loaded",m="applied",p="error",h="native",E="data-",I="ll-status",y=function(n,t){return n.getAttribute(E+t)},k=function(n){return y(n,I)},w=function(n,t){return function(n,t,e){var i="data-ll-status";null!==e?n.setAttribute(i,e):n.removeAttribute(i)}(n,0,t)},A=function(n){return w(n,null)},L=function(n){return null===k(n)},O=function(n){return k(n)===h},x=[v,b,m,p],C=function(n,t,e,i){n&&(void 0===i?void 0===e?n(t):n(t,e):n(t,e,i))},N=function(n,t){o?n.classList.add(t):n.className+=(n.className?" ":"")+t},M=function(n,t){o?n.classList.remove(t):n.className=n.className.replace(new RegExp("(^|\\s+)"+t+"(\\s+|$)")," ").replace(/^\s+/,"").replace(/\s+$/,"")},z=function(n){return n.llTempImage},T=function(n,t){if(t){var e=t._observer;e&&e.unobserve(n)}},R=function(n,t){n&&(n.loadingCount+=t)},G=function(n,t){n&&(n.toLoadCount=t)},j=function(n){for(var t,e=[],i=0;t=n.children[i];i+=1)"SOURCE"===t.tagName&&e.push(t);return e},D=function(n,t){var e=n.parentNode;e&&"PICTURE"===e.tagName&&j(e).forEach(t)},H=function(n,t){j(n).forEach(t)},V=[u],F=[u,f],B=[u,s,d],J=[g],P=function(n){return!!n[_]},S=function(n){return n[_]},U=function(n){return delete n[_]},$=function(n,t){if(!P(n)){var e={};t.forEach((function(t){e[t]=n.getAttribute(t)})),n[_]=e}},q=function(n,t){if(P(n)){var e=S(n);t.forEach((function(t){!function(n,t,e){e?n.setAttribute(t,e):n.removeAttribute(t)}(n,t,e[t])}))}},K=function(n,t,e){N(n,t.class_applied),w(n,m),e&&(t.unobserve_completed&&T(n,t),C(t.callback_applied,n,e))},Q=function(n,t,e){N(n,t.class_loading),w(n,v),e&&(R(e,1),C(t.callback_loading,n,e))},W=function(n,t,e){e&&n.setAttribute(t,e)},X=function(n,t){W(n,d,y(n,t.data_sizes)),W(n,s,y(n,t.data_srcset)),W(n,u,y(n,t.data_src))},Y={IMG:function(n,t){D(n,(function(n){$(n,B),X(n,t)})),$(n,B),X(n,t)},IFRAME:function(n,t){$(n,V),W(n,u,y(n,t.data_src))},VIDEO:function(n,t){H(n,(function(n){$(n,V),W(n,u,y(n,t.data_src))})),$(n,F),W(n,f,y(n,t.data_poster)),W(n,u,y(n,t.data_src)),n.load()},OBJECT:function(n,t){$(n,J),W(n,g,y(n,t.data_src))}},Z=["IMG","IFRAME","VIDEO","OBJECT"],nn=function(n,t){!t||function(n){return n.loadingCount>0}(t)||function(n){return n.toLoadCount>0}(t)||C(n.callback_finish,t)},tn=function(n,t,e){n.addEventListener(t,e),n.llEvLisnrs[t]=e},en=function(n,t,e){n.removeEventListener(t,e)},on=function(n){return!!n.llEvLisnrs},an=function(n){if(on(n)){var t=n.llEvLisnrs;for(var e in t){var i=t[e];en(n,e,i)}delete n.llEvLisnrs}},rn=function(n,t,e){!function(n){delete n.llTempImage}(n),R(e,-1),function(n){n&&(n.toLoadCount-=1)}(e),M(n,t.class_loading),t.unobserve_completed&&T(n,e)},cn=function(n,t,e){var i=z(n)||n;on(i)||function(n,t,e){on(n)||(n.llEvLisnrs={});var i="VIDEO"===n.tagName?"loadeddata":"load";tn(n,i,t),tn(n,"error",e)}(i,(function(o){!function(n,t,e,i){var o=O(t);rn(t,e,i),N(t,e.class_loaded),w(t,b),C(e.callback_loaded,t,i),o||nn(e,i)}(0,n,t,e),an(i)}),(function(o){!function(n,t,e,i){var o=O(t);rn(t,e,i),N(t,e.class_error),w(t,p),C(e.callback_error,t,i),e.restore_on_error&&q(t,B),o||nn(e,i)}(0,n,t,e),an(i)}))},ln=function(n,t,e){!function(n){return Z.indexOf(n.tagName)>-1}(n)?function(n,t,e){!function(n){n.llTempImage=document.createElement("IMG")}(n),cn(n,t,e),function(n){P(n)||(n[_]={backgroundImage:n.style.backgroundImage})}(n),function(n,t,e){var i=y(n,t.data_bg),o=y(n,t.data_bg_hidpi),r=a&&o?o:i;r&&(n.style.backgroundImage='url("'.concat(r,'")'),z(n).setAttribute(u,r),Q(n,t,e))}(n,t,e),function(n,t,e){var i=y(n,t.data_bg_multi),o=y(n,t.data_bg_multi_hidpi),r=a&&o?o:i;r&&(n.style.backgroundImage=r,K(n,t,e))}(n,t,e),function(n,t,e){var i=y(n,t.data_bg_set);if(i){var o=i.split("|"),a=o.map((function(n){return"image-set(".concat(n,")")}));n.style.backgroundImage=a.join(),""===n.style.backgroundImage&&(a=o.map((function(n){return"-webkit-image-set(".concat(n,")")})),n.style.backgroundImage=a.join()),K(n,t,e)}}(n,t,e)}(n,t,e):function(n,t,e){cn(n,t,e),function(n,t,e){var i=Y[n.tagName];i&&(i(n,t),Q(n,t,e))}(n,t,e)}(n,t,e)},un=function(n){n.removeAttribute(u),n.removeAttribute(s),n.removeAttribute(d)},sn=function(n){D(n,(function(n){q(n,B)})),q(n,B)},dn={IMG:sn,IFRAME:function(n){q(n,V)},VIDEO:function(n){H(n,(function(n){q(n,V)})),q(n,F),n.load()},OBJECT:function(n){q(n,J)}},fn=function(n,t){(function(n){var t=dn[n.tagName];t?t(n):function(n){if(P(n)){var t=S(n);n.style.backgroundImage=t.backgroundImage}}(n)})(n),function(n,t){L(n)||O(n)||(M(n,t.class_entered),M(n,t.class_exited),M(n,t.class_applied),M(n,t.class_loading),M(n,t.class_loaded),M(n,t.class_error))}(n,t),A(n),U(n)},_n=["IMG","IFRAME","VIDEO"],gn=function(n){return n.use_native&&"loading"in HTMLImageElement.prototype},vn=function(n,t,e){n.forEach((function(n){return function(n){return n.isIntersecting||n.intersectionRatio>0}(n)?function(n,t,e,i){var o=function(n){return x.indexOf(k(n))>=0}(n);w(n,"entered"),N(n,e.class_entered),M(n,e.class_exited),function(n,t,e){t.unobserve_entered&&T(n,e)}(n,e,i),C(e.callback_enter,n,t,i),o||ln(n,e,i)}(n.target,n,t,e):function(n,t,e,i){L(n)||(N(n,e.class_exited),function(n,t,e,i){e.cancel_on_exit&&function(n){return k(n)===v}(n)&&"IMG"===n.tagName&&(an(n),function(n){D(n,(function(n){un(n)})),un(n)}(n),sn(n),M(n,e.class_loading),R(i,-1),A(n),C(e.callback_cancel,n,t,i))}(n,t,e,i),C(e.callback_exit,n,t,i))}(n.target,n,t,e)}))},bn=function(n){return Array.prototype.slice.call(n)},mn=function(n){return n.container.querySelectorAll(n.elements_selector)},pn=function(n){return function(n){return k(n)===p}(n)},hn=function(n,t){return function(n){return bn(n).filter(L)}(n||mn(t))},En=function(n,e){var o=c(n);this._settings=o,this.loadingCount=0,function(n,t){i&&!gn(n)&&(t._observer=new IntersectionObserver((function(e){vn(e,n,t)}),function(n){return{root:n.container===document?null:n.container,rootMargin:n.thresholds||n.threshold+"px"}}(n)))}(o,this),function(n,e){t&&(e._onlineHandler=function(){!function(n,t){var e;(e=mn(n),bn(e).filter(pn)).forEach((function(t){M(t,n.class_error),A(t)})),t.update()}(n,e)},window.addEventListener("online",e._onlineHandler))}(o,this),this.update(e)};return En.prototype={update:function(n){var t,o,a=this._settings,r=hn(n,a);G(this,r.length),!e&&i?gn(a)?function(n,t,e){n.forEach((function(n){-1!==_n.indexOf(n.tagName)&&function(n,t,e){n.setAttribute("loading","lazy"),cn(n,t,e),function(n,t){var e=Y[n.tagName];e&&e(n,t)}(n,t),w(n,h)}(n,t,e)})),G(e,0)}(r,a,this):(o=r,function(n){n.disconnect()}(t=this._observer),function(n,t){t.forEach((function(t){n.observe(t)}))}(t,o)):this.loadAll(r)},destroy:function(){this._observer&&this._observer.disconnect(),t&&window.removeEventListener("online",this._onlineHandler),mn(this._settings).forEach((function(n){U(n)})),delete this._observer,delete this._settings,delete this._onlineHandler,delete this.loadingCount,delete this.toLoadCount},loadAll:function(n){var t=this,e=this._settings;hn(n,e).forEach((function(n){T(n,t),ln(n,e,t)}))},restoreAll:function(){var n=this._settings;mn(n).forEach((function(t){fn(t,n)}))}},En.load=function(n,t){var e=c(t);ln(n,e)},En.resetStatus=function(n){A(n)},t&&function(n,t){if(t)if(t.length)for(var e,i=0;e=t[i];i+=1)l(n,e);else l(n,t)}(En,window.lazyLoadOptions),En}));

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
      LiteYTEmbed.addPrefetch('preconnect', 'https://www.google.com');
      LiteYTEmbed.addPrefetch('preconnect', 'https://googleads.g.doubleclick.net');
      LiteYTEmbed.addPrefetch('preconnect', 'https://static.doubleclick.net');
      LiteYTEmbed.preconnected = false;
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

var AlertFloat = new function() {
	this.prefix = 'alertfloat__color--',
	this.element = 'alertfloat',
	this.elclass = '',
	this._type = 'default',
	this._timeout = 3000,
	this.show = objects => {
		let body = empty(objects.body) ? this._body : objects.body;
		let type = empty(objects.type) ? this._type : objects.type;
		let title = (!empty(objects.title)) ? `<h3 class="alertfloat--title">${objects.title}</h3>` : '';
		$('#brandday').after(`
			<div id="${this.element}" class="alertfloat ${this.elclass} ${this.prefix}${type}">
				${title}
				<p class="alertfloat--content">${body}<p>
			</div>
		`)
		this.close(objects.timeout)
	},
	this.close = end => {
		removeElementOfBody = empty(end) ? this._timeout : end * 1000;
		setTimeout(() => {
			$('#brandday #' + this.element).remove()
		}, removeElementOfBody);
	}
}

/**
 * MyDialog v2
 * @autor Miguel92
*/
var mydialog = {
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

		$('#mydialog #dialog').css('position', 'absolute');
		$('#mydialog #dialog').fadeIn('fast');
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
	buttons_action: (remBtn, dataObject) => {	
		var is_html = '';	
		if(!dataObject.ok && !dataObject.fail && remBtn) $('#mydialog #buttons').hide()
		// Si existe "OK"
		if(dataObject.ok) {
			// Si tiene accion definido
			if(dataObject.ok.action === 'close' || !dataObject.ok.action) dataObject.ok.action = 'mydialog.close()';
			let classdisabled = dataObject.ok.active ? '' : ' disabled';
			is_html += `<input type="button" class="btn btn-success mBtn btnOk${classdisabled}" style="display:inline-block!important;" onclick="${dataObject.ok.action}" value="${dataObject.ok.text}"${classdisabled} />`;
		}
		// Si existe "fail"
		if(dataObject.fail) {
			// Si tiene accion definido
			if(dataObject.fail.action === 'close' || !dataObject.fail.action) dataObject.ok.action = 'mydialog.close()';
			let classdisabled = dataObject.fail.active ? '' : ' disabled';
			is_html += `<input type="button" class="btn btn-danger mBtn btnCancel${classdisabled}" style="display:inline-block!important;" onclick="${dataObject.ok.action}" value="${dataObject.fail.text}"${classdisabled} />`;
		}
		// Por que si se ejecuta 2 veces y el 1ro tiene mydialog.buttons(false)
		// El 2do ya no se visualizará ya que no existe en el DOM #buttons
		$('#mydialog #buttons').show().html(is_html)
		
		if(!dataObject.ok && !dataObject.fail) {
			if(dataObject.ok.focus) $('#mydialog #buttons .mBtn.btnOk').focus();
			else if(dataObject.fail.focus) $('#mydialog #buttons .mBtn.btnCancel').focus();
		}
	},
	alert: function(title, body, reload, url = '') {
		if(reload && !empty(url)) {
			action = `locahtion.href='${url}'`;
		} else action = 'mydialog.close();' + (reload ? 'location.reload();' : 'close');
		this.show();
		this.title(title);
		this.body(body);
		this.buttons(true, true, 'Aceptar', action, true, true, false);
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
		if(!empty(obj.class_aux)) mydialog.class_aux = obj.addClass;
		if(obj.close_button) mydialog.close_button = obj.close_button;
		if(obj.mask_button) mydialog.mask_button = obj.mask_button;
		if(obj.size) mydialog.size = obj.size; // small | normal | big
		mydialog.show(true);
		mydialog.title(obj.title);
		mydialog.body(obj.body);
		if(typeof obj.buttons === 'boolean') {
			mydialog.buttons(false)
		} else if(obj.buttons.fail !== undefined) {
			mydialog.buttons(true, true, obj.buttons.ok.text, obj.buttons.ok.action, true, true, true, obj.buttons.fail.text, obj.buttons.fail.action, true, false);
		} else {
			mydialog.buttons(true, true, obj.buttons.ok.text, obj.buttons.ok.action, true, true, false);
		}
		mydialog.center();
	}

};

document.onkeydown = e => {
	if(e.keyCode === 27 || e.which === 27) mydialog.close();
};