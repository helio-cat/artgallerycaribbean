/*! modernizr 3.6.0 (Custom Build) | MIT *
 * https://modernizr.com/download/?-backgroundsize-placeholder-setclasses !*/
!function(e,n,t){function r(e,n){return typeof e===n}function o(){var e,n,t,o,s,i,l;for(var a in S)if(S.hasOwnProperty(a)){if(e=[],n=S[a],n.name&&(e.push(n.name.toLowerCase()),n.options&&n.options.aliases&&n.options.aliases.length))for(t=0;t<n.options.aliases.length;t++)e.push(n.options.aliases[t].toLowerCase());for(o=r(n.fn,"function")?n.fn():n.fn,s=0;s<e.length;s++)i=e[s],l=i.split("."),1===l.length?Modernizr[l[0]]=o:(!Modernizr[l[0]]||Modernizr[l[0]]instanceof Boolean||(Modernizr[l[0]]=new Boolean(Modernizr[l[0]])),Modernizr[l[0]][l[1]]=o),C.push((o?"":"no-")+l.join("-"))}}function s(e){var n=_.className,t=Modernizr._config.classPrefix||"";if(b&&(n=n.baseVal),Modernizr._config.enableJSClass){var r=new RegExp("(^|\\s)"+t+"no-js(\\s|$)");n=n.replace(r,"$1"+t+"js$2")}Modernizr._config.enableClasses&&(n+=" "+t+e.join(" "+t),b?_.className.baseVal=n:_.className=n)}function i(e,n){return!!~(""+e).indexOf(n)}function l(){return"function"!=typeof n.createElement?n.createElement(arguments[0]):b?n.createElementNS.call(n,"http://www.w3.org/2000/svg",arguments[0]):n.createElement.apply(n,arguments)}function a(e){return e.replace(/([a-z])-([a-z])/g,function(e,n,t){return n+t.toUpperCase()}).replace(/^-/,"")}function u(e,n){return function(){return e.apply(n,arguments)}}function f(e,n,t){var o;for(var s in e)if(e[s]in n)return t===!1?e[s]:(o=n[e[s]],r(o,"function")?u(o,t||n):o);return!1}function c(e){return e.replace(/([A-Z])/g,function(e,n){return"-"+n.toLowerCase()}).replace(/^ms-/,"-ms-")}function d(n,t,r){var o;if("getComputedStyle"in e){o=getComputedStyle.call(e,n,t);var s=e.console;if(null!==o)r&&(o=o.getPropertyValue(r));else if(s){var i=s.error?"error":"log";s[i].call(s,"getComputedStyle returning null, its possible modernizr test results are inaccurate")}}else o=!t&&n.currentStyle&&n.currentStyle[r];return o}function p(){var e=n.body;return e||(e=l(b?"svg":"body"),e.fake=!0),e}function m(e,t,r,o){var s,i,a,u,f="modernizr",c=l("div"),d=p();if(parseInt(r,10))for(;r--;)a=l("div"),a.id=o?o[r]:f+(r+1),c.appendChild(a);return s=l("style"),s.type="text/css",s.id="s"+f,(d.fake?d:c).appendChild(s),d.appendChild(c),s.styleSheet?s.styleSheet.cssText=e:s.appendChild(n.createTextNode(e)),c.id=f,d.fake&&(d.style.background="",d.style.overflow="hidden",u=_.style.overflow,_.style.overflow="hidden",_.appendChild(d)),i=t(c,e),d.fake?(d.parentNode.removeChild(d),_.style.overflow=u,_.offsetHeight):c.parentNode.removeChild(c),!!i}function h(n,r){var o=n.length;if("CSS"in e&&"supports"in e.CSS){for(;o--;)if(e.CSS.supports(c(n[o]),r))return!0;return!1}if("CSSSupportsRule"in e){for(var s=[];o--;)s.push("("+c(n[o])+":"+r+")");return s=s.join(" or "),m("@supports ("+s+") { #modernizr { position: absolute; } }",function(e){return"absolute"==d(e,null,"position")})}return t}function y(e,n,o,s){function u(){c&&(delete N.style,delete N.modElem)}if(s=r(s,"undefined")?!1:s,!r(o,"undefined")){var f=h(e,o);if(!r(f,"undefined"))return f}for(var c,d,p,m,y,g=["modernizr","tspan","samp"];!N.style&&g.length;)c=!0,N.modElem=l(g.shift()),N.style=N.modElem.style;for(p=e.length,d=0;p>d;d++)if(m=e[d],y=N.style[m],i(m,"-")&&(m=a(m)),N.style[m]!==t){if(s||r(o,"undefined"))return u(),"pfx"==n?m:!0;try{N.style[m]=o}catch(v){}if(N.style[m]!=y)return u(),"pfx"==n?m:!0}return u(),!1}function g(e,n,t,o,s){var i=e.charAt(0).toUpperCase()+e.slice(1),l=(e+" "+z.join(i+" ")+i).split(" ");return r(n,"string")||r(n,"undefined")?y(l,n,o,s):(l=(e+" "+P.join(i+" ")+i).split(" "),f(l,n,t))}function v(e,n,r){return g(e,t,t,n,r)}var C=[],S=[],w={_version:"3.6.0",_config:{classPrefix:"",enableClasses:!0,enableJSClass:!0,usePrefixes:!0},_q:[],on:function(e,n){var t=this;setTimeout(function(){n(t[e])},0)},addTest:function(e,n,t){S.push({name:e,fn:n,options:t})},addAsyncTest:function(e){S.push({name:null,fn:e})}},Modernizr=function(){};Modernizr.prototype=w,Modernizr=new Modernizr;var _=n.documentElement,b="svg"===_.nodeName.toLowerCase(),x="Moz O ms Webkit",z=w._config.usePrefixes?x.split(" "):[];w._cssomPrefixes=z;var P=w._config.usePrefixes?x.toLowerCase().split(" "):[];w._domPrefixes=P;var E={elem:l("modernizr")};Modernizr._q.push(function(){delete E.elem});var N={style:E.elem.style};Modernizr._q.unshift(function(){delete N.style}),w.testAllProps=g,w.testAllProps=v,Modernizr.addTest("backgroundsize",v("backgroundSize","100%",!0)),Modernizr.addTest("placeholder","placeholder"in l("input")&&"placeholder"in l("textarea")),o(),s(C),delete w.addTest,delete w.addAsyncTest;for(var T=0;T<Modernizr._q.length;T++)Modernizr._q[T]();e.Modernizr=Modernizr}(window,document);


