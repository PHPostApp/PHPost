!function(n,t){"object"==typeof exports&&"undefined"!=typeof module?module.exports=t():"function"==typeof define&&define.amd?define(t):(n="undefined"!=typeof globalThis?globalThis:n||self).LazyLoad=t()}(this,(function(){"use strict";var n="undefined"!=typeof window,t=n&&!("onscroll"in window)||"undefined"!=typeof navigator&&/(gle|ing|ro)bot|crawl|spider/i.test(navigator.userAgent),e=n&&window.devicePixelRatio>1,i={elements_selector:".lazy",container:t||n?document:null,threshold:300,thresholds:null,data_src:"src",data_srcset:"srcset",data_sizes:"sizes",data_bg:"bg",data_bg_hidpi:"bg-hidpi",data_bg_multi:"bg-multi",data_bg_multi_hidpi:"bg-multi-hidpi",data_bg_set:"bg-set",data_poster:"poster",class_applied:"applied",class_loading:"loading",class_loaded:"loaded",class_error:"error",class_entered:"entered",class_exited:"exited",unobserve_completed:!0,unobserve_entered:!1,cancel_on_exit:!0,callback_enter:null,callback_exit:null,callback_applied:null,callback_loading:null,callback_loaded:null,callback_error:null,callback_finish:null,callback_cancel:null,use_native:!1,restore_on_error:!1},o=function(n){return Object.assign({},i,n)},a=function(n,t){var e,i="LazyLoad::Initialized",o=new n(t);try{e=new CustomEvent(i,{detail:{instance:o}})}catch(n){(e=document.createEvent("CustomEvent")).initCustomEvent(i,!1,!1,{instance:o})}window.dispatchEvent(e)},r="src",c="srcset",u="sizes",l="poster",s="llOriginalAttrs",d="data",f="loading",_="loaded",g="applied",v="error",b="native",m="data-",h="ll-status",p=function(n,t){return n.getAttribute(m+t)},E=function(n){return p(n,h)},I=function(n,t){return function(n,t,e){var i=m+t;null!==e?n.setAttribute(i,e):n.removeAttribute(i)}(n,h,t)},k=function(n){return I(n,null)},y=function(n){return null===E(n)},A=function(n){return E(n)===b},L=[f,_,g,v],w=function(n,t,e,i){n&&"function"==typeof n&&(void 0===i?void 0===e?n(t):n(t,e):n(t,e,i))},x=function(t,e){n&&""!==e&&t.classList.add(e)},C=function(t,e){n&&""!==e&&t.classList.remove(e)},O=function(n){return n.llTempImage},M=function(n,t){if(t){var e=t._observer;e&&e.unobserve(n)}},z=function(n,t){n&&(n.loadingCount+=t)},N=function(n,t){n&&(n.toLoadCount=t)},T=function(n){for(var t,e=[],i=0;t=n.children[i];i+=1)"SOURCE"===t.tagName&&e.push(t);return e},R=function(n,t){var e=n.parentNode;e&&"PICTURE"===e.tagName&&T(e).forEach(t)},G=function(n,t){T(n).forEach(t)},D=[r],H=[r,l],V=[r,c,u],j=[d],F=function(n){return!!n[s]},B=function(n){return n[s]},J=function(n){return delete n[s]},S=function(n,t){if(!F(n)){var e={};t.forEach((function(t){e[t]=n.getAttribute(t)})),n[s]=e}},P=function(n,t){if(F(n)){var e=B(n);t.forEach((function(t){!function(n,t,e){e?n.setAttribute(t,e):n.removeAttribute(t)}(n,t,e[t])}))}},U=function(n,t,e){x(n,t.class_applied),I(n,g),e&&(t.unobserve_completed&&M(n,t),w(t.callback_applied,n,e))},q=function(n,t,e){x(n,t.class_loading),I(n,f),e&&(z(e,1),w(t.callback_loading,n,e))},K=function(n,t,e){e&&n.setAttribute(t,e)},Q=function(n,t){K(n,u,p(n,t.data_sizes)),K(n,c,p(n,t.data_srcset)),K(n,r,p(n,t.data_src))},W={IMG:function(n,t){R(n,(function(n){S(n,V),Q(n,t)})),S(n,V),Q(n,t)},IFRAME:function(n,t){S(n,D),K(n,r,p(n,t.data_src))},VIDEO:function(n,t){G(n,(function(n){S(n,D),K(n,r,p(n,t.data_src))})),S(n,H),K(n,l,p(n,t.data_poster)),K(n,r,p(n,t.data_src)),n.load()},OBJECT:function(n,t){S(n,j),K(n,d,p(n,t.data_src))}},X=["IMG","IFRAME","VIDEO","OBJECT"],Y=function(n,t){!t||function(n){return n.loadingCount>0}(t)||function(n){return n.toLoadCount>0}(t)||w(n.callback_finish,t)},Z=function(n,t,e){n.addEventListener(t,e),n.llEvLisnrs[t]=e},$=function(n,t,e){n.removeEventListener(t,e)},nn=function(n){return!!n.llEvLisnrs},tn=function(n){if(nn(n)){var t=n.llEvLisnrs;for(var e in t){var i=t[e];$(n,e,i)}delete n.llEvLisnrs}},en=function(n,t,e){!function(n){delete n.llTempImage}(n),z(e,-1),function(n){n&&(n.toLoadCount-=1)}(e),C(n,t.class_loading),t.unobserve_completed&&M(n,e)},on=function(n,t,e){var i=O(n)||n;nn(i)||function(n,t,e){nn(n)||(n.llEvLisnrs={});var i="VIDEO"===n.tagName?"loadeddata":"load";Z(n,i,t),Z(n,"error",e)}(i,(function(o){!function(n,t,e,i){var o=A(t);en(t,e,i),x(t,e.class_loaded),I(t,_),w(e.callback_loaded,t,i),o||Y(e,i)}(0,n,t,e),tn(i)}),(function(o){!function(n,t,e,i){var o=A(t);en(t,e,i),x(t,e.class_error),I(t,v),w(e.callback_error,t,i),e.restore_on_error&&P(t,V),o||Y(e,i)}(0,n,t,e),tn(i)}))},an=function(n,t,i){!function(n){return X.indexOf(n.tagName)>-1}(n)?function(n,t,i){!function(n){n.llTempImage=document.createElement("IMG")}(n),on(n,t,i),function(n){F(n)||(n[s]={backgroundImage:n.style.backgroundImage})}(n),function(n,t,i){var o=p(n,t.data_bg),a=p(n,t.data_bg_hidpi),c=e&&a?a:o;c&&(n.style.backgroundImage='url("'.concat(c,'")'),O(n).setAttribute(r,c),q(n,t,i))}(n,t,i),function(n,t,i){var o=p(n,t.data_bg_multi),a=p(n,t.data_bg_multi_hidpi),r=e&&a?a:o;r&&(n.style.backgroundImage=r,U(n,t,i))}(n,t,i),function(n,t,e){var i=p(n,t.data_bg_set);if(i){var o=i.split("|"),a=o.map((function(n){return"image-set(".concat(n,")")}));n.style.backgroundImage=a.join(),""===n.style.backgroundImage&&(a=o.map((function(n){return"-webkit-image-set(".concat(n,")")})),n.style.backgroundImage=a.join()),U(n,t,e)}}(n,t,i)}(n,t,i):function(n,t,e){on(n,t,e),function(n,t,e){var i=W[n.tagName];i&&(i(n,t),q(n,t,e))}(n,t,e)}(n,t,i)},rn=function(n){n.removeAttribute(r),n.removeAttribute(c),n.removeAttribute(u)},cn=function(n){R(n,(function(n){P(n,V)})),P(n,V)},un={IMG:cn,IFRAME:function(n){P(n,D)},VIDEO:function(n){G(n,(function(n){P(n,D)})),P(n,H),n.load()},OBJECT:function(n){P(n,j)}},ln=function(n,t){(function(n){var t=un[n.tagName];t?t(n):function(n){if(F(n)){var t=B(n);n.style.backgroundImage=t.backgroundImage}}(n)})(n),function(n,t){y(n)||A(n)||(C(n,t.class_entered),C(n,t.class_exited),C(n,t.class_applied),C(n,t.class_loading),C(n,t.class_loaded),C(n,t.class_error))}(n,t),k(n),J(n)},sn=["IMG","IFRAME","VIDEO"],dn=function(n){return n.use_native&&"loading"in HTMLImageElement.prototype},fn=function(n,t,e){n.forEach((function(n){return function(n){return n.isIntersecting||n.intersectionRatio>0}(n)?function(n,t,e,i){var o=function(n){return L.indexOf(E(n))>=0}(n);I(n,"entered"),x(n,e.class_entered),C(n,e.class_exited),function(n,t,e){t.unobserve_entered&&M(n,e)}(n,e,i),w(e.callback_enter,n,t,i),o||an(n,e,i)}(n.target,n,t,e):function(n,t,e,i){y(n)||(x(n,e.class_exited),function(n,t,e,i){e.cancel_on_exit&&function(n){return E(n)===f}(n)&&"IMG"===n.tagName&&(tn(n),function(n){R(n,(function(n){rn(n)})),rn(n)}(n),cn(n),C(n,e.class_loading),z(i,-1),k(n),w(e.callback_cancel,n,t,i))}(n,t,e,i),w(e.callback_exit,n,t,i))}(n.target,n,t,e)}))},_n=function(n){return Array.prototype.slice.call(n)},gn=function(n){return n.container.querySelectorAll(n.elements_selector)},vn=function(n){return function(n){return E(n)===v}(n)},bn=function(n,t){return function(n){return _n(n).filter(y)}(n||gn(t))},mn=function(t,e){var i=o(t);this._settings=i,this.loadingCount=0,function(n,t){dn(n)||(t._observer=new IntersectionObserver((function(e){fn(e,n,t)}),function(n){return{root:n.container===document?null:n.container,rootMargin:n.thresholds||n.threshold+"px"}}(n)))}(i,this),function(t,e){n&&(e._onlineHandler=function(){!function(n,t){var e;(e=gn(n),_n(e).filter(vn)).forEach((function(t){C(t,n.class_error),k(t)})),t.update()}(t,e)},window.addEventListener("online",e._onlineHandler))}(i,this),this.update(e)};return mn.prototype={update:function(n){var e,i,o=this._settings,a=bn(n,o);N(this,a.length),t?this.loadAll(a):dn(o)?function(n,t,e){n.forEach((function(n){-1!==sn.indexOf(n.tagName)&&function(n,t,e){n.setAttribute("loading","lazy"),on(n,t,e),function(n,t){var e=W[n.tagName];e&&e(n,t)}(n,t),I(n,b)}(n,t,e)})),N(e,0)}(a,o,this):(i=a,function(n){n.disconnect()}(e=this._observer),function(n,t){t.forEach((function(t){n.observe(t)}))}(e,i))},destroy:function(){this._observer&&this._observer.disconnect(),n&&window.removeEventListener("online",this._onlineHandler),gn(this._settings).forEach((function(n){J(n)})),delete this._observer,delete this._settings,delete this._onlineHandler,delete this.loadingCount,delete this.toLoadCount},loadAll:function(n){var t=this,e=this._settings;bn(n,e).forEach((function(n){M(n,t),an(n,e,t)}))},restoreAll:function(){var n=this._settings;gn(n).forEach((function(t){ln(t,n)}))}},mn.load=function(n,t){var e=o(t);an(n,e)},mn.resetStatus=function(n){k(n)},n&&function(n,t){if(t)if(t.length)for(var e,i=0;e=t[i];i+=1)a(n,e);else a(n,t)}(mn,window.lazyLoadOptions),mn}));