/**
 * jQuery.browser.mobile (http://detectmobilebrowser.com/)
 **/
(function(a){(jQuery.browser=jQuery.browser||{}).mobile=/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i.test(a)||/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0,4))})(navigator.userAgent||navigator.vendor||window.opera);


function isEmpty(val){ if(val == null){return true;} for(var i=0; i < val.length; i++) { if ((val.charAt(i)!=' ')&&(val.charAt(i)!="\t")&&(val.charAt(i)!="\n")&&(val.charAt(i)!="\r")){ return false; } } return true; }
function isEmail(val){if (isEmpty(val)){ return false; }var i = 1,length = val.length;while ((i < length) && (val.charAt(i) != "@")){i++;}if ((i >= length) || (val.charAt(i) != "@")){ return false; }else { i += 2; }while ((i < length) && (val.charAt(i) != ".")){i++;}if ((i >= length - 1) || (val.charAt(i) != ".")){ return false; }else { return true; }}
function isPhone(val){ pattern = new RegExp(/^[0-9-\s+]{5,16}$/); if (!pattern.test(val)) { return false; } return true; }
function onSelectRedirect(what){ var destination = what.options[what.selectedIndex].value; if (destination) location.href = destination; }

var windowObjectReference = null;
function printArea(id, w, h){
    
    var html = '<html><head><link rel="stylesheet" type="text/css" href="/css/print.css"></head><body>';
		html += '<h1>Gallery Of Caribbean Art</h1>';
		html += '<p><strong>Website:</strong> https://artgallerycaribbean.com<br><strong>Email:</strong> artgallerycaribbean@caribsurf.com<br><strong>Phone:</strong> (246) 419-0858</p><hr>';
		html += document.getElementById(id).innerHTML;
        html += '<hr>';
        html += '<p>' + window.location.href + '</p>';
        html += '</body></html>';
	
	var windowAttr = "location=yes,statusbar=no,directories=no,menubar=no,titlebar=no,toolbar=no,dependent=no";
		windowAttr += ",width=" + w + ",height=" + h;
		windowAttr += ",resizable=yes,screenX=" + 200 + ",screenY=" + 200 + ",personalbar=no,scrollbars=yes";
	
	if(windowObjectReference == null || windowObjectReference.closed) {
        windowObjectReference = window.open("", "_blank", windowAttr);

        windowObjectReference.document.write(html);
        windowObjectReference.document.close();
        windowObjectReference.focus();
        windowObjectReference.print();
        windowObjectReference.close();
    }
    else{
        windowObjectReference.focus();
    }
    
}

$(function() {
		
	if (!Modernizr.placeholder) {
	 
		$('[placeholder]').focus(function() {
			var input = $(this);
			if (input.val() == input.attr('placeholder')) {
				input.val('');
				input.removeClass('placeholder');
			}
		}).blur(function() {
			var input = $(this);
			if (input.val() == '' || input.val() == input.attr('placeholder')) {
				input.addClass('placeholder');
				input.val(input.attr('placeholder'));
			}
		}).blur();
		
		$('[placeholder]').parents('form').submit(function() {
			$(this).find('[placeholder]').each(function() {
				var input = $(this);
				if (input.val() == input.attr('placeholder')) {
					input.val('');
				}
			})
		});	
	
	}
	
});

(function($){

	$.fn.center = function (owidth) {
		if(jQuery.browser.mobile){ 
            if($(window).width() <= 575){
                owidth = ($(window).width()-40);
            }
        }
		
		this.css({
			'width': owidth + 'px',
			'top': Math.max(0, (($(window).height() - $(this).outerHeight()) / 2)) + 'px',
			'left': Math.max(0, (($(window).width() - owidth) / 2)) + 'px'
		});
		return this;
	};
	
	$.fn.imageHoverPreview = function(options){	
		var offset = 15, settings = $.extend({w: 'auto'}, options);
		
		return this.each(function() {
			var self = $(this);
			
			self.hover(function(e) {
				var src = self.data('src');
				$('body').append('<div id="imageHoverPreview" style="display:none;position:absolute;width:'+settings.w+';border:1px solid #333;background:#fff;padding:5px;color:#fff;"><img src="' + src + '" alt="Image Preview" /></div>');
				$('#imageHoverPreview').css({'top': (e.pageY - offset), 'left': (e.pageX + offset)}).fadeIn('fast');
			}, function(){
				$('#imageHoverPreview').remove();
			}).mousemove(function(e) {
				var preview = $('#imageHoverPreview'),
					ttw = preview.width(),
					tth = preview.height(),
					wscrY = $(window).scrollTop(),
					wscrX = $(window).scrollLeft(),
					curX = (document.all) ? event.clientX + wscrX : e.pageX,
					curY = (document.all) ? event.clientY + wscrY : e.pageY,
					ttleft = ((curX - wscrX + offset*2 + ttw) > $(window).width()) ? curX - ttw - offset : curX + offset,
					tttop = ((curY - wscrY + offset*2 + tth) > $(window).height()) ? curY - tth - offset : curY + offset;
				
				if (ttleft < wscrX + offset){ ttleft = wscrX + offset; }
				if (tttop < wscrY + offset) { tttop = curY + offset; }
				
				preview.css({'left': ttleft, 'top': tttop});
			});
		});
	};
	
})(jQuery);
