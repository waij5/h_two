/*! jQuery v2.2.4 | (c) jQuery Foundation | jquery.org/license */
!function(a,b){"object"==typeof module&&"object"==typeof module.exports?module.exports=a.document?b(a,!0):function(a){if(!a.document)throw new Error("jQuery requires a window with a document");return b(a)}:b(a)}("undefined"!=typeof window?window:this,function(a,b){var c=[],d=a.document,e=c.slice,f=c.concat,g=c.push,h=c.indexOf,i={},j=i.toString,k=i.hasOwnProperty,l={},m="2.2.4",n=function(a,b){return new n.fn.init(a,b)},o=/^[\s\uFEFF\xA0]+|[\s\uFEFF\xA0]+$/g,p=/^-ms-/,q=/-([\da-z])/gi,r=function(a,b){return b.toUpperCase()};n.fn=n.prototype={jquery:m,constructor:n,selector:"",length:0,toArray:function(){return e.call(this)},get:function(a){return null!=a?0>a?this[a+this.length]:this[a]:e.call(this)},pushStack:function(a){var b=n.merge(this.constructor(),a);return b.prevObject=this,b.context=this.context,b},each:function(a){return n.each(this,a)},map:function(a){return this.pushStack(n.map(this,function(b,c){return a.call(b,c,b)}))},slice:function(){return this.pushStack(e.apply(this,arguments))},first:function(){return this.eq(0)},last:function(){return this.eq(-1)},eq:function(a){var b=this.length,c=+a+(0>a?b:0);return this.pushStack(c>=0&&b>c?[this[c]]:[])},end:function(){return this.prevObject||this.constructor()},push:g,sort:c.sort,splice:c.splice},n.extend=n.fn.extend=function(){var a,b,c,d,e,f,g=arguments[0]||{},h=1,i=arguments.length,j=!1;for("boolean"==typeof g&&(j=g,g=arguments[h]||{},h++),"object"==typeof g||n.isFunction(g)||(g={}),h===i&&(g=this,h--);i>h;h++)if(null!=(a=arguments[h]))for(b in a)c=g[b],d=a[b],g!==d&&(j&&d&&(n.isPlainObject(d)||(e=n.isArray(d)))?(e?(e=!1,f=c&&n.isArray(c)?c:[]):f=c&&n.isPlainObject(c)?c:{},g[b]=n.extend(j,f,d)):void 0!==d&&(g[b]=d));return g},n.extend({expando:"jQuery"+(m+Math.random()).replace(/\D/g,""),isReady:!0,error:function(a){throw new Error(a)},noop:function(){},isFunction:function(a){return"function"===n.type(a)},isArray:Array.isArray,isWindow:function(a){return null!=a&&a===a.window},isNumeric:function(a){var b=a&&a.toString();return!n.isArray(a)&&b-parseFloat(b)+1>=0},isPlainObject:function(a){var b;if("object"!==n.type(a)||a.nodeType||n.isWindow(a))return!1;if(a.constructor&&!k.call(a,"constructor")&&!k.call(a.constructor.prototype||{},"isPrototypeOf"))return!1;for(b in a);return void 0===b||k.call(a,b)},isEmptyObject:function(a){var b;for(b in a)return!1;return!0},type:function(a){return null==a?a+"":"object"==typeof a||"function"==typeof a?i[j.call(a)]||"object":typeof a},globalEval:function(a){var b,c=eval;a=n.trim(a),a&&(1===a.indexOf("use strict")?(b=d.createElement("script"),b.text=a,d.head.appendChild(b).parentNode.removeChild(b)):c(a))},camelCase:function(a){return a.replace(p,"ms-").replace(q,r)},nodeName:function(a,b){return a.nodeName&&a.nodeName.toLowerCase()===b.toLowerCase()},each:function(a,b){var c,d=0;if(s(a)){for(c=a.length;c>d;d++)if(b.call(a[d],d,a[d])===!1)break}else for(d in a)if(b.call(a[d],d,a[d])===!1)break;return a},trim:function(a){return null==a?"":(a+"").replace(o,"")},makeArray:function(a,b){var c=b||[];return null!=a&&(s(Object(a))?n.merge(c,"string"==typeof a?[a]:a):g.call(c,a)),c},inArray:function(a,b,c){return null==b?-1:h.call(b,a,c)},merge:function(a,b){for(var c=+b.length,d=0,e=a.length;c>d;d++)a[e++]=b[d];return a.length=e,a},grep:function(a,b,c){for(var d,e=[],f=0,g=a.length,h=!c;g>f;f++)d=!b(a[f],f),d!==h&&e.push(a[f]);return e},map:function(a,b,c){var d,e,g=0,h=[];if(s(a))for(d=a.length;d>g;g++)e=b(a[g],g,c),null!=e&&h.push(e);else for(g in a)e=b(a[g],g,c),null!=e&&h.push(e);return f.apply([],h)},guid:1,proxy:function(a,b){var c,d,f;return"string"==typeof b&&(c=a[b],b=a,a=c),n.isFunction(a)?(d=e.call(arguments,2),f=function(){return a.apply(b||this,d.concat(e.call(arguments)))},f.guid=a.guid=a.guid||n.guid++,f):void 0},now:Date.now,support:l}),"function"==typeof Symbol&&(n.fn[Symbol.iterator]=c[Symbol.iterator]),n.each("Boolean Number String Function Array Date RegExp Object Error Symbol".split(" "),function(a,b){i["[object "+b+"]"]=b.toLowerCase()});function s(a){var b=!!a&&"length"in a&&a.length,c=n.type(a);return"function"===c||n.isWindow(a)?!1:"array"===c||0===b||"number"==typeof b&&b>0&&b-1 in a}var t=function(a){var b,c,d,e,f,g,h,i,j,k,l,m,n,o,p,q,r,s,t,u="sizzle"+1*new Date,v=a.document,w=0,x=0,y=ga(),z=ga(),A=ga(),B=function(a,b){return a===b&&(l=!0),0},C=1<<31,D={}.hasOwnProperty,E=[],F=E.pop,G=E.push,H=E.push,I=E.slice,J=function(a,b){for(var c=0,d=a.length;d>c;c++)if(a[c]===b)return c;return-1},K="checked|selected|async|autofocus|autoplay|controls|defer|disabled|hidden|ismap|loop|multiple|open|readonly|required|scoped",L="[\\x20\\t\\r\\n\\f]",M="(?:\\\\.|[\\w-]|[^\\x00-\\xa0])+",N="\\["+L+"*("+M+")(?:"+L+"*([*^$|!~]?=)"+L+"*(?:'((?:\\\\.|[^\\\\'])*)'|\"((?:\\\\.|[^\\\\\"])*)\"|("+M+"))|)"+L+"*\\]",O=":("+M+")(?:\\((('((?:\\\\.|[^\\\\'])*)'|\"((?:\\\\.|[^\\\\\"])*)\")|((?:\\\\.|[^\\\\()[\\]]|"+N+")*)|.*)\\)|)",P=new RegExp(L+"+","g"),Q=new RegExp("^"+L+"+|((?:^|[^\\\\])(?:\\\\.)*)"+L+"+$","g"),R=new RegExp("^"+L+"*,"+L+"*"),S=new RegExp("^"+L+"*([>+~]|"+L+")"+L+"*"),T=new RegExp("="+L+"*([^\\]'\"]*?)"+L+"*\\]","g"),U=new RegExp(O),V=new RegExp("^"+M+"$"),W={ID:new RegExp("^#("+M+")"),CLASS:new RegExp("^\\.("+M+")"),TAG:new RegExp("^("+M+"|[*])"),ATTR:new RegExp("^"+N),PSEUDO:new RegExp("^"+O),CHILD:new RegExp("^:(only|first|last|nth|nth-last)-(child|of-type)(?:\\("+L+"*(even|odd|(([+-]|)(\\d*)n|)"+L+"*(?:([+-]|)"+L+"*(\\d+)|))"+L+"*\\)|)","i"),bool:new RegExp("^(?:"+K+")$","i"),needsContext:new RegExp("^"+L+"*[>+~]|:(even|odd|eq|gt|lt|nth|first|last)(?:\\("+L+"*((?:-\\d)?\\d*)"+L+"*\\)|)(?=[^-]|$)","i")},X=/^(?:input|select|textarea|button)$/i,Y=/^h\d$/i,Z=/^[^{]+\{\s*\[native \w/,$=/^(?:#([\w-]+)|(\w+)|\.([\w-]+))$/,_=/[+~]/,aa=/'|\\/g,ba=new RegExp("\\\\([\\da-f]{1,6}"+L+"?|("+L+")|.)","ig"),ca=function(a,b,c){var d="0x"+b-65536;return d!==d||c?b:0>d?String.fromCharCode(d+65536):String.fromCharCode(d>>10|55296,1023&d|56320)},da=function(){m()};try{H.apply(E=I.call(v.childNodes),v.childNodes),E[v.childNodes.length].nodeType}catch(ea){H={apply:E.length?function(a,b){G.apply(a,I.call(b))}:function(a,b){var c=a.length,d=0;while(a[c++]=b[d++]);a.length=c-1}}}function fa(a,b,d,e){var f,h,j,k,l,o,r,s,w=b&&b.ownerDocument,x=b?b.nodeType:9;if(d=d||[],"string"!=typeof a||!a||1!==x&&9!==x&&11!==x)return d;if(!e&&((b?b.ownerDocument||b:v)!==n&&m(b),b=b||n,p)){if(11!==x&&(o=$.exec(a)))if(f=o[1]){if(9===x){if(!(j=b.getElementById(f)))return d;if(j.id===f)return d.push(j),d}else if(w&&(j=w.getElementById(f))&&t(b,j)&&j.id===f)return d.push(j),d}else{if(o[2])return H.apply(d,b.getElementsByTagName(a)),d;if((f=o[3])&&c.getElementsByClassName&&b.getElementsByClassName)return H.apply(d,b.getElementsByClassName(f)),d}if(c.qsa&&!A[a+" "]&&(!q||!q.test(a))){if(1!==x)w=b,s=a;else if("object"!==b.nodeName.toLowerCase()){(k=b.getAttribute("id"))?k=k.replace(aa,"\\$&"):b.setAttribute("id",k=u),r=g(a),h=r.length,l=V.test(k)?"#"+k:"[id='"+k+"']";while(h--)r[h]=l+" "+qa(r[h]);s=r.join(","),w=_.test(a)&&oa(b.parentNode)||b}if(s)try{return H.apply(d,w.querySelectorAll(s)),d}catch(y){}finally{k===u&&b.removeAttribute("id")}}}return i(a.replace(Q,"$1"),b,d,e)}function ga(){var a=[];function b(c,e){return a.push(c+" ")>d.cacheLength&&delete b[a.shift()],b[c+" "]=e}return b}function ha(a){return a[u]=!0,a}function ia(a){var b=n.createElement("div");try{return!!a(b)}catch(c){return!1}finally{b.parentNode&&b.parentNode.removeChild(b),b=null}}function ja(a,b){var c=a.split("|"),e=c.length;while(e--)d.attrHandle[c[e]]=b}function ka(a,b){var c=b&&a,d=c&&1===a.nodeType&&1===b.nodeType&&(~b.sourceIndex||C)-(~a.sourceIndex||C);if(d)return d;if(c)while(c=c.nextSibling)if(c===b)return-1;return a?1:-1}function la(a){return function(b){var c=b.nodeName.toLowerCase();return"input"===c&&b.type===a}}function ma(a){return function(b){var c=b.nodeName.toLowerCase();return("input"===c||"button"===c)&&b.type===a}}function na(a){return ha(function(b){return b=+b,ha(function(c,d){var e,f=a([],c.length,b),g=f.length;while(g--)c[e=f[g]]&&(c[e]=!(d[e]=c[e]))})})}function oa(a){return a&&"undefined"!=typeof a.getElementsByTagName&&a}c=fa.support={},f=fa.isXML=function(a){var b=a&&(a.ownerDocument||a).documentElement;return b?"HTML"!==b.nodeName:!1},m=fa.setDocument=function(a){var b,e,g=a?a.ownerDocument||a:v;return g!==n&&9===g.nodeType&&g.documentElement?(n=g,o=n.documentElement,p=!f(n),(e=n.defaultView)&&e.top!==e&&(e.addEventListener?e.addEventListener("unload",da,!1):e.attachEvent&&e.attachEvent("onunload",da)),c.attributes=ia(function(a){return a.className="i",!a.getAttribute("className")}),c.getElementsByTagName=ia(function(a){return a.appendChild(n.createComment("")),!a.getElementsByTagName("*").length}),c.getElementsByClassName=Z.test(n.getElementsByClassName),c.getById=ia(function(a){return o.appendChild(a).id=u,!n.getElementsByName||!n.getElementsByName(u).length}),c.getById?(d.find.ID=function(a,b){if("undefined"!=typeof b.getElementById&&p){var c=b.getElementById(a);return c?[c]:[]}},d.filter.ID=function(a){var b=a.replace(ba,ca);return function(a){return a.getAttribute("id")===b}}):(delete d.find.ID,d.filter.ID=function(a){var b=a.replace(ba,ca);return function(a){var c="undefined"!=typeof a.getAttributeNode&&a.getAttributeNode("id");return c&&c.value===b}}),d.find.TAG=c.getElementsByTagName?function(a,b){return"undefined"!=typeof b.getElementsByTagName?b.getElementsByTagName(a):c.qsa?b.querySelectorAll(a):void 0}:function(a,b){var c,d=[],e=0,f=b.getElementsByTagName(a);if("*"===a){while(c=f[e++])1===c.nodeType&&d.push(c);return d}return f},d.find.CLASS=c.getElementsByClassName&&function(a,b){return"undefined"!=typeof b.getElementsByClassName&&p?b.getElementsByClassName(a):void 0},r=[],q=[],(c.qsa=Z.test(n.querySelectorAll))&&(ia(function(a){o.appendChild(a).innerHTML="<a id='"+u+"'></a><select id='"+u+"-\r\\' msallowcapture=''><option selected=''></option></select>",a.querySelectorAll("[msallowcapture^='']").length&&q.push("[*^$]="+L+"*(?:''|\"\")"),a.querySelectorAll("[selected]").length||q.push("\\["+L+"*(?:value|"+K+")"),a.querySelectorAll("[id~="+u+"-]").length||q.push("~="),a.querySelectorAll(":checked").length||q.push(":checked"),a.querySelectorAll("a#"+u+"+*").length||q.push(".#.+[+~]")}),ia(function(a){var b=n.createElement("input");b.setAttribute("type","hidden"),a.appendChild(b).setAttribute("name","D"),a.querySelectorAll("[name=d]").length&&q.push("name"+L+"*[*^$|!~]?="),a.querySelectorAll(":enabled").length||q.push(":enabled",":disabled"),a.querySelectorAll("*,:x"),q.push(",.*:")})),(c.matchesSelector=Z.test(s=o.matches||o.webkitMatchesSelector||o.mozMatchesSelector||o.oMatchesSelector||o.msMatchesSelector))&&ia(function(a){c.disconnectedMatch=s.call(a,"div"),s.call(a,"[s!='']:x"),r.push("!=",O)}),q=q.length&&new RegExp(q.join("|")),r=r.length&&new RegExp(r.join("|")),b=Z.test(o.compareDocumentPosition),t=b||Z.test(o.contains)?function(a,b){var c=9===a.nodeType?a.documentElement:a,d=b&&b.parentNode;return a===d||!(!d||1!==d.nodeType||!(c.contains?c.contains(d):a.compareDocumentPosition&&16&a.compareDocumentPosition(d)))}:function(a,b){if(b)while(b=b.parentNode)if(b===a)return!0;return!1},B=b?function(a,b){if(a===b)return l=!0,0;var d=!a.compareDocumentPosition-!b.compareDocumentPosition;return d?d:(d=(a.ownerDocument||a)===(b.ownerDocument||b)?a.compareDocumentPosition(b):1,1&d||!c.sortDetached&&b.compareDocumentPosition(a)===d?a===n||a.ownerDocument===v&&t(v,a)?-1:b===n||b.ownerDocument===v&&t(v,b)?1:k?J(k,a)-J(k,b):0:4&d?-1:1)}:function(a,b){if(a===b)return l=!0,0;var c,d=0,e=a.parentNode,f=b.parentNode,g=[a],h=[b];if(!e||!f)return a===n?-1:b===n?1:e?-1:f?1:k?J(k,a)-J(k,b):0;if(e===f)return ka(a,b);c=a;while(c=c.parentNode)g.unshift(c);c=b;while(c=c.parentNode)h.unshift(c);while(g[d]===h[d])d++;return d?ka(g[d],h[d]):g[d]===v?-1:h[d]===v?1:0},n):n},fa.matches=function(a,b){return fa(a,null,null,b)},fa.matchesSelector=function(a,b){if((a.ownerDocument||a)!==n&&m(a),b=b.replace(T,"='$1']"),c.matchesSelector&&p&&!A[b+" "]&&(!r||!r.test(b))&&(!q||!q.test(b)))try{var d=s.call(a,b);if(d||c.disconnectedMatch||a.document&&11!==a.document.nodeType)return d}catch(e){}return fa(b,n,null,[a]).length>0},fa.contains=function(a,b){return(a.ownerDocument||a)!==n&&m(a),t(a,b)},fa.attr=function(a,b){(a.ownerDocument||a)!==n&&m(a);var e=d.attrHandle[b.toLowerCase()],f=e&&D.call(d.attrHandle,b.toLowerCase())?e(a,b,!p):void 0;return void 0!==f?f:c.attributes||!p?a.getAttribute(b):(f=a.getAttributeNode(b))&&f.specified?f.value:null},fa.error=function(a){throw new Error("Syntax error, unrecognized expression: "+a)},fa.uniqueSort=function(a){var b,d=[],e=0,f=0;if(l=!c.detectDuplicates,k=!c.sortStable&&a.slice(0),a.sort(B),l){while(b=a[f++])b===a[f]&&(e=d.push(f));while(e--)a.splice(d[e],1)}return k=null,a},e=fa.getText=function(a){var b,c="",d=0,f=a.nodeType;if(f){if(1===f||9===f||11===f){if("string"==typeof a.textContent)return a.textContent;for(a=a.firstChild;a;a=a.nextSibling)c+=e(a)}else if(3===f||4===f)return a.nodeValue}else while(b=a[d++])c+=e(b);return c},d=fa.selectors={cacheLength:50,createPseudo:ha,match:W,attrHandle:{},find:{},relative:{">":{dir:"parentNode",first:!0}," ":{dir:"parentNode"},"+":{dir:"previousSibling",first:!0},"~":{dir:"previousSibling"}},preFilter:{ATTR:function(a){return a[1]=a[1].replace(ba,ca),a[3]=(a[3]||a[4]||a[5]||"").replace(ba,ca),"~="===a[2]&&(a[3]=" "+a[3]+" "),a.slice(0,4)},CHILD:function(a){return a[1]=a[1].toLowerCase(),"nth"===a[1].slice(0,3)?(a[3]||fa.error(a[0]),a[4]=+(a[4]?a[5]+(a[6]||1):2*("even"===a[3]||"odd"===a[3])),a[5]=+(a[7]+a[8]||"odd"===a[3])):a[3]&&fa.error(a[0]),a},PSEUDO:function(a){var b,c=!a[6]&&a[2];return W.CHILD.test(a[0])?null:(a[3]?a[2]=a[4]||a[5]||"":c&&U.test(c)&&(b=g(c,!0))&&(b=c.indexOf(")",c.length-b)-c.length)&&(a[0]=a[0].slice(0,b),a[2]=c.slice(0,b)),a.slice(0,3))}},filter:{TAG:function(a){var b=a.replace(ba,ca).toLowerCase();return"*"===a?function(){return!0}:function(a){return a.nodeName&&a.nodeName.toLowerCase()===b}},CLASS:function(a){var b=y[a+" "];return b||(b=new RegExp("(^|"+L+")"+a+"("+L+"|$)"))&&y(a,function(a){return b.test("string"==typeof a.className&&a.className||"undefined"!=typeof a.getAttribute&&a.getAttribute("class")||"")})},ATTR:function(a,b,c){return function(d){var e=fa.attr(d,a);return null==e?"!="===b:b?(e+="","="===b?e===c:"!="===b?e!==c:"^="===b?c&&0===e.indexOf(c):"*="===b?c&&e.indexOf(c)>-1:"$="===b?c&&e.slice(-c.length)===c:"~="===b?(" "+e.replace(P," ")+" ").indexOf(c)>-1:"|="===b?e===c||e.slice(0,c.length+1)===c+"-":!1):!0}},CHILD:function(a,b,c,d,e){var f="nth"!==a.slice(0,3),g="last"!==a.slice(-4),h="of-type"===b;return 1===d&&0===e?function(a){return!!a.parentNode}:function(b,c,i){var j,k,l,m,n,o,p=f!==g?"nextSibling":"previousSibling",q=b.parentNode,r=h&&b.nodeName.toLowerCase(),s=!i&&!h,t=!1;if(q){if(f){while(p){m=b;while(m=m[p])if(h?m.nodeName.toLowerCase()===r:1===m.nodeType)return!1;o=p="only"===a&&!o&&"nextSibling"}return!0}if(o=[g?q.firstChild:q.lastChild],g&&s){m=q,l=m[u]||(m[u]={}),k=l[m.uniqueID]||(l[m.uniqueID]={}),j=k[a]||[],n=j[0]===w&&j[1],t=n&&j[2],m=n&&q.childNodes[n];while(m=++n&&m&&m[p]||(t=n=0)||o.pop())if(1===m.nodeType&&++t&&m===b){k[a]=[w,n,t];break}}else if(s&&(m=b,l=m[u]||(m[u]={}),k=l[m.uniqueID]||(l[m.uniqueID]={}),j=k[a]||[],n=j[0]===w&&j[1],t=n),t===!1)while(m=++n&&m&&m[p]||(t=n=0)||o.pop())if((h?m.nodeName.toLowerCase()===r:1===m.nodeType)&&++t&&(s&&(l=m[u]||(m[u]={}),k=l[m.uniqueID]||(l[m.uniqueID]={}),k[a]=[w,t]),m===b))break;return t-=e,t===d||t%d===0&&t/d>=0}}},PSEUDO:function(a,b){var c,e=d.pseudos[a]||d.setFilters[a.toLowerCase()]||fa.error("unsupported pseudo: "+a);return e[u]?e(b):e.length>1?(c=[a,a,"",b],d.setFilters.hasOwnProperty(a.toLowerCase())?ha(function(a,c){var d,f=e(a,b),g=f.length;while(g--)d=J(a,f[g]),a[d]=!(c[d]=f[g])}):function(a){return e(a,0,c)}):e}},pseudos:{not:ha(function(a){var b=[],c=[],d=h(a.replace(Q,"$1"));return d[u]?ha(function(a,b,c,e){var f,g=d(a,null,e,[]),h=a.length;while(h--)(f=g[h])&&(a[h]=!(b[h]=f))}):function(a,e,f){return b[0]=a,d(b,null,f,c),b[0]=null,!c.pop()}}),has:ha(function(a){return function(b){return fa(a,b).length>0}}),contains:ha(function(a){return a=a.replace(ba,ca),function(b){return(b.textContent||b.innerText||e(b)).indexOf(a)>-1}}),lang:ha(function(a){return V.test(a||"")||fa.error("unsupported lang: "+a),a=a.replace(ba,ca).toLowerCase(),function(b){var c;do if(c=p?b.lang:b.getAttribute("xml:lang")||b.getAttribute("lang"))return c=c.toLowerCase(),c===a||0===c.indexOf(a+"-");while((b=b.parentNode)&&1===b.nodeType);return!1}}),target:function(b){var c=a.location&&a.location.hash;return c&&c.slice(1)===b.id},root:function(a){return a===o},focus:function(a){return a===n.activeElement&&(!n.hasFocus||n.hasFocus())&&!!(a.type||a.href||~a.tabIndex)},enabled:function(a){return a.disabled===!1},disabled:function(a){return a.disabled===!0},checked:function(a){var b=a.nodeName.toLowerCase();return"input"===b&&!!a.checked||"option"===b&&!!a.selected},selected:function(a){return a.parentNode&&a.parentNode.selectedIndex,a.selected===!0},empty:function(a){for(a=a.firstChild;a;a=a.nextSibling)if(a.nodeType<6)return!1;return!0},parent:function(a){return!d.pseudos.empty(a)},header:function(a){return Y.test(a.nodeName)},input:function(a){return X.test(a.nodeName)},button:function(a){var b=a.nodeName.toLowerCase();return"input"===b&&"button"===a.type||"button"===b},text:function(a){var b;return"input"===a.nodeName.toLowerCase()&&"text"===a.type&&(null==(b=a.getAttribute("type"))||"text"===b.toLowerCase())},first:na(function(){return[0]}),last:na(function(a,b){return[b-1]}),eq:na(function(a,b,c){return[0>c?c+b:c]}),even:na(function(a,b){for(var c=0;b>c;c+=2)a.push(c);return a}),odd:na(function(a,b){for(var c=1;b>c;c+=2)a.push(c);return a}),lt:na(function(a,b,c){for(var d=0>c?c+b:c;--d>=0;)a.push(d);return a}),gt:na(function(a,b,c){for(var d=0>c?c+b:c;++d<b;)a.push(d);return a})}},d.pseudos.nth=d.pseudos.eq;for(b in{radio:!0,checkbox:!0,file:!0,password:!0,image:!0})d.pseudos[b]=la(b);for(b in{submit:!0,reset:!0})d.pseudos[b]=ma(b);function pa(){}pa.prototype=d.filters=d.pseudos,d.setFilters=new pa,g=fa.tokenize=function(a,b){var c,e,f,g,h,i,j,k=z[a+" "];if(k)return b?0:k.slice(0);h=a,i=[],j=d.preFilter;while(h){c&&!(e=R.exec(h))||(e&&(h=h.slice(e[0].length)||h),i.push(f=[])),c=!1,(e=S.exec(h))&&(c=e.shift(),f.push({value:c,type:e[0].replace(Q," ")}),h=h.slice(c.length));for(g in d.filter)!(e=W[g].exec(h))||j[g]&&!(e=j[g](e))||(c=e.shift(),f.push({value:c,type:g,matches:e}),h=h.slice(c.length));if(!c)break}return b?h.length:h?fa.error(a):z(a,i).slice(0)};function qa(a){for(var b=0,c=a.length,d="";c>b;b++)d+=a[b].value;return d}function ra(a,b,c){var d=b.dir,e=c&&"parentNode"===d,f=x++;return b.first?function(b,c,f){while(b=b[d])if(1===b.nodeType||e)return a(b,c,f)}:function(b,c,g){var h,i,j,k=[w,f];if(g){while(b=b[d])if((1===b.nodeType||e)&&a(b,c,g))return!0}else while(b=b[d])if(1===b.nodeType||e){if(j=b[u]||(b[u]={}),i=j[b.uniqueID]||(j[b.uniqueID]={}),(h=i[d])&&h[0]===w&&h[1]===f)return k[2]=h[2];if(i[d]=k,k[2]=a(b,c,g))return!0}}}function sa(a){return a.length>1?function(b,c,d){var e=a.length;while(e--)if(!a[e](b,c,d))return!1;return!0}:a[0]}function ta(a,b,c){for(var d=0,e=b.length;e>d;d++)fa(a,b[d],c);return c}function ua(a,b,c,d,e){for(var f,g=[],h=0,i=a.length,j=null!=b;i>h;h++)(f=a[h])&&(c&&!c(f,d,e)||(g.push(f),j&&b.push(h)));return g}function va(a,b,c,d,e,f){return d&&!d[u]&&(d=va(d)),e&&!e[u]&&(e=va(e,f)),ha(function(f,g,h,i){var j,k,l,m=[],n=[],o=g.length,p=f||ta(b||"*",h.nodeType?[h]:h,[]),q=!a||!f&&b?p:ua(p,m,a,h,i),r=c?e||(f?a:o||d)?[]:g:q;if(c&&c(q,r,h,i),d){j=ua(r,n),d(j,[],h,i),k=j.length;while(k--)(l=j[k])&&(r[n[k]]=!(q[n[k]]=l))}if(f){if(e||a){if(e){j=[],k=r.length;while(k--)(l=r[k])&&j.push(q[k]=l);e(null,r=[],j,i)}k=r.length;while(k--)(l=r[k])&&(j=e?J(f,l):m[k])>-1&&(f[j]=!(g[j]=l))}}else r=ua(r===g?r.splice(o,r.length):r),e?e(null,g,r,i):H.apply(g,r)})}function wa(a){for(var b,c,e,f=a.length,g=d.relative[a[0].type],h=g||d.relative[" "],i=g?1:0,k=ra(function(a){return a===b},h,!0),l=ra(function(a){return J(b,a)>-1},h,!0),m=[function(a,c,d){var e=!g&&(d||c!==j)||((b=c).nodeType?k(a,c,d):l(a,c,d));return b=null,e}];f>i;i++)if(c=d.relative[a[i].type])m=[ra(sa(m),c)];else{if(c=d.filter[a[i].type].apply(null,a[i].matches),c[u]){for(e=++i;f>e;e++)if(d.relative[a[e].type])break;return va(i>1&&sa(m),i>1&&qa(a.slice(0,i-1).concat({value:" "===a[i-2].type?"*":""})).replace(Q,"$1"),c,e>i&&wa(a.slice(i,e)),f>e&&wa(a=a.slice(e)),f>e&&qa(a))}m.push(c)}return sa(m)}function xa(a,b){var c=b.length>0,e=a.length>0,f=function(f,g,h,i,k){var l,o,q,r=0,s="0",t=f&&[],u=[],v=j,x=f||e&&d.find.TAG("*",k),y=w+=null==v?1:Math.random()||.1,z=x.length;for(k&&(j=g===n||g||k);s!==z&&null!=(l=x[s]);s++){if(e&&l){o=0,g||l.ownerDocument===n||(m(l),h=!p);while(q=a[o++])if(q(l,g||n,h)){i.push(l);break}k&&(w=y)}c&&((l=!q&&l)&&r--,f&&t.push(l))}if(r+=s,c&&s!==r){o=0;while(q=b[o++])q(t,u,g,h);if(f){if(r>0)while(s--)t[s]||u[s]||(u[s]=F.call(i));u=ua(u)}H.apply(i,u),k&&!f&&u.length>0&&r+b.length>1&&fa.uniqueSort(i)}return k&&(w=y,j=v),t};return c?ha(f):f}return h=fa.compile=function(a,b){var c,d=[],e=[],f=A[a+" "];if(!f){b||(b=g(a)),c=b.length;while(c--)f=wa(b[c]),f[u]?d.push(f):e.push(f);f=A(a,xa(e,d)),f.selector=a}return f},i=fa.select=function(a,b,e,f){var i,j,k,l,m,n="function"==typeof a&&a,o=!f&&g(a=n.selector||a);if(e=e||[],1===o.length){if(j=o[0]=o[0].slice(0),j.length>2&&"ID"===(k=j[0]).type&&c.getById&&9===b.nodeType&&p&&d.relative[j[1].type]){if(b=(d.find.ID(k.matches[0].replace(ba,ca),b)||[])[0],!b)return e;n&&(b=b.parentNode),a=a.slice(j.shift().value.length)}i=W.needsContext.test(a)?0:j.length;while(i--){if(k=j[i],d.relative[l=k.type])break;if((m=d.find[l])&&(f=m(k.matches[0].replace(ba,ca),_.test(j[0].type)&&oa(b.parentNode)||b))){if(j.splice(i,1),a=f.length&&qa(j),!a)return H.apply(e,f),e;break}}}return(n||h(a,o))(f,b,!p,e,!b||_.test(a)&&oa(b.parentNode)||b),e},c.sortStable=u.split("").sort(B).join("")===u,c.detectDuplicates=!!l,m(),c.sortDetached=ia(function(a){return 1&a.compareDocumentPosition(n.createElement("div"))}),ia(function(a){return a.innerHTML="<a href='#'></a>","#"===a.firstChild.getAttribute("href")})||ja("type|href|height|width",function(a,b,c){return c?void 0:a.getAttribute(b,"type"===b.toLowerCase()?1:2)}),c.attributes&&ia(function(a){return a.innerHTML="<input/>",a.firstChild.setAttribute("value",""),""===a.firstChild.getAttribute("value")})||ja("value",function(a,b,c){return c||"input"!==a.nodeName.toLowerCase()?void 0:a.defaultValue}),ia(function(a){return null==a.getAttribute("disabled")})||ja(K,function(a,b,c){var d;return c?void 0:a[b]===!0?b.toLowerCase():(d=a.getAttributeNode(b))&&d.specified?d.value:null}),fa}(a);n.find=t,n.expr=t.selectors,n.expr[":"]=n.expr.pseudos,n.uniqueSort=n.unique=t.uniqueSort,n.text=t.getText,n.isXMLDoc=t.isXML,n.contains=t.contains;var u=function(a,b,c){var d=[],e=void 0!==c;while((a=a[b])&&9!==a.nodeType)if(1===a.nodeType){if(e&&n(a).is(c))break;d.push(a)}return d},v=function(a,b){for(var c=[];a;a=a.nextSibling)1===a.nodeType&&a!==b&&c.push(a);return c},w=n.expr.match.needsContext,x=/^<([\w-]+)\s*\/?>(?:<\/\1>|)$/,y=/^.[^:#\[\.,]*$/;function z(a,b,c){if(n.isFunction(b))return n.grep(a,function(a,d){return!!b.call(a,d,a)!==c});if(b.nodeType)return n.grep(a,function(a){return a===b!==c});if("string"==typeof b){if(y.test(b))return n.filter(b,a,c);b=n.filter(b,a)}return n.grep(a,function(a){return h.call(b,a)>-1!==c})}n.filter=function(a,b,c){var d=b[0];return c&&(a=":not("+a+")"),1===b.length&&1===d.nodeType?n.find.matchesSelector(d,a)?[d]:[]:n.find.matches(a,n.grep(b,function(a){return 1===a.nodeType}))},n.fn.extend({find:function(a){var b,c=this.length,d=[],e=this;if("string"!=typeof a)return this.pushStack(n(a).filter(function(){for(b=0;c>b;b++)if(n.contains(e[b],this))return!0}));for(b=0;c>b;b++)n.find(a,e[b],d);return d=this.pushStack(c>1?n.unique(d):d),d.selector=this.selector?this.selector+" "+a:a,d},filter:function(a){return this.pushStack(z(this,a||[],!1))},not:function(a){return this.pushStack(z(this,a||[],!0))},is:function(a){return!!z(this,"string"==typeof a&&w.test(a)?n(a):a||[],!1).length}});var A,B=/^(?:\s*(<[\w\W]+>)[^>]*|#([\w-]*))$/,C=n.fn.init=function(a,b,c){var e,f;if(!a)return this;if(c=c||A,"string"==typeof a){if(e="<"===a[0]&&">"===a[a.length-1]&&a.length>=3?[null,a,null]:B.exec(a),!e||!e[1]&&b)return!b||b.jquery?(b||c).find(a):this.constructor(b).find(a);if(e[1]){if(b=b instanceof n?b[0]:b,n.merge(this,n.parseHTML(e[1],b&&b.nodeType?b.ownerDocument||b:d,!0)),x.test(e[1])&&n.isPlainObject(b))for(e in b)n.isFunction(this[e])?this[e](b[e]):this.attr(e,b[e]);return this}return f=d.getElementById(e[2]),f&&f.parentNode&&(this.length=1,this[0]=f),this.context=d,this.selector=a,this}return a.nodeType?(this.context=this[0]=a,this.length=1,this):n.isFunction(a)?void 0!==c.ready?c.ready(a):a(n):(void 0!==a.selector&&(this.selector=a.selector,this.context=a.context),n.makeArray(a,this))};C.prototype=n.fn,A=n(d);var D=/^(?:parents|prev(?:Until|All))/,E={children:!0,contents:!0,next:!0,prev:!0};n.fn.extend({has:function(a){var b=n(a,this),c=b.length;return this.filter(function(){for(var a=0;c>a;a++)if(n.contains(this,b[a]))return!0})},closest:function(a,b){for(var c,d=0,e=this.length,f=[],g=w.test(a)||"string"!=typeof a?n(a,b||this.context):0;e>d;d++)for(c=this[d];c&&c!==b;c=c.parentNode)if(c.nodeType<11&&(g?g.index(c)>-1:1===c.nodeType&&n.find.matchesSelector(c,a))){f.push(c);break}return this.pushStack(f.length>1?n.uniqueSort(f):f)},index:function(a){return a?"string"==typeof a?h.call(n(a),this[0]):h.call(this,a.jquery?a[0]:a):this[0]&&this[0].parentNode?this.first().prevAll().length:-1},add:function(a,b){return this.pushStack(n.uniqueSort(n.merge(this.get(),n(a,b))))},addBack:function(a){return this.add(null==a?this.prevObject:this.prevObject.filter(a))}});function F(a,b){while((a=a[b])&&1!==a.nodeType);return a}n.each({parent:function(a){var b=a.parentNode;return b&&11!==b.nodeType?b:null},parents:function(a){return u(a,"parentNode")},parentsUntil:function(a,b,c){return u(a,"parentNode",c)},next:function(a){return F(a,"nextSibling")},prev:function(a){return F(a,"previousSibling")},nextAll:function(a){return u(a,"nextSibling")},prevAll:function(a){return u(a,"previousSibling")},nextUntil:function(a,b,c){return u(a,"nextSibling",c)},prevUntil:function(a,b,c){return u(a,"previousSibling",c)},siblings:function(a){return v((a.parentNode||{}).firstChild,a)},children:function(a){return v(a.firstChild)},contents:function(a){return a.contentDocument||n.merge([],a.childNodes)}},function(a,b){n.fn[a]=function(c,d){var e=n.map(this,b,c);return"Until"!==a.slice(-5)&&(d=c),d&&"string"==typeof d&&(e=n.filter(d,e)),this.length>1&&(E[a]||n.uniqueSort(e),D.test(a)&&e.reverse()),this.pushStack(e)}});var G=/\S+/g;function H(a){var b={};return n.each(a.match(G)||[],function(a,c){b[c]=!0}),b}n.Callbacks=function(a){a="string"==typeof a?H(a):n.extend({},a);var b,c,d,e,f=[],g=[],h=-1,i=function(){for(e=a.once,d=b=!0;g.length;h=-1){c=g.shift();while(++h<f.length)f[h].apply(c[0],c[1])===!1&&a.stopOnFalse&&(h=f.length,c=!1)}a.memory||(c=!1),b=!1,e&&(f=c?[]:"")},j={add:function(){return f&&(c&&!b&&(h=f.length-1,g.push(c)),function d(b){n.each(b,function(b,c){n.isFunction(c)?a.unique&&j.has(c)||f.push(c):c&&c.length&&"string"!==n.type(c)&&d(c)})}(arguments),c&&!b&&i()),this},remove:function(){return n.each(arguments,function(a,b){var c;while((c=n.inArray(b,f,c))>-1)f.splice(c,1),h>=c&&h--}),this},has:function(a){return a?n.inArray(a,f)>-1:f.length>0},empty:function(){return f&&(f=[]),this},disable:function(){return e=g=[],f=c="",this},disabled:function(){return!f},lock:function(){return e=g=[],c||(f=c=""),this},locked:function(){return!!e},fireWith:function(a,c){return e||(c=c||[],c=[a,c.slice?c.slice():c],g.push(c),b||i()),this},fire:function(){return j.fireWith(this,arguments),this},fired:function(){return!!d}};return j},n.extend({Deferred:function(a){var b=[["resolve","done",n.Callbacks("once memory"),"resolved"],["reject","fail",n.Callbacks("once memory"),"rejected"],["notify","progress",n.Callbacks("memory")]],c="pending",d={state:function(){return c},always:function(){return e.done(arguments).fail(arguments),this},then:function(){var a=arguments;return n.Deferred(function(c){n.each(b,function(b,f){var g=n.isFunction(a[b])&&a[b];e[f[1]](function(){var a=g&&g.apply(this,arguments);a&&n.isFunction(a.promise)?a.promise().progress(c.notify).done(c.resolve).fail(c.reject):c[f[0]+"With"](this===d?c.promise():this,g?[a]:arguments)})}),a=null}).promise()},promise:function(a){return null!=a?n.extend(a,d):d}},e={};return d.pipe=d.then,n.each(b,function(a,f){var g=f[2],h=f[3];d[f[1]]=g.add,h&&g.add(function(){c=h},b[1^a][2].disable,b[2][2].lock),e[f[0]]=function(){return e[f[0]+"With"](this===e?d:this,arguments),this},e[f[0]+"With"]=g.fireWith}),d.promise(e),a&&a.call(e,e),e},when:function(a){var b=0,c=e.call(arguments),d=c.length,f=1!==d||a&&n.isFunction(a.promise)?d:0,g=1===f?a:n.Deferred(),h=function(a,b,c){return function(d){b[a]=this,c[a]=arguments.length>1?e.call(arguments):d,c===i?g.notifyWith(b,c):--f||g.resolveWith(b,c)}},i,j,k;if(d>1)for(i=new Array(d),j=new Array(d),k=new Array(d);d>b;b++)c[b]&&n.isFunction(c[b].promise)?c[b].promise().progress(h(b,j,i)).done(h(b,k,c)).fail(g.reject):--f;return f||g.resolveWith(k,c),g.promise()}});var I;n.fn.ready=function(a){return n.ready.promise().done(a),this},n.extend({isReady:!1,readyWait:1,holdReady:function(a){a?n.readyWait++:n.ready(!0)},ready:function(a){(a===!0?--n.readyWait:n.isReady)||(n.isReady=!0,a!==!0&&--n.readyWait>0||(I.resolveWith(d,[n]),n.fn.triggerHandler&&(n(d).triggerHandler("ready"),n(d).off("ready"))))}});function J(){d.removeEventListener("DOMContentLoaded",J),a.removeEventListener("load",J),n.ready()}n.ready.promise=function(b){return I||(I=n.Deferred(),"complete"===d.readyState||"loading"!==d.readyState&&!d.documentElement.doScroll?a.setTimeout(n.ready):(d.addEventListener("DOMContentLoaded",J),a.addEventListener("load",J))),I.promise(b)},n.ready.promise();var K=function(a,b,c,d,e,f,g){var h=0,i=a.length,j=null==c;if("object"===n.type(c)){e=!0;for(h in c)K(a,b,h,c[h],!0,f,g)}else if(void 0!==d&&(e=!0,n.isFunction(d)||(g=!0),j&&(g?(b.call(a,d),b=null):(j=b,b=function(a,b,c){return j.call(n(a),c)})),b))for(;i>h;h++)b(a[h],c,g?d:d.call(a[h],h,b(a[h],c)));return e?a:j?b.call(a):i?b(a[0],c):f},L=function(a){return 1===a.nodeType||9===a.nodeType||!+a.nodeType};function M(){this.expando=n.expando+M.uid++}M.uid=1,M.prototype={register:function(a,b){var c=b||{};return a.nodeType?a[this.expando]=c:Object.defineProperty(a,this.expando,{value:c,writable:!0,configurable:!0}),a[this.expando]},cache:function(a){if(!L(a))return{};var b=a[this.expando];return b||(b={},L(a)&&(a.nodeType?a[this.expando]=b:Object.defineProperty(a,this.expando,{value:b,configurable:!0}))),b},set:function(a,b,c){var d,e=this.cache(a);if("string"==typeof b)e[b]=c;else for(d in b)e[d]=b[d];return e},get:function(a,b){return void 0===b?this.cache(a):a[this.expando]&&a[this.expando][b]},access:function(a,b,c){var d;return void 0===b||b&&"string"==typeof b&&void 0===c?(d=this.get(a,b),void 0!==d?d:this.get(a,n.camelCase(b))):(this.set(a,b,c),void 0!==c?c:b)},remove:function(a,b){var c,d,e,f=a[this.expando];if(void 0!==f){if(void 0===b)this.register(a);else{n.isArray(b)?d=b.concat(b.map(n.camelCase)):(e=n.camelCase(b),b in f?d=[b,e]:(d=e,d=d in f?[d]:d.match(G)||[])),c=d.length;while(c--)delete f[d[c]]}(void 0===b||n.isEmptyObject(f))&&(a.nodeType?a[this.expando]=void 0:delete a[this.expando])}},hasData:function(a){var b=a[this.expando];return void 0!==b&&!n.isEmptyObject(b)}};var N=new M,O=new M,P=/^(?:\{[\w\W]*\}|\[[\w\W]*\])$/,Q=/[A-Z]/g;function R(a,b,c){var d;if(void 0===c&&1===a.nodeType)if(d="data-"+b.replace(Q,"-$&").toLowerCase(),c=a.getAttribute(d),"string"==typeof c){try{c="true"===c?!0:"false"===c?!1:"null"===c?null:+c+""===c?+c:P.test(c)?n.parseJSON(c):c;
}catch(e){}O.set(a,b,c)}else c=void 0;return c}n.extend({hasData:function(a){return O.hasData(a)||N.hasData(a)},data:function(a,b,c){return O.access(a,b,c)},removeData:function(a,b){O.remove(a,b)},_data:function(a,b,c){return N.access(a,b,c)},_removeData:function(a,b){N.remove(a,b)}}),n.fn.extend({data:function(a,b){var c,d,e,f=this[0],g=f&&f.attributes;if(void 0===a){if(this.length&&(e=O.get(f),1===f.nodeType&&!N.get(f,"hasDataAttrs"))){c=g.length;while(c--)g[c]&&(d=g[c].name,0===d.indexOf("data-")&&(d=n.camelCase(d.slice(5)),R(f,d,e[d])));N.set(f,"hasDataAttrs",!0)}return e}return"object"==typeof a?this.each(function(){O.set(this,a)}):K(this,function(b){var c,d;if(f&&void 0===b){if(c=O.get(f,a)||O.get(f,a.replace(Q,"-$&").toLowerCase()),void 0!==c)return c;if(d=n.camelCase(a),c=O.get(f,d),void 0!==c)return c;if(c=R(f,d,void 0),void 0!==c)return c}else d=n.camelCase(a),this.each(function(){var c=O.get(this,d);O.set(this,d,b),a.indexOf("-")>-1&&void 0!==c&&O.set(this,a,b)})},null,b,arguments.length>1,null,!0)},removeData:function(a){return this.each(function(){O.remove(this,a)})}}),n.extend({queue:function(a,b,c){var d;return a?(b=(b||"fx")+"queue",d=N.get(a,b),c&&(!d||n.isArray(c)?d=N.access(a,b,n.makeArray(c)):d.push(c)),d||[]):void 0},dequeue:function(a,b){b=b||"fx";var c=n.queue(a,b),d=c.length,e=c.shift(),f=n._queueHooks(a,b),g=function(){n.dequeue(a,b)};"inprogress"===e&&(e=c.shift(),d--),e&&("fx"===b&&c.unshift("inprogress"),delete f.stop,e.call(a,g,f)),!d&&f&&f.empty.fire()},_queueHooks:function(a,b){var c=b+"queueHooks";return N.get(a,c)||N.access(a,c,{empty:n.Callbacks("once memory").add(function(){N.remove(a,[b+"queue",c])})})}}),n.fn.extend({queue:function(a,b){var c=2;return"string"!=typeof a&&(b=a,a="fx",c--),arguments.length<c?n.queue(this[0],a):void 0===b?this:this.each(function(){var c=n.queue(this,a,b);n._queueHooks(this,a),"fx"===a&&"inprogress"!==c[0]&&n.dequeue(this,a)})},dequeue:function(a){return this.each(function(){n.dequeue(this,a)})},clearQueue:function(a){return this.queue(a||"fx",[])},promise:function(a,b){var c,d=1,e=n.Deferred(),f=this,g=this.length,h=function(){--d||e.resolveWith(f,[f])};"string"!=typeof a&&(b=a,a=void 0),a=a||"fx";while(g--)c=N.get(f[g],a+"queueHooks"),c&&c.empty&&(d++,c.empty.add(h));return h(),e.promise(b)}});var S=/[+-]?(?:\d*\.|)\d+(?:[eE][+-]?\d+|)/.source,T=new RegExp("^(?:([+-])=|)("+S+")([a-z%]*)$","i"),U=["Top","Right","Bottom","Left"],V=function(a,b){return a=b||a,"none"===n.css(a,"display")||!n.contains(a.ownerDocument,a)};function W(a,b,c,d){var e,f=1,g=20,h=d?function(){return d.cur()}:function(){return n.css(a,b,"")},i=h(),j=c&&c[3]||(n.cssNumber[b]?"":"px"),k=(n.cssNumber[b]||"px"!==j&&+i)&&T.exec(n.css(a,b));if(k&&k[3]!==j){j=j||k[3],c=c||[],k=+i||1;do f=f||".5",k/=f,n.style(a,b,k+j);while(f!==(f=h()/i)&&1!==f&&--g)}return c&&(k=+k||+i||0,e=c[1]?k+(c[1]+1)*c[2]:+c[2],d&&(d.unit=j,d.start=k,d.end=e)),e}var X=/^(?:checkbox|radio)$/i,Y=/<([\w:-]+)/,Z=/^$|\/(?:java|ecma)script/i,$={option:[1,"<select multiple='multiple'>","</select>"],thead:[1,"<table>","</table>"],col:[2,"<table><colgroup>","</colgroup></table>"],tr:[2,"<table><tbody>","</tbody></table>"],td:[3,"<table><tbody><tr>","</tr></tbody></table>"],_default:[0,"",""]};$.optgroup=$.option,$.tbody=$.tfoot=$.colgroup=$.caption=$.thead,$.th=$.td;function _(a,b){var c="undefined"!=typeof a.getElementsByTagName?a.getElementsByTagName(b||"*"):"undefined"!=typeof a.querySelectorAll?a.querySelectorAll(b||"*"):[];return void 0===b||b&&n.nodeName(a,b)?n.merge([a],c):c}function aa(a,b){for(var c=0,d=a.length;d>c;c++)N.set(a[c],"globalEval",!b||N.get(b[c],"globalEval"))}var ba=/<|&#?\w+;/;function ca(a,b,c,d,e){for(var f,g,h,i,j,k,l=b.createDocumentFragment(),m=[],o=0,p=a.length;p>o;o++)if(f=a[o],f||0===f)if("object"===n.type(f))n.merge(m,f.nodeType?[f]:f);else if(ba.test(f)){g=g||l.appendChild(b.createElement("div")),h=(Y.exec(f)||["",""])[1].toLowerCase(),i=$[h]||$._default,g.innerHTML=i[1]+n.htmlPrefilter(f)+i[2],k=i[0];while(k--)g=g.lastChild;n.merge(m,g.childNodes),g=l.firstChild,g.textContent=""}else m.push(b.createTextNode(f));l.textContent="",o=0;while(f=m[o++])if(d&&n.inArray(f,d)>-1)e&&e.push(f);else if(j=n.contains(f.ownerDocument,f),g=_(l.appendChild(f),"script"),j&&aa(g),c){k=0;while(f=g[k++])Z.test(f.type||"")&&c.push(f)}return l}!function(){var a=d.createDocumentFragment(),b=a.appendChild(d.createElement("div")),c=d.createElement("input");c.setAttribute("type","radio"),c.setAttribute("checked","checked"),c.setAttribute("name","t"),b.appendChild(c),l.checkClone=b.cloneNode(!0).cloneNode(!0).lastChild.checked,b.innerHTML="<textarea>x</textarea>",l.noCloneChecked=!!b.cloneNode(!0).lastChild.defaultValue}();var da=/^key/,ea=/^(?:mouse|pointer|contextmenu|drag|drop)|click/,fa=/^([^.]*)(?:\.(.+)|)/;function ga(){return!0}function ha(){return!1}function ia(){try{return d.activeElement}catch(a){}}function ja(a,b,c,d,e,f){var g,h;if("object"==typeof b){"string"!=typeof c&&(d=d||c,c=void 0);for(h in b)ja(a,h,c,d,b[h],f);return a}if(null==d&&null==e?(e=c,d=c=void 0):null==e&&("string"==typeof c?(e=d,d=void 0):(e=d,d=c,c=void 0)),e===!1)e=ha;else if(!e)return a;return 1===f&&(g=e,e=function(a){return n().off(a),g.apply(this,arguments)},e.guid=g.guid||(g.guid=n.guid++)),a.each(function(){n.event.add(this,b,e,d,c)})}n.event={global:{},add:function(a,b,c,d,e){var f,g,h,i,j,k,l,m,o,p,q,r=N.get(a);if(r){c.handler&&(f=c,c=f.handler,e=f.selector),c.guid||(c.guid=n.guid++),(i=r.events)||(i=r.events={}),(g=r.handle)||(g=r.handle=function(b){return"undefined"!=typeof n&&n.event.triggered!==b.type?n.event.dispatch.apply(a,arguments):void 0}),b=(b||"").match(G)||[""],j=b.length;while(j--)h=fa.exec(b[j])||[],o=q=h[1],p=(h[2]||"").split(".").sort(),o&&(l=n.event.special[o]||{},o=(e?l.delegateType:l.bindType)||o,l=n.event.special[o]||{},k=n.extend({type:o,origType:q,data:d,handler:c,guid:c.guid,selector:e,needsContext:e&&n.expr.match.needsContext.test(e),namespace:p.join(".")},f),(m=i[o])||(m=i[o]=[],m.delegateCount=0,l.setup&&l.setup.call(a,d,p,g)!==!1||a.addEventListener&&a.addEventListener(o,g)),l.add&&(l.add.call(a,k),k.handler.guid||(k.handler.guid=c.guid)),e?m.splice(m.delegateCount++,0,k):m.push(k),n.event.global[o]=!0)}},remove:function(a,b,c,d,e){var f,g,h,i,j,k,l,m,o,p,q,r=N.hasData(a)&&N.get(a);if(r&&(i=r.events)){b=(b||"").match(G)||[""],j=b.length;while(j--)if(h=fa.exec(b[j])||[],o=q=h[1],p=(h[2]||"").split(".").sort(),o){l=n.event.special[o]||{},o=(d?l.delegateType:l.bindType)||o,m=i[o]||[],h=h[2]&&new RegExp("(^|\\.)"+p.join("\\.(?:.*\\.|)")+"(\\.|$)"),g=f=m.length;while(f--)k=m[f],!e&&q!==k.origType||c&&c.guid!==k.guid||h&&!h.test(k.namespace)||d&&d!==k.selector&&("**"!==d||!k.selector)||(m.splice(f,1),k.selector&&m.delegateCount--,l.remove&&l.remove.call(a,k));g&&!m.length&&(l.teardown&&l.teardown.call(a,p,r.handle)!==!1||n.removeEvent(a,o,r.handle),delete i[o])}else for(o in i)n.event.remove(a,o+b[j],c,d,!0);n.isEmptyObject(i)&&N.remove(a,"handle events")}},dispatch:function(a){a=n.event.fix(a);var b,c,d,f,g,h=[],i=e.call(arguments),j=(N.get(this,"events")||{})[a.type]||[],k=n.event.special[a.type]||{};if(i[0]=a,a.delegateTarget=this,!k.preDispatch||k.preDispatch.call(this,a)!==!1){h=n.event.handlers.call(this,a,j),b=0;while((f=h[b++])&&!a.isPropagationStopped()){a.currentTarget=f.elem,c=0;while((g=f.handlers[c++])&&!a.isImmediatePropagationStopped())a.rnamespace&&!a.rnamespace.test(g.namespace)||(a.handleObj=g,a.data=g.data,d=((n.event.special[g.origType]||{}).handle||g.handler).apply(f.elem,i),void 0!==d&&(a.result=d)===!1&&(a.preventDefault(),a.stopPropagation()))}return k.postDispatch&&k.postDispatch.call(this,a),a.result}},handlers:function(a,b){var c,d,e,f,g=[],h=b.delegateCount,i=a.target;if(h&&i.nodeType&&("click"!==a.type||isNaN(a.button)||a.button<1))for(;i!==this;i=i.parentNode||this)if(1===i.nodeType&&(i.disabled!==!0||"click"!==a.type)){for(d=[],c=0;h>c;c++)f=b[c],e=f.selector+" ",void 0===d[e]&&(d[e]=f.needsContext?n(e,this).index(i)>-1:n.find(e,this,null,[i]).length),d[e]&&d.push(f);d.length&&g.push({elem:i,handlers:d})}return h<b.length&&g.push({elem:this,handlers:b.slice(h)}),g},props:"altKey bubbles cancelable ctrlKey currentTarget detail eventPhase metaKey relatedTarget shiftKey target timeStamp view which".split(" "),fixHooks:{},keyHooks:{props:"char charCode key keyCode".split(" "),filter:function(a,b){return null==a.which&&(a.which=null!=b.charCode?b.charCode:b.keyCode),a}},mouseHooks:{props:"button buttons clientX clientY offsetX offsetY pageX pageY screenX screenY toElement".split(" "),filter:function(a,b){var c,e,f,g=b.button;return null==a.pageX&&null!=b.clientX&&(c=a.target.ownerDocument||d,e=c.documentElement,f=c.body,a.pageX=b.clientX+(e&&e.scrollLeft||f&&f.scrollLeft||0)-(e&&e.clientLeft||f&&f.clientLeft||0),a.pageY=b.clientY+(e&&e.scrollTop||f&&f.scrollTop||0)-(e&&e.clientTop||f&&f.clientTop||0)),a.which||void 0===g||(a.which=1&g?1:2&g?3:4&g?2:0),a}},fix:function(a){if(a[n.expando])return a;var b,c,e,f=a.type,g=a,h=this.fixHooks[f];h||(this.fixHooks[f]=h=ea.test(f)?this.mouseHooks:da.test(f)?this.keyHooks:{}),e=h.props?this.props.concat(h.props):this.props,a=new n.Event(g),b=e.length;while(b--)c=e[b],a[c]=g[c];return a.target||(a.target=d),3===a.target.nodeType&&(a.target=a.target.parentNode),h.filter?h.filter(a,g):a},special:{load:{noBubble:!0},focus:{trigger:function(){return this!==ia()&&this.focus?(this.focus(),!1):void 0},delegateType:"focusin"},blur:{trigger:function(){return this===ia()&&this.blur?(this.blur(),!1):void 0},delegateType:"focusout"},click:{trigger:function(){return"checkbox"===this.type&&this.click&&n.nodeName(this,"input")?(this.click(),!1):void 0},_default:function(a){return n.nodeName(a.target,"a")}},beforeunload:{postDispatch:function(a){void 0!==a.result&&a.originalEvent&&(a.originalEvent.returnValue=a.result)}}}},n.removeEvent=function(a,b,c){a.removeEventListener&&a.removeEventListener(b,c)},n.Event=function(a,b){return this instanceof n.Event?(a&&a.type?(this.originalEvent=a,this.type=a.type,this.isDefaultPrevented=a.defaultPrevented||void 0===a.defaultPrevented&&a.returnValue===!1?ga:ha):this.type=a,b&&n.extend(this,b),this.timeStamp=a&&a.timeStamp||n.now(),void(this[n.expando]=!0)):new n.Event(a,b)},n.Event.prototype={constructor:n.Event,isDefaultPrevented:ha,isPropagationStopped:ha,isImmediatePropagationStopped:ha,isSimulated:!1,preventDefault:function(){var a=this.originalEvent;this.isDefaultPrevented=ga,a&&!this.isSimulated&&a.preventDefault()},stopPropagation:function(){var a=this.originalEvent;this.isPropagationStopped=ga,a&&!this.isSimulated&&a.stopPropagation()},stopImmediatePropagation:function(){var a=this.originalEvent;this.isImmediatePropagationStopped=ga,a&&!this.isSimulated&&a.stopImmediatePropagation(),this.stopPropagation()}},n.each({mouseenter:"mouseover",mouseleave:"mouseout",pointerenter:"pointerover",pointerleave:"pointerout"},function(a,b){n.event.special[a]={delegateType:b,bindType:b,handle:function(a){var c,d=this,e=a.relatedTarget,f=a.handleObj;return e&&(e===d||n.contains(d,e))||(a.type=f.origType,c=f.handler.apply(this,arguments),a.type=b),c}}}),n.fn.extend({on:function(a,b,c,d){return ja(this,a,b,c,d)},one:function(a,b,c,d){return ja(this,a,b,c,d,1)},off:function(a,b,c){var d,e;if(a&&a.preventDefault&&a.handleObj)return d=a.handleObj,n(a.delegateTarget).off(d.namespace?d.origType+"."+d.namespace:d.origType,d.selector,d.handler),this;if("object"==typeof a){for(e in a)this.off(e,b,a[e]);return this}return b!==!1&&"function"!=typeof b||(c=b,b=void 0),c===!1&&(c=ha),this.each(function(){n.event.remove(this,a,c,b)})}});var ka=/<(?!area|br|col|embed|hr|img|input|link|meta|param)(([\w:-]+)[^>]*)\/>/gi,la=/<script|<style|<link/i,ma=/checked\s*(?:[^=]|=\s*.checked.)/i,na=/^true\/(.*)/,oa=/^\s*<!(?:\[CDATA\[|--)|(?:\]\]|--)>\s*$/g;function pa(a,b){return n.nodeName(a,"table")&&n.nodeName(11!==b.nodeType?b:b.firstChild,"tr")?a.getElementsByTagName("tbody")[0]||a.appendChild(a.ownerDocument.createElement("tbody")):a}function qa(a){return a.type=(null!==a.getAttribute("type"))+"/"+a.type,a}function ra(a){var b=na.exec(a.type);return b?a.type=b[1]:a.removeAttribute("type"),a}function sa(a,b){var c,d,e,f,g,h,i,j;if(1===b.nodeType){if(N.hasData(a)&&(f=N.access(a),g=N.set(b,f),j=f.events)){delete g.handle,g.events={};for(e in j)for(c=0,d=j[e].length;d>c;c++)n.event.add(b,e,j[e][c])}O.hasData(a)&&(h=O.access(a),i=n.extend({},h),O.set(b,i))}}function ta(a,b){var c=b.nodeName.toLowerCase();"input"===c&&X.test(a.type)?b.checked=a.checked:"input"!==c&&"textarea"!==c||(b.defaultValue=a.defaultValue)}function ua(a,b,c,d){b=f.apply([],b);var e,g,h,i,j,k,m=0,o=a.length,p=o-1,q=b[0],r=n.isFunction(q);if(r||o>1&&"string"==typeof q&&!l.checkClone&&ma.test(q))return a.each(function(e){var f=a.eq(e);r&&(b[0]=q.call(this,e,f.html())),ua(f,b,c,d)});if(o&&(e=ca(b,a[0].ownerDocument,!1,a,d),g=e.firstChild,1===e.childNodes.length&&(e=g),g||d)){for(h=n.map(_(e,"script"),qa),i=h.length;o>m;m++)j=e,m!==p&&(j=n.clone(j,!0,!0),i&&n.merge(h,_(j,"script"))),c.call(a[m],j,m);if(i)for(k=h[h.length-1].ownerDocument,n.map(h,ra),m=0;i>m;m++)j=h[m],Z.test(j.type||"")&&!N.access(j,"globalEval")&&n.contains(k,j)&&(j.src?n._evalUrl&&n._evalUrl(j.src):n.globalEval(j.textContent.replace(oa,"")))}return a}function va(a,b,c){for(var d,e=b?n.filter(b,a):a,f=0;null!=(d=e[f]);f++)c||1!==d.nodeType||n.cleanData(_(d)),d.parentNode&&(c&&n.contains(d.ownerDocument,d)&&aa(_(d,"script")),d.parentNode.removeChild(d));return a}n.extend({htmlPrefilter:function(a){return a.replace(ka,"<$1></$2>")},clone:function(a,b,c){var d,e,f,g,h=a.cloneNode(!0),i=n.contains(a.ownerDocument,a);if(!(l.noCloneChecked||1!==a.nodeType&&11!==a.nodeType||n.isXMLDoc(a)))for(g=_(h),f=_(a),d=0,e=f.length;e>d;d++)ta(f[d],g[d]);if(b)if(c)for(f=f||_(a),g=g||_(h),d=0,e=f.length;e>d;d++)sa(f[d],g[d]);else sa(a,h);return g=_(h,"script"),g.length>0&&aa(g,!i&&_(a,"script")),h},cleanData:function(a){for(var b,c,d,e=n.event.special,f=0;void 0!==(c=a[f]);f++)if(L(c)){if(b=c[N.expando]){if(b.events)for(d in b.events)e[d]?n.event.remove(c,d):n.removeEvent(c,d,b.handle);c[N.expando]=void 0}c[O.expando]&&(c[O.expando]=void 0)}}}),n.fn.extend({domManip:ua,detach:function(a){return va(this,a,!0)},remove:function(a){return va(this,a)},text:function(a){return K(this,function(a){return void 0===a?n.text(this):this.empty().each(function(){1!==this.nodeType&&11!==this.nodeType&&9!==this.nodeType||(this.textContent=a)})},null,a,arguments.length)},append:function(){return ua(this,arguments,function(a){if(1===this.nodeType||11===this.nodeType||9===this.nodeType){var b=pa(this,a);b.appendChild(a)}})},prepend:function(){return ua(this,arguments,function(a){if(1===this.nodeType||11===this.nodeType||9===this.nodeType){var b=pa(this,a);b.insertBefore(a,b.firstChild)}})},before:function(){return ua(this,arguments,function(a){this.parentNode&&this.parentNode.insertBefore(a,this)})},after:function(){return ua(this,arguments,function(a){this.parentNode&&this.parentNode.insertBefore(a,this.nextSibling)})},empty:function(){for(var a,b=0;null!=(a=this[b]);b++)1===a.nodeType&&(n.cleanData(_(a,!1)),a.textContent="");return this},clone:function(a,b){return a=null==a?!1:a,b=null==b?a:b,this.map(function(){return n.clone(this,a,b)})},html:function(a){return K(this,function(a){var b=this[0]||{},c=0,d=this.length;if(void 0===a&&1===b.nodeType)return b.innerHTML;if("string"==typeof a&&!la.test(a)&&!$[(Y.exec(a)||["",""])[1].toLowerCase()]){a=n.htmlPrefilter(a);try{for(;d>c;c++)b=this[c]||{},1===b.nodeType&&(n.cleanData(_(b,!1)),b.innerHTML=a);b=0}catch(e){}}b&&this.empty().append(a)},null,a,arguments.length)},replaceWith:function(){var a=[];return ua(this,arguments,function(b){var c=this.parentNode;n.inArray(this,a)<0&&(n.cleanData(_(this)),c&&c.replaceChild(b,this))},a)}}),n.each({appendTo:"append",prependTo:"prepend",insertBefore:"before",insertAfter:"after",replaceAll:"replaceWith"},function(a,b){n.fn[a]=function(a){for(var c,d=[],e=n(a),f=e.length-1,h=0;f>=h;h++)c=h===f?this:this.clone(!0),n(e[h])[b](c),g.apply(d,c.get());return this.pushStack(d)}});var wa,xa={HTML:"block",BODY:"block"};function ya(a,b){var c=n(b.createElement(a)).appendTo(b.body),d=n.css(c[0],"display");return c.detach(),d}function za(a){var b=d,c=xa[a];return c||(c=ya(a,b),"none"!==c&&c||(wa=(wa||n("<iframe frameborder='0' width='0' height='0'/>")).appendTo(b.documentElement),b=wa[0].contentDocument,b.write(),b.close(),c=ya(a,b),wa.detach()),xa[a]=c),c}var Aa=/^margin/,Ba=new RegExp("^("+S+")(?!px)[a-z%]+$","i"),Ca=function(b){var c=b.ownerDocument.defaultView;return c&&c.opener||(c=a),c.getComputedStyle(b)},Da=function(a,b,c,d){var e,f,g={};for(f in b)g[f]=a.style[f],a.style[f]=b[f];e=c.apply(a,d||[]);for(f in b)a.style[f]=g[f];return e},Ea=d.documentElement;!function(){var b,c,e,f,g=d.createElement("div"),h=d.createElement("div");if(h.style){h.style.backgroundClip="content-box",h.cloneNode(!0).style.backgroundClip="",l.clearCloneStyle="content-box"===h.style.backgroundClip,g.style.cssText="border:0;width:8px;height:0;top:0;left:-9999px;padding:0;margin-top:1px;position:absolute",g.appendChild(h);function i(){h.style.cssText="-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box;position:relative;display:block;margin:auto;border:1px;padding:1px;top:1%;width:50%",h.innerHTML="",Ea.appendChild(g);var d=a.getComputedStyle(h);b="1%"!==d.top,f="2px"===d.marginLeft,c="4px"===d.width,h.style.marginRight="50%",e="4px"===d.marginRight,Ea.removeChild(g)}n.extend(l,{pixelPosition:function(){return i(),b},boxSizingReliable:function(){return null==c&&i(),c},pixelMarginRight:function(){return null==c&&i(),e},reliableMarginLeft:function(){return null==c&&i(),f},reliableMarginRight:function(){var b,c=h.appendChild(d.createElement("div"));return c.style.cssText=h.style.cssText="-webkit-box-sizing:content-box;box-sizing:content-box;display:block;margin:0;border:0;padding:0",c.style.marginRight=c.style.width="0",h.style.width="1px",Ea.appendChild(g),b=!parseFloat(a.getComputedStyle(c).marginRight),Ea.removeChild(g),h.removeChild(c),b}})}}();function Fa(a,b,c){var d,e,f,g,h=a.style;return c=c||Ca(a),g=c?c.getPropertyValue(b)||c[b]:void 0,""!==g&&void 0!==g||n.contains(a.ownerDocument,a)||(g=n.style(a,b)),c&&!l.pixelMarginRight()&&Ba.test(g)&&Aa.test(b)&&(d=h.width,e=h.minWidth,f=h.maxWidth,h.minWidth=h.maxWidth=h.width=g,g=c.width,h.width=d,h.minWidth=e,h.maxWidth=f),void 0!==g?g+"":g}function Ga(a,b){return{get:function(){return a()?void delete this.get:(this.get=b).apply(this,arguments)}}}var Ha=/^(none|table(?!-c[ea]).+)/,Ia={position:"absolute",visibility:"hidden",display:"block"},Ja={letterSpacing:"0",fontWeight:"400"},Ka=["Webkit","O","Moz","ms"],La=d.createElement("div").style;function Ma(a){if(a in La)return a;var b=a[0].toUpperCase()+a.slice(1),c=Ka.length;while(c--)if(a=Ka[c]+b,a in La)return a}function Na(a,b,c){var d=T.exec(b);return d?Math.max(0,d[2]-(c||0))+(d[3]||"px"):b}function Oa(a,b,c,d,e){for(var f=c===(d?"border":"content")?4:"width"===b?1:0,g=0;4>f;f+=2)"margin"===c&&(g+=n.css(a,c+U[f],!0,e)),d?("content"===c&&(g-=n.css(a,"padding"+U[f],!0,e)),"margin"!==c&&(g-=n.css(a,"border"+U[f]+"Width",!0,e))):(g+=n.css(a,"padding"+U[f],!0,e),"padding"!==c&&(g+=n.css(a,"border"+U[f]+"Width",!0,e)));return g}function Pa(a,b,c){var d=!0,e="width"===b?a.offsetWidth:a.offsetHeight,f=Ca(a),g="border-box"===n.css(a,"boxSizing",!1,f);if(0>=e||null==e){if(e=Fa(a,b,f),(0>e||null==e)&&(e=a.style[b]),Ba.test(e))return e;d=g&&(l.boxSizingReliable()||e===a.style[b]),e=parseFloat(e)||0}return e+Oa(a,b,c||(g?"border":"content"),d,f)+"px"}function Qa(a,b){for(var c,d,e,f=[],g=0,h=a.length;h>g;g++)d=a[g],d.style&&(f[g]=N.get(d,"olddisplay"),c=d.style.display,b?(f[g]||"none"!==c||(d.style.display=""),""===d.style.display&&V(d)&&(f[g]=N.access(d,"olddisplay",za(d.nodeName)))):(e=V(d),"none"===c&&e||N.set(d,"olddisplay",e?c:n.css(d,"display"))));for(g=0;h>g;g++)d=a[g],d.style&&(b&&"none"!==d.style.display&&""!==d.style.display||(d.style.display=b?f[g]||"":"none"));return a}n.extend({cssHooks:{opacity:{get:function(a,b){if(b){var c=Fa(a,"opacity");return""===c?"1":c}}}},cssNumber:{animationIterationCount:!0,columnCount:!0,fillOpacity:!0,flexGrow:!0,flexShrink:!0,fontWeight:!0,lineHeight:!0,opacity:!0,order:!0,orphans:!0,widows:!0,zIndex:!0,zoom:!0},cssProps:{"float":"cssFloat"},style:function(a,b,c,d){if(a&&3!==a.nodeType&&8!==a.nodeType&&a.style){var e,f,g,h=n.camelCase(b),i=a.style;return b=n.cssProps[h]||(n.cssProps[h]=Ma(h)||h),g=n.cssHooks[b]||n.cssHooks[h],void 0===c?g&&"get"in g&&void 0!==(e=g.get(a,!1,d))?e:i[b]:(f=typeof c,"string"===f&&(e=T.exec(c))&&e[1]&&(c=W(a,b,e),f="number"),null!=c&&c===c&&("number"===f&&(c+=e&&e[3]||(n.cssNumber[h]?"":"px")),l.clearCloneStyle||""!==c||0!==b.indexOf("background")||(i[b]="inherit"),g&&"set"in g&&void 0===(c=g.set(a,c,d))||(i[b]=c)),void 0)}},css:function(a,b,c,d){var e,f,g,h=n.camelCase(b);return b=n.cssProps[h]||(n.cssProps[h]=Ma(h)||h),g=n.cssHooks[b]||n.cssHooks[h],g&&"get"in g&&(e=g.get(a,!0,c)),void 0===e&&(e=Fa(a,b,d)),"normal"===e&&b in Ja&&(e=Ja[b]),""===c||c?(f=parseFloat(e),c===!0||isFinite(f)?f||0:e):e}}),n.each(["height","width"],function(a,b){n.cssHooks[b]={get:function(a,c,d){return c?Ha.test(n.css(a,"display"))&&0===a.offsetWidth?Da(a,Ia,function(){return Pa(a,b,d)}):Pa(a,b,d):void 0},set:function(a,c,d){var e,f=d&&Ca(a),g=d&&Oa(a,b,d,"border-box"===n.css(a,"boxSizing",!1,f),f);return g&&(e=T.exec(c))&&"px"!==(e[3]||"px")&&(a.style[b]=c,c=n.css(a,b)),Na(a,c,g)}}}),n.cssHooks.marginLeft=Ga(l.reliableMarginLeft,function(a,b){return b?(parseFloat(Fa(a,"marginLeft"))||a.getBoundingClientRect().left-Da(a,{marginLeft:0},function(){return a.getBoundingClientRect().left}))+"px":void 0}),n.cssHooks.marginRight=Ga(l.reliableMarginRight,function(a,b){return b?Da(a,{display:"inline-block"},Fa,[a,"marginRight"]):void 0}),n.each({margin:"",padding:"",border:"Width"},function(a,b){n.cssHooks[a+b]={expand:function(c){for(var d=0,e={},f="string"==typeof c?c.split(" "):[c];4>d;d++)e[a+U[d]+b]=f[d]||f[d-2]||f[0];return e}},Aa.test(a)||(n.cssHooks[a+b].set=Na)}),n.fn.extend({css:function(a,b){return K(this,function(a,b,c){var d,e,f={},g=0;if(n.isArray(b)){for(d=Ca(a),e=b.length;e>g;g++)f[b[g]]=n.css(a,b[g],!1,d);return f}return void 0!==c?n.style(a,b,c):n.css(a,b)},a,b,arguments.length>1)},show:function(){return Qa(this,!0)},hide:function(){return Qa(this)},toggle:function(a){return"boolean"==typeof a?a?this.show():this.hide():this.each(function(){V(this)?n(this).show():n(this).hide()})}});function Ra(a,b,c,d,e){return new Ra.prototype.init(a,b,c,d,e)}n.Tween=Ra,Ra.prototype={constructor:Ra,init:function(a,b,c,d,e,f){this.elem=a,this.prop=c,this.easing=e||n.easing._default,this.options=b,this.start=this.now=this.cur(),this.end=d,this.unit=f||(n.cssNumber[c]?"":"px")},cur:function(){var a=Ra.propHooks[this.prop];return a&&a.get?a.get(this):Ra.propHooks._default.get(this)},run:function(a){var b,c=Ra.propHooks[this.prop];return this.options.duration?this.pos=b=n.easing[this.easing](a,this.options.duration*a,0,1,this.options.duration):this.pos=b=a,this.now=(this.end-this.start)*b+this.start,this.options.step&&this.options.step.call(this.elem,this.now,this),c&&c.set?c.set(this):Ra.propHooks._default.set(this),this}},Ra.prototype.init.prototype=Ra.prototype,Ra.propHooks={_default:{get:function(a){var b;return 1!==a.elem.nodeType||null!=a.elem[a.prop]&&null==a.elem.style[a.prop]?a.elem[a.prop]:(b=n.css(a.elem,a.prop,""),b&&"auto"!==b?b:0)},set:function(a){n.fx.step[a.prop]?n.fx.step[a.prop](a):1!==a.elem.nodeType||null==a.elem.style[n.cssProps[a.prop]]&&!n.cssHooks[a.prop]?a.elem[a.prop]=a.now:n.style(a.elem,a.prop,a.now+a.unit)}}},Ra.propHooks.scrollTop=Ra.propHooks.scrollLeft={set:function(a){a.elem.nodeType&&a.elem.parentNode&&(a.elem[a.prop]=a.now)}},n.easing={linear:function(a){return a},swing:function(a){return.5-Math.cos(a*Math.PI)/2},_default:"swing"},n.fx=Ra.prototype.init,n.fx.step={};var Sa,Ta,Ua=/^(?:toggle|show|hide)$/,Va=/queueHooks$/;function Wa(){return a.setTimeout(function(){Sa=void 0}),Sa=n.now()}function Xa(a,b){var c,d=0,e={height:a};for(b=b?1:0;4>d;d+=2-b)c=U[d],e["margin"+c]=e["padding"+c]=a;return b&&(e.opacity=e.width=a),e}function Ya(a,b,c){for(var d,e=(_a.tweeners[b]||[]).concat(_a.tweeners["*"]),f=0,g=e.length;g>f;f++)if(d=e[f].call(c,b,a))return d}function Za(a,b,c){var d,e,f,g,h,i,j,k,l=this,m={},o=a.style,p=a.nodeType&&V(a),q=N.get(a,"fxshow");c.queue||(h=n._queueHooks(a,"fx"),null==h.unqueued&&(h.unqueued=0,i=h.empty.fire,h.empty.fire=function(){h.unqueued||i()}),h.unqueued++,l.always(function(){l.always(function(){h.unqueued--,n.queue(a,"fx").length||h.empty.fire()})})),1===a.nodeType&&("height"in b||"width"in b)&&(c.overflow=[o.overflow,o.overflowX,o.overflowY],j=n.css(a,"display"),k="none"===j?N.get(a,"olddisplay")||za(a.nodeName):j,"inline"===k&&"none"===n.css(a,"float")&&(o.display="inline-block")),c.overflow&&(o.overflow="hidden",l.always(function(){o.overflow=c.overflow[0],o.overflowX=c.overflow[1],o.overflowY=c.overflow[2]}));for(d in b)if(e=b[d],Ua.exec(e)){if(delete b[d],f=f||"toggle"===e,e===(p?"hide":"show")){if("show"!==e||!q||void 0===q[d])continue;p=!0}m[d]=q&&q[d]||n.style(a,d)}else j=void 0;if(n.isEmptyObject(m))"inline"===("none"===j?za(a.nodeName):j)&&(o.display=j);else{q?"hidden"in q&&(p=q.hidden):q=N.access(a,"fxshow",{}),f&&(q.hidden=!p),p?n(a).show():l.done(function(){n(a).hide()}),l.done(function(){var b;N.remove(a,"fxshow");for(b in m)n.style(a,b,m[b])});for(d in m)g=Ya(p?q[d]:0,d,l),d in q||(q[d]=g.start,p&&(g.end=g.start,g.start="width"===d||"height"===d?1:0))}}function $a(a,b){var c,d,e,f,g;for(c in a)if(d=n.camelCase(c),e=b[d],f=a[c],n.isArray(f)&&(e=f[1],f=a[c]=f[0]),c!==d&&(a[d]=f,delete a[c]),g=n.cssHooks[d],g&&"expand"in g){f=g.expand(f),delete a[d];for(c in f)c in a||(a[c]=f[c],b[c]=e)}else b[d]=e}function _a(a,b,c){var d,e,f=0,g=_a.prefilters.length,h=n.Deferred().always(function(){delete i.elem}),i=function(){if(e)return!1;for(var b=Sa||Wa(),c=Math.max(0,j.startTime+j.duration-b),d=c/j.duration||0,f=1-d,g=0,i=j.tweens.length;i>g;g++)j.tweens[g].run(f);return h.notifyWith(a,[j,f,c]),1>f&&i?c:(h.resolveWith(a,[j]),!1)},j=h.promise({elem:a,props:n.extend({},b),opts:n.extend(!0,{specialEasing:{},easing:n.easing._default},c),originalProperties:b,originalOptions:c,startTime:Sa||Wa(),duration:c.duration,tweens:[],createTween:function(b,c){var d=n.Tween(a,j.opts,b,c,j.opts.specialEasing[b]||j.opts.easing);return j.tweens.push(d),d},stop:function(b){var c=0,d=b?j.tweens.length:0;if(e)return this;for(e=!0;d>c;c++)j.tweens[c].run(1);return b?(h.notifyWith(a,[j,1,0]),h.resolveWith(a,[j,b])):h.rejectWith(a,[j,b]),this}}),k=j.props;for($a(k,j.opts.specialEasing);g>f;f++)if(d=_a.prefilters[f].call(j,a,k,j.opts))return n.isFunction(d.stop)&&(n._queueHooks(j.elem,j.opts.queue).stop=n.proxy(d.stop,d)),d;return n.map(k,Ya,j),n.isFunction(j.opts.start)&&j.opts.start.call(a,j),n.fx.timer(n.extend(i,{elem:a,anim:j,queue:j.opts.queue})),j.progress(j.opts.progress).done(j.opts.done,j.opts.complete).fail(j.opts.fail).always(j.opts.always)}n.Animation=n.extend(_a,{tweeners:{"*":[function(a,b){var c=this.createTween(a,b);return W(c.elem,a,T.exec(b),c),c}]},tweener:function(a,b){n.isFunction(a)?(b=a,a=["*"]):a=a.match(G);for(var c,d=0,e=a.length;e>d;d++)c=a[d],_a.tweeners[c]=_a.tweeners[c]||[],_a.tweeners[c].unshift(b)},prefilters:[Za],prefilter:function(a,b){b?_a.prefilters.unshift(a):_a.prefilters.push(a)}}),n.speed=function(a,b,c){var d=a&&"object"==typeof a?n.extend({},a):{complete:c||!c&&b||n.isFunction(a)&&a,duration:a,easing:c&&b||b&&!n.isFunction(b)&&b};return d.duration=n.fx.off?0:"number"==typeof d.duration?d.duration:d.duration in n.fx.speeds?n.fx.speeds[d.duration]:n.fx.speeds._default,null!=d.queue&&d.queue!==!0||(d.queue="fx"),d.old=d.complete,d.complete=function(){n.isFunction(d.old)&&d.old.call(this),d.queue&&n.dequeue(this,d.queue)},d},n.fn.extend({fadeTo:function(a,b,c,d){return this.filter(V).css("opacity",0).show().end().animate({opacity:b},a,c,d)},animate:function(a,b,c,d){var e=n.isEmptyObject(a),f=n.speed(b,c,d),g=function(){var b=_a(this,n.extend({},a),f);(e||N.get(this,"finish"))&&b.stop(!0)};return g.finish=g,e||f.queue===!1?this.each(g):this.queue(f.queue,g)},stop:function(a,b,c){var d=function(a){var b=a.stop;delete a.stop,b(c)};return"string"!=typeof a&&(c=b,b=a,a=void 0),b&&a!==!1&&this.queue(a||"fx",[]),this.each(function(){var b=!0,e=null!=a&&a+"queueHooks",f=n.timers,g=N.get(this);if(e)g[e]&&g[e].stop&&d(g[e]);else for(e in g)g[e]&&g[e].stop&&Va.test(e)&&d(g[e]);for(e=f.length;e--;)f[e].elem!==this||null!=a&&f[e].queue!==a||(f[e].anim.stop(c),b=!1,f.splice(e,1));!b&&c||n.dequeue(this,a)})},finish:function(a){return a!==!1&&(a=a||"fx"),this.each(function(){var b,c=N.get(this),d=c[a+"queue"],e=c[a+"queueHooks"],f=n.timers,g=d?d.length:0;for(c.finish=!0,n.queue(this,a,[]),e&&e.stop&&e.stop.call(this,!0),b=f.length;b--;)f[b].elem===this&&f[b].queue===a&&(f[b].anim.stop(!0),f.splice(b,1));for(b=0;g>b;b++)d[b]&&d[b].finish&&d[b].finish.call(this);delete c.finish})}}),n.each(["toggle","show","hide"],function(a,b){var c=n.fn[b];n.fn[b]=function(a,d,e){return null==a||"boolean"==typeof a?c.apply(this,arguments):this.animate(Xa(b,!0),a,d,e)}}),n.each({slideDown:Xa("show"),slideUp:Xa("hide"),slideToggle:Xa("toggle"),fadeIn:{opacity:"show"},fadeOut:{opacity:"hide"},fadeToggle:{opacity:"toggle"}},function(a,b){n.fn[a]=function(a,c,d){return this.animate(b,a,c,d)}}),n.timers=[],n.fx.tick=function(){var a,b=0,c=n.timers;for(Sa=n.now();b<c.length;b++)a=c[b],a()||c[b]!==a||c.splice(b--,1);c.length||n.fx.stop(),Sa=void 0},n.fx.timer=function(a){n.timers.push(a),a()?n.fx.start():n.timers.pop()},n.fx.interval=13,n.fx.start=function(){Ta||(Ta=a.setInterval(n.fx.tick,n.fx.interval))},n.fx.stop=function(){a.clearInterval(Ta),Ta=null},n.fx.speeds={slow:600,fast:200,_default:400},n.fn.delay=function(b,c){return b=n.fx?n.fx.speeds[b]||b:b,c=c||"fx",this.queue(c,function(c,d){var e=a.setTimeout(c,b);d.stop=function(){a.clearTimeout(e)}})},function(){var a=d.createElement("input"),b=d.createElement("select"),c=b.appendChild(d.createElement("option"));a.type="checkbox",l.checkOn=""!==a.value,l.optSelected=c.selected,b.disabled=!0,l.optDisabled=!c.disabled,a=d.createElement("input"),a.value="t",a.type="radio",l.radioValue="t"===a.value}();var ab,bb=n.expr.attrHandle;n.fn.extend({attr:function(a,b){return K(this,n.attr,a,b,arguments.length>1)},removeAttr:function(a){return this.each(function(){n.removeAttr(this,a)})}}),n.extend({attr:function(a,b,c){var d,e,f=a.nodeType;if(3!==f&&8!==f&&2!==f)return"undefined"==typeof a.getAttribute?n.prop(a,b,c):(1===f&&n.isXMLDoc(a)||(b=b.toLowerCase(),e=n.attrHooks[b]||(n.expr.match.bool.test(b)?ab:void 0)),void 0!==c?null===c?void n.removeAttr(a,b):e&&"set"in e&&void 0!==(d=e.set(a,c,b))?d:(a.setAttribute(b,c+""),c):e&&"get"in e&&null!==(d=e.get(a,b))?d:(d=n.find.attr(a,b),null==d?void 0:d))},attrHooks:{type:{set:function(a,b){if(!l.radioValue&&"radio"===b&&n.nodeName(a,"input")){var c=a.value;return a.setAttribute("type",b),c&&(a.value=c),b}}}},removeAttr:function(a,b){var c,d,e=0,f=b&&b.match(G);if(f&&1===a.nodeType)while(c=f[e++])d=n.propFix[c]||c,n.expr.match.bool.test(c)&&(a[d]=!1),a.removeAttribute(c)}}),ab={set:function(a,b,c){return b===!1?n.removeAttr(a,c):a.setAttribute(c,c),c}},n.each(n.expr.match.bool.source.match(/\w+/g),function(a,b){var c=bb[b]||n.find.attr;bb[b]=function(a,b,d){var e,f;return d||(f=bb[b],bb[b]=e,e=null!=c(a,b,d)?b.toLowerCase():null,bb[b]=f),e}});var cb=/^(?:input|select|textarea|button)$/i,db=/^(?:a|area)$/i;n.fn.extend({prop:function(a,b){return K(this,n.prop,a,b,arguments.length>1)},removeProp:function(a){return this.each(function(){delete this[n.propFix[a]||a]})}}),n.extend({prop:function(a,b,c){var d,e,f=a.nodeType;if(3!==f&&8!==f&&2!==f)return 1===f&&n.isXMLDoc(a)||(b=n.propFix[b]||b,e=n.propHooks[b]),
void 0!==c?e&&"set"in e&&void 0!==(d=e.set(a,c,b))?d:a[b]=c:e&&"get"in e&&null!==(d=e.get(a,b))?d:a[b]},propHooks:{tabIndex:{get:function(a){var b=n.find.attr(a,"tabindex");return b?parseInt(b,10):cb.test(a.nodeName)||db.test(a.nodeName)&&a.href?0:-1}}},propFix:{"for":"htmlFor","class":"className"}}),l.optSelected||(n.propHooks.selected={get:function(a){var b=a.parentNode;return b&&b.parentNode&&b.parentNode.selectedIndex,null},set:function(a){var b=a.parentNode;b&&(b.selectedIndex,b.parentNode&&b.parentNode.selectedIndex)}}),n.each(["tabIndex","readOnly","maxLength","cellSpacing","cellPadding","rowSpan","colSpan","useMap","frameBorder","contentEditable"],function(){n.propFix[this.toLowerCase()]=this});var eb=/[\t\r\n\f]/g;function fb(a){return a.getAttribute&&a.getAttribute("class")||""}n.fn.extend({addClass:function(a){var b,c,d,e,f,g,h,i=0;if(n.isFunction(a))return this.each(function(b){n(this).addClass(a.call(this,b,fb(this)))});if("string"==typeof a&&a){b=a.match(G)||[];while(c=this[i++])if(e=fb(c),d=1===c.nodeType&&(" "+e+" ").replace(eb," ")){g=0;while(f=b[g++])d.indexOf(" "+f+" ")<0&&(d+=f+" ");h=n.trim(d),e!==h&&c.setAttribute("class",h)}}return this},removeClass:function(a){var b,c,d,e,f,g,h,i=0;if(n.isFunction(a))return this.each(function(b){n(this).removeClass(a.call(this,b,fb(this)))});if(!arguments.length)return this.attr("class","");if("string"==typeof a&&a){b=a.match(G)||[];while(c=this[i++])if(e=fb(c),d=1===c.nodeType&&(" "+e+" ").replace(eb," ")){g=0;while(f=b[g++])while(d.indexOf(" "+f+" ")>-1)d=d.replace(" "+f+" "," ");h=n.trim(d),e!==h&&c.setAttribute("class",h)}}return this},toggleClass:function(a,b){var c=typeof a;return"boolean"==typeof b&&"string"===c?b?this.addClass(a):this.removeClass(a):n.isFunction(a)?this.each(function(c){n(this).toggleClass(a.call(this,c,fb(this),b),b)}):this.each(function(){var b,d,e,f;if("string"===c){d=0,e=n(this),f=a.match(G)||[];while(b=f[d++])e.hasClass(b)?e.removeClass(b):e.addClass(b)}else void 0!==a&&"boolean"!==c||(b=fb(this),b&&N.set(this,"__className__",b),this.setAttribute&&this.setAttribute("class",b||a===!1?"":N.get(this,"__className__")||""))})},hasClass:function(a){var b,c,d=0;b=" "+a+" ";while(c=this[d++])if(1===c.nodeType&&(" "+fb(c)+" ").replace(eb," ").indexOf(b)>-1)return!0;return!1}});var gb=/\r/g,hb=/[\x20\t\r\n\f]+/g;n.fn.extend({val:function(a){var b,c,d,e=this[0];{if(arguments.length)return d=n.isFunction(a),this.each(function(c){var e;1===this.nodeType&&(e=d?a.call(this,c,n(this).val()):a,null==e?e="":"number"==typeof e?e+="":n.isArray(e)&&(e=n.map(e,function(a){return null==a?"":a+""})),b=n.valHooks[this.type]||n.valHooks[this.nodeName.toLowerCase()],b&&"set"in b&&void 0!==b.set(this,e,"value")||(this.value=e))});if(e)return b=n.valHooks[e.type]||n.valHooks[e.nodeName.toLowerCase()],b&&"get"in b&&void 0!==(c=b.get(e,"value"))?c:(c=e.value,"string"==typeof c?c.replace(gb,""):null==c?"":c)}}}),n.extend({valHooks:{option:{get:function(a){var b=n.find.attr(a,"value");return null!=b?b:n.trim(n.text(a)).replace(hb," ")}},select:{get:function(a){for(var b,c,d=a.options,e=a.selectedIndex,f="select-one"===a.type||0>e,g=f?null:[],h=f?e+1:d.length,i=0>e?h:f?e:0;h>i;i++)if(c=d[i],(c.selected||i===e)&&(l.optDisabled?!c.disabled:null===c.getAttribute("disabled"))&&(!c.parentNode.disabled||!n.nodeName(c.parentNode,"optgroup"))){if(b=n(c).val(),f)return b;g.push(b)}return g},set:function(a,b){var c,d,e=a.options,f=n.makeArray(b),g=e.length;while(g--)d=e[g],(d.selected=n.inArray(n.valHooks.option.get(d),f)>-1)&&(c=!0);return c||(a.selectedIndex=-1),f}}}}),n.each(["radio","checkbox"],function(){n.valHooks[this]={set:function(a,b){return n.isArray(b)?a.checked=n.inArray(n(a).val(),b)>-1:void 0}},l.checkOn||(n.valHooks[this].get=function(a){return null===a.getAttribute("value")?"on":a.value})});var ib=/^(?:focusinfocus|focusoutblur)$/;n.extend(n.event,{trigger:function(b,c,e,f){var g,h,i,j,l,m,o,p=[e||d],q=k.call(b,"type")?b.type:b,r=k.call(b,"namespace")?b.namespace.split("."):[];if(h=i=e=e||d,3!==e.nodeType&&8!==e.nodeType&&!ib.test(q+n.event.triggered)&&(q.indexOf(".")>-1&&(r=q.split("."),q=r.shift(),r.sort()),l=q.indexOf(":")<0&&"on"+q,b=b[n.expando]?b:new n.Event(q,"object"==typeof b&&b),b.isTrigger=f?2:3,b.namespace=r.join("."),b.rnamespace=b.namespace?new RegExp("(^|\\.)"+r.join("\\.(?:.*\\.|)")+"(\\.|$)"):null,b.result=void 0,b.target||(b.target=e),c=null==c?[b]:n.makeArray(c,[b]),o=n.event.special[q]||{},f||!o.trigger||o.trigger.apply(e,c)!==!1)){if(!f&&!o.noBubble&&!n.isWindow(e)){for(j=o.delegateType||q,ib.test(j+q)||(h=h.parentNode);h;h=h.parentNode)p.push(h),i=h;i===(e.ownerDocument||d)&&p.push(i.defaultView||i.parentWindow||a)}g=0;while((h=p[g++])&&!b.isPropagationStopped())b.type=g>1?j:o.bindType||q,m=(N.get(h,"events")||{})[b.type]&&N.get(h,"handle"),m&&m.apply(h,c),m=l&&h[l],m&&m.apply&&L(h)&&(b.result=m.apply(h,c),b.result===!1&&b.preventDefault());return b.type=q,f||b.isDefaultPrevented()||o._default&&o._default.apply(p.pop(),c)!==!1||!L(e)||l&&n.isFunction(e[q])&&!n.isWindow(e)&&(i=e[l],i&&(e[l]=null),n.event.triggered=q,e[q](),n.event.triggered=void 0,i&&(e[l]=i)),b.result}},simulate:function(a,b,c){var d=n.extend(new n.Event,c,{type:a,isSimulated:!0});n.event.trigger(d,null,b)}}),n.fn.extend({trigger:function(a,b){return this.each(function(){n.event.trigger(a,b,this)})},triggerHandler:function(a,b){var c=this[0];return c?n.event.trigger(a,b,c,!0):void 0}}),n.each("blur focus focusin focusout load resize scroll unload click dblclick mousedown mouseup mousemove mouseover mouseout mouseenter mouseleave change select submit keydown keypress keyup error contextmenu".split(" "),function(a,b){n.fn[b]=function(a,c){return arguments.length>0?this.on(b,null,a,c):this.trigger(b)}}),n.fn.extend({hover:function(a,b){return this.mouseenter(a).mouseleave(b||a)}}),l.focusin="onfocusin"in a,l.focusin||n.each({focus:"focusin",blur:"focusout"},function(a,b){var c=function(a){n.event.simulate(b,a.target,n.event.fix(a))};n.event.special[b]={setup:function(){var d=this.ownerDocument||this,e=N.access(d,b);e||d.addEventListener(a,c,!0),N.access(d,b,(e||0)+1)},teardown:function(){var d=this.ownerDocument||this,e=N.access(d,b)-1;e?N.access(d,b,e):(d.removeEventListener(a,c,!0),N.remove(d,b))}}});var jb=a.location,kb=n.now(),lb=/\?/;n.parseJSON=function(a){return JSON.parse(a+"")},n.parseXML=function(b){var c;if(!b||"string"!=typeof b)return null;try{c=(new a.DOMParser).parseFromString(b,"text/xml")}catch(d){c=void 0}return c&&!c.getElementsByTagName("parsererror").length||n.error("Invalid XML: "+b),c};var mb=/#.*$/,nb=/([?&])_=[^&]*/,ob=/^(.*?):[ \t]*([^\r\n]*)$/gm,pb=/^(?:about|app|app-storage|.+-extension|file|res|widget):$/,qb=/^(?:GET|HEAD)$/,rb=/^\/\//,sb={},tb={},ub="*/".concat("*"),vb=d.createElement("a");vb.href=jb.href;function wb(a){return function(b,c){"string"!=typeof b&&(c=b,b="*");var d,e=0,f=b.toLowerCase().match(G)||[];if(n.isFunction(c))while(d=f[e++])"+"===d[0]?(d=d.slice(1)||"*",(a[d]=a[d]||[]).unshift(c)):(a[d]=a[d]||[]).push(c)}}function xb(a,b,c,d){var e={},f=a===tb;function g(h){var i;return e[h]=!0,n.each(a[h]||[],function(a,h){var j=h(b,c,d);return"string"!=typeof j||f||e[j]?f?!(i=j):void 0:(b.dataTypes.unshift(j),g(j),!1)}),i}return g(b.dataTypes[0])||!e["*"]&&g("*")}function yb(a,b){var c,d,e=n.ajaxSettings.flatOptions||{};for(c in b)void 0!==b[c]&&((e[c]?a:d||(d={}))[c]=b[c]);return d&&n.extend(!0,a,d),a}function zb(a,b,c){var d,e,f,g,h=a.contents,i=a.dataTypes;while("*"===i[0])i.shift(),void 0===d&&(d=a.mimeType||b.getResponseHeader("Content-Type"));if(d)for(e in h)if(h[e]&&h[e].test(d)){i.unshift(e);break}if(i[0]in c)f=i[0];else{for(e in c){if(!i[0]||a.converters[e+" "+i[0]]){f=e;break}g||(g=e)}f=f||g}return f?(f!==i[0]&&i.unshift(f),c[f]):void 0}function Ab(a,b,c,d){var e,f,g,h,i,j={},k=a.dataTypes.slice();if(k[1])for(g in a.converters)j[g.toLowerCase()]=a.converters[g];f=k.shift();while(f)if(a.responseFields[f]&&(c[a.responseFields[f]]=b),!i&&d&&a.dataFilter&&(b=a.dataFilter(b,a.dataType)),i=f,f=k.shift())if("*"===f)f=i;else if("*"!==i&&i!==f){if(g=j[i+" "+f]||j["* "+f],!g)for(e in j)if(h=e.split(" "),h[1]===f&&(g=j[i+" "+h[0]]||j["* "+h[0]])){g===!0?g=j[e]:j[e]!==!0&&(f=h[0],k.unshift(h[1]));break}if(g!==!0)if(g&&a["throws"])b=g(b);else try{b=g(b)}catch(l){return{state:"parsererror",error:g?l:"No conversion from "+i+" to "+f}}}return{state:"success",data:b}}n.extend({active:0,lastModified:{},etag:{},ajaxSettings:{url:jb.href,type:"GET",isLocal:pb.test(jb.protocol),global:!0,processData:!0,async:!0,contentType:"application/x-www-form-urlencoded; charset=UTF-8",accepts:{"*":ub,text:"text/plain",html:"text/html",xml:"application/xml, text/xml",json:"application/json, text/javascript"},contents:{xml:/\bxml\b/,html:/\bhtml/,json:/\bjson\b/},responseFields:{xml:"responseXML",text:"responseText",json:"responseJSON"},converters:{"* text":String,"text html":!0,"text json":n.parseJSON,"text xml":n.parseXML},flatOptions:{url:!0,context:!0}},ajaxSetup:function(a,b){return b?yb(yb(a,n.ajaxSettings),b):yb(n.ajaxSettings,a)},ajaxPrefilter:wb(sb),ajaxTransport:wb(tb),ajax:function(b,c){"object"==typeof b&&(c=b,b=void 0),c=c||{};var e,f,g,h,i,j,k,l,m=n.ajaxSetup({},c),o=m.context||m,p=m.context&&(o.nodeType||o.jquery)?n(o):n.event,q=n.Deferred(),r=n.Callbacks("once memory"),s=m.statusCode||{},t={},u={},v=0,w="canceled",x={readyState:0,getResponseHeader:function(a){var b;if(2===v){if(!h){h={};while(b=ob.exec(g))h[b[1].toLowerCase()]=b[2]}b=h[a.toLowerCase()]}return null==b?null:b},getAllResponseHeaders:function(){return 2===v?g:null},setRequestHeader:function(a,b){var c=a.toLowerCase();return v||(a=u[c]=u[c]||a,t[a]=b),this},overrideMimeType:function(a){return v||(m.mimeType=a),this},statusCode:function(a){var b;if(a)if(2>v)for(b in a)s[b]=[s[b],a[b]];else x.always(a[x.status]);return this},abort:function(a){var b=a||w;return e&&e.abort(b),z(0,b),this}};if(q.promise(x).complete=r.add,x.success=x.done,x.error=x.fail,m.url=((b||m.url||jb.href)+"").replace(mb,"").replace(rb,jb.protocol+"//"),m.type=c.method||c.type||m.method||m.type,m.dataTypes=n.trim(m.dataType||"*").toLowerCase().match(G)||[""],null==m.crossDomain){j=d.createElement("a");try{j.href=m.url,j.href=j.href,m.crossDomain=vb.protocol+"//"+vb.host!=j.protocol+"//"+j.host}catch(y){m.crossDomain=!0}}if(m.data&&m.processData&&"string"!=typeof m.data&&(m.data=n.param(m.data,m.traditional)),xb(sb,m,c,x),2===v)return x;k=n.event&&m.global,k&&0===n.active++&&n.event.trigger("ajaxStart"),m.type=m.type.toUpperCase(),m.hasContent=!qb.test(m.type),f=m.url,m.hasContent||(m.data&&(f=m.url+=(lb.test(f)?"&":"?")+m.data,delete m.data),m.cache===!1&&(m.url=nb.test(f)?f.replace(nb,"$1_="+kb++):f+(lb.test(f)?"&":"?")+"_="+kb++)),m.ifModified&&(n.lastModified[f]&&x.setRequestHeader("If-Modified-Since",n.lastModified[f]),n.etag[f]&&x.setRequestHeader("If-None-Match",n.etag[f])),(m.data&&m.hasContent&&m.contentType!==!1||c.contentType)&&x.setRequestHeader("Content-Type",m.contentType),x.setRequestHeader("Accept",m.dataTypes[0]&&m.accepts[m.dataTypes[0]]?m.accepts[m.dataTypes[0]]+("*"!==m.dataTypes[0]?", "+ub+"; q=0.01":""):m.accepts["*"]);for(l in m.headers)x.setRequestHeader(l,m.headers[l]);if(m.beforeSend&&(m.beforeSend.call(o,x,m)===!1||2===v))return x.abort();w="abort";for(l in{success:1,error:1,complete:1})x[l](m[l]);if(e=xb(tb,m,c,x)){if(x.readyState=1,k&&p.trigger("ajaxSend",[x,m]),2===v)return x;m.async&&m.timeout>0&&(i=a.setTimeout(function(){x.abort("timeout")},m.timeout));try{v=1,e.send(t,z)}catch(y){if(!(2>v))throw y;z(-1,y)}}else z(-1,"No Transport");function z(b,c,d,h){var j,l,t,u,w,y=c;2!==v&&(v=2,i&&a.clearTimeout(i),e=void 0,g=h||"",x.readyState=b>0?4:0,j=b>=200&&300>b||304===b,d&&(u=zb(m,x,d)),u=Ab(m,u,x,j),j?(m.ifModified&&(w=x.getResponseHeader("Last-Modified"),w&&(n.lastModified[f]=w),w=x.getResponseHeader("etag"),w&&(n.etag[f]=w)),204===b||"HEAD"===m.type?y="nocontent":304===b?y="notmodified":(y=u.state,l=u.data,t=u.error,j=!t)):(t=y,!b&&y||(y="error",0>b&&(b=0))),x.status=b,x.statusText=(c||y)+"",j?q.resolveWith(o,[l,y,x]):q.rejectWith(o,[x,y,t]),x.statusCode(s),s=void 0,k&&p.trigger(j?"ajaxSuccess":"ajaxError",[x,m,j?l:t]),r.fireWith(o,[x,y]),k&&(p.trigger("ajaxComplete",[x,m]),--n.active||n.event.trigger("ajaxStop")))}return x},getJSON:function(a,b,c){return n.get(a,b,c,"json")},getScript:function(a,b){return n.get(a,void 0,b,"script")}}),n.each(["get","post"],function(a,b){n[b]=function(a,c,d,e){return n.isFunction(c)&&(e=e||d,d=c,c=void 0),n.ajax(n.extend({url:a,type:b,dataType:e,data:c,success:d},n.isPlainObject(a)&&a))}}),n._evalUrl=function(a){return n.ajax({url:a,type:"GET",dataType:"script",async:!1,global:!1,"throws":!0})},n.fn.extend({wrapAll:function(a){var b;return n.isFunction(a)?this.each(function(b){n(this).wrapAll(a.call(this,b))}):(this[0]&&(b=n(a,this[0].ownerDocument).eq(0).clone(!0),this[0].parentNode&&b.insertBefore(this[0]),b.map(function(){var a=this;while(a.firstElementChild)a=a.firstElementChild;return a}).append(this)),this)},wrapInner:function(a){return n.isFunction(a)?this.each(function(b){n(this).wrapInner(a.call(this,b))}):this.each(function(){var b=n(this),c=b.contents();c.length?c.wrapAll(a):b.append(a)})},wrap:function(a){var b=n.isFunction(a);return this.each(function(c){n(this).wrapAll(b?a.call(this,c):a)})},unwrap:function(){return this.parent().each(function(){n.nodeName(this,"body")||n(this).replaceWith(this.childNodes)}).end()}}),n.expr.filters.hidden=function(a){return!n.expr.filters.visible(a)},n.expr.filters.visible=function(a){return a.offsetWidth>0||a.offsetHeight>0||a.getClientRects().length>0};var Bb=/%20/g,Cb=/\[\]$/,Db=/\r?\n/g,Eb=/^(?:submit|button|image|reset|file)$/i,Fb=/^(?:input|select|textarea|keygen)/i;function Gb(a,b,c,d){var e;if(n.isArray(b))n.each(b,function(b,e){c||Cb.test(a)?d(a,e):Gb(a+"["+("object"==typeof e&&null!=e?b:"")+"]",e,c,d)});else if(c||"object"!==n.type(b))d(a,b);else for(e in b)Gb(a+"["+e+"]",b[e],c,d)}n.param=function(a,b){var c,d=[],e=function(a,b){b=n.isFunction(b)?b():null==b?"":b,d[d.length]=encodeURIComponent(a)+"="+encodeURIComponent(b)};if(void 0===b&&(b=n.ajaxSettings&&n.ajaxSettings.traditional),n.isArray(a)||a.jquery&&!n.isPlainObject(a))n.each(a,function(){e(this.name,this.value)});else for(c in a)Gb(c,a[c],b,e);return d.join("&").replace(Bb,"+")},n.fn.extend({serialize:function(){return n.param(this.serializeArray())},serializeArray:function(){return this.map(function(){var a=n.prop(this,"elements");return a?n.makeArray(a):this}).filter(function(){var a=this.type;return this.name&&!n(this).is(":disabled")&&Fb.test(this.nodeName)&&!Eb.test(a)&&(this.checked||!X.test(a))}).map(function(a,b){var c=n(this).val();return null==c?null:n.isArray(c)?n.map(c,function(a){return{name:b.name,value:a.replace(Db,"\r\n")}}):{name:b.name,value:c.replace(Db,"\r\n")}}).get()}}),n.ajaxSettings.xhr=function(){try{return new a.XMLHttpRequest}catch(b){}};var Hb={0:200,1223:204},Ib=n.ajaxSettings.xhr();l.cors=!!Ib&&"withCredentials"in Ib,l.ajax=Ib=!!Ib,n.ajaxTransport(function(b){var c,d;return l.cors||Ib&&!b.crossDomain?{send:function(e,f){var g,h=b.xhr();if(h.open(b.type,b.url,b.async,b.username,b.password),b.xhrFields)for(g in b.xhrFields)h[g]=b.xhrFields[g];b.mimeType&&h.overrideMimeType&&h.overrideMimeType(b.mimeType),b.crossDomain||e["X-Requested-With"]||(e["X-Requested-With"]="XMLHttpRequest");for(g in e)h.setRequestHeader(g,e[g]);c=function(a){return function(){c&&(c=d=h.onload=h.onerror=h.onabort=h.onreadystatechange=null,"abort"===a?h.abort():"error"===a?"number"!=typeof h.status?f(0,"error"):f(h.status,h.statusText):f(Hb[h.status]||h.status,h.statusText,"text"!==(h.responseType||"text")||"string"!=typeof h.responseText?{binary:h.response}:{text:h.responseText},h.getAllResponseHeaders()))}},h.onload=c(),d=h.onerror=c("error"),void 0!==h.onabort?h.onabort=d:h.onreadystatechange=function(){4===h.readyState&&a.setTimeout(function(){c&&d()})},c=c("abort");try{h.send(b.hasContent&&b.data||null)}catch(i){if(c)throw i}},abort:function(){c&&c()}}:void 0}),n.ajaxSetup({accepts:{script:"text/javascript, application/javascript, application/ecmascript, application/x-ecmascript"},contents:{script:/\b(?:java|ecma)script\b/},converters:{"text script":function(a){return n.globalEval(a),a}}}),n.ajaxPrefilter("script",function(a){void 0===a.cache&&(a.cache=!1),a.crossDomain&&(a.type="GET")}),n.ajaxTransport("script",function(a){if(a.crossDomain){var b,c;return{send:function(e,f){b=n("<script>").prop({charset:a.scriptCharset,src:a.url}).on("load error",c=function(a){b.remove(),c=null,a&&f("error"===a.type?404:200,a.type)}),d.head.appendChild(b[0])},abort:function(){c&&c()}}}});var Jb=[],Kb=/(=)\?(?=&|$)|\?\?/;n.ajaxSetup({jsonp:"callback",jsonpCallback:function(){var a=Jb.pop()||n.expando+"_"+kb++;return this[a]=!0,a}}),n.ajaxPrefilter("json jsonp",function(b,c,d){var e,f,g,h=b.jsonp!==!1&&(Kb.test(b.url)?"url":"string"==typeof b.data&&0===(b.contentType||"").indexOf("application/x-www-form-urlencoded")&&Kb.test(b.data)&&"data");return h||"jsonp"===b.dataTypes[0]?(e=b.jsonpCallback=n.isFunction(b.jsonpCallback)?b.jsonpCallback():b.jsonpCallback,h?b[h]=b[h].replace(Kb,"$1"+e):b.jsonp!==!1&&(b.url+=(lb.test(b.url)?"&":"?")+b.jsonp+"="+e),b.converters["script json"]=function(){return g||n.error(e+" was not called"),g[0]},b.dataTypes[0]="json",f=a[e],a[e]=function(){g=arguments},d.always(function(){void 0===f?n(a).removeProp(e):a[e]=f,b[e]&&(b.jsonpCallback=c.jsonpCallback,Jb.push(e)),g&&n.isFunction(f)&&f(g[0]),g=f=void 0}),"script"):void 0}),n.parseHTML=function(a,b,c){if(!a||"string"!=typeof a)return null;"boolean"==typeof b&&(c=b,b=!1),b=b||d;var e=x.exec(a),f=!c&&[];return e?[b.createElement(e[1])]:(e=ca([a],b,f),f&&f.length&&n(f).remove(),n.merge([],e.childNodes))};var Lb=n.fn.load;n.fn.load=function(a,b,c){if("string"!=typeof a&&Lb)return Lb.apply(this,arguments);var d,e,f,g=this,h=a.indexOf(" ");return h>-1&&(d=n.trim(a.slice(h)),a=a.slice(0,h)),n.isFunction(b)?(c=b,b=void 0):b&&"object"==typeof b&&(e="POST"),g.length>0&&n.ajax({url:a,type:e||"GET",dataType:"html",data:b}).done(function(a){f=arguments,g.html(d?n("<div>").append(n.parseHTML(a)).find(d):a)}).always(c&&function(a,b){g.each(function(){c.apply(this,f||[a.responseText,b,a])})}),this},n.each(["ajaxStart","ajaxStop","ajaxComplete","ajaxError","ajaxSuccess","ajaxSend"],function(a,b){n.fn[b]=function(a){return this.on(b,a)}}),n.expr.filters.animated=function(a){return n.grep(n.timers,function(b){return a===b.elem}).length};function Mb(a){return n.isWindow(a)?a:9===a.nodeType&&a.defaultView}n.offset={setOffset:function(a,b,c){var d,e,f,g,h,i,j,k=n.css(a,"position"),l=n(a),m={};"static"===k&&(a.style.position="relative"),h=l.offset(),f=n.css(a,"top"),i=n.css(a,"left"),j=("absolute"===k||"fixed"===k)&&(f+i).indexOf("auto")>-1,j?(d=l.position(),g=d.top,e=d.left):(g=parseFloat(f)||0,e=parseFloat(i)||0),n.isFunction(b)&&(b=b.call(a,c,n.extend({},h))),null!=b.top&&(m.top=b.top-h.top+g),null!=b.left&&(m.left=b.left-h.left+e),"using"in b?b.using.call(a,m):l.css(m)}},n.fn.extend({offset:function(a){if(arguments.length)return void 0===a?this:this.each(function(b){n.offset.setOffset(this,a,b)});var b,c,d=this[0],e={top:0,left:0},f=d&&d.ownerDocument;if(f)return b=f.documentElement,n.contains(b,d)?(e=d.getBoundingClientRect(),c=Mb(f),{top:e.top+c.pageYOffset-b.clientTop,left:e.left+c.pageXOffset-b.clientLeft}):e},position:function(){if(this[0]){var a,b,c=this[0],d={top:0,left:0};return"fixed"===n.css(c,"position")?b=c.getBoundingClientRect():(a=this.offsetParent(),b=this.offset(),n.nodeName(a[0],"html")||(d=a.offset()),d.top+=n.css(a[0],"borderTopWidth",!0),d.left+=n.css(a[0],"borderLeftWidth",!0)),{top:b.top-d.top-n.css(c,"marginTop",!0),left:b.left-d.left-n.css(c,"marginLeft",!0)}}},offsetParent:function(){return this.map(function(){var a=this.offsetParent;while(a&&"static"===n.css(a,"position"))a=a.offsetParent;return a||Ea})}}),n.each({scrollLeft:"pageXOffset",scrollTop:"pageYOffset"},function(a,b){var c="pageYOffset"===b;n.fn[a]=function(d){return K(this,function(a,d,e){var f=Mb(a);return void 0===e?f?f[b]:a[d]:void(f?f.scrollTo(c?f.pageXOffset:e,c?e:f.pageYOffset):a[d]=e)},a,d,arguments.length)}}),n.each(["top","left"],function(a,b){n.cssHooks[b]=Ga(l.pixelPosition,function(a,c){return c?(c=Fa(a,b),Ba.test(c)?n(a).position()[b]+"px":c):void 0})}),n.each({Height:"height",Width:"width"},function(a,b){n.each({padding:"inner"+a,content:b,"":"outer"+a},function(c,d){n.fn[d]=function(d,e){var f=arguments.length&&(c||"boolean"!=typeof d),g=c||(d===!0||e===!0?"margin":"border");return K(this,function(b,c,d){var e;return n.isWindow(b)?b.document.documentElement["client"+a]:9===b.nodeType?(e=b.documentElement,Math.max(b.body["scroll"+a],e["scroll"+a],b.body["offset"+a],e["offset"+a],e["client"+a])):void 0===d?n.css(b,c,g):n.style(b,c,d,g)},b,f?d:void 0,f,null)}})}),n.fn.extend({bind:function(a,b,c){return this.on(a,null,b,c)},unbind:function(a,b){return this.off(a,null,b)},delegate:function(a,b,c,d){return this.on(b,a,c,d)},undelegate:function(a,b,c){return 1===arguments.length?this.off(a,"**"):this.off(b,a||"**",c)},size:function(){return this.length}}),n.fn.andSelf=n.fn.addBack,"function"==typeof define&&define.amd&&define("jquery",[],function(){return n});var Nb=a.jQuery,Ob=a.$;return n.noConflict=function(b){return a.$===n&&(a.$=Ob),b&&a.jQuery===n&&(a.jQuery=Nb),n},b||(a.jQuery=a.$=n),n});

/*!
 * Bootstrap v3.3.7 (http://getbootstrap.com)
 * Copyright 2011-2016 Twitter, Inc.
 * Licensed under the MIT license
 */
if("undefined"==typeof jQuery)throw new Error("Bootstrap's JavaScript requires jQuery");+function(a){"use strict";var b=a.fn.jquery.split(" ")[0].split(".");if(b[0]<2&&b[1]<9||1==b[0]&&9==b[1]&&b[2]<1||b[0]>3)throw new Error("Bootstrap's JavaScript requires jQuery version 1.9.1 or higher, but lower than version 4")}(jQuery),+function(a){"use strict";function b(){var a=document.createElement("bootstrap"),b={WebkitTransition:"webkitTransitionEnd",MozTransition:"transitionend",OTransition:"oTransitionEnd otransitionend",transition:"transitionend"};for(var c in b)if(void 0!==a.style[c])return{end:b[c]};return!1}a.fn.emulateTransitionEnd=function(b){var c=!1,d=this;a(this).one("bsTransitionEnd",function(){c=!0});var e=function(){c||a(d).trigger(a.support.transition.end)};return setTimeout(e,b),this},a(function(){a.support.transition=b(),a.support.transition&&(a.event.special.bsTransitionEnd={bindType:a.support.transition.end,delegateType:a.support.transition.end,handle:function(b){if(a(b.target).is(this))return b.handleObj.handler.apply(this,arguments)}})})}(jQuery),+function(a){"use strict";function b(b){return this.each(function(){var c=a(this),e=c.data("bs.alert");e||c.data("bs.alert",e=new d(this)),"string"==typeof b&&e[b].call(c)})}var c='[data-dismiss="alert"]',d=function(b){a(b).on("click",c,this.close)};d.VERSION="3.3.7",d.TRANSITION_DURATION=150,d.prototype.close=function(b){function c(){g.detach().trigger("closed.bs.alert").remove()}var e=a(this),f=e.attr("data-target");f||(f=e.attr("href"),f=f&&f.replace(/.*(?=#[^\s]*$)/,""));var g=a("#"===f?[]:f);b&&b.preventDefault(),g.length||(g=e.closest(".alert")),g.trigger(b=a.Event("close.bs.alert")),b.isDefaultPrevented()||(g.removeClass("in"),a.support.transition&&g.hasClass("fade")?g.one("bsTransitionEnd",c).emulateTransitionEnd(d.TRANSITION_DURATION):c())};var e=a.fn.alert;a.fn.alert=b,a.fn.alert.Constructor=d,a.fn.alert.noConflict=function(){return a.fn.alert=e,this},a(document).on("click.bs.alert.data-api",c,d.prototype.close)}(jQuery),+function(a){"use strict";function b(b){return this.each(function(){var d=a(this),e=d.data("bs.button"),f="object"==typeof b&&b;e||d.data("bs.button",e=new c(this,f)),"toggle"==b?e.toggle():b&&e.setState(b)})}var c=function(b,d){this.$element=a(b),this.options=a.extend({},c.DEFAULTS,d),this.isLoading=!1};c.VERSION="3.3.7",c.DEFAULTS={loadingText:"loading..."},c.prototype.setState=function(b){var c="disabled",d=this.$element,e=d.is("input")?"val":"html",f=d.data();b+="Text",null==f.resetText&&d.data("resetText",d[e]()),setTimeout(a.proxy(function(){d[e](null==f[b]?this.options[b]:f[b]),"loadingText"==b?(this.isLoading=!0,d.addClass(c).attr(c,c).prop(c,!0)):this.isLoading&&(this.isLoading=!1,d.removeClass(c).removeAttr(c).prop(c,!1))},this),0)},c.prototype.toggle=function(){var a=!0,b=this.$element.closest('[data-toggle="buttons"]');if(b.length){var c=this.$element.find("input");"radio"==c.prop("type")?(c.prop("checked")&&(a=!1),b.find(".active").removeClass("active"),this.$element.addClass("active")):"checkbox"==c.prop("type")&&(c.prop("checked")!==this.$element.hasClass("active")&&(a=!1),this.$element.toggleClass("active")),c.prop("checked",this.$element.hasClass("active")),a&&c.trigger("change")}else this.$element.attr("aria-pressed",!this.$element.hasClass("active")),this.$element.toggleClass("active")};var d=a.fn.button;a.fn.button=b,a.fn.button.Constructor=c,a.fn.button.noConflict=function(){return a.fn.button=d,this},a(document).on("click.bs.button.data-api",'[data-toggle^="button"]',function(c){var d=a(c.target).closest(".btn");b.call(d,"toggle"),a(c.target).is('input[type="radio"], input[type="checkbox"]')||(c.preventDefault(),d.is("input,button")?d.trigger("focus"):d.find("input:visible,button:visible").first().trigger("focus"))}).on("focus.bs.button.data-api blur.bs.button.data-api",'[data-toggle^="button"]',function(b){a(b.target).closest(".btn").toggleClass("focus",/^focus(in)?$/.test(b.type))})}(jQuery),+function(a){"use strict";function b(b){return this.each(function(){var d=a(this),e=d.data("bs.carousel"),f=a.extend({},c.DEFAULTS,d.data(),"object"==typeof b&&b),g="string"==typeof b?b:f.slide;e||d.data("bs.carousel",e=new c(this,f)),"number"==typeof b?e.to(b):g?e[g]():f.interval&&e.pause().cycle()})}var c=function(b,c){this.$element=a(b),this.$indicators=this.$element.find(".carousel-indicators"),this.options=c,this.paused=null,this.sliding=null,this.interval=null,this.$active=null,this.$items=null,this.options.keyboard&&this.$element.on("keydown.bs.carousel",a.proxy(this.keydown,this)),"hover"==this.options.pause&&!("ontouchstart"in document.documentElement)&&this.$element.on("mouseenter.bs.carousel",a.proxy(this.pause,this)).on("mouseleave.bs.carousel",a.proxy(this.cycle,this))};c.VERSION="3.3.7",c.TRANSITION_DURATION=600,c.DEFAULTS={interval:5e3,pause:"hover",wrap:!0,keyboard:!0},c.prototype.keydown=function(a){if(!/input|textarea/i.test(a.target.tagName)){switch(a.which){case 37:this.prev();break;case 39:this.next();break;default:return}a.preventDefault()}},c.prototype.cycle=function(b){return b||(this.paused=!1),this.interval&&clearInterval(this.interval),this.options.interval&&!this.paused&&(this.interval=setInterval(a.proxy(this.next,this),this.options.interval)),this},c.prototype.getItemIndex=function(a){return this.$items=a.parent().children(".item"),this.$items.index(a||this.$active)},c.prototype.getItemForDirection=function(a,b){var c=this.getItemIndex(b),d="prev"==a&&0===c||"next"==a&&c==this.$items.length-1;if(d&&!this.options.wrap)return b;var e="prev"==a?-1:1,f=(c+e)%this.$items.length;return this.$items.eq(f)},c.prototype.to=function(a){var b=this,c=this.getItemIndex(this.$active=this.$element.find(".item.active"));if(!(a>this.$items.length-1||a<0))return this.sliding?this.$element.one("slid.bs.carousel",function(){b.to(a)}):c==a?this.pause().cycle():this.slide(a>c?"next":"prev",this.$items.eq(a))},c.prototype.pause=function(b){return b||(this.paused=!0),this.$element.find(".next, .prev").length&&a.support.transition&&(this.$element.trigger(a.support.transition.end),this.cycle(!0)),this.interval=clearInterval(this.interval),this},c.prototype.next=function(){if(!this.sliding)return this.slide("next")},c.prototype.prev=function(){if(!this.sliding)return this.slide("prev")},c.prototype.slide=function(b,d){var e=this.$element.find(".item.active"),f=d||this.getItemForDirection(b,e),g=this.interval,h="next"==b?"left":"right",i=this;if(f.hasClass("active"))return this.sliding=!1;var j=f[0],k=a.Event("slide.bs.carousel",{relatedTarget:j,direction:h});if(this.$element.trigger(k),!k.isDefaultPrevented()){if(this.sliding=!0,g&&this.pause(),this.$indicators.length){this.$indicators.find(".active").removeClass("active");var l=a(this.$indicators.children()[this.getItemIndex(f)]);l&&l.addClass("active")}var m=a.Event("slid.bs.carousel",{relatedTarget:j,direction:h});return a.support.transition&&this.$element.hasClass("slide")?(f.addClass(b),f[0].offsetWidth,e.addClass(h),f.addClass(h),e.one("bsTransitionEnd",function(){f.removeClass([b,h].join(" ")).addClass("active"),e.removeClass(["active",h].join(" ")),i.sliding=!1,setTimeout(function(){i.$element.trigger(m)},0)}).emulateTransitionEnd(c.TRANSITION_DURATION)):(e.removeClass("active"),f.addClass("active"),this.sliding=!1,this.$element.trigger(m)),g&&this.cycle(),this}};var d=a.fn.carousel;a.fn.carousel=b,a.fn.carousel.Constructor=c,a.fn.carousel.noConflict=function(){return a.fn.carousel=d,this};var e=function(c){var d,e=a(this),f=a(e.attr("data-target")||(d=e.attr("href"))&&d.replace(/.*(?=#[^\s]+$)/,""));if(f.hasClass("carousel")){var g=a.extend({},f.data(),e.data()),h=e.attr("data-slide-to");h&&(g.interval=!1),b.call(f,g),h&&f.data("bs.carousel").to(h),c.preventDefault()}};a(document).on("click.bs.carousel.data-api","[data-slide]",e).on("click.bs.carousel.data-api","[data-slide-to]",e),a(window).on("load",function(){a('[data-ride="carousel"]').each(function(){var c=a(this);b.call(c,c.data())})})}(jQuery),+function(a){"use strict";function b(b){var c,d=b.attr("data-target")||(c=b.attr("href"))&&c.replace(/.*(?=#[^\s]+$)/,"");return a(d)}function c(b){return this.each(function(){var c=a(this),e=c.data("bs.collapse"),f=a.extend({},d.DEFAULTS,c.data(),"object"==typeof b&&b);!e&&f.toggle&&/show|hide/.test(b)&&(f.toggle=!1),e||c.data("bs.collapse",e=new d(this,f)),"string"==typeof b&&e[b]()})}var d=function(b,c){this.$element=a(b),this.options=a.extend({},d.DEFAULTS,c),this.$trigger=a('[data-toggle="collapse"][href="#'+b.id+'"],[data-toggle="collapse"][data-target="#'+b.id+'"]'),this.transitioning=null,this.options.parent?this.$parent=this.getParent():this.addAriaAndCollapsedClass(this.$element,this.$trigger),this.options.toggle&&this.toggle()};d.VERSION="3.3.7",d.TRANSITION_DURATION=350,d.DEFAULTS={toggle:!0},d.prototype.dimension=function(){var a=this.$element.hasClass("width");return a?"width":"height"},d.prototype.show=function(){if(!this.transitioning&&!this.$element.hasClass("in")){var b,e=this.$parent&&this.$parent.children(".panel").children(".in, .collapsing");if(!(e&&e.length&&(b=e.data("bs.collapse"),b&&b.transitioning))){var f=a.Event("show.bs.collapse");if(this.$element.trigger(f),!f.isDefaultPrevented()){e&&e.length&&(c.call(e,"hide"),b||e.data("bs.collapse",null));var g=this.dimension();this.$element.removeClass("collapse").addClass("collapsing")[g](0).attr("aria-expanded",!0),this.$trigger.removeClass("collapsed").attr("aria-expanded",!0),this.transitioning=1;var h=function(){this.$element.removeClass("collapsing").addClass("collapse in")[g](""),this.transitioning=0,this.$element.trigger("shown.bs.collapse")};if(!a.support.transition)return h.call(this);var i=a.camelCase(["scroll",g].join("-"));this.$element.one("bsTransitionEnd",a.proxy(h,this)).emulateTransitionEnd(d.TRANSITION_DURATION)[g](this.$element[0][i])}}}},d.prototype.hide=function(){if(!this.transitioning&&this.$element.hasClass("in")){var b=a.Event("hide.bs.collapse");if(this.$element.trigger(b),!b.isDefaultPrevented()){var c=this.dimension();this.$element[c](this.$element[c]())[0].offsetHeight,this.$element.addClass("collapsing").removeClass("collapse in").attr("aria-expanded",!1),this.$trigger.addClass("collapsed").attr("aria-expanded",!1),this.transitioning=1;var e=function(){this.transitioning=0,this.$element.removeClass("collapsing").addClass("collapse").trigger("hidden.bs.collapse")};return a.support.transition?void this.$element[c](0).one("bsTransitionEnd",a.proxy(e,this)).emulateTransitionEnd(d.TRANSITION_DURATION):e.call(this)}}},d.prototype.toggle=function(){this[this.$element.hasClass("in")?"hide":"show"]()},d.prototype.getParent=function(){return a(this.options.parent).find('[data-toggle="collapse"][data-parent="'+this.options.parent+'"]').each(a.proxy(function(c,d){var e=a(d);this.addAriaAndCollapsedClass(b(e),e)},this)).end()},d.prototype.addAriaAndCollapsedClass=function(a,b){var c=a.hasClass("in");a.attr("aria-expanded",c),b.toggleClass("collapsed",!c).attr("aria-expanded",c)};var e=a.fn.collapse;a.fn.collapse=c,a.fn.collapse.Constructor=d,a.fn.collapse.noConflict=function(){return a.fn.collapse=e,this},a(document).on("click.bs.collapse.data-api",'[data-toggle="collapse"]',function(d){var e=a(this);e.attr("data-target")||d.preventDefault();var f=b(e),g=f.data("bs.collapse"),h=g?"toggle":e.data();c.call(f,h)})}(jQuery),+function(a){"use strict";function b(b){var c=b.attr("data-target");c||(c=b.attr("href"),c=c&&/#[A-Za-z]/.test(c)&&c.replace(/.*(?=#[^\s]*$)/,""));var d=c&&a(c);return d&&d.length?d:b.parent()}function c(c){c&&3===c.which||(a(e).remove(),a(f).each(function(){var d=a(this),e=b(d),f={relatedTarget:this};e.hasClass("open")&&(c&&"click"==c.type&&/input|textarea/i.test(c.target.tagName)&&a.contains(e[0],c.target)||(e.trigger(c=a.Event("hide.bs.dropdown",f)),c.isDefaultPrevented()||(d.attr("aria-expanded","false"),e.removeClass("open").trigger(a.Event("hidden.bs.dropdown",f)))))}))}function d(b){return this.each(function(){var c=a(this),d=c.data("bs.dropdown");d||c.data("bs.dropdown",d=new g(this)),"string"==typeof b&&d[b].call(c)})}var e=".dropdown-backdrop",f='[data-toggle="dropdown"]',g=function(b){a(b).on("click.bs.dropdown",this.toggle)};g.VERSION="3.3.7",g.prototype.toggle=function(d){var e=a(this);if(!e.is(".disabled, :disabled")){var f=b(e),g=f.hasClass("open");if(c(),!g){"ontouchstart"in document.documentElement&&!f.closest(".navbar-nav").length&&a(document.createElement("div")).addClass("dropdown-backdrop").insertAfter(a(this)).on("click",c);var h={relatedTarget:this};if(f.trigger(d=a.Event("show.bs.dropdown",h)),d.isDefaultPrevented())return;e.trigger("focus").attr("aria-expanded","true"),f.toggleClass("open").trigger(a.Event("shown.bs.dropdown",h))}return!1}},g.prototype.keydown=function(c){if(/(38|40|27|32)/.test(c.which)&&!/input|textarea/i.test(c.target.tagName)){var d=a(this);if(c.preventDefault(),c.stopPropagation(),!d.is(".disabled, :disabled")){var e=b(d),g=e.hasClass("open");if(!g&&27!=c.which||g&&27==c.which)return 27==c.which&&e.find(f).trigger("focus"),d.trigger("click");var h=" li:not(.disabled):visible a",i=e.find(".dropdown-menu"+h);if(i.length){var j=i.index(c.target);38==c.which&&j>0&&j--,40==c.which&&j<i.length-1&&j++,~j||(j=0),i.eq(j).trigger("focus")}}}};var h=a.fn.dropdown;a.fn.dropdown=d,a.fn.dropdown.Constructor=g,a.fn.dropdown.noConflict=function(){return a.fn.dropdown=h,this},a(document).on("click.bs.dropdown.data-api",c).on("click.bs.dropdown.data-api",".dropdown form",function(a){a.stopPropagation()}).on("click.bs.dropdown.data-api",f,g.prototype.toggle).on("keydown.bs.dropdown.data-api",f,g.prototype.keydown).on("keydown.bs.dropdown.data-api",".dropdown-menu",g.prototype.keydown)}(jQuery),+function(a){"use strict";function b(b,d){return this.each(function(){var e=a(this),f=e.data("bs.modal"),g=a.extend({},c.DEFAULTS,e.data(),"object"==typeof b&&b);f||e.data("bs.modal",f=new c(this,g)),"string"==typeof b?f[b](d):g.show&&f.show(d)})}var c=function(b,c){this.options=c,this.$body=a(document.body),this.$element=a(b),this.$dialog=this.$element.find(".modal-dialog"),this.$backdrop=null,this.isShown=null,this.originalBodyPad=null,this.scrollbarWidth=0,this.ignoreBackdropClick=!1,this.options.remote&&this.$element.find(".modal-content").load(this.options.remote,a.proxy(function(){this.$element.trigger("loaded.bs.modal")},this))};c.VERSION="3.3.7",c.TRANSITION_DURATION=300,c.BACKDROP_TRANSITION_DURATION=150,c.DEFAULTS={backdrop:!0,keyboard:!0,show:!0},c.prototype.toggle=function(a){return this.isShown?this.hide():this.show(a)},c.prototype.show=function(b){var d=this,e=a.Event("show.bs.modal",{relatedTarget:b});this.$element.trigger(e),this.isShown||e.isDefaultPrevented()||(this.isShown=!0,this.checkScrollbar(),this.setScrollbar(),this.$body.addClass("modal-open"),this.escape(),this.resize(),this.$element.on("click.dismiss.bs.modal",'[data-dismiss="modal"]',a.proxy(this.hide,this)),this.$dialog.on("mousedown.dismiss.bs.modal",function(){d.$element.one("mouseup.dismiss.bs.modal",function(b){a(b.target).is(d.$element)&&(d.ignoreBackdropClick=!0)})}),this.backdrop(function(){var e=a.support.transition&&d.$element.hasClass("fade");d.$element.parent().length||d.$element.appendTo(d.$body),d.$element.show().scrollTop(0),d.adjustDialog(),e&&d.$element[0].offsetWidth,d.$element.addClass("in"),d.enforceFocus();var f=a.Event("shown.bs.modal",{relatedTarget:b});e?d.$dialog.one("bsTransitionEnd",function(){d.$element.trigger("focus").trigger(f)}).emulateTransitionEnd(c.TRANSITION_DURATION):d.$element.trigger("focus").trigger(f)}))},c.prototype.hide=function(b){b&&b.preventDefault(),b=a.Event("hide.bs.modal"),this.$element.trigger(b),this.isShown&&!b.isDefaultPrevented()&&(this.isShown=!1,this.escape(),this.resize(),a(document).off("focusin.bs.modal"),this.$element.removeClass("in").off("click.dismiss.bs.modal").off("mouseup.dismiss.bs.modal"),this.$dialog.off("mousedown.dismiss.bs.modal"),a.support.transition&&this.$element.hasClass("fade")?this.$element.one("bsTransitionEnd",a.proxy(this.hideModal,this)).emulateTransitionEnd(c.TRANSITION_DURATION):this.hideModal())},c.prototype.enforceFocus=function(){a(document).off("focusin.bs.modal").on("focusin.bs.modal",a.proxy(function(a){document===a.target||this.$element[0]===a.target||this.$element.has(a.target).length||this.$element.trigger("focus")},this))},c.prototype.escape=function(){this.isShown&&this.options.keyboard?this.$element.on("keydown.dismiss.bs.modal",a.proxy(function(a){27==a.which&&this.hide()},this)):this.isShown||this.$element.off("keydown.dismiss.bs.modal")},c.prototype.resize=function(){this.isShown?a(window).on("resize.bs.modal",a.proxy(this.handleUpdate,this)):a(window).off("resize.bs.modal")},c.prototype.hideModal=function(){var a=this;this.$element.hide(),this.backdrop(function(){a.$body.removeClass("modal-open"),a.resetAdjustments(),a.resetScrollbar(),a.$element.trigger("hidden.bs.modal")})},c.prototype.removeBackdrop=function(){this.$backdrop&&this.$backdrop.remove(),this.$backdrop=null},c.prototype.backdrop=function(b){var d=this,e=this.$element.hasClass("fade")?"fade":"";if(this.isShown&&this.options.backdrop){var f=a.support.transition&&e;if(this.$backdrop=a(document.createElement("div")).addClass("modal-backdrop "+e).appendTo(this.$body),this.$element.on("click.dismiss.bs.modal",a.proxy(function(a){return this.ignoreBackdropClick?void(this.ignoreBackdropClick=!1):void(a.target===a.currentTarget&&("static"==this.options.backdrop?this.$element[0].focus():this.hide()))},this)),f&&this.$backdrop[0].offsetWidth,this.$backdrop.addClass("in"),!b)return;f?this.$backdrop.one("bsTransitionEnd",b).emulateTransitionEnd(c.BACKDROP_TRANSITION_DURATION):b()}else if(!this.isShown&&this.$backdrop){this.$backdrop.removeClass("in");var g=function(){d.removeBackdrop(),b&&b()};a.support.transition&&this.$element.hasClass("fade")?this.$backdrop.one("bsTransitionEnd",g).emulateTransitionEnd(c.BACKDROP_TRANSITION_DURATION):g()}else b&&b()},c.prototype.handleUpdate=function(){this.adjustDialog()},c.prototype.adjustDialog=function(){var a=this.$element[0].scrollHeight>document.documentElement.clientHeight;this.$element.css({paddingLeft:!this.bodyIsOverflowing&&a?this.scrollbarWidth:"",paddingRight:this.bodyIsOverflowing&&!a?this.scrollbarWidth:""})},c.prototype.resetAdjustments=function(){this.$element.css({paddingLeft:"",paddingRight:""})},c.prototype.checkScrollbar=function(){var a=window.innerWidth;if(!a){var b=document.documentElement.getBoundingClientRect();a=b.right-Math.abs(b.left)}this.bodyIsOverflowing=document.body.clientWidth<a,this.scrollbarWidth=this.measureScrollbar()},c.prototype.setScrollbar=function(){var a=parseInt(this.$body.css("padding-right")||0,10);this.originalBodyPad=document.body.style.paddingRight||"",this.bodyIsOverflowing&&this.$body.css("padding-right",a+this.scrollbarWidth)},c.prototype.resetScrollbar=function(){this.$body.css("padding-right",this.originalBodyPad)},c.prototype.measureScrollbar=function(){var a=document.createElement("div");a.className="modal-scrollbar-measure",this.$body.append(a);var b=a.offsetWidth-a.clientWidth;return this.$body[0].removeChild(a),b};var d=a.fn.modal;a.fn.modal=b,a.fn.modal.Constructor=c,a.fn.modal.noConflict=function(){return a.fn.modal=d,this},a(document).on("click.bs.modal.data-api",'[data-toggle="modal"]',function(c){var d=a(this),e=d.attr("href"),f=a(d.attr("data-target")||e&&e.replace(/.*(?=#[^\s]+$)/,"")),g=f.data("bs.modal")?"toggle":a.extend({remote:!/#/.test(e)&&e},f.data(),d.data());d.is("a")&&c.preventDefault(),f.one("show.bs.modal",function(a){a.isDefaultPrevented()||f.one("hidden.bs.modal",function(){d.is(":visible")&&d.trigger("focus")})}),b.call(f,g,this)})}(jQuery),+function(a){"use strict";function b(b){return this.each(function(){var d=a(this),e=d.data("bs.tooltip"),f="object"==typeof b&&b;!e&&/destroy|hide/.test(b)||(e||d.data("bs.tooltip",e=new c(this,f)),"string"==typeof b&&e[b]())})}var c=function(a,b){this.type=null,this.options=null,this.enabled=null,this.timeout=null,this.hoverState=null,this.$element=null,this.inState=null,this.init("tooltip",a,b)};c.VERSION="3.3.7",c.TRANSITION_DURATION=150,c.DEFAULTS={animation:!0,placement:"top",selector:!1,template:'<div class="tooltip" role="tooltip"><div class="tooltip-arrow"></div><div class="tooltip-inner"></div></div>',trigger:"hover focus",title:"",delay:0,html:!1,container:!1,viewport:{selector:"body",padding:0}},c.prototype.init=function(b,c,d){if(this.enabled=!0,this.type=b,this.$element=a(c),this.options=this.getOptions(d),this.$viewport=this.options.viewport&&a(a.isFunction(this.options.viewport)?this.options.viewport.call(this,this.$element):this.options.viewport.selector||this.options.viewport),this.inState={click:!1,hover:!1,focus:!1},this.$element[0]instanceof document.constructor&&!this.options.selector)throw new Error("`selector` option must be specified when initializing "+this.type+" on the window.document object!");for(var e=this.options.trigger.split(" "),f=e.length;f--;){var g=e[f];if("click"==g)this.$element.on("click."+this.type,this.options.selector,a.proxy(this.toggle,this));else if("manual"!=g){var h="hover"==g?"mouseenter":"focusin",i="hover"==g?"mouseleave":"focusout";this.$element.on(h+"."+this.type,this.options.selector,a.proxy(this.enter,this)),this.$element.on(i+"."+this.type,this.options.selector,a.proxy(this.leave,this))}}this.options.selector?this._options=a.extend({},this.options,{trigger:"manual",selector:""}):this.fixTitle()},c.prototype.getDefaults=function(){return c.DEFAULTS},c.prototype.getOptions=function(b){return b=a.extend({},this.getDefaults(),this.$element.data(),b),b.delay&&"number"==typeof b.delay&&(b.delay={show:b.delay,hide:b.delay}),b},c.prototype.getDelegateOptions=function(){var b={},c=this.getDefaults();return this._options&&a.each(this._options,function(a,d){c[a]!=d&&(b[a]=d)}),b},c.prototype.enter=function(b){var c=b instanceof this.constructor?b:a(b.currentTarget).data("bs."+this.type);return c||(c=new this.constructor(b.currentTarget,this.getDelegateOptions()),a(b.currentTarget).data("bs."+this.type,c)),b instanceof a.Event&&(c.inState["focusin"==b.type?"focus":"hover"]=!0),c.tip().hasClass("in")||"in"==c.hoverState?void(c.hoverState="in"):(clearTimeout(c.timeout),c.hoverState="in",c.options.delay&&c.options.delay.show?void(c.timeout=setTimeout(function(){"in"==c.hoverState&&c.show()},c.options.delay.show)):c.show())},c.prototype.isInStateTrue=function(){for(var a in this.inState)if(this.inState[a])return!0;return!1},c.prototype.leave=function(b){var c=b instanceof this.constructor?b:a(b.currentTarget).data("bs."+this.type);if(c||(c=new this.constructor(b.currentTarget,this.getDelegateOptions()),a(b.currentTarget).data("bs."+this.type,c)),b instanceof a.Event&&(c.inState["focusout"==b.type?"focus":"hover"]=!1),!c.isInStateTrue())return clearTimeout(c.timeout),c.hoverState="out",c.options.delay&&c.options.delay.hide?void(c.timeout=setTimeout(function(){"out"==c.hoverState&&c.hide()},c.options.delay.hide)):c.hide()},c.prototype.show=function(){var b=a.Event("show.bs."+this.type);if(this.hasContent()&&this.enabled){this.$element.trigger(b);var d=a.contains(this.$element[0].ownerDocument.documentElement,this.$element[0]);if(b.isDefaultPrevented()||!d)return;var e=this,f=this.tip(),g=this.getUID(this.type);this.setContent(),f.attr("id",g),this.$element.attr("aria-describedby",g),this.options.animation&&f.addClass("fade");var h="function"==typeof this.options.placement?this.options.placement.call(this,f[0],this.$element[0]):this.options.placement,i=/\s?auto?\s?/i,j=i.test(h);j&&(h=h.replace(i,"")||"top"),f.detach().css({top:0,left:0,display:"block"}).addClass(h).data("bs."+this.type,this),this.options.container?f.appendTo(this.options.container):f.insertAfter(this.$element),this.$element.trigger("inserted.bs."+this.type);var k=this.getPosition(),l=f[0].offsetWidth,m=f[0].offsetHeight;if(j){var n=h,o=this.getPosition(this.$viewport);h="bottom"==h&&k.bottom+m>o.bottom?"top":"top"==h&&k.top-m<o.top?"bottom":"right"==h&&k.right+l>o.width?"left":"left"==h&&k.left-l<o.left?"right":h,f.removeClass(n).addClass(h)}var p=this.getCalculatedOffset(h,k,l,m);this.applyPlacement(p,h);var q=function(){var a=e.hoverState;e.$element.trigger("shown.bs."+e.type),e.hoverState=null,"out"==a&&e.leave(e)};a.support.transition&&this.$tip.hasClass("fade")?f.one("bsTransitionEnd",q).emulateTransitionEnd(c.TRANSITION_DURATION):q()}},c.prototype.applyPlacement=function(b,c){var d=this.tip(),e=d[0].offsetWidth,f=d[0].offsetHeight,g=parseInt(d.css("margin-top"),10),h=parseInt(d.css("margin-left"),10);isNaN(g)&&(g=0),isNaN(h)&&(h=0),b.top+=g,b.left+=h,a.offset.setOffset(d[0],a.extend({using:function(a){d.css({top:Math.round(a.top),left:Math.round(a.left)})}},b),0),d.addClass("in");var i=d[0].offsetWidth,j=d[0].offsetHeight;"top"==c&&j!=f&&(b.top=b.top+f-j);var k=this.getViewportAdjustedDelta(c,b,i,j);k.left?b.left+=k.left:b.top+=k.top;var l=/top|bottom/.test(c),m=l?2*k.left-e+i:2*k.top-f+j,n=l?"offsetWidth":"offsetHeight";d.offset(b),this.replaceArrow(m,d[0][n],l)},c.prototype.replaceArrow=function(a,b,c){this.arrow().css(c?"left":"top",50*(1-a/b)+"%").css(c?"top":"left","")},c.prototype.setContent=function(){var a=this.tip(),b=this.getTitle();a.find(".tooltip-inner")[this.options.html?"html":"text"](b),a.removeClass("fade in top bottom left right")},c.prototype.hide=function(b){function d(){"in"!=e.hoverState&&f.detach(),e.$element&&e.$element.removeAttr("aria-describedby").trigger("hidden.bs."+e.type),b&&b()}var e=this,f=a(this.$tip),g=a.Event("hide.bs."+this.type);if(this.$element.trigger(g),!g.isDefaultPrevented())return f.removeClass("in"),a.support.transition&&f.hasClass("fade")?f.one("bsTransitionEnd",d).emulateTransitionEnd(c.TRANSITION_DURATION):d(),this.hoverState=null,this},c.prototype.fixTitle=function(){var a=this.$element;(a.attr("title")||"string"!=typeof a.attr("data-original-title"))&&a.attr("data-original-title",a.attr("title")||"").attr("title","")},c.prototype.hasContent=function(){return this.getTitle()},c.prototype.getPosition=function(b){b=b||this.$element;var c=b[0],d="BODY"==c.tagName,e=c.getBoundingClientRect();null==e.width&&(e=a.extend({},e,{width:e.right-e.left,height:e.bottom-e.top}));var f=window.SVGElement&&c instanceof window.SVGElement,g=d?{top:0,left:0}:f?null:b.offset(),h={scroll:d?document.documentElement.scrollTop||document.body.scrollTop:b.scrollTop()},i=d?{width:a(window).width(),height:a(window).height()}:null;return a.extend({},e,h,i,g)},c.prototype.getCalculatedOffset=function(a,b,c,d){return"bottom"==a?{top:b.top+b.height,left:b.left+b.width/2-c/2}:"top"==a?{top:b.top-d,left:b.left+b.width/2-c/2}:"left"==a?{top:b.top+b.height/2-d/2,left:b.left-c}:{top:b.top+b.height/2-d/2,left:b.left+b.width}},c.prototype.getViewportAdjustedDelta=function(a,b,c,d){var e={top:0,left:0};if(!this.$viewport)return e;var f=this.options.viewport&&this.options.viewport.padding||0,g=this.getPosition(this.$viewport);if(/right|left/.test(a)){var h=b.top-f-g.scroll,i=b.top+f-g.scroll+d;h<g.top?e.top=g.top-h:i>g.top+g.height&&(e.top=g.top+g.height-i)}else{var j=b.left-f,k=b.left+f+c;j<g.left?e.left=g.left-j:k>g.right&&(e.left=g.left+g.width-k)}return e},c.prototype.getTitle=function(){var a,b=this.$element,c=this.options;return a=b.attr("data-original-title")||("function"==typeof c.title?c.title.call(b[0]):c.title)},c.prototype.getUID=function(a){do a+=~~(1e6*Math.random());while(document.getElementById(a));return a},c.prototype.tip=function(){if(!this.$tip&&(this.$tip=a(this.options.template),1!=this.$tip.length))throw new Error(this.type+" `template` option must consist of exactly 1 top-level element!");return this.$tip},c.prototype.arrow=function(){return this.$arrow=this.$arrow||this.tip().find(".tooltip-arrow")},c.prototype.enable=function(){this.enabled=!0},c.prototype.disable=function(){this.enabled=!1},c.prototype.toggleEnabled=function(){this.enabled=!this.enabled},c.prototype.toggle=function(b){var c=this;b&&(c=a(b.currentTarget).data("bs."+this.type),c||(c=new this.constructor(b.currentTarget,this.getDelegateOptions()),a(b.currentTarget).data("bs."+this.type,c))),b?(c.inState.click=!c.inState.click,c.isInStateTrue()?c.enter(c):c.leave(c)):c.tip().hasClass("in")?c.leave(c):c.enter(c)},c.prototype.destroy=function(){var a=this;clearTimeout(this.timeout),this.hide(function(){a.$element.off("."+a.type).removeData("bs."+a.type),a.$tip&&a.$tip.detach(),a.$tip=null,a.$arrow=null,a.$viewport=null,a.$element=null})};var d=a.fn.tooltip;a.fn.tooltip=b,a.fn.tooltip.Constructor=c,a.fn.tooltip.noConflict=function(){return a.fn.tooltip=d,this}}(jQuery),+function(a){"use strict";function b(b){return this.each(function(){var d=a(this),e=d.data("bs.popover"),f="object"==typeof b&&b;!e&&/destroy|hide/.test(b)||(e||d.data("bs.popover",e=new c(this,f)),"string"==typeof b&&e[b]())})}var c=function(a,b){this.init("popover",a,b)};if(!a.fn.tooltip)throw new Error("Popover requires tooltip.js");c.VERSION="3.3.7",c.DEFAULTS=a.extend({},a.fn.tooltip.Constructor.DEFAULTS,{placement:"right",trigger:"click",content:"",template:'<div class="popover" role="tooltip"><div class="arrow"></div><h3 class="popover-title"></h3><div class="popover-content"></div></div>'}),c.prototype=a.extend({},a.fn.tooltip.Constructor.prototype),c.prototype.constructor=c,c.prototype.getDefaults=function(){return c.DEFAULTS},c.prototype.setContent=function(){var a=this.tip(),b=this.getTitle(),c=this.getContent();a.find(".popover-title")[this.options.html?"html":"text"](b),a.find(".popover-content").children().detach().end()[this.options.html?"string"==typeof c?"html":"append":"text"](c),a.removeClass("fade top bottom left right in"),a.find(".popover-title").html()||a.find(".popover-title").hide()},c.prototype.hasContent=function(){return this.getTitle()||this.getContent()},c.prototype.getContent=function(){var a=this.$element,b=this.options;return a.attr("data-content")||("function"==typeof b.content?b.content.call(a[0]):b.content)},c.prototype.arrow=function(){return this.$arrow=this.$arrow||this.tip().find(".arrow")};var d=a.fn.popover;a.fn.popover=b,a.fn.popover.Constructor=c,a.fn.popover.noConflict=function(){return a.fn.popover=d,this}}(jQuery),+function(a){"use strict";function b(c,d){this.$body=a(document.body),this.$scrollElement=a(a(c).is(document.body)?window:c),this.options=a.extend({},b.DEFAULTS,d),this.selector=(this.options.target||"")+" .nav li > a",this.offsets=[],this.targets=[],this.activeTarget=null,this.scrollHeight=0,this.$scrollElement.on("scroll.bs.scrollspy",a.proxy(this.process,this)),this.refresh(),this.process()}function c(c){return this.each(function(){var d=a(this),e=d.data("bs.scrollspy"),f="object"==typeof c&&c;e||d.data("bs.scrollspy",e=new b(this,f)),"string"==typeof c&&e[c]()})}b.VERSION="3.3.7",b.DEFAULTS={offset:10},b.prototype.getScrollHeight=function(){return this.$scrollElement[0].scrollHeight||Math.max(this.$body[0].scrollHeight,document.documentElement.scrollHeight)},b.prototype.refresh=function(){var b=this,c="offset",d=0;this.offsets=[],this.targets=[],this.scrollHeight=this.getScrollHeight(),a.isWindow(this.$scrollElement[0])||(c="position",d=this.$scrollElement.scrollTop()),this.$body.find(this.selector).map(function(){var b=a(this),e=b.data("target")||b.attr("href"),f=/^#./.test(e)&&a(e);return f&&f.length&&f.is(":visible")&&[[f[c]().top+d,e]]||null}).sort(function(a,b){return a[0]-b[0]}).each(function(){b.offsets.push(this[0]),b.targets.push(this[1])})},b.prototype.process=function(){var a,b=this.$scrollElement.scrollTop()+this.options.offset,c=this.getScrollHeight(),d=this.options.offset+c-this.$scrollElement.height(),e=this.offsets,f=this.targets,g=this.activeTarget;if(this.scrollHeight!=c&&this.refresh(),b>=d)return g!=(a=f[f.length-1])&&this.activate(a);if(g&&b<e[0])return this.activeTarget=null,this.clear();for(a=e.length;a--;)g!=f[a]&&b>=e[a]&&(void 0===e[a+1]||b<e[a+1])&&this.activate(f[a])},b.prototype.activate=function(b){
this.activeTarget=b,this.clear();var c=this.selector+'[data-target="'+b+'"],'+this.selector+'[href="'+b+'"]',d=a(c).parents("li").addClass("active");d.parent(".dropdown-menu").length&&(d=d.closest("li.dropdown").addClass("active")),d.trigger("activate.bs.scrollspy")},b.prototype.clear=function(){a(this.selector).parentsUntil(this.options.target,".active").removeClass("active")};var d=a.fn.scrollspy;a.fn.scrollspy=c,a.fn.scrollspy.Constructor=b,a.fn.scrollspy.noConflict=function(){return a.fn.scrollspy=d,this},a(window).on("load.bs.scrollspy.data-api",function(){a('[data-spy="scroll"]').each(function(){var b=a(this);c.call(b,b.data())})})}(jQuery),+function(a){"use strict";function b(b){return this.each(function(){var d=a(this),e=d.data("bs.tab");e||d.data("bs.tab",e=new c(this)),"string"==typeof b&&e[b]()})}var c=function(b){this.element=a(b)};c.VERSION="3.3.7",c.TRANSITION_DURATION=150,c.prototype.show=function(){var b=this.element,c=b.closest("ul:not(.dropdown-menu)"),d=b.data("target");if(d||(d=b.attr("href"),d=d&&d.replace(/.*(?=#[^\s]*$)/,"")),!b.parent("li").hasClass("active")){var e=c.find(".active:last a"),f=a.Event("hide.bs.tab",{relatedTarget:b[0]}),g=a.Event("show.bs.tab",{relatedTarget:e[0]});if(e.trigger(f),b.trigger(g),!g.isDefaultPrevented()&&!f.isDefaultPrevented()){var h=a(d);this.activate(b.closest("li"),c),this.activate(h,h.parent(),function(){e.trigger({type:"hidden.bs.tab",relatedTarget:b[0]}),b.trigger({type:"shown.bs.tab",relatedTarget:e[0]})})}}},c.prototype.activate=function(b,d,e){function f(){g.removeClass("active").find("> .dropdown-menu > .active").removeClass("active").end().find('[data-toggle="tab"]').attr("aria-expanded",!1),b.addClass("active").find('[data-toggle="tab"]').attr("aria-expanded",!0),h?(b[0].offsetWidth,b.addClass("in")):b.removeClass("fade"),b.parent(".dropdown-menu").length&&b.closest("li.dropdown").addClass("active").end().find('[data-toggle="tab"]').attr("aria-expanded",!0),e&&e()}var g=d.find("> .active"),h=e&&a.support.transition&&(g.length&&g.hasClass("fade")||!!d.find("> .fade").length);g.length&&h?g.one("bsTransitionEnd",f).emulateTransitionEnd(c.TRANSITION_DURATION):f(),g.removeClass("in")};var d=a.fn.tab;a.fn.tab=b,a.fn.tab.Constructor=c,a.fn.tab.noConflict=function(){return a.fn.tab=d,this};var e=function(c){c.preventDefault(),b.call(a(this),"show")};a(document).on("click.bs.tab.data-api",'[data-toggle="tab"]',e).on("click.bs.tab.data-api",'[data-toggle="pill"]',e)}(jQuery),+function(a){"use strict";function b(b){return this.each(function(){var d=a(this),e=d.data("bs.affix"),f="object"==typeof b&&b;e||d.data("bs.affix",e=new c(this,f)),"string"==typeof b&&e[b]()})}var c=function(b,d){this.options=a.extend({},c.DEFAULTS,d),this.$target=a(this.options.target).on("scroll.bs.affix.data-api",a.proxy(this.checkPosition,this)).on("click.bs.affix.data-api",a.proxy(this.checkPositionWithEventLoop,this)),this.$element=a(b),this.affixed=null,this.unpin=null,this.pinnedOffset=null,this.checkPosition()};c.VERSION="3.3.7",c.RESET="affix affix-top affix-bottom",c.DEFAULTS={offset:0,target:window},c.prototype.getState=function(a,b,c,d){var e=this.$target.scrollTop(),f=this.$element.offset(),g=this.$target.height();if(null!=c&&"top"==this.affixed)return e<c&&"top";if("bottom"==this.affixed)return null!=c?!(e+this.unpin<=f.top)&&"bottom":!(e+g<=a-d)&&"bottom";var h=null==this.affixed,i=h?e:f.top,j=h?g:b;return null!=c&&e<=c?"top":null!=d&&i+j>=a-d&&"bottom"},c.prototype.getPinnedOffset=function(){if(this.pinnedOffset)return this.pinnedOffset;this.$element.removeClass(c.RESET).addClass("affix");var a=this.$target.scrollTop(),b=this.$element.offset();return this.pinnedOffset=b.top-a},c.prototype.checkPositionWithEventLoop=function(){setTimeout(a.proxy(this.checkPosition,this),1)},c.prototype.checkPosition=function(){if(this.$element.is(":visible")){var b=this.$element.height(),d=this.options.offset,e=d.top,f=d.bottom,g=Math.max(a(document).height(),a(document.body).height());"object"!=typeof d&&(f=e=d),"function"==typeof e&&(e=d.top(this.$element)),"function"==typeof f&&(f=d.bottom(this.$element));var h=this.getState(g,b,e,f);if(this.affixed!=h){null!=this.unpin&&this.$element.css("top","");var i="affix"+(h?"-"+h:""),j=a.Event(i+".bs.affix");if(this.$element.trigger(j),j.isDefaultPrevented())return;this.affixed=h,this.unpin="bottom"==h?this.getPinnedOffset():null,this.$element.removeClass(c.RESET).addClass(i).trigger(i.replace("affix","affixed")+".bs.affix")}"bottom"==h&&this.$element.offset({top:g-b-f})}};var d=a.fn.affix;a.fn.affix=b,a.fn.affix.Constructor=c,a.fn.affix.noConflict=function(){return a.fn.affix=d,this},a(window).on("load",function(){a('[data-spy="affix"]').each(function(){var c=a(this),d=c.data();d.offset=d.offset||{},null!=d.offsetBottom&&(d.offset.bottom=d.offsetBottom),null!=d.offsetTop&&(d.offset.top=d.offsetTop),b.call(c,d)})})}(jQuery);
define("bootstrap", ["jquery"], function(){});

require.config({
    urlArgs: "v=" + requirejs.s.contexts._.config.config.site.version,
    packages: [{
            name: 'moment',
            location: '../libs/moment',
            main: 'moment'
        }
    ],
    //在打包压缩时将会把include中的模块合并到主文件中
    include: ['css', 'layer', 'toastr', 'fast', 'backend', 'table', 'form', 'dragsort', 'drag', 'drop', 'addtabs', 'selectpage'],
    paths: {
        'lang': "empty:",
        'form': 'require-form',
        'table': 'require-table',
        'upload': 'require-upload',
        'validator': 'require-validator',
        'drag': 'jquery.drag.min',
        'drop': 'jquery.drop.min',
        'echarts': 'echarts.min',
        'echarts-theme': 'echarts-theme',
        'adminlte': 'adminlte',
        'bootstrap-table-commonsearch': 'bootstrap-table-commonsearch',
        'bootstrap-table-template': 'bootstrap-table-template',
        //
        // 以下的包从bower的libs目录加载
        'jquery': '../libs/jquery/dist/jquery.min',
        'bootstrap': '../libs/bootstrap/dist/js/bootstrap.min',
        'bootstrap-datetimepicker': '../libs/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min',
        'bootstrap-select': '../libs/bootstrap-select/dist/js/bootstrap-select.min',
        'bootstrap-select-lang': '../libs/bootstrap-select/dist/js/i18n/defaults-zh_CN',
        'bootstrap-table': '../libs/bootstrap-table/dist/bootstrap-table.min',
        'bootstrap-table-export': '../libs/bootstrap-table/dist/extensions/export/bootstrap-table-export.min',
        'bootstrap-table-mobile': '../libs/bootstrap-table/dist/extensions/mobile/bootstrap-table-mobile',
        'bootstrap-table-lang': '../libs/bootstrap-table/dist/locale/bootstrap-table-zh-CN',
        'tableexport': '../libs/tableExport.jquery.plugin/tableExport.min',
        'dragsort': '../libs/dragsort/jquery.dragsort',
        'qrcode': '../libs/jquery-qrcode/jquery.qrcode.min',
        'sortable': '../libs/Sortable/Sortable.min',
        'addtabs': '../libs/jquery-addtabs/jquery.addtabs',
        'slimscroll': '../libs/jquery-slimscroll/jquery.slimscroll',
        'summernote': '../libs/summernote/dist/lang/summernote-zh-CN.min',
        'validator-core': '../libs/nice-validator/dist/jquery.validator',
        'validator-lang': '../libs/nice-validator/dist/local/zh-CN',
        'plupload': '../libs/plupload/js/plupload.min',
        'toastr': '../libs/toastr/toastr',
        'jstree': '../libs/jstree/dist/jstree.min',
        'layer': '../libs/layer/src/layer',
        'cookie': '../libs/jquery.cookie/jquery.cookie',
        'cxselect': '../libs/jquery-cxselect/js/jquery.cxselect',
        'template': '../libs/art-template/dist/template-native',
        'selectpage': '../libs/selectpage/selectpage',
        'citypicker': '../libs/city-picker/dist/js/city-picker.min',
        'citypicker-data': '../libs/city-picker/dist/js/city-picker.data',
    },
    // shim依赖配置
    shim: {
        'addons': ['backend'],
        'bootstrap': ['jquery'],
        'bootstrap-table': {
            deps: [
                'bootstrap',
//                'css!../libs/bootstrap-table/dist/bootstrap-table.min.css'
            ],
            exports: '$.fn.bootstrapTable'
        },
        'bootstrap-table-lang': {
            deps: ['bootstrap-table'],
            exports: '$.fn.bootstrapTable.defaults'
        },
        'bootstrap-table-export': {
            deps: ['bootstrap-table', 'tableexport'],
            exports: '$.fn.bootstrapTable.defaults'
        },
        'bootstrap-table-mobile': {
            deps: ['bootstrap-table'],
            exports: '$.fn.bootstrapTable.defaults'
        },
        'bootstrap-table-advancedsearch': {
            deps: ['bootstrap-table'],
            exports: '$.fn.bootstrapTable.defaults'
        },
        'bootstrap-table-commonsearch': {
            deps: ['bootstrap-table'],
            exports: '$.fn.bootstrapTable.defaults'
        },
        'bootstrap-table-template': {
            deps: ['bootstrap-table', 'template'],
            exports: '$.fn.bootstrapTable.defaults'
        },
        'tableexport': {
            deps: ['jquery'],
            exports: '$.fn.extend'
        },
        'slimscroll': {
            deps: ['jquery'],
            exports: '$.fn.extend'
        },
        'adminlte': {
            deps: ['bootstrap', 'slimscroll'],
            exports: '$.AdminLTE'
        },
        'bootstrap-datetimepicker': [
            'moment/locale/zh-cn',
//            'css!../libs/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css',
        ],
        'bootstrap-select': ['css!../libs/bootstrap-select/dist/css/bootstrap-select.min.css', ],
        'bootstrap-select-lang': ['bootstrap-select'],
        'summernote': ['../libs/summernote/dist/summernote.min', 'css!../libs/summernote/dist/summernote.css'],
//        'toastr': ['css!../libs/toastr/toastr.min.css'],
        'jstree': ['css!../libs/jstree/dist/themes/default/style.css', ],
        'plupload': {
            deps: ['../libs/plupload/js/moxie.min'],
            exports: "plupload"
        },
//        'layer': ['css!../libs/layer/build/skin/default/layer.css'],
//        'validator-core': ['css!../libs/nice-validator/dist/jquery.validator.css'],
        'validator-lang': ['validator-core'],
//        'selectpage': ['css!../libs/selectpage/selectpage.css'],
        'citypicker': ['citypicker-data', 'css!../libs/city-picker/dist/css/city-picker.css']
    },
    baseUrl: requirejs.s.contexts._.config.config.site.cdnurl + '/assets/js/', //资源基础路径
    map: {
        '*': {
            'css': '../libs/require-css/css.min'
        }
    },
    waitSeconds: 30,
    charset: 'utf-8' // 文件编码
});

require(['jquery', 'bootstrap'], function ($, undefined) {
    //初始配置
    var Config = requirejs.s.contexts._.config.config;
    //将Config渲染到全局
    window.Config = Config;
    // 配置语言包的路径
    var paths = {};
    paths['lang'] = Config.moduleurl + '/ajax/lang?callback=define&controllername=' + Config.controllername;
    // 避免目录冲突
    paths['backend/'] = 'backend/';
    require.config({paths: paths});

    // 初始化
    $(function () {
        require(['fast'], function (Fast) {
            require(['backend', 'addons'], function (Backend, Addons) {
                //加载相应模块
                if (Config.jsname) {
                    require([Config.jsname], function (Controller) {
                        Controller[Config.actionname] != undefined && Controller[Config.actionname]();
                    }, function (e) {
                        console.error(e);
                        // 这里可捕获模块加载的错误
                    });
                }
            });
        });
    });
});

define("require-backend", function(){});

define('../libs/require-css/css.min',[],function(){if("undefined"==typeof window)return{load:function(a,b,c){c()}};var a=document.getElementsByTagName("head")[0],b=window.navigator.userAgent.match(/Trident\/([^ ;]*)|AppleWebKit\/([^ ;]*)|Opera\/([^ ;]*)|rv\:([^ ;]*)(.*?)Gecko\/([^ ;]*)|MSIE\s([^ ;]*)|AndroidWebKit\/([^ ;]*)/)||0,c=!1,d=!0;b[1]||b[7]?c=parseInt(b[1])<6||parseInt(b[7])<=9:b[2]||b[8]?d=!1:b[4]&&(c=parseInt(b[4])<18);var e={};e.pluginBuilder="./css-builder";var f,g,h,i=function(){f=document.createElement("style"),a.appendChild(f),g=f.styleSheet||f.sheet},j=0,k=[],l=function(a){g.addImport(a),f.onload=function(){m()},j++,31==j&&(i(),j=0)},m=function(){h();var a=k.shift();return a?(h=a[1],void l(a[0])):void(h=null)},n=function(a,b){if(g&&g.addImport||i(),g&&g.addImport)h?k.push([a,b]):(l(a),h=b);else{f.textContent='@import "'+a+'";';var c=setInterval(function(){try{f.sheet.cssRules,clearInterval(c),b()}catch(a){}},10)}},o=function(b,c){var e=document.createElement("link");if(e.type="text/css",e.rel="stylesheet",d)e.onload=function(){e.onload=function(){},setTimeout(c,7)};else var f=setInterval(function(){for(var a=0;a<document.styleSheets.length;a++){var b=document.styleSheets[a];if(b.href==e.href)return clearInterval(f),c()}},10);e.href=b,a.appendChild(e)};return e.normalize=function(a,b){return".css"==a.substr(a.length-4,4)&&(a=a.substr(0,a.length-4)),b(a)},e.load=function(a,b,d,e){(c?n:o)(b.toUrl(a+".css"),d)},e});
/**

 @Name：layer v3.0.3 Web弹层组件
 @Author：贤心
 @Site：http://layer.layui.com
 @License：MIT
    
 */

;!function(window, undefined){
"use strict";

var isLayui = window.layui && layui.define, $, win, ready = {
  getPath: function(){
    var js = document.scripts, script = js[js.length - 1], jsPath = script.src;
    if(script.getAttribute('merge')) return;
    return jsPath.substring(0, jsPath.lastIndexOf("/") + 1);
  }(),

  config: {}, end: {}, minIndex: 0, minLeft: [],
  btn: ['&#x786E;&#x5B9A;', '&#x53D6;&#x6D88;'],

  //五种原始层模式
  type: ['dialog', 'page', 'iframe', 'loading', 'tips']
};

//默认内置方法。
var layer = {
  v: '3.0.3',
  ie: function(){ //ie版本
    var agent = navigator.userAgent.toLowerCase();
    return (!!window.ActiveXObject || "ActiveXObject" in window) ? (
      (agent.match(/msie\s(\d+)/) || [])[1] || '11' //由于ie11并没有msie的标识
    ) : false;
  }(),
  index: (window.layer && window.layer.v) ? 100000 : 0,
  path: ready.getPath,
  config: function(options, fn){
    options = options || {};
    layer.cache = ready.config = $.extend({}, ready.config, options);
    layer.path = ready.config.path || layer.path;
    typeof options.extend === 'string' && (options.extend = [options.extend]);
    
    if(ready.config.path) layer.ready();
    
    if(!options.extend) return this;
    
    isLayui 
      ? layui.addcss('modules/layer/' + options.extend)
    : layer.link('skin/' + options.extend);
    
    return this;
  },
  
  //载入CSS配件
  link: function(href, fn, cssname){
    
    //未设置路径，则不主动加载css
    if(!layer.path) return;
    
    var head = $('head')[0], link = document.createElement('link');
    if(typeof fn === 'string') cssname = fn;
    var app = (cssname || href).replace(/\.|\//g, '');
    var id = 'layuicss-'+app, timeout = 0;
    
    link.rel = 'stylesheet';
    link.href = layer.path + href;
    link.id = id;
    
    if(!$('#'+ id)[0]){
      head.appendChild(link);
    }
    
    if(typeof fn !== 'function') return;
    
    //轮询css是否加载完毕
    (function poll() {
      if(++timeout > 8 * 1000 / 100){
        return window.console && console.error('layer.css: Invalid');
      };
      parseInt($('#'+id).css('width')) === 1989 ? fn() : setTimeout(poll, 100);
    }());
  },
  
  ready: function(callback){
    var cssname = 'skinlayercss', ver = '303';
    isLayui ? layui.addcss('modules/layer/default/layer.css?v='+layer.v+ver, callback, cssname)
    : layer.link('skin/default/layer.css?v='+layer.v+ver, callback, cssname);
    return this;
  },
  
  //各种快捷引用
  alert: function(content, options, yes){
    var type = typeof options === 'function';
    if(type) yes = options;
    return layer.open($.extend({
      content: content,
      yes: yes
    }, type ? {} : options));
  }, 
  
  confirm: function(content, options, yes, cancel){ 
    var type = typeof options === 'function';
    if(type){
      cancel = yes;
      yes = options;
    }
    return layer.open($.extend({
      content: content,
      btn: ready.btn,
      yes: yes,
      btn2: cancel
    }, type ? {} : options));
  },
  
  msg: function(content, options, end){ //最常用提示层
    var type = typeof options === 'function', rskin = ready.config.skin;
    var skin = (rskin ? rskin + ' ' + rskin + '-msg' : '')||'layui-layer-msg';
    var anim = doms.anim.length - 1;
    if(type) end = options;
    return layer.open($.extend({
      content: content,
      time: 3000,
      shade: false,
      skin: skin,
      title: false,
      closeBtn: false,
      btn: false,
      resize: false,
      end: end
    }, (type && !ready.config.skin) ? {
      skin: skin + ' layui-layer-hui',
      anim: anim
    } : function(){
       options = options || {};
       if(options.icon === -1 || options.icon === undefined && !ready.config.skin){
         options.skin = skin + ' ' + (options.skin||'layui-layer-hui');
       }
       return options;
    }()));  
  },
  
  load: function(icon, options){
    return layer.open($.extend({
      type: 3,
      icon: icon || 0,
      resize: false,
      shade: 0.01
    }, options));
  }, 
  
  tips: function(content, follow, options){
    return layer.open($.extend({
      type: 4,
      content: [content, follow],
      closeBtn: false,
      time: 3000,
      shade: false,
      resize: false,
      fixed: false,
      maxWidth: 210
    }, options));
  }
};

var Class = function(setings){  
  var that = this;
  that.index = ++layer.index;
  that.config = $.extend({}, that.config, ready.config, setings);
  document.body ? that.creat() : setTimeout(function(){
    that.creat();
  }, 30);
};

Class.pt = Class.prototype;

//缓存常用字符
var doms = ['layui-layer', '.layui-layer-title', '.layui-layer-main', '.layui-layer-dialog', 'layui-layer-iframe', 'layui-layer-content', 'layui-layer-btn', 'layui-layer-close'];
doms.anim = ['layer-anim', 'layer-anim-01', 'layer-anim-02', 'layer-anim-03', 'layer-anim-04', 'layer-anim-05', 'layer-anim-06'];

//默认配置
Class.pt.config = {
  type: 0,
  shade: 0.3,
  fixed: true,
  move: doms[1],
  title: '&#x4FE1;&#x606F;',
  offset: 'auto',
  area: 'auto',
  closeBtn: 1,
  time: 0, //0表示不自动关闭
  zIndex: 19891014, 
  maxWidth: 360,
  anim: 0,
  isOutAnim: true,
  icon: -1,
  moveType: 1,
  resize: true,
  scrollbar: true, //是否允许浏览器滚动条
  tips: 2
};

//容器
Class.pt.vessel = function(conType, callback){
  var that = this, times = that.index, config = that.config;
  var zIndex = config.zIndex + times, titype = typeof config.title === 'object';
  var ismax = config.maxmin && (config.type === 1 || config.type === 2);
  var titleHTML = (config.title ? '<div class="layui-layer-title" style="'+ (titype ? config.title[1] : '') +'">' 
    + (titype ? config.title[0] : config.title) 
  + '</div>' : '');
  
  config.zIndex = zIndex;
  callback([
    //遮罩
    config.shade ? ('<div class="layui-layer-shade" id="layui-layer-shade'+ times +'" times="'+ times +'" style="'+ ('z-index:'+ (zIndex-1) +'; background-color:'+ (config.shade[1]||'#000') +'; opacity:'+ (config.shade[0]||config.shade) +'; filter:alpha(opacity='+ (config.shade[0]*100||config.shade*100) +');') +'"></div>') : '',
    
    //主体
    '<div class="'+ doms[0] + (' layui-layer-'+ready.type[config.type]) + (((config.type == 0 || config.type == 2) && !config.shade) ? ' layui-layer-border' : '') + ' ' + (config.skin||'') +'" id="'+ doms[0] + times +'" type="'+ ready.type[config.type] +'" times="'+ times +'" showtime="'+ config.time +'" conType="'+ (conType ? 'object' : 'string') +'" style="z-index: '+ zIndex +'; width:'+ config.area[0] + ';height:' + config.area[1] + (config.fixed ? '' : ';position:absolute;') +'">'
      + (conType && config.type != 2 ? '' : titleHTML)
      + '<div id="'+ (config.id||'') +'" class="layui-layer-content'+ ((config.type == 0 && config.icon !== -1) ? ' layui-layer-padding' :'') + (config.type == 3 ? ' layui-layer-loading'+config.icon : '') +'">'
        + (config.type == 0 && config.icon !== -1 ? '<i class="layui-layer-ico layui-layer-ico'+ config.icon +'"></i>' : '')
        + (config.type == 1 && conType ? '' : (config.content||''))
      + '</div>'
      + '<span class="layui-layer-setwin">'+ function(){
        var closebtn = ismax ? '<a class="layui-layer-min" href="javascript:;"><cite></cite></a><a class="layui-layer-ico layui-layer-max" href="javascript:;"></a>' : '';
        config.closeBtn && (closebtn += '<a class="layui-layer-ico '+ doms[7] +' '+ doms[7] + (config.title ? config.closeBtn : (config.type == 4 ? '1' : '2')) +'" href="javascript:;"></a>');
        return closebtn;
      }() + '</span>'
      + (config.btn ? function(){
        var button = '';
        typeof config.btn === 'string' && (config.btn = [config.btn]);
        for(var i = 0, len = config.btn.length; i < len; i++){
          button += '<a class="'+ doms[6] +''+ i +'">'+ config.btn[i] +'</a>'
        }
        return '<div class="'+ doms[6] +' layui-layer-btn-'+ (config.btnAlign||'') +'">'+ button +'</div>'
      }() : '')
      + (config.resize ? '<span class="layui-layer-resize"></span>' : '')
    + '</div>'
  ], titleHTML, $('<div class="layui-layer-move"></div>'));
  return that;
};

//创建骨架
Class.pt.creat = function(){
  var that = this
  ,config = that.config
  ,times = that.index, nodeIndex
  ,content = config.content
  ,conType = typeof content === 'object'
  ,body = $('body');
  
  if(config.id && $('#'+config.id)[0])  return;

  if(typeof config.area === 'string'){
    config.area = config.area === 'auto' ? ['', ''] : [config.area, ''];
  }
  
  //anim兼容旧版shift
  if(config.shift){
    config.anim = config.shift;
  }
  
  if(layer.ie == 6){
    config.fixed = false;
  }
  
  switch(config.type){
    case 0:
      config.btn = ('btn' in config) ? config.btn : ready.btn[0];
      layer.closeAll('dialog');
    break;
    case 2:
      var content = config.content = conType ? config.content : [config.content||'http://layer.layui.com', 'auto'];
      config.content = '<iframe scrolling="'+ (config.content[1]||'auto') +'" allowtransparency="true" id="'+ doms[4] +''+ times +'" name="'+ doms[4] +''+ times +'" onload="this.className=\'\';" class="layui-layer-load" frameborder="0" src="' + config.content[0] + '"></iframe>';
    break;
    case 3:
      delete config.title;
      delete config.closeBtn;
      config.icon === -1 && (config.icon === 0);
      layer.closeAll('loading');
    break;
    case 4:
      conType || (config.content = [config.content, 'body']);
      config.follow = config.content[1];
      config.content = config.content[0] + '<i class="layui-layer-TipsG"></i>';
      delete config.title;
      config.tips = typeof config.tips === 'object' ? config.tips : [config.tips, true];
      config.tipsMore || layer.closeAll('tips');
    break;
  }
  
  //建立容器
  that.vessel(conType, function(html, titleHTML, moveElem){
    body.append(html[0]);
    conType ? function(){
      (config.type == 2 || config.type == 4) ? function(){
        $('body').append(html[1]);
      }() : function(){
        if(!content.parents('.'+doms[0])[0]){
          content.data('display', content.css('display')).show().addClass('layui-layer-wrap').wrap(html[1]);
          $('#'+ doms[0] + times).find('.'+doms[5]).before(titleHTML);
        }
      }();
    }() : body.append(html[1]);
    $('.layui-layer-move')[0] || body.append(ready.moveElem = moveElem);
    that.layero = $('#'+ doms[0] + times);
    config.scrollbar || doms.html.css('overflow', 'hidden').attr('layer-full', times);
  }).auto(times);

  config.type == 2 && layer.ie == 6 && that.layero.find('iframe').attr('src', content[0]);

  //坐标自适应浏览器窗口尺寸
  config.type == 4 ? that.tips() : that.offset();
  if(config.fixed){
    win.on('resize', function(){
      that.offset();
      (/^\d+%$/.test(config.area[0]) || /^\d+%$/.test(config.area[1])) && that.auto(times);
      config.type == 4 && that.tips();
    });
  }
  
  config.time <= 0 || setTimeout(function(){
    layer.close(that.index)
  }, config.time);
  that.move().callback();
  
  //为兼容jQuery3.0的css动画影响元素尺寸计算
  if(doms.anim[config.anim]){
    that.layero.addClass(doms.anim[config.anim]);
  };
  
  //记录关闭动画
  if(config.isOutAnim){
    that.layero.data('isOutAnim', true);
  }
};

//自适应
Class.pt.auto = function(index){
  var that = this, config = that.config, layero = $('#'+ doms[0] + index);
  if(config.area[0] === '' && config.maxWidth > 0){
    //为了修复IE7下一个让人难以理解的bug
    if(layer.ie && layer.ie < 8 && config.btn){
      layero.width(layero.innerWidth());
    }
    layero.outerWidth() > config.maxWidth && layero.width(config.maxWidth);
  }
  var area = [layero.innerWidth(), layero.innerHeight()];
  var titHeight = layero.find(doms[1]).outerHeight() || 0;
  var btnHeight = layero.find('.'+doms[6]).outerHeight() || 0;
  function setHeight(elem){
    elem = layero.find(elem);
    elem.height(area[1] - titHeight - btnHeight - 2*(parseFloat(elem.css('padding-top'))|0));
  }
  switch(config.type){
    case 2: 
      setHeight('iframe');
    break;
    default:
      if(config.area[1] === ''){
        if(config.fixed && area[1] >= win.height()){
          area[1] = win.height();
          setHeight('.'+doms[5]);
        }
      } else {
        setHeight('.'+doms[5]);
      }
    break;
  }
  return that;
};

//计算坐标
Class.pt.offset = function(){
  var that = this, config = that.config, layero = that.layero;
  var area = [layero.outerWidth(), layero.outerHeight()];
  var type = typeof config.offset === 'object';
  that.offsetTop = (win.height() - area[1])/2;
  that.offsetLeft = (win.width() - area[0])/2;
  
  if(type){
    that.offsetTop = config.offset[0];
    that.offsetLeft = config.offset[1]||that.offsetLeft;
  } else if(config.offset !== 'auto'){
    
    if(config.offset === 't'){ //上
      that.offsetTop = 0;
    } else if(config.offset === 'r'){ //右
      that.offsetLeft = win.width() - area[0];
    } else if(config.offset === 'b'){ //下
      that.offsetTop = win.height() - area[1];
    } else if(config.offset === 'l'){ //左
      that.offsetLeft = 0;
    } else if(config.offset === 'lt'){ //左上角
      that.offsetTop = 0;
      that.offsetLeft = 0;
    } else if(config.offset === 'lb'){ //左下角
      that.offsetTop = win.height() - area[1];
      that.offsetLeft = 0;
    } else if(config.offset === 'rt'){ //右上角
      that.offsetTop = 0;
      that.offsetLeft = win.width() - area[0];
    } else if(config.offset === 'rb'){ //右下角
      that.offsetTop = win.height() - area[1];
      that.offsetLeft = win.width() - area[0];
    } else {
      that.offsetTop = config.offset;
    }
    
  }
 
  if(!config.fixed){
    that.offsetTop = /%$/.test(that.offsetTop) ? 
      win.height()*parseFloat(that.offsetTop)/100
    : parseFloat(that.offsetTop);
    that.offsetLeft = /%$/.test(that.offsetLeft) ? 
      win.width()*parseFloat(that.offsetLeft)/100
    : parseFloat(that.offsetLeft);
    that.offsetTop += win.scrollTop();
    that.offsetLeft += win.scrollLeft();
  }
  
  if(layero.attr('minLeft')){
    that.offsetTop = win.height() - (layero.find(doms[1]).outerHeight() || 0);
    that.offsetLeft = layero.css('left');
  }

  layero.css({top: that.offsetTop, left: that.offsetLeft});
};

//Tips
Class.pt.tips = function(){
  var that = this, config = that.config, layero = that.layero;
  var layArea = [layero.outerWidth(), layero.outerHeight()], follow = $(config.follow);
  if(!follow[0]) follow = $('body');
  var goal = {
    width: follow.outerWidth(),
    height: follow.outerHeight(),
    top: follow.offset().top,
    left: follow.offset().left
  }, tipsG = layero.find('.layui-layer-TipsG');
  
  var guide = config.tips[0];
  config.tips[1] || tipsG.remove();
  
  goal.autoLeft = function(){
    if(goal.left + layArea[0] - win.width() > 0){
      goal.tipLeft = goal.left + goal.width - layArea[0];
      tipsG.css({right: 12, left: 'auto'});
    } else {
      goal.tipLeft = goal.left;
    };
  };
  
  //辨别tips的方位
  goal.where = [function(){ //上        
    goal.autoLeft();
    goal.tipTop = goal.top - layArea[1] - 10;
    tipsG.removeClass('layui-layer-TipsB').addClass('layui-layer-TipsT').css('border-right-color', config.tips[1]);
  }, function(){ //右
    goal.tipLeft = goal.left + goal.width + 10;
    goal.tipTop = goal.top;
    tipsG.removeClass('layui-layer-TipsL').addClass('layui-layer-TipsR').css('border-bottom-color', config.tips[1]); 
  }, function(){ //下
    goal.autoLeft();
    goal.tipTop = goal.top + goal.height + 10;
    tipsG.removeClass('layui-layer-TipsT').addClass('layui-layer-TipsB').css('border-right-color', config.tips[1]);
  }, function(){ //左
    goal.tipLeft = goal.left - layArea[0] - 10;
    goal.tipTop = goal.top;
    tipsG.removeClass('layui-layer-TipsR').addClass('layui-layer-TipsL').css('border-bottom-color', config.tips[1]);
  }];
  goal.where[guide-1]();
  
  /* 8*2为小三角形占据的空间 */
  if(guide === 1){
    goal.top - (win.scrollTop() + layArea[1] + 8*2) < 0 && goal.where[2]();
  } else if(guide === 2){
    win.width() - (goal.left + goal.width + layArea[0] + 8*2) > 0 || goal.where[3]()
  } else if(guide === 3){
    (goal.top - win.scrollTop() + goal.height + layArea[1] + 8*2) - win.height() > 0 && goal.where[0]();
  } else if(guide === 4){
     layArea[0] + 8*2 - goal.left > 0 && goal.where[1]()
  }

  layero.find('.'+doms[5]).css({
    'background-color': config.tips[1], 
    'padding-right': (config.closeBtn ? '30px' : '')
  });
  layero.css({
    left: goal.tipLeft - (config.fixed ? win.scrollLeft() : 0), 
    top: goal.tipTop  - (config.fixed ? win.scrollTop() : 0)
  });
}

//拖拽层
Class.pt.move = function(){
  var that = this
  ,config = that.config
  ,_DOC = $(document)
  ,layero = that.layero
  ,moveElem = layero.find(config.move)
  ,resizeElem = layero.find('.layui-layer-resize')
  ,dict = {};
  
  if(config.move){
    moveElem.css('cursor', 'move');
  }

  moveElem.on('mousedown', function(e){
    e.preventDefault();
    if(config.move){
      dict.moveStart = true;
      dict.offset = [
        e.clientX - parseFloat(layero.css('left'))
        ,e.clientY - parseFloat(layero.css('top'))
      ];
      ready.moveElem.css('cursor', 'move').show();
    }
  });
  
  resizeElem.on('mousedown', function(e){
    e.preventDefault();
    dict.resizeStart = true;
    dict.offset = [e.clientX, e.clientY];
    dict.area = [
      layero.outerWidth()
      ,layero.outerHeight()
    ];
    ready.moveElem.css('cursor', 'se-resize').show();
  });
  
  _DOC.on('mousemove', function(e){

    //拖拽移动
    if(dict.moveStart){
      var X = e.clientX - dict.offset[0]
      ,Y = e.clientY - dict.offset[1]
      ,fixed = layero.css('position') === 'fixed';
      
      e.preventDefault();
      
      dict.stX = fixed ? 0 : win.scrollLeft();
      dict.stY = fixed ? 0 : win.scrollTop();

      //控制元素不被拖出窗口外
      if(!config.moveOut){
        var setRig = win.width() - layero.outerWidth() + dict.stX
        ,setBot = win.height() - layero.outerHeight() + dict.stY;  
        X < dict.stX && (X = dict.stX);
        X > setRig && (X = setRig); 
        Y < dict.stY && (Y = dict.stY);
        Y > setBot && (Y = setBot);
      }
      
      layero.css({
        left: X
        ,top: Y
      });
    }
    
    //Resize
    if(config.resize && dict.resizeStart){
      var X = e.clientX - dict.offset[0]
      ,Y = e.clientY - dict.offset[1];
      
      e.preventDefault();
      
      layer.style(that.index, {
        width: dict.area[0] + X
        ,height: dict.area[1] + Y
      })
      dict.isResize = true;
      config.resizing && config.resizing(layero);
    }
  }).on('mouseup', function(e){
    if(dict.moveStart){
      delete dict.moveStart;
      ready.moveElem.hide();
      config.moveEnd && config.moveEnd(layero);
    }
    if(dict.resizeStart){
      delete dict.resizeStart;
      ready.moveElem.hide();
    }
  });
  
  return that;
};

Class.pt.callback = function(){
  var that = this, layero = that.layero, config = that.config;
  that.openLayer();
  if(config.success){
    if(config.type == 2){
      layero.find('iframe').on('load', function(){
        config.success(layero, that.index);
      });
    } else {
      config.success(layero, that.index);
    }
  }
  layer.ie == 6 && that.IE6(layero);
  
  //按钮
  layero.find('.'+ doms[6]).children('a').on('click', function(){
    var index = $(this).index();
    if(index === 0){
      if(config.yes){
        config.yes(that.index, layero)
      } else if(config['btn1']){
        config['btn1'](that.index, layero)
      } else {
        layer.close(that.index);
      }
    } else {
      var close = config['btn'+(index+1)] && config['btn'+(index+1)](that.index, layero);
      close === false || layer.close(that.index);
    }
  });
  
  //取消
  function cancel(){
    var close = config.cancel && config.cancel(that.index, layero);
    close === false || layer.close(that.index);
  }
  
  //右上角关闭回调
  layero.find('.'+ doms[7]).on('click', cancel);
  
  //点遮罩关闭
  if(config.shadeClose){
    $('#layui-layer-shade'+ that.index).on('click', function(){
      layer.close(that.index);
    });
  } 
  
  //最小化
  layero.find('.layui-layer-min').on('click', function(){
    var min = config.min && config.min(layero);
    min === false || layer.min(that.index, config); 
  });
  
  //全屏/还原
  layero.find('.layui-layer-max').on('click', function(){
    if($(this).hasClass('layui-layer-maxmin')){
      layer.restore(that.index);
      config.restore && config.restore(layero);
    } else {
      layer.full(that.index, config);
      setTimeout(function(){
        config.full && config.full(layero);
      }, 100);
    }
  });

  config.end && (ready.end[that.index] = config.end);
};

//for ie6 恢复select
ready.reselect = function(){
  $.each($('select'), function(index , value){
    var sthis = $(this);
    if(!sthis.parents('.'+doms[0])[0]){
      (sthis.attr('layer') == 1 && $('.'+doms[0]).length < 1) && sthis.removeAttr('layer').show(); 
    }
    sthis = null;
  });
}; 

Class.pt.IE6 = function(layero){
  //隐藏select
  $('select').each(function(index , value){
    var sthis = $(this);
    if(!sthis.parents('.'+doms[0])[0]){
      sthis.css('display') === 'none' || sthis.attr({'layer' : '1'}).hide();
    }
    sthis = null;
  });
};

//需依赖原型的对外方法
Class.pt.openLayer = function(){
  var that = this;
  
  //置顶当前窗口
  layer.zIndex = that.config.zIndex;
  layer.setTop = function(layero){
    var setZindex = function(){
      layer.zIndex++;
      layero.css('z-index', layer.zIndex + 1);
    };
    layer.zIndex = parseInt(layero[0].style.zIndex);
    layero.on('mousedown', setZindex);
    return layer.zIndex;
  };
};

ready.record = function(layero){
  var area = [
    layero.width(),
    layero.height(),
    layero.position().top, 
    layero.position().left + parseFloat(layero.css('margin-left'))
  ];
  layero.find('.layui-layer-max').addClass('layui-layer-maxmin');
  layero.attr({area: area});
};

ready.rescollbar = function(index){
  if(doms.html.attr('layer-full') == index){
    if(doms.html[0].style.removeProperty){
      doms.html[0].style.removeProperty('overflow');
    } else {
      doms.html[0].style.removeAttribute('overflow');
    }
    doms.html.removeAttr('layer-full');
  }
};

/** 内置成员 */

window.layer = layer;

//获取子iframe的DOM
layer.getChildFrame = function(selector, index){
  index = index || $('.'+doms[4]).attr('times');
  return $('#'+ doms[0] + index).find('iframe').contents().find(selector);  
};

//得到当前iframe层的索引，子iframe时使用
layer.getFrameIndex = function(name){
  return $('#'+ name).parents('.'+doms[4]).attr('times');
};

//iframe层自适应宽高
layer.iframeAuto = function(index){
  if(!index) return;
  var heg = layer.getChildFrame('html', index).outerHeight();
  var layero = $('#'+ doms[0] + index);
  var titHeight = layero.find(doms[1]).outerHeight() || 0;
  var btnHeight = layero.find('.'+doms[6]).outerHeight() || 0;
  layero.css({height: heg + titHeight + btnHeight});
  layero.find('iframe').css({height: heg});
};

//重置iframe url
layer.iframeSrc = function(index, url){
  $('#'+ doms[0] + index).find('iframe').attr('src', url);
};

//设定层的样式
layer.style = function(index, options, limit){
  var layero = $('#'+ doms[0] + index)
  ,contElem = layero.find('.layui-layer-content')
  ,type = layero.attr('type')
  ,titHeight = layero.find(doms[1]).outerHeight() || 0
  ,btnHeight = layero.find('.'+doms[6]).outerHeight() || 0
  ,minLeft = layero.attr('minLeft');
  
  if(type === ready.type[3] || type === ready.type[4]){
    return;
  }
  
  if(!limit){
    if(parseFloat(options.width) <= 260){
      options.width = 260;
    };
    
    if(parseFloat(options.height) - titHeight - btnHeight <= 64){
      options.height = 64 + titHeight + btnHeight;
    };
  }
  
  layero.css(options);
  btnHeight = layero.find('.'+doms[6]).outerHeight();
  
  if(type === ready.type[2]){
    layero.find('iframe').css({
      height: parseFloat(options.height) - titHeight - btnHeight
    });
  } else {
    contElem.css({
      height: parseFloat(options.height) - titHeight - btnHeight
      - parseFloat(contElem.css('padding-top'))
      - parseFloat(contElem.css('padding-bottom'))
    })
  }
};

//最小化
layer.min = function(index, options){
  var layero = $('#'+ doms[0] + index)
  ,titHeight = layero.find(doms[1]).outerHeight() || 0
  ,left = layero.attr('minLeft') || (181*ready.minIndex)+'px'
  ,position = layero.css('position');
  
  ready.record(layero);
  
  if(ready.minLeft[0]){
    left = ready.minLeft[0];
    ready.minLeft.shift();
  }
  
  layero.attr('position', position);
  
  layer.style(index, {
    width: 180
    ,height: titHeight
    ,left: left
    ,top: win.height() - titHeight
    ,position: 'fixed'
    ,overflow: 'hidden'
  }, true);

  layero.find('.layui-layer-min').hide();
  layero.attr('type') === 'page' && layero.find(doms[4]).hide();
  ready.rescollbar(index);
  
  if(!layero.attr('minLeft')){
    ready.minIndex++;
  }
  layero.attr('minLeft', left);
};

//还原
layer.restore = function(index){
  var layero = $('#'+ doms[0] + index), area = layero.attr('area').split(',');
  var type = layero.attr('type');
  layer.style(index, {
    width: parseFloat(area[0]), 
    height: parseFloat(area[1]), 
    top: parseFloat(area[2]), 
    left: parseFloat(area[3]),
    position: layero.attr('position'),
    overflow: 'visible'
  }, true);
  layero.find('.layui-layer-max').removeClass('layui-layer-maxmin');
  layero.find('.layui-layer-min').show();
  layero.attr('type') === 'page' && layero.find(doms[4]).show();
  ready.rescollbar(index);
};

//全屏
layer.full = function(index){
  var layero = $('#'+ doms[0] + index), timer;
  ready.record(layero);
  if(!doms.html.attr('layer-full')){
    doms.html.css('overflow','hidden').attr('layer-full', index);
  }
  clearTimeout(timer);
  timer = setTimeout(function(){
    var isfix = layero.css('position') === 'fixed';
    layer.style(index, {
      top: isfix ? 0 : win.scrollTop(),
      left: isfix ? 0 : win.scrollLeft(),
      width: win.width(),
      height: win.height()
    }, true);
    layero.find('.layui-layer-min').hide();
  }, 100);
};

//改变title
layer.title = function(name, index){
  var title = $('#'+ doms[0] + (index||layer.index)).find(doms[1]);
  title.html(name);
};

//关闭layer总方法
layer.close = function(index){
  var layero = $('#'+ doms[0] + index), type = layero.attr('type'), closeAnim = 'layer-anim-close';
  if(!layero[0]) return;
  var WRAP = 'layui-layer-wrap', remove = function(){
    if(type === ready.type[1] && layero.attr('conType') === 'object'){
      layero.children(':not(.'+ doms[5] +')').remove();
      var wrap = layero.find('.'+WRAP);
      for(var i = 0; i < 2; i++){
        wrap.unwrap();
      }
      wrap.css('display', wrap.data('display')).removeClass(WRAP);
    } else {
      //低版本IE 回收 iframe
      if(type === ready.type[2]){
        try {
          var iframe = $('#'+doms[4]+index)[0];
          iframe.contentWindow.document.write('');
          iframe.contentWindow.close();
          layero.find('.'+doms[5])[0].removeChild(iframe);
        } catch(e){}
      }
      layero[0].innerHTML = '';
      layero.remove();
    }
    typeof ready.end[index] === 'function' && ready.end[index]();
    delete ready.end[index];
  };
  
  if(layero.data('isOutAnim')){
    layero.addClass(closeAnim);
  }
  
  $('#layui-layer-moves, #layui-layer-shade' + index).remove();
  layer.ie == 6 && ready.reselect();
  ready.rescollbar(index); 
  if(layero.attr('minLeft')){
    ready.minIndex--;
    ready.minLeft.push(layero.attr('minLeft'));
  }
  
  if((layer.ie && layer.ie < 10) || !layero.data('isOutAnim')){
    remove()
  } else {
    setTimeout(function(){
      remove();
    }, 200);
  }
};

//关闭所有层
layer.closeAll = function(type){
  $.each($('.'+doms[0]), function(){
    var othis = $(this);
    var is = type ? (othis.attr('type') === type) : 1;
    is && layer.close(othis.attr('times'));
    is = null;
  });
};

/** 

  拓展模块，layui开始合并在一起

 */

var cache = layer.cache||{}, skin = function(type){
  return (cache.skin ? (' ' + cache.skin + ' ' + cache.skin + '-'+type) : '');
}; 
 
//仿系统prompt
layer.prompt = function(options, yes){
  var style = '';
  options = options || {};
  
  if(typeof options === 'function') yes = options;
  
  if(options.area){
    var area = options.area;
    style = 'style="width: '+ area[0] +'; height: '+ area[1] + ';"';
    delete options.area;
  }
  var prompt, content = options.formType == 2 ? '<textarea class="layui-layer-input"' + style +'>' + (options.value||'') +'</textarea>' : function(){
    return '<input type="'+ (options.formType == 1 ? 'password' : 'text') +'" class="layui-layer-input" value="'+ (options.value||'') +'">';
  }();
  
  var success = options.success;
  delete options.success;
  
  return layer.open($.extend({
    type: 1
    ,btn: ['&#x786E;&#x5B9A;','&#x53D6;&#x6D88;']
    ,content: content
    ,skin: 'layui-layer-prompt' + skin('prompt')
    ,maxWidth: win.width()
    ,success: function(layero){
      prompt = layero.find('.layui-layer-input');
      prompt.focus();
      typeof success === 'function' && success(layero);
    }
    ,resize: false
    ,yes: function(index){
      var value = prompt.val();
      if(value === ''){
        prompt.focus();
      } else if(value.length > (options.maxlength||500)) {
        layer.tips('&#x6700;&#x591A;&#x8F93;&#x5165;'+ (options.maxlength || 500) +'&#x4E2A;&#x5B57;&#x6570;', prompt, {tips: 1});
      } else {
        yes && yes(value, index, prompt);
      }
    }
  }, options));
};

//tab层
layer.tab = function(options){
  options = options || {};
  
  var tab = options.tab || {}
  ,success = options.success;
  
  delete options.success;
  
  return layer.open($.extend({
    type: 1,
    skin: 'layui-layer-tab' + skin('tab'),
    resize: false,
    title: function(){
      var len = tab.length, ii = 1, str = '';
      if(len > 0){
        str = '<span class="layui-layer-tabnow">'+ tab[0].title +'</span>';
        for(; ii < len; ii++){
          str += '<span>'+ tab[ii].title +'</span>';
        }
      }
      return str;
    }(),
    content: '<ul class="layui-layer-tabmain">'+ function(){
      var len = tab.length, ii = 1, str = '';
      if(len > 0){
        str = '<li class="layui-layer-tabli xubox_tab_layer">'+ (tab[0].content || 'no content') +'</li>';
        for(; ii < len; ii++){
          str += '<li class="layui-layer-tabli">'+ (tab[ii].content || 'no  content') +'</li>';
        }
      }
      return str;
    }() +'</ul>',
    success: function(layero){
      var btn = layero.find('.layui-layer-title').children();
      var main = layero.find('.layui-layer-tabmain').children();
      btn.on('mousedown', function(e){
        e.stopPropagation ? e.stopPropagation() : e.cancelBubble = true;
        var othis = $(this), index = othis.index();
        othis.addClass('layui-layer-tabnow').siblings().removeClass('layui-layer-tabnow');
        main.eq(index).show().siblings().hide();
        typeof options.change === 'function' && options.change(index);
      });
      typeof success === 'function' && success(layero);
    }
  }, options));
};

//相册层
layer.photos = function(options, loop, key){
  var dict = {};
  options = options || {};
  if(!options.photos) return;
  var type = options.photos.constructor === Object;
  var photos = type ? options.photos : {}, data = photos.data || [];
  var start = photos.start || 0;
  dict.imgIndex = (start|0) + 1;
  
  options.img = options.img || 'img';
  
  var success = options.success;
  delete options.success;

  if(!type){ //页面直接获取
    var parent = $(options.photos), pushData = function(){
      data = [];
      parent.find(options.img).each(function(index){
        var othis = $(this);
        othis.attr('layer-index', index);
        data.push({
          alt: othis.attr('alt'),
          pid: othis.attr('layer-pid'),
          src: othis.attr('layer-src') || othis.attr('src'),
          thumb: othis.attr('src')
        });
      })
    };
    
    pushData();
    
    if (data.length === 0) return;
    
    loop || parent.on('click', options.img, function(){
      var othis = $(this), index = othis.attr('layer-index'); 
      layer.photos($.extend(options, {
        photos: {
          start: index,
          data: data,
          tab: options.tab
        },
        full: options.full
      }), true);
      pushData();
    })
    
    //不直接弹出
    if(!loop) return;
    
  } else if (data.length === 0){
    return layer.msg('&#x6CA1;&#x6709;&#x56FE;&#x7247;');
  }
  
  //上一张
  dict.imgprev = function(key){
    dict.imgIndex--;
    if(dict.imgIndex < 1){
      dict.imgIndex = data.length;
    }
    dict.tabimg(key);
  };
  
  //下一张
  dict.imgnext = function(key,errorMsg){
    dict.imgIndex++;
    if(dict.imgIndex > data.length){
      dict.imgIndex = 1;
      if (errorMsg) {return};
    }
    dict.tabimg(key)
  };
  
  //方向键
  dict.keyup = function(event){
    if(!dict.end){
      var code = event.keyCode;
      event.preventDefault();
      if(code === 37){
        dict.imgprev(true);
      } else if(code === 39) {
        dict.imgnext(true);
      } else if(code === 27) {
        layer.close(dict.index);
      }
    }
  }
  
  //切换
  dict.tabimg = function(key){
    if(data.length <= 1) return;
    photos.start = dict.imgIndex - 1;
    layer.close(dict.index);
    return layer.photos(options, true, key);
    setTimeout(function(){
      layer.photos(options, true, key);
    }, 200);
  }
  
  //一些动作
  dict.event = function(){
    dict.bigimg.hover(function(){
      dict.imgsee.show();
    }, function(){
      dict.imgsee.hide();
    });
    
    dict.bigimg.find('.layui-layer-imgprev').on('click', function(event){
      event.preventDefault();
      dict.imgprev();
    });  
    
    dict.bigimg.find('.layui-layer-imgnext').on('click', function(event){     
      event.preventDefault();
      dict.imgnext();
    });
    
    $(document).on('keyup', dict.keyup);
  };
  
  //图片预加载
  function loadImage(url, callback, error) {   
    var img = new Image();
    img.src = url; 
    if(img.complete){
      return callback(img);
    }
    img.onload = function(){
      img.onload = null;
      callback(img);
    };
    img.onerror = function(e){
      img.onerror = null;
      error(e);
    };  
  };
  
  dict.loadi = layer.load(1, {
    shade: 'shade' in options ? false : 0.9,
    scrollbar: false
  });

  loadImage(data[start].src, function(img){
    layer.close(dict.loadi);
    dict.index = layer.open($.extend({
      type: 1,
      id: 'layui-layer-photos',
      area: function(){
        var imgarea = [img.width, img.height];
        var winarea = [$(window).width() - 100, $(window).height() - 100];
        
        //如果 实际图片的宽或者高比 屏幕大（那么进行缩放）
        if(!options.full && (imgarea[0]>winarea[0]||imgarea[1]>winarea[1])){
          var wh = [imgarea[0]/winarea[0],imgarea[1]/winarea[1]];//取宽度缩放比例、高度缩放比例
          if(wh[0] > wh[1]){//取缩放比例最大的进行缩放
            imgarea[0] = imgarea[0]/wh[0];
            imgarea[1] = imgarea[1]/wh[0];
          } else if(wh[0] < wh[1]){
            imgarea[0] = imgarea[0]/wh[1];
            imgarea[1] = imgarea[1]/wh[1];
          }
        }
        
        return [imgarea[0]+'px', imgarea[1]+'px']; 
      }(),
      title: false,
      shade: 0.9,
      shadeClose: true,
      closeBtn: false,
      move: '.layui-layer-phimg img',
      moveType: 1,
      scrollbar: false,
      moveOut: true,
      //anim: Math.random()*5|0,
      isOutAnim: false,
      skin: 'layui-layer-photos' + skin('photos'),
      content: '<div class="layui-layer-phimg">'
        +'<img src="'+ data[start].src +'" alt="'+ (data[start].alt||'') +'" layer-pid="'+ data[start].pid +'">'
        +'<div class="layui-layer-imgsee">'
          +(data.length > 1 ? '<span class="layui-layer-imguide"><a href="javascript:;" class="layui-layer-iconext layui-layer-imgprev"></a><a href="javascript:;" class="layui-layer-iconext layui-layer-imgnext"></a></span>' : '')
          +'<div class="layui-layer-imgbar" style="display:'+ (key ? 'block' : '') +'"><span class="layui-layer-imgtit"><a href="javascript:;">'+ (data[start].alt||'') +'</a><em>'+ dict.imgIndex +'/'+ data.length +'</em></span></div>'
        +'</div>'
      +'</div>',
      success: function(layero, index){
        dict.bigimg = layero.find('.layui-layer-phimg');
        dict.imgsee = layero.find('.layui-layer-imguide,.layui-layer-imgbar');
        dict.event(layero);
        options.tab && options.tab(data[start], layero);
        typeof success === 'function' && success(layero);
      }, end: function(){
        dict.end = true;
        $(document).off('keyup', dict.keyup);
      }
    }, options));
  }, function(){
    layer.close(dict.loadi);
    layer.msg('&#x5F53;&#x524D;&#x56FE;&#x7247;&#x5730;&#x5740;&#x5F02;&#x5E38;<br>&#x662F;&#x5426;&#x7EE7;&#x7EED;&#x67E5;&#x770B;&#x4E0B;&#x4E00;&#x5F20;&#xFF1F;', {
      time: 30000, 
      btn: ['&#x4E0B;&#x4E00;&#x5F20;', '&#x4E0D;&#x770B;&#x4E86;'], 
      yes: function(){
        data.length > 1 && dict.imgnext(true,true);
      }
    });
  });
};

//主入口
ready.run = function(_$){
  $ = _$;
  win = $(window);
  doms.html = $('html');
  layer.open = function(deliver){
    var o = new Class(deliver);
    return o.index;
  };
};

//加载方式
window.layui && layui.define ? (
  layer.ready()
  ,layui.define('jquery', function(exports){ //layui加载
    layer.path = layui.cache.dir;
    ready.run(layui.jquery);

    //暴露模块
    window.layer = layer;
    exports('layer', layer);
  })
) : (
  (typeof define === 'function' && define.amd) ? define('layer',['jquery'], function(){ //requirejs加载
    ready.run(window.jQuery);
    return layer;
  }) : function(){ //普通script标签加载
    ready.run(window.jQuery);
    layer.ready();
  }()
);

}(window);

/*
 * Toastr
 * Copyright 2012-2015
 * Authors: John Papa, Hans Fjällemark, and Tim Ferrell.
 * All Rights Reserved.
 * Use, reproduction, distribution, and modification of this code is subject to the terms and
 * conditions of the MIT license, available at http://www.opensource.org/licenses/mit-license.php
 *
 * ARIA Support: Greta Krafsig
 *
 * Project: https://github.com/CodeSeven/toastr
 */
/* global define */
(function (define) {
    define('toastr',['jquery'], function ($) {
        return (function () {
            var $container;
            var listener;
            var toastId = 0;
            var toastType = {
                error: 'error',
                info: 'info',
                success: 'success',
                warning: 'warning'
            };

            var toastr = {
                clear: clear,
                remove: remove,
                error: error,
                getContainer: getContainer,
                info: info,
                options: {},
                subscribe: subscribe,
                success: success,
                version: '2.1.3',
                warning: warning
            };

            var previousToast;

            return toastr;

            ////////////////

            function error(message, title, optionsOverride) {
                return notify({
                    type: toastType.error,
                    iconClass: getOptions().iconClasses.error,
                    message: message,
                    optionsOverride: optionsOverride,
                    title: title
                });
            }

            function getContainer(options, create) {
                if (!options) { options = getOptions(); }
                $container = $('#' + options.containerId);
                if ($container.length) {
                    return $container;
                }
                if (create) {
                    $container = createContainer(options);
                }
                return $container;
            }

            function info(message, title, optionsOverride) {
                return notify({
                    type: toastType.info,
                    iconClass: getOptions().iconClasses.info,
                    message: message,
                    optionsOverride: optionsOverride,
                    title: title
                });
            }

            function subscribe(callback) {
                listener = callback;
            }

            function success(message, title, optionsOverride) {
                return notify({
                    type: toastType.success,
                    iconClass: getOptions().iconClasses.success,
                    message: message,
                    optionsOverride: optionsOverride,
                    title: title
                });
            }

            function warning(message, title, optionsOverride) {
                return notify({
                    type: toastType.warning,
                    iconClass: getOptions().iconClasses.warning,
                    message: message,
                    optionsOverride: optionsOverride,
                    title: title
                });
            }

            function clear($toastElement, clearOptions) {
                var options = getOptions();
                if (!$container) { getContainer(options); }
                if (!clearToast($toastElement, options, clearOptions)) {
                    clearContainer(options);
                }
            }

            function remove($toastElement) {
                var options = getOptions();
                if (!$container) { getContainer(options); }
                if ($toastElement && $(':focus', $toastElement).length === 0) {
                    removeToast($toastElement);
                    return;
                }
                if ($container.children().length) {
                    $container.remove();
                }
            }

            // internal functions

            function clearContainer (options) {
                var toastsToClear = $container.children();
                for (var i = toastsToClear.length - 1; i >= 0; i--) {
                    clearToast($(toastsToClear[i]), options);
                }
            }

            function clearToast ($toastElement, options, clearOptions) {
                var force = clearOptions && clearOptions.force ? clearOptions.force : false;
                if ($toastElement && (force || $(':focus', $toastElement).length === 0)) {
                    $toastElement[options.hideMethod]({
                        duration: options.hideDuration,
                        easing: options.hideEasing,
                        complete: function () { removeToast($toastElement); }
                    });
                    return true;
                }
                return false;
            }

            function createContainer(options) {
                $container = $('<div/>')
                    .attr('id', options.containerId)
                    .addClass(options.positionClass);

                $container.appendTo($(options.target));
                return $container;
            }

            function getDefaults() {
                return {
                    tapToDismiss: true,
                    toastClass: 'toast',
                    containerId: 'toast-container',
                    debug: false,

                    showMethod: 'fadeIn', //fadeIn, slideDown, and show are built into jQuery
                    showDuration: 300,
                    showEasing: 'swing', //swing and linear are built into jQuery
                    onShown: undefined,
                    hideMethod: 'fadeOut',
                    hideDuration: 1000,
                    hideEasing: 'swing',
                    onHidden: undefined,
                    closeMethod: false,
                    closeDuration: false,
                    closeEasing: false,
                    closeOnHover: true,

                    extendedTimeOut: 1000,
                    iconClasses: {
                        error: 'toast-error',
                        info: 'toast-info',
                        success: 'toast-success',
                        warning: 'toast-warning'
                    },
                    iconClass: 'toast-info',
                    positionClass: 'toast-top-right',
                    timeOut: 5000, // Set timeOut and extendedTimeOut to 0 to make it sticky
                    titleClass: 'toast-title',
                    messageClass: 'toast-message',
                    escapeHtml: false,
                    target: 'body',
                    closeHtml: '<button type="button">&times;</button>',
                    closeClass: 'toast-close-button',
                    newestOnTop: true,
                    preventDuplicates: false,
                    progressBar: false,
                    progressClass: 'toast-progress',
                    rtl: false
                };
            }

            function publish(args) {
                if (!listener) { return; }
                listener(args);
            }

            function notify(map) {
                var options = getOptions();
                var iconClass = map.iconClass || options.iconClass;

                if (typeof (map.optionsOverride) !== 'undefined') {
                    options = $.extend(options, map.optionsOverride);
                    iconClass = map.optionsOverride.iconClass || iconClass;
                }

                if (shouldExit(options, map)) { return; }

                toastId++;

                $container = getContainer(options, true);

                var intervalId = null;
                var $toastElement = $('<div/>');
                var $titleElement = $('<div/>');
                var $messageElement = $('<div/>');
                var $progressElement = $('<div/>');
                var $closeElement = $(options.closeHtml);
                var progressBar = {
                    intervalId: null,
                    hideEta: null,
                    maxHideTime: null
                };
                var response = {
                    toastId: toastId,
                    state: 'visible',
                    startTime: new Date(),
                    options: options,
                    map: map
                };

                personalizeToast();

                displayToast();

                handleEvents();

                publish(response);

                if (options.debug && console) {
                    console.log(response);
                }

                return $toastElement;

                function escapeHtml(source) {
                    if (source == null) {
                        source = '';
                    }

                    return source
                        .replace(/&/g, '&amp;')
                        .replace(/"/g, '&quot;')
                        .replace(/'/g, '&#39;')
                        .replace(/</g, '&lt;')
                        .replace(/>/g, '&gt;');
                }

                function personalizeToast() {
                    setIcon();
                    setTitle();
                    setMessage();
                    setCloseButton();
                    setProgressBar();
                    setRTL();
                    setSequence();
                    setAria();
                }

                function setAria() {
                    var ariaValue = '';
                    switch (map.iconClass) {
                        case 'toast-success':
                        case 'toast-info':
                            ariaValue =  'polite';
                            break;
                        default:
                            ariaValue = 'assertive';
                    }
                    $toastElement.attr('aria-live', ariaValue);
                }

                function handleEvents() {
                    if (options.closeOnHover) {
                        $toastElement.hover(stickAround, delayedHideToast);
                    }

                    if (!options.onclick && options.tapToDismiss) {
                        $toastElement.click(hideToast);
                    }

                    if (options.closeButton && $closeElement) {
                        $closeElement.click(function (event) {
                            if (event.stopPropagation) {
                                event.stopPropagation();
                            } else if (event.cancelBubble !== undefined && event.cancelBubble !== true) {
                                event.cancelBubble = true;
                            }

                            if (options.onCloseClick) {
                                options.onCloseClick(event);
                            }

                            hideToast(true);
                        });
                    }

                    if (options.onclick) {
                        $toastElement.click(function (event) {
                            options.onclick(event);
                            hideToast();
                        });
                    }
                }

                function displayToast() {
                    $toastElement.hide();

                    $toastElement[options.showMethod](
                        {duration: options.showDuration, easing: options.showEasing, complete: options.onShown}
                    );

                    if (options.timeOut > 0) {
                        intervalId = setTimeout(hideToast, options.timeOut);
                        progressBar.maxHideTime = parseFloat(options.timeOut);
                        progressBar.hideEta = new Date().getTime() + progressBar.maxHideTime;
                        if (options.progressBar) {
                            progressBar.intervalId = setInterval(updateProgress, 10);
                        }
                    }
                }

                function setIcon() {
                    if (map.iconClass) {
                        $toastElement.addClass(options.toastClass).addClass(iconClass);
                    }
                }

                function setSequence() {
                    if (options.newestOnTop) {
                        $container.prepend($toastElement);
                    } else {
                        $container.append($toastElement);
                    }
                }

                function setTitle() {
                    if (map.title) {
                        var suffix = map.title;
                        if (options.escapeHtml) {
                            suffix = escapeHtml(map.title);
                        }
                        $titleElement.append(suffix).addClass(options.titleClass);
                        $toastElement.append($titleElement);
                    }
                }

                function setMessage() {
                    if (map.message) {
                        var suffix = map.message;
                        if (options.escapeHtml) {
                            suffix = escapeHtml(map.message);
                        }
                        $messageElement.append(suffix).addClass(options.messageClass);
                        $toastElement.append($messageElement);
                    }
                }

                function setCloseButton() {
                    if (options.closeButton) {
                        $closeElement.addClass(options.closeClass).attr('role', 'button');
                        $toastElement.prepend($closeElement);
                    }
                }

                function setProgressBar() {
                    if (options.progressBar) {
                        $progressElement.addClass(options.progressClass);
                        $toastElement.prepend($progressElement);
                    }
                }

                function setRTL() {
                    if (options.rtl) {
                        $toastElement.addClass('rtl');
                    }
                }

                function shouldExit(options, map) {
                    if (options.preventDuplicates) {
                        if (map.message === previousToast) {
                            return true;
                        } else {
                            previousToast = map.message;
                        }
                    }
                    return false;
                }

                function hideToast(override) {
                    var method = override && options.closeMethod !== false ? options.closeMethod : options.hideMethod;
                    var duration = override && options.closeDuration !== false ?
                        options.closeDuration : options.hideDuration;
                    var easing = override && options.closeEasing !== false ? options.closeEasing : options.hideEasing;
                    if ($(':focus', $toastElement).length && !override) {
                        return;
                    }
                    clearTimeout(progressBar.intervalId);
                    return $toastElement[method]({
                        duration: duration,
                        easing: easing,
                        complete: function () {
                            removeToast($toastElement);
                            clearTimeout(intervalId);
                            if (options.onHidden && response.state !== 'hidden') {
                                options.onHidden();
                            }
                            response.state = 'hidden';
                            response.endTime = new Date();
                            publish(response);
                        }
                    });
                }

                function delayedHideToast() {
                    if (options.timeOut > 0 || options.extendedTimeOut > 0) {
                        intervalId = setTimeout(hideToast, options.extendedTimeOut);
                        progressBar.maxHideTime = parseFloat(options.extendedTimeOut);
                        progressBar.hideEta = new Date().getTime() + progressBar.maxHideTime;
                    }
                }

                function stickAround() {
                    clearTimeout(intervalId);
                    progressBar.hideEta = 0;
                    $toastElement.stop(true, true)[options.showMethod](
                        {duration: options.showDuration, easing: options.showEasing}
                    );
                }

                function updateProgress() {
                    var percentage = ((progressBar.hideEta - (new Date().getTime())) / progressBar.maxHideTime) * 100;
                    $progressElement.width(percentage + '%');
                }
            }

            function getOptions() {
                return $.extend({}, getDefaults(), toastr.options);
            }

            function removeToast($toastElement) {
                if (!$container) { $container = getContainer(); }
                if ($toastElement.is(':visible')) {
                    return;
                }
                $toastElement.remove();
                $toastElement = null;
                if ($container.children().length === 0) {
                    $container.remove();
                    previousToast = undefined;
                }
            }

        })();
    });
}(typeof define === 'function' && define.amd ? define : function (deps, factory) {
    if (typeof module !== 'undefined' && module.exports) { //Node
        module.exports = factory(require('jquery'));
    } else {
        window.toastr = factory(window.jQuery);
    }
}));

define('fast',['jquery', 'bootstrap', 'toastr', 'layer', 'lang'], function ($, undefined, Toastr, Layer, Lang) {
    var Fast = {
        config: {
            //toastr默认配置
            toastr: {
                "closeButton": true,
                "debug": false,
                "newestOnTop": false,
                "progressBar": false,
                "positionClass": "toast-top-center",
                "preventDuplicates": false,
                "onclick": null,
                "showDuration": "300",
                "hideDuration": "1000",
                "timeOut": "5000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            }
        },
        events: {
            //请求成功的回调
            onAjaxSuccess: function (ret, onAjaxSuccess) {
                var data = typeof ret.data !== 'undefined' ? ret.data : null;
                var msg = typeof ret.msg !== 'undefined' && ret.msg ? ret.msg : __('Operation completed');

                if (typeof onAjaxSuccess === 'function') {
                    var result = onAjaxSuccess.call(this, data, ret);
                    if (result === false)
                        return;
                }
                Toastr.success(msg);
            },
            //请求错误的回调
            onAjaxError: function (ret, onAjaxError) {
                var data = typeof ret.data !== 'undefined' ? ret.data : null;
                if (typeof onAjaxError === 'function') {
                    var result = onAjaxError.call(this, data, ret);
                    if (result === false) {
                        return;
                    }
                }
                Toastr.error(ret.msg + "(code:" + ret.code + ")");
            },
            //服务器响应数据后
            onAjaxResponse: function (response) {
                try {
                    var ret = typeof response === 'object' ? response : JSON.parse(response);
                    if (!ret.hasOwnProperty('code')) {
                        $.extend(ret, {code: -2, msg: response, data: null});
                    }
                } catch (e) {
                    var ret = {code: -1, msg: e.message, data: null};
                }
                return ret;
            }
        },
        api: {
            //发送Ajax请求
            ajax: function (options, success, error) {
                options = typeof options === 'string' ? {url: options} : options;
                var index = Layer.load();
                options = $.extend({
                    type: "POST",
                    dataType: "json",
                    success: function (ret) {
                        Layer.close(index);
                        ret = Fast.events.onAjaxResponse(ret);
                        if (ret.code === 1) {
                            Fast.events.onAjaxSuccess(ret, success);
                        } else {
                            Fast.events.onAjaxError(ret, error);
                        }
                    },
                    error: function (xhr) {
                        Layer.close(index);
                        var ret = {code: xhr.status, msg: xhr.statusText, data: null};
                        Fast.events.onAjaxError(ret, error);
                    }
                }, options);
                $.ajax(options);
            },
            //修复URL
            fixurl: function (url) {
                if (url.substr(0, 1) !== "/") {
                    var r = new RegExp('^(?:[a-z]+:)?//', 'i');
                    if (!r.test(url)) {
                        url = Config.moduleurl + "/" + url;
                    }
                }
                return url;
            },
            //获取修复后可访问的cdn链接
            cdnurl: function (url) {
                return /^(?:[a-z]+:)?\/\//i.test(url) ? url : Config.upload.cdnurl + url;
            },
            //查询Url参数
            query: function (name, url) {
                if (!url) {
                    url = window.location.href;
                }
                name = name.replace(/[\[\]]/g, "\\$&");
                var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
                        results = regex.exec(url);
                if (!results)
                    return null;
                if (!results[2])
                    return '';
                return decodeURIComponent(results[2].replace(/\+/g, " "));
            },
            //打开一个弹出窗口
            open: function (url, title, options) {
                title = title ? title : "";
                url = Fast.api.fixurl(url);
                url = url + (url.indexOf("?") > -1 ? "&" : "?") + "dialog=1";
                var area = [$(window).width() > 800 ? '800px' : '95%', $(window).height() > 600 ? '600px' : '95%'];
                options = $.extend({
                    type: 2,
                    title: title,
                    shadeClose: true,
                    shade: false,
                    maxmin: true,
                    moveOut: true,
                    area: area,
                    content: url,
                    zIndex: Layer.zIndex,
                    success: function (layero, index) {
                        var that = this;
                        //存储callback事件
                        $(layero).data("callback", that.callback);
                        //$(layero).removeClass("layui-layer-border");
                        Layer.setTop(layero);
                        var frame = Layer.getChildFrame('html', index);
                        var layerfooter = frame.find(".layer-footer");
                        Fast.api.layerfooter(layero, index, that);

                        //绑定事件
                        if (layerfooter.size() > 0) {
                            // 监听窗口内的元素及属性变化
                            // Firefox和Chrome早期版本中带有前缀
                            var MutationObserver = window.MutationObserver || window.WebKitMutationObserver || window.MozMutationObserver
                            // 选择目标节点
                            var target = layerfooter[0];
                            // 创建观察者对象
                            var observer = new MutationObserver(function (mutations) {
                                Fast.api.layerfooter(layero, index, that);
                                mutations.forEach(function (mutation) {
                                });
                            });
                            // 配置观察选项:
                            var config = {attributes: true, childList: true, characterData: true, subtree: true}
                            // 传入目标节点和观察选项
                            observer.observe(target, config);
                            // 随后,你还可以停止观察
                            // observer.disconnect();
                        }
                    }
                }, options ? options : {});
                if ($(window).width() < 480 || (/iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream && top.$(".tab-pane.active").size() > 0)) {
                    options.area = [top.$(".tab-pane.active").width() + "px", top.$(".tab-pane.active").height() + "px"];
                    options.offset = [top.$(".tab-pane.active").scrollTop() + "px", "0px"];
                }
                Layer.open(options);
                return false;
            },
            //关闭窗口并回传数据
            close: function (data) {
                var index = parent.Layer.getFrameIndex(window.name);
                var callback = parent.$("#layui-layer" + index).data("callback");
                //再执行关闭
                parent.Layer.close(index);
                //再调用回传函数
                if (typeof callback === 'function') {
                    callback.call(undefined, data);
                }
            },
            layerfooter: function (layero, index, that) {
                var frame = Layer.getChildFrame('html', index);
                var layerfooter = frame.find(".layer-footer");
                if (layerfooter.size() > 0) {
                    $(".layui-layer-footer", layero).remove();
                    var footer = $("<div />").addClass('layui-layer-btn layui-layer-footer');
                    footer.html(layerfooter.html());
                    if ($(".row", footer).size() === 0) {
                        $(">", footer).wrapAll("<div class='row'></div>");
                    }
                    footer.insertAfter(layero.find('.layui-layer-content'));
                    //绑定事件
                    footer.on("click", ".btn", function () {
                        if ($(this).hasClass("disabled") || $(this).parent().hasClass("disabled")) {
                            return;
                        }
                        $(".btn:eq(" + $(this).index() + ")", layerfooter).trigger("click");
                    });

                    var titHeight = layero.find('.layui-layer-title').outerHeight() || 0;
                    var btnHeight = layero.find('.layui-layer-btn').outerHeight() || 0;
                    //重设iframe高度
                    $("iframe", layero).height(layero.height() - titHeight - btnHeight);
                }
                //修复iOS下弹出窗口的高度和iOS下iframe无法滚动的BUG
                if (/iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream) {
                    var titHeight = layero.find('.layui-layer-title').outerHeight() || 0;
                    var btnHeight = layero.find('.layui-layer-btn').outerHeight() || 0;
                    $("iframe", layero).parent().css("height", layero.height() - titHeight - btnHeight);
                    $("iframe", layero).css("height", "100%");
                }
            },
            success: function (options, callback) {
                var type = typeof options === 'function';
                if (type) {
                    callback = options;
                }
                return Layer.msg(__('Operation completed'), $.extend({
                    offset: 0, icon: 1
                }, type ? {} : options), callback);
            },
            error: function (options, callback) {
                var type = typeof options === 'function';
                if (type) {
                    callback = options;
                }
                return Layer.msg(__('Operation failed'), $.extend({
                    offset: 0, icon: 2
                }, type ? {} : options), callback);
            },
            toastr: Toastr,
            layer: Layer
        },
        lang: function () {
            var args = arguments,
                    string = args[0],
                    i = 1;
            string = string.toLowerCase();
            //string = typeof Lang[string] != 'undefined' ? Lang[string] : string;
            if (typeof Lang[string] != 'undefined') {
                if (typeof Lang[string] == 'object')
                    return Lang[string];
                string = Lang[string];
            } else if (string.indexOf('.') !== -1 && false) {
                var arr = string.split('.');
                var current = Lang[arr[0]];
                for (var i = 1; i < arr.length; i++) {
                    current = typeof current[arr[i]] != 'undefined' ? current[arr[i]] : '';
                    if (typeof current != 'object')
                        break;
                }
                if (typeof current == 'object')
                    return current;
                string = current;
            } else {
                string = args[0];
            }
            return string.replace(/%((%)|s|d)/g, function (m) {
                // m is the matched format, e.g. %s, %d
                var val = null;
                if (m[2]) {
                    val = m[2];
                } else {
                    val = args[i];
                    // A switch statement so that the formatter can be extended. Default is %s
                    switch (m) {
                        case '%d':
                            val = parseFloat(val);
                            if (isNaN(val)) {
                                val = 0;
                            }
                            break;
                    }
                    i++;
                }
                return val;
            });
        },
        init: function () {
            // 对相对地址进行处理
            $.ajaxSetup({
                beforeSend: function (xhr, setting) {
                    setting.url = Fast.api.fixurl(setting.url);
                }
            });
            Layer.config({
                skin: 'layui-layer-fast'
            });
            // 绑定ESC关闭窗口事件
            $(window).keyup(function (e) {
                if (e.keyCode == 27) {
                    if ($(".layui-layer").size() > 0) {
                        var index = 0;
                        $(".layui-layer").each(function () {
                            index = Math.max(index, parseInt($(this).attr("times")));
                        });
                        if (index) {
                            Layer.close(index);
                        }
                    }
                }
            });

            //公共代码
            //配置Toastr的参数
            Toastr.options = Fast.config.toastr;
        }
    };
    //将Layer暴露到全局中去
    window.Layer = Layer;
    //将Toastr暴露到全局中去
    window.Toastr = Toastr;
    //将语言方法暴露到全局中去
    window.__ = Fast.lang;
    //将Fast渲染至全局
    window.Fast = Fast;

    //默认初始化执行的代码
    Fast.init();
    return Fast;
});

//! moment.js
//! version : 2.18.1
//! authors : Tim Wood, Iskren Chernev, Moment.js contributors
//! license : MIT
//! momentjs.com

;(function (global, factory) {
    typeof exports === 'object' && typeof module !== 'undefined' ? module.exports = factory() :
    typeof define === 'function' && define.amd ? define('moment/moment',factory) :
    global.moment = factory()
}(this, (function () { 'use strict';

var hookCallback;

function hooks () {
    return hookCallback.apply(null, arguments);
}

// This is done to register the method called with moment()
// without creating circular dependencies.
function setHookCallback (callback) {
    hookCallback = callback;
}

function isArray(input) {
    return input instanceof Array || Object.prototype.toString.call(input) === '[object Array]';
}

function isObject(input) {
    // IE8 will treat undefined and null as object if it wasn't for
    // input != null
    return input != null && Object.prototype.toString.call(input) === '[object Object]';
}

function isObjectEmpty(obj) {
    var k;
    for (k in obj) {
        // even if its not own property I'd still call it non-empty
        return false;
    }
    return true;
}

function isUndefined(input) {
    return input === void 0;
}

function isNumber(input) {
    return typeof input === 'number' || Object.prototype.toString.call(input) === '[object Number]';
}

function isDate(input) {
    return input instanceof Date || Object.prototype.toString.call(input) === '[object Date]';
}

function map(arr, fn) {
    var res = [], i;
    for (i = 0; i < arr.length; ++i) {
        res.push(fn(arr[i], i));
    }
    return res;
}

function hasOwnProp(a, b) {
    return Object.prototype.hasOwnProperty.call(a, b);
}

function extend(a, b) {
    for (var i in b) {
        if (hasOwnProp(b, i)) {
            a[i] = b[i];
        }
    }

    if (hasOwnProp(b, 'toString')) {
        a.toString = b.toString;
    }

    if (hasOwnProp(b, 'valueOf')) {
        a.valueOf = b.valueOf;
    }

    return a;
}

function createUTC (input, format, locale, strict) {
    return createLocalOrUTC(input, format, locale, strict, true).utc();
}

function defaultParsingFlags() {
    // We need to deep clone this object.
    return {
        empty           : false,
        unusedTokens    : [],
        unusedInput     : [],
        overflow        : -2,
        charsLeftOver   : 0,
        nullInput       : false,
        invalidMonth    : null,
        invalidFormat   : false,
        userInvalidated : false,
        iso             : false,
        parsedDateParts : [],
        meridiem        : null,
        rfc2822         : false,
        weekdayMismatch : false
    };
}

function getParsingFlags(m) {
    if (m._pf == null) {
        m._pf = defaultParsingFlags();
    }
    return m._pf;
}

var some;
if (Array.prototype.some) {
    some = Array.prototype.some;
} else {
    some = function (fun) {
        var t = Object(this);
        var len = t.length >>> 0;

        for (var i = 0; i < len; i++) {
            if (i in t && fun.call(this, t[i], i, t)) {
                return true;
            }
        }

        return false;
    };
}

var some$1 = some;

function isValid(m) {
    if (m._isValid == null) {
        var flags = getParsingFlags(m);
        var parsedParts = some$1.call(flags.parsedDateParts, function (i) {
            return i != null;
        });
        var isNowValid = !isNaN(m._d.getTime()) &&
            flags.overflow < 0 &&
            !flags.empty &&
            !flags.invalidMonth &&
            !flags.invalidWeekday &&
            !flags.nullInput &&
            !flags.invalidFormat &&
            !flags.userInvalidated &&
            (!flags.meridiem || (flags.meridiem && parsedParts));

        if (m._strict) {
            isNowValid = isNowValid &&
                flags.charsLeftOver === 0 &&
                flags.unusedTokens.length === 0 &&
                flags.bigHour === undefined;
        }

        if (Object.isFrozen == null || !Object.isFrozen(m)) {
            m._isValid = isNowValid;
        }
        else {
            return isNowValid;
        }
    }
    return m._isValid;
}

function createInvalid (flags) {
    var m = createUTC(NaN);
    if (flags != null) {
        extend(getParsingFlags(m), flags);
    }
    else {
        getParsingFlags(m).userInvalidated = true;
    }

    return m;
}

// Plugins that add properties should also add the key here (null value),
// so we can properly clone ourselves.
var momentProperties = hooks.momentProperties = [];

function copyConfig(to, from) {
    var i, prop, val;

    if (!isUndefined(from._isAMomentObject)) {
        to._isAMomentObject = from._isAMomentObject;
    }
    if (!isUndefined(from._i)) {
        to._i = from._i;
    }
    if (!isUndefined(from._f)) {
        to._f = from._f;
    }
    if (!isUndefined(from._l)) {
        to._l = from._l;
    }
    if (!isUndefined(from._strict)) {
        to._strict = from._strict;
    }
    if (!isUndefined(from._tzm)) {
        to._tzm = from._tzm;
    }
    if (!isUndefined(from._isUTC)) {
        to._isUTC = from._isUTC;
    }
    if (!isUndefined(from._offset)) {
        to._offset = from._offset;
    }
    if (!isUndefined(from._pf)) {
        to._pf = getParsingFlags(from);
    }
    if (!isUndefined(from._locale)) {
        to._locale = from._locale;
    }

    if (momentProperties.length > 0) {
        for (i = 0; i < momentProperties.length; i++) {
            prop = momentProperties[i];
            val = from[prop];
            if (!isUndefined(val)) {
                to[prop] = val;
            }
        }
    }

    return to;
}

var updateInProgress = false;

// Moment prototype object
function Moment(config) {
    copyConfig(this, config);
    this._d = new Date(config._d != null ? config._d.getTime() : NaN);
    if (!this.isValid()) {
        this._d = new Date(NaN);
    }
    // Prevent infinite loop in case updateOffset creates new moment
    // objects.
    if (updateInProgress === false) {
        updateInProgress = true;
        hooks.updateOffset(this);
        updateInProgress = false;
    }
}

function isMoment (obj) {
    return obj instanceof Moment || (obj != null && obj._isAMomentObject != null);
}

function absFloor (number) {
    if (number < 0) {
        // -0 -> 0
        return Math.ceil(number) || 0;
    } else {
        return Math.floor(number);
    }
}

function toInt(argumentForCoercion) {
    var coercedNumber = +argumentForCoercion,
        value = 0;

    if (coercedNumber !== 0 && isFinite(coercedNumber)) {
        value = absFloor(coercedNumber);
    }

    return value;
}

// compare two arrays, return the number of differences
function compareArrays(array1, array2, dontConvert) {
    var len = Math.min(array1.length, array2.length),
        lengthDiff = Math.abs(array1.length - array2.length),
        diffs = 0,
        i;
    for (i = 0; i < len; i++) {
        if ((dontConvert && array1[i] !== array2[i]) ||
            (!dontConvert && toInt(array1[i]) !== toInt(array2[i]))) {
            diffs++;
        }
    }
    return diffs + lengthDiff;
}

function warn(msg) {
    if (hooks.suppressDeprecationWarnings === false &&
            (typeof console !==  'undefined') && console.warn) {
        console.warn('Deprecation warning: ' + msg);
    }
}

function deprecate(msg, fn) {
    var firstTime = true;

    return extend(function () {
        if (hooks.deprecationHandler != null) {
            hooks.deprecationHandler(null, msg);
        }
        if (firstTime) {
            var args = [];
            var arg;
            for (var i = 0; i < arguments.length; i++) {
                arg = '';
                if (typeof arguments[i] === 'object') {
                    arg += '\n[' + i + '] ';
                    for (var key in arguments[0]) {
                        arg += key + ': ' + arguments[0][key] + ', ';
                    }
                    arg = arg.slice(0, -2); // Remove trailing comma and space
                } else {
                    arg = arguments[i];
                }
                args.push(arg);
            }
            warn(msg + '\nArguments: ' + Array.prototype.slice.call(args).join('') + '\n' + (new Error()).stack);
            firstTime = false;
        }
        return fn.apply(this, arguments);
    }, fn);
}

var deprecations = {};

function deprecateSimple(name, msg) {
    if (hooks.deprecationHandler != null) {
        hooks.deprecationHandler(name, msg);
    }
    if (!deprecations[name]) {
        warn(msg);
        deprecations[name] = true;
    }
}

hooks.suppressDeprecationWarnings = false;
hooks.deprecationHandler = null;

function isFunction(input) {
    return input instanceof Function || Object.prototype.toString.call(input) === '[object Function]';
}

function set (config) {
    var prop, i;
    for (i in config) {
        prop = config[i];
        if (isFunction(prop)) {
            this[i] = prop;
        } else {
            this['_' + i] = prop;
        }
    }
    this._config = config;
    // Lenient ordinal parsing accepts just a number in addition to
    // number + (possibly) stuff coming from _dayOfMonthOrdinalParse.
    // TODO: Remove "ordinalParse" fallback in next major release.
    this._dayOfMonthOrdinalParseLenient = new RegExp(
        (this._dayOfMonthOrdinalParse.source || this._ordinalParse.source) +
            '|' + (/\d{1,2}/).source);
}

function mergeConfigs(parentConfig, childConfig) {
    var res = extend({}, parentConfig), prop;
    for (prop in childConfig) {
        if (hasOwnProp(childConfig, prop)) {
            if (isObject(parentConfig[prop]) && isObject(childConfig[prop])) {
                res[prop] = {};
                extend(res[prop], parentConfig[prop]);
                extend(res[prop], childConfig[prop]);
            } else if (childConfig[prop] != null) {
                res[prop] = childConfig[prop];
            } else {
                delete res[prop];
            }
        }
    }
    for (prop in parentConfig) {
        if (hasOwnProp(parentConfig, prop) &&
                !hasOwnProp(childConfig, prop) &&
                isObject(parentConfig[prop])) {
            // make sure changes to properties don't modify parent config
            res[prop] = extend({}, res[prop]);
        }
    }
    return res;
}

function Locale(config) {
    if (config != null) {
        this.set(config);
    }
}

var keys;

if (Object.keys) {
    keys = Object.keys;
} else {
    keys = function (obj) {
        var i, res = [];
        for (i in obj) {
            if (hasOwnProp(obj, i)) {
                res.push(i);
            }
        }
        return res;
    };
}

var keys$1 = keys;

var defaultCalendar = {
    sameDay : '[Today at] LT',
    nextDay : '[Tomorrow at] LT',
    nextWeek : 'dddd [at] LT',
    lastDay : '[Yesterday at] LT',
    lastWeek : '[Last] dddd [at] LT',
    sameElse : 'L'
};

function calendar (key, mom, now) {
    var output = this._calendar[key] || this._calendar['sameElse'];
    return isFunction(output) ? output.call(mom, now) : output;
}

var defaultLongDateFormat = {
    LTS  : 'h:mm:ss A',
    LT   : 'h:mm A',
    L    : 'MM/DD/YYYY',
    LL   : 'MMMM D, YYYY',
    LLL  : 'MMMM D, YYYY h:mm A',
    LLLL : 'dddd, MMMM D, YYYY h:mm A'
};

function longDateFormat (key) {
    var format = this._longDateFormat[key],
        formatUpper = this._longDateFormat[key.toUpperCase()];

    if (format || !formatUpper) {
        return format;
    }

    this._longDateFormat[key] = formatUpper.replace(/MMMM|MM|DD|dddd/g, function (val) {
        return val.slice(1);
    });

    return this._longDateFormat[key];
}

var defaultInvalidDate = 'Invalid date';

function invalidDate () {
    return this._invalidDate;
}

var defaultOrdinal = '%d';
var defaultDayOfMonthOrdinalParse = /\d{1,2}/;

function ordinal (number) {
    return this._ordinal.replace('%d', number);
}

var defaultRelativeTime = {
    future : 'in %s',
    past   : '%s ago',
    s  : 'a few seconds',
    ss : '%d seconds',
    m  : 'a minute',
    mm : '%d minutes',
    h  : 'an hour',
    hh : '%d hours',
    d  : 'a day',
    dd : '%d days',
    M  : 'a month',
    MM : '%d months',
    y  : 'a year',
    yy : '%d years'
};

function relativeTime (number, withoutSuffix, string, isFuture) {
    var output = this._relativeTime[string];
    return (isFunction(output)) ?
        output(number, withoutSuffix, string, isFuture) :
        output.replace(/%d/i, number);
}

function pastFuture (diff, output) {
    var format = this._relativeTime[diff > 0 ? 'future' : 'past'];
    return isFunction(format) ? format(output) : format.replace(/%s/i, output);
}

var aliases = {};

function addUnitAlias (unit, shorthand) {
    var lowerCase = unit.toLowerCase();
    aliases[lowerCase] = aliases[lowerCase + 's'] = aliases[shorthand] = unit;
}

function normalizeUnits(units) {
    return typeof units === 'string' ? aliases[units] || aliases[units.toLowerCase()] : undefined;
}

function normalizeObjectUnits(inputObject) {
    var normalizedInput = {},
        normalizedProp,
        prop;

    for (prop in inputObject) {
        if (hasOwnProp(inputObject, prop)) {
            normalizedProp = normalizeUnits(prop);
            if (normalizedProp) {
                normalizedInput[normalizedProp] = inputObject[prop];
            }
        }
    }

    return normalizedInput;
}

var priorities = {};

function addUnitPriority(unit, priority) {
    priorities[unit] = priority;
}

function getPrioritizedUnits(unitsObj) {
    var units = [];
    for (var u in unitsObj) {
        units.push({unit: u, priority: priorities[u]});
    }
    units.sort(function (a, b) {
        return a.priority - b.priority;
    });
    return units;
}

function makeGetSet (unit, keepTime) {
    return function (value) {
        if (value != null) {
            set$1(this, unit, value);
            hooks.updateOffset(this, keepTime);
            return this;
        } else {
            return get(this, unit);
        }
    };
}

function get (mom, unit) {
    return mom.isValid() ?
        mom._d['get' + (mom._isUTC ? 'UTC' : '') + unit]() : NaN;
}

function set$1 (mom, unit, value) {
    if (mom.isValid()) {
        mom._d['set' + (mom._isUTC ? 'UTC' : '') + unit](value);
    }
}

// MOMENTS

function stringGet (units) {
    units = normalizeUnits(units);
    if (isFunction(this[units])) {
        return this[units]();
    }
    return this;
}


function stringSet (units, value) {
    if (typeof units === 'object') {
        units = normalizeObjectUnits(units);
        var prioritized = getPrioritizedUnits(units);
        for (var i = 0; i < prioritized.length; i++) {
            this[prioritized[i].unit](units[prioritized[i].unit]);
        }
    } else {
        units = normalizeUnits(units);
        if (isFunction(this[units])) {
            return this[units](value);
        }
    }
    return this;
}

function zeroFill(number, targetLength, forceSign) {
    var absNumber = '' + Math.abs(number),
        zerosToFill = targetLength - absNumber.length,
        sign = number >= 0;
    return (sign ? (forceSign ? '+' : '') : '-') +
        Math.pow(10, Math.max(0, zerosToFill)).toString().substr(1) + absNumber;
}

var formattingTokens = /(\[[^\[]*\])|(\\)?([Hh]mm(ss)?|Mo|MM?M?M?|Do|DDDo|DD?D?D?|ddd?d?|do?|w[o|w]?|W[o|W]?|Qo?|YYYYYY|YYYYY|YYYY|YY|gg(ggg?)?|GG(GGG?)?|e|E|a|A|hh?|HH?|kk?|mm?|ss?|S{1,9}|x|X|zz?|ZZ?|.)/g;

var localFormattingTokens = /(\[[^\[]*\])|(\\)?(LTS|LT|LL?L?L?|l{1,4})/g;

var formatFunctions = {};

var formatTokenFunctions = {};

// token:    'M'
// padded:   ['MM', 2]
// ordinal:  'Mo'
// callback: function () { this.month() + 1 }
function addFormatToken (token, padded, ordinal, callback) {
    var func = callback;
    if (typeof callback === 'string') {
        func = function () {
            return this[callback]();
        };
    }
    if (token) {
        formatTokenFunctions[token] = func;
    }
    if (padded) {
        formatTokenFunctions[padded[0]] = function () {
            return zeroFill(func.apply(this, arguments), padded[1], padded[2]);
        };
    }
    if (ordinal) {
        formatTokenFunctions[ordinal] = function () {
            return this.localeData().ordinal(func.apply(this, arguments), token);
        };
    }
}

function removeFormattingTokens(input) {
    if (input.match(/\[[\s\S]/)) {
        return input.replace(/^\[|\]$/g, '');
    }
    return input.replace(/\\/g, '');
}

function makeFormatFunction(format) {
    var array = format.match(formattingTokens), i, length;

    for (i = 0, length = array.length; i < length; i++) {
        if (formatTokenFunctions[array[i]]) {
            array[i] = formatTokenFunctions[array[i]];
        } else {
            array[i] = removeFormattingTokens(array[i]);
        }
    }

    return function (mom) {
        var output = '', i;
        for (i = 0; i < length; i++) {
            output += isFunction(array[i]) ? array[i].call(mom, format) : array[i];
        }
        return output;
    };
}

// format date using native date object
function formatMoment(m, format) {
    if (!m.isValid()) {
        return m.localeData().invalidDate();
    }

    format = expandFormat(format, m.localeData());
    formatFunctions[format] = formatFunctions[format] || makeFormatFunction(format);

    return formatFunctions[format](m);
}

function expandFormat(format, locale) {
    var i = 5;

    function replaceLongDateFormatTokens(input) {
        return locale.longDateFormat(input) || input;
    }

    localFormattingTokens.lastIndex = 0;
    while (i >= 0 && localFormattingTokens.test(format)) {
        format = format.replace(localFormattingTokens, replaceLongDateFormatTokens);
        localFormattingTokens.lastIndex = 0;
        i -= 1;
    }

    return format;
}

var match1         = /\d/;            //       0 - 9
var match2         = /\d\d/;          //      00 - 99
var match3         = /\d{3}/;         //     000 - 999
var match4         = /\d{4}/;         //    0000 - 9999
var match6         = /[+-]?\d{6}/;    // -999999 - 999999
var match1to2      = /\d\d?/;         //       0 - 99
var match3to4      = /\d\d\d\d?/;     //     999 - 9999
var match5to6      = /\d\d\d\d\d\d?/; //   99999 - 999999
var match1to3      = /\d{1,3}/;       //       0 - 999
var match1to4      = /\d{1,4}/;       //       0 - 9999
var match1to6      = /[+-]?\d{1,6}/;  // -999999 - 999999

var matchUnsigned  = /\d+/;           //       0 - inf
var matchSigned    = /[+-]?\d+/;      //    -inf - inf

var matchOffset    = /Z|[+-]\d\d:?\d\d/gi; // +00:00 -00:00 +0000 -0000 or Z
var matchShortOffset = /Z|[+-]\d\d(?::?\d\d)?/gi; // +00 -00 +00:00 -00:00 +0000 -0000 or Z

var matchTimestamp = /[+-]?\d+(\.\d{1,3})?/; // 123456789 123456789.123

// any word (or two) characters or numbers including two/three word month in arabic.
// includes scottish gaelic two word and hyphenated months
var matchWord = /[0-9]*['a-z\u00A0-\u05FF\u0700-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+|[\u0600-\u06FF\/]+(\s*?[\u0600-\u06FF]+){1,2}/i;


var regexes = {};

function addRegexToken (token, regex, strictRegex) {
    regexes[token] = isFunction(regex) ? regex : function (isStrict, localeData) {
        return (isStrict && strictRegex) ? strictRegex : regex;
    };
}

function getParseRegexForToken (token, config) {
    if (!hasOwnProp(regexes, token)) {
        return new RegExp(unescapeFormat(token));
    }

    return regexes[token](config._strict, config._locale);
}

// Code from http://stackoverflow.com/questions/3561493/is-there-a-regexp-escape-function-in-javascript
function unescapeFormat(s) {
    return regexEscape(s.replace('\\', '').replace(/\\(\[)|\\(\])|\[([^\]\[]*)\]|\\(.)/g, function (matched, p1, p2, p3, p4) {
        return p1 || p2 || p3 || p4;
    }));
}

function regexEscape(s) {
    return s.replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&');
}

var tokens = {};

function addParseToken (token, callback) {
    var i, func = callback;
    if (typeof token === 'string') {
        token = [token];
    }
    if (isNumber(callback)) {
        func = function (input, array) {
            array[callback] = toInt(input);
        };
    }
    for (i = 0; i < token.length; i++) {
        tokens[token[i]] = func;
    }
}

function addWeekParseToken (token, callback) {
    addParseToken(token, function (input, array, config, token) {
        config._w = config._w || {};
        callback(input, config._w, config, token);
    });
}

function addTimeToArrayFromToken(token, input, config) {
    if (input != null && hasOwnProp(tokens, token)) {
        tokens[token](input, config._a, config, token);
    }
}

var YEAR = 0;
var MONTH = 1;
var DATE = 2;
var HOUR = 3;
var MINUTE = 4;
var SECOND = 5;
var MILLISECOND = 6;
var WEEK = 7;
var WEEKDAY = 8;

var indexOf;

if (Array.prototype.indexOf) {
    indexOf = Array.prototype.indexOf;
} else {
    indexOf = function (o) {
        // I know
        var i;
        for (i = 0; i < this.length; ++i) {
            if (this[i] === o) {
                return i;
            }
        }
        return -1;
    };
}

var indexOf$1 = indexOf;

function daysInMonth(year, month) {
    return new Date(Date.UTC(year, month + 1, 0)).getUTCDate();
}

// FORMATTING

addFormatToken('M', ['MM', 2], 'Mo', function () {
    return this.month() + 1;
});

addFormatToken('MMM', 0, 0, function (format) {
    return this.localeData().monthsShort(this, format);
});

addFormatToken('MMMM', 0, 0, function (format) {
    return this.localeData().months(this, format);
});

// ALIASES

addUnitAlias('month', 'M');

// PRIORITY

addUnitPriority('month', 8);

// PARSING

addRegexToken('M',    match1to2);
addRegexToken('MM',   match1to2, match2);
addRegexToken('MMM',  function (isStrict, locale) {
    return locale.monthsShortRegex(isStrict);
});
addRegexToken('MMMM', function (isStrict, locale) {
    return locale.monthsRegex(isStrict);
});

addParseToken(['M', 'MM'], function (input, array) {
    array[MONTH] = toInt(input) - 1;
});

addParseToken(['MMM', 'MMMM'], function (input, array, config, token) {
    var month = config._locale.monthsParse(input, token, config._strict);
    // if we didn't find a month name, mark the date as invalid.
    if (month != null) {
        array[MONTH] = month;
    } else {
        getParsingFlags(config).invalidMonth = input;
    }
});

// LOCALES

var MONTHS_IN_FORMAT = /D[oD]?(\[[^\[\]]*\]|\s)+MMMM?/;
var defaultLocaleMonths = 'January_February_March_April_May_June_July_August_September_October_November_December'.split('_');
function localeMonths (m, format) {
    if (!m) {
        return isArray(this._months) ? this._months :
            this._months['standalone'];
    }
    return isArray(this._months) ? this._months[m.month()] :
        this._months[(this._months.isFormat || MONTHS_IN_FORMAT).test(format) ? 'format' : 'standalone'][m.month()];
}

var defaultLocaleMonthsShort = 'Jan_Feb_Mar_Apr_May_Jun_Jul_Aug_Sep_Oct_Nov_Dec'.split('_');
function localeMonthsShort (m, format) {
    if (!m) {
        return isArray(this._monthsShort) ? this._monthsShort :
            this._monthsShort['standalone'];
    }
    return isArray(this._monthsShort) ? this._monthsShort[m.month()] :
        this._monthsShort[MONTHS_IN_FORMAT.test(format) ? 'format' : 'standalone'][m.month()];
}

function handleStrictParse(monthName, format, strict) {
    var i, ii, mom, llc = monthName.toLocaleLowerCase();
    if (!this._monthsParse) {
        // this is not used
        this._monthsParse = [];
        this._longMonthsParse = [];
        this._shortMonthsParse = [];
        for (i = 0; i < 12; ++i) {
            mom = createUTC([2000, i]);
            this._shortMonthsParse[i] = this.monthsShort(mom, '').toLocaleLowerCase();
            this._longMonthsParse[i] = this.months(mom, '').toLocaleLowerCase();
        }
    }

    if (strict) {
        if (format === 'MMM') {
            ii = indexOf$1.call(this._shortMonthsParse, llc);
            return ii !== -1 ? ii : null;
        } else {
            ii = indexOf$1.call(this._longMonthsParse, llc);
            return ii !== -1 ? ii : null;
        }
    } else {
        if (format === 'MMM') {
            ii = indexOf$1.call(this._shortMonthsParse, llc);
            if (ii !== -1) {
                return ii;
            }
            ii = indexOf$1.call(this._longMonthsParse, llc);
            return ii !== -1 ? ii : null;
        } else {
            ii = indexOf$1.call(this._longMonthsParse, llc);
            if (ii !== -1) {
                return ii;
            }
            ii = indexOf$1.call(this._shortMonthsParse, llc);
            return ii !== -1 ? ii : null;
        }
    }
}

function localeMonthsParse (monthName, format, strict) {
    var i, mom, regex;

    if (this._monthsParseExact) {
        return handleStrictParse.call(this, monthName, format, strict);
    }

    if (!this._monthsParse) {
        this._monthsParse = [];
        this._longMonthsParse = [];
        this._shortMonthsParse = [];
    }

    // TODO: add sorting
    // Sorting makes sure if one month (or abbr) is a prefix of another
    // see sorting in computeMonthsParse
    for (i = 0; i < 12; i++) {
        // make the regex if we don't have it already
        mom = createUTC([2000, i]);
        if (strict && !this._longMonthsParse[i]) {
            this._longMonthsParse[i] = new RegExp('^' + this.months(mom, '').replace('.', '') + '$', 'i');
            this._shortMonthsParse[i] = new RegExp('^' + this.monthsShort(mom, '').replace('.', '') + '$', 'i');
        }
        if (!strict && !this._monthsParse[i]) {
            regex = '^' + this.months(mom, '') + '|^' + this.monthsShort(mom, '');
            this._monthsParse[i] = new RegExp(regex.replace('.', ''), 'i');
        }
        // test the regex
        if (strict && format === 'MMMM' && this._longMonthsParse[i].test(monthName)) {
            return i;
        } else if (strict && format === 'MMM' && this._shortMonthsParse[i].test(monthName)) {
            return i;
        } else if (!strict && this._monthsParse[i].test(monthName)) {
            return i;
        }
    }
}

// MOMENTS

function setMonth (mom, value) {
    var dayOfMonth;

    if (!mom.isValid()) {
        // No op
        return mom;
    }

    if (typeof value === 'string') {
        if (/^\d+$/.test(value)) {
            value = toInt(value);
        } else {
            value = mom.localeData().monthsParse(value);
            // TODO: Another silent failure?
            if (!isNumber(value)) {
                return mom;
            }
        }
    }

    dayOfMonth = Math.min(mom.date(), daysInMonth(mom.year(), value));
    mom._d['set' + (mom._isUTC ? 'UTC' : '') + 'Month'](value, dayOfMonth);
    return mom;
}

function getSetMonth (value) {
    if (value != null) {
        setMonth(this, value);
        hooks.updateOffset(this, true);
        return this;
    } else {
        return get(this, 'Month');
    }
}

function getDaysInMonth () {
    return daysInMonth(this.year(), this.month());
}

var defaultMonthsShortRegex = matchWord;
function monthsShortRegex (isStrict) {
    if (this._monthsParseExact) {
        if (!hasOwnProp(this, '_monthsRegex')) {
            computeMonthsParse.call(this);
        }
        if (isStrict) {
            return this._monthsShortStrictRegex;
        } else {
            return this._monthsShortRegex;
        }
    } else {
        if (!hasOwnProp(this, '_monthsShortRegex')) {
            this._monthsShortRegex = defaultMonthsShortRegex;
        }
        return this._monthsShortStrictRegex && isStrict ?
            this._monthsShortStrictRegex : this._monthsShortRegex;
    }
}

var defaultMonthsRegex = matchWord;
function monthsRegex (isStrict) {
    if (this._monthsParseExact) {
        if (!hasOwnProp(this, '_monthsRegex')) {
            computeMonthsParse.call(this);
        }
        if (isStrict) {
            return this._monthsStrictRegex;
        } else {
            return this._monthsRegex;
        }
    } else {
        if (!hasOwnProp(this, '_monthsRegex')) {
            this._monthsRegex = defaultMonthsRegex;
        }
        return this._monthsStrictRegex && isStrict ?
            this._monthsStrictRegex : this._monthsRegex;
    }
}

function computeMonthsParse () {
    function cmpLenRev(a, b) {
        return b.length - a.length;
    }

    var shortPieces = [], longPieces = [], mixedPieces = [],
        i, mom;
    for (i = 0; i < 12; i++) {
        // make the regex if we don't have it already
        mom = createUTC([2000, i]);
        shortPieces.push(this.monthsShort(mom, ''));
        longPieces.push(this.months(mom, ''));
        mixedPieces.push(this.months(mom, ''));
        mixedPieces.push(this.monthsShort(mom, ''));
    }
    // Sorting makes sure if one month (or abbr) is a prefix of another it
    // will match the longer piece.
    shortPieces.sort(cmpLenRev);
    longPieces.sort(cmpLenRev);
    mixedPieces.sort(cmpLenRev);
    for (i = 0; i < 12; i++) {
        shortPieces[i] = regexEscape(shortPieces[i]);
        longPieces[i] = regexEscape(longPieces[i]);
    }
    for (i = 0; i < 24; i++) {
        mixedPieces[i] = regexEscape(mixedPieces[i]);
    }

    this._monthsRegex = new RegExp('^(' + mixedPieces.join('|') + ')', 'i');
    this._monthsShortRegex = this._monthsRegex;
    this._monthsStrictRegex = new RegExp('^(' + longPieces.join('|') + ')', 'i');
    this._monthsShortStrictRegex = new RegExp('^(' + shortPieces.join('|') + ')', 'i');
}

// FORMATTING

addFormatToken('Y', 0, 0, function () {
    var y = this.year();
    return y <= 9999 ? '' + y : '+' + y;
});

addFormatToken(0, ['YY', 2], 0, function () {
    return this.year() % 100;
});

addFormatToken(0, ['YYYY',   4],       0, 'year');
addFormatToken(0, ['YYYYY',  5],       0, 'year');
addFormatToken(0, ['YYYYYY', 6, true], 0, 'year');

// ALIASES

addUnitAlias('year', 'y');

// PRIORITIES

addUnitPriority('year', 1);

// PARSING

addRegexToken('Y',      matchSigned);
addRegexToken('YY',     match1to2, match2);
addRegexToken('YYYY',   match1to4, match4);
addRegexToken('YYYYY',  match1to6, match6);
addRegexToken('YYYYYY', match1to6, match6);

addParseToken(['YYYYY', 'YYYYYY'], YEAR);
addParseToken('YYYY', function (input, array) {
    array[YEAR] = input.length === 2 ? hooks.parseTwoDigitYear(input) : toInt(input);
});
addParseToken('YY', function (input, array) {
    array[YEAR] = hooks.parseTwoDigitYear(input);
});
addParseToken('Y', function (input, array) {
    array[YEAR] = parseInt(input, 10);
});

// HELPERS

function daysInYear(year) {
    return isLeapYear(year) ? 366 : 365;
}

function isLeapYear(year) {
    return (year % 4 === 0 && year % 100 !== 0) || year % 400 === 0;
}

// HOOKS

hooks.parseTwoDigitYear = function (input) {
    return toInt(input) + (toInt(input) > 68 ? 1900 : 2000);
};

// MOMENTS

var getSetYear = makeGetSet('FullYear', true);

function getIsLeapYear () {
    return isLeapYear(this.year());
}

function createDate (y, m, d, h, M, s, ms) {
    // can't just apply() to create a date:
    // https://stackoverflow.com/q/181348
    var date = new Date(y, m, d, h, M, s, ms);

    // the date constructor remaps years 0-99 to 1900-1999
    if (y < 100 && y >= 0 && isFinite(date.getFullYear())) {
        date.setFullYear(y);
    }
    return date;
}

function createUTCDate (y) {
    var date = new Date(Date.UTC.apply(null, arguments));

    // the Date.UTC function remaps years 0-99 to 1900-1999
    if (y < 100 && y >= 0 && isFinite(date.getUTCFullYear())) {
        date.setUTCFullYear(y);
    }
    return date;
}

// start-of-first-week - start-of-year
function firstWeekOffset(year, dow, doy) {
    var // first-week day -- which january is always in the first week (4 for iso, 1 for other)
        fwd = 7 + dow - doy,
        // first-week day local weekday -- which local weekday is fwd
        fwdlw = (7 + createUTCDate(year, 0, fwd).getUTCDay() - dow) % 7;

    return -fwdlw + fwd - 1;
}

// https://en.wikipedia.org/wiki/ISO_week_date#Calculating_a_date_given_the_year.2C_week_number_and_weekday
function dayOfYearFromWeeks(year, week, weekday, dow, doy) {
    var localWeekday = (7 + weekday - dow) % 7,
        weekOffset = firstWeekOffset(year, dow, doy),
        dayOfYear = 1 + 7 * (week - 1) + localWeekday + weekOffset,
        resYear, resDayOfYear;

    if (dayOfYear <= 0) {
        resYear = year - 1;
        resDayOfYear = daysInYear(resYear) + dayOfYear;
    } else if (dayOfYear > daysInYear(year)) {
        resYear = year + 1;
        resDayOfYear = dayOfYear - daysInYear(year);
    } else {
        resYear = year;
        resDayOfYear = dayOfYear;
    }

    return {
        year: resYear,
        dayOfYear: resDayOfYear
    };
}

function weekOfYear(mom, dow, doy) {
    var weekOffset = firstWeekOffset(mom.year(), dow, doy),
        week = Math.floor((mom.dayOfYear() - weekOffset - 1) / 7) + 1,
        resWeek, resYear;

    if (week < 1) {
        resYear = mom.year() - 1;
        resWeek = week + weeksInYear(resYear, dow, doy);
    } else if (week > weeksInYear(mom.year(), dow, doy)) {
        resWeek = week - weeksInYear(mom.year(), dow, doy);
        resYear = mom.year() + 1;
    } else {
        resYear = mom.year();
        resWeek = week;
    }

    return {
        week: resWeek,
        year: resYear
    };
}

function weeksInYear(year, dow, doy) {
    var weekOffset = firstWeekOffset(year, dow, doy),
        weekOffsetNext = firstWeekOffset(year + 1, dow, doy);
    return (daysInYear(year) - weekOffset + weekOffsetNext) / 7;
}

// FORMATTING

addFormatToken('w', ['ww', 2], 'wo', 'week');
addFormatToken('W', ['WW', 2], 'Wo', 'isoWeek');

// ALIASES

addUnitAlias('week', 'w');
addUnitAlias('isoWeek', 'W');

// PRIORITIES

addUnitPriority('week', 5);
addUnitPriority('isoWeek', 5);

// PARSING

addRegexToken('w',  match1to2);
addRegexToken('ww', match1to2, match2);
addRegexToken('W',  match1to2);
addRegexToken('WW', match1to2, match2);

addWeekParseToken(['w', 'ww', 'W', 'WW'], function (input, week, config, token) {
    week[token.substr(0, 1)] = toInt(input);
});

// HELPERS

// LOCALES

function localeWeek (mom) {
    return weekOfYear(mom, this._week.dow, this._week.doy).week;
}

var defaultLocaleWeek = {
    dow : 0, // Sunday is the first day of the week.
    doy : 6  // The week that contains Jan 1st is the first week of the year.
};

function localeFirstDayOfWeek () {
    return this._week.dow;
}

function localeFirstDayOfYear () {
    return this._week.doy;
}

// MOMENTS

function getSetWeek (input) {
    var week = this.localeData().week(this);
    return input == null ? week : this.add((input - week) * 7, 'd');
}

function getSetISOWeek (input) {
    var week = weekOfYear(this, 1, 4).week;
    return input == null ? week : this.add((input - week) * 7, 'd');
}

// FORMATTING

addFormatToken('d', 0, 'do', 'day');

addFormatToken('dd', 0, 0, function (format) {
    return this.localeData().weekdaysMin(this, format);
});

addFormatToken('ddd', 0, 0, function (format) {
    return this.localeData().weekdaysShort(this, format);
});

addFormatToken('dddd', 0, 0, function (format) {
    return this.localeData().weekdays(this, format);
});

addFormatToken('e', 0, 0, 'weekday');
addFormatToken('E', 0, 0, 'isoWeekday');

// ALIASES

addUnitAlias('day', 'd');
addUnitAlias('weekday', 'e');
addUnitAlias('isoWeekday', 'E');

// PRIORITY
addUnitPriority('day', 11);
addUnitPriority('weekday', 11);
addUnitPriority('isoWeekday', 11);

// PARSING

addRegexToken('d',    match1to2);
addRegexToken('e',    match1to2);
addRegexToken('E',    match1to2);
addRegexToken('dd',   function (isStrict, locale) {
    return locale.weekdaysMinRegex(isStrict);
});
addRegexToken('ddd',   function (isStrict, locale) {
    return locale.weekdaysShortRegex(isStrict);
});
addRegexToken('dddd',   function (isStrict, locale) {
    return locale.weekdaysRegex(isStrict);
});

addWeekParseToken(['dd', 'ddd', 'dddd'], function (input, week, config, token) {
    var weekday = config._locale.weekdaysParse(input, token, config._strict);
    // if we didn't get a weekday name, mark the date as invalid
    if (weekday != null) {
        week.d = weekday;
    } else {
        getParsingFlags(config).invalidWeekday = input;
    }
});

addWeekParseToken(['d', 'e', 'E'], function (input, week, config, token) {
    week[token] = toInt(input);
});

// HELPERS

function parseWeekday(input, locale) {
    if (typeof input !== 'string') {
        return input;
    }

    if (!isNaN(input)) {
        return parseInt(input, 10);
    }

    input = locale.weekdaysParse(input);
    if (typeof input === 'number') {
        return input;
    }

    return null;
}

function parseIsoWeekday(input, locale) {
    if (typeof input === 'string') {
        return locale.weekdaysParse(input) % 7 || 7;
    }
    return isNaN(input) ? null : input;
}

// LOCALES

var defaultLocaleWeekdays = 'Sunday_Monday_Tuesday_Wednesday_Thursday_Friday_Saturday'.split('_');
function localeWeekdays (m, format) {
    if (!m) {
        return isArray(this._weekdays) ? this._weekdays :
            this._weekdays['standalone'];
    }
    return isArray(this._weekdays) ? this._weekdays[m.day()] :
        this._weekdays[this._weekdays.isFormat.test(format) ? 'format' : 'standalone'][m.day()];
}

var defaultLocaleWeekdaysShort = 'Sun_Mon_Tue_Wed_Thu_Fri_Sat'.split('_');
function localeWeekdaysShort (m) {
    return (m) ? this._weekdaysShort[m.day()] : this._weekdaysShort;
}

var defaultLocaleWeekdaysMin = 'Su_Mo_Tu_We_Th_Fr_Sa'.split('_');
function localeWeekdaysMin (m) {
    return (m) ? this._weekdaysMin[m.day()] : this._weekdaysMin;
}

function handleStrictParse$1(weekdayName, format, strict) {
    var i, ii, mom, llc = weekdayName.toLocaleLowerCase();
    if (!this._weekdaysParse) {
        this._weekdaysParse = [];
        this._shortWeekdaysParse = [];
        this._minWeekdaysParse = [];

        for (i = 0; i < 7; ++i) {
            mom = createUTC([2000, 1]).day(i);
            this._minWeekdaysParse[i] = this.weekdaysMin(mom, '').toLocaleLowerCase();
            this._shortWeekdaysParse[i] = this.weekdaysShort(mom, '').toLocaleLowerCase();
            this._weekdaysParse[i] = this.weekdays(mom, '').toLocaleLowerCase();
        }
    }

    if (strict) {
        if (format === 'dddd') {
            ii = indexOf$1.call(this._weekdaysParse, llc);
            return ii !== -1 ? ii : null;
        } else if (format === 'ddd') {
            ii = indexOf$1.call(this._shortWeekdaysParse, llc);
            return ii !== -1 ? ii : null;
        } else {
            ii = indexOf$1.call(this._minWeekdaysParse, llc);
            return ii !== -1 ? ii : null;
        }
    } else {
        if (format === 'dddd') {
            ii = indexOf$1.call(this._weekdaysParse, llc);
            if (ii !== -1) {
                return ii;
            }
            ii = indexOf$1.call(this._shortWeekdaysParse, llc);
            if (ii !== -1) {
                return ii;
            }
            ii = indexOf$1.call(this._minWeekdaysParse, llc);
            return ii !== -1 ? ii : null;
        } else if (format === 'ddd') {
            ii = indexOf$1.call(this._shortWeekdaysParse, llc);
            if (ii !== -1) {
                return ii;
            }
            ii = indexOf$1.call(this._weekdaysParse, llc);
            if (ii !== -1) {
                return ii;
            }
            ii = indexOf$1.call(this._minWeekdaysParse, llc);
            return ii !== -1 ? ii : null;
        } else {
            ii = indexOf$1.call(this._minWeekdaysParse, llc);
            if (ii !== -1) {
                return ii;
            }
            ii = indexOf$1.call(this._weekdaysParse, llc);
            if (ii !== -1) {
                return ii;
            }
            ii = indexOf$1.call(this._shortWeekdaysParse, llc);
            return ii !== -1 ? ii : null;
        }
    }
}

function localeWeekdaysParse (weekdayName, format, strict) {
    var i, mom, regex;

    if (this._weekdaysParseExact) {
        return handleStrictParse$1.call(this, weekdayName, format, strict);
    }

    if (!this._weekdaysParse) {
        this._weekdaysParse = [];
        this._minWeekdaysParse = [];
        this._shortWeekdaysParse = [];
        this._fullWeekdaysParse = [];
    }

    for (i = 0; i < 7; i++) {
        // make the regex if we don't have it already

        mom = createUTC([2000, 1]).day(i);
        if (strict && !this._fullWeekdaysParse[i]) {
            this._fullWeekdaysParse[i] = new RegExp('^' + this.weekdays(mom, '').replace('.', '\.?') + '$', 'i');
            this._shortWeekdaysParse[i] = new RegExp('^' + this.weekdaysShort(mom, '').replace('.', '\.?') + '$', 'i');
            this._minWeekdaysParse[i] = new RegExp('^' + this.weekdaysMin(mom, '').replace('.', '\.?') + '$', 'i');
        }
        if (!this._weekdaysParse[i]) {
            regex = '^' + this.weekdays(mom, '') + '|^' + this.weekdaysShort(mom, '') + '|^' + this.weekdaysMin(mom, '');
            this._weekdaysParse[i] = new RegExp(regex.replace('.', ''), 'i');
        }
        // test the regex
        if (strict && format === 'dddd' && this._fullWeekdaysParse[i].test(weekdayName)) {
            return i;
        } else if (strict && format === 'ddd' && this._shortWeekdaysParse[i].test(weekdayName)) {
            return i;
        } else if (strict && format === 'dd' && this._minWeekdaysParse[i].test(weekdayName)) {
            return i;
        } else if (!strict && this._weekdaysParse[i].test(weekdayName)) {
            return i;
        }
    }
}

// MOMENTS

function getSetDayOfWeek (input) {
    if (!this.isValid()) {
        return input != null ? this : NaN;
    }
    var day = this._isUTC ? this._d.getUTCDay() : this._d.getDay();
    if (input != null) {
        input = parseWeekday(input, this.localeData());
        return this.add(input - day, 'd');
    } else {
        return day;
    }
}

function getSetLocaleDayOfWeek (input) {
    if (!this.isValid()) {
        return input != null ? this : NaN;
    }
    var weekday = (this.day() + 7 - this.localeData()._week.dow) % 7;
    return input == null ? weekday : this.add(input - weekday, 'd');
}

function getSetISODayOfWeek (input) {
    if (!this.isValid()) {
        return input != null ? this : NaN;
    }

    // behaves the same as moment#day except
    // as a getter, returns 7 instead of 0 (1-7 range instead of 0-6)
    // as a setter, sunday should belong to the previous week.

    if (input != null) {
        var weekday = parseIsoWeekday(input, this.localeData());
        return this.day(this.day() % 7 ? weekday : weekday - 7);
    } else {
        return this.day() || 7;
    }
}

var defaultWeekdaysRegex = matchWord;
function weekdaysRegex (isStrict) {
    if (this._weekdaysParseExact) {
        if (!hasOwnProp(this, '_weekdaysRegex')) {
            computeWeekdaysParse.call(this);
        }
        if (isStrict) {
            return this._weekdaysStrictRegex;
        } else {
            return this._weekdaysRegex;
        }
    } else {
        if (!hasOwnProp(this, '_weekdaysRegex')) {
            this._weekdaysRegex = defaultWeekdaysRegex;
        }
        return this._weekdaysStrictRegex && isStrict ?
            this._weekdaysStrictRegex : this._weekdaysRegex;
    }
}

var defaultWeekdaysShortRegex = matchWord;
function weekdaysShortRegex (isStrict) {
    if (this._weekdaysParseExact) {
        if (!hasOwnProp(this, '_weekdaysRegex')) {
            computeWeekdaysParse.call(this);
        }
        if (isStrict) {
            return this._weekdaysShortStrictRegex;
        } else {
            return this._weekdaysShortRegex;
        }
    } else {
        if (!hasOwnProp(this, '_weekdaysShortRegex')) {
            this._weekdaysShortRegex = defaultWeekdaysShortRegex;
        }
        return this._weekdaysShortStrictRegex && isStrict ?
            this._weekdaysShortStrictRegex : this._weekdaysShortRegex;
    }
}

var defaultWeekdaysMinRegex = matchWord;
function weekdaysMinRegex (isStrict) {
    if (this._weekdaysParseExact) {
        if (!hasOwnProp(this, '_weekdaysRegex')) {
            computeWeekdaysParse.call(this);
        }
        if (isStrict) {
            return this._weekdaysMinStrictRegex;
        } else {
            return this._weekdaysMinRegex;
        }
    } else {
        if (!hasOwnProp(this, '_weekdaysMinRegex')) {
            this._weekdaysMinRegex = defaultWeekdaysMinRegex;
        }
        return this._weekdaysMinStrictRegex && isStrict ?
            this._weekdaysMinStrictRegex : this._weekdaysMinRegex;
    }
}


function computeWeekdaysParse () {
    function cmpLenRev(a, b) {
        return b.length - a.length;
    }

    var minPieces = [], shortPieces = [], longPieces = [], mixedPieces = [],
        i, mom, minp, shortp, longp;
    for (i = 0; i < 7; i++) {
        // make the regex if we don't have it already
        mom = createUTC([2000, 1]).day(i);
        minp = this.weekdaysMin(mom, '');
        shortp = this.weekdaysShort(mom, '');
        longp = this.weekdays(mom, '');
        minPieces.push(minp);
        shortPieces.push(shortp);
        longPieces.push(longp);
        mixedPieces.push(minp);
        mixedPieces.push(shortp);
        mixedPieces.push(longp);
    }
    // Sorting makes sure if one weekday (or abbr) is a prefix of another it
    // will match the longer piece.
    minPieces.sort(cmpLenRev);
    shortPieces.sort(cmpLenRev);
    longPieces.sort(cmpLenRev);
    mixedPieces.sort(cmpLenRev);
    for (i = 0; i < 7; i++) {
        shortPieces[i] = regexEscape(shortPieces[i]);
        longPieces[i] = regexEscape(longPieces[i]);
        mixedPieces[i] = regexEscape(mixedPieces[i]);
    }

    this._weekdaysRegex = new RegExp('^(' + mixedPieces.join('|') + ')', 'i');
    this._weekdaysShortRegex = this._weekdaysRegex;
    this._weekdaysMinRegex = this._weekdaysRegex;

    this._weekdaysStrictRegex = new RegExp('^(' + longPieces.join('|') + ')', 'i');
    this._weekdaysShortStrictRegex = new RegExp('^(' + shortPieces.join('|') + ')', 'i');
    this._weekdaysMinStrictRegex = new RegExp('^(' + minPieces.join('|') + ')', 'i');
}

// FORMATTING

function hFormat() {
    return this.hours() % 12 || 12;
}

function kFormat() {
    return this.hours() || 24;
}

addFormatToken('H', ['HH', 2], 0, 'hour');
addFormatToken('h', ['hh', 2], 0, hFormat);
addFormatToken('k', ['kk', 2], 0, kFormat);

addFormatToken('hmm', 0, 0, function () {
    return '' + hFormat.apply(this) + zeroFill(this.minutes(), 2);
});

addFormatToken('hmmss', 0, 0, function () {
    return '' + hFormat.apply(this) + zeroFill(this.minutes(), 2) +
        zeroFill(this.seconds(), 2);
});

addFormatToken('Hmm', 0, 0, function () {
    return '' + this.hours() + zeroFill(this.minutes(), 2);
});

addFormatToken('Hmmss', 0, 0, function () {
    return '' + this.hours() + zeroFill(this.minutes(), 2) +
        zeroFill(this.seconds(), 2);
});

function meridiem (token, lowercase) {
    addFormatToken(token, 0, 0, function () {
        return this.localeData().meridiem(this.hours(), this.minutes(), lowercase);
    });
}

meridiem('a', true);
meridiem('A', false);

// ALIASES

addUnitAlias('hour', 'h');

// PRIORITY
addUnitPriority('hour', 13);

// PARSING

function matchMeridiem (isStrict, locale) {
    return locale._meridiemParse;
}

addRegexToken('a',  matchMeridiem);
addRegexToken('A',  matchMeridiem);
addRegexToken('H',  match1to2);
addRegexToken('h',  match1to2);
addRegexToken('k',  match1to2);
addRegexToken('HH', match1to2, match2);
addRegexToken('hh', match1to2, match2);
addRegexToken('kk', match1to2, match2);

addRegexToken('hmm', match3to4);
addRegexToken('hmmss', match5to6);
addRegexToken('Hmm', match3to4);
addRegexToken('Hmmss', match5to6);

addParseToken(['H', 'HH'], HOUR);
addParseToken(['k', 'kk'], function (input, array, config) {
    var kInput = toInt(input);
    array[HOUR] = kInput === 24 ? 0 : kInput;
});
addParseToken(['a', 'A'], function (input, array, config) {
    config._isPm = config._locale.isPM(input);
    config._meridiem = input;
});
addParseToken(['h', 'hh'], function (input, array, config) {
    array[HOUR] = toInt(input);
    getParsingFlags(config).bigHour = true;
});
addParseToken('hmm', function (input, array, config) {
    var pos = input.length - 2;
    array[HOUR] = toInt(input.substr(0, pos));
    array[MINUTE] = toInt(input.substr(pos));
    getParsingFlags(config).bigHour = true;
});
addParseToken('hmmss', function (input, array, config) {
    var pos1 = input.length - 4;
    var pos2 = input.length - 2;
    array[HOUR] = toInt(input.substr(0, pos1));
    array[MINUTE] = toInt(input.substr(pos1, 2));
    array[SECOND] = toInt(input.substr(pos2));
    getParsingFlags(config).bigHour = true;
});
addParseToken('Hmm', function (input, array, config) {
    var pos = input.length - 2;
    array[HOUR] = toInt(input.substr(0, pos));
    array[MINUTE] = toInt(input.substr(pos));
});
addParseToken('Hmmss', function (input, array, config) {
    var pos1 = input.length - 4;
    var pos2 = input.length - 2;
    array[HOUR] = toInt(input.substr(0, pos1));
    array[MINUTE] = toInt(input.substr(pos1, 2));
    array[SECOND] = toInt(input.substr(pos2));
});

// LOCALES

function localeIsPM (input) {
    // IE8 Quirks Mode & IE7 Standards Mode do not allow accessing strings like arrays
    // Using charAt should be more compatible.
    return ((input + '').toLowerCase().charAt(0) === 'p');
}

var defaultLocaleMeridiemParse = /[ap]\.?m?\.?/i;
function localeMeridiem (hours, minutes, isLower) {
    if (hours > 11) {
        return isLower ? 'pm' : 'PM';
    } else {
        return isLower ? 'am' : 'AM';
    }
}


// MOMENTS

// Setting the hour should keep the time, because the user explicitly
// specified which hour he wants. So trying to maintain the same hour (in
// a new timezone) makes sense. Adding/subtracting hours does not follow
// this rule.
var getSetHour = makeGetSet('Hours', true);

// months
// week
// weekdays
// meridiem
var baseConfig = {
    calendar: defaultCalendar,
    longDateFormat: defaultLongDateFormat,
    invalidDate: defaultInvalidDate,
    ordinal: defaultOrdinal,
    dayOfMonthOrdinalParse: defaultDayOfMonthOrdinalParse,
    relativeTime: defaultRelativeTime,

    months: defaultLocaleMonths,
    monthsShort: defaultLocaleMonthsShort,

    week: defaultLocaleWeek,

    weekdays: defaultLocaleWeekdays,
    weekdaysMin: defaultLocaleWeekdaysMin,
    weekdaysShort: defaultLocaleWeekdaysShort,

    meridiemParse: defaultLocaleMeridiemParse
};

// internal storage for locale config files
var locales = {};
var localeFamilies = {};
var globalLocale;

function normalizeLocale(key) {
    return key ? key.toLowerCase().replace('_', '-') : key;
}

// pick the locale from the array
// try ['en-au', 'en-gb'] as 'en-au', 'en-gb', 'en', as in move through the list trying each
// substring from most specific to least, but move to the next array item if it's a more specific variant than the current root
function chooseLocale(names) {
    var i = 0, j, next, locale, split;

    while (i < names.length) {
        split = normalizeLocale(names[i]).split('-');
        j = split.length;
        next = normalizeLocale(names[i + 1]);
        next = next ? next.split('-') : null;
        while (j > 0) {
            locale = loadLocale(split.slice(0, j).join('-'));
            if (locale) {
                return locale;
            }
            if (next && next.length >= j && compareArrays(split, next, true) >= j - 1) {
                //the next array item is better than a shallower substring of this one
                break;
            }
            j--;
        }
        i++;
    }
    return null;
}

function loadLocale(name) {
    var oldLocale = null;
    // TODO: Find a better way to register and load all the locales in Node
    if (!locales[name] && (typeof module !== 'undefined') &&
            module && module.exports) {
        try {
            oldLocale = globalLocale._abbr;
            require('./locale/' + name);
            // because defineLocale currently also sets the global locale, we
            // want to undo that for lazy loaded locales
            getSetGlobalLocale(oldLocale);
        } catch (e) { }
    }
    return locales[name];
}

// This function will load locale and then set the global locale.  If
// no arguments are passed in, it will simply return the current global
// locale key.
function getSetGlobalLocale (key, values) {
    var data;
    if (key) {
        if (isUndefined(values)) {
            data = getLocale(key);
        }
        else {
            data = defineLocale(key, values);
        }

        if (data) {
            // moment.duration._locale = moment._locale = data;
            globalLocale = data;
        }
    }

    return globalLocale._abbr;
}

function defineLocale (name, config) {
    if (config !== null) {
        var parentConfig = baseConfig;
        config.abbr = name;
        if (locales[name] != null) {
            deprecateSimple('defineLocaleOverride',
                    'use moment.updateLocale(localeName, config) to change ' +
                    'an existing locale. moment.defineLocale(localeName, ' +
                    'config) should only be used for creating a new locale ' +
                    'See http://momentjs.com/guides/#/warnings/define-locale/ for more info.');
            parentConfig = locales[name]._config;
        } else if (config.parentLocale != null) {
            if (locales[config.parentLocale] != null) {
                parentConfig = locales[config.parentLocale]._config;
            } else {
                if (!localeFamilies[config.parentLocale]) {
                    localeFamilies[config.parentLocale] = [];
                }
                localeFamilies[config.parentLocale].push({
                    name: name,
                    config: config
                });
                return null;
            }
        }
        locales[name] = new Locale(mergeConfigs(parentConfig, config));

        if (localeFamilies[name]) {
            localeFamilies[name].forEach(function (x) {
                defineLocale(x.name, x.config);
            });
        }

        // backwards compat for now: also set the locale
        // make sure we set the locale AFTER all child locales have been
        // created, so we won't end up with the child locale set.
        getSetGlobalLocale(name);


        return locales[name];
    } else {
        // useful for testing
        delete locales[name];
        return null;
    }
}

function updateLocale(name, config) {
    if (config != null) {
        var locale, parentConfig = baseConfig;
        // MERGE
        if (locales[name] != null) {
            parentConfig = locales[name]._config;
        }
        config = mergeConfigs(parentConfig, config);
        locale = new Locale(config);
        locale.parentLocale = locales[name];
        locales[name] = locale;

        // backwards compat for now: also set the locale
        getSetGlobalLocale(name);
    } else {
        // pass null for config to unupdate, useful for tests
        if (locales[name] != null) {
            if (locales[name].parentLocale != null) {
                locales[name] = locales[name].parentLocale;
            } else if (locales[name] != null) {
                delete locales[name];
            }
        }
    }
    return locales[name];
}

// returns locale data
function getLocale (key) {
    var locale;

    if (key && key._locale && key._locale._abbr) {
        key = key._locale._abbr;
    }

    if (!key) {
        return globalLocale;
    }

    if (!isArray(key)) {
        //short-circuit everything else
        locale = loadLocale(key);
        if (locale) {
            return locale;
        }
        key = [key];
    }

    return chooseLocale(key);
}

function listLocales() {
    return keys$1(locales);
}

function checkOverflow (m) {
    var overflow;
    var a = m._a;

    if (a && getParsingFlags(m).overflow === -2) {
        overflow =
            a[MONTH]       < 0 || a[MONTH]       > 11  ? MONTH :
            a[DATE]        < 1 || a[DATE]        > daysInMonth(a[YEAR], a[MONTH]) ? DATE :
            a[HOUR]        < 0 || a[HOUR]        > 24 || (a[HOUR] === 24 && (a[MINUTE] !== 0 || a[SECOND] !== 0 || a[MILLISECOND] !== 0)) ? HOUR :
            a[MINUTE]      < 0 || a[MINUTE]      > 59  ? MINUTE :
            a[SECOND]      < 0 || a[SECOND]      > 59  ? SECOND :
            a[MILLISECOND] < 0 || a[MILLISECOND] > 999 ? MILLISECOND :
            -1;

        if (getParsingFlags(m)._overflowDayOfYear && (overflow < YEAR || overflow > DATE)) {
            overflow = DATE;
        }
        if (getParsingFlags(m)._overflowWeeks && overflow === -1) {
            overflow = WEEK;
        }
        if (getParsingFlags(m)._overflowWeekday && overflow === -1) {
            overflow = WEEKDAY;
        }

        getParsingFlags(m).overflow = overflow;
    }

    return m;
}

// iso 8601 regex
// 0000-00-00 0000-W00 or 0000-W00-0 + T + 00 or 00:00 or 00:00:00 or 00:00:00.000 + +00:00 or +0000 or +00)
var extendedIsoRegex = /^\s*((?:[+-]\d{6}|\d{4})-(?:\d\d-\d\d|W\d\d-\d|W\d\d|\d\d\d|\d\d))(?:(T| )(\d\d(?::\d\d(?::\d\d(?:[.,]\d+)?)?)?)([\+\-]\d\d(?::?\d\d)?|\s*Z)?)?$/;
var basicIsoRegex = /^\s*((?:[+-]\d{6}|\d{4})(?:\d\d\d\d|W\d\d\d|W\d\d|\d\d\d|\d\d))(?:(T| )(\d\d(?:\d\d(?:\d\d(?:[.,]\d+)?)?)?)([\+\-]\d\d(?::?\d\d)?|\s*Z)?)?$/;

var tzRegex = /Z|[+-]\d\d(?::?\d\d)?/;

var isoDates = [
    ['YYYYYY-MM-DD', /[+-]\d{6}-\d\d-\d\d/],
    ['YYYY-MM-DD', /\d{4}-\d\d-\d\d/],
    ['GGGG-[W]WW-E', /\d{4}-W\d\d-\d/],
    ['GGGG-[W]WW', /\d{4}-W\d\d/, false],
    ['YYYY-DDD', /\d{4}-\d{3}/],
    ['YYYY-MM', /\d{4}-\d\d/, false],
    ['YYYYYYMMDD', /[+-]\d{10}/],
    ['YYYYMMDD', /\d{8}/],
    // YYYYMM is NOT allowed by the standard
    ['GGGG[W]WWE', /\d{4}W\d{3}/],
    ['GGGG[W]WW', /\d{4}W\d{2}/, false],
    ['YYYYDDD', /\d{7}/]
];

// iso time formats and regexes
var isoTimes = [
    ['HH:mm:ss.SSSS', /\d\d:\d\d:\d\d\.\d+/],
    ['HH:mm:ss,SSSS', /\d\d:\d\d:\d\d,\d+/],
    ['HH:mm:ss', /\d\d:\d\d:\d\d/],
    ['HH:mm', /\d\d:\d\d/],
    ['HHmmss.SSSS', /\d\d\d\d\d\d\.\d+/],
    ['HHmmss,SSSS', /\d\d\d\d\d\d,\d+/],
    ['HHmmss', /\d\d\d\d\d\d/],
    ['HHmm', /\d\d\d\d/],
    ['HH', /\d\d/]
];

var aspNetJsonRegex = /^\/?Date\((\-?\d+)/i;

// date from iso format
function configFromISO(config) {
    var i, l,
        string = config._i,
        match = extendedIsoRegex.exec(string) || basicIsoRegex.exec(string),
        allowTime, dateFormat, timeFormat, tzFormat;

    if (match) {
        getParsingFlags(config).iso = true;

        for (i = 0, l = isoDates.length; i < l; i++) {
            if (isoDates[i][1].exec(match[1])) {
                dateFormat = isoDates[i][0];
                allowTime = isoDates[i][2] !== false;
                break;
            }
        }
        if (dateFormat == null) {
            config._isValid = false;
            return;
        }
        if (match[3]) {
            for (i = 0, l = isoTimes.length; i < l; i++) {
                if (isoTimes[i][1].exec(match[3])) {
                    // match[2] should be 'T' or space
                    timeFormat = (match[2] || ' ') + isoTimes[i][0];
                    break;
                }
            }
            if (timeFormat == null) {
                config._isValid = false;
                return;
            }
        }
        if (!allowTime && timeFormat != null) {
            config._isValid = false;
            return;
        }
        if (match[4]) {
            if (tzRegex.exec(match[4])) {
                tzFormat = 'Z';
            } else {
                config._isValid = false;
                return;
            }
        }
        config._f = dateFormat + (timeFormat || '') + (tzFormat || '');
        configFromStringAndFormat(config);
    } else {
        config._isValid = false;
    }
}

// RFC 2822 regex: For details see https://tools.ietf.org/html/rfc2822#section-3.3
var basicRfcRegex = /^((?:Mon|Tue|Wed|Thu|Fri|Sat|Sun),?\s)?(\d?\d\s(?:Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec)\s(?:\d\d)?\d\d\s)(\d\d:\d\d)(\:\d\d)?(\s(?:UT|GMT|[ECMP][SD]T|[A-IK-Za-ik-z]|[+-]\d{4}))$/;

// date and time from ref 2822 format
function configFromRFC2822(config) {
    var string, match, dayFormat,
        dateFormat, timeFormat, tzFormat;
    var timezones = {
        ' GMT': ' +0000',
        ' EDT': ' -0400',
        ' EST': ' -0500',
        ' CDT': ' -0500',
        ' CST': ' -0600',
        ' MDT': ' -0600',
        ' MST': ' -0700',
        ' PDT': ' -0700',
        ' PST': ' -0800'
    };
    var military = 'YXWVUTSRQPONZABCDEFGHIKLM';
    var timezone, timezoneIndex;

    string = config._i
        .replace(/\([^\)]*\)|[\n\t]/g, ' ') // Remove comments and folding whitespace
        .replace(/(\s\s+)/g, ' ') // Replace multiple-spaces with a single space
        .replace(/^\s|\s$/g, ''); // Remove leading and trailing spaces
    match = basicRfcRegex.exec(string);

    if (match) {
        dayFormat = match[1] ? 'ddd' + ((match[1].length === 5) ? ', ' : ' ') : '';
        dateFormat = 'D MMM ' + ((match[2].length > 10) ? 'YYYY ' : 'YY ');
        timeFormat = 'HH:mm' + (match[4] ? ':ss' : '');

        // TODO: Replace the vanilla JS Date object with an indepentent day-of-week check.
        if (match[1]) { // day of week given
            var momentDate = new Date(match[2]);
            var momentDay = ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'][momentDate.getDay()];

            if (match[1].substr(0,3) !== momentDay) {
                getParsingFlags(config).weekdayMismatch = true;
                config._isValid = false;
                return;
            }
        }

        switch (match[5].length) {
            case 2: // military
                if (timezoneIndex === 0) {
                    timezone = ' +0000';
                } else {
                    timezoneIndex = military.indexOf(match[5][1].toUpperCase()) - 12;
                    timezone = ((timezoneIndex < 0) ? ' -' : ' +') +
                        (('' + timezoneIndex).replace(/^-?/, '0')).match(/..$/)[0] + '00';
                }
                break;
            case 4: // Zone
                timezone = timezones[match[5]];
                break;
            default: // UT or +/-9999
                timezone = timezones[' GMT'];
        }
        match[5] = timezone;
        config._i = match.splice(1).join('');
        tzFormat = ' ZZ';
        config._f = dayFormat + dateFormat + timeFormat + tzFormat;
        configFromStringAndFormat(config);
        getParsingFlags(config).rfc2822 = true;
    } else {
        config._isValid = false;
    }
}

// date from iso format or fallback
function configFromString(config) {
    var matched = aspNetJsonRegex.exec(config._i);

    if (matched !== null) {
        config._d = new Date(+matched[1]);
        return;
    }

    configFromISO(config);
    if (config._isValid === false) {
        delete config._isValid;
    } else {
        return;
    }

    configFromRFC2822(config);
    if (config._isValid === false) {
        delete config._isValid;
    } else {
        return;
    }

    // Final attempt, use Input Fallback
    hooks.createFromInputFallback(config);
}

hooks.createFromInputFallback = deprecate(
    'value provided is not in a recognized RFC2822 or ISO format. moment construction falls back to js Date(), ' +
    'which is not reliable across all browsers and versions. Non RFC2822/ISO date formats are ' +
    'discouraged and will be removed in an upcoming major release. Please refer to ' +
    'http://momentjs.com/guides/#/warnings/js-date/ for more info.',
    function (config) {
        config._d = new Date(config._i + (config._useUTC ? ' UTC' : ''));
    }
);

// Pick the first defined of two or three arguments.
function defaults(a, b, c) {
    if (a != null) {
        return a;
    }
    if (b != null) {
        return b;
    }
    return c;
}

function currentDateArray(config) {
    // hooks is actually the exported moment object
    var nowValue = new Date(hooks.now());
    if (config._useUTC) {
        return [nowValue.getUTCFullYear(), nowValue.getUTCMonth(), nowValue.getUTCDate()];
    }
    return [nowValue.getFullYear(), nowValue.getMonth(), nowValue.getDate()];
}

// convert an array to a date.
// the array should mirror the parameters below
// note: all values past the year are optional and will default to the lowest possible value.
// [year, month, day , hour, minute, second, millisecond]
function configFromArray (config) {
    var i, date, input = [], currentDate, yearToUse;

    if (config._d) {
        return;
    }

    currentDate = currentDateArray(config);

    //compute day of the year from weeks and weekdays
    if (config._w && config._a[DATE] == null && config._a[MONTH] == null) {
        dayOfYearFromWeekInfo(config);
    }

    //if the day of the year is set, figure out what it is
    if (config._dayOfYear != null) {
        yearToUse = defaults(config._a[YEAR], currentDate[YEAR]);

        if (config._dayOfYear > daysInYear(yearToUse) || config._dayOfYear === 0) {
            getParsingFlags(config)._overflowDayOfYear = true;
        }

        date = createUTCDate(yearToUse, 0, config._dayOfYear);
        config._a[MONTH] = date.getUTCMonth();
        config._a[DATE] = date.getUTCDate();
    }

    // Default to current date.
    // * if no year, month, day of month are given, default to today
    // * if day of month is given, default month and year
    // * if month is given, default only year
    // * if year is given, don't default anything
    for (i = 0; i < 3 && config._a[i] == null; ++i) {
        config._a[i] = input[i] = currentDate[i];
    }

    // Zero out whatever was not defaulted, including time
    for (; i < 7; i++) {
        config._a[i] = input[i] = (config._a[i] == null) ? (i === 2 ? 1 : 0) : config._a[i];
    }

    // Check for 24:00:00.000
    if (config._a[HOUR] === 24 &&
            config._a[MINUTE] === 0 &&
            config._a[SECOND] === 0 &&
            config._a[MILLISECOND] === 0) {
        config._nextDay = true;
        config._a[HOUR] = 0;
    }

    config._d = (config._useUTC ? createUTCDate : createDate).apply(null, input);
    // Apply timezone offset from input. The actual utcOffset can be changed
    // with parseZone.
    if (config._tzm != null) {
        config._d.setUTCMinutes(config._d.getUTCMinutes() - config._tzm);
    }

    if (config._nextDay) {
        config._a[HOUR] = 24;
    }
}

function dayOfYearFromWeekInfo(config) {
    var w, weekYear, week, weekday, dow, doy, temp, weekdayOverflow;

    w = config._w;
    if (w.GG != null || w.W != null || w.E != null) {
        dow = 1;
        doy = 4;

        // TODO: We need to take the current isoWeekYear, but that depends on
        // how we interpret now (local, utc, fixed offset). So create
        // a now version of current config (take local/utc/offset flags, and
        // create now).
        weekYear = defaults(w.GG, config._a[YEAR], weekOfYear(createLocal(), 1, 4).year);
        week = defaults(w.W, 1);
        weekday = defaults(w.E, 1);
        if (weekday < 1 || weekday > 7) {
            weekdayOverflow = true;
        }
    } else {
        dow = config._locale._week.dow;
        doy = config._locale._week.doy;

        var curWeek = weekOfYear(createLocal(), dow, doy);

        weekYear = defaults(w.gg, config._a[YEAR], curWeek.year);

        // Default to current week.
        week = defaults(w.w, curWeek.week);

        if (w.d != null) {
            // weekday -- low day numbers are considered next week
            weekday = w.d;
            if (weekday < 0 || weekday > 6) {
                weekdayOverflow = true;
            }
        } else if (w.e != null) {
            // local weekday -- counting starts from begining of week
            weekday = w.e + dow;
            if (w.e < 0 || w.e > 6) {
                weekdayOverflow = true;
            }
        } else {
            // default to begining of week
            weekday = dow;
        }
    }
    if (week < 1 || week > weeksInYear(weekYear, dow, doy)) {
        getParsingFlags(config)._overflowWeeks = true;
    } else if (weekdayOverflow != null) {
        getParsingFlags(config)._overflowWeekday = true;
    } else {
        temp = dayOfYearFromWeeks(weekYear, week, weekday, dow, doy);
        config._a[YEAR] = temp.year;
        config._dayOfYear = temp.dayOfYear;
    }
}

// constant that refers to the ISO standard
hooks.ISO_8601 = function () {};

// constant that refers to the RFC 2822 form
hooks.RFC_2822 = function () {};

// date from string and format string
function configFromStringAndFormat(config) {
    // TODO: Move this to another part of the creation flow to prevent circular deps
    if (config._f === hooks.ISO_8601) {
        configFromISO(config);
        return;
    }
    if (config._f === hooks.RFC_2822) {
        configFromRFC2822(config);
        return;
    }
    config._a = [];
    getParsingFlags(config).empty = true;

    // This array is used to make a Date, either with `new Date` or `Date.UTC`
    var string = '' + config._i,
        i, parsedInput, tokens, token, skipped,
        stringLength = string.length,
        totalParsedInputLength = 0;

    tokens = expandFormat(config._f, config._locale).match(formattingTokens) || [];

    for (i = 0; i < tokens.length; i++) {
        token = tokens[i];
        parsedInput = (string.match(getParseRegexForToken(token, config)) || [])[0];
        // console.log('token', token, 'parsedInput', parsedInput,
        //         'regex', getParseRegexForToken(token, config));
        if (parsedInput) {
            skipped = string.substr(0, string.indexOf(parsedInput));
            if (skipped.length > 0) {
                getParsingFlags(config).unusedInput.push(skipped);
            }
            string = string.slice(string.indexOf(parsedInput) + parsedInput.length);
            totalParsedInputLength += parsedInput.length;
        }
        // don't parse if it's not a known token
        if (formatTokenFunctions[token]) {
            if (parsedInput) {
                getParsingFlags(config).empty = false;
            }
            else {
                getParsingFlags(config).unusedTokens.push(token);
            }
            addTimeToArrayFromToken(token, parsedInput, config);
        }
        else if (config._strict && !parsedInput) {
            getParsingFlags(config).unusedTokens.push(token);
        }
    }

    // add remaining unparsed input length to the string
    getParsingFlags(config).charsLeftOver = stringLength - totalParsedInputLength;
    if (string.length > 0) {
        getParsingFlags(config).unusedInput.push(string);
    }

    // clear _12h flag if hour is <= 12
    if (config._a[HOUR] <= 12 &&
        getParsingFlags(config).bigHour === true &&
        config._a[HOUR] > 0) {
        getParsingFlags(config).bigHour = undefined;
    }

    getParsingFlags(config).parsedDateParts = config._a.slice(0);
    getParsingFlags(config).meridiem = config._meridiem;
    // handle meridiem
    config._a[HOUR] = meridiemFixWrap(config._locale, config._a[HOUR], config._meridiem);

    configFromArray(config);
    checkOverflow(config);
}


function meridiemFixWrap (locale, hour, meridiem) {
    var isPm;

    if (meridiem == null) {
        // nothing to do
        return hour;
    }
    if (locale.meridiemHour != null) {
        return locale.meridiemHour(hour, meridiem);
    } else if (locale.isPM != null) {
        // Fallback
        isPm = locale.isPM(meridiem);
        if (isPm && hour < 12) {
            hour += 12;
        }
        if (!isPm && hour === 12) {
            hour = 0;
        }
        return hour;
    } else {
        // this is not supposed to happen
        return hour;
    }
}

// date from string and array of format strings
function configFromStringAndArray(config) {
    var tempConfig,
        bestMoment,

        scoreToBeat,
        i,
        currentScore;

    if (config._f.length === 0) {
        getParsingFlags(config).invalidFormat = true;
        config._d = new Date(NaN);
        return;
    }

    for (i = 0; i < config._f.length; i++) {
        currentScore = 0;
        tempConfig = copyConfig({}, config);
        if (config._useUTC != null) {
            tempConfig._useUTC = config._useUTC;
        }
        tempConfig._f = config._f[i];
        configFromStringAndFormat(tempConfig);

        if (!isValid(tempConfig)) {
            continue;
        }

        // if there is any input that was not parsed add a penalty for that format
        currentScore += getParsingFlags(tempConfig).charsLeftOver;

        //or tokens
        currentScore += getParsingFlags(tempConfig).unusedTokens.length * 10;

        getParsingFlags(tempConfig).score = currentScore;

        if (scoreToBeat == null || currentScore < scoreToBeat) {
            scoreToBeat = currentScore;
            bestMoment = tempConfig;
        }
    }

    extend(config, bestMoment || tempConfig);
}

function configFromObject(config) {
    if (config._d) {
        return;
    }

    var i = normalizeObjectUnits(config._i);
    config._a = map([i.year, i.month, i.day || i.date, i.hour, i.minute, i.second, i.millisecond], function (obj) {
        return obj && parseInt(obj, 10);
    });

    configFromArray(config);
}

function createFromConfig (config) {
    var res = new Moment(checkOverflow(prepareConfig(config)));
    if (res._nextDay) {
        // Adding is smart enough around DST
        res.add(1, 'd');
        res._nextDay = undefined;
    }

    return res;
}

function prepareConfig (config) {
    var input = config._i,
        format = config._f;

    config._locale = config._locale || getLocale(config._l);

    if (input === null || (format === undefined && input === '')) {
        return createInvalid({nullInput: true});
    }

    if (typeof input === 'string') {
        config._i = input = config._locale.preparse(input);
    }

    if (isMoment(input)) {
        return new Moment(checkOverflow(input));
    } else if (isDate(input)) {
        config._d = input;
    } else if (isArray(format)) {
        configFromStringAndArray(config);
    } else if (format) {
        configFromStringAndFormat(config);
    }  else {
        configFromInput(config);
    }

    if (!isValid(config)) {
        config._d = null;
    }

    return config;
}

function configFromInput(config) {
    var input = config._i;
    if (isUndefined(input)) {
        config._d = new Date(hooks.now());
    } else if (isDate(input)) {
        config._d = new Date(input.valueOf());
    } else if (typeof input === 'string') {
        configFromString(config);
    } else if (isArray(input)) {
        config._a = map(input.slice(0), function (obj) {
            return parseInt(obj, 10);
        });
        configFromArray(config);
    } else if (isObject(input)) {
        configFromObject(config);
    } else if (isNumber(input)) {
        // from milliseconds
        config._d = new Date(input);
    } else {
        hooks.createFromInputFallback(config);
    }
}

function createLocalOrUTC (input, format, locale, strict, isUTC) {
    var c = {};

    if (locale === true || locale === false) {
        strict = locale;
        locale = undefined;
    }

    if ((isObject(input) && isObjectEmpty(input)) ||
            (isArray(input) && input.length === 0)) {
        input = undefined;
    }
    // object construction must be done this way.
    // https://github.com/moment/moment/issues/1423
    c._isAMomentObject = true;
    c._useUTC = c._isUTC = isUTC;
    c._l = locale;
    c._i = input;
    c._f = format;
    c._strict = strict;

    return createFromConfig(c);
}

function createLocal (input, format, locale, strict) {
    return createLocalOrUTC(input, format, locale, strict, false);
}

var prototypeMin = deprecate(
    'moment().min is deprecated, use moment.max instead. http://momentjs.com/guides/#/warnings/min-max/',
    function () {
        var other = createLocal.apply(null, arguments);
        if (this.isValid() && other.isValid()) {
            return other < this ? this : other;
        } else {
            return createInvalid();
        }
    }
);

var prototypeMax = deprecate(
    'moment().max is deprecated, use moment.min instead. http://momentjs.com/guides/#/warnings/min-max/',
    function () {
        var other = createLocal.apply(null, arguments);
        if (this.isValid() && other.isValid()) {
            return other > this ? this : other;
        } else {
            return createInvalid();
        }
    }
);

// Pick a moment m from moments so that m[fn](other) is true for all
// other. This relies on the function fn to be transitive.
//
// moments should either be an array of moment objects or an array, whose
// first element is an array of moment objects.
function pickBy(fn, moments) {
    var res, i;
    if (moments.length === 1 && isArray(moments[0])) {
        moments = moments[0];
    }
    if (!moments.length) {
        return createLocal();
    }
    res = moments[0];
    for (i = 1; i < moments.length; ++i) {
        if (!moments[i].isValid() || moments[i][fn](res)) {
            res = moments[i];
        }
    }
    return res;
}

// TODO: Use [].sort instead?
function min () {
    var args = [].slice.call(arguments, 0);

    return pickBy('isBefore', args);
}

function max () {
    var args = [].slice.call(arguments, 0);

    return pickBy('isAfter', args);
}

var now = function () {
    return Date.now ? Date.now() : +(new Date());
};

var ordering = ['year', 'quarter', 'month', 'week', 'day', 'hour', 'minute', 'second', 'millisecond'];

function isDurationValid(m) {
    for (var key in m) {
        if (!(ordering.indexOf(key) !== -1 && (m[key] == null || !isNaN(m[key])))) {
            return false;
        }
    }

    var unitHasDecimal = false;
    for (var i = 0; i < ordering.length; ++i) {
        if (m[ordering[i]]) {
            if (unitHasDecimal) {
                return false; // only allow non-integers for smallest unit
            }
            if (parseFloat(m[ordering[i]]) !== toInt(m[ordering[i]])) {
                unitHasDecimal = true;
            }
        }
    }

    return true;
}

function isValid$1() {
    return this._isValid;
}

function createInvalid$1() {
    return createDuration(NaN);
}

function Duration (duration) {
    var normalizedInput = normalizeObjectUnits(duration),
        years = normalizedInput.year || 0,
        quarters = normalizedInput.quarter || 0,
        months = normalizedInput.month || 0,
        weeks = normalizedInput.week || 0,
        days = normalizedInput.day || 0,
        hours = normalizedInput.hour || 0,
        minutes = normalizedInput.minute || 0,
        seconds = normalizedInput.second || 0,
        milliseconds = normalizedInput.millisecond || 0;

    this._isValid = isDurationValid(normalizedInput);

    // representation for dateAddRemove
    this._milliseconds = +milliseconds +
        seconds * 1e3 + // 1000
        minutes * 6e4 + // 1000 * 60
        hours * 1000 * 60 * 60; //using 1000 * 60 * 60 instead of 36e5 to avoid floating point rounding errors https://github.com/moment/moment/issues/2978
    // Because of dateAddRemove treats 24 hours as different from a
    // day when working around DST, we need to store them separately
    this._days = +days +
        weeks * 7;
    // It is impossible translate months into days without knowing
    // which months you are are talking about, so we have to store
    // it separately.
    this._months = +months +
        quarters * 3 +
        years * 12;

    this._data = {};

    this._locale = getLocale();

    this._bubble();
}

function isDuration (obj) {
    return obj instanceof Duration;
}

function absRound (number) {
    if (number < 0) {
        return Math.round(-1 * number) * -1;
    } else {
        return Math.round(number);
    }
}

// FORMATTING

function offset (token, separator) {
    addFormatToken(token, 0, 0, function () {
        var offset = this.utcOffset();
        var sign = '+';
        if (offset < 0) {
            offset = -offset;
            sign = '-';
        }
        return sign + zeroFill(~~(offset / 60), 2) + separator + zeroFill(~~(offset) % 60, 2);
    });
}

offset('Z', ':');
offset('ZZ', '');

// PARSING

addRegexToken('Z',  matchShortOffset);
addRegexToken('ZZ', matchShortOffset);
addParseToken(['Z', 'ZZ'], function (input, array, config) {
    config._useUTC = true;
    config._tzm = offsetFromString(matchShortOffset, input);
});

// HELPERS

// timezone chunker
// '+10:00' > ['10',  '00']
// '-1530'  > ['-15', '30']
var chunkOffset = /([\+\-]|\d\d)/gi;

function offsetFromString(matcher, string) {
    var matches = (string || '').match(matcher);

    if (matches === null) {
        return null;
    }

    var chunk   = matches[matches.length - 1] || [];
    var parts   = (chunk + '').match(chunkOffset) || ['-', 0, 0];
    var minutes = +(parts[1] * 60) + toInt(parts[2]);

    return minutes === 0 ?
      0 :
      parts[0] === '+' ? minutes : -minutes;
}

// Return a moment from input, that is local/utc/zone equivalent to model.
function cloneWithOffset(input, model) {
    var res, diff;
    if (model._isUTC) {
        res = model.clone();
        diff = (isMoment(input) || isDate(input) ? input.valueOf() : createLocal(input).valueOf()) - res.valueOf();
        // Use low-level api, because this fn is low-level api.
        res._d.setTime(res._d.valueOf() + diff);
        hooks.updateOffset(res, false);
        return res;
    } else {
        return createLocal(input).local();
    }
}

function getDateOffset (m) {
    // On Firefox.24 Date#getTimezoneOffset returns a floating point.
    // https://github.com/moment/moment/pull/1871
    return -Math.round(m._d.getTimezoneOffset() / 15) * 15;
}

// HOOKS

// This function will be called whenever a moment is mutated.
// It is intended to keep the offset in sync with the timezone.
hooks.updateOffset = function () {};

// MOMENTS

// keepLocalTime = true means only change the timezone, without
// affecting the local hour. So 5:31:26 +0300 --[utcOffset(2, true)]-->
// 5:31:26 +0200 It is possible that 5:31:26 doesn't exist with offset
// +0200, so we adjust the time as needed, to be valid.
//
// Keeping the time actually adds/subtracts (one hour)
// from the actual represented time. That is why we call updateOffset
// a second time. In case it wants us to change the offset again
// _changeInProgress == true case, then we have to adjust, because
// there is no such time in the given timezone.
function getSetOffset (input, keepLocalTime, keepMinutes) {
    var offset = this._offset || 0,
        localAdjust;
    if (!this.isValid()) {
        return input != null ? this : NaN;
    }
    if (input != null) {
        if (typeof input === 'string') {
            input = offsetFromString(matchShortOffset, input);
            if (input === null) {
                return this;
            }
        } else if (Math.abs(input) < 16 && !keepMinutes) {
            input = input * 60;
        }
        if (!this._isUTC && keepLocalTime) {
            localAdjust = getDateOffset(this);
        }
        this._offset = input;
        this._isUTC = true;
        if (localAdjust != null) {
            this.add(localAdjust, 'm');
        }
        if (offset !== input) {
            if (!keepLocalTime || this._changeInProgress) {
                addSubtract(this, createDuration(input - offset, 'm'), 1, false);
            } else if (!this._changeInProgress) {
                this._changeInProgress = true;
                hooks.updateOffset(this, true);
                this._changeInProgress = null;
            }
        }
        return this;
    } else {
        return this._isUTC ? offset : getDateOffset(this);
    }
}

function getSetZone (input, keepLocalTime) {
    if (input != null) {
        if (typeof input !== 'string') {
            input = -input;
        }

        this.utcOffset(input, keepLocalTime);

        return this;
    } else {
        return -this.utcOffset();
    }
}

function setOffsetToUTC (keepLocalTime) {
    return this.utcOffset(0, keepLocalTime);
}

function setOffsetToLocal (keepLocalTime) {
    if (this._isUTC) {
        this.utcOffset(0, keepLocalTime);
        this._isUTC = false;

        if (keepLocalTime) {
            this.subtract(getDateOffset(this), 'm');
        }
    }
    return this;
}

function setOffsetToParsedOffset () {
    if (this._tzm != null) {
        this.utcOffset(this._tzm, false, true);
    } else if (typeof this._i === 'string') {
        var tZone = offsetFromString(matchOffset, this._i);
        if (tZone != null) {
            this.utcOffset(tZone);
        }
        else {
            this.utcOffset(0, true);
        }
    }
    return this;
}

function hasAlignedHourOffset (input) {
    if (!this.isValid()) {
        return false;
    }
    input = input ? createLocal(input).utcOffset() : 0;

    return (this.utcOffset() - input) % 60 === 0;
}

function isDaylightSavingTime () {
    return (
        this.utcOffset() > this.clone().month(0).utcOffset() ||
        this.utcOffset() > this.clone().month(5).utcOffset()
    );
}

function isDaylightSavingTimeShifted () {
    if (!isUndefined(this._isDSTShifted)) {
        return this._isDSTShifted;
    }

    var c = {};

    copyConfig(c, this);
    c = prepareConfig(c);

    if (c._a) {
        var other = c._isUTC ? createUTC(c._a) : createLocal(c._a);
        this._isDSTShifted = this.isValid() &&
            compareArrays(c._a, other.toArray()) > 0;
    } else {
        this._isDSTShifted = false;
    }

    return this._isDSTShifted;
}

function isLocal () {
    return this.isValid() ? !this._isUTC : false;
}

function isUtcOffset () {
    return this.isValid() ? this._isUTC : false;
}

function isUtc () {
    return this.isValid() ? this._isUTC && this._offset === 0 : false;
}

// ASP.NET json date format regex
var aspNetRegex = /^(\-)?(?:(\d*)[. ])?(\d+)\:(\d+)(?:\:(\d+)(\.\d*)?)?$/;

// from http://docs.closure-library.googlecode.com/git/closure_goog_date_date.js.source.html
// somewhat more in line with 4.4.3.2 2004 spec, but allows decimal anywhere
// and further modified to allow for strings containing both week and day
var isoRegex = /^(-)?P(?:(-?[0-9,.]*)Y)?(?:(-?[0-9,.]*)M)?(?:(-?[0-9,.]*)W)?(?:(-?[0-9,.]*)D)?(?:T(?:(-?[0-9,.]*)H)?(?:(-?[0-9,.]*)M)?(?:(-?[0-9,.]*)S)?)?$/;

function createDuration (input, key) {
    var duration = input,
        // matching against regexp is expensive, do it on demand
        match = null,
        sign,
        ret,
        diffRes;

    if (isDuration(input)) {
        duration = {
            ms : input._milliseconds,
            d  : input._days,
            M  : input._months
        };
    } else if (isNumber(input)) {
        duration = {};
        if (key) {
            duration[key] = input;
        } else {
            duration.milliseconds = input;
        }
    } else if (!!(match = aspNetRegex.exec(input))) {
        sign = (match[1] === '-') ? -1 : 1;
        duration = {
            y  : 0,
            d  : toInt(match[DATE])                         * sign,
            h  : toInt(match[HOUR])                         * sign,
            m  : toInt(match[MINUTE])                       * sign,
            s  : toInt(match[SECOND])                       * sign,
            ms : toInt(absRound(match[MILLISECOND] * 1000)) * sign // the millisecond decimal point is included in the match
        };
    } else if (!!(match = isoRegex.exec(input))) {
        sign = (match[1] === '-') ? -1 : 1;
        duration = {
            y : parseIso(match[2], sign),
            M : parseIso(match[3], sign),
            w : parseIso(match[4], sign),
            d : parseIso(match[5], sign),
            h : parseIso(match[6], sign),
            m : parseIso(match[7], sign),
            s : parseIso(match[8], sign)
        };
    } else if (duration == null) {// checks for null or undefined
        duration = {};
    } else if (typeof duration === 'object' && ('from' in duration || 'to' in duration)) {
        diffRes = momentsDifference(createLocal(duration.from), createLocal(duration.to));

        duration = {};
        duration.ms = diffRes.milliseconds;
        duration.M = diffRes.months;
    }

    ret = new Duration(duration);

    if (isDuration(input) && hasOwnProp(input, '_locale')) {
        ret._locale = input._locale;
    }

    return ret;
}

createDuration.fn = Duration.prototype;
createDuration.invalid = createInvalid$1;

function parseIso (inp, sign) {
    // We'd normally use ~~inp for this, but unfortunately it also
    // converts floats to ints.
    // inp may be undefined, so careful calling replace on it.
    var res = inp && parseFloat(inp.replace(',', '.'));
    // apply sign while we're at it
    return (isNaN(res) ? 0 : res) * sign;
}

function positiveMomentsDifference(base, other) {
    var res = {milliseconds: 0, months: 0};

    res.months = other.month() - base.month() +
        (other.year() - base.year()) * 12;
    if (base.clone().add(res.months, 'M').isAfter(other)) {
        --res.months;
    }

    res.milliseconds = +other - +(base.clone().add(res.months, 'M'));

    return res;
}

function momentsDifference(base, other) {
    var res;
    if (!(base.isValid() && other.isValid())) {
        return {milliseconds: 0, months: 0};
    }

    other = cloneWithOffset(other, base);
    if (base.isBefore(other)) {
        res = positiveMomentsDifference(base, other);
    } else {
        res = positiveMomentsDifference(other, base);
        res.milliseconds = -res.milliseconds;
        res.months = -res.months;
    }

    return res;
}

// TODO: remove 'name' arg after deprecation is removed
function createAdder(direction, name) {
    return function (val, period) {
        var dur, tmp;
        //invert the arguments, but complain about it
        if (period !== null && !isNaN(+period)) {
            deprecateSimple(name, 'moment().' + name  + '(period, number) is deprecated. Please use moment().' + name + '(number, period). ' +
            'See http://momentjs.com/guides/#/warnings/add-inverted-param/ for more info.');
            tmp = val; val = period; period = tmp;
        }

        val = typeof val === 'string' ? +val : val;
        dur = createDuration(val, period);
        addSubtract(this, dur, direction);
        return this;
    };
}

function addSubtract (mom, duration, isAdding, updateOffset) {
    var milliseconds = duration._milliseconds,
        days = absRound(duration._days),
        months = absRound(duration._months);

    if (!mom.isValid()) {
        // No op
        return;
    }

    updateOffset = updateOffset == null ? true : updateOffset;

    if (milliseconds) {
        mom._d.setTime(mom._d.valueOf() + milliseconds * isAdding);
    }
    if (days) {
        set$1(mom, 'Date', get(mom, 'Date') + days * isAdding);
    }
    if (months) {
        setMonth(mom, get(mom, 'Month') + months * isAdding);
    }
    if (updateOffset) {
        hooks.updateOffset(mom, days || months);
    }
}

var add      = createAdder(1, 'add');
var subtract = createAdder(-1, 'subtract');

function getCalendarFormat(myMoment, now) {
    var diff = myMoment.diff(now, 'days', true);
    return diff < -6 ? 'sameElse' :
            diff < -1 ? 'lastWeek' :
            diff < 0 ? 'lastDay' :
            diff < 1 ? 'sameDay' :
            diff < 2 ? 'nextDay' :
            diff < 7 ? 'nextWeek' : 'sameElse';
}

function calendar$1 (time, formats) {
    // We want to compare the start of today, vs this.
    // Getting start-of-today depends on whether we're local/utc/offset or not.
    var now = time || createLocal(),
        sod = cloneWithOffset(now, this).startOf('day'),
        format = hooks.calendarFormat(this, sod) || 'sameElse';

    var output = formats && (isFunction(formats[format]) ? formats[format].call(this, now) : formats[format]);

    return this.format(output || this.localeData().calendar(format, this, createLocal(now)));
}

function clone () {
    return new Moment(this);
}

function isAfter (input, units) {
    var localInput = isMoment(input) ? input : createLocal(input);
    if (!(this.isValid() && localInput.isValid())) {
        return false;
    }
    units = normalizeUnits(!isUndefined(units) ? units : 'millisecond');
    if (units === 'millisecond') {
        return this.valueOf() > localInput.valueOf();
    } else {
        return localInput.valueOf() < this.clone().startOf(units).valueOf();
    }
}

function isBefore (input, units) {
    var localInput = isMoment(input) ? input : createLocal(input);
    if (!(this.isValid() && localInput.isValid())) {
        return false;
    }
    units = normalizeUnits(!isUndefined(units) ? units : 'millisecond');
    if (units === 'millisecond') {
        return this.valueOf() < localInput.valueOf();
    } else {
        return this.clone().endOf(units).valueOf() < localInput.valueOf();
    }
}

function isBetween (from, to, units, inclusivity) {
    inclusivity = inclusivity || '()';
    return (inclusivity[0] === '(' ? this.isAfter(from, units) : !this.isBefore(from, units)) &&
        (inclusivity[1] === ')' ? this.isBefore(to, units) : !this.isAfter(to, units));
}

function isSame (input, units) {
    var localInput = isMoment(input) ? input : createLocal(input),
        inputMs;
    if (!(this.isValid() && localInput.isValid())) {
        return false;
    }
    units = normalizeUnits(units || 'millisecond');
    if (units === 'millisecond') {
        return this.valueOf() === localInput.valueOf();
    } else {
        inputMs = localInput.valueOf();
        return this.clone().startOf(units).valueOf() <= inputMs && inputMs <= this.clone().endOf(units).valueOf();
    }
}

function isSameOrAfter (input, units) {
    return this.isSame(input, units) || this.isAfter(input,units);
}

function isSameOrBefore (input, units) {
    return this.isSame(input, units) || this.isBefore(input,units);
}

function diff (input, units, asFloat) {
    var that,
        zoneDelta,
        delta, output;

    if (!this.isValid()) {
        return NaN;
    }

    that = cloneWithOffset(input, this);

    if (!that.isValid()) {
        return NaN;
    }

    zoneDelta = (that.utcOffset() - this.utcOffset()) * 6e4;

    units = normalizeUnits(units);

    if (units === 'year' || units === 'month' || units === 'quarter') {
        output = monthDiff(this, that);
        if (units === 'quarter') {
            output = output / 3;
        } else if (units === 'year') {
            output = output / 12;
        }
    } else {
        delta = this - that;
        output = units === 'second' ? delta / 1e3 : // 1000
            units === 'minute' ? delta / 6e4 : // 1000 * 60
            units === 'hour' ? delta / 36e5 : // 1000 * 60 * 60
            units === 'day' ? (delta - zoneDelta) / 864e5 : // 1000 * 60 * 60 * 24, negate dst
            units === 'week' ? (delta - zoneDelta) / 6048e5 : // 1000 * 60 * 60 * 24 * 7, negate dst
            delta;
    }
    return asFloat ? output : absFloor(output);
}

function monthDiff (a, b) {
    // difference in months
    var wholeMonthDiff = ((b.year() - a.year()) * 12) + (b.month() - a.month()),
        // b is in (anchor - 1 month, anchor + 1 month)
        anchor = a.clone().add(wholeMonthDiff, 'months'),
        anchor2, adjust;

    if (b - anchor < 0) {
        anchor2 = a.clone().add(wholeMonthDiff - 1, 'months');
        // linear across the month
        adjust = (b - anchor) / (anchor - anchor2);
    } else {
        anchor2 = a.clone().add(wholeMonthDiff + 1, 'months');
        // linear across the month
        adjust = (b - anchor) / (anchor2 - anchor);
    }

    //check for negative zero, return zero if negative zero
    return -(wholeMonthDiff + adjust) || 0;
}

hooks.defaultFormat = 'YYYY-MM-DDTHH:mm:ssZ';
hooks.defaultFormatUtc = 'YYYY-MM-DDTHH:mm:ss[Z]';

function toString () {
    return this.clone().locale('en').format('ddd MMM DD YYYY HH:mm:ss [GMT]ZZ');
}

function toISOString() {
    if (!this.isValid()) {
        return null;
    }
    var m = this.clone().utc();
    if (m.year() < 0 || m.year() > 9999) {
        return formatMoment(m, 'YYYYYY-MM-DD[T]HH:mm:ss.SSS[Z]');
    }
    if (isFunction(Date.prototype.toISOString)) {
        // native implementation is ~50x faster, use it when we can
        return this.toDate().toISOString();
    }
    return formatMoment(m, 'YYYY-MM-DD[T]HH:mm:ss.SSS[Z]');
}

/**
 * Return a human readable representation of a moment that can
 * also be evaluated to get a new moment which is the same
 *
 * @link https://nodejs.org/dist/latest/docs/api/util.html#util_custom_inspect_function_on_objects
 */
function inspect () {
    if (!this.isValid()) {
        return 'moment.invalid(/* ' + this._i + ' */)';
    }
    var func = 'moment';
    var zone = '';
    if (!this.isLocal()) {
        func = this.utcOffset() === 0 ? 'moment.utc' : 'moment.parseZone';
        zone = 'Z';
    }
    var prefix = '[' + func + '("]';
    var year = (0 <= this.year() && this.year() <= 9999) ? 'YYYY' : 'YYYYYY';
    var datetime = '-MM-DD[T]HH:mm:ss.SSS';
    var suffix = zone + '[")]';

    return this.format(prefix + year + datetime + suffix);
}

function format (inputString) {
    if (!inputString) {
        inputString = this.isUtc() ? hooks.defaultFormatUtc : hooks.defaultFormat;
    }
    var output = formatMoment(this, inputString);
    return this.localeData().postformat(output);
}

function from (time, withoutSuffix) {
    if (this.isValid() &&
            ((isMoment(time) && time.isValid()) ||
             createLocal(time).isValid())) {
        return createDuration({to: this, from: time}).locale(this.locale()).humanize(!withoutSuffix);
    } else {
        return this.localeData().invalidDate();
    }
}

function fromNow (withoutSuffix) {
    return this.from(createLocal(), withoutSuffix);
}

function to (time, withoutSuffix) {
    if (this.isValid() &&
            ((isMoment(time) && time.isValid()) ||
             createLocal(time).isValid())) {
        return createDuration({from: this, to: time}).locale(this.locale()).humanize(!withoutSuffix);
    } else {
        return this.localeData().invalidDate();
    }
}

function toNow (withoutSuffix) {
    return this.to(createLocal(), withoutSuffix);
}

// If passed a locale key, it will set the locale for this
// instance.  Otherwise, it will return the locale configuration
// variables for this instance.
function locale (key) {
    var newLocaleData;

    if (key === undefined) {
        return this._locale._abbr;
    } else {
        newLocaleData = getLocale(key);
        if (newLocaleData != null) {
            this._locale = newLocaleData;
        }
        return this;
    }
}

var lang = deprecate(
    'moment().lang() is deprecated. Instead, use moment().localeData() to get the language configuration. Use moment().locale() to change languages.',
    function (key) {
        if (key === undefined) {
            return this.localeData();
        } else {
            return this.locale(key);
        }
    }
);

function localeData () {
    return this._locale;
}

function startOf (units) {
    units = normalizeUnits(units);
    // the following switch intentionally omits break keywords
    // to utilize falling through the cases.
    switch (units) {
        case 'year':
            this.month(0);
            /* falls through */
        case 'quarter':
        case 'month':
            this.date(1);
            /* falls through */
        case 'week':
        case 'isoWeek':
        case 'day':
        case 'date':
            this.hours(0);
            /* falls through */
        case 'hour':
            this.minutes(0);
            /* falls through */
        case 'minute':
            this.seconds(0);
            /* falls through */
        case 'second':
            this.milliseconds(0);
    }

    // weeks are a special case
    if (units === 'week') {
        this.weekday(0);
    }
    if (units === 'isoWeek') {
        this.isoWeekday(1);
    }

    // quarters are also special
    if (units === 'quarter') {
        this.month(Math.floor(this.month() / 3) * 3);
    }

    return this;
}

function endOf (units) {
    units = normalizeUnits(units);
    if (units === undefined || units === 'millisecond') {
        return this;
    }

    // 'date' is an alias for 'day', so it should be considered as such.
    if (units === 'date') {
        units = 'day';
    }

    return this.startOf(units).add(1, (units === 'isoWeek' ? 'week' : units)).subtract(1, 'ms');
}

function valueOf () {
    return this._d.valueOf() - ((this._offset || 0) * 60000);
}

function unix () {
    return Math.floor(this.valueOf() / 1000);
}

function toDate () {
    return new Date(this.valueOf());
}

function toArray () {
    var m = this;
    return [m.year(), m.month(), m.date(), m.hour(), m.minute(), m.second(), m.millisecond()];
}

function toObject () {
    var m = this;
    return {
        years: m.year(),
        months: m.month(),
        date: m.date(),
        hours: m.hours(),
        minutes: m.minutes(),
        seconds: m.seconds(),
        milliseconds: m.milliseconds()
    };
}

function toJSON () {
    // new Date(NaN).toJSON() === null
    return this.isValid() ? this.toISOString() : null;
}

function isValid$2 () {
    return isValid(this);
}

function parsingFlags () {
    return extend({}, getParsingFlags(this));
}

function invalidAt () {
    return getParsingFlags(this).overflow;
}

function creationData() {
    return {
        input: this._i,
        format: this._f,
        locale: this._locale,
        isUTC: this._isUTC,
        strict: this._strict
    };
}

// FORMATTING

addFormatToken(0, ['gg', 2], 0, function () {
    return this.weekYear() % 100;
});

addFormatToken(0, ['GG', 2], 0, function () {
    return this.isoWeekYear() % 100;
});

function addWeekYearFormatToken (token, getter) {
    addFormatToken(0, [token, token.length], 0, getter);
}

addWeekYearFormatToken('gggg',     'weekYear');
addWeekYearFormatToken('ggggg',    'weekYear');
addWeekYearFormatToken('GGGG',  'isoWeekYear');
addWeekYearFormatToken('GGGGG', 'isoWeekYear');

// ALIASES

addUnitAlias('weekYear', 'gg');
addUnitAlias('isoWeekYear', 'GG');

// PRIORITY

addUnitPriority('weekYear', 1);
addUnitPriority('isoWeekYear', 1);


// PARSING

addRegexToken('G',      matchSigned);
addRegexToken('g',      matchSigned);
addRegexToken('GG',     match1to2, match2);
addRegexToken('gg',     match1to2, match2);
addRegexToken('GGGG',   match1to4, match4);
addRegexToken('gggg',   match1to4, match4);
addRegexToken('GGGGG',  match1to6, match6);
addRegexToken('ggggg',  match1to6, match6);

addWeekParseToken(['gggg', 'ggggg', 'GGGG', 'GGGGG'], function (input, week, config, token) {
    week[token.substr(0, 2)] = toInt(input);
});

addWeekParseToken(['gg', 'GG'], function (input, week, config, token) {
    week[token] = hooks.parseTwoDigitYear(input);
});

// MOMENTS

function getSetWeekYear (input) {
    return getSetWeekYearHelper.call(this,
            input,
            this.week(),
            this.weekday(),
            this.localeData()._week.dow,
            this.localeData()._week.doy);
}

function getSetISOWeekYear (input) {
    return getSetWeekYearHelper.call(this,
            input, this.isoWeek(), this.isoWeekday(), 1, 4);
}

function getISOWeeksInYear () {
    return weeksInYear(this.year(), 1, 4);
}

function getWeeksInYear () {
    var weekInfo = this.localeData()._week;
    return weeksInYear(this.year(), weekInfo.dow, weekInfo.doy);
}

function getSetWeekYearHelper(input, week, weekday, dow, doy) {
    var weeksTarget;
    if (input == null) {
        return weekOfYear(this, dow, doy).year;
    } else {
        weeksTarget = weeksInYear(input, dow, doy);
        if (week > weeksTarget) {
            week = weeksTarget;
        }
        return setWeekAll.call(this, input, week, weekday, dow, doy);
    }
}

function setWeekAll(weekYear, week, weekday, dow, doy) {
    var dayOfYearData = dayOfYearFromWeeks(weekYear, week, weekday, dow, doy),
        date = createUTCDate(dayOfYearData.year, 0, dayOfYearData.dayOfYear);

    this.year(date.getUTCFullYear());
    this.month(date.getUTCMonth());
    this.date(date.getUTCDate());
    return this;
}

// FORMATTING

addFormatToken('Q', 0, 'Qo', 'quarter');

// ALIASES

addUnitAlias('quarter', 'Q');

// PRIORITY

addUnitPriority('quarter', 7);

// PARSING

addRegexToken('Q', match1);
addParseToken('Q', function (input, array) {
    array[MONTH] = (toInt(input) - 1) * 3;
});

// MOMENTS

function getSetQuarter (input) {
    return input == null ? Math.ceil((this.month() + 1) / 3) : this.month((input - 1) * 3 + this.month() % 3);
}

// FORMATTING

addFormatToken('D', ['DD', 2], 'Do', 'date');

// ALIASES

addUnitAlias('date', 'D');

// PRIOROITY
addUnitPriority('date', 9);

// PARSING

addRegexToken('D',  match1to2);
addRegexToken('DD', match1to2, match2);
addRegexToken('Do', function (isStrict, locale) {
    // TODO: Remove "ordinalParse" fallback in next major release.
    return isStrict ?
      (locale._dayOfMonthOrdinalParse || locale._ordinalParse) :
      locale._dayOfMonthOrdinalParseLenient;
});

addParseToken(['D', 'DD'], DATE);
addParseToken('Do', function (input, array) {
    array[DATE] = toInt(input.match(match1to2)[0], 10);
});

// MOMENTS

var getSetDayOfMonth = makeGetSet('Date', true);

// FORMATTING

addFormatToken('DDD', ['DDDD', 3], 'DDDo', 'dayOfYear');

// ALIASES

addUnitAlias('dayOfYear', 'DDD');

// PRIORITY
addUnitPriority('dayOfYear', 4);

// PARSING

addRegexToken('DDD',  match1to3);
addRegexToken('DDDD', match3);
addParseToken(['DDD', 'DDDD'], function (input, array, config) {
    config._dayOfYear = toInt(input);
});

// HELPERS

// MOMENTS

function getSetDayOfYear (input) {
    var dayOfYear = Math.round((this.clone().startOf('day') - this.clone().startOf('year')) / 864e5) + 1;
    return input == null ? dayOfYear : this.add((input - dayOfYear), 'd');
}

// FORMATTING

addFormatToken('m', ['mm', 2], 0, 'minute');

// ALIASES

addUnitAlias('minute', 'm');

// PRIORITY

addUnitPriority('minute', 14);

// PARSING

addRegexToken('m',  match1to2);
addRegexToken('mm', match1to2, match2);
addParseToken(['m', 'mm'], MINUTE);

// MOMENTS

var getSetMinute = makeGetSet('Minutes', false);

// FORMATTING

addFormatToken('s', ['ss', 2], 0, 'second');

// ALIASES

addUnitAlias('second', 's');

// PRIORITY

addUnitPriority('second', 15);

// PARSING

addRegexToken('s',  match1to2);
addRegexToken('ss', match1to2, match2);
addParseToken(['s', 'ss'], SECOND);

// MOMENTS

var getSetSecond = makeGetSet('Seconds', false);

// FORMATTING

addFormatToken('S', 0, 0, function () {
    return ~~(this.millisecond() / 100);
});

addFormatToken(0, ['SS', 2], 0, function () {
    return ~~(this.millisecond() / 10);
});

addFormatToken(0, ['SSS', 3], 0, 'millisecond');
addFormatToken(0, ['SSSS', 4], 0, function () {
    return this.millisecond() * 10;
});
addFormatToken(0, ['SSSSS', 5], 0, function () {
    return this.millisecond() * 100;
});
addFormatToken(0, ['SSSSSS', 6], 0, function () {
    return this.millisecond() * 1000;
});
addFormatToken(0, ['SSSSSSS', 7], 0, function () {
    return this.millisecond() * 10000;
});
addFormatToken(0, ['SSSSSSSS', 8], 0, function () {
    return this.millisecond() * 100000;
});
addFormatToken(0, ['SSSSSSSSS', 9], 0, function () {
    return this.millisecond() * 1000000;
});


// ALIASES

addUnitAlias('millisecond', 'ms');

// PRIORITY

addUnitPriority('millisecond', 16);

// PARSING

addRegexToken('S',    match1to3, match1);
addRegexToken('SS',   match1to3, match2);
addRegexToken('SSS',  match1to3, match3);

var token;
for (token = 'SSSS'; token.length <= 9; token += 'S') {
    addRegexToken(token, matchUnsigned);
}

function parseMs(input, array) {
    array[MILLISECOND] = toInt(('0.' + input) * 1000);
}

for (token = 'S'; token.length <= 9; token += 'S') {
    addParseToken(token, parseMs);
}
// MOMENTS

var getSetMillisecond = makeGetSet('Milliseconds', false);

// FORMATTING

addFormatToken('z',  0, 0, 'zoneAbbr');
addFormatToken('zz', 0, 0, 'zoneName');

// MOMENTS

function getZoneAbbr () {
    return this._isUTC ? 'UTC' : '';
}

function getZoneName () {
    return this._isUTC ? 'Coordinated Universal Time' : '';
}

var proto = Moment.prototype;

proto.add               = add;
proto.calendar          = calendar$1;
proto.clone             = clone;
proto.diff              = diff;
proto.endOf             = endOf;
proto.format            = format;
proto.from              = from;
proto.fromNow           = fromNow;
proto.to                = to;
proto.toNow             = toNow;
proto.get               = stringGet;
proto.invalidAt         = invalidAt;
proto.isAfter           = isAfter;
proto.isBefore          = isBefore;
proto.isBetween         = isBetween;
proto.isSame            = isSame;
proto.isSameOrAfter     = isSameOrAfter;
proto.isSameOrBefore    = isSameOrBefore;
proto.isValid           = isValid$2;
proto.lang              = lang;
proto.locale            = locale;
proto.localeData        = localeData;
proto.max               = prototypeMax;
proto.min               = prototypeMin;
proto.parsingFlags      = parsingFlags;
proto.set               = stringSet;
proto.startOf           = startOf;
proto.subtract          = subtract;
proto.toArray           = toArray;
proto.toObject          = toObject;
proto.toDate            = toDate;
proto.toISOString       = toISOString;
proto.inspect           = inspect;
proto.toJSON            = toJSON;
proto.toString          = toString;
proto.unix              = unix;
proto.valueOf           = valueOf;
proto.creationData      = creationData;

// Year
proto.year       = getSetYear;
proto.isLeapYear = getIsLeapYear;

// Week Year
proto.weekYear    = getSetWeekYear;
proto.isoWeekYear = getSetISOWeekYear;

// Quarter
proto.quarter = proto.quarters = getSetQuarter;

// Month
proto.month       = getSetMonth;
proto.daysInMonth = getDaysInMonth;

// Week
proto.week           = proto.weeks        = getSetWeek;
proto.isoWeek        = proto.isoWeeks     = getSetISOWeek;
proto.weeksInYear    = getWeeksInYear;
proto.isoWeeksInYear = getISOWeeksInYear;

// Day
proto.date       = getSetDayOfMonth;
proto.day        = proto.days             = getSetDayOfWeek;
proto.weekday    = getSetLocaleDayOfWeek;
proto.isoWeekday = getSetISODayOfWeek;
proto.dayOfYear  = getSetDayOfYear;

// Hour
proto.hour = proto.hours = getSetHour;

// Minute
proto.minute = proto.minutes = getSetMinute;

// Second
proto.second = proto.seconds = getSetSecond;

// Millisecond
proto.millisecond = proto.milliseconds = getSetMillisecond;

// Offset
proto.utcOffset            = getSetOffset;
proto.utc                  = setOffsetToUTC;
proto.local                = setOffsetToLocal;
proto.parseZone            = setOffsetToParsedOffset;
proto.hasAlignedHourOffset = hasAlignedHourOffset;
proto.isDST                = isDaylightSavingTime;
proto.isLocal              = isLocal;
proto.isUtcOffset          = isUtcOffset;
proto.isUtc                = isUtc;
proto.isUTC                = isUtc;

// Timezone
proto.zoneAbbr = getZoneAbbr;
proto.zoneName = getZoneName;

// Deprecations
proto.dates  = deprecate('dates accessor is deprecated. Use date instead.', getSetDayOfMonth);
proto.months = deprecate('months accessor is deprecated. Use month instead', getSetMonth);
proto.years  = deprecate('years accessor is deprecated. Use year instead', getSetYear);
proto.zone   = deprecate('moment().zone is deprecated, use moment().utcOffset instead. http://momentjs.com/guides/#/warnings/zone/', getSetZone);
proto.isDSTShifted = deprecate('isDSTShifted is deprecated. See http://momentjs.com/guides/#/warnings/dst-shifted/ for more information', isDaylightSavingTimeShifted);

function createUnix (input) {
    return createLocal(input * 1000);
}

function createInZone () {
    return createLocal.apply(null, arguments).parseZone();
}

function preParsePostFormat (string) {
    return string;
}

var proto$1 = Locale.prototype;

proto$1.calendar        = calendar;
proto$1.longDateFormat  = longDateFormat;
proto$1.invalidDate     = invalidDate;
proto$1.ordinal         = ordinal;
proto$1.preparse        = preParsePostFormat;
proto$1.postformat      = preParsePostFormat;
proto$1.relativeTime    = relativeTime;
proto$1.pastFuture      = pastFuture;
proto$1.set             = set;

// Month
proto$1.months            =        localeMonths;
proto$1.monthsShort       =        localeMonthsShort;
proto$1.monthsParse       =        localeMonthsParse;
proto$1.monthsRegex       = monthsRegex;
proto$1.monthsShortRegex  = monthsShortRegex;

// Week
proto$1.week = localeWeek;
proto$1.firstDayOfYear = localeFirstDayOfYear;
proto$1.firstDayOfWeek = localeFirstDayOfWeek;

// Day of Week
proto$1.weekdays       =        localeWeekdays;
proto$1.weekdaysMin    =        localeWeekdaysMin;
proto$1.weekdaysShort  =        localeWeekdaysShort;
proto$1.weekdaysParse  =        localeWeekdaysParse;

proto$1.weekdaysRegex       =        weekdaysRegex;
proto$1.weekdaysShortRegex  =        weekdaysShortRegex;
proto$1.weekdaysMinRegex    =        weekdaysMinRegex;

// Hours
proto$1.isPM = localeIsPM;
proto$1.meridiem = localeMeridiem;

function get$1 (format, index, field, setter) {
    var locale = getLocale();
    var utc = createUTC().set(setter, index);
    return locale[field](utc, format);
}

function listMonthsImpl (format, index, field) {
    if (isNumber(format)) {
        index = format;
        format = undefined;
    }

    format = format || '';

    if (index != null) {
        return get$1(format, index, field, 'month');
    }

    var i;
    var out = [];
    for (i = 0; i < 12; i++) {
        out[i] = get$1(format, i, field, 'month');
    }
    return out;
}

// ()
// (5)
// (fmt, 5)
// (fmt)
// (true)
// (true, 5)
// (true, fmt, 5)
// (true, fmt)
function listWeekdaysImpl (localeSorted, format, index, field) {
    if (typeof localeSorted === 'boolean') {
        if (isNumber(format)) {
            index = format;
            format = undefined;
        }

        format = format || '';
    } else {
        format = localeSorted;
        index = format;
        localeSorted = false;

        if (isNumber(format)) {
            index = format;
            format = undefined;
        }

        format = format || '';
    }

    var locale = getLocale(),
        shift = localeSorted ? locale._week.dow : 0;

    if (index != null) {
        return get$1(format, (index + shift) % 7, field, 'day');
    }

    var i;
    var out = [];
    for (i = 0; i < 7; i++) {
        out[i] = get$1(format, (i + shift) % 7, field, 'day');
    }
    return out;
}

function listMonths (format, index) {
    return listMonthsImpl(format, index, 'months');
}

function listMonthsShort (format, index) {
    return listMonthsImpl(format, index, 'monthsShort');
}

function listWeekdays (localeSorted, format, index) {
    return listWeekdaysImpl(localeSorted, format, index, 'weekdays');
}

function listWeekdaysShort (localeSorted, format, index) {
    return listWeekdaysImpl(localeSorted, format, index, 'weekdaysShort');
}

function listWeekdaysMin (localeSorted, format, index) {
    return listWeekdaysImpl(localeSorted, format, index, 'weekdaysMin');
}

getSetGlobalLocale('en', {
    dayOfMonthOrdinalParse: /\d{1,2}(th|st|nd|rd)/,
    ordinal : function (number) {
        var b = number % 10,
            output = (toInt(number % 100 / 10) === 1) ? 'th' :
            (b === 1) ? 'st' :
            (b === 2) ? 'nd' :
            (b === 3) ? 'rd' : 'th';
        return number + output;
    }
});

// Side effect imports
hooks.lang = deprecate('moment.lang is deprecated. Use moment.locale instead.', getSetGlobalLocale);
hooks.langData = deprecate('moment.langData is deprecated. Use moment.localeData instead.', getLocale);

var mathAbs = Math.abs;

function abs () {
    var data           = this._data;

    this._milliseconds = mathAbs(this._milliseconds);
    this._days         = mathAbs(this._days);
    this._months       = mathAbs(this._months);

    data.milliseconds  = mathAbs(data.milliseconds);
    data.seconds       = mathAbs(data.seconds);
    data.minutes       = mathAbs(data.minutes);
    data.hours         = mathAbs(data.hours);
    data.months        = mathAbs(data.months);
    data.years         = mathAbs(data.years);

    return this;
}

function addSubtract$1 (duration, input, value, direction) {
    var other = createDuration(input, value);

    duration._milliseconds += direction * other._milliseconds;
    duration._days         += direction * other._days;
    duration._months       += direction * other._months;

    return duration._bubble();
}

// supports only 2.0-style add(1, 's') or add(duration)
function add$1 (input, value) {
    return addSubtract$1(this, input, value, 1);
}

// supports only 2.0-style subtract(1, 's') or subtract(duration)
function subtract$1 (input, value) {
    return addSubtract$1(this, input, value, -1);
}

function absCeil (number) {
    if (number < 0) {
        return Math.floor(number);
    } else {
        return Math.ceil(number);
    }
}

function bubble () {
    var milliseconds = this._milliseconds;
    var days         = this._days;
    var months       = this._months;
    var data         = this._data;
    var seconds, minutes, hours, years, monthsFromDays;

    // if we have a mix of positive and negative values, bubble down first
    // check: https://github.com/moment/moment/issues/2166
    if (!((milliseconds >= 0 && days >= 0 && months >= 0) ||
            (milliseconds <= 0 && days <= 0 && months <= 0))) {
        milliseconds += absCeil(monthsToDays(months) + days) * 864e5;
        days = 0;
        months = 0;
    }

    // The following code bubbles up values, see the tests for
    // examples of what that means.
    data.milliseconds = milliseconds % 1000;

    seconds           = absFloor(milliseconds / 1000);
    data.seconds      = seconds % 60;

    minutes           = absFloor(seconds / 60);
    data.minutes      = minutes % 60;

    hours             = absFloor(minutes / 60);
    data.hours        = hours % 24;

    days += absFloor(hours / 24);

    // convert days to months
    monthsFromDays = absFloor(daysToMonths(days));
    months += monthsFromDays;
    days -= absCeil(monthsToDays(monthsFromDays));

    // 12 months -> 1 year
    years = absFloor(months / 12);
    months %= 12;

    data.days   = days;
    data.months = months;
    data.years  = years;

    return this;
}

function daysToMonths (days) {
    // 400 years have 146097 days (taking into account leap year rules)
    // 400 years have 12 months === 4800
    return days * 4800 / 146097;
}

function monthsToDays (months) {
    // the reverse of daysToMonths
    return months * 146097 / 4800;
}

function as (units) {
    if (!this.isValid()) {
        return NaN;
    }
    var days;
    var months;
    var milliseconds = this._milliseconds;

    units = normalizeUnits(units);

    if (units === 'month' || units === 'year') {
        days   = this._days   + milliseconds / 864e5;
        months = this._months + daysToMonths(days);
        return units === 'month' ? months : months / 12;
    } else {
        // handle milliseconds separately because of floating point math errors (issue #1867)
        days = this._days + Math.round(monthsToDays(this._months));
        switch (units) {
            case 'week'   : return days / 7     + milliseconds / 6048e5;
            case 'day'    : return days         + milliseconds / 864e5;
            case 'hour'   : return days * 24    + milliseconds / 36e5;
            case 'minute' : return days * 1440  + milliseconds / 6e4;
            case 'second' : return days * 86400 + milliseconds / 1000;
            // Math.floor prevents floating point math errors here
            case 'millisecond': return Math.floor(days * 864e5) + milliseconds;
            default: throw new Error('Unknown unit ' + units);
        }
    }
}

// TODO: Use this.as('ms')?
function valueOf$1 () {
    if (!this.isValid()) {
        return NaN;
    }
    return (
        this._milliseconds +
        this._days * 864e5 +
        (this._months % 12) * 2592e6 +
        toInt(this._months / 12) * 31536e6
    );
}

function makeAs (alias) {
    return function () {
        return this.as(alias);
    };
}

var asMilliseconds = makeAs('ms');
var asSeconds      = makeAs('s');
var asMinutes      = makeAs('m');
var asHours        = makeAs('h');
var asDays         = makeAs('d');
var asWeeks        = makeAs('w');
var asMonths       = makeAs('M');
var asYears        = makeAs('y');

function get$2 (units) {
    units = normalizeUnits(units);
    return this.isValid() ? this[units + 's']() : NaN;
}

function makeGetter(name) {
    return function () {
        return this.isValid() ? this._data[name] : NaN;
    };
}

var milliseconds = makeGetter('milliseconds');
var seconds      = makeGetter('seconds');
var minutes      = makeGetter('minutes');
var hours        = makeGetter('hours');
var days         = makeGetter('days');
var months       = makeGetter('months');
var years        = makeGetter('years');

function weeks () {
    return absFloor(this.days() / 7);
}

var round = Math.round;
var thresholds = {
    ss: 44,         // a few seconds to seconds
    s : 45,         // seconds to minute
    m : 45,         // minutes to hour
    h : 22,         // hours to day
    d : 26,         // days to month
    M : 11          // months to year
};

// helper function for moment.fn.from, moment.fn.fromNow, and moment.duration.fn.humanize
function substituteTimeAgo(string, number, withoutSuffix, isFuture, locale) {
    return locale.relativeTime(number || 1, !!withoutSuffix, string, isFuture);
}

function relativeTime$1 (posNegDuration, withoutSuffix, locale) {
    var duration = createDuration(posNegDuration).abs();
    var seconds  = round(duration.as('s'));
    var minutes  = round(duration.as('m'));
    var hours    = round(duration.as('h'));
    var days     = round(duration.as('d'));
    var months   = round(duration.as('M'));
    var years    = round(duration.as('y'));

    var a = seconds <= thresholds.ss && ['s', seconds]  ||
            seconds < thresholds.s   && ['ss', seconds] ||
            minutes <= 1             && ['m']           ||
            minutes < thresholds.m   && ['mm', minutes] ||
            hours   <= 1             && ['h']           ||
            hours   < thresholds.h   && ['hh', hours]   ||
            days    <= 1             && ['d']           ||
            days    < thresholds.d   && ['dd', days]    ||
            months  <= 1             && ['M']           ||
            months  < thresholds.M   && ['MM', months]  ||
            years   <= 1             && ['y']           || ['yy', years];

    a[2] = withoutSuffix;
    a[3] = +posNegDuration > 0;
    a[4] = locale;
    return substituteTimeAgo.apply(null, a);
}

// This function allows you to set the rounding function for relative time strings
function getSetRelativeTimeRounding (roundingFunction) {
    if (roundingFunction === undefined) {
        return round;
    }
    if (typeof(roundingFunction) === 'function') {
        round = roundingFunction;
        return true;
    }
    return false;
}

// This function allows you to set a threshold for relative time strings
function getSetRelativeTimeThreshold (threshold, limit) {
    if (thresholds[threshold] === undefined) {
        return false;
    }
    if (limit === undefined) {
        return thresholds[threshold];
    }
    thresholds[threshold] = limit;
    if (threshold === 's') {
        thresholds.ss = limit - 1;
    }
    return true;
}

function humanize (withSuffix) {
    if (!this.isValid()) {
        return this.localeData().invalidDate();
    }

    var locale = this.localeData();
    var output = relativeTime$1(this, !withSuffix, locale);

    if (withSuffix) {
        output = locale.pastFuture(+this, output);
    }

    return locale.postformat(output);
}

var abs$1 = Math.abs;

function toISOString$1() {
    // for ISO strings we do not use the normal bubbling rules:
    //  * milliseconds bubble up until they become hours
    //  * days do not bubble at all
    //  * months bubble up until they become years
    // This is because there is no context-free conversion between hours and days
    // (think of clock changes)
    // and also not between days and months (28-31 days per month)
    if (!this.isValid()) {
        return this.localeData().invalidDate();
    }

    var seconds = abs$1(this._milliseconds) / 1000;
    var days         = abs$1(this._days);
    var months       = abs$1(this._months);
    var minutes, hours, years;

    // 3600 seconds -> 60 minutes -> 1 hour
    minutes           = absFloor(seconds / 60);
    hours             = absFloor(minutes / 60);
    seconds %= 60;
    minutes %= 60;

    // 12 months -> 1 year
    years  = absFloor(months / 12);
    months %= 12;


    // inspired by https://github.com/dordille/moment-isoduration/blob/master/moment.isoduration.js
    var Y = years;
    var M = months;
    var D = days;
    var h = hours;
    var m = minutes;
    var s = seconds;
    var total = this.asSeconds();

    if (!total) {
        // this is the same as C#'s (Noda) and python (isodate)...
        // but not other JS (goog.date)
        return 'P0D';
    }

    return (total < 0 ? '-' : '') +
        'P' +
        (Y ? Y + 'Y' : '') +
        (M ? M + 'M' : '') +
        (D ? D + 'D' : '') +
        ((h || m || s) ? 'T' : '') +
        (h ? h + 'H' : '') +
        (m ? m + 'M' : '') +
        (s ? s + 'S' : '');
}

var proto$2 = Duration.prototype;

proto$2.isValid        = isValid$1;
proto$2.abs            = abs;
proto$2.add            = add$1;
proto$2.subtract       = subtract$1;
proto$2.as             = as;
proto$2.asMilliseconds = asMilliseconds;
proto$2.asSeconds      = asSeconds;
proto$2.asMinutes      = asMinutes;
proto$2.asHours        = asHours;
proto$2.asDays         = asDays;
proto$2.asWeeks        = asWeeks;
proto$2.asMonths       = asMonths;
proto$2.asYears        = asYears;
proto$2.valueOf        = valueOf$1;
proto$2._bubble        = bubble;
proto$2.get            = get$2;
proto$2.milliseconds   = milliseconds;
proto$2.seconds        = seconds;
proto$2.minutes        = minutes;
proto$2.hours          = hours;
proto$2.days           = days;
proto$2.weeks          = weeks;
proto$2.months         = months;
proto$2.years          = years;
proto$2.humanize       = humanize;
proto$2.toISOString    = toISOString$1;
proto$2.toString       = toISOString$1;
proto$2.toJSON         = toISOString$1;
proto$2.locale         = locale;
proto$2.localeData     = localeData;

// Deprecations
proto$2.toIsoString = deprecate('toIsoString() is deprecated. Please use toISOString() instead (notice the capitals)', toISOString$1);
proto$2.lang = lang;

// Side effect imports

// FORMATTING

addFormatToken('X', 0, 0, 'unix');
addFormatToken('x', 0, 0, 'valueOf');

// PARSING

addRegexToken('x', matchSigned);
addRegexToken('X', matchTimestamp);
addParseToken('X', function (input, array, config) {
    config._d = new Date(parseFloat(input, 10) * 1000);
});
addParseToken('x', function (input, array, config) {
    config._d = new Date(toInt(input));
});

// Side effect imports


hooks.version = '2.18.1';

setHookCallback(createLocal);

hooks.fn                    = proto;
hooks.min                   = min;
hooks.max                   = max;
hooks.now                   = now;
hooks.utc                   = createUTC;
hooks.unix                  = createUnix;
hooks.months                = listMonths;
hooks.isDate                = isDate;
hooks.locale                = getSetGlobalLocale;
hooks.invalid               = createInvalid;
hooks.duration              = createDuration;
hooks.isMoment              = isMoment;
hooks.weekdays              = listWeekdays;
hooks.parseZone             = createInZone;
hooks.localeData            = getLocale;
hooks.isDuration            = isDuration;
hooks.monthsShort           = listMonthsShort;
hooks.weekdaysMin           = listWeekdaysMin;
hooks.defineLocale          = defineLocale;
hooks.updateLocale          = updateLocale;
hooks.locales               = listLocales;
hooks.weekdaysShort         = listWeekdaysShort;
hooks.normalizeUnits        = normalizeUnits;
hooks.relativeTimeRounding = getSetRelativeTimeRounding;
hooks.relativeTimeThreshold = getSetRelativeTimeThreshold;
hooks.calendarFormat        = getCalendarFormat;
hooks.prototype             = proto;

return hooks;

})));

define('moment', ['moment/moment'], function (main) { return main; });

define('backend',['fast', 'moment'], function (Fast, Moment) {
    var Backend = {
        api: {
            sidebar: function (params) {
                colorArr = ['red', 'green', 'yellow', 'blue', 'teal', 'orange', 'purple'];
                $colorNums = colorArr.length;
                badgeList = {};
                $.each(params, function (k, v) {
                    $url = Fast.api.fixurl(k);

                    if ($.isArray(v))
                    {
                        $nums = typeof v[0] !== 'undefined' ? v[0] : 0;
                        $color = typeof v[1] !== 'undefined' ? v[1] : colorArr[(!isNaN($nums) ? $nums : $nums.length) % $colorNums];
                        $class = typeof v[2] !== 'undefined' ? v[2] : 'label';
                    } else
                    {
                        $nums = v;
                        $color = colorArr[(!isNaN($nums) ? $nums : $nums.length) % $colorNums];
                        $class = 'label';
                    }
                    //必须nums大于0才显示
                    badgeList[$url] = $nums > 0 ? '<small class="' + $class + ' pull-right bg-' + $color + '">' + $nums + '</small>' : '';
                });
                $.each(badgeList, function (k, v) {
                    var anchor = top.window.$("li a[addtabs][url='" + k + "']");
                    if (anchor) {
                        top.window.$(".pull-right-container", anchor).html(v);
                        top.window.$(".nav-addtabs li a[node-id='" + anchor.attr("addtabs") + "'] .pull-right-container").html(v);
                    }
                });
            },
            addtabs: function (url, title, icon) {
                var dom = "a[url='{url}']"
                var leftlink = top.window.$(dom.replace(/\{url\}/, url));
                if (leftlink.size() > 0) {
                    leftlink.trigger("click");
                } else {
                    url = Fast.api.fixurl(url);
                    leftlink = top.window.$(dom.replace(/\{url\}/, url));
                    if (leftlink.size() > 0) {
                        var event = leftlink.parent().hasClass("active") ? "dblclick" : "click";
                        leftlink.trigger(event);
                    } else {
                        var baseurl = url.substr(0, url.indexOf("?") > -1 ? url.indexOf("?") : url.length);
                        leftlink = top.window.$(dom.replace(/\{url\}/, baseurl));
                        //能找到相对地址
                        if (leftlink.size() > 0) {
                            icon = typeof icon !== 'undefined' ? icon : leftlink.find("i").attr("class");
                            title = typeof title !== 'undefined' ? title : leftlink.find("span:first").text();
                            leftlink.trigger("fa.event.toggleitem");
                        }
                        var navnode = $(".nav-tabs ul li a[node-url='" + url + "']");
                        if (navnode.size() > 0) {
                            navnode.trigger("click");
                        } else {
                            //追加新的tab
                            var id = Math.floor(new Date().valueOf() * Math.random());
                            icon = typeof icon !== 'undefined' ? icon : 'fa fa-circle-o';
                            title = typeof title !== 'undefined' ? title : '';
                            top.window.$("<a />").append('<i class="' + icon + '"></i> <span>' + title + '</span>').prop("href", url).attr({url: url, addtabs: id}).addClass("hide").appendTo(top.window.document.body).trigger("click");
                        }
                    }
                }
            },
        },
        init: function () {
            //公共代码
            //添加ios-fix兼容iOS下的iframe
            if(/iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream){
                $("html").addClass("ios-fix");
            }
            //配置Toastr的参数
            Toastr.options.positionClass = Config.controllername === 'index' ? "toast-top-right-index" : "toast-top-right";
            //点击包含.btn-dialog的元素时弹出dialog
            $(document).on('click', '.btn-dialog,.dialogit', function (e) {
                e.preventDefault();
                var options = $(this).data();
                options = options ? options : {};
                Backend.api.open(Backend.api.fixurl($(this).attr('href')), $(this).attr('title'), options);
            });
            //点击包含.btn-addtabs的元素时事件
            $(document).on('click', '.btn-addtabs,.addtabsit', function (e) {
                e.preventDefault();
                Backend.api.addtabs($(this).attr("href"), $(this).attr("title"));
            });
            //点击包含.btn-ajax的元素时事件
            $(document).on('click', '.btn-ajax,.ajaxit', function (e) {
                e.preventDefault();
                var options = $(this).data();
                if (typeof options.url === 'undefined' && $(this).attr("href")) {
                    options.url = $(this).attr("href");
                }
                Backend.api.ajax(options);
            });
            //修复含有fixed-footer类的body边距
            if ($(".fixed-footer").size() > 0) {
                $(document.body).css("padding-bottom", $(".fixed-footer").height());
            }
        }
    };
    Backend.api = $.extend(Fast.api, Backend.api);
    //将Moment渲染至全局,以便于在子框架中调用
    window.Moment = Moment;
    //将Backend渲染至全局,以便于在子框架中调用
    window.Backend = Backend;

    Backend.init();
    return Backend;
});
//! moment.js locale configuration
//! locale : Chinese (China) [zh-cn]
//! author : suupic : https://github.com/suupic
//! author : Zeno Zeng : https://github.com/zenozeng

;(function (global, factory) {
   typeof exports === 'object' && typeof module !== 'undefined'
       && typeof require === 'function' ? factory(require('../moment')) :
   typeof define === 'function' && define.amd ? define('moment/locale/zh-cn',['../moment'], factory) :
   factory(global.moment)
}(this, (function (moment) { 'use strict';


var zhCn = moment.defineLocale('zh-cn', {
    months : '一月_二月_三月_四月_五月_六月_七月_八月_九月_十月_十一月_十二月'.split('_'),
    monthsShort : '1月_2月_3月_4月_5月_6月_7月_8月_9月_10月_11月_12月'.split('_'),
    weekdays : '星期日_星期一_星期二_星期三_星期四_星期五_星期六'.split('_'),
    weekdaysShort : '周日_周一_周二_周三_周四_周五_周六'.split('_'),
    weekdaysMin : '日_一_二_三_四_五_六'.split('_'),
    longDateFormat : {
        LT : 'HH:mm',
        LTS : 'HH:mm:ss',
        L : 'YYYY年MMMD日',
        LL : 'YYYY年MMMD日',
        LLL : 'YYYY年MMMD日Ah点mm分',
        LLLL : 'YYYY年MMMD日ddddAh点mm分',
        l : 'YYYY年MMMD日',
        ll : 'YYYY年MMMD日',
        lll : 'YYYY年MMMD日 HH:mm',
        llll : 'YYYY年MMMD日dddd HH:mm'
    },
    meridiemParse: /凌晨|早上|上午|中午|下午|晚上/,
    meridiemHour: function (hour, meridiem) {
        if (hour === 12) {
            hour = 0;
        }
        if (meridiem === '凌晨' || meridiem === '早上' ||
                meridiem === '上午') {
            return hour;
        } else if (meridiem === '下午' || meridiem === '晚上') {
            return hour + 12;
        } else {
            // '中午'
            return hour >= 11 ? hour : hour + 12;
        }
    },
    meridiem : function (hour, minute, isLower) {
        var hm = hour * 100 + minute;
        if (hm < 600) {
            return '凌晨';
        } else if (hm < 900) {
            return '早上';
        } else if (hm < 1130) {
            return '上午';
        } else if (hm < 1230) {
            return '中午';
        } else if (hm < 1800) {
            return '下午';
        } else {
            return '晚上';
        }
    },
    calendar : {
        sameDay : '[今天]LT',
        nextDay : '[明天]LT',
        nextWeek : '[下]ddddLT',
        lastDay : '[昨天]LT',
        lastWeek : '[上]ddddLT',
        sameElse : 'L'
    },
    dayOfMonthOrdinalParse: /\d{1,2}(日|月|周)/,
    ordinal : function (number, period) {
        switch (period) {
            case 'd':
            case 'D':
            case 'DDD':
                return number + '日';
            case 'M':
                return number + '月';
            case 'w':
            case 'W':
                return number + '周';
            default:
                return number;
        }
    },
    relativeTime : {
        future : '%s内',
        past : '%s前',
        s : '几秒',
        m : '1 分钟',
        mm : '%d 分钟',
        h : '1 小时',
        hh : '%d 小时',
        d : '1 天',
        dd : '%d 天',
        M : '1 个月',
        MM : '%d 个月',
        y : '1 年',
        yy : '%d 年'
    },
    week : {
        // GB/T 7408-1994《数据元和交换格式·信息交换·日期和时间表示法》与ISO 8601:1988等效
        dow : 1, // Monday is the first day of the week.
        doy : 4  // The week that contains Jan 4th is the first week of the year.
    }
});

return zhCn;

})));

/*
* bootstrap-table - v1.11.1 - 2017-02-22
* https://github.com/wenzhixin/bootstrap-table
* Copyright (c) 2017 zhixin wen
* Licensed MIT License
*/
!function(a){"use strict";var b=null,c=function(a){var b=arguments,c=!0,d=1;return a=a.replace(/%s/g,function(){var a=b[d++];return"undefined"==typeof a?(c=!1,""):a}),c?a:""},d=function(b,c,d,e){var f="";return a.each(b,function(a,b){return b[c]===e?(f=b[d],!1):!0}),f},e=function(b,c){var d=-1;return a.each(b,function(a,b){return b.field===c?(d=a,!1):!0}),d},f=function(b){var c,d,e,f=0,g=[];for(c=0;c<b[0].length;c++)f+=b[0][c].colspan||1;for(c=0;c<b.length;c++)for(g[c]=[],d=0;f>d;d++)g[c][d]=!1;for(c=0;c<b.length;c++)for(d=0;d<b[c].length;d++){var h=b[c][d],i=h.rowspan||1,j=h.colspan||1,k=a.inArray(!1,g[c]);for(1===j&&(h.fieldIndex=k,"undefined"==typeof h.field&&(h.field=k)),e=0;i>e;e++)g[c+e][k]=!0;for(e=0;j>e;e++)g[c][k+e]=!0}},g=function(){if(null===b){var c,d,e=a("<p/>").addClass("fixed-table-scroll-inner"),f=a("<div/>").addClass("fixed-table-scroll-outer");f.append(e),a("body").append(f),c=e[0].offsetWidth,f.css("overflow","scroll"),d=e[0].offsetWidth,c===d&&(d=f[0].clientWidth),f.remove(),b=c-d}return b},h=function(b,d,e,f){var g=d;if("string"==typeof d){var h=d.split(".");h.length>1?(g=window,a.each(h,function(a,b){g=g[b]})):g=window[d]}return"object"==typeof g?g:"function"==typeof g?g.apply(b,e||[]):!g&&"string"==typeof d&&c.apply(this,[d].concat(e))?c.apply(this,[d].concat(e)):f},i=function(b,c,d){var e=Object.getOwnPropertyNames(b),f=Object.getOwnPropertyNames(c),g="";if(d&&e.length!==f.length)return!1;for(var h=0;h<e.length;h++)if(g=e[h],a.inArray(g,f)>-1&&b[g]!==c[g])return!1;return!0},j=function(a){return"string"==typeof a?a.replace(/&/g,"&amp;").replace(/</g,"&lt;").replace(/>/g,"&gt;").replace(/"/g,"&quot;").replace(/'/g,"&#039;").replace(/`/g,"&#x60;"):a},k=function(a){for(var b in a){var c=b.split(/(?=[A-Z])/).join("-").toLowerCase();c!==b&&(a[c]=a[b],delete a[b])}return a},l=function(a,b,c){var d=a;if("string"!=typeof b||a.hasOwnProperty(b))return c?j(a[b]):a[b];var e=b.split(".");for(var f in e)e.hasOwnProperty(f)&&(d=d&&d[e[f]]);return c?j(d):d},m=function(){return!!(navigator.userAgent.indexOf("MSIE ")>0||navigator.userAgent.match(/Trident.*rv\:11\./))},n=function(){Object.keys||(Object.keys=function(){var a=Object.prototype.hasOwnProperty,b=!{toString:null}.propertyIsEnumerable("toString"),c=["toString","toLocaleString","valueOf","hasOwnProperty","isPrototypeOf","propertyIsEnumerable","constructor"],d=c.length;return function(e){if("object"!=typeof e&&("function"!=typeof e||null===e))throw new TypeError("Object.keys called on non-object");var f,g,h=[];for(f in e)a.call(e,f)&&h.push(f);if(b)for(g=0;d>g;g++)a.call(e,c[g])&&h.push(c[g]);return h}}())},o=function(b,c){this.options=c,this.$el=a(b),this.$el_=this.$el.clone(),this.timeoutId_=0,this.timeoutFooter_=0,this.init()};o.DEFAULTS={classes:"table table-hover",sortClass:void 0,locale:void 0,height:void 0,undefinedText:"-",sortName:void 0,sortOrder:"asc",sortStable:!1,striped:!1,columns:[[]],data:[],totalField:"total",dataField:"rows",method:"get",url:void 0,ajax:void 0,cache:!0,contentType:"application/json",dataType:"json",ajaxOptions:{},queryParams:function(a){return a},queryParamsType:"limit",responseHandler:function(a){return a},pagination:!1,onlyInfoPagination:!1,paginationLoop:!0,sidePagination:"client",totalRows:0,pageNumber:1,pageSize:10,pageList:[10,25,50,100],paginationHAlign:"right",paginationVAlign:"bottom",paginationDetailHAlign:"left",paginationPreText:"&lsaquo;",paginationNextText:"&rsaquo;",search:!1,searchOnEnterKey:!1,strictSearch:!1,searchAlign:"right",selectItemName:"btSelectItem",showHeader:!0,showFooter:!1,showColumns:!1,showPaginationSwitch:!1,showRefresh:!1,showToggle:!1,buttonsAlign:"right",smartDisplay:!0,escape:!1,minimumCountColumns:1,idField:void 0,uniqueId:void 0,cardView:!1,detailView:!1,detailFormatter:function(){return""},trimOnSearch:!0,clickToSelect:!1,singleSelect:!1,toolbar:void 0,toolbarAlign:"left",checkboxHeader:!0,sortable:!0,silentSort:!0,maintainSelected:!1,searchTimeOut:500,searchText:"",iconSize:void 0,buttonsClass:"default",iconsPrefix:"glyphicon",icons:{paginationSwitchDown:"glyphicon-collapse-down icon-chevron-down",paginationSwitchUp:"glyphicon-collapse-up icon-chevron-up",refresh:"glyphicon-refresh icon-refresh",toggle:"glyphicon-list-alt icon-list-alt",columns:"glyphicon-th icon-th",detailOpen:"glyphicon-plus icon-plus",detailClose:"glyphicon-minus icon-minus"},customSearch:a.noop,customSort:a.noop,rowStyle:function(){return{}},rowAttributes:function(){return{}},footerStyle:function(){return{}},onAll:function(){return!1},onClickCell:function(){return!1},onDblClickCell:function(){return!1},onClickRow:function(){return!1},onDblClickRow:function(){return!1},onSort:function(){return!1},onCheck:function(){return!1},onUncheck:function(){return!1},onCheckAll:function(){return!1},onUncheckAll:function(){return!1},onCheckSome:function(){return!1},onUncheckSome:function(){return!1},onLoadSuccess:function(){return!1},onLoadError:function(){return!1},onColumnSwitch:function(){return!1},onPageChange:function(){return!1},onSearch:function(){return!1},onToggle:function(){return!1},onPreBody:function(){return!1},onPostBody:function(){return!1},onPostHeader:function(){return!1},onExpandRow:function(){return!1},onCollapseRow:function(){return!1},onRefreshOptions:function(){return!1},onRefresh:function(){return!1},onResetView:function(){return!1}},o.LOCALES={},o.LOCALES["en-US"]=o.LOCALES.en={formatLoadingMessage:function(){return"Loading, please wait..."},formatRecordsPerPage:function(a){return c("%s rows per page",a)},formatShowingRows:function(a,b,d){return c("Showing %s to %s of %s rows",a,b,d)},formatDetailPagination:function(a){return c("Showing %s rows",a)},formatSearch:function(){return"Search"},formatNoMatches:function(){return"No matching records found"},formatPaginationSwitch:function(){return"Hide/Show pagination"},formatRefresh:function(){return"Refresh"},formatToggle:function(){return"Toggle"},formatColumns:function(){return"Columns"},formatAllRows:function(){return"All"}},a.extend(o.DEFAULTS,o.LOCALES["en-US"]),o.COLUMN_DEFAULTS={radio:!1,checkbox:!1,checkboxEnabled:!0,field:void 0,title:void 0,titleTooltip:void 0,"class":void 0,align:void 0,halign:void 0,falign:void 0,valign:void 0,width:void 0,sortable:!1,order:"asc",visible:!0,switchable:!0,clickToSelect:!0,formatter:void 0,footerFormatter:void 0,events:void 0,sorter:void 0,sortName:void 0,cellStyle:void 0,searchable:!0,searchFormatter:!0,cardVisible:!0,escape:!1},o.EVENTS={"all.bs.table":"onAll","click-cell.bs.table":"onClickCell","dbl-click-cell.bs.table":"onDblClickCell","click-row.bs.table":"onClickRow","dbl-click-row.bs.table":"onDblClickRow","sort.bs.table":"onSort","check.bs.table":"onCheck","uncheck.bs.table":"onUncheck","check-all.bs.table":"onCheckAll","uncheck-all.bs.table":"onUncheckAll","check-some.bs.table":"onCheckSome","uncheck-some.bs.table":"onUncheckSome","load-success.bs.table":"onLoadSuccess","load-error.bs.table":"onLoadError","column-switch.bs.table":"onColumnSwitch","page-change.bs.table":"onPageChange","search.bs.table":"onSearch","toggle.bs.table":"onToggle","pre-body.bs.table":"onPreBody","post-body.bs.table":"onPostBody","post-header.bs.table":"onPostHeader","expand-row.bs.table":"onExpandRow","collapse-row.bs.table":"onCollapseRow","refresh-options.bs.table":"onRefreshOptions","reset-view.bs.table":"onResetView","refresh.bs.table":"onRefresh"},o.prototype.init=function(){this.initLocale(),this.initContainer(),this.initTable(),this.initHeader(),this.initData(),this.initHiddenRows(),this.initFooter(),this.initToolbar(),this.initPagination(),this.initBody(),this.initSearchText(),this.initServer()},o.prototype.initLocale=function(){if(this.options.locale){var b=this.options.locale.split(/-|_/);b[0].toLowerCase(),b[1]&&b[1].toUpperCase(),a.fn.bootstrapTable.locales[this.options.locale]?a.extend(this.options,a.fn.bootstrapTable.locales[this.options.locale]):a.fn.bootstrapTable.locales[b.join("-")]?a.extend(this.options,a.fn.bootstrapTable.locales[b.join("-")]):a.fn.bootstrapTable.locales[b[0]]&&a.extend(this.options,a.fn.bootstrapTable.locales[b[0]])}},o.prototype.initContainer=function(){this.$container=a(['<div class="bootstrap-table">','<div class="fixed-table-toolbar"></div>',"top"===this.options.paginationVAlign||"both"===this.options.paginationVAlign?'<div class="fixed-table-pagination" style="clear: both;"></div>':"",'<div class="fixed-table-container">','<div class="fixed-table-header"><table></table></div>','<div class="fixed-table-body">','<div class="fixed-table-loading">',this.options.formatLoadingMessage(),"</div>","</div>",'<div class="fixed-table-footer"><table><tr></tr></table></div>',"bottom"===this.options.paginationVAlign||"both"===this.options.paginationVAlign?'<div class="fixed-table-pagination"></div>':"","</div>","</div>"].join("")),this.$container.insertAfter(this.$el),this.$tableContainer=this.$container.find(".fixed-table-container"),this.$tableHeader=this.$container.find(".fixed-table-header"),this.$tableBody=this.$container.find(".fixed-table-body"),this.$tableLoading=this.$container.find(".fixed-table-loading"),this.$tableFooter=this.$container.find(".fixed-table-footer"),this.$toolbar=this.$container.find(".fixed-table-toolbar"),this.$pagination=this.$container.find(".fixed-table-pagination"),this.$tableBody.append(this.$el),this.$container.after('<div class="clearfix"></div>'),this.$el.addClass(this.options.classes),this.options.striped&&this.$el.addClass("table-striped"),-1!==a.inArray("table-no-bordered",this.options.classes.split(" "))&&this.$tableContainer.addClass("table-no-bordered")},o.prototype.initTable=function(){var b=this,c=[],d=[];if(this.$header=this.$el.find(">thead"),this.$header.length||(this.$header=a("<thead></thead>").appendTo(this.$el)),this.$header.find("tr").each(function(){var b=[];a(this).find("th").each(function(){"undefined"!=typeof a(this).data("field")&&a(this).data("field",a(this).data("field")+""),b.push(a.extend({},{title:a(this).html(),"class":a(this).attr("class"),titleTooltip:a(this).attr("title"),rowspan:a(this).attr("rowspan")?+a(this).attr("rowspan"):void 0,colspan:a(this).attr("colspan")?+a(this).attr("colspan"):void 0},a(this).data()))}),c.push(b)}),a.isArray(this.options.columns[0])||(this.options.columns=[this.options.columns]),this.options.columns=a.extend(!0,[],c,this.options.columns),this.columns=[],f(this.options.columns),a.each(this.options.columns,function(c,d){a.each(d,function(d,e){e=a.extend({},o.COLUMN_DEFAULTS,e),"undefined"!=typeof e.fieldIndex&&(b.columns[e.fieldIndex]=e),b.options.columns[c][d]=e})}),!this.options.data.length){var e=[];this.$el.find(">tbody>tr").each(function(c){var f={};f._id=a(this).attr("id"),f._class=a(this).attr("class"),f._data=k(a(this).data()),a(this).find(">td").each(function(d){for(var g,h,i=a(this),j=+i.attr("colspan")||1,l=+i.attr("rowspan")||1;e[c]&&e[c][d];d++);for(g=d;d+j>g;g++)for(h=c;c+l>h;h++)e[h]||(e[h]=[]),e[h][g]=!0;var m=b.columns[d].field;f[m]=a(this).html(),f["_"+m+"_id"]=a(this).attr("id"),f["_"+m+"_class"]=a(this).attr("class"),f["_"+m+"_rowspan"]=a(this).attr("rowspan"),f["_"+m+"_colspan"]=a(this).attr("colspan"),f["_"+m+"_title"]=a(this).attr("title"),f["_"+m+"_data"]=k(a(this).data())}),d.push(f)}),this.options.data=d,d.length&&(this.fromHtml=!0)}},o.prototype.initHeader=function(){var b=this,d={},e=[];this.header={fields:[],styles:[],classes:[],formatters:[],events:[],sorters:[],sortNames:[],cellStyles:[],searchables:[]},a.each(this.options.columns,function(f,g){e.push("<tr>"),0===f&&!b.options.cardView&&b.options.detailView&&e.push(c('<th class="detail" rowspan="%s"><div class="fht-cell"></div></th>',b.options.columns.length)),a.each(g,function(a,f){var g="",h="",i="",k="",l=c(' class="%s"',f["class"]),m=(b.options.sortOrder||f.order,"px"),n=f.width;if(void 0===f.width||b.options.cardView||"string"==typeof f.width&&-1!==f.width.indexOf("%")&&(m="%"),f.width&&"string"==typeof f.width&&(n=f.width.replace("%","").replace("px","")),h=c("text-align: %s; ",f.halign?f.halign:f.align),i=c("text-align: %s; ",f.align),k=c("vertical-align: %s; ",f.valign),k+=c("width: %s; ",!f.checkbox&&!f.radio||n?n?n+m:void 0:"36px"),"undefined"!=typeof f.fieldIndex){if(b.header.fields[f.fieldIndex]=f.field,b.header.styles[f.fieldIndex]=i+k,b.header.classes[f.fieldIndex]=l,b.header.formatters[f.fieldIndex]=f.formatter,b.header.events[f.fieldIndex]=f.events,b.header.sorters[f.fieldIndex]=f.sorter,b.header.sortNames[f.fieldIndex]=f.sortName,b.header.cellStyles[f.fieldIndex]=f.cellStyle,b.header.searchables[f.fieldIndex]=f.searchable,!f.visible)return;if(b.options.cardView&&!f.cardVisible)return;d[f.field]=f}e.push("<th"+c(' title="%s"',f.titleTooltip),f.checkbox||f.radio?c(' class="bs-checkbox %s"',f["class"]||""):l,c(' style="%s"',h+k),c(' rowspan="%s"',f.rowspan),c(' colspan="%s"',f.colspan),c(' data-field="%s"',f.field),">"),e.push(c('<div class="th-inner %s">',b.options.sortable&&f.sortable?"sortable both":"")),g=b.options.escape?j(f.title):f.title,f.checkbox&&(!b.options.singleSelect&&b.options.checkboxHeader&&(g='<input name="btSelectAll" type="checkbox" />'),b.header.stateField=f.field),f.radio&&(g="",b.header.stateField=f.field,b.options.singleSelect=!0),e.push(g),e.push("</div>"),e.push('<div class="fht-cell"></div>'),e.push("</div>"),e.push("</th>")}),e.push("</tr>")}),this.$header.html(e.join("")),this.$header.find("th[data-field]").each(function(){a(this).data(d[a(this).data("field")])}),this.$container.off("click",".th-inner").on("click",".th-inner",function(c){var d=a(this);return b.options.detailView&&d.closest(".bootstrap-table")[0]!==b.$container[0]?!1:void(b.options.sortable&&d.parent().data().sortable&&b.onSort(c))}),this.$header.children().children().off("keypress").on("keypress",function(c){if(b.options.sortable&&a(this).data().sortable){var d=c.keyCode||c.which;13==d&&b.onSort(c)}}),a(window).off("resize.bootstrap-table"),!this.options.showHeader||this.options.cardView?(this.$header.hide(),this.$tableHeader.hide(),this.$tableLoading.css("top",0)):(this.$header.show(),this.$tableHeader.show(),this.$tableLoading.css("top",this.$header.outerHeight()+1),this.getCaret(),a(window).on("resize.bootstrap-table",a.proxy(this.resetWidth,this))),this.$selectAll=this.$header.find('[name="btSelectAll"]'),this.$selectAll.off("click").on("click",function(){var c=a(this).prop("checked");b[c?"checkAll":"uncheckAll"](),b.updateSelected()})},o.prototype.initFooter=function(){!this.options.showFooter||this.options.cardView?this.$tableFooter.hide():this.$tableFooter.show()},o.prototype.initData=function(a,b){this.data="append"===b?this.data.concat(a):"prepend"===b?[].concat(a).concat(this.data):a||this.options.data,this.options.data="append"===b?this.options.data.concat(a):"prepend"===b?[].concat(a).concat(this.options.data):this.data,"server"!==this.options.sidePagination&&this.initSort()},o.prototype.initSort=function(){var b=this,d=this.options.sortName,e="desc"===this.options.sortOrder?-1:1,f=a.inArray(this.options.sortName,this.header.fields),g=0;return this.options.customSort!==a.noop?void this.options.customSort.apply(this,[this.options.sortName,this.options.sortOrder]):void(-1!==f&&(this.options.sortStable&&a.each(this.data,function(a,b){b.hasOwnProperty("_position")||(b._position=a)}),this.data.sort(function(c,g){b.header.sortNames[f]&&(d=b.header.sortNames[f]);var i=l(c,d,b.options.escape),j=l(g,d,b.options.escape),k=h(b.header,b.header.sorters[f],[i,j]);return void 0!==k?e*k:((void 0===i||null===i)&&(i=""),(void 0===j||null===j)&&(j=""),b.options.sortStable&&i===j&&(i=c._position,j=g._position),a.isNumeric(i)&&a.isNumeric(j)?(i=parseFloat(i),j=parseFloat(j),j>i?-1*e:e):i===j?0:("string"!=typeof i&&(i=i.toString()),-1===i.localeCompare(j)?-1*e:e))}),void 0!==this.options.sortClass&&(clearTimeout(g),g=setTimeout(function(){b.$el.removeClass(b.options.sortClass);var a=b.$header.find(c('[data-field="%s"]',b.options.sortName).index()+1);b.$el.find(c("tr td:nth-child(%s)",a)).addClass(b.options.sortClass)},250))))},o.prototype.onSort=function(b){var c="keypress"===b.type?a(b.currentTarget):a(b.currentTarget).parent(),d=this.$header.find("th").eq(c.index());return this.$header.add(this.$header_).find("span.order").remove(),this.options.sortName===c.data("field")?this.options.sortOrder="asc"===this.options.sortOrder?"desc":"asc":(this.options.sortName=c.data("field"),this.options.sortOrder="asc"===c.data("order")?"desc":"asc"),this.trigger("sort",this.options.sortName,this.options.sortOrder),c.add(d).data("order",this.options.sortOrder),this.getCaret(),"server"===this.options.sidePagination?void this.initServer(this.options.silentSort):(this.initSort(),void this.initBody())},o.prototype.initToolbar=function(){var b,d,e=this,f=[],g=0,i=0;this.$toolbar.find(".bs-bars").children().length&&a("body").append(a(this.options.toolbar)),this.$toolbar.html(""),("string"==typeof this.options.toolbar||"object"==typeof this.options.toolbar)&&a(c('<div class="bs-bars pull-%s"></div>',this.options.toolbarAlign)).appendTo(this.$toolbar).append(a(this.options.toolbar)),f=[c('<div class="columns columns-%s btn-group pull-%s">',this.options.buttonsAlign,this.options.buttonsAlign)],"string"==typeof this.options.icons&&(this.options.icons=h(null,this.options.icons)),this.options.showPaginationSwitch&&f.push(c('<button class="btn'+c(" btn-%s",this.options.buttonsClass)+c(" btn-%s",this.options.iconSize)+'" type="button" name="paginationSwitch" aria-label="pagination Switch" title="%s">',this.options.formatPaginationSwitch()),c('<i class="%s %s"></i>',this.options.iconsPrefix,this.options.icons.paginationSwitchDown),"</button>"),this.options.showRefresh&&f.push(c('<button class="btn'+c(" btn-%s",this.options.buttonsClass)+c(" btn-%s",this.options.iconSize)+'" type="button" name="refresh" aria-label="refresh" title="%s">',this.options.formatRefresh()),c('<i class="%s %s"></i>',this.options.iconsPrefix,this.options.icons.refresh),"</button>"),this.options.showToggle&&f.push(c('<button class="btn'+c(" btn-%s",this.options.buttonsClass)+c(" btn-%s",this.options.iconSize)+'" type="button" name="toggle" aria-label="toggle" title="%s">',this.options.formatToggle()),c('<i class="%s %s"></i>',this.options.iconsPrefix,this.options.icons.toggle),"</button>"),this.options.showColumns&&(f.push(c('<div class="keep-open btn-group" title="%s">',this.options.formatColumns()),'<button type="button" aria-label="columns" class="btn'+c(" btn-%s",this.options.buttonsClass)+c(" btn-%s",this.options.iconSize)+' dropdown-toggle" data-toggle="dropdown">',c('<i class="%s %s"></i>',this.options.iconsPrefix,this.options.icons.columns),' <span class="caret"></span>',"</button>",'<ul class="dropdown-menu" role="menu">'),a.each(this.columns,function(a,b){if(!(b.radio||b.checkbox||e.options.cardView&&!b.cardVisible)){var d=b.visible?' checked="checked"':"";b.switchable&&(f.push(c('<li role="menuitem"><label><input type="checkbox" data-field="%s" value="%s"%s> %s</label></li>',b.field,a,d,b.title)),i++)}}),f.push("</ul>","</div>")),f.push("</div>"),(this.showToolbar||f.length>2)&&this.$toolbar.append(f.join("")),this.options.showPaginationSwitch&&this.$toolbar.find('button[name="paginationSwitch"]').off("click").on("click",a.proxy(this.togglePagination,this)),this.options.showRefresh&&this.$toolbar.find('button[name="refresh"]').off("click").on("click",a.proxy(this.refresh,this)),this.options.showToggle&&this.$toolbar.find('button[name="toggle"]').off("click").on("click",function(){e.toggleView()}),this.options.showColumns&&(b=this.$toolbar.find(".keep-open"),i<=this.options.minimumCountColumns&&b.find("input").prop("disabled",!0),b.find("li").off("click").on("click",function(a){a.stopImmediatePropagation()}),b.find("input").off("click").on("click",function(){var b=a(this);e.toggleColumn(a(this).val(),b.prop("checked"),!1),e.trigger("column-switch",a(this).data("field"),b.prop("checked"))})),this.options.search&&(f=[],f.push('<div class="pull-'+this.options.searchAlign+' search">',c('<input class="form-control'+c(" input-%s",this.options.iconSize)+'" type="text" placeholder="%s">',this.options.formatSearch()),"</div>"),this.$toolbar.append(f.join("")),d=this.$toolbar.find(".search input"),d.off("keyup drop blur").on("keyup drop blur",function(b){e.options.searchOnEnterKey&&13!==b.keyCode||a.inArray(b.keyCode,[37,38,39,40])>-1||(clearTimeout(g),g=setTimeout(function(){e.onSearch(b)},e.options.searchTimeOut))}),m()&&d.off("mouseup").on("mouseup",function(a){clearTimeout(g),g=setTimeout(function(){e.onSearch(a)},e.options.searchTimeOut)}))},o.prototype.onSearch=function(b){var c=a.trim(a(b.currentTarget).val());this.options.trimOnSearch&&a(b.currentTarget).val()!==c&&a(b.currentTarget).val(c),c!==this.searchText&&(this.searchText=c,this.options.searchText=c,this.options.pageNumber=1,this.initSearch(),this.updatePagination(),this.trigger("search",c))},o.prototype.initSearch=function(){var b=this;if("server"!==this.options.sidePagination){if(this.options.customSearch!==a.noop)return void this.options.customSearch.apply(this,[this.searchText]);var c=this.searchText&&(this.options.escape?j(this.searchText):this.searchText).toLowerCase(),d=a.isEmptyObject(this.filterColumns)?null:this.filterColumns;this.data=d?a.grep(this.options.data,function(b){for(var c in d)if(a.isArray(d[c])&&-1===a.inArray(b[c],d[c])||!a.isArray(d[c])&&b[c]!==d[c])return!1;return!0}):this.options.data,this.data=c?a.grep(this.data,function(d,f){for(var g=0;g<b.header.fields.length;g++)if(b.header.searchables[g]){var i,j=a.isNumeric(b.header.fields[g])?parseInt(b.header.fields[g],10):b.header.fields[g],k=b.columns[e(b.columns,j)];if("string"==typeof j){i=d;for(var l=j.split("."),m=0;m<l.length;m++)i=i[l[m]];k&&k.searchFormatter&&(i=h(k,b.header.formatters[g],[i,d,f],i))}else i=d[j];if("string"==typeof i||"number"==typeof i)if(b.options.strictSearch){if((i+"").toLowerCase()===c)return!0}else if(-1!==(i+"").toLowerCase().indexOf(c))return!0}return!1}):this.data}},o.prototype.initPagination=function(){if(!this.options.pagination)return void this.$pagination.hide();this.$pagination.show();var b,d,e,f,g,h,i,j,k,l=this,m=[],n=!1,o=this.getData(),p=this.options.pageList;if("server"!==this.options.sidePagination&&(this.options.totalRows=o.length),this.totalPages=0,this.options.totalRows){if(this.options.pageSize===this.options.formatAllRows())this.options.pageSize=this.options.totalRows,n=!0;else if(this.options.pageSize===this.options.totalRows){var q="string"==typeof this.options.pageList?this.options.pageList.replace("[","").replace("]","").replace(/ /g,"").toLowerCase().split(","):this.options.pageList;a.inArray(this.options.formatAllRows().toLowerCase(),q)>-1&&(n=!0)}this.totalPages=~~((this.options.totalRows-1)/this.options.pageSize)+1,this.options.totalPages=this.totalPages}if(this.totalPages>0&&this.options.pageNumber>this.totalPages&&(this.options.pageNumber=this.totalPages),this.pageFrom=(this.options.pageNumber-1)*this.options.pageSize+1,this.pageTo=this.options.pageNumber*this.options.pageSize,this.pageTo>this.options.totalRows&&(this.pageTo=this.options.totalRows),m.push('<div class="pull-'+this.options.paginationDetailHAlign+' pagination-detail">','<span class="pagination-info">',this.options.onlyInfoPagination?this.options.formatDetailPagination(this.options.totalRows):this.options.formatShowingRows(this.pageFrom,this.pageTo,this.options.totalRows),"</span>"),!this.options.onlyInfoPagination){m.push('<span class="page-list">');var r=[c('<span class="btn-group %s">',"top"===this.options.paginationVAlign||"both"===this.options.paginationVAlign?"dropdown":"dropup"),'<button type="button" class="btn'+c(" btn-%s",this.options.buttonsClass)+c(" btn-%s",this.options.iconSize)+' dropdown-toggle" data-toggle="dropdown">','<span class="page-size">',n?this.options.formatAllRows():this.options.pageSize,"</span>",' <span class="caret"></span>',"</button>",'<ul class="dropdown-menu" role="menu">'];if("string"==typeof this.options.pageList){var s=this.options.pageList.replace("[","").replace("]","").replace(/ /g,"").split(",");p=[],a.each(s,function(a,b){p.push(b.toUpperCase()===l.options.formatAllRows().toUpperCase()?l.options.formatAllRows():+b)})}for(a.each(p,function(a,b){if(!l.options.smartDisplay||0===a||p[a-1]<l.options.totalRows){var d;d=n?b===l.options.formatAllRows()?' class="active"':"":b===l.options.pageSize?' class="active"':"",r.push(c('<li role="menuitem"%s><a href="#">%s</a></li>',d,b))}}),r.push("</ul></span>"),m.push(this.options.formatRecordsPerPage(r.join(""))),m.push("</span>"),m.push("</div>",'<div class="pull-'+this.options.paginationHAlign+' pagination">','<ul class="pagination'+c(" pagination-%s",this.options.iconSize)+'">','<li class="page-pre"><a href="#">'+this.options.paginationPreText+"</a></li>"),this.totalPages<5?(d=1,e=this.totalPages):(d=this.options.pageNumber-2,e=d+4,1>d&&(d=1,e=5),e>this.totalPages&&(e=this.totalPages,d=e-4)),this.totalPages>=6&&(this.options.pageNumber>=3&&(m.push('<li class="page-first'+(1===this.options.pageNumber?" active":"")+'">','<a href="#">',1,"</a>","</li>"),d++),this.options.pageNumber>=4&&(4==this.options.pageNumber||6==this.totalPages||7==this.totalPages?d--:m.push('<li class="page-first-separator disabled">','<a href="#">...</a>',"</li>"),e--)),this.totalPages>=7&&this.options.pageNumber>=this.totalPages-2&&d--,6==this.totalPages?this.options.pageNumber>=this.totalPages-2&&e++:this.totalPages>=7&&(7==this.totalPages||this.options.pageNumber>=this.totalPages-3)&&e++,b=d;e>=b;b++)m.push('<li class="page-number'+(b===this.options.pageNumber?" active":"")+'">','<a href="#">',b,"</a>","</li>");this.totalPages>=8&&this.options.pageNumber<=this.totalPages-4&&m.push('<li class="page-last-separator disabled">','<a href="#">...</a>',"</li>"),this.totalPages>=6&&this.options.pageNumber<=this.totalPages-3&&m.push('<li class="page-last'+(this.totalPages===this.options.pageNumber?" active":"")+'">','<a href="#">',this.totalPages,"</a>","</li>"),m.push('<li class="page-next"><a href="#">'+this.options.paginationNextText+"</a></li>","</ul>","</div>")}this.$pagination.html(m.join("")),this.options.onlyInfoPagination||(f=this.$pagination.find(".page-list a"),g=this.$pagination.find(".page-first"),h=this.$pagination.find(".page-pre"),i=this.$pagination.find(".page-next"),j=this.$pagination.find(".page-last"),k=this.$pagination.find(".page-number"),this.options.smartDisplay&&(this.totalPages<=1&&this.$pagination.find("div.pagination").hide(),(p.length<2||this.options.totalRows<=p[0])&&this.$pagination.find("span.page-list").hide(),this.$pagination[this.getData().length?"show":"hide"]()),this.options.paginationLoop||(1===this.options.pageNumber&&h.addClass("disabled"),this.options.pageNumber===this.totalPages&&i.addClass("disabled")),n&&(this.options.pageSize=this.options.formatAllRows()),f.off("click").on("click",a.proxy(this.onPageListChange,this)),g.off("click").on("click",a.proxy(this.onPageFirst,this)),h.off("click").on("click",a.proxy(this.onPagePre,this)),i.off("click").on("click",a.proxy(this.onPageNext,this)),j.off("click").on("click",a.proxy(this.onPageLast,this)),k.off("click").on("click",a.proxy(this.onPageNumber,this)))},o.prototype.updatePagination=function(b){b&&a(b.currentTarget).hasClass("disabled")||(this.options.maintainSelected||this.resetRows(),this.initPagination(),"server"===this.options.sidePagination?this.initServer():this.initBody(),this.trigger("page-change",this.options.pageNumber,this.options.pageSize))},o.prototype.onPageListChange=function(b){var c=a(b.currentTarget);return c.parent().addClass("active").siblings().removeClass("active"),this.options.pageSize=c.text().toUpperCase()===this.options.formatAllRows().toUpperCase()?this.options.formatAllRows():+c.text(),this.$toolbar.find(".page-size").text(this.options.pageSize),this.updatePagination(b),!1},o.prototype.onPageFirst=function(a){return this.options.pageNumber=1,this.updatePagination(a),!1},o.prototype.onPagePre=function(a){return this.options.pageNumber-1===0?this.options.pageNumber=this.options.totalPages:this.options.pageNumber--,this.updatePagination(a),!1},o.prototype.onPageNext=function(a){return this.options.pageNumber+1>this.options.totalPages?this.options.pageNumber=1:this.options.pageNumber++,this.updatePagination(a),!1},o.prototype.onPageLast=function(a){return this.options.pageNumber=this.totalPages,this.updatePagination(a),!1},o.prototype.onPageNumber=function(b){return this.options.pageNumber!==+a(b.currentTarget).text()?(this.options.pageNumber=+a(b.currentTarget).text(),this.updatePagination(b),!1):void 0},o.prototype.initRow=function(b,e){var f,g=this,i=[],k={},m=[],n="",o={},p=[];if(!(a.inArray(b,this.hiddenRows)>-1)){if(k=h(this.options,this.options.rowStyle,[b,e],k),k&&k.css)for(f in k.css)m.push(f+": "+k.css[f]);if(o=h(this.options,this.options.rowAttributes,[b,e],o))for(f in o)p.push(c('%s="%s"',f,j(o[f])));return b._data&&!a.isEmptyObject(b._data)&&a.each(b._data,function(a,b){"index"!==a&&(n+=c(' data-%s="%s"',a,b))}),i.push("<tr",c(" %s",p.join(" ")),c(' id="%s"',a.isArray(b)?void 0:b._id),c(' class="%s"',k.classes||(a.isArray(b)?void 0:b._class)),c(' data-index="%s"',e),c(' data-uniqueid="%s"',b[this.options.uniqueId]),c("%s",n),">"),this.options.cardView&&i.push(c('<td colspan="%s"><div class="card-views">',this.header.fields.length)),!this.options.cardView&&this.options.detailView&&i.push("<td>",'<a class="detail-icon" href="#">',c('<i class="%s %s"></i>',this.options.iconsPrefix,this.options.icons.detailOpen),"</a>","</td>"),a.each(this.header.fields,function(f,n){var o="",p=l(b,n,g.options.escape),q="",r="",s={},t="",u=g.header.classes[f],v="",w="",x="",y="",z=g.columns[f];if(!(g.fromHtml&&"undefined"==typeof p||!z.visible||g.options.cardView&&!z.cardVisible)){if(z.escape&&(p=j(p)),k=c('style="%s"',m.concat(g.header.styles[f]).join("; ")),b["_"+n+"_id"]&&(t=c(' id="%s"',b["_"+n+"_id"])),b["_"+n+"_class"]&&(u=c(' class="%s"',b["_"+n+"_class"])),b["_"+n+"_rowspan"]&&(w=c(' rowspan="%s"',b["_"+n+"_rowspan"])),b["_"+n+"_colspan"]&&(x=c(' colspan="%s"',b["_"+n+"_colspan"])),b["_"+n+"_title"]&&(y=c(' title="%s"',b["_"+n+"_title"])),s=h(g.header,g.header.cellStyles[f],[p,b,e,n],s),s.classes&&(u=c(' class="%s"',s.classes)),s.css){var A=[];for(var B in s.css)A.push(B+": "+s.css[B]);k=c('style="%s"',A.concat(g.header.styles[f]).join("; "))}q=h(z,g.header.formatters[f],[p,b,e],p),b["_"+n+"_data"]&&!a.isEmptyObject(b["_"+n+"_data"])&&a.each(b["_"+n+"_data"],function(a,b){"index"!==a&&(v+=c(' data-%s="%s"',a,b))}),z.checkbox||z.radio?(r=z.checkbox?"checkbox":r,r=z.radio?"radio":r,o=[c(g.options.cardView?'<div class="card-view %s">':'<td class="bs-checkbox %s">',z["class"]||""),"<input"+c(' data-index="%s"',e)+c(' name="%s"',g.options.selectItemName)+c(' type="%s"',r)+c(' value="%s"',b[g.options.idField])+c(' checked="%s"',q===!0||p||q&&q.checked?"checked":void 0)+c(' disabled="%s"',!z.checkboxEnabled||q&&q.disabled?"disabled":void 0)+" />",g.header.formatters[f]&&"string"==typeof q?q:"",g.options.cardView?"</div>":"</td>"].join(""),b[g.header.stateField]=q===!0||q&&q.checked):(q="undefined"==typeof q||null===q?g.options.undefinedText:q,o=g.options.cardView?['<div class="card-view">',g.options.showHeader?c('<span class="title" %s>%s</span>',k,d(g.columns,"field","title",n)):"",c('<span class="value">%s</span>',q),"</div>"].join(""):[c("<td%s %s %s %s %s %s %s>",t,u,k,v,w,x,y),q,"</td>"].join(""),g.options.cardView&&g.options.smartDisplay&&""===q&&(o='<div class="card-view"></div>')),i.push(o)}}),this.options.cardView&&i.push("</div></td>"),i.push("</tr>"),i.join(" ")}},o.prototype.initBody=function(b){var d=this,f=this.getData();this.trigger("pre-body",f),this.$body=this.$el.find(">tbody"),this.$body.length||(this.$body=a("<tbody></tbody>").appendTo(this.$el)),this.options.pagination&&"server"!==this.options.sidePagination||(this.pageFrom=1,this.pageTo=f.length);for(var g,i=a(document.createDocumentFragment()),j=this.pageFrom-1;j<this.pageTo;j++){
var k=f[j],m=this.initRow(k,j,f,i);g=g||!!m,m&&m!==!0&&i.append(m)}g||i.append('<tr class="no-records-found">'+c('<td colspan="%s">%s</td>',this.$header.find("th").length,this.options.formatNoMatches())+"</tr>"),this.$body.html(i),b||this.scrollTo(0),this.$body.find("> tr[data-index] > td").off("click dblclick").on("click dblclick",function(b){var f=a(this),g=f.parent(),h=d.data[g.data("index")],i=f[0].cellIndex,j=d.getVisibleFields(),k=j[d.options.detailView&&!d.options.cardView?i-1:i],m=d.columns[e(d.columns,k)],n=l(h,k,d.options.escape);if(!f.find(".detail-icon").length&&(d.trigger("click"===b.type?"click-cell":"dbl-click-cell",k,n,h,f),d.trigger("click"===b.type?"click-row":"dbl-click-row",h,g,k),"click"===b.type&&d.options.clickToSelect&&m.clickToSelect)){var o=g.find(c('[name="%s"]',d.options.selectItemName));o.length&&o[0].click()}}),this.$body.find("> tr[data-index] > td > .detail-icon").off("click").on("click",function(){var b=a(this),e=b.parent().parent(),g=e.data("index"),i=f[g];if(e.next().is("tr.detail-view"))b.find("i").attr("class",c("%s %s",d.options.iconsPrefix,d.options.icons.detailOpen)),d.trigger("collapse-row",g,i),e.next().remove();else{b.find("i").attr("class",c("%s %s",d.options.iconsPrefix,d.options.icons.detailClose)),e.after(c('<tr class="detail-view"><td colspan="%s"></td></tr>',e.find("td").length));var j=e.next().find("td"),k=h(d.options,d.options.detailFormatter,[g,i,j],"");1===j.length&&j.append(k),d.trigger("expand-row",g,i,j)}return d.resetView(),!1}),this.$selectItem=this.$body.find(c('[name="%s"]',this.options.selectItemName)),this.$selectItem.off("click").on("click",function(b){b.stopImmediatePropagation();var c=a(this),e=c.prop("checked"),f=d.data[c.data("index")];d.options.maintainSelected&&a(this).is(":radio")&&a.each(d.options.data,function(a,b){b[d.header.stateField]=!1}),f[d.header.stateField]=e,d.options.singleSelect&&(d.$selectItem.not(this).each(function(){d.data[a(this).data("index")][d.header.stateField]=!1}),d.$selectItem.filter(":checked").not(this).prop("checked",!1)),d.updateSelected(),d.trigger(e?"check":"uncheck",f,c)}),a.each(this.header.events,function(b,c){if(c){"string"==typeof c&&(c=h(null,c));var e=d.header.fields[b],f=a.inArray(e,d.getVisibleFields());d.options.detailView&&!d.options.cardView&&(f+=1);for(var g in c)d.$body.find(">tr:not(.no-records-found)").each(function(){var b=a(this),h=b.find(d.options.cardView?".card-view":"td").eq(f),i=g.indexOf(" "),j=g.substring(0,i),k=g.substring(i+1),l=c[g];h.find(k).off(j).on(j,function(a){var c=b.data("index"),f=d.data[c],g=f[e];l.apply(this,[a,g,f,c])})})}}),this.updateSelected(),this.resetView(),this.trigger("post-body",f)},o.prototype.initServer=function(b,c,d){var e,f=this,g={},i={searchText:this.searchText,sortName:this.options.sortName,sortOrder:this.options.sortOrder};this.options.pagination&&(i.pageSize=this.options.pageSize===this.options.formatAllRows()?this.options.totalRows:this.options.pageSize,i.pageNumber=this.options.pageNumber),(d||this.options.url||this.options.ajax)&&("limit"===this.options.queryParamsType&&(i={search:i.searchText,sort:i.sortName,order:i.sortOrder},this.options.pagination&&(i.offset=this.options.pageSize===this.options.formatAllRows()?0:this.options.pageSize*(this.options.pageNumber-1),i.limit=this.options.pageSize===this.options.formatAllRows()?this.options.totalRows:this.options.pageSize)),a.isEmptyObject(this.filterColumnsPartial)||(i.filter=JSON.stringify(this.filterColumnsPartial,null)),g=h(this.options,this.options.queryParams,[i],g),a.extend(g,c||{}),g!==!1&&(b||this.$tableLoading.show(),e=a.extend({},h(null,this.options.ajaxOptions),{type:this.options.method,url:d||this.options.url,data:"application/json"===this.options.contentType&&"post"===this.options.method?JSON.stringify(g):g,cache:this.options.cache,contentType:this.options.contentType,dataType:this.options.dataType,success:function(a){a=h(f.options,f.options.responseHandler,[a],a),f.load(a),f.trigger("load-success",a),b||f.$tableLoading.hide()},error:function(a){f.trigger("load-error",a.status,a),b||f.$tableLoading.hide()}}),this.options.ajax?h(this,this.options.ajax,[e],null):(this._xhr&&4!==this._xhr.readyState&&this._xhr.abort(),this._xhr=a.ajax(e))))},o.prototype.initSearchText=function(){if(this.options.search&&""!==this.options.searchText){var a=this.$toolbar.find(".search input");a.val(this.options.searchText),this.onSearch({currentTarget:a})}},o.prototype.getCaret=function(){var b=this;a.each(this.$header.find("th"),function(c,d){a(d).find(".sortable").removeClass("desc asc").addClass(a(d).data("field")===b.options.sortName?b.options.sortOrder:"both")})},o.prototype.updateSelected=function(){var b=this.$selectItem.filter(":enabled").length&&this.$selectItem.filter(":enabled").length===this.$selectItem.filter(":enabled").filter(":checked").length;this.$selectAll.add(this.$selectAll_).prop("checked",b),this.$selectItem.each(function(){a(this).closest("tr")[a(this).prop("checked")?"addClass":"removeClass"]("selected")})},o.prototype.updateRows=function(){var b=this;this.$selectItem.each(function(){b.data[a(this).data("index")][b.header.stateField]=a(this).prop("checked")})},o.prototype.resetRows=function(){var b=this;a.each(this.data,function(a,c){b.$selectAll.prop("checked",!1),b.$selectItem.prop("checked",!1),b.header.stateField&&(c[b.header.stateField]=!1)}),this.initHiddenRows()},o.prototype.trigger=function(b){var c=Array.prototype.slice.call(arguments,1);b+=".bs.table",this.options[o.EVENTS[b]].apply(this.options,c),this.$el.trigger(a.Event(b),c),this.options.onAll(b,c),this.$el.trigger(a.Event("all.bs.table"),[b,c])},o.prototype.resetHeader=function(){clearTimeout(this.timeoutId_),this.timeoutId_=setTimeout(a.proxy(this.fitHeader,this),this.$el.is(":hidden")?100:0)},o.prototype.fitHeader=function(){var b,d,e,f,h=this;if(h.$el.is(":hidden"))return void(h.timeoutId_=setTimeout(a.proxy(h.fitHeader,h),100));if(b=this.$tableBody.get(0),d=b.scrollWidth>b.clientWidth&&b.scrollHeight>b.clientHeight+this.$header.outerHeight()?g():0,this.$el.css("margin-top",-this.$header.outerHeight()),e=a(":focus"),e.length>0){var i=e.parents("th");if(i.length>0){var j=i.attr("data-field");if(void 0!==j){var k=this.$header.find("[data-field='"+j+"']");k.length>0&&k.find(":input").addClass("focus-temp")}}}this.$header_=this.$header.clone(!0,!0),this.$selectAll_=this.$header_.find('[name="btSelectAll"]'),this.$tableHeader.css({"margin-right":d}).find("table").css("width",this.$el.outerWidth()).html("").attr("class",this.$el.attr("class")).append(this.$header_),f=a(".focus-temp:visible:eq(0)"),f.length>0&&(f.focus(),this.$header.find(".focus-temp").removeClass("focus-temp")),this.$header.find("th[data-field]").each(function(){h.$header_.find(c('th[data-field="%s"]',a(this).data("field"))).data(a(this).data())});var l=this.getVisibleFields(),m=this.$header_.find("th");this.$body.find(">tr:first-child:not(.no-records-found) > *").each(function(b){var d=a(this),e=b;h.options.detailView&&!h.options.cardView&&(0===b&&h.$header_.find("th.detail").find(".fht-cell").width(d.innerWidth()),e=b-1);var f=h.$header_.find(c('th[data-field="%s"]',l[e]));f.length>1&&(f=a(m[d[0].cellIndex])),f.find(".fht-cell").width(d.innerWidth())}),this.$tableBody.off("scroll").on("scroll",function(){h.$tableHeader.scrollLeft(a(this).scrollLeft()),h.options.showFooter&&!h.options.cardView&&h.$tableFooter.scrollLeft(a(this).scrollLeft())}),h.trigger("post-header")},o.prototype.resetFooter=function(){var b=this,d=b.getData(),e=[];this.options.showFooter&&!this.options.cardView&&(!this.options.cardView&&this.options.detailView&&e.push('<td><div class="th-inner">&nbsp;</div><div class="fht-cell"></div></td>'),a.each(this.columns,function(a,f){var g,i="",j="",k=[],l={},m=c(' class="%s"',f["class"]);if(f.visible&&(!b.options.cardView||f.cardVisible)){if(i=c("text-align: %s; ",f.falign?f.falign:f.align),j=c("vertical-align: %s; ",f.valign),l=h(null,b.options.footerStyle),l&&l.css)for(g in l.css)k.push(g+": "+l.css[g]);e.push("<td",m,c(' style="%s"',i+j+k.concat().join("; ")),">"),e.push('<div class="th-inner">'),e.push(h(f,f.footerFormatter,[d],"&nbsp;")||"&nbsp;"),e.push("</div>"),e.push('<div class="fht-cell"></div>'),e.push("</div>"),e.push("</td>")}}),this.$tableFooter.find("tr").html(e.join("")),this.$tableFooter.show(),clearTimeout(this.timeoutFooter_),this.timeoutFooter_=setTimeout(a.proxy(this.fitFooter,this),this.$el.is(":hidden")?100:0))},o.prototype.fitFooter=function(){var b,c,d;return clearTimeout(this.timeoutFooter_),this.$el.is(":hidden")?void(this.timeoutFooter_=setTimeout(a.proxy(this.fitFooter,this),100)):(c=this.$el.css("width"),d=c>this.$tableBody.width()?g():0,this.$tableFooter.css({"margin-right":d}).find("table").css("width",c).attr("class",this.$el.attr("class")),b=this.$tableFooter.find("td"),void this.$body.find(">tr:first-child:not(.no-records-found) > *").each(function(c){var d=a(this);b.eq(c).find(".fht-cell").width(d.innerWidth())}))},o.prototype.toggleColumn=function(a,b,d){if(-1!==a&&(this.columns[a].visible=b,this.initHeader(),this.initSearch(),this.initPagination(),this.initBody(),this.options.showColumns)){var e=this.$toolbar.find(".keep-open input").prop("disabled",!1);d&&e.filter(c('[value="%s"]',a)).prop("checked",b),e.filter(":checked").length<=this.options.minimumCountColumns&&e.filter(":checked").prop("disabled",!0)}},o.prototype.getVisibleFields=function(){var b=this,c=[];return a.each(this.header.fields,function(a,d){var f=b.columns[e(b.columns,d)];f.visible&&c.push(d)}),c},o.prototype.resetView=function(a){var b=0;if(a&&a.height&&(this.options.height=a.height),this.$selectAll.prop("checked",this.$selectItem.length>0&&this.$selectItem.length===this.$selectItem.filter(":checked").length),this.options.height){var c=this.$toolbar.outerHeight(!0),d=this.$pagination.outerHeight(!0),e=this.options.height-c-d;this.$tableContainer.css("height",e+"px")}return this.options.cardView?(this.$el.css("margin-top","0"),this.$tableContainer.css("padding-bottom","0"),void this.$tableFooter.hide()):(this.options.showHeader&&this.options.height?(this.$tableHeader.show(),this.resetHeader(),b+=this.$header.outerHeight()):(this.$tableHeader.hide(),this.trigger("post-header")),this.options.showFooter&&(this.resetFooter(),this.options.height&&(b+=this.$tableFooter.outerHeight()+1)),this.getCaret(),this.$tableContainer.css("padding-bottom",b+"px"),void this.trigger("reset-view"))},o.prototype.getData=function(b){return!this.searchText&&a.isEmptyObject(this.filterColumns)&&a.isEmptyObject(this.filterColumnsPartial)?b?this.options.data.slice(this.pageFrom-1,this.pageTo):this.options.data:b?this.data.slice(this.pageFrom-1,this.pageTo):this.data},o.prototype.load=function(b){var c=!1;"server"===this.options.sidePagination?(this.options.totalRows=b[this.options.totalField],c=b.fixedScroll,b=b[this.options.dataField]):a.isArray(b)||(c=b.fixedScroll,b=b.data),this.initData(b),this.initSearch(),this.initPagination(),this.initBody(c)},o.prototype.append=function(a){this.initData(a,"append"),this.initSearch(),this.initPagination(),this.initSort(),this.initBody(!0)},o.prototype.prepend=function(a){this.initData(a,"prepend"),this.initSearch(),this.initPagination(),this.initSort(),this.initBody(!0)},o.prototype.remove=function(b){var c,d,e=this.options.data.length;if(b.hasOwnProperty("field")&&b.hasOwnProperty("values")){for(c=e-1;c>=0;c--)d=this.options.data[c],d.hasOwnProperty(b.field)&&-1!==a.inArray(d[b.field],b.values)&&(this.options.data.splice(c,1),"server"===this.options.sidePagination&&(this.options.totalRows-=1));e!==this.options.data.length&&(this.initSearch(),this.initPagination(),this.initSort(),this.initBody(!0))}},o.prototype.removeAll=function(){this.options.data.length>0&&(this.options.data.splice(0,this.options.data.length),this.initSearch(),this.initPagination(),this.initBody(!0))},o.prototype.getRowByUniqueId=function(a){var b,c,d,e=this.options.uniqueId,f=this.options.data.length,g=null;for(b=f-1;b>=0;b--){if(c=this.options.data[b],c.hasOwnProperty(e))d=c[e];else{if(!c._data.hasOwnProperty(e))continue;d=c._data[e]}if("string"==typeof d?a=a.toString():"number"==typeof d&&(Number(d)===d&&d%1===0?a=parseInt(a):d===Number(d)&&0!==d&&(a=parseFloat(a))),d===a){g=c;break}}return g},o.prototype.removeByUniqueId=function(a){var b=this.options.data.length,c=this.getRowByUniqueId(a);c&&this.options.data.splice(this.options.data.indexOf(c),1),b!==this.options.data.length&&(this.initSearch(),this.initPagination(),this.initBody(!0))},o.prototype.updateByUniqueId=function(b){var c=this,d=a.isArray(b)?b:[b];a.each(d,function(b,d){var e;d.hasOwnProperty("id")&&d.hasOwnProperty("row")&&(e=a.inArray(c.getRowByUniqueId(d.id),c.options.data),-1!==e&&a.extend(c.options.data[e],d.row))}),this.initSearch(),this.initPagination(),this.initSort(),this.initBody(!0)},o.prototype.insertRow=function(a){a.hasOwnProperty("index")&&a.hasOwnProperty("row")&&(this.data.splice(a.index,0,a.row),this.initSearch(),this.initPagination(),this.initSort(),this.initBody(!0))},o.prototype.updateRow=function(b){var c=this,d=a.isArray(b)?b:[b];a.each(d,function(b,d){d.hasOwnProperty("index")&&d.hasOwnProperty("row")&&a.extend(c.options.data[d.index],d.row)}),this.initSearch(),this.initPagination(),this.initSort(),this.initBody(!0)},o.prototype.initHiddenRows=function(){this.hiddenRows=[]},o.prototype.showRow=function(a){this.toggleRow(a,!0)},o.prototype.hideRow=function(a){this.toggleRow(a,!1)},o.prototype.toggleRow=function(b,c){var d,e;b.hasOwnProperty("index")?d=this.getData()[b.index]:b.hasOwnProperty("uniqueId")&&(d=this.getRowByUniqueId(b.uniqueId)),d&&(e=a.inArray(d,this.hiddenRows),c||-1!==e?c&&e>-1&&this.hiddenRows.splice(e,1):this.hiddenRows.push(d),this.initBody(!0))},o.prototype.getHiddenRows=function(){var b=this,c=this.getData(),d=[];return a.each(c,function(c,e){a.inArray(e,b.hiddenRows)>-1&&d.push(e)}),this.hiddenRows=d,d},o.prototype.mergeCells=function(b){var c,d,e,f=b.index,g=a.inArray(b.field,this.getVisibleFields()),h=b.rowspan||1,i=b.colspan||1,j=this.$body.find(">tr");if(this.options.detailView&&!this.options.cardView&&(g+=1),e=j.eq(f).find(">td").eq(g),!(0>f||0>g||f>=this.data.length)){for(c=f;f+h>c;c++)for(d=g;g+i>d;d++)j.eq(c).find(">td").eq(d).hide();e.attr("rowspan",h).attr("colspan",i).show()}},o.prototype.updateCell=function(a){a.hasOwnProperty("index")&&a.hasOwnProperty("field")&&a.hasOwnProperty("value")&&(this.data[a.index][a.field]=a.value,a.reinit!==!1&&(this.initSort(),this.initBody(!0)))},o.prototype.getOptions=function(){return this.options},o.prototype.getSelections=function(){var b=this;return a.grep(this.options.data,function(a){return a[b.header.stateField]===!0})},o.prototype.getAllSelections=function(){var b=this;return a.grep(this.options.data,function(a){return a[b.header.stateField]})},o.prototype.checkAll=function(){this.checkAll_(!0)},o.prototype.uncheckAll=function(){this.checkAll_(!1)},o.prototype.checkInvert=function(){var b=this,c=b.$selectItem.filter(":enabled"),d=c.filter(":checked");c.each(function(){a(this).prop("checked",!a(this).prop("checked"))}),b.updateRows(),b.updateSelected(),b.trigger("uncheck-some",d),d=b.getSelections(),b.trigger("check-some",d)},o.prototype.checkAll_=function(a){var b;a||(b=this.getSelections()),this.$selectAll.add(this.$selectAll_).prop("checked",a),this.$selectItem.filter(":enabled").prop("checked",a),this.updateRows(),a&&(b=this.getSelections()),this.trigger(a?"check-all":"uncheck-all",b)},o.prototype.check=function(a){this.check_(!0,a)},o.prototype.uncheck=function(a){this.check_(!1,a)},o.prototype.check_=function(a,b){var d=this.$selectItem.filter(c('[data-index="%s"]',b)).prop("checked",a);this.data[b][this.header.stateField]=a,this.updateSelected(),this.trigger(a?"check":"uncheck",this.data[b],d)},o.prototype.checkBy=function(a){this.checkBy_(!0,a)},o.prototype.uncheckBy=function(a){this.checkBy_(!1,a)},o.prototype.checkBy_=function(b,d){if(d.hasOwnProperty("field")&&d.hasOwnProperty("values")){var e=this,f=[];a.each(this.options.data,function(g,h){if(!h.hasOwnProperty(d.field))return!1;if(-1!==a.inArray(h[d.field],d.values)){var i=e.$selectItem.filter(":enabled").filter(c('[data-index="%s"]',g)).prop("checked",b);h[e.header.stateField]=b,f.push(h),e.trigger(b?"check":"uncheck",h,i)}}),this.updateSelected(),this.trigger(b?"check-some":"uncheck-some",f)}},o.prototype.destroy=function(){this.$el.insertBefore(this.$container),a(this.options.toolbar).insertBefore(this.$el),this.$container.next().remove(),this.$container.remove(),this.$el.html(this.$el_.html()).css("margin-top","0").attr("class",this.$el_.attr("class")||"")},o.prototype.showLoading=function(){this.$tableLoading.show()},o.prototype.hideLoading=function(){this.$tableLoading.hide()},o.prototype.togglePagination=function(){this.options.pagination=!this.options.pagination;var a=this.$toolbar.find('button[name="paginationSwitch"] i');this.options.pagination?a.attr("class",this.options.iconsPrefix+" "+this.options.icons.paginationSwitchDown):a.attr("class",this.options.iconsPrefix+" "+this.options.icons.paginationSwitchUp),this.updatePagination()},o.prototype.refresh=function(a){a&&a.url&&(this.options.url=a.url),a&&a.pageNumber&&(this.options.pageNumber=a.pageNumber),a&&a.pageSize&&(this.options.pageSize=a.pageSize),this.initServer(a&&a.silent,a&&a.query,a&&a.url),this.trigger("refresh",a)},o.prototype.resetWidth=function(){this.options.showHeader&&this.options.height&&this.fitHeader(),this.options.showFooter&&this.fitFooter()},o.prototype.showColumn=function(a){this.toggleColumn(e(this.columns,a),!0,!0)},o.prototype.hideColumn=function(a){this.toggleColumn(e(this.columns,a),!1,!0)},o.prototype.getHiddenColumns=function(){return a.grep(this.columns,function(a){return!a.visible})},o.prototype.getVisibleColumns=function(){return a.grep(this.columns,function(a){return a.visible})},o.prototype.toggleAllColumns=function(b){if(a.each(this.columns,function(a){this.columns[a].visible=b}),this.initHeader(),this.initSearch(),this.initPagination(),this.initBody(),this.options.showColumns){var c=this.$toolbar.find(".keep-open input").prop("disabled",!1);c.filter(":checked").length<=this.options.minimumCountColumns&&c.filter(":checked").prop("disabled",!0)}},o.prototype.showAllColumns=function(){this.toggleAllColumns(!0)},o.prototype.hideAllColumns=function(){this.toggleAllColumns(!1)},o.prototype.filterBy=function(b){this.filterColumns=a.isEmptyObject(b)?{}:b,this.options.pageNumber=1,this.initSearch(),this.updatePagination()},o.prototype.scrollTo=function(a){return"string"==typeof a&&(a="bottom"===a?this.$tableBody[0].scrollHeight:0),"number"==typeof a&&this.$tableBody.scrollTop(a),"undefined"==typeof a?this.$tableBody.scrollTop():void 0},o.prototype.getScrollPosition=function(){return this.scrollTo()},o.prototype.selectPage=function(a){a>0&&a<=this.options.totalPages&&(this.options.pageNumber=a,this.updatePagination())},o.prototype.prevPage=function(){this.options.pageNumber>1&&(this.options.pageNumber--,this.updatePagination())},o.prototype.nextPage=function(){this.options.pageNumber<this.options.totalPages&&(this.options.pageNumber++,this.updatePagination())},o.prototype.toggleView=function(){this.options.cardView=!this.options.cardView,this.initHeader(),this.initBody(),this.trigger("toggle",this.options.cardView)},o.prototype.refreshOptions=function(b){i(this.options,b,!0)||(this.options=a.extend(this.options,b),this.trigger("refresh-options",this.options),this.destroy(),this.init())},o.prototype.resetSearch=function(a){var b=this.$toolbar.find(".search input");b.val(a||""),this.onSearch({currentTarget:b})},o.prototype.expandRow_=function(a,b){var d=this.$body.find(c('> tr[data-index="%s"]',b));d.next().is("tr.detail-view")===(a?!1:!0)&&d.find("> td > .detail-icon").click()},o.prototype.expandRow=function(a){this.expandRow_(!0,a)},o.prototype.collapseRow=function(a){this.expandRow_(!1,a)},o.prototype.expandAllRows=function(b){if(b){var d=this.$body.find(c('> tr[data-index="%s"]',0)),e=this,f=null,g=!1,h=-1;if(d.next().is("tr.detail-view")?d.next().next().is("tr.detail-view")||(d.next().find(".detail-icon").click(),g=!0):(d.find("> td > .detail-icon").click(),g=!0),g)try{h=setInterval(function(){f=e.$body.find("tr.detail-view").last().find(".detail-icon"),f.length>0?f.click():clearInterval(h)},1)}catch(i){clearInterval(h)}}else for(var j=this.$body.children(),k=0;k<j.length;k++)this.expandRow_(!0,a(j[k]).data("index"))},o.prototype.collapseAllRows=function(b){if(b)this.expandRow_(!1,0);else for(var c=this.$body.children(),d=0;d<c.length;d++)this.expandRow_(!1,a(c[d]).data("index"))},o.prototype.updateFormatText=function(a,b){this.options[c("format%s",a)]&&("string"==typeof b?this.options[c("format%s",a)]=function(){return b}:"function"==typeof b&&(this.options[c("format%s",a)]=b)),this.initToolbar(),this.initPagination(),this.initBody()};var p=["getOptions","getSelections","getAllSelections","getData","load","append","prepend","remove","removeAll","insertRow","updateRow","updateCell","updateByUniqueId","removeByUniqueId","getRowByUniqueId","showRow","hideRow","getHiddenRows","mergeCells","checkAll","uncheckAll","checkInvert","check","uncheck","checkBy","uncheckBy","refresh","resetView","resetWidth","destroy","showLoading","hideLoading","showColumn","hideColumn","getHiddenColumns","getVisibleColumns","showAllColumns","hideAllColumns","filterBy","scrollTo","getScrollPosition","selectPage","prevPage","nextPage","togglePagination","toggleView","refreshOptions","resetSearch","expandRow","collapseRow","expandAllRows","collapseAllRows","updateFormatText"];a.fn.bootstrapTable=function(b){var c,d=Array.prototype.slice.call(arguments,1);return this.each(function(){var e=a(this),f=e.data("bootstrap.table"),g=a.extend({},o.DEFAULTS,e.data(),"object"==typeof b&&b);if("string"==typeof b){if(a.inArray(b,p)<0)throw new Error("Unknown method: "+b);if(!f)return;c=f[b].apply(f,d),"destroy"===b&&e.removeData("bootstrap.table")}f||e.data("bootstrap.table",f=new o(this,g))}),"undefined"==typeof c?this:c},a.fn.bootstrapTable.Constructor=o,a.fn.bootstrapTable.defaults=o.DEFAULTS,a.fn.bootstrapTable.columnDefaults=o.COLUMN_DEFAULTS,a.fn.bootstrapTable.locales=o.LOCALES,a.fn.bootstrapTable.methods=p,a.fn.bootstrapTable.utils={sprintf:c,getFieldIndex:e,compareObjects:i,calculateObjectValue:h,getItemField:l,objectKeys:n,isIEBrowser:m},a(function(){a('[data-toggle="table"]').bootstrapTable()})}(jQuery);
define("bootstrap-table", ["bootstrap"], (function (global) {
    return function () {
        var ret, fn;
        return ret || global.$.fn.bootstrapTable;
    };
}(this)));

/**
 * Bootstrap Table Chinese translation
 * Author: Zhixin Wen<wenzhixin2010@gmail.com>
 */
(function ($) {
    'use strict';

    $.fn.bootstrapTable.locales['zh-CN'] = {
        formatLoadingMessage: function () {
            return '正在努力地加载数据中，请稍候……';
        },
        formatRecordsPerPage: function (pageNumber) {
            return '每页显示 ' + pageNumber + ' 条记录';
        },
        formatShowingRows: function (pageFrom, pageTo, totalRows) {
            return '显示第 ' + pageFrom + ' 到第 ' + pageTo + ' 条记录，总共 ' + totalRows + ' 条记录';
        },
        formatSearch: function () {
            return '搜索';
        },
        formatNoMatches: function () {
            return '没有找到匹配的记录';
        },
        formatPaginationSwitch: function () {
            return '隐藏/显示分页';
        },
        formatRefresh: function () {
            return '刷新';
        },
        formatToggle: function () {
            return '切换';
        },
        formatColumns: function () {
            return '列';
        },
        formatExport: function () {
            return '导出数据';
        },
        formatClearFilters: function () {
            return '清空过滤';
        }
    };

    $.extend($.fn.bootstrapTable.defaults, $.fn.bootstrapTable.locales['zh-CN']);

})(jQuery);

define("bootstrap-table-lang", ["bootstrap-table"], (function (global) {
    return function () {
        var ret, fn;
        return ret || global.$.fn.bootstrapTable.defaults;
    };
}(this)));

/**
 * @author: Dennis Hernández
 * @webSite: http://djhvscf.github.io/Blog
 * @version: v1.1.0
 */

!function ($) {

    'use strict';

    var showHideColumns = function (that, checked) {
        if (that.options.columnsHidden.length > 0 ) {
            $.each(that.columns, function (i, column) {
                if (that.options.columnsHidden.indexOf(column.field) !== -1) {
                    if (column.visible !== checked) {
                        that.toggleColumn($.fn.bootstrapTable.utils.getFieldIndex(that.columns, column.field), checked, true);
                    }
                }
            });
        }
    };

    var resetView = function (that) {
        if (that.options.height || that.options.showFooter) {
            setTimeout(function(){
                that.resetView.call(that);
            }, 1);
        }
    };

    var changeView = function (that, width, height) {
        if (that.options.minHeight) {
            if ((width <= that.options.minWidth) && (height <= that.options.minHeight)) {
                conditionCardView(that);
            } else if ((width > that.options.minWidth) && (height > that.options.minHeight)) {
                conditionFullView(that);
            }
        } else {
            if (width <= that.options.minWidth) {
                conditionCardView(that);
            } else if (width > that.options.minWidth) {
                conditionFullView(that);
            }
        }

        resetView(that);
    };

    var conditionCardView = function (that) {
        changeTableView(that, false);
        showHideColumns(that, false);
    };

    var conditionFullView = function (that) {
        changeTableView(that, true);
        showHideColumns(that, true);
    };

    var changeTableView = function (that, cardViewState) {
        that.options.cardView = cardViewState;
        that.toggleView();
    };

    var debounce = function(func,wait) {
        var timeout;
        return function() {
            var context = this,
                args = arguments;
            var later = function() {
                timeout = null;
                func.apply(context,args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    };

    $.extend($.fn.bootstrapTable.defaults, {
        mobileResponsive: false,
        minWidth: 562,
        minHeight: undefined,
        heightThreshold: 100, // just slightly larger than mobile chrome's auto-hiding toolbar
        checkOnInit: true,
        columnsHidden: []
    });

    var BootstrapTable = $.fn.bootstrapTable.Constructor,
        _init = BootstrapTable.prototype.init;

    BootstrapTable.prototype.init = function () {
        _init.apply(this, Array.prototype.slice.apply(arguments));

        if (!this.options.mobileResponsive) {
            return;
        }

        if (!this.options.minWidth) {
            return;
        }

        if (this.options.minWidth < 100 && this.options.resizable) {
            console.log("The minWidth when the resizable extension is active should be greater or equal than 100");
            this.options.minWidth = 100;
        }

        var that = this,
            old = {
                width: $(window).width(),
                height: $(window).height()
            };

        $(window).on('resize orientationchange',debounce(function (evt) {
            // reset view if height has only changed by at least the threshold.
            var height = $(this).height(),
                width = $(this).width();

            if (Math.abs(old.height - height) > that.options.heightThreshold || old.width != width) {
                changeView(that, width, height);
                old = {
                    width: width,
                    height: height
                };
            }
        },200));

        if (this.options.checkOnInit) {
            var height = $(window).height(),
                width = $(window).width();
            changeView(this, width, height);
            old = {
                width: width,
                height: height
            };
        }
    };
}(jQuery);

define("bootstrap-table-mobile", ["bootstrap-table"], (function (global) {
    return function () {
        var ret, fn;
        return ret || global.$.fn.bootstrapTable.defaults;
    };
}(this)));

/*
 tableExport.jquery.plugin

 Copyright (c) 2015-2017 hhurz, https://github.com/hhurz/
 Multi Worksheets Support, Copyright (c) 2017 hejiheji001, https://github.com/hejiheji001/
 Original Work, Copyright (c) 2014 Giri Raj, https://github.com/kayalshri/

 Licensed under the MIT License, http://opensource.org/licenses/mit-license
*/
(function(c){c.fn.extend({tableExport:function(n){function N(b){var a=[];c(b).find("thead").first().find("th").each(function(b,f){void 0!==c(f).attr("data-field")?a[b]=c(f).attr("data-field"):a[b]=b.toString()});return a}function x(b,m,e,f,z){if(-1==c.inArray(e,a.ignoreRow)&&-1==c.inArray(e-f,a.ignoreRow)){var F=c(b).filter(function(){return"none"!=c(this).data("tableexport-display")&&(c(this).is(":visible")||"always"==c(this).data("tableexport-display")||"always"==c(this).closest("table").data("tableexport-display"))}).find(m),
l=0;F.each(function(b){if(("always"==c(this).data("tableexport-display")||"none"!=c(this).css("display")&&"hidden"!=c(this).css("visibility")&&"none"!=c(this).data("tableexport-display"))&&"function"===typeof z){var f,m=1,r=1;var d=F.length;if("undefined"!=typeof y[e]&&0<y[e].length){var g=b;for(f=0;f<=g;f++)"undefined"!=typeof y[e][f]&&(z(null,e,f),delete y[e][f],g++);b+=y[e].length;d+=y[e].length}c(this).is("[colspan]")&&(m=parseInt(c(this).attr("colspan"))||1,l+=0<m?m-1:0);c(this).is("[rowspan]")&&
(r=parseInt(c(this).attr("rowspan"))||1);f=d;d=b+l;g=!1;0<a.ignoreColumn.length&&("string"==typeof a.ignoreColumn[0]?I.length>d&&"undefined"!=typeof I[d]&&-1!=c.inArray(I[d],a.ignoreColumn)&&(g=!0):"number"!=typeof a.ignoreColumn[0]||-1==c.inArray(d,a.ignoreColumn)&&-1==c.inArray(d-f,a.ignoreColumn)||(g=!0));if(!1===g)for(z(this,e,b),f=1;f<m;f++)z(null,e,b+f);if(1<r)for(d=1;d<r;d++)for("undefined"==typeof y[e+d]&&(y[e+d]=[]),y[e+d][b+l]="",f=1;f<m;f++)y[e+d][b+l-f]=""}});if("undefined"!=typeof y[e]&&
0<y[e].length)for(b=0;b<=y[e].length;b++)"undefined"!=typeof y[e][b]&&(z(null,e,b),delete y[e][b])}}function aa(b,m){!0===a.consoleLog&&console.log(b.output());if("string"===a.outputMode)return b.output();if("base64"===a.outputMode)return D(b.output());if("window"===a.outputMode)window.open(URL.createObjectURL(b.output("blob")));else try{var e=b.output("blob");saveAs(e,a.fileName+".pdf")}catch(f){A(a.fileName+".pdf","data:application/pdf"+(m?"":";base64")+",",m?e:b.output())}}function ba(b,a,e){var f=
0;"undefined"!=typeof e&&(f=e.colspan);if(0<=f){for(var m=b.width,c=b.textPos.x,l=a.table.columns.indexOf(a.column),r=1;r<f;r++)m+=a.table.columns[l+r].width;1<f&&("right"===b.styles.halign?c=b.textPos.x+m-b.width:"center"===b.styles.halign&&(c=b.textPos.x+(m-b.width)/2));b.width=m;b.textPos.x=c;"undefined"!=typeof e&&1<e.rowspan&&(b.height*=e.rowspan);if("middle"===b.styles.valign||"bottom"===b.styles.valign)e=("string"===typeof b.text?b.text.split(/\r\n|\r|\n/g):b.text).length||1,2<e&&(b.textPos.y-=
(2-1.15)/2*a.row.styles.fontSize*(e-2)/3);return!0}return!1}function ca(b,a,e){"undefined"!=typeof e.images&&a.each(function(){var a=c(this).children();if(c(this).is("img")){var m=da(this.src);e.images[m]={url:this.src,src:this.src}}"undefined"!=typeof a&&0<a.length&&ca(b,a,e)})}function ma(b,a){function e(b){if(b.url){var f=new Image;m=++c;f.crossOrigin="Anonymous";f.onerror=f.onload=function(){if(f.complete&&(0===f.src.indexOf("data:image/")&&(f.width=b.width||f.width||0,f.height=b.height||f.height||
0),f.width+f.height)){var e=document.createElement("canvas"),l=e.getContext("2d");e.width=f.width;e.height=f.height;l.drawImage(f,0,0);b.src=e.toDataURL("image/jpeg")}--c||a(m)};f.src=b.url}}var f,m=0,c=0;if("undefined"!=typeof b.images)for(f in b.images)b.images.hasOwnProperty(f)&&e(b.images[f]);(f=c)||(a(m),f=void 0);return f}function ea(b,m,e){m.each(function(){var f=c(this).children();if(c(this).is("div")){var m=O(E(this,"background-color"),[255,255,255]),F=O(E(this,"border-top-color"),[0,0,0]),
l=P(this,"border-top-width",a.jspdf.unit),r=this.getBoundingClientRect(),d=this.offsetLeft*e.dw;var g=this.offsetTop*e.dh;var h=r.width*e.dw,r=r.height*e.dh;e.doc.setDrawColor.apply(void 0,F);e.doc.setFillColor.apply(void 0,m);e.doc.setLineWidth(l);e.doc.rect(b.x+d,b.y+g,h,r,l?"FD":"F")}else if(c(this).is("img")&&"undefined"!=typeof e.images&&(g=da(this.src),m=e.images[g],"undefined"!=typeof m)){F=b.width/b.height;l=this.width/this.height;d=b.width;h=b.height;r=19.049976/25.4;g=0;l<=F?(h=Math.min(b.height,
this.height),d=this.width*h/this.height):l>F&&(d=Math.min(b.width,this.width),h=this.height*d/this.width);d*=r;h*=r;h<b.height&&(g=(b.height-h)/2);try{e.doc.addImage(m.src,b.textPos.x,b.y+g,d,h)}catch(qa){}b.textPos.x+=d}"undefined"!=typeof f&&0<f.length&&ea(b,f,e)})}function fa(b,a,e){if("function"===typeof e.onAutotableText)e.onAutotableText(e.doc,b,a);else{var f=b.textPos.x,m=b.textPos.y,d={halign:b.styles.halign,valign:b.styles.valign};if(a.length){for(a=a[0];a.previousSibling;)a=a.previousSibling;
for(var l=!1,r=!1;a;){var g=a.innerText||a.textContent||"",g=(g.length&&" "==g[0]?" ":"")+c.trim(g)+(1<g.length&&" "==g[g.length-1]?" ":"");c(a).is("br")&&(f=b.textPos.x,m+=e.doc.internal.getFontSize());c(a).is("b")?l=!0:c(a).is("i")&&(r=!0);(l||r)&&e.doc.setFontType(l&&r?"bolditalic":l?"bold":"italic");var h=e.doc.getStringUnitWidth(g)*e.doc.internal.getFontSize();if(h){if("linebreak"===b.styles.overflow&&f>b.textPos.x&&f+h>b.textPos.x+b.width){if(0<=".,!%*;:=-".indexOf(g.charAt(0))){var k=g.charAt(0),
h=e.doc.getStringUnitWidth(k)*e.doc.internal.getFontSize();f+h<=b.textPos.x+b.width&&(e.doc.autoTableText(k,f,m,d),g=g.substring(1,g.length));h=e.doc.getStringUnitWidth(g)*e.doc.internal.getFontSize()}f=b.textPos.x;m+=e.doc.internal.getFontSize()}for(;g.length&&f+h>b.textPos.x+b.width;)g=g.substring(0,g.length-1),h=e.doc.getStringUnitWidth(g)*e.doc.internal.getFontSize();e.doc.autoTableText(g,f,m,d);f+=h}if(l||r)c(a).is("b")?l=!1:c(a).is("i")&&(r=!1),e.doc.setFontType(l||r?l?"bold":"italic":"normal");
a=a.nextSibling}b.textPos.x=f;b.textPos.y=m}else e.doc.autoTableText(b.text,b.textPos.x,b.textPos.y,d)}}function Q(b,a,e){return b.replace(new RegExp(a.replace(/([.*+?^=!:${}()|\[\]\/\\])/g,"\\$1"),"g"),e)}function U(b){b=Q(b||"0",a.numbers.html.thousandsSeparator,"");b=Q(b,a.numbers.html.decimalMark,".");return"number"===typeof b||!1!==jQuery.isNumeric(b)?b:!1}function v(b,m,e){var f="";if(null!==b){var z=c(b);if(z[0].hasAttribute("data-tableexport-value"))var d=z.data("tableexport-value");else if(d=
z.html(),"function"===typeof a.onCellHtmlData)d=a.onCellHtmlData(z,m,e,d);else if(""!=d){b=c.parseHTML(d);var l=0,g=0;d="";c.each(b,function(){if(c(this).is("input"))d+=z.find("input").eq(l++).val();else if(c(this).is("select"))d+=z.find("select option:selected").eq(g++).text();else if("undefined"===typeof c(this).html())d+=c(this).text();else if(void 0===jQuery().bootstrapTable||!0!==c(this).hasClass("filterControl"))d+=c(this).html()})}if(!0===a.htmlContent)f=c.trim(d);else if(""!=d){var h=d.replace(/\n/g,
"\u2028").replace(/<br\s*[\/]?>/gi,"\u2060");b=c("<div/>").html(h).contents();h="";c.each(b.text().split("\u2028"),function(b,a){0<b&&(h+=" ");h+=c.trim(a)});c.each(h.split("\u2060"),function(b,a){0<b&&(f+="\n");f+=c.trim(a).replace(/\u00AD/g,"")});if("json"==a.type||"excel"===a.type&&"xmlss"===a.excelFileFormat||!1===a.numbers.output)b=U(f),!1!==b&&(f=Number(b));else if(a.numbers.html.decimalMark!=a.numbers.output.decimalMark||a.numbers.html.thousandsSeparator!=a.numbers.output.thousandsSeparator)if(b=
U(f),!1!==b){var k=(""+b).split(".");1==k.length&&(k[1]="");var n=3<k[0].length?k[0].length%3:0,f=(0>b?"-":"")+(a.numbers.output.thousandsSeparator?(n?k[0].substr(0,n)+a.numbers.output.thousandsSeparator:"")+k[0].substr(n).replace(/(\d{3})(?=\d)/g,"$1"+a.numbers.output.thousandsSeparator):k[0])+(k[1].length?a.numbers.output.decimalMark+k[1]:"")}}!0===a.escape&&(f=escape(f));"function"===typeof a.onCellData&&(f=a.onCellData(z,m,e,f))}return f}function na(b,a,e){return a+"-"+e.toLowerCase()}function O(a,
m){var b=/^rgb\((\d{1,3}),\s*(\d{1,3}),\s*(\d{1,3})\)$/.exec(a),f=m;b&&(f=[parseInt(b[1]),parseInt(b[2]),parseInt(b[3])]);return f}function ga(b){var a=E(b,"text-align"),e=E(b,"font-weight"),f=E(b,"font-style"),d="";"start"==a&&(a="rtl"==E(b,"direction")?"right":"left");700<=e&&(d="bold");"italic"==f&&(d+=f);""===d&&(d="normal");a={style:{align:a,bcolor:O(E(b,"background-color"),[255,255,255]),color:O(E(b,"color"),[0,0,0]),fstyle:d},colspan:parseInt(c(b).attr("colspan"))||0,rowspan:parseInt(c(b).attr("rowspan"))||
0};null!==b&&(b=b.getBoundingClientRect(),a.rect={width:b.width,height:b.height});return a}function E(a,c){try{return window.getComputedStyle?(c=c.replace(/([a-z])([A-Z])/,na),window.getComputedStyle(a,null).getPropertyValue(c)):a.currentStyle?a.currentStyle[c]:a.style[c]}catch(e){}return""}function P(a,c,e){c=E(a,c).match(/\d+/);if(null!==c){c=c[0];a=a.parentElement;var b=document.createElement("div");b.style.overflow="hidden";b.style.visibility="hidden";a.appendChild(b);b.style.width=100+e;e=100/
b.offsetWidth;a.removeChild(b);return c*e}return 0}function V(){if(!(this instanceof V))return new V;this.SheetNames=[];this.Sheets={}}function oa(a){for(var b=new ArrayBuffer(a.length),e=new Uint8Array(b),f=0;f!=a.length;++f)e[f]=a.charCodeAt(f)&255;return b}function pa(a){for(var b={},e={s:{c:1E7,r:1E7},e:{c:0,r:0}},f=0;f!=a.length;++f)for(var c=0;c!=a[f].length;++c){e.s.r>f&&(e.s.r=f);e.s.c>c&&(e.s.c=c);e.e.r<f&&(e.e.r=f);e.e.c<c&&(e.e.c=c);var d={v:a[f][c]};if(null!==d.v){var l=XLSX.utils.encode_cell({c:c,
r:f});if("number"===typeof d.v)d.t="n";else if("boolean"===typeof d.v)d.t="b";else if(d.v instanceof Date){d.t="n";d.z=XLSX.SSF._table[14];var g=d;var h=(Date.parse(d.v)-new Date(Date.UTC(1899,11,30)))/864E5;g.v=h}else d.t="s";b[l]=d}}1E7>e.s.c&&(b["!ref"]=XLSX.utils.encode_range(e));return b}function da(a){var b=0,c;if(0===a.length)return b;var f=0;for(c=a.length;f<c;f++){var d=a.charCodeAt(f);b=(b<<5)-b+d;b|=0}return b}function A(a,c,e){var b=window.navigator.userAgent;if(!1!==a&&(0<b.indexOf("MSIE ")||
b.match(/Trident.*rv\:11\./)))if(window.navigator.msSaveOrOpenBlob)window.navigator.msSaveOrOpenBlob(new Blob([e]),a);else{if(c=document.createElement("iframe"))document.body.appendChild(c),c.setAttribute("style","display:none"),c.contentDocument.open("txt/html","replace"),c.contentDocument.write(e),c.contentDocument.close(),c.focus(),c.contentDocument.execCommand("SaveAs",!0,a),document.body.removeChild(c)}else if(b=document.createElement("a")){var d=null;b.style.display="none";!1!==a?b.download=
a:b.target="_blank";"object"==typeof e?(d=window.URL.createObjectURL(e),b.href=d):0<=c.toLowerCase().indexOf("base64,")?b.href=c+D(e):b.href=c+encodeURIComponent(e);document.body.appendChild(b);if(document.createEvent)null===R&&(R=document.createEvent("MouseEvents")),R.initEvent("click",!0,!1),b.dispatchEvent(R);else if(document.createEventObject)b.fireEvent("onclick");else if("function"==typeof b.onclick)b.onclick();d&&window.URL.revokeObjectURL(d);document.body.removeChild(b)}}function D(a){var b=
"",c,f=0;a=a.replace(/\x0d\x0a/g,"\n");var d="";for(c=0;c<a.length;c++){var g=a.charCodeAt(c);128>g?d+=String.fromCharCode(g):(127<g&&2048>g?d+=String.fromCharCode(g>>6|192):(d+=String.fromCharCode(g>>12|224),d+=String.fromCharCode(g>>6&63|128)),d+=String.fromCharCode(g&63|128))}for(a=d;f<a.length;){var l=a.charCodeAt(f++);d=a.charCodeAt(f++);c=a.charCodeAt(f++);g=l>>2;l=(l&3)<<4|d>>4;var r=(d&15)<<2|c>>6;var h=c&63;isNaN(d)?r=h=64:isNaN(c)&&(h=64);b=b+"ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=".charAt(g)+
"ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=".charAt(l)+"ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=".charAt(r)+"ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=".charAt(h)}return b}var a={consoleLog:!1,csvEnclosure:'"',csvSeparator:",",csvUseBOM:!0,displayTableName:!1,escape:!1,excelFileFormat:"xlshtml",excelstyles:[],fileName:"tableExport",htmlContent:!1,ignoreColumn:[],ignoreRow:[],jsonScope:"all",jspdf:{orientation:"p",unit:"pt",format:"a4",
margins:{left:20,right:10,top:10,bottom:10},autotable:{styles:{cellPadding:2,rowHeight:12,fontSize:8,fillColor:255,textColor:50,fontStyle:"normal",overflow:"ellipsize",halign:"left",valign:"middle"},headerStyles:{fillColor:[52,73,94],textColor:255,fontStyle:"bold",halign:"center"},alternateRowStyles:{fillColor:245},tableExport:{onAfterAutotable:null,onBeforeAutotable:null,onAutotableText:null,onTable:null,outputImages:!0}}},numbers:{html:{decimalMark:".",thousandsSeparator:","},output:{decimalMark:".",
thousandsSeparator:","}},onCellData:null,onCellHtmlData:null,onMsoNumberFormat:null,outputMode:"file",pdfmake:{enabled:!1,docDefinition:{pageOrientation:"portrait",defaultStyle:{font:"Roboto"}},fonts:{}},tbodySelector:"tr",tfootSelector:"tr",theadSelector:"tr",tableName:"myTableName",type:"csv",worksheetName:"Worksheet"},t=this,R=null,q=[],g=[],p=0,y=[],h="",I=[];c.extend(!0,a,n);I=N(t);if("csv"==a.type||"tsv"==a.type||"txt"==a.type){var C="",J=0,p=0,W=function(b,d,e){b.each(function(){h="";x(this,
d,p,e+b.length,function(b,c,e){var f=h,d="";if(null!==b)if(b=v(b,c,e),c=null===b||""===b?"":b.toString(),"tsv"==a.type)b instanceof Date&&b.toLocaleString(),d=Q(c,"\t"," ");else if(b instanceof Date)d=a.csvEnclosure+b.toLocaleString()+a.csvEnclosure;else if(d=Q(c,a.csvEnclosure,a.csvEnclosure+a.csvEnclosure),0<=d.indexOf(a.csvSeparator)||/[\r\n ]/g.test(d))d=a.csvEnclosure+d+a.csvEnclosure;h=f+(d+("tsv"==a.type?"\t":a.csvSeparator))});h=c.trim(h).substring(0,h.length-1);0<h.length&&(0<C.length&&(C+=
"\n"),C+=h);p++});return b.length},J=J+W(c(t).find("thead").first().find(a.theadSelector),"th,td",J);c(t).find("tbody").each(function(){J+=W(c(this).find(a.tbodySelector),"td,th",J)});a.tfootSelector.length&&W(c(t).find("tfoot").first().find(a.tfootSelector),"td,th",J);C+="\n";!0===a.consoleLog&&console.log(C);if("string"===a.outputMode)return C;if("base64"===a.outputMode)return D(C);if("window"===a.outputMode){A(!1,"data:text/"+("csv"==a.type?"csv":"plain")+";charset=utf-8,",C);return}try{var w=
new Blob([C],{type:"text/"+("csv"==a.type?"csv":"plain")+";charset=utf-8"});saveAs(w,a.fileName+"."+a.type,"csv"!=a.type||!1===a.csvUseBOM)}catch(b){A(a.fileName+"."+a.type,"data:text/"+("csv"==a.type?"csv":"plain")+";charset=utf-8,"+("csv"==a.type&&a.csvUseBOM?"\ufeff":""),C)}}else if("sql"==a.type){var p=0,u="INSERT INTO `"+a.tableName+"` (",q=c(t).find("thead").first().find(a.theadSelector);q.each(function(){x(this,"th,td",p,q.length,function(a,c,e){u+="'"+v(a,c,e)+"',"});p++;u=c.trim(u);u=c.trim(u).substring(0,
u.length-1)});u+=") VALUES ";c(t).find("tbody").each(function(){g.push.apply(g,c(this).find(a.tbodySelector))});a.tfootSelector.length&&g.push.apply(g,c(t).find("tfoot").find(a.tfootSelector));c(g).each(function(){h="";x(this,"td,th",p,q.length+g.length,function(a,c,e){h+="'"+v(a,c,e)+"',"});3<h.length&&(u+="("+h,u=c.trim(u).substring(0,u.length-1),u+="),");p++});u=c.trim(u).substring(0,u.length-1);u+=";";!0===a.consoleLog&&console.log(u);if("string"===a.outputMode)return u;if("base64"===a.outputMode)return D(u);
try{w=new Blob([u],{type:"text/plain;charset=utf-8"}),saveAs(w,a.fileName+".sql")}catch(b){A(a.fileName+".sql","data:application/sql;charset=utf-8,",u)}}else if("json"==a.type){var K=[],q=c(t).find("thead").first().find(a.theadSelector);q.each(function(){var a=[];x(this,"th,td",p,q.length,function(b,c,f){a.push(v(b,c,f))});K.push(a)});var X=[];c(t).find("tbody").each(function(){g.push.apply(g,c(this).find(a.tbodySelector))});a.tfootSelector.length&&g.push.apply(g,c(t).find("tfoot").find(a.tfootSelector));
c(g).each(function(){var a={},d=0;x(this,"td,th",p,q.length+g.length,function(b,c,g){K.length?a[K[K.length-1][d]]=v(b,c,g):a[d]=v(b,c,g);d++});!1===c.isEmptyObject(a)&&X.push(a);p++});n="";n="head"==a.jsonScope?JSON.stringify(K):"data"==a.jsonScope?JSON.stringify(X):JSON.stringify({header:K,data:X});!0===a.consoleLog&&console.log(n);if("string"===a.outputMode)return n;if("base64"===a.outputMode)return D(n);try{w=new Blob([n],{type:"application/json;charset=utf-8"}),saveAs(w,a.fileName+".json")}catch(b){A(a.fileName+
".json","data:application/json;charset=utf-8;base64,",n)}}else if("xml"===a.type){p=0;var B='<?xml version="1.0" encoding="utf-8"?>';B+="<tabledata><fields>";q=c(t).find("thead").first().find(a.theadSelector);q.each(function(){x(this,"th,td",p,q.length,function(a,c,e){B+="<field>"+v(a,c,e)+"</field>"});p++});B+="</fields><data>";var ha=1;c(t).find("tbody").each(function(){g.push.apply(g,c(this).find(a.tbodySelector))});a.tfootSelector.length&&g.push.apply(g,c(t).find("tfoot").find(a.tfootSelector));
c(g).each(function(){var a=1;h="";x(this,"td,th",p,q.length+g.length,function(b,c,f){h+="<column-"+a+">"+v(b,c,f)+"</column-"+a+">";a++});0<h.length&&"<column-1></column-1>"!=h&&(B+='<row id="'+ha+'">'+h+"</row>",ha++);p++});B+="</data></tabledata>";!0===a.consoleLog&&console.log(B);if("string"===a.outputMode)return B;if("base64"===a.outputMode)return D(B);try{w=new Blob([B],{type:"application/xml;charset=utf-8"}),saveAs(w,a.fileName+".xml")}catch(b){A(a.fileName+".xml","data:application/xml;charset=utf-8;base64,",
B)}}else if("excel"===a.type&&"xmlss"===a.excelFileFormat){var k=c(t).filter(function(){return"none"!=c(this).data("tableexport-display")&&(c(this).is(":visible")||"always"==c(this).data("tableexport-display"))});var Y=[];k.each(function(){var b=c(this),d="";p=0;I=N(this);q=b.find("thead").first().find(a.theadSelector);var d=d+"<Table>",e=0;q.each(function(){h="";x(this,"th,td",p,q.length,function(a,b,c){null!==a&&(h+='<Cell><Data ss:Type="String">'+v(a,b,c)+"</Data></Cell>",e++)});0<h.length&&(d+=
"<Row>"+h+"</Row>");p++});g=[];b.find("tbody").each(function(){g.push.apply(g,c(this).find(a.tbodySelector))});c(g).each(function(){c(this);h="";x(this,"td,th",p,q.length+g.length,function(a,b,c){if(null!==a){var d="String",e="";a=v(a,b,c);!1!==jQuery.isNumeric(a)?d="Number":(b=a,-1<b.indexOf("%")?(b=U(b.replace(/%/g,"")),!1!==b&&(b/=100)):b=!1,number=b,!1!==number&&(a=number,d="Number",e=' ss:StyleID="pct1"'));"Number"!==d&&(a=a.replace(/\n/g,"<br>"));h+="<Cell"+e+'><Data ss:Type="'+d+'">'+a+"</Data></Cell>"}});
0<h.length&&(d+="<Row>"+h+"</Row>");p++});d+="</Table>";Y.push(d);!0===a.consoleLog&&console.log(d)});k='<?xml version="1.0" encoding="UTF-8"?><?mso-application progid="Excel.Sheet"?> <Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet" xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet" xmlns:html="http://www.w3.org/TR/REC-html40"> <DocumentProperties xmlns="urn:schemas-microsoft-com:office:office"> <Created>'+
(new Date).toISOString()+'</Created> </DocumentProperties> <OfficeDocumentSettings xmlns="urn:schemas-microsoft-com:office:office"> <AllowPNG/> </OfficeDocumentSettings> <ExcelWorkbook xmlns="urn:schemas-microsoft-com:office:excel"> <WindowHeight>9000</WindowHeight> <WindowWidth>13860</WindowWidth> <WindowTopX>0</WindowTopX> <WindowTopY>0</WindowTopY> <ProtectStructure>False</ProtectStructure> <ProtectWindows>False</ProtectWindows> </ExcelWorkbook> <Styles> <Style ss:ID="Default" ss:Name="Default"> <Alignment ss:Vertical="Center"/> <Borders/> <Font/> <Interior/> <NumberFormat/> <Protection/> </Style> <Style ss:ID="Normal" ss:Name="Normal"/> <Style ss:ID="pct1">   <NumberFormat ss:Format="Percent"/> </Style> </Styles>';
for(n=0;n<Y.length;n++)k+='<Worksheet ss:Name="'+("string"===typeof a.worksheetName?a.worksheetName+" "+(n+1):"undefined"!==typeof a.worksheetName[n]?a.worksheetName[n]:"Table "+(n+1))+'">'+Y[n]+"<WorksheetOptions/> </Worksheet>";k+="</Workbook>";!0===a.consoleLog&&console.log(k);if("string"===a.outputMode)return k;if("base64"===a.outputMode)return D(k);try{w=new Blob([k],{type:"application/xml;charset=utf-8"}),saveAs(w,a.fileName+".xml")}catch(b){A(a.fileName+".xml","data:application/xml;charset=utf-8;base64,",
B)}}else if("excel"==a.type||"xls"==a.type||"word"==a.type||"doc"==a.type){n="excel"==a.type||"xls"==a.type?"excel":"word";var G="excel"==n?"xls":"doc",S='xmlns:x="urn:schemas-microsoft-com:office:'+n+'"';k=c(t).filter(function(){return"none"!=c(this).data("tableexport-display")&&(c(this).is(":visible")||"always"==c(this).data("tableexport-display"))});var H="";k.each(function(){var b=c(this);p=0;I=N(this);H+="<table><thead>";q=b.find("thead").first().find(a.theadSelector);q.each(function(){h="";
x(this,"th,td",p,q.length,function(b,d,f){if(null!==b){var e="";h+="<th";for(var g in a.excelstyles)if(a.excelstyles.hasOwnProperty(g)){var l=c(b).css(a.excelstyles[g]);""!==l&&"0px none rgb(0, 0, 0)"!=l&&"rgba(0, 0, 0, 0)"!=l&&(e+=""===e?'style="':";",e+=a.excelstyles[g]+":"+l)}""!==e&&(h+=" "+e+'"');c(b).is("[colspan]")&&(h+=' colspan="'+c(b).attr("colspan")+'"');c(b).is("[rowspan]")&&(h+=' rowspan="'+c(b).attr("rowspan")+'"');h+=">"+v(b,d,f)+"</th>"}});0<h.length&&(H+="<tr>"+h+"</tr>");p++});H+=
"</thead><tbody>";b.find("tbody").each(function(){g.push.apply(g,c(this).find(a.tbodySelector))});a.tfootSelector.length&&g.push.apply(g,b.find("tfoot").find(a.tfootSelector));c(g).each(function(){var b=c(this);h="";x(this,"td,th",p,q.length+g.length,function(d,f,g){if(null!==d){var e="",l=c(d).data("tableexport-msonumberformat");"undefined"==typeof l&&"function"===typeof a.onMsoNumberFormat&&(l=a.onMsoNumberFormat(d,f,g));"undefined"!=typeof l&&""!==l&&(e="style=\"mso-number-format:'"+l+"'");for(var m in a.excelstyles)a.excelstyles.hasOwnProperty(m)&&
(l=c(d).css(a.excelstyles[m]),""===l&&(l=b.css(a.excelstyles[m])),""!==l&&"0px none rgb(0, 0, 0)"!=l&&"rgba(0, 0, 0, 0)"!=l&&(e+=""===e?'style="':";",e+=a.excelstyles[m]+":"+l));h+="<td";""!==e&&(h+=" "+e+'"');c(d).is("[colspan]")&&(h+=' colspan="'+c(d).attr("colspan")+'"');c(d).is("[rowspan]")&&(h+=' rowspan="'+c(d).attr("rowspan")+'"');h+=">"+v(d,f,g).replace(/\n/g,"<br>")+"</td>"}});0<h.length&&(H+="<tr>"+h+"</tr>");p++});a.displayTableName&&(H+="<tr><td></td></tr><tr><td></td></tr><tr><td>"+v(c("<p>"+
a.tableName+"</p>"))+"</td></tr>");H+="</tbody></table>";!0===a.consoleLog&&console.log(H)});k='<html xmlns:o="urn:schemas-microsoft-com:office:office" '+S+' xmlns="http://www.w3.org/TR/REC-html40">'+('<meta http-equiv="content-type" content="application/vnd.ms-'+n+'; charset=UTF-8">')+"<head>";"excel"===n&&(k+="\x3c!--[if gte mso 9]>",k+="<xml>",k+="<x:ExcelWorkbook>",k+="<x:ExcelWorksheets>",k+="<x:ExcelWorksheet>",k+="<x:Name>",k+=a.worksheetName,k+="</x:Name>",k+="<x:WorksheetOptions>",k+="<x:DisplayGridlines/>",
k+="</x:WorksheetOptions>",k+="</x:ExcelWorksheet>",k+="</x:ExcelWorksheets>",k+="</x:ExcelWorkbook>",k+="</xml>",k+="<![endif]--\x3e");k+="<style>br {mso-data-placement:same-cell;}</style>";k+="</head>";k+="<body>";k+=H;k+="</body>";k+="</html>";!0===a.consoleLog&&console.log(k);if("string"===a.outputMode)return k;if("base64"===a.outputMode)return D(k);try{w=new Blob([k],{type:"application/vnd.ms-"+a.type}),saveAs(w,a.fileName+"."+G)}catch(b){A(a.fileName+"."+G,"data:application/vnd.ms-"+n+";base64,",
k)}}else if("xlsx"==a.type){var ia=[],Z=[],p=0,g=c(t).find("thead").first().find(a.theadSelector);c(t).find("tbody").each(function(){g.push.apply(g,c(this).find(a.tbodySelector))});a.tfootSelector.length&&g.push.apply(g,c(t).find("tfoot").find(a.tfootSelector));c(g).each(function(){var a=[];x(this,"th,td",p,g.length,function(b,c,d){if("undefined"!==typeof b&&null!==b){var e=parseInt(b.getAttribute("colspan")),f=parseInt(b.getAttribute("rowspan"));b=v(b,c,d);""!==b&&b==+b&&(b=+b);Z.forEach(function(b){if(p>=
b.s.r&&p<=b.e.r&&a.length>=b.s.c&&a.length<=b.e.c)for(var c=0;c<=b.e.c-b.s.c;++c)a.push(null)});if(f||e)e=e||1,Z.push({s:{r:p,c:a.length},e:{r:p+(f||1)-1,c:a.length+e-1}});a.push(""!==b?b:null);if(e)for(f=0;f<e-1;++f)a.push(null)}});ia.push(a);p++});n=new V;G=pa(ia);G["!merges"]=Z;n.SheetNames.push(a.worksheetName);n.Sheets[a.worksheetName]=G;n=XLSX.write(n,{bookType:a.type,bookSST:!1,type:"binary"});try{w=new Blob([oa(n)],{type:"application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=UTF-8"}),
saveAs(w,a.fileName+"."+a.type)}catch(b){A(a.fileName+"."+a.type,"data:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=UTF-8,",w)}}else if("png"==a.type)html2canvas(c(t)[0]).then(function(b){b=b.toDataURL();for(var c=atob(b.substring(22)),d=new ArrayBuffer(c.length),f=new Uint8Array(d),g=0;g<c.length;g++)f[g]=c.charCodeAt(g);!0===a.consoleLog&&console.log(c);if("string"===a.outputMode)return c;if("base64"===a.outputMode)return D(b);if("window"===a.outputMode)window.open(b);
else try{w=new Blob([d],{type:"image/png"}),saveAs(w,a.fileName+".png")}catch(F){A(a.fileName+".png","data:image/png,",w)}});else if("pdf"==a.type)if(!0===a.pdfmake.enabled){n=[];var ja=[],p=0,G=function(a,d,e){var b=0;c(a).each(function(){var a=[];x(this,d,p,e,function(b,c,d){if("undefined"!==typeof b&&null!==b){var e=parseInt(b.getAttribute("colspan")),f=parseInt(b.getAttribute("rowspan"));b=v(b,c,d)||" ";1<e||1<f?a.push({colSpan:e||1,rowSpan:f||1,text:b}):a.push(b)}else a.push(" ")});a.length&&
ja.push(a);b<a.length&&(b=a.length);p++});return b},q=c(this).find("thead").first().find(a.theadSelector);k=G(q,"th,td",q.length);for(S=n.length;S<k;S++)n.push("*");c(this).find("tbody").each(function(){g.push.apply(g,c(this).find(a.tbodySelector))});a.tfootSelector.length&&g.push.apply(g,c(this).find("tfoot").find(a.tfootSelector));G(g,"th,td",q.length+g.length);n={content:[{table:{headerRows:q.length,widths:n,body:ja}}]};c.extend(!0,n,a.pdfmake.docDefinition);pdfMake.fonts={Roboto:{normal:"Roboto-Regular.ttf",
bold:"Roboto-Medium.ttf",italics:"Roboto-Italic.ttf",bolditalics:"Roboto-MediumItalic.ttf"}};c.extend(!0,pdfMake.fonts,a.pdfmake.fonts);pdfMake.createPdf(n).getBuffer(function(b){try{var c=new Blob([b],{type:"application/pdf"});saveAs(c,a.fileName+".pdf")}catch(e){A(a.fileName+".pdf","data:application/pdf;base64,",b)}})}else if(!1===a.jspdf.autotable){n={dim:{w:P(c(t).first().get(0),"width","mm"),h:P(c(t).first().get(0),"height","mm")},pagesplit:!1};var ka=new jsPDF(a.jspdf.orientation,a.jspdf.unit,
a.jspdf.format);ka.addHTML(c(t).first(),a.jspdf.margins.left,a.jspdf.margins.top,n,function(){aa(ka,!1)})}else{var d=a.jspdf.autotable.tableExport;if("string"===typeof a.jspdf.format&&"bestfit"===a.jspdf.format.toLowerCase()){var L={a0:[2383.94,3370.39],a1:[1683.78,2383.94],a2:[1190.55,1683.78],a3:[841.89,1190.55],a4:[595.28,841.89]},T="",M="",la=0;c(t).filter(":visible").each(function(){if("none"!=c(this).css("display")){var a=P(c(this).get(0),"width","pt");if(a>la){a>L.a0[0]&&(T="a0",M="l");for(var d in L)L.hasOwnProperty(d)&&
L[d][1]>a&&(T=d,M="l",L[d][0]>a&&(M="p"));la=a}}});a.jspdf.format=""===T?"a4":T;a.jspdf.orientation=""===M?"w":M}d.doc=new jsPDF(a.jspdf.orientation,a.jspdf.unit,a.jspdf.format);!0===d.outputImages&&(d.images={});"undefined"!=typeof d.images&&(c(t).filter(function(){return"none"!=c(this).data("tableexport-display")&&(c(this).is(":visible")||"always"==c(this).data("tableexport-display"))}).each(function(){var b=0;q=c(this).find("thead").find(a.theadSelector);c(this).find("tbody").each(function(){g.push.apply(g,
c(this).find(a.tbodySelector))});a.tfootSelector.length&&g.push.apply(g,c(this).find("tfoot").find(a.tfootSelector));c(g).each(function(){x(this,"td,th",q.length+b,q.length+g.length,function(a,b,f){"undefined"!==typeof a&&null!==a&&(b=c(a).children(),"undefined"!=typeof b&&0<b.length&&ca(a,b,d))});b++})}),q=[],g=[]);ma(d,function(b){c(t).filter(function(){return"none"!=c(this).data("tableexport-display")&&(c(this).is(":visible")||"always"==c(this).data("tableexport-display"))}).each(function(){var b,
e=0;I=N(this);d.columns=[];d.rows=[];d.rowoptions={};if("function"===typeof d.onTable&&!1===d.onTable(c(this),a))return!0;a.jspdf.autotable.tableExport=null;var f=c.extend(!0,{},a.jspdf.autotable);a.jspdf.autotable.tableExport=d;f.margin={};c.extend(!0,f.margin,a.jspdf.margins);f.tableExport=d;"function"!==typeof f.beforePageContent&&(f.beforePageContent=function(a){1==a.pageCount&&a.table.rows.concat(a.table.headerRow).forEach(function(b){0<b.height&&(b.height+=(2-1.15)/2*b.styles.fontSize,a.table.height+=
(2-1.15)/2*b.styles.fontSize)})});"function"!==typeof f.createdHeaderCell&&(f.createdHeaderCell=function(a,b){a.styles=c.extend({},b.row.styles);if("undefined"!=typeof d.columns[b.column.dataKey]){var e=d.columns[b.column.dataKey];if("undefined"!=typeof e.rect){a.contentWidth=e.rect.width;if("undefined"==typeof d.heightRatio||0===d.heightRatio){var g=b.row.raw[b.column.dataKey].rowspan?b.row.raw[b.column.dataKey].rect.height/b.row.raw[b.column.dataKey].rowspan:b.row.raw[b.column.dataKey].rect.height;
d.heightRatio=a.styles.rowHeight/g}g=b.row.raw[b.column.dataKey].rect.height*d.heightRatio;g>a.styles.rowHeight&&(a.styles.rowHeight=g)}"undefined"!=typeof e.style&&!0!==e.style.hidden&&(a.styles.halign=e.style.align,"inherit"===f.styles.fillColor&&(a.styles.fillColor=e.style.bcolor),"inherit"===f.styles.textColor&&(a.styles.textColor=e.style.color),"inherit"===f.styles.fontStyle&&(a.styles.fontStyle=e.style.fstyle))}});"function"!==typeof f.createdCell&&(f.createdCell=function(a,b){var c=d.rowoptions[b.row.index+
":"+b.column.dataKey];"undefined"!=typeof c&&"undefined"!=typeof c.style&&!0!==c.style.hidden&&(a.styles.halign=c.style.align,"inherit"===f.styles.fillColor&&(a.styles.fillColor=c.style.bcolor),"inherit"===f.styles.textColor&&(a.styles.textColor=c.style.color),"inherit"===f.styles.fontStyle&&(a.styles.fontStyle=c.style.fstyle))});"function"!==typeof f.drawHeaderCell&&(f.drawHeaderCell=function(a,b){var c=d.columns[b.column.dataKey];return(!0!==c.style.hasOwnProperty("hidden")||!0!==c.style.hidden)&&
0<=c.rowIndex?ba(a,b,c):!1});"function"!==typeof f.drawCell&&(f.drawCell=function(a,b){var c=d.rowoptions[b.row.index+":"+b.column.dataKey];if(ba(a,b,c))if(d.doc.rect(a.x,a.y,a.width,a.height,a.styles.fillStyle),"undefined"!=typeof c&&"undefined"!=typeof c.kids&&0<c.kids.length){var e=a.height/c.rect.height;if(e>d.dh||"undefined"==typeof d.dh)d.dh=e;d.dw=a.width/c.rect.width;e=a.textPos.y;ea(a,c.kids,d);a.textPos.y=e;fa(a,c.kids,d)}else fa(a,{},d);return!1});d.headerrows=[];q=c(this).find("thead").find(a.theadSelector);
q.each(function(){b=0;d.headerrows[e]=[];x(this,"th,td",e,q.length,function(a,c,f){var g=ga(a);g.title=v(a,c,f);g.key=b++;g.rowIndex=e;d.headerrows[e].push(g)});e++});if(0<e)for(var h=e-1;0<=h;)c.each(d.headerrows[h],function(){var a=this;0<h&&null===this.rect&&(a=d.headerrows[h-1][this.key]);null!==a&&0<=a.rowIndex&&(!0!==a.style.hasOwnProperty("hidden")||!0!==a.style.hidden)&&d.columns.push(a)}),h=0<d.columns.length?-1:h-1;var k=0;g=[];c(this).find("tbody").each(function(){g.push.apply(g,c(this).find(a.tbodySelector))});
a.tfootSelector.length&&g.push.apply(g,c(this).find("tfoot").find(a.tfootSelector));c(g).each(function(){var a=[];b=0;x(this,"td,th",e,q.length+g.length,function(e,f,g){if("undefined"===typeof d.columns[b]){var h={title:"",key:b,style:{hidden:!0}};d.columns.push(h)}"undefined"!==typeof e&&null!==e?(h=ga(e),h.kids=c(e).children()):(h=c.extend(!0,{},d.rowoptions[k+":"+(b-1)]),h.colspan=-1);d.rowoptions[k+":"+b++]=h;a.push(v(e,f,g))});a.length&&(d.rows.push(a),k++);e++});if("function"===typeof d.onBeforeAutotable)d.onBeforeAutotable(c(this),
d.columns,d.rows,f);d.doc.autoTable(d.columns,d.rows,f);if("function"===typeof d.onAfterAutotable)d.onAfterAutotable(c(this),f);a.jspdf.autotable.startY=d.doc.autoTableEndPosY()+f.margin.top});aa(d.doc,"undefined"!=typeof d.images&&!1===jQuery.isEmptyObject(d.images));"undefined"!=typeof d.headerrows&&(d.headerrows.length=0);"undefined"!=typeof d.columns&&(d.columns.length=0);"undefined"!=typeof d.rows&&(d.rows.length=0);delete d.doc;d.doc=null})}return this}})})(jQuery);

define("tableexport", ["jquery"], (function (global) {
    return function () {
        var ret, fn;
        return ret || global.$.fn.extend;
    };
}(this)));

/*
* bootstrap-table - v1.11.1 - 2017-02-22
* https://github.com/wenzhixin/bootstrap-table
* Copyright (c) 2017 zhixin wen
* Licensed MIT License
*/
!function(a){"use strict";var b=a.fn.bootstrapTable.utils.sprintf,c={json:"JSON",xml:"XML",png:"PNG",csv:"CSV",txt:"TXT",sql:"SQL",doc:"MS-Word",excel:"MS-Excel",xlsx:"MS-Excel (OpenXML)",powerpoint:"MS-Powerpoint",pdf:"PDF"};a.extend(a.fn.bootstrapTable.defaults,{showExport:!1,exportDataType:"basic",exportTypes:["json","xml","csv","txt","sql","excel"],exportOptions:{}}),a.extend(a.fn.bootstrapTable.defaults.icons,{"export":"glyphicon-export icon-share"}),a.extend(a.fn.bootstrapTable.locales,{formatExport:function(){return"Export data"}}),a.extend(a.fn.bootstrapTable.defaults,a.fn.bootstrapTable.locales);var d=a.fn.bootstrapTable.Constructor,e=d.prototype.initToolbar;d.prototype.initToolbar=function(){if(this.showToolbar=this.options.showExport,e.apply(this,Array.prototype.slice.apply(arguments)),this.options.showExport){var d=this,f=this.$toolbar.find(">.btn-group"),g=f.find("div.export");if(!g.length){g=a(['<div class="export btn-group">','<button class="btn'+b(" btn-%s",this.options.buttonsClass)+b(" btn-%s",this.options.iconSize)+' dropdown-toggle" aria-label="export type" title="'+this.options.formatExport()+'" data-toggle="dropdown" type="button">',b('<i class="%s %s"></i> ',this.options.iconsPrefix,this.options.icons["export"]),'<span class="caret"></span>',"</button>",'<ul class="dropdown-menu" role="menu">',"</ul>","</div>"].join("")).appendTo(f);var h=g.find(".dropdown-menu"),i=this.options.exportTypes;if("string"==typeof this.options.exportTypes){var j=this.options.exportTypes.slice(1,-1).replace(/ /g,"").split(",");i=[],a.each(j,function(a,b){i.push(b.slice(1,-1))})}a.each(i,function(a,b){c.hasOwnProperty(b)&&h.append(['<li role="menuitem" data-type="'+b+'">','<a href="javascript:void(0)">',c[b],"</a>","</li>"].join(""))}),h.find("li").click(function(){var b=a(this).data("type"),c=function(){d.$el.tableExport(a.extend({},d.options.exportOptions,{type:b,escape:!1}))};if("all"===d.options.exportDataType&&d.options.pagination)d.$el.one("server"===d.options.sidePagination?"post-body.bs.table":"page-change.bs.table",function(){c(),d.togglePagination()}),d.togglePagination();else if("selected"===d.options.exportDataType){var e=d.getData(),f=d.getAllSelections();"server"===d.options.sidePagination&&(e={total:d.options.totalRows},e[d.options.dataField]=d.getData(),f={total:d.options.totalRows},f[d.options.dataField]=d.getAllSelections()),d.load(f),c(),d.load(e)}else c()})}}}}(jQuery);
define("bootstrap-table-export", ["bootstrap-table","tableexport"], (function (global) {
    return function () {
        var ret, fn;
        return ret || global.$.fn.bootstrapTable.defaults;
    };
}(this)));

!function(a){"use strict";if("function"==typeof define&&define.amd)define('bootstrap-datetimepicker',["jquery","moment"],a);else if("object"==typeof exports)module.exports=a(require("jquery"),require("moment"));else{if("undefined"==typeof jQuery)throw"bootstrap-datetimepicker requires jQuery to be loaded first";if("undefined"==typeof moment)throw"bootstrap-datetimepicker requires Moment.js to be loaded first";a(jQuery,moment)}}(function(a,b){"use strict";if(!b)throw new Error("bootstrap-datetimepicker requires Moment.js to be loaded first");var c=function(c,d){var e,f,g,h,i,j,k,l={},m=!0,n=!1,o=!1,p=0,q=[{clsName:"days",navFnc:"M",navStep:1},{clsName:"months",navFnc:"y",navStep:1},{clsName:"years",navFnc:"y",navStep:10},{clsName:"decades",navFnc:"y",navStep:100}],r=["days","months","years","decades"],s=["top","bottom","auto"],t=["left","right","auto"],u=["default","top","bottom"],v={up:38,38:"up",down:40,40:"down",left:37,37:"left",right:39,39:"right",tab:9,9:"tab",escape:27,27:"escape",enter:13,13:"enter",pageUp:33,33:"pageUp",pageDown:34,34:"pageDown",shift:16,16:"shift",control:17,17:"control",space:32,32:"space",t:84,84:"t",delete:46,46:"delete"},w={},x=function(){return void 0!==b.tz&&void 0!==d.timeZone&&null!==d.timeZone&&""!==d.timeZone},y=function(a){var c;return c=void 0===a||null===a?b():b.isDate(a)||b.isMoment(a)?b(a):x()?b.tz(a,j,d.useStrict,d.timeZone):b(a,j,d.useStrict),x()&&c.tz(d.timeZone),c},z=function(a){if("string"!=typeof a||a.length>1)throw new TypeError("isEnabled expects a single character string parameter");switch(a){case"y":return i.indexOf("Y")!==-1;case"M":return i.indexOf("M")!==-1;case"d":return i.toLowerCase().indexOf("d")!==-1;case"h":case"H":return i.toLowerCase().indexOf("h")!==-1;case"m":return i.indexOf("m")!==-1;case"s":return i.indexOf("s")!==-1;default:return!1}},A=function(){return z("h")||z("m")||z("s")},B=function(){return z("y")||z("M")||z("d")},C=function(){var b=a("<thead>").append(a("<tr>").append(a("<th>").addClass("prev").attr("data-action","previous").append(a("<span>").addClass(d.icons.previous))).append(a("<th>").addClass("picker-switch").attr("data-action","pickerSwitch").attr("colspan",d.calendarWeeks?"6":"5")).append(a("<th>").addClass("next").attr("data-action","next").append(a("<span>").addClass(d.icons.next)))),c=a("<tbody>").append(a("<tr>").append(a("<td>").attr("colspan",d.calendarWeeks?"8":"7")));return[a("<div>").addClass("datepicker-days").append(a("<table>").addClass("table-condensed").append(b).append(a("<tbody>"))),a("<div>").addClass("datepicker-months").append(a("<table>").addClass("table-condensed").append(b.clone()).append(c.clone())),a("<div>").addClass("datepicker-years").append(a("<table>").addClass("table-condensed").append(b.clone()).append(c.clone())),a("<div>").addClass("datepicker-decades").append(a("<table>").addClass("table-condensed").append(b.clone()).append(c.clone()))]},D=function(){var b=a("<tr>"),c=a("<tr>"),e=a("<tr>");return z("h")&&(b.append(a("<td>").append(a("<a>").attr({href:"#",tabindex:"-1",title:d.tooltips.incrementHour}).addClass("btn").attr("data-action","incrementHours").append(a("<span>").addClass(d.icons.up)))),c.append(a("<td>").append(a("<span>").addClass("timepicker-hour").attr({"data-time-component":"hours",title:d.tooltips.pickHour}).attr("data-action","showHours"))),e.append(a("<td>").append(a("<a>").attr({href:"#",tabindex:"-1",title:d.tooltips.decrementHour}).addClass("btn").attr("data-action","decrementHours").append(a("<span>").addClass(d.icons.down))))),z("m")&&(z("h")&&(b.append(a("<td>").addClass("separator")),c.append(a("<td>").addClass("separator").html(":")),e.append(a("<td>").addClass("separator"))),b.append(a("<td>").append(a("<a>").attr({href:"#",tabindex:"-1",title:d.tooltips.incrementMinute}).addClass("btn").attr("data-action","incrementMinutes").append(a("<span>").addClass(d.icons.up)))),c.append(a("<td>").append(a("<span>").addClass("timepicker-minute").attr({"data-time-component":"minutes",title:d.tooltips.pickMinute}).attr("data-action","showMinutes"))),e.append(a("<td>").append(a("<a>").attr({href:"#",tabindex:"-1",title:d.tooltips.decrementMinute}).addClass("btn").attr("data-action","decrementMinutes").append(a("<span>").addClass(d.icons.down))))),z("s")&&(z("m")&&(b.append(a("<td>").addClass("separator")),c.append(a("<td>").addClass("separator").html(":")),e.append(a("<td>").addClass("separator"))),b.append(a("<td>").append(a("<a>").attr({href:"#",tabindex:"-1",title:d.tooltips.incrementSecond}).addClass("btn").attr("data-action","incrementSeconds").append(a("<span>").addClass(d.icons.up)))),c.append(a("<td>").append(a("<span>").addClass("timepicker-second").attr({"data-time-component":"seconds",title:d.tooltips.pickSecond}).attr("data-action","showSeconds"))),e.append(a("<td>").append(a("<a>").attr({href:"#",tabindex:"-1",title:d.tooltips.decrementSecond}).addClass("btn").attr("data-action","decrementSeconds").append(a("<span>").addClass(d.icons.down))))),h||(b.append(a("<td>").addClass("separator")),c.append(a("<td>").append(a("<button>").addClass("btn btn-primary").attr({"data-action":"togglePeriod",tabindex:"-1",title:d.tooltips.togglePeriod}))),e.append(a("<td>").addClass("separator"))),a("<div>").addClass("timepicker-picker").append(a("<table>").addClass("table-condensed").append([b,c,e]))},E=function(){var b=a("<div>").addClass("timepicker-hours").append(a("<table>").addClass("table-condensed")),c=a("<div>").addClass("timepicker-minutes").append(a("<table>").addClass("table-condensed")),d=a("<div>").addClass("timepicker-seconds").append(a("<table>").addClass("table-condensed")),e=[D()];return z("h")&&e.push(b),z("m")&&e.push(c),z("s")&&e.push(d),e},F=function(){var b=[];return d.showTodayButton&&b.push(a("<td>").append(a("<a>").attr({"data-action":"today",title:d.tooltips.today}).append(a("<span>").addClass(d.icons.today)))),!d.sideBySide&&B()&&A()&&b.push(a("<td>").append(a("<a>").attr({"data-action":"togglePicker",title:d.tooltips.selectTime}).append(a("<span>").addClass(d.icons.time)))),d.showClear&&b.push(a("<td>").append(a("<a>").attr({"data-action":"clear",title:d.tooltips.clear}).append(a("<span>").addClass(d.icons.clear)))),d.showClose&&b.push(a("<td>").append(a("<a>").attr({"data-action":"close",title:d.tooltips.close}).append(a("<span>").addClass(d.icons.close)))),a("<table>").addClass("table-condensed").append(a("<tbody>").append(a("<tr>").append(b)))},G=function(){var b=a("<div>").addClass("bootstrap-datetimepicker-widget dropdown-menu"),c=a("<div>").addClass("datepicker").append(C()),e=a("<div>").addClass("timepicker").append(E()),f=a("<ul>").addClass("list-unstyled"),g=a("<li>").addClass("picker-switch"+(d.collapse?" accordion-toggle":"")).append(F());return d.inline&&b.removeClass("dropdown-menu"),h&&b.addClass("usetwentyfour"),z("s")&&!h&&b.addClass("wider"),d.sideBySide&&B()&&A()?(b.addClass("timepicker-sbs"),"top"===d.toolbarPlacement&&b.append(g),b.append(a("<div>").addClass("row").append(c.addClass("col-md-6")).append(e.addClass("col-md-6"))),"bottom"===d.toolbarPlacement&&b.append(g),b):("top"===d.toolbarPlacement&&f.append(g),B()&&f.append(a("<li>").addClass(d.collapse&&A()?"collapse in":"").append(c)),"default"===d.toolbarPlacement&&f.append(g),A()&&f.append(a("<li>").addClass(d.collapse&&B()?"collapse":"").append(e)),"bottom"===d.toolbarPlacement&&f.append(g),b.append(f))},H=function(){var b,e={};return b=c.is("input")||d.inline?c.data():c.find("input").data(),b.dateOptions&&b.dateOptions instanceof Object&&(e=a.extend(!0,e,b.dateOptions)),a.each(d,function(a){var c="date"+a.charAt(0).toUpperCase()+a.slice(1);void 0!==b[c]&&(e[a]=b[c])}),e},I=function(){var b,e=(n||c).position(),f=(n||c).offset(),g=d.widgetPositioning.vertical,h=d.widgetPositioning.horizontal;if(d.widgetParent)b=d.widgetParent.append(o);else if(c.is("input"))b=c.after(o).parent();else{if(d.inline)return void(b=c.append(o));b=c,c.children().first().after(o)}if("auto"===g&&(g=f.top+1.5*o.height()>=a(window).height()+a(window).scrollTop()&&o.height()+c.outerHeight()<f.top?"top":"bottom"),"auto"===h&&(h=b.width()<f.left+o.outerWidth()/2&&f.left+o.outerWidth()>a(window).width()?"right":"left"),"top"===g?o.addClass("top").removeClass("bottom"):o.addClass("bottom").removeClass("top"),"right"===h?o.addClass("pull-right"):o.removeClass("pull-right"),"static"===b.css("position")&&(b=b.parents().filter(function(){return"static"!==a(this).css("position")}).first()),0===b.length)throw new Error("datetimepicker component should be placed within a non-static positioned container");o.css({top:"top"===g?"auto":e.top+c.outerHeight(),bottom:"top"===g?b.outerHeight()-(b===c?0:e.top):"auto",left:"left"===h?b===c?0:e.left:"auto",right:"left"===h?"auto":b.outerWidth()-c.outerWidth()-(b===c?0:e.left)})},J=function(a){"dp.change"===a.type&&(a.date&&a.date.isSame(a.oldDate)||!a.date&&!a.oldDate)||c.trigger(a)},K=function(a){"y"===a&&(a="YYYY"),J({type:"dp.update",change:a,viewDate:f.clone()})},L=function(a){o&&(a&&(k=Math.max(p,Math.min(3,k+a))),o.find(".datepicker > div").hide().filter(".datepicker-"+q[k].clsName).show())},M=function(){var b=a("<tr>"),c=f.clone().startOf("w").startOf("d");for(d.calendarWeeks===!0&&b.append(a("<th>").addClass("cw").text("#"));c.isBefore(f.clone().endOf("w"));)b.append(a("<th>").addClass("dow").text(c.format("dd"))),c.add(1,"d");o.find(".datepicker-days thead").append(b)},N=function(a){return d.disabledDates[a.format("YYYY-MM-DD")]===!0},O=function(a){return d.enabledDates[a.format("YYYY-MM-DD")]===!0},P=function(a){return d.disabledHours[a.format("H")]===!0},Q=function(a){return d.enabledHours[a.format("H")]===!0},R=function(b,c){if(!b.isValid())return!1;if(d.disabledDates&&"d"===c&&N(b))return!1;if(d.enabledDates&&"d"===c&&!O(b))return!1;if(d.minDate&&b.isBefore(d.minDate,c))return!1;if(d.maxDate&&b.isAfter(d.maxDate,c))return!1;if(d.daysOfWeekDisabled&&"d"===c&&d.daysOfWeekDisabled.indexOf(b.day())!==-1)return!1;if(d.disabledHours&&("h"===c||"m"===c||"s"===c)&&P(b))return!1;if(d.enabledHours&&("h"===c||"m"===c||"s"===c)&&!Q(b))return!1;if(d.disabledTimeIntervals&&("h"===c||"m"===c||"s"===c)){var e=!1;if(a.each(d.disabledTimeIntervals,function(){if(b.isBetween(this[0],this[1]))return e=!0,!1}),e)return!1}return!0},S=function(){for(var b=[],c=f.clone().startOf("y").startOf("d");c.isSame(f,"y");)b.push(a("<span>").attr("data-action","selectMonth").addClass("month").text(c.format("MMM"))),c.add(1,"M");o.find(".datepicker-months td").empty().append(b)},T=function(){var b=o.find(".datepicker-months"),c=b.find("th"),g=b.find("tbody").find("span");c.eq(0).find("span").attr("title",d.tooltips.prevYear),c.eq(1).attr("title",d.tooltips.selectYear),c.eq(2).find("span").attr("title",d.tooltips.nextYear),b.find(".disabled").removeClass("disabled"),R(f.clone().subtract(1,"y"),"y")||c.eq(0).addClass("disabled"),c.eq(1).text(f.year()),R(f.clone().add(1,"y"),"y")||c.eq(2).addClass("disabled"),g.removeClass("active"),e.isSame(f,"y")&&!m&&g.eq(e.month()).addClass("active"),g.each(function(b){R(f.clone().month(b),"M")||a(this).addClass("disabled")})},U=function(){var a=o.find(".datepicker-years"),b=a.find("th"),c=f.clone().subtract(5,"y"),g=f.clone().add(6,"y"),h="";for(b.eq(0).find("span").attr("title",d.tooltips.prevDecade),b.eq(1).attr("title",d.tooltips.selectDecade),b.eq(2).find("span").attr("title",d.tooltips.nextDecade),a.find(".disabled").removeClass("disabled"),d.minDate&&d.minDate.isAfter(c,"y")&&b.eq(0).addClass("disabled"),b.eq(1).text(c.year()+"-"+g.year()),d.maxDate&&d.maxDate.isBefore(g,"y")&&b.eq(2).addClass("disabled");!c.isAfter(g,"y");)h+='<span data-action="selectYear" class="year'+(c.isSame(e,"y")&&!m?" active":"")+(R(c,"y")?"":" disabled")+'">'+c.year()+"</span>",c.add(1,"y");a.find("td").html(h)},V=function(){var a,c=o.find(".datepicker-decades"),g=c.find("th"),h=b({y:f.year()-f.year()%100-1}),i=h.clone().add(100,"y"),j=h.clone(),k=!1,l=!1,m="";for(g.eq(0).find("span").attr("title",d.tooltips.prevCentury),g.eq(2).find("span").attr("title",d.tooltips.nextCentury),c.find(".disabled").removeClass("disabled"),(h.isSame(b({y:1900}))||d.minDate&&d.minDate.isAfter(h,"y"))&&g.eq(0).addClass("disabled"),g.eq(1).text(h.year()+"-"+i.year()),(h.isSame(b({y:2e3}))||d.maxDate&&d.maxDate.isBefore(i,"y"))&&g.eq(2).addClass("disabled");!h.isAfter(i,"y");)a=h.year()+12,k=d.minDate&&d.minDate.isAfter(h,"y")&&d.minDate.year()<=a,l=d.maxDate&&d.maxDate.isAfter(h,"y")&&d.maxDate.year()<=a,m+='<span data-action="selectDecade" class="decade'+(e.isAfter(h)&&e.year()<=a?" active":"")+(R(h,"y")||k||l?"":" disabled")+'" data-selection="'+(h.year()+6)+'">'+(h.year()+1)+" - "+(h.year()+12)+"</span>",h.add(12,"y");m+="<span></span><span></span><span></span>",c.find("td").html(m),g.eq(1).text(j.year()+1+"-"+h.year())},W=function(){var b,c,g,h=o.find(".datepicker-days"),i=h.find("th"),j=[],k=[];if(B()){for(i.eq(0).find("span").attr("title",d.tooltips.prevMonth),i.eq(1).attr("title",d.tooltips.selectMonth),i.eq(2).find("span").attr("title",d.tooltips.nextMonth),h.find(".disabled").removeClass("disabled"),i.eq(1).text(f.format(d.dayViewHeaderFormat)),R(f.clone().subtract(1,"M"),"M")||i.eq(0).addClass("disabled"),R(f.clone().add(1,"M"),"M")||i.eq(2).addClass("disabled"),b=f.clone().startOf("M").startOf("w").startOf("d"),g=0;g<42;g++)0===b.weekday()&&(c=a("<tr>"),d.calendarWeeks&&c.append('<td class="cw">'+b.week()+"</td>"),j.push(c)),k=["day"],b.isBefore(f,"M")&&k.push("old"),b.isAfter(f,"M")&&k.push("new"),b.isSame(e,"d")&&!m&&k.push("active"),R(b,"d")||k.push("disabled"),b.isSame(y(),"d")&&k.push("today"),0!==b.day()&&6!==b.day()||k.push("weekend"),J({type:"dp.classify",date:b,classNames:k}),c.append('<td data-action="selectDay" data-day="'+b.format("L")+'" class="'+k.join(" ")+'">'+b.date()+"</td>"),b.add(1,"d");h.find("tbody").empty().append(j),T(),U(),V()}},X=function(){var b=o.find(".timepicker-hours table"),c=f.clone().startOf("d"),d=[],e=a("<tr>");for(f.hour()>11&&!h&&c.hour(12);c.isSame(f,"d")&&(h||f.hour()<12&&c.hour()<12||f.hour()>11);)c.hour()%4===0&&(e=a("<tr>"),d.push(e)),e.append('<td data-action="selectHour" class="hour'+(R(c,"h")?"":" disabled")+'">'+c.format(h?"HH":"hh")+"</td>"),c.add(1,"h");b.empty().append(d)},Y=function(){for(var b=o.find(".timepicker-minutes table"),c=f.clone().startOf("h"),e=[],g=a("<tr>"),h=1===d.stepping?5:d.stepping;f.isSame(c,"h");)c.minute()%(4*h)===0&&(g=a("<tr>"),e.push(g)),g.append('<td data-action="selectMinute" class="minute'+(R(c,"m")?"":" disabled")+'">'+c.format("mm")+"</td>"),c.add(h,"m");b.empty().append(e)},Z=function(){for(var b=o.find(".timepicker-seconds table"),c=f.clone().startOf("m"),d=[],e=a("<tr>");f.isSame(c,"m");)c.second()%20===0&&(e=a("<tr>"),d.push(e)),e.append('<td data-action="selectSecond" class="second'+(R(c,"s")?"":" disabled")+'">'+c.format("ss")+"</td>"),c.add(5,"s");b.empty().append(d)},$=function(){var a,b,c=o.find(".timepicker span[data-time-component]");h||(a=o.find(".timepicker [data-action=togglePeriod]"),b=e.clone().add(e.hours()>=12?-12:12,"h"),a.text(e.format("A")),R(b,"h")?a.removeClass("disabled"):a.addClass("disabled")),c.filter("[data-time-component=hours]").text(e.format(h?"HH":"hh")),c.filter("[data-time-component=minutes]").text(e.format("mm")),c.filter("[data-time-component=seconds]").text(e.format("ss")),X(),Y(),Z()},_=function(){o&&(W(),$())},aa=function(a){var b=m?null:e;if(!a)return m=!0,g.val(""),c.data("date",""),J({type:"dp.change",date:!1,oldDate:b}),void _();if(a=a.clone().locale(d.locale),x()&&a.tz(d.timeZone),1!==d.stepping)for(a.minutes(Math.round(a.minutes()/d.stepping)*d.stepping).seconds(0);d.minDate&&a.isBefore(d.minDate);)a.add(d.stepping,"minutes");R(a)?(e=a,f=e.clone(),g.val(e.format(i)),c.data("date",e.format(i)),m=!1,_(),J({type:"dp.change",date:e.clone(),oldDate:b})):(d.keepInvalid?J({type:"dp.change",date:a,oldDate:b}):g.val(m?"":e.format(i)),J({type:"dp.error",date:a,oldDate:b}))},ba=function(){var b=!1;return o?(o.find(".collapse").each(function(){var c=a(this).data("collapse");return!c||!c.transitioning||(b=!0,!1)}),b?l:(n&&n.hasClass("btn")&&n.toggleClass("active"),o.hide(),a(window).off("resize",I),o.off("click","[data-action]"),o.off("mousedown",!1),o.remove(),o=!1,J({type:"dp.hide",date:e.clone()}),g.blur(),f=e.clone(),l)):l},ca=function(){aa(null)},da=function(a){return void 0===d.parseInputDate?(!b.isMoment(a)||a instanceof Date)&&(a=y(a)):a=d.parseInputDate(a),a},ea={next:function(){var a=q[k].navFnc;f.add(q[k].navStep,a),W(),K(a)},previous:function(){var a=q[k].navFnc;f.subtract(q[k].navStep,a),W(),K(a)},pickerSwitch:function(){L(1)},selectMonth:function(b){var c=a(b.target).closest("tbody").find("span").index(a(b.target));f.month(c),k===p?(aa(e.clone().year(f.year()).month(f.month())),d.inline||ba()):(L(-1),W()),K("M")},selectYear:function(b){var c=parseInt(a(b.target).text(),10)||0;f.year(c),k===p?(aa(e.clone().year(f.year())),d.inline||ba()):(L(-1),W()),K("YYYY")},selectDecade:function(b){var c=parseInt(a(b.target).data("selection"),10)||0;f.year(c),k===p?(aa(e.clone().year(f.year())),d.inline||ba()):(L(-1),W()),K("YYYY")},selectDay:function(b){var c=f.clone();a(b.target).is(".old")&&c.subtract(1,"M"),a(b.target).is(".new")&&c.add(1,"M"),aa(c.date(parseInt(a(b.target).text(),10))),A()||d.keepOpen||d.inline||ba()},incrementHours:function(){var a=e.clone().add(1,"h");R(a,"h")&&aa(a)},incrementMinutes:function(){var a=e.clone().add(d.stepping,"m");R(a,"m")&&aa(a)},incrementSeconds:function(){var a=e.clone().add(1,"s");R(a,"s")&&aa(a)},decrementHours:function(){var a=e.clone().subtract(1,"h");R(a,"h")&&aa(a)},decrementMinutes:function(){var a=e.clone().subtract(d.stepping,"m");R(a,"m")&&aa(a)},decrementSeconds:function(){var a=e.clone().subtract(1,"s");R(a,"s")&&aa(a)},togglePeriod:function(){aa(e.clone().add(e.hours()>=12?-12:12,"h"))},togglePicker:function(b){var c,e=a(b.target),f=e.closest("ul"),g=f.find(".in"),h=f.find(".collapse:not(.in)");if(g&&g.length){if(c=g.data("collapse"),c&&c.transitioning)return;g.collapse?(g.collapse("hide"),h.collapse("show")):(g.removeClass("in"),h.addClass("in")),e.is("span")?e.toggleClass(d.icons.time+" "+d.icons.date):e.find("span").toggleClass(d.icons.time+" "+d.icons.date)}},showPicker:function(){o.find(".timepicker > div:not(.timepicker-picker)").hide(),o.find(".timepicker .timepicker-picker").show()},showHours:function(){o.find(".timepicker .timepicker-picker").hide(),o.find(".timepicker .timepicker-hours").show()},showMinutes:function(){o.find(".timepicker .timepicker-picker").hide(),o.find(".timepicker .timepicker-minutes").show()},showSeconds:function(){o.find(".timepicker .timepicker-picker").hide(),o.find(".timepicker .timepicker-seconds").show()},selectHour:function(b){var c=parseInt(a(b.target).text(),10);h||(e.hours()>=12?12!==c&&(c+=12):12===c&&(c=0)),aa(e.clone().hours(c)),ea.showPicker.call(l)},selectMinute:function(b){aa(e.clone().minutes(parseInt(a(b.target).text(),10))),ea.showPicker.call(l)},selectSecond:function(b){aa(e.clone().seconds(parseInt(a(b.target).text(),10))),ea.showPicker.call(l)},clear:ca,today:function(){var a=y();R(a,"d")&&aa(a)},close:ba},fa=function(b){return!a(b.currentTarget).is(".disabled")&&(ea[a(b.currentTarget).data("action")].apply(l,arguments),!1)},ga=function(){var b,c={year:function(a){return a.month(0).date(1).hours(0).seconds(0).minutes(0)},month:function(a){return a.date(1).hours(0).seconds(0).minutes(0)},day:function(a){return a.hours(0).seconds(0).minutes(0)},hour:function(a){return a.seconds(0).minutes(0)},minute:function(a){return a.seconds(0)}};return g.prop("disabled")||!d.ignoreReadonly&&g.prop("readonly")||o?l:(void 0!==g.val()&&0!==g.val().trim().length?aa(da(g.val().trim())):m&&d.useCurrent&&(d.inline||g.is("input")&&0===g.val().trim().length)&&(b=y(),"string"==typeof d.useCurrent&&(b=c[d.useCurrent](b)),aa(b)),o=G(),M(),S(),o.find(".timepicker-hours").hide(),o.find(".timepicker-minutes").hide(),o.find(".timepicker-seconds").hide(),_(),L(),a(window).on("resize",I),o.on("click","[data-action]",fa),o.on("mousedown",!1),n&&n.hasClass("btn")&&n.toggleClass("active"),I(),o.show(),d.focusOnShow&&!g.is(":focus")&&g.focus(),J({type:"dp.show"}),l)},ha=function(){return o?ba():ga()},ia=function(a){var b,c,e,f,g=null,h=[],i={},j=a.which,k="p";w[j]=k;for(b in w)w.hasOwnProperty(b)&&w[b]===k&&(h.push(b),parseInt(b,10)!==j&&(i[b]=!0));for(b in d.keyBinds)if(d.keyBinds.hasOwnProperty(b)&&"function"==typeof d.keyBinds[b]&&(e=b.split(" "),e.length===h.length&&v[j]===e[e.length-1])){for(f=!0,c=e.length-2;c>=0;c--)if(!(v[e[c]]in i)){f=!1;break}if(f){g=d.keyBinds[b];break}}g&&(g.call(l,o),a.stopPropagation(),a.preventDefault())},ja=function(a){w[a.which]="r",a.stopPropagation(),a.preventDefault()},ka=function(b){var c=a(b.target).val().trim(),d=c?da(c):null;return aa(d),b.stopImmediatePropagation(),!1},la=function(){g.on({change:ka,blur:d.debug?"":ba,keydown:ia,keyup:ja,focus:d.allowInputToggle?ga:""}),c.is("input")?g.on({focus:ga}):n&&(n.on("click",ha),n.on("mousedown",!1))},ma=function(){g.off({change:ka,blur:blur,keydown:ia,keyup:ja,focus:d.allowInputToggle?ba:""}),c.is("input")?g.off({focus:ga}):n&&(n.off("click",ha),n.off("mousedown",!1))},na=function(b){var c={};return a.each(b,function(){var a=da(this);a.isValid()&&(c[a.format("YYYY-MM-DD")]=!0)}),!!Object.keys(c).length&&c},oa=function(b){var c={};return a.each(b,function(){c[this]=!0}),!!Object.keys(c).length&&c},pa=function(){var a=d.format||"L LT";i=a.replace(/(\[[^\[]*\])|(\\)?(LTS|LT|LL?L?L?|l{1,4})/g,function(a){var b=e.localeData().longDateFormat(a)||a;return b.replace(/(\[[^\[]*\])|(\\)?(LTS|LT|LL?L?L?|l{1,4})/g,function(a){return e.localeData().longDateFormat(a)||a})}),j=d.extraFormats?d.extraFormats.slice():[],j.indexOf(a)<0&&j.indexOf(i)<0&&j.push(i),h=i.toLowerCase().indexOf("a")<1&&i.replace(/\[.*?\]/g,"").indexOf("h")<1,z("y")&&(p=2),z("M")&&(p=1),z("d")&&(p=0),k=Math.max(p,k),m||aa(e)};if(l.destroy=function(){ba(),ma(),c.removeData("DateTimePicker"),c.removeData("date")},l.toggle=ha,l.show=ga,l.hide=ba,l.disable=function(){return ba(),n&&n.hasClass("btn")&&n.addClass("disabled"),g.prop("disabled",!0),l},l.enable=function(){return n&&n.hasClass("btn")&&n.removeClass("disabled"),g.prop("disabled",!1),l},l.ignoreReadonly=function(a){if(0===arguments.length)return d.ignoreReadonly;if("boolean"!=typeof a)throw new TypeError("ignoreReadonly () expects a boolean parameter");return d.ignoreReadonly=a,l},l.options=function(b){if(0===arguments.length)return a.extend(!0,{},d);if(!(b instanceof Object))throw new TypeError("options() options parameter should be an object");return a.extend(!0,d,b),a.each(d,function(a,b){if(void 0===l[a])throw new TypeError("option "+a+" is not recognized!");l[a](b)}),l},l.date=function(a){if(0===arguments.length)return m?null:e.clone();if(!(null===a||"string"==typeof a||b.isMoment(a)||a instanceof Date))throw new TypeError("date() parameter must be one of [null, string, moment or Date]");return aa(null===a?null:da(a)),l},l.format=function(a){if(0===arguments.length)return d.format;if("string"!=typeof a&&("boolean"!=typeof a||a!==!1))throw new TypeError("format() expects a string or boolean:false parameter "+a);return d.format=a,i&&pa(),l},l.timeZone=function(a){if(0===arguments.length)return d.timeZone;if("string"!=typeof a)throw new TypeError("newZone() expects a string parameter");return d.timeZone=a,l},l.dayViewHeaderFormat=function(a){if(0===arguments.length)return d.dayViewHeaderFormat;if("string"!=typeof a)throw new TypeError("dayViewHeaderFormat() expects a string parameter");return d.dayViewHeaderFormat=a,l},l.extraFormats=function(a){if(0===arguments.length)return d.extraFormats;if(a!==!1&&!(a instanceof Array))throw new TypeError("extraFormats() expects an array or false parameter");return d.extraFormats=a,j&&pa(),l},l.disabledDates=function(b){if(0===arguments.length)return d.disabledDates?a.extend({},d.disabledDates):d.disabledDates;if(!b)return d.disabledDates=!1,_(),l;if(!(b instanceof Array))throw new TypeError("disabledDates() expects an array parameter");return d.disabledDates=na(b),d.enabledDates=!1,_(),l},l.enabledDates=function(b){if(0===arguments.length)return d.enabledDates?a.extend({},d.enabledDates):d.enabledDates;if(!b)return d.enabledDates=!1,_(),l;if(!(b instanceof Array))throw new TypeError("enabledDates() expects an array parameter");return d.enabledDates=na(b),d.disabledDates=!1,_(),l},l.daysOfWeekDisabled=function(a){if(0===arguments.length)return d.daysOfWeekDisabled.splice(0);if("boolean"==typeof a&&!a)return d.daysOfWeekDisabled=!1,_(),l;if(!(a instanceof Array))throw new TypeError("daysOfWeekDisabled() expects an array parameter");if(d.daysOfWeekDisabled=a.reduce(function(a,b){return b=parseInt(b,10),b>6||b<0||isNaN(b)?a:(a.indexOf(b)===-1&&a.push(b),a)},[]).sort(),d.useCurrent&&!d.keepInvalid){for(var b=0;!R(e,"d");){if(e.add(1,"d"),31===b)throw"Tried 31 times to find a valid date";b++}aa(e)}return _(),l},l.maxDate=function(a){if(0===arguments.length)return d.maxDate?d.maxDate.clone():d.maxDate;if("boolean"==typeof a&&a===!1)return d.maxDate=!1,_(),l;"string"==typeof a&&("now"!==a&&"moment"!==a||(a=y()));var b=da(a);if(!b.isValid())throw new TypeError("maxDate() Could not parse date parameter: "+a);if(d.minDate&&b.isBefore(d.minDate))throw new TypeError("maxDate() date parameter is before options.minDate: "+b.format(i));return d.maxDate=b,d.useCurrent&&!d.keepInvalid&&e.isAfter(a)&&aa(d.maxDate),f.isAfter(b)&&(f=b.clone().subtract(d.stepping,"m")),_(),l},l.minDate=function(a){if(0===arguments.length)return d.minDate?d.minDate.clone():d.minDate;if("boolean"==typeof a&&a===!1)return d.minDate=!1,_(),l;"string"==typeof a&&("now"!==a&&"moment"!==a||(a=y()));var b=da(a);if(!b.isValid())throw new TypeError("minDate() Could not parse date parameter: "+a);if(d.maxDate&&b.isAfter(d.maxDate))throw new TypeError("minDate() date parameter is after options.maxDate: "+b.format(i));return d.minDate=b,d.useCurrent&&!d.keepInvalid&&e.isBefore(a)&&aa(d.minDate),f.isBefore(b)&&(f=b.clone().add(d.stepping,"m")),_(),l},l.defaultDate=function(a){if(0===arguments.length)return d.defaultDate?d.defaultDate.clone():d.defaultDate;if(!a)return d.defaultDate=!1,l;"string"==typeof a&&(a="now"===a||"moment"===a?y():y(a));var b=da(a);if(!b.isValid())throw new TypeError("defaultDate() Could not parse date parameter: "+a);if(!R(b))throw new TypeError("defaultDate() date passed is invalid according to component setup validations");return d.defaultDate=b,(d.defaultDate&&d.inline||""===g.val().trim())&&aa(d.defaultDate),l},l.locale=function(a){if(0===arguments.length)return d.locale;if(!b.localeData(a))throw new TypeError("locale() locale "+a+" is not loaded from moment locales!");return d.locale=a,e.locale(d.locale),f.locale(d.locale),i&&pa(),o&&(ba(),ga()),l},l.stepping=function(a){return 0===arguments.length?d.stepping:(a=parseInt(a,10),(isNaN(a)||a<1)&&(a=1),d.stepping=a,l)},l.useCurrent=function(a){var b=["year","month","day","hour","minute"];if(0===arguments.length)return d.useCurrent;if("boolean"!=typeof a&&"string"!=typeof a)throw new TypeError("useCurrent() expects a boolean or string parameter");if("string"==typeof a&&b.indexOf(a.toLowerCase())===-1)throw new TypeError("useCurrent() expects a string parameter of "+b.join(", "));return d.useCurrent=a,l},l.collapse=function(a){if(0===arguments.length)return d.collapse;if("boolean"!=typeof a)throw new TypeError("collapse() expects a boolean parameter");return d.collapse===a?l:(d.collapse=a,o&&(ba(),ga()),l)},l.icons=function(b){if(0===arguments.length)return a.extend({},d.icons);if(!(b instanceof Object))throw new TypeError("icons() expects parameter to be an Object");return a.extend(d.icons,b),o&&(ba(),ga()),l},l.tooltips=function(b){if(0===arguments.length)return a.extend({},d.tooltips);if(!(b instanceof Object))throw new TypeError("tooltips() expects parameter to be an Object");return a.extend(d.tooltips,b),o&&(ba(),ga()),l},l.useStrict=function(a){if(0===arguments.length)return d.useStrict;if("boolean"!=typeof a)throw new TypeError("useStrict() expects a boolean parameter");return d.useStrict=a,l},l.sideBySide=function(a){if(0===arguments.length)return d.sideBySide;if("boolean"!=typeof a)throw new TypeError("sideBySide() expects a boolean parameter");return d.sideBySide=a,o&&(ba(),ga()),l},l.viewMode=function(a){if(0===arguments.length)return d.viewMode;if("string"!=typeof a)throw new TypeError("viewMode() expects a string parameter");if(r.indexOf(a)===-1)throw new TypeError("viewMode() parameter must be one of ("+r.join(", ")+") value");return d.viewMode=a,k=Math.max(r.indexOf(a),p),L(),l},l.toolbarPlacement=function(a){if(0===arguments.length)return d.toolbarPlacement;if("string"!=typeof a)throw new TypeError("toolbarPlacement() expects a string parameter");if(u.indexOf(a)===-1)throw new TypeError("toolbarPlacement() parameter must be one of ("+u.join(", ")+") value");return d.toolbarPlacement=a,o&&(ba(),ga()),l},l.widgetPositioning=function(b){if(0===arguments.length)return a.extend({},d.widgetPositioning);if("[object Object]"!=={}.toString.call(b))throw new TypeError("widgetPositioning() expects an object variable");if(b.horizontal){if("string"!=typeof b.horizontal)throw new TypeError("widgetPositioning() horizontal variable must be a string");if(b.horizontal=b.horizontal.toLowerCase(),t.indexOf(b.horizontal)===-1)throw new TypeError("widgetPositioning() expects horizontal parameter to be one of ("+t.join(", ")+")");d.widgetPositioning.horizontal=b.horizontal}if(b.vertical){if("string"!=typeof b.vertical)throw new TypeError("widgetPositioning() vertical variable must be a string");if(b.vertical=b.vertical.toLowerCase(),s.indexOf(b.vertical)===-1)throw new TypeError("widgetPositioning() expects vertical parameter to be one of ("+s.join(", ")+")");d.widgetPositioning.vertical=b.vertical}return _(),l},l.calendarWeeks=function(a){if(0===arguments.length)return d.calendarWeeks;if("boolean"!=typeof a)throw new TypeError("calendarWeeks() expects parameter to be a boolean value");return d.calendarWeeks=a,_(),l},l.showTodayButton=function(a){if(0===arguments.length)return d.showTodayButton;if("boolean"!=typeof a)throw new TypeError("showTodayButton() expects a boolean parameter");return d.showTodayButton=a,o&&(ba(),ga()),l},l.showClear=function(a){if(0===arguments.length)return d.showClear;if("boolean"!=typeof a)throw new TypeError("showClear() expects a boolean parameter");return d.showClear=a,o&&(ba(),ga()),l},l.widgetParent=function(b){if(0===arguments.length)return d.widgetParent;if("string"==typeof b&&(b=a(b)),null!==b&&"string"!=typeof b&&!(b instanceof a))throw new TypeError("widgetParent() expects a string or a jQuery object parameter");return d.widgetParent=b,o&&(ba(),ga()),l},l.keepOpen=function(a){if(0===arguments.length)return d.keepOpen;if("boolean"!=typeof a)throw new TypeError("keepOpen() expects a boolean parameter");return d.keepOpen=a,l},l.focusOnShow=function(a){if(0===arguments.length)return d.focusOnShow;if("boolean"!=typeof a)throw new TypeError("focusOnShow() expects a boolean parameter");return d.focusOnShow=a,l},l.inline=function(a){if(0===arguments.length)return d.inline;if("boolean"!=typeof a)throw new TypeError("inline() expects a boolean parameter");return d.inline=a,l},l.clear=function(){return ca(),l},l.keyBinds=function(a){return 0===arguments.length?d.keyBinds:(d.keyBinds=a,l)},l.getMoment=function(a){return y(a)},l.debug=function(a){if("boolean"!=typeof a)throw new TypeError("debug() expects a boolean parameter");return d.debug=a,l},l.allowInputToggle=function(a){if(0===arguments.length)return d.allowInputToggle;if("boolean"!=typeof a)throw new TypeError("allowInputToggle() expects a boolean parameter");return d.allowInputToggle=a,l},l.showClose=function(a){if(0===arguments.length)return d.showClose;if("boolean"!=typeof a)throw new TypeError("showClose() expects a boolean parameter");return d.showClose=a,l},l.keepInvalid=function(a){if(0===arguments.length)return d.keepInvalid;if("boolean"!=typeof a)throw new TypeError("keepInvalid() expects a boolean parameter");
return d.keepInvalid=a,l},l.datepickerInput=function(a){if(0===arguments.length)return d.datepickerInput;if("string"!=typeof a)throw new TypeError("datepickerInput() expects a string parameter");return d.datepickerInput=a,l},l.parseInputDate=function(a){if(0===arguments.length)return d.parseInputDate;if("function"!=typeof a)throw new TypeError("parseInputDate() sholud be as function");return d.parseInputDate=a,l},l.disabledTimeIntervals=function(b){if(0===arguments.length)return d.disabledTimeIntervals?a.extend({},d.disabledTimeIntervals):d.disabledTimeIntervals;if(!b)return d.disabledTimeIntervals=!1,_(),l;if(!(b instanceof Array))throw new TypeError("disabledTimeIntervals() expects an array parameter");return d.disabledTimeIntervals=b,_(),l},l.disabledHours=function(b){if(0===arguments.length)return d.disabledHours?a.extend({},d.disabledHours):d.disabledHours;if(!b)return d.disabledHours=!1,_(),l;if(!(b instanceof Array))throw new TypeError("disabledHours() expects an array parameter");if(d.disabledHours=oa(b),d.enabledHours=!1,d.useCurrent&&!d.keepInvalid){for(var c=0;!R(e,"h");){if(e.add(1,"h"),24===c)throw"Tried 24 times to find a valid date";c++}aa(e)}return _(),l},l.enabledHours=function(b){if(0===arguments.length)return d.enabledHours?a.extend({},d.enabledHours):d.enabledHours;if(!b)return d.enabledHours=!1,_(),l;if(!(b instanceof Array))throw new TypeError("enabledHours() expects an array parameter");if(d.enabledHours=oa(b),d.disabledHours=!1,d.useCurrent&&!d.keepInvalid){for(var c=0;!R(e,"h");){if(e.add(1,"h"),24===c)throw"Tried 24 times to find a valid date";c++}aa(e)}return _(),l},l.viewDate=function(a){if(0===arguments.length)return f.clone();if(!a)return f=e.clone(),l;if(!("string"==typeof a||b.isMoment(a)||a instanceof Date))throw new TypeError("viewDate() parameter must be one of [string, moment or Date]");return f=da(a),K(),l},c.is("input"))g=c;else if(g=c.find(d.datepickerInput),0===g.length)g=c.find("input");else if(!g.is("input"))throw new Error('CSS class "'+d.datepickerInput+'" cannot be applied to non input element');if(c.hasClass("input-group")&&(n=0===c.find(".datepickerbutton").length?c.find(".input-group-addon"):c.find(".datepickerbutton")),!d.inline&&!g.is("input"))throw new Error("Could not initialize DateTimePicker without an input element");return e=y(),f=e.clone(),a.extend(!0,d,H()),l.options(d),pa(),la(),g.prop("disabled")&&l.disable(),g.is("input")&&0!==g.val().trim().length?aa(da(g.val().trim())):d.defaultDate&&void 0===g.attr("placeholder")&&aa(d.defaultDate),d.inline&&ga(),l};return a.fn.datetimepicker=function(b){b=b||{};var d,e=Array.prototype.slice.call(arguments,1),f=!0,g=["destroy","hide","show","toggle"];if("object"==typeof b)return this.each(function(){var d,e=a(this);e.data("DateTimePicker")||(d=a.extend(!0,{},a.fn.datetimepicker.defaults,b),e.data("DateTimePicker",c(e,d)))});if("string"==typeof b)return this.each(function(){var c=a(this),g=c.data("DateTimePicker");if(!g)throw new Error('bootstrap-datetimepicker("'+b+'") method was called on an element that is not using DateTimePicker');d=g[b].apply(g,e),f=d===g}),f||a.inArray(b,g)>-1?this:d;throw new TypeError("Invalid arguments for DateTimePicker: "+b)},a.fn.datetimepicker.defaults={timeZone:"",format:!1,dayViewHeaderFormat:"MMMM YYYY",extraFormats:!1,stepping:1,minDate:!1,maxDate:!1,useCurrent:!0,collapse:!0,locale:b.locale(),defaultDate:!1,disabledDates:!1,enabledDates:!1,icons:{time:"glyphicon glyphicon-time",date:"glyphicon glyphicon-calendar",up:"glyphicon glyphicon-chevron-up",down:"glyphicon glyphicon-chevron-down",previous:"glyphicon glyphicon-chevron-left",next:"glyphicon glyphicon-chevron-right",today:"glyphicon glyphicon-screenshot",clear:"glyphicon glyphicon-trash",close:"glyphicon glyphicon-remove"},tooltips:{today:"Go to today",clear:"Clear selection",close:"Close the picker",selectMonth:"Select Month",prevMonth:"Previous Month",nextMonth:"Next Month",selectYear:"Select Year",prevYear:"Previous Year",nextYear:"Next Year",selectDecade:"Select Decade",prevDecade:"Previous Decade",nextDecade:"Next Decade",prevCentury:"Previous Century",nextCentury:"Next Century",pickHour:"Pick Hour",incrementHour:"Increment Hour",decrementHour:"Decrement Hour",pickMinute:"Pick Minute",incrementMinute:"Increment Minute",decrementMinute:"Decrement Minute",pickSecond:"Pick Second",incrementSecond:"Increment Second",decrementSecond:"Decrement Second",togglePeriod:"Toggle Period",selectTime:"Select Time"},useStrict:!1,sideBySide:!1,daysOfWeekDisabled:!1,calendarWeeks:!1,viewMode:"days",toolbarPlacement:"default",showTodayButton:!1,showClear:!1,showClose:!1,widgetPositioning:{horizontal:"auto",vertical:"auto"},widgetParent:null,ignoreReadonly:!1,keepOpen:!1,focusOnShow:!0,inline:!1,keepInvalid:!1,datepickerInput:".datepickerinput",keyBinds:{up:function(a){if(a){var b=this.date()||this.getMoment();a.find(".datepicker").is(":visible")?this.date(b.clone().subtract(7,"d")):this.date(b.clone().add(this.stepping(),"m"))}},down:function(a){if(!a)return void this.show();var b=this.date()||this.getMoment();a.find(".datepicker").is(":visible")?this.date(b.clone().add(7,"d")):this.date(b.clone().subtract(this.stepping(),"m"))},"control up":function(a){if(a){var b=this.date()||this.getMoment();a.find(".datepicker").is(":visible")?this.date(b.clone().subtract(1,"y")):this.date(b.clone().add(1,"h"))}},"control down":function(a){if(a){var b=this.date()||this.getMoment();a.find(".datepicker").is(":visible")?this.date(b.clone().add(1,"y")):this.date(b.clone().subtract(1,"h"))}},left:function(a){if(a){var b=this.date()||this.getMoment();a.find(".datepicker").is(":visible")&&this.date(b.clone().subtract(1,"d"))}},right:function(a){if(a){var b=this.date()||this.getMoment();a.find(".datepicker").is(":visible")&&this.date(b.clone().add(1,"d"))}},pageUp:function(a){if(a){var b=this.date()||this.getMoment();a.find(".datepicker").is(":visible")&&this.date(b.clone().subtract(1,"M"))}},pageDown:function(a){if(a){var b=this.date()||this.getMoment();a.find(".datepicker").is(":visible")&&this.date(b.clone().add(1,"M"))}},enter:function(){this.hide()},escape:function(){this.hide()},"control space":function(a){a&&a.find(".timepicker").is(":visible")&&a.find('.btn[data-action="togglePeriod"]').click()},t:function(){this.date(this.getMoment())},delete:function(){this.clear()}},debug:!1,allowInputToggle:!1,disabledTimeIntervals:!1,disabledHours:!1,enabledHours:!1,viewDate:!1},a.fn.datetimepicker});
/**
 * @author: pppscn <35696959@qq.com>
 * @version: v0.0.1
 *
 * @update 2017-05-07 <http://git.oschina.net/pp/fastadmin>
 */

!function ($) {
    'use strict';

    var ColumnsForSearch = [];

    var sprintf = $.fn.bootstrapTable.utils.sprintf;

    var initCommonSearch = function (pColumns, that) {
        var vFormCommon = createFormCommon(pColumns, that);

        var vModal = sprintf("<div class=\"commonsearch-table %s\">", that.options.searchFormVisible ? "" : "hidden");
        vModal += vFormCommon.join('');
        vModal += "</div>";
        that.$container.prepend($(vModal));

        var form = $("form.form-commonsearch", that.$container);

        //绑定日期时间元素事件
        if ($(".datetimepicker", form).size() > 0) {

            require(['bootstrap-datetimepicker'], function () {
                $('.datetimepicker', form).parent().css('position', 'relative');
                $('.datetimepicker', form).datetimepicker({
                    //format: 'YYYY-MM-DD',
                    icons: {
                        time: 'fa fa-clock-o',
                        date: 'fa fa-calendar',
                        up: 'fa fa-chevron-up',
                        down: 'fa fa-chevron-down',
                        previous: 'fa fa-chevron-left',
                        next: 'fa fa-chevron-right',
                        today: 'fa fa-history',
                        clear: 'fa fa-trash',
                        close: 'fa fa-remove'
                    },
                    showTodayButton: true,
                    showClose: true
                });
            });
        }

        // 表单提交
        form.on("submit", function (event) {
            event.preventDefault();
            that.onColumnCommonSearch();
            return false;
        });

        // 重置搜索
        form.on("click", "button[type=reset]", function (event) {
            form[0].reset();
            that.onColumnCommonSearch();
        });

    };

    var createFormCommon = function (pColumns, that) {
        var htmlForm = [];
        var opList = ['=', '>', '>=', '<', '<=', '!=', 'LIKE', 'LIKE %...%', 'NOT LIKE', 'IN(...)', 'NOT IN(...)', 'BETWEEN', 'NOT BETWEEN', 'IS NULL', 'IS NOT NULL'];
        htmlForm.push(sprintf('<form class="form-inline form-commonsearch" action="%s" >', that.options.actionForm));
        htmlForm.push('<fieldset>');
        if (that.options.titleForm.length > 0)
            htmlForm.push(sprintf("<legend>%s</legend>", that.options.titleForm));
        for (var i in pColumns) {
            var vObjCol = pColumns[i];
            if (!vObjCol.checkbox && vObjCol.field !== 'operate' && vObjCol.searchable && vObjCol.operate !== false) {
                var query = Backend.api.query(vObjCol.field);
                query = query ? query : '';
                vObjCol.defaultValue = that.options.renderDefault && query != '' ? query : (typeof vObjCol.defaultValue === 'undefined' ? '' : vObjCol.defaultValue);
                ColumnsForSearch.push(vObjCol);
                htmlForm.push('<div class="form-group" style="margin:5px">');
                htmlForm.push(sprintf('<label for="%s" class="control-label" style="padding:0 10px">%s</label>', vObjCol.field, vObjCol.title));
                vObjCol.operate = (typeof vObjCol.operate === 'undefined' || $.inArray(vObjCol.operate, opList) === -1) ? '=' : vObjCol.operate;
                htmlForm.push(sprintf('<input type="hidden" class="form-control operate" name="field-%s" data-name="%s" value="%s" readonly>', vObjCol.field, vObjCol.field, vObjCol.operate));

                var style = typeof vObjCol.style === 'undefined' ? '' : sprintf('style="%s"', vObjCol.style);
                if (vObjCol.searchList) {
                    if (typeof vObjCol.searchList === 'object' && typeof vObjCol.searchList.then === 'function') {
                        htmlForm.push(sprintf('<select class="form-control" name="%s" %s>%s</select>', vObjCol.field, style, sprintf('<option value="">%s</option>', that.options.formatCommonChoose())));
                        (function (vObjCol, that) {
                            $.when(vObjCol.searchList).done(function (ret) {
                                
                                var isArray = false;
                                if (ret.data && ret.data.searchlist && $.isArray(ret.data.searchlist)) {
                                    var resultlist = {};
                                    $.each(ret.data.searchlist, function (key, value) {
                                        resultlist[value.id] = value.name;
                                    });
                                } else if (ret.constructor === Array || ret.constructor === Object) {
                                    var resultlist = ret;
                                    isArray = ret.constructor === Array ? true : isArray;
                                }
                                var optionList = [];
                                $.each(resultlist, function (key, value) {
                                    var isSelect = (isArray ? value : key) == vObjCol.defaultValue ? 'selected' : '';
                                    optionList.push(sprintf("<option value='" + (isArray ? value : key) + "' %s>" + value + "</option>", isSelect));
                                });
                                $("form.form-commonsearch select[name='" + vObjCol.field + "']", that.$container).append(optionList.join(''));
                            });
                        })(vObjCol, that);
                    } else if (typeof vObjCol.searchList == 'function') {
                        htmlForm.push(vObjCol.searchList.call(this, vObjCol));
                    } else {
                        var isArray = vObjCol.searchList.constructor === Array;
                        var searchList = [];
                        searchList.push(sprintf('<option value="">%s</option>', that.options.formatCommonChoose()));
                        $.each(vObjCol.searchList, function (key, value) {
                            var isSelect = (isArray ? value : key) == vObjCol.defaultValue ? 'selected' : '';
                            searchList.push(sprintf("<option value='" + (isArray ? value : key) + "' %s>" + value + "</option>", isSelect));
                        });
                        htmlForm.push(sprintf('<select class="form-control" name="%s" %s>%s</select>', vObjCol.field, style, searchList.join('')));
                    }
                } else {
                    var placeholder = typeof vObjCol.placeholder === 'undefined' ? vObjCol.title : vObjCol.placeholder;
                    var type = typeof vObjCol.type === 'undefined' ? 'text' : vObjCol.type;
                    var addclass = typeof vObjCol.addclass === 'undefined' ? 'form-control' : 'form-control ' + vObjCol.addclass;
                    var data = typeof vObjCol.data === 'undefined' ? '' : vObjCol.data;
                    var defaultValue = typeof vObjCol.defaultValue === 'undefined' ? '' : vObjCol.defaultValue;
                    if (/BETWEEN$/.test(vObjCol.operate)) {
                        var defaultValueArr = /^.+|.+$/.test(defaultValue) ? defaultValue.split('|') : ['', ''];
                        htmlForm.push(sprintf('<input type="%s" class="%s" name="%s" value="%s" placeholder="%s" id="%s" %s %s>', type, addclass, vObjCol.field, defaultValueArr[0], placeholder, vObjCol.field, style, data));
                        htmlForm.push(sprintf('&nbsp;-&nbsp;<input type="%s" class="%s" name="%s" value="%s" placeholder="%s" id="%s" %s %s>', type, addclass, vObjCol.field, defaultValueArr[1], placeholder, vObjCol.field, style, data));
                    } else {
                        htmlForm.push(sprintf('<input type="%s" class="%s" name="%s" value="%s" placeholder="%s" id="%s" %s %s>', type, addclass, vObjCol.field, defaultValue, placeholder, vObjCol.field, style, data));
                    }
                }

                htmlForm.push('</div>');
            }
        }

        htmlForm.push(createFormBtn(that).join(''));
        htmlForm.push('</fieldset>');
        htmlForm.push('</form>');

        return htmlForm;
    };

    var createFormBtn = function (that) {
        var htmlBtn = [];
        var searchSubmit = that.options.formatCommonSubmitButton();
        var searchReset = that.options.formatCommonResetButton();
        htmlBtn.push('<div class="form-group" style="margin:5px">');
        htmlBtn.push('<div class="col-sm-12 text-center">');
        htmlBtn.push(sprintf('<button type="submit" class="btn btn-success" >%s</button> ', searchSubmit));
        htmlBtn.push(sprintf('<button type="reset" class="btn btn-default" >%s</button> ', searchReset));
        htmlBtn.push('</div>');
        htmlBtn.push('</div>');
        return htmlBtn;
    };

    var isSearchAvailble = function (that) {

        //只支持服务端搜索
        if (!that.options.commonSearch || that.options.sidePagination != 'server' || !that.options.url) {
            return false;
        }

        return true;
    };

    var getSearchQuery = function (that) {
        var op = {};
        var filter = {};
        $("form.form-commonsearch input.operate", that.$container).each(function (i) {
            var name = $(this).data("name");
            var sym = $(this).val();
            var obj = $("[name='" + name + "']");
            if (obj.size() == 0)
                return true;
            var vObjCol = ColumnsForSearch[i];
            if (obj.size() > 1) {
                if (/BETWEEN$/.test(sym)) {
                    var value_begin = $.trim($("[name='" + name + "']:first").val()), value_end = $.trim($("[name='" + name + "']:last").val());
                    if (!value_begin.length || !value_end.length) {
                        return true;
                    }
                    if (typeof vObjCol.process === 'function') {
                        value_begin = vObjCol.process(value_begin, 'begin');
                        value_end = vObjCol.process(value_end, 'end');
                    } else if ($("[name='" + name + "']:first").attr('type') === 'datetime') { //datetime类型字段转换成时间戳
                        var Hms = Moment(value_begin).format("HH:mm:ss");
                        value_begin = parseInt(Moment(value_begin) / 1000);
                        value_end = parseInt(Moment(value_end) / 1000);
                        if (value_begin === value_end && '00:00:00' === Hms) {
                            value_end += 86399;
                        }
                    }
                    var value = value_begin + ',' + value_end;
                } else {
                    var value = $("[name='" + name + "']:checked").val();
                }
            } else {
                var value = (typeof vObjCol.process === 'function') ? vObjCol.process(obj.val()) : (sym == 'LIKE %...%' ? obj.val().replace(/\*/g, '%') : obj.val());
            }
            if (value == '' && sym.indexOf("NULL") == -1) {
                return true;
            }

            op[name] = sym;
            filter[name] = value;
        });
        return {op: op, filter: filter};
    };

    $.extend($.fn.bootstrapTable.defaults, {
        commonSearch: false,
        titleForm: "Common search",
        actionForm: "",
        searchFormVisible: true,
        searchClass: 'searchit',
        renderDefault: true,
        onColumnCommonSearch: function (field, text) {
            return false;
        }
    });

    $.extend($.fn.bootstrapTable.defaults.icons, {
        commonSearchIcon: 'glyphicon-search'
    });

    $.extend($.fn.bootstrapTable.Constructor.EVENTS, {
        'column-common-search.bs.table': 'onColumnCommonSearch'
    });

    $.extend($.fn.bootstrapTable.locales[$.fn.bootstrapTable.defaults.locale], {
        formatCommonSearch: function () {
            return "Common search";
        },
        formatCommonSubmitButton: function () {
            return "Submit";
        },
        formatCommonResetButton: function () {
            return "Reset";
        },
        formatCommonCloseButton: function () {
            return "Close";
        },
        formatCommonChoose: function () {
            return "Choose";
        }
    });

    $.extend($.fn.bootstrapTable.defaults, $.fn.bootstrapTable.locales);

    var BootstrapTable = $.fn.bootstrapTable.Constructor,
            _initToolbar = BootstrapTable.prototype.initToolbar,
            _load = BootstrapTable.prototype.load,
            _initSearch = BootstrapTable.prototype.initSearch;

    BootstrapTable.prototype.initToolbar = function () {
        _initToolbar.apply(this, Array.prototype.slice.apply(arguments));

        if (!isSearchAvailble(this)) {
            return;
        }

        var that = this,
                html = [];
        html.push(sprintf('<div class="columns-%s pull-%s" style="margin-top:10px;">', this.options.buttonsAlign, this.options.buttonsAlign));
        html.push(sprintf('<button class="btn btn-default%s' + '" type="button" name="commonSearch" title="%s">', that.options.iconSize === undefined ? '' : ' btn-' + that.options.iconSize, that.options.formatCommonSearch()));
        html.push(sprintf('<i class="%s %s"></i>', that.options.iconsPrefix, that.options.icons.commonSearchIcon))
        html.push('</button></div>');

        that.$toolbar.prepend(html.join(''));

        initCommonSearch(that.columns, that);

        var searchContainer = $(".commonsearch-table", that.$container);

        that.$toolbar.find('button[name="commonSearch"]')
                .off('click').on('click', function () {
            searchContainer.toggleClass("hidden");
            return;
        });

        that.$container.on("click", "." + that.options.searchClass, function () {
            var obj = $("form [name='" + $(this).data("field") + "']", searchContainer);
            if (obj.size() > 0) {
                obj.val($(this).data("value"));
                $("form", searchContainer).trigger("submit");
            }
        });

        var searchQuery = getSearchQuery(this);
        var queryParams = this.options.queryParams;
        this.options.queryParams = function () {
            var params = queryParams.apply(this, arguments);
            params.filter = JSON.stringify($.extend(params.filter || {}, searchQuery.filter));
            params.op = JSON.stringify($.extend(params.op || {}, searchQuery.op));
            return params;
        };

    };

    BootstrapTable.prototype.load = function (data) {
        _load.apply(this, Array.prototype.slice.apply(arguments));

        if (!isSearchAvailble(this)) {
            return;
        }
    };

    BootstrapTable.prototype.initSearch = function () {
        _initSearch.apply(this, Array.prototype.slice.apply(arguments));

        if (!isSearchAvailble(this)) {
            return;
        }

        var that = this;
        var fp = $.isEmptyObject(this.filterColumnsPartial) ? null : this.filterColumnsPartial;
        this.data = fp ? $.grep(this.data, function (item, i) {
            for (var key in fp) {
                var fval = fp[key].toLowerCase();
                var value = item[key];
                value = $.fn.bootstrapTable.utils.calculateObjectValue(that.header,
                        that.header.formatters[$.inArray(key, that.header.fields)],
                        [value, item, i], value);

                if (!($.inArray(key, that.header.fields) !== -1 &&
                        (typeof value === 'string' || typeof value === 'number') &&
                        (value + '').toLowerCase().indexOf(fval) !== -1)) {
                    return false;
                }
            }
            return true;
        }) : this.data;
    };

    BootstrapTable.prototype.onColumnCommonSearch = function (event) {
        if (typeof event === 'undefined') {
            var searchquery = getSearchQuery(this);
            // 追加查询关键字
            this.options.pageNumber = 1;
            this.options.queryParams = function (params) {
                return {
                    search: params.search,
                    sort: params.sort,
                    order: params.order,
                    filter: JSON.stringify(searchquery.filter),
                    op: JSON.stringify(searchquery.op),
                    offset: params.offset,
                    limit: params.limit,
                };
            };
            this.refresh({query: {filter: JSON.stringify(searchquery.filter), op: JSON.stringify(searchquery.op)}});

        } else {
            var text = $.trim($(event.currentTarget).val());
            var $field = $(event.currentTarget)[0].id;

            if ($.isEmptyObject(this.filterColumnsPartial)) {
                this.filterColumnsPartial = {};
            }
            if (text) {
                this.filterColumnsPartial[$field] = text;
            } else {
                delete this.filterColumnsPartial[$field];
            }
            this.options.pageNumber = 1;
            this.onSearch(event);
//        this.updatePagination();
            this.trigger('column-common-search', $field, text);
        }
    };
}(jQuery);

define("bootstrap-table-commonsearch", ["bootstrap-table"], (function (global) {
    return function () {
        var ret, fn;
        return ret || global.$.fn.bootstrapTable.defaults;
    };
}(this)));

/*!art-template - Template Engine | http://aui.github.com/artTemplate/*/
!function(){function a(a){return a.replace(t,"").replace(u,",").replace(v,"").replace(w,"").replace(x,"").split(y)}function b(a){return"'"+a.replace(/('|\\)/g,"\\$1").replace(/\r/g,"\\r").replace(/\n/g,"\\n")+"'"}function c(c,d){function e(a){return m+=a.split(/\n/).length-1,k&&(a=a.replace(/\s+/g," ").replace(/<!--[\w\W]*?-->/g,"")),a&&(a=s[1]+b(a)+s[2]+"\n"),a}function f(b){var c=m;if(j?b=j(b,d):g&&(b=b.replace(/\n/g,function(){return m++,"$line="+m+";"})),0===b.indexOf("=")){var e=l&&!/^=[=#]/.test(b);if(b=b.replace(/^=[=#]?|[\s;]*$/g,""),e){var f=b.replace(/\s*\([^\)]+\)/,"");n[f]||/^(include|print)$/.test(f)||(b="$escape("+b+")")}else b="$string("+b+")";b=s[1]+b+s[2]}return g&&(b="$line="+c+";"+b),r(a(b),function(a){if(a&&!p[a]){var b;b="print"===a?u:"include"===a?v:n[a]?"$utils."+a:o[a]?"$helpers."+a:"$data."+a,w+=a+"="+b+",",p[a]=!0}}),b+"\n"}var g=d.debug,h=d.openTag,i=d.closeTag,j=d.parser,k=d.compress,l=d.escape,m=1,p={$data:1,$filename:1,$utils:1,$helpers:1,$out:1,$line:1},q="".trim,s=q?["$out='';","$out+=",";","$out"]:["$out=[];","$out.push(",");","$out.join('')"],t=q?"$out+=text;return $out;":"$out.push(text);",u="function(){var text=''.concat.apply('',arguments);"+t+"}",v="function(filename,data){data=data||$data;var text=$utils.$include(filename,data,$filename);"+t+"}",w="'use strict';var $utils=this,$helpers=$utils.$helpers,"+(g?"$line=0,":""),x=s[0],y="return new String("+s[3]+");";r(c.split(h),function(a){a=a.split(i);var b=a[0],c=a[1];1===a.length?x+=e(b):(x+=f(b),c&&(x+=e(c)))});var z=w+x+y;g&&(z="try{"+z+"}catch(e){throw {filename:$filename,name:'Render Error',message:e.message,line:$line,source:"+b(c)+".split(/\\n/)[$line-1].replace(/^\\s+/,'')};}");try{var A=new Function("$data","$filename",z);return A.prototype=n,A}catch(a){throw a.temp="function anonymous($data,$filename) {"+z+"}",a}}var d=function(a,b){return"string"==typeof b?q(b,{filename:a}):g(a,b)};d.version="3.0.0",d.config=function(a,b){e[a]=b};var e=d.defaults={openTag:"<%",closeTag:"%>",escape:!0,cache:!0,compress:!1,parser:null},f=d.cache={};d.render=function(a,b){return q(a)(b)};var g=d.renderFile=function(a,b){var c=d.get(a)||p({filename:a,name:"Render Error",message:"Template not found"});return b?c(b):c};d.get=function(a){var b;if(f[a])b=f[a];else if("object"==typeof document){var c=document.getElementById(a);if(c){var d=(c.value||c.innerHTML).replace(/^\s*|\s*$/g,"");b=q(d,{filename:a})}}return b};var h=function(a,b){return"string"!=typeof a&&(b=typeof a,"number"===b?a+="":a="function"===b?h(a.call(a)):""),a},i={"<":"&#60;",">":"&#62;",'"':"&#34;","'":"&#39;","&":"&#38;"},j=function(a){return i[a]},k=function(a){return h(a).replace(/&(?![\w#]+;)|[<>"']/g,j)},l=Array.isArray||function(a){return"[object Array]"==={}.toString.call(a)},m=function(a,b){var c,d;if(l(a))for(c=0,d=a.length;c<d;c++)b.call(a,a[c],c,a);else for(c in a)b.call(a,a[c],c)},n=d.utils={$helpers:{},$include:g,$string:h,$escape:k,$each:m};d.helper=function(a,b){o[a]=b};var o=d.helpers=n.$helpers;d.onerror=function(a){var b="Template Error\n\n";for(var c in a)b+="<"+c+">\n"+a[c]+"\n\n";"object"==typeof console&&console.error(b)};var p=function(a){return d.onerror(a),function(){return"{Template Error}"}},q=d.compile=function(a,b){function d(c){try{return new i(c,h)+""}catch(d){return b.debug?p(d)():(b.debug=!0,q(a,b)(c))}}b=b||{};for(var g in e)void 0===b[g]&&(b[g]=e[g]);var h=b.filename;try{var i=c(a,b)}catch(a){return a.filename=h||"anonymous",a.name="Syntax Error",p(a)}return d.prototype=i.prototype,d.toString=function(){return i.toString()},h&&b.cache&&(f[h]=d),d},r=n.$each,s="break,case,catch,continue,debugger,default,delete,do,else,false,finally,for,function,if,in,instanceof,new,null,return,switch,this,throw,true,try,typeof,var,void,while,with,abstract,boolean,byte,char,class,const,double,enum,export,extends,final,float,goto,implements,import,int,interface,long,native,package,private,protected,public,short,static,super,synchronized,throws,transient,volatile,arguments,let,yield,undefined",t=/\/\*[\w\W]*?\*\/|\/\/[^\n]*\n|\/\/[^\n]*$|"(?:[^"\\]|\\[\w\W])*"|'(?:[^'\\]|\\[\w\W])*'|\s*\.\s*[$\w\.]+/g,u=/[^\w$]+/g,v=new RegExp(["\\b"+s.replace(/,/g,"\\b|\\b")+"\\b"].join("|"),"g"),w=/^\d[^,]*|,\d[^,]*/g,x=/^,+|,+$/g,y=/^$|,+/;"object"==typeof exports&&"undefined"!=typeof module?module.exports=d:"function"==typeof define?define('template',[],function(){return d}):this.template=d}();
/**
 * 将BootstrapTable的行使用自定义的模板来渲染
 * 
 * @author: karson
 * @version: v0.0.1
 *
 * @update 2017-06-24 <http://github.com/karsonzhang/fastadmin>
 */

!function ($) {
    'use strict';

    $.extend($.fn.bootstrapTable.defaults, {
        //是否启用模板渲染
        templateView: false,
        //数据格式化的模板ID或格式函数
        templateFormatter: "itemtpl",
        //添加的父类的class
        templateParentClass: "row row-flex",
        //向table添加的class
        templateTableClass: "table-template",

    });

    var BootstrapTable = $.fn.bootstrapTable.Constructor,
            _initContainer = BootstrapTable.prototype.initContainer,
            _initBody = BootstrapTable.prototype.initBody,
            _initRow = BootstrapTable.prototype.initRow;

    BootstrapTable.prototype.initContainer = function () {
        _initContainer.apply(this, Array.prototype.slice.apply(arguments));
        var that = this;
        if (!that.options.templateView) {
            return;
        }

    };

    BootstrapTable.prototype.initBody = function () {
        var that = this;
        $.extend(that.options, {
            showHeader: !that.options.templateView ? $.fn.bootstrapTable.defaults.showHeader : false,
            showFooter: !that.options.templateView ? $.fn.bootstrapTable.defaults.showFooter : false,
        });
        $(that.$el).toggleClass(that.options.templateTableClass, that.options.templateView);

        _initBody.apply(this, Array.prototype.slice.apply(arguments));

        if (!that.options.templateView) {
            return;
        } else {
            //由于Bootstrap是基于Table的，添加一个父类容器
            $("> *:not(.no-records-found)", that.$body).wrapAll($("<div />").addClass(that.options.templateParentClass));
        }
    };

    BootstrapTable.prototype.initRow = function (item, i, data, parentDom) {
        var that = this;
        //如果未启用则使用原生的initRow方法
        if (!that.options.templateView) {
            return _initRow.apply(that, Array.prototype.slice.apply(arguments));
        }
        var $content = '';
        if (typeof that.options.templateFormatter === 'function') {
            $content = that.options.templateFormatter.call(that, item, i, data);
        } else {
            var Template = require('template');
            $content = Template(that.options.templateFormatter, {item: item, i: i, data: data});
        }
        return $content;
    };

}(jQuery);

define("bootstrap-table-template", ["bootstrap-table","template"], (function (global) {
    return function () {
        var ret, fn;
        return ret || global.$.fn.bootstrapTable.defaults;
    };
}(this)));

define('table',['jquery', 'bootstrap', 'moment', 'moment/locale/zh-cn', 'bootstrap-table', 'bootstrap-table-lang', 'bootstrap-table-mobile', 'bootstrap-table-export', 'bootstrap-table-commonsearch', 'bootstrap-table-template'], function ($, undefined, Moment) {
    var Table = {
        list: {},
        // Bootstrap-table 基础配置
        defaults: {
            url: '',
            sidePagination: 'server',
            method: 'get',
            toolbar: "#toolbar",
            search: true,
            cache: false,
            commonSearch: true,
            searchFormVisible: false,
            titleForm: '', //为空则不显示标题，不定义默认显示：普通搜索
            idTable: 'commonTable',
            showExport: true,
            exportDataType: "all",
            exportTypes: ['json', 'xml', 'csv', 'txt', 'doc', 'excel'],
            pageSize: 10,
            pageList: [10, 25, 50, 'All'],
            pagination: true,
            clickToSelect: true,
            showRefresh: false,
            locale: 'zh-CN',
            showToggle: true,
            showColumns: true,
            pk: 'id',
            sortName: 'id',
            sortOrder: 'desc',
            paginationFirstText: __("First"),
            paginationPreText: __("Previous"),
            paginationNextText: __("Next"),
            paginationLastText: __("Last"),
            mobileResponsive: true,
            cardView: true,
            checkOnInit: true,
            escape: true,
            extend: {
                index_url: '',
                add_url: '',
                edit_url: '',
                del_url: '',
                multi_url: '',
                dragsort_url: 'ajax/weigh',
            }
        },
        // Bootstrap-table 列配置
        columnDefaults: {
            align: 'center',
            valign: 'middle',
        },
        config: {
            firsttd: 'tbody tr td:first-child:not(:has(div.card-views))',
            toolbar: '.toolbar',
            refreshbtn: '.btn-refresh',
            addbtn: '.btn-add',
            editbtn: '.btn-edit',
            delbtn: '.btn-del',
            multibtn: '.btn-multi',
            disabledbtn: '.btn-disabled',
            editonebtn: '.btn-editone',
            dragsortfield: 'weigh',
        },
        api: {
            init: function (defaults, columnDefaults, locales) {
                defaults = defaults ? defaults : {};
                columnDefaults = columnDefaults ? columnDefaults : {};
                locales = locales ? locales : {};
                // 写入bootstrap-table默认配置
                $.extend(true, $.fn.bootstrapTable.defaults, Table.defaults, defaults);
                // 写入bootstrap-table column配置
                $.extend($.fn.bootstrapTable.columnDefaults, Table.columnDefaults, columnDefaults);
                // 写入bootstrap-table locale配置
                $.extend($.fn.bootstrapTable.locales[Table.defaults.locale], {
                    formatCommonSearch: function () {
                        return __('Common search');
                    },
                    formatCommonSubmitButton: function () {
                        return __('Submit');
                    },
                    formatCommonResetButton: function () {
                        return __('Reset');
                    },
                    formatCommonCloseButton: function () {
                        return __('Close');
                    },
                    formatCommonChoose: function () {
                        return __('Choose');
                    }
                }, locales);
            },
            // 绑定事件
            bindevent: function (table) {
                //Bootstrap-table的父元素,包含table,toolbar,pagnation
                var parenttable = table.closest('.bootstrap-table');
                //Bootstrap-table配置
                var options = table.bootstrapTable('getOptions');
                //Bootstrap操作区
                var toolbar = $(options.toolbar, parenttable);
                //当刷新表格时
                table.on('load-error.bs.table', function (status, res) {
                    Toastr.error(__('Unknown data format'));
                });
                //当刷新表格时
                table.on('refresh.bs.table', function (e, settings, data) {
                    $(Table.config.refreshbtn, toolbar).find(".fa").addClass("fa-spin");
                });
                //当双击单元格时
                table.on('dbl-click-row.bs.table', function (e, row, element, field) {
                    $(Table.config.editonebtn, element).trigger("click");
                });
                //当内容渲染完成后
                table.on('post-body.bs.table', function (e, settings, json, xhr) {
                    $(Table.config.refreshbtn, toolbar).find(".fa").removeClass("fa-spin");
                    $(Table.config.disabledbtn, toolbar).toggleClass('disabled', true);
                    if ($(Table.config.firsttd, table).find("input[type='checkbox'][data-index]").size() > 0) {
                        // 挺拽选择,需要重新绑定事件
                        require(['drag', 'drop'], function () {
                            $(Table.config.firsttd, table).drag("start", function (ev, dd) {
                                return $('<div class="selection" />').css('opacity', .65).appendTo(document.body);
                            }).drag(function (ev, dd) {
                                $(dd.proxy).css({
                                    top: Math.min(ev.pageY, dd.startY),
                                    left: Math.min(ev.pageX, dd.startX),
                                    height: Math.abs(ev.pageY - dd.startY),
                                    width: Math.abs(ev.pageX - dd.startX)
                                });
                            }).drag("end", function (ev, dd) {
                                $(dd.proxy).remove();
                            });
                            $(Table.config.firsttd, table).drop("start", function () {
                                Table.api.toggleattr(this);
                            }).drop(function () {
                                Table.api.toggleattr(this);
                            }).drop("end", function () {
                                Table.api.toggleattr(this);
                            });
                            $.drop({
                                multi: true
                            });
                        });
                    }
                });
                // 处理选中筛选框后按钮的状态统一变更
                table.on('check.bs.table uncheck.bs.table check-all.bs.table uncheck-all.bs.table fa.event.check', function () {
                    var ids = Table.api.selectedids(table);
                    $(Table.config.disabledbtn, toolbar).toggleClass('disabled', !ids.length);
                });
                // 刷新按钮事件
                $(toolbar).on('click', Table.config.refreshbtn, function () {
                    table.bootstrapTable('refresh');
                });
                // 添加按钮事件
                $(toolbar).on('click', Table.config.addbtn, function () {
                    var ids = Table.api.selectedids(table);
                    Fast.api.open(options.extend.add_url + (ids.length > 0 ? (options.extend.add_url.match(/(\?|&)+/) ? "&ids=" : "/ids/") + ids.join(",") : ''), __('Add'));
                });
                // 批量编辑按钮事件
                $(toolbar).on('click', Table.config.editbtn, function () {
                    var ids = Table.api.selectedids(table);
                    //循环弹出多个编辑框
                    $.each(ids, function (i, j) {
                        Fast.api.open(options.extend.edit_url + (options.extend.edit_url.match(/(\?|&)+/) ? "&ids=" : "/ids/") + j, __('Edit'));
                    });
                });
                // 批量操作按钮事件
                $(toolbar).on('click', Table.config.multibtn, function () {
                    var ids = Table.api.selectedids(table);
                    Table.api.multi($(this).data("action"), ids, table, this);
                });
                // 批量删除按钮事件
                $(toolbar).on('click', Table.config.delbtn, function () {
                    var that = this;
                    var ids = Table.api.selectedids(table);
                    var index = Layer.confirm(
                            __('Are you sure you want to delete the %s selected item?', ids.length),
                            {icon: 3, title: __('Warning'), offset: 0, shadeClose: true},
                            function () {
                                Table.api.multi("del", ids, table, that);
                                Layer.close(index);
                            }
                    );
                });
                // 拖拽排序
                require(['dragsort'], function () {
                    //绑定拖动排序
                    $("tbody", table).dragsort({
                        itemSelector: 'tr',
                        dragSelector: "a.btn-dragsort",
                        dragEnd: function () {
                            var data = table.bootstrapTable('getData');
                            var current = data[parseInt($(this).data("index"))];
                            var options = table.bootstrapTable('getOptions');
                            //改变的值和改变的ID集合
                            var ids = $.map($("tbody tr:visible", table), function (tr) {
                                return data[parseInt($(tr).data("index"))][options.pk];
                            });
                            var changeid = current[options.pk];
                            var pid = typeof current.pid != 'undefined' ? current.pid : '';
                            var params = {
                                url: table.bootstrapTable('getOptions').extend.dragsort_url,
                                data: {
                                    ids: ids.join(','),
                                    changeid: changeid,
                                    pid: pid,
                                    field: Table.config.dragsortfield,
                                    orderway: options.sortOrder,
                                    table: options.extend.table
                                }
                            };
                            Fast.api.ajax(params, function (data) {
                                table.bootstrapTable('refresh');
                            });
                        },
                        placeHolderTemplate: ""
                    });
                });
                $(table).on("click", "input[data-id][name='checkbox']", function (e) {
                    table.trigger('fa.event.check');
                });
                $(table).on("click", "[data-id].btn-change", function (e) {
                    e.preventDefault();
                    Table.api.multi($(this).data("action") ? $(this).data("action") : '', [$(this).data("id")], table, this);
                });
                $(table).on("click", "[data-id].btn-edit", function (e) {
                    e.preventDefault();
                    Fast.api.open(options.extend.edit_url + (options.extend.edit_url.match(/(\?|&)+/) ? "&ids=" : "/ids/") + $(this).data("id"), __('Edit'));
                });
                $(table).on("click", "[data-id].btn-del", function (e) {
                    e.preventDefault();
                    var id = $(this).data("id");
                    var that = this;
                    var index = Layer.confirm(
                            __('Are you sure you want to delete this item?'),
                            {icon: 3, title: __('Warning'), shadeClose: true},
                            function () {
                                Table.api.multi("del", id, table, that);
                                Layer.close(index);
                            }
                    );
                });
                var id = table.attr("id");
                Table.list[id] = table;
                return table;
            },
            // 批量操作请求
            multi: function (action, ids, table, element) {
                var options = table.bootstrapTable('getOptions');
                var data = element ? $(element).data() : {};
                var url = typeof data.url !== "undefined" ? data.url : (action == "del" ? options.extend.del_url : options.extend.multi_url);
                url = url + (url.match(/(\?|&)+/) ? "&ids=" : "/ids/") + ($.isArray(ids) ? ids.join(",") : ids);
                var params = typeof data.params !== "undefined" ? (typeof data.params == 'object' ? $.param(data.params) : data.params) : '';
                var options = {url: url, data: {action: action, ids: ids, params: params}};
                Fast.api.ajax(options, function (data) {
                    table.bootstrapTable('refresh');
                });
            },
            // 单元格元素事件
            events: {
                operate: {
                    'click .btn-editone': function (e, value, row, index) {
                        e.stopPropagation();
                        var options = $(this).closest('table').bootstrapTable('getOptions');
                        Fast.api.open(options.extend.edit_url + (options.extend.edit_url.match(/(\?|&)+/) ? "&ids=" : "/ids/") + row[options.pk], __('Edit'));
                    },
                    'click .btn-delone': function (e, value, row, index) {
                        e.stopPropagation();
                        var that = this;
                        var top = $(that).offset().top - $(window).scrollTop();
                        var left = $(that).offset().left - $(window).scrollLeft() - 260;
                        if (top + 154 > $(window).height()) {
                            top = top - 154;
                        }
                        if ($(window).width() < 480) {
                            top = left = undefined;
                        }
                        var index = Layer.confirm(
                                __('Are you sure you want to delete this item?'),
                                {icon: 3, title: __('Warning'), offset: [top, left], shadeClose: true},
                                function () {
                                    var table = $(that).closest('table');
                                    var options = table.bootstrapTable('getOptions');
                                    Table.api.multi("del", row[options.pk], table, that);
                                    Layer.close(index);
                                }
                        );
                    }
                }
            },
            auth: {
                check: function (name) {

                }
            },
            // 单元格数据格式化
            formatter: {
                icon: function (value, row, index) {
                    if (!value)
                        return '';
                    value = value.indexOf(" ") > -1 ? value : "fa fa-" + value;
                    //渲染fontawesome图标
                    return '<i class="' + value + '"></i> ' + value;
                },
                image: function (value, row, index, custom) {
                    var classname = typeof custom !== 'undefined' ? custom : 'img-sm img-center';
                    return '<img class="' + classname + '" src="' + Fast.api.cdnurl(value) + '" />';
                },
                images: function (value, row, index, custom) {
                    var classname = typeof custom !== 'undefined' ? custom : 'img-sm img-center';
                    var arr = value.split(',');
                    var html = [];
                    $.each(arr, function (i, value) {
                        html.push('<img class="' + classname + '" src="' + Fast.api.cdnurl(value) + '" />');
                    });
                    return html.join(' ');
                },
                status: function (value, row, index, custom) {
                    //颜色状态数组,可使用red/yellow/aqua/blue/navy/teal/olive/lime/fuchsia/purple/maroon
                    var colorArr = {normal: 'success', hidden: 'grey', deleted: 'danger', locked: 'info'};
                    //如果有自定义状态,可以按需传入
                    if (typeof custom !== 'undefined') {
                        colorArr = $.extend(colorArr, custom);
                    }
                    value = value.toString();
                    var color = value && typeof colorArr[value] !== 'undefined' ? colorArr[value] : 'primary';
                    value = value.charAt(0).toUpperCase() + value.slice(1);
                    //渲染状态
                    var html = '<span class="text-' + color + '"><i class="fa fa-circle"></i> ' + __(value) + '</span>';
                    return html;
                },
                url: function (value, row, index) {
                    return '<div class="input-group input-group-sm" style="width:250px;"><input type="text" class="form-control input-sm" value="' + value + '"><span class="input-group-btn input-group-sm"><a href="' + value + '" target="_blank" class="btn btn-default btn-sm"><i class="fa fa-link"></i></a></span></div>';
                },
                search: function (value, row, index) {
                    return '<a href="javascript:;" class="searchit" data-field="' + this.field + '" data-value="' + value + '">' + value + '</a>';
                },
                addtabs: function (value, row, index, url) {
                    return '<a href="' + url + '" class="addtabsit" title="' + __("Search %s", value) + '">' + value + '</a>';
                },
                flag: function (value, row, index, custom) {
                    var colorArr = {index: 'success', hot: 'warning', recommend: 'danger', 'new': 'info'};
                    //如果有自定义状态,可以按需传入
                    if (typeof custom !== 'undefined') {
                        colorArr = $.extend(colorArr, custom);
                    }
                    //渲染Flag
                    var html = [];
                    var arr = value.split(',');
                    $.each(arr, function (i, value) {
                        value = value.toString();
                        if (value == '')
                            return true;
                        var color = value && typeof colorArr[value] !== 'undefined' ? colorArr[value] : 'primary';
                        value = value.charAt(0).toUpperCase() + value.slice(1);
                        html.push('<span class="label label-' + color + '">' + __(value) + '</span>');
                    });
                    return html.join(' ');
                },
                label: function (value, row, index, custom) {
                    var colorArr = ['success', 'warning', 'danger', 'info'];
                    //渲染Flag
                    var html = [];
                    var arr = value.split(',');
                    $.each(arr, function (i, value) {
                        value = value.toString();
                        var color = colorArr[i % colorArr.length];
                        html.push('<span class="label label-' + color + '">' + __(value) + '</span>');
                    });
                    return html.join(' ');
                },
                datetime: function (value, row, index) {
                    return value ? Moment(parseInt(value) * 1000).format("YYYY-MM-DD HH:mm:ss") : __('None');
                },
                operate: function (value, row, index) {
                    var table = this.table;
                    // 操作配置
                    var options = table ? table.bootstrapTable('getOptions') : {};
                    // 默认按钮组
                    var buttons = $.extend([], this.buttons || []);
                    buttons.push({name: 'dragsort', icon: 'fa fa-arrows', classname: 'btn btn-xs btn-primary btn-dragsort'});
                    buttons.push({name: 'edit', icon: 'fa fa-pencil', classname: 'btn btn-xs btn-success btn-editone'});
                    buttons.push({name: 'del', icon: 'fa fa-trash', classname: 'btn btn-xs btn-danger btn-delone'});
                    var html = [];
                    $.each(buttons, function (i, j) {
                        var attr = table.data("operate-" + j.name);
                        if ((typeof attr === 'undefined' || attr) || (j.name === 'dragsort' && typeof row[Table.config.dragsortfield] == 'undefined')) {
                            if (['add', 'edit', 'del', 'multi'].indexOf(j.name) > -1 && !options.extend[j.name + "_url"]) {
                                return true;
                            }
                            //自动加上ids
                            j.url = j.url ? j.url + (j.url.match(/(\?|&)+/) ? "&ids=" : "/ids/") + row[options.pk] : '';
                            url = j.url ? Fast.api.fixurl(j.url) : 'javascript:;';
                            classname = j.classname ? j.classname : 'btn-primary btn-' + name + 'one';
                            icon = j.icon ? j.icon : '';
                            text = j.text ? j.text : '';
                            title = j.title ? j.title : text;
                            html.push('<a href="' + url + '" class="' + classname + '" title="' + title + '"><i class="' + icon + '"></i>' + (text ? ' ' + text : '') + '</a>');
                        }
                    });
                    return html.join(' ');
                }
            },
            // 获取选中的条目ID集合
            selectedids: function (table) {
                var options = table.bootstrapTable('getOptions');
                if (options.templateView) {
                    return $.map($("input[data-id][name='checkbox']:checked"), function (dom) {
                        return $(dom).data("id");
                    });
                } else {
                    return $.map(table.bootstrapTable('getSelections'), function (row) {
                        return row[options.pk];
                    });
                }
            },
            // 切换复选框状态
            toggleattr: function (table) {
                $("input[type='checkbox']", table).trigger('click');
            }
        },
    };
    return Table;
});

/**
 * mOxie - multi-runtime File API & XMLHttpRequest L2 Polyfill
 * v1.5.3
 *
 * Copyright 2013, Moxiecode Systems AB
 * Released under GPL License.
 *
 * License: http://www.plupload.com/license
 * Contributing: http://www.plupload.com/contributing
 *
 * Date: 2017-02-02
 */
!function(e,t){var i=function(){var e={};return t.apply(e,arguments),e.moxie};"function"==typeof define&&define.amd?define("moxie",[],i):"object"==typeof module&&module.exports?module.exports=i():e.moxie=i()}(this||window,function(){!function(e,t){"use strict";function i(e,t){for(var i,n=[],r=0;r<e.length;++r){if(i=s[e[r]]||o(e[r]),!i)throw"module definition dependecy not found: "+e[r];n.push(i)}t.apply(null,n)}function n(e,n,r){if("string"!=typeof e)throw"invalid module definition, module id must be defined and be a string";if(n===t)throw"invalid module definition, dependencies must be specified";if(r===t)throw"invalid module definition, definition function must be specified";i(n,function(){s[e]=r.apply(null,arguments)})}function r(e){return!!s[e]}function o(t){for(var i=e,n=t.split(/[.\/]/),r=0;r<n.length;++r){if(!i[n[r]])return;i=i[n[r]]}return i}function a(i){for(var n=0;n<i.length;n++){for(var r=e,o=i[n],a=o.split(/[.\/]/),u=0;u<a.length-1;++u)r[a[u]]===t&&(r[a[u]]={}),r=r[a[u]];r[a[a.length-1]]=s[o]}}var s={};n("moxie/core/utils/Basic",[],function(){function e(e){var t;return e===t?"undefined":null===e?"null":e.nodeType?"node":{}.toString.call(e).match(/\s([a-z|A-Z]+)/)[1].toLowerCase()}function t(){return a(!1,!1,arguments)}function i(){return a(!0,!1,arguments)}function n(){return a(!1,!0,arguments)}function r(){return a(!0,!0,arguments)}function o(i){switch(e(i)){case"array":return Array.prototype.slice.call(i);case"object":return t({},i)}return i}function a(t,i,n){var r,s=n[0];return u(n,function(n,c){c>0&&u(n,function(n,u){var c=-1!==m(e(n),["array","object"]);return n===r||t&&s[u]===r?!0:(c&&i&&(n=o(n)),e(s[u])===e(n)&&c?a(t,i,[s[u],n]):s[u]=n,void 0)})}),s}function s(e,t){function i(){this.constructor=e}for(var n in t)({}).hasOwnProperty.call(t,n)&&(e[n]=t[n]);return i.prototype=t.prototype,e.prototype=new i,e.__parent__=t.prototype,e}function u(e,t){var i,n,r,o;if(e){try{i=e.length}catch(a){i=o}if(i===o||"number"!=typeof i){for(n in e)if(e.hasOwnProperty(n)&&t(e[n],n)===!1)return}else for(r=0;i>r;r++)if(t(e[r],r)===!1)return}}function c(t){var i;if(!t||"object"!==e(t))return!0;for(i in t)return!1;return!0}function l(t,i){function n(r){"function"===e(t[r])&&t[r](function(e){++r<o&&!e?n(r):i(e)})}var r=0,o=t.length;"function"!==e(i)&&(i=function(){}),t&&t.length||i(),n(r)}function d(e,t){var i=0,n=e.length,r=new Array(n);u(e,function(e,o){e(function(e){if(e)return t(e);var a=[].slice.call(arguments);a.shift(),r[o]=a,i++,i===n&&(r.unshift(null),t.apply(this,r))})})}function m(e,t){if(t){if(Array.prototype.indexOf)return Array.prototype.indexOf.call(t,e);for(var i=0,n=t.length;n>i;i++)if(t[i]===e)return i}return-1}function h(t,i){var n=[];"array"!==e(t)&&(t=[t]),"array"!==e(i)&&(i=[i]);for(var r in t)-1===m(t[r],i)&&n.push(t[r]);return n.length?n:!1}function f(e,t){var i=[];return u(e,function(e){-1!==m(e,t)&&i.push(e)}),i.length?i:null}function p(e){var t,i=[];for(t=0;t<e.length;t++)i[t]=e[t];return i}function g(e){return e?String.prototype.trim?String.prototype.trim.call(e):e.toString().replace(/^\s*/,"").replace(/\s*$/,""):e}function x(e){if("string"!=typeof e)return e;var t,i={t:1099511627776,g:1073741824,m:1048576,k:1024};return e=/^([0-9\.]+)([tmgk]?)$/.exec(e.toLowerCase().replace(/[^0-9\.tmkg]/g,"")),t=e[2],e=+e[1],i.hasOwnProperty(t)&&(e*=i[t]),Math.floor(e)}function v(t){var i=[].slice.call(arguments,1);return t.replace(/%[a-z]/g,function(){var t=i.shift();return"undefined"!==e(t)?t:""})}function w(e,t){var i=this;setTimeout(function(){e.call(i)},t||1)}var y=function(){var e=0;return function(t){var i,n=(new Date).getTime().toString(32);for(i=0;5>i;i++)n+=Math.floor(65535*Math.random()).toString(32);return(t||"o_")+n+(e++).toString(32)}}();return{guid:y,typeOf:e,extend:t,extendIf:i,extendImmutable:n,extendImmutableIf:r,inherit:s,each:u,isEmptyObj:c,inSeries:l,inParallel:d,inArray:m,arrayDiff:h,arrayIntersect:f,toArray:p,trim:g,sprintf:v,parseSizeStr:x,delay:w}}),n("moxie/core/utils/Encode",[],function(){var e=function(e){return unescape(encodeURIComponent(e))},t=function(e){return decodeURIComponent(escape(e))},i=function(e,i){if("function"==typeof window.atob)return i?t(window.atob(e)):window.atob(e);var n,r,o,a,s,u,c,l,d="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",m=0,h=0,f="",p=[];if(!e)return e;e+="";do a=d.indexOf(e.charAt(m++)),s=d.indexOf(e.charAt(m++)),u=d.indexOf(e.charAt(m++)),c=d.indexOf(e.charAt(m++)),l=a<<18|s<<12|u<<6|c,n=255&l>>16,r=255&l>>8,o=255&l,p[h++]=64==u?String.fromCharCode(n):64==c?String.fromCharCode(n,r):String.fromCharCode(n,r,o);while(m<e.length);return f=p.join(""),i?t(f):f},n=function(t,i){if(i&&(t=e(t)),"function"==typeof window.btoa)return window.btoa(t);var n,r,o,a,s,u,c,l,d="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",m=0,h=0,f="",p=[];if(!t)return t;do n=t.charCodeAt(m++),r=t.charCodeAt(m++),o=t.charCodeAt(m++),l=n<<16|r<<8|o,a=63&l>>18,s=63&l>>12,u=63&l>>6,c=63&l,p[h++]=d.charAt(a)+d.charAt(s)+d.charAt(u)+d.charAt(c);while(m<t.length);f=p.join("");var g=t.length%3;return(g?f.slice(0,g-3):f)+"===".slice(g||3)};return{utf8_encode:e,utf8_decode:t,atob:i,btoa:n}}),n("moxie/core/utils/Env",["moxie/core/utils/Basic"],function(e){function t(e,t,i){var n=0,r=0,o=0,a={dev:-6,alpha:-5,a:-5,beta:-4,b:-4,RC:-3,rc:-3,"#":-2,p:1,pl:1},s=function(e){return e=(""+e).replace(/[_\-+]/g,"."),e=e.replace(/([^.\d]+)/g,".$1.").replace(/\.{2,}/g,"."),e.length?e.split("."):[-8]},u=function(e){return e?isNaN(e)?a[e]||-7:parseInt(e,10):0};for(e=s(e),t=s(t),r=Math.max(e.length,t.length),n=0;r>n;n++)if(e[n]!=t[n]){if(e[n]=u(e[n]),t[n]=u(t[n]),e[n]<t[n]){o=-1;break}if(e[n]>t[n]){o=1;break}}if(!i)return o;switch(i){case">":case"gt":return o>0;case">=":case"ge":return o>=0;case"<=":case"le":return 0>=o;case"==":case"=":case"eq":return 0===o;case"<>":case"!=":case"ne":return 0!==o;case"":case"<":case"lt":return 0>o;default:return null}}var i=function(e){var t="",i="?",n="function",r="undefined",o="object",a="name",s="version",u={has:function(e,t){return-1!==t.toLowerCase().indexOf(e.toLowerCase())},lowerize:function(e){return e.toLowerCase()}},c={rgx:function(){for(var t,i,a,s,u,c,l,d=0,m=arguments;d<m.length;d+=2){var h=m[d],f=m[d+1];if(typeof t===r){t={};for(s in f)u=f[s],typeof u===o?t[u[0]]=e:t[u]=e}for(i=a=0;i<h.length;i++)if(c=h[i].exec(this.getUA())){for(s=0;s<f.length;s++)l=c[++a],u=f[s],typeof u===o&&u.length>0?2==u.length?t[u[0]]=typeof u[1]==n?u[1].call(this,l):u[1]:3==u.length?t[u[0]]=typeof u[1]!==n||u[1].exec&&u[1].test?l?l.replace(u[1],u[2]):e:l?u[1].call(this,l,u[2]):e:4==u.length&&(t[u[0]]=l?u[3].call(this,l.replace(u[1],u[2])):e):t[u]=l?l:e;break}if(c)break}return t},str:function(t,n){for(var r in n)if(typeof n[r]===o&&n[r].length>0){for(var a=0;a<n[r].length;a++)if(u.has(n[r][a],t))return r===i?e:r}else if(u.has(n[r],t))return r===i?e:r;return t}},l={browser:{oldsafari:{major:{1:["/8","/1","/3"],2:"/4","?":"/"},version:{"1.0":"/8",1.2:"/1",1.3:"/3","2.0":"/412","2.0.2":"/416","2.0.3":"/417","2.0.4":"/419","?":"/"}}},device:{sprint:{model:{"Evo Shift 4G":"7373KT"},vendor:{HTC:"APA",Sprint:"Sprint"}}},os:{windows:{version:{ME:"4.90","NT 3.11":"NT3.51","NT 4.0":"NT4.0",2000:"NT 5.0",XP:["NT 5.1","NT 5.2"],Vista:"NT 6.0",7:"NT 6.1",8:"NT 6.2",8.1:"NT 6.3",RT:"ARM"}}}},d={browser:[[/(opera\smini)\/([\w\.-]+)/i,/(opera\s[mobiletab]+).+version\/([\w\.-]+)/i,/(opera).+version\/([\w\.]+)/i,/(opera)[\/\s]+([\w\.]+)/i],[a,s],[/\s(opr)\/([\w\.]+)/i],[[a,"Opera"],s],[/(kindle)\/([\w\.]+)/i,/(lunascape|maxthon|netfront|jasmine|blazer)[\/\s]?([\w\.]+)*/i,/(avant\s|iemobile|slim|baidu)(?:browser)?[\/\s]?([\w\.]*)/i,/(?:ms|\()(ie)\s([\w\.]+)/i,/(rekonq)\/([\w\.]+)*/i,/(chromium|flock|rockmelt|midori|epiphany|silk|skyfire|ovibrowser|bolt|iron|vivaldi)\/([\w\.-]+)/i],[a,s],[/(trident).+rv[:\s]([\w\.]+).+like\sgecko/i],[[a,"IE"],s],[/(edge)\/((\d+)?[\w\.]+)/i],[a,s],[/(yabrowser)\/([\w\.]+)/i],[[a,"Yandex"],s],[/(comodo_dragon)\/([\w\.]+)/i],[[a,/_/g," "],s],[/(chrome|omniweb|arora|[tizenoka]{5}\s?browser)\/v?([\w\.]+)/i,/(uc\s?browser|qqbrowser)[\/\s]?([\w\.]+)/i],[a,s],[/(dolfin)\/([\w\.]+)/i],[[a,"Dolphin"],s],[/((?:android.+)crmo|crios)\/([\w\.]+)/i],[[a,"Chrome"],s],[/XiaoMi\/MiuiBrowser\/([\w\.]+)/i],[s,[a,"MIUI Browser"]],[/android.+version\/([\w\.]+)\s+(?:mobile\s?safari|safari)/i],[s,[a,"Android Browser"]],[/FBAV\/([\w\.]+);/i],[s,[a,"Facebook"]],[/version\/([\w\.]+).+?mobile\/\w+\s(safari)/i],[s,[a,"Mobile Safari"]],[/version\/([\w\.]+).+?(mobile\s?safari|safari)/i],[s,a],[/webkit.+?(mobile\s?safari|safari)(\/[\w\.]+)/i],[a,[s,c.str,l.browser.oldsafari.version]],[/(konqueror)\/([\w\.]+)/i,/(webkit|khtml)\/([\w\.]+)/i],[a,s],[/(navigator|netscape)\/([\w\.-]+)/i],[[a,"Netscape"],s],[/(swiftfox)/i,/(icedragon|iceweasel|camino|chimera|fennec|maemo\sbrowser|minimo|conkeror)[\/\s]?([\w\.\+]+)/i,/(firefox|seamonkey|k-meleon|icecat|iceape|firebird|phoenix)\/([\w\.-]+)/i,/(mozilla)\/([\w\.]+).+rv\:.+gecko\/\d+/i,/(polaris|lynx|dillo|icab|doris|amaya|w3m|netsurf)[\/\s]?([\w\.]+)/i,/(links)\s\(([\w\.]+)/i,/(gobrowser)\/?([\w\.]+)*/i,/(ice\s?browser)\/v?([\w\._]+)/i,/(mosaic)[\/\s]([\w\.]+)/i],[a,s]],engine:[[/windows.+\sedge\/([\w\.]+)/i],[s,[a,"EdgeHTML"]],[/(presto)\/([\w\.]+)/i,/(webkit|trident|netfront|netsurf|amaya|lynx|w3m)\/([\w\.]+)/i,/(khtml|tasman|links)[\/\s]\(?([\w\.]+)/i,/(icab)[\/\s]([23]\.[\d\.]+)/i],[a,s],[/rv\:([\w\.]+).*(gecko)/i],[s,a]],os:[[/microsoft\s(windows)\s(vista|xp)/i],[a,s],[/(windows)\snt\s6\.2;\s(arm)/i,/(windows\sphone(?:\sos)*|windows\smobile|windows)[\s\/]?([ntce\d\.\s]+\w)/i],[a,[s,c.str,l.os.windows.version]],[/(win(?=3|9|n)|win\s9x\s)([nt\d\.]+)/i],[[a,"Windows"],[s,c.str,l.os.windows.version]],[/\((bb)(10);/i],[[a,"BlackBerry"],s],[/(blackberry)\w*\/?([\w\.]+)*/i,/(tizen)[\/\s]([\w\.]+)/i,/(android|webos|palm\os|qnx|bada|rim\stablet\sos|meego|contiki)[\/\s-]?([\w\.]+)*/i,/linux;.+(sailfish);/i],[a,s],[/(symbian\s?os|symbos|s60(?=;))[\/\s-]?([\w\.]+)*/i],[[a,"Symbian"],s],[/\((series40);/i],[a],[/mozilla.+\(mobile;.+gecko.+firefox/i],[[a,"Firefox OS"],s],[/(nintendo|playstation)\s([wids3portablevu]+)/i,/(mint)[\/\s\(]?(\w+)*/i,/(mageia|vectorlinux)[;\s]/i,/(joli|[kxln]?ubuntu|debian|[open]*suse|gentoo|arch|slackware|fedora|mandriva|centos|pclinuxos|redhat|zenwalk|linpus)[\/\s-]?([\w\.-]+)*/i,/(hurd|linux)\s?([\w\.]+)*/i,/(gnu)\s?([\w\.]+)*/i],[a,s],[/(cros)\s[\w]+\s([\w\.]+\w)/i],[[a,"Chromium OS"],s],[/(sunos)\s?([\w\.]+\d)*/i],[[a,"Solaris"],s],[/\s([frentopc-]{0,4}bsd|dragonfly)\s?([\w\.]+)*/i],[a,s],[/(ip[honead]+)(?:.*os\s*([\w]+)*\slike\smac|;\sopera)/i],[[a,"iOS"],[s,/_/g,"."]],[/(mac\sos\sx)\s?([\w\s\.]+\w)*/i,/(macintosh|mac(?=_powerpc)\s)/i],[[a,"Mac OS"],[s,/_/g,"."]],[/((?:open)?solaris)[\/\s-]?([\w\.]+)*/i,/(haiku)\s(\w+)/i,/(aix)\s((\d)(?=\.|\)|\s)[\w\.]*)*/i,/(plan\s9|minix|beos|os\/2|amigaos|morphos|risc\sos|openvms)/i,/(unix)\s?([\w\.]+)*/i],[a,s]]},m=function(e){var i=e||(window&&window.navigator&&window.navigator.userAgent?window.navigator.userAgent:t);this.getBrowser=function(){return c.rgx.apply(this,d.browser)},this.getEngine=function(){return c.rgx.apply(this,d.engine)},this.getOS=function(){return c.rgx.apply(this,d.os)},this.getResult=function(){return{ua:this.getUA(),browser:this.getBrowser(),engine:this.getEngine(),os:this.getOS()}},this.getUA=function(){return i},this.setUA=function(e){return i=e,this},this.setUA(i)};return m}(),n=function(){var t={define_property:function(){return!1}(),create_canvas:function(){var e=document.createElement("canvas");return!(!e.getContext||!e.getContext("2d"))}(),return_response_type:function(t){try{if(-1!==e.inArray(t,["","text","document"]))return!0;if(window.XMLHttpRequest){var i=new XMLHttpRequest;if(i.open("get","/"),"responseType"in i)return i.responseType=t,i.responseType!==t?!1:!0}}catch(n){}return!1},use_data_uri:function(){var e=new Image;return e.onload=function(){t.use_data_uri=1===e.width&&1===e.height},setTimeout(function(){e.src="data:image/gif;base64,R0lGODlhAQABAIAAAP8AAAAAACH5BAAAAAAALAAAAAABAAEAAAICRAEAOw=="},1),!1}(),use_data_uri_over32kb:function(){return t.use_data_uri&&("IE"!==o.browser||o.version>=9)},use_data_uri_of:function(e){return t.use_data_uri&&33e3>e||t.use_data_uri_over32kb()},use_fileinput:function(){if(navigator.userAgent.match(/(Android (1.0|1.1|1.5|1.6|2.0|2.1))|(Windows Phone (OS 7|8.0))|(XBLWP)|(ZuneWP)|(w(eb)?OSBrowser)|(webOS)|(Kindle\/(1.0|2.0|2.5|3.0))/))return!1;var e=document.createElement("input");return e.setAttribute("type","file"),!e.disabled}};return function(i){var n=[].slice.call(arguments);return n.shift(),"function"===e.typeOf(t[i])?t[i].apply(this,n):!!t[i]}}(),r=(new i).getResult(),o={can:n,uaParser:i,browser:r.browser.name,version:r.browser.version,os:r.os.name,osVersion:r.os.version,verComp:t,swf_url:"../flash/Moxie.swf",xap_url:"../silverlight/Moxie.xap",global_event_dispatcher:"moxie.core.EventTarget.instance.dispatchEvent"};return o.OS=o.os,o}),n("moxie/core/Exceptions",["moxie/core/utils/Basic"],function(e){function t(e,t){var i;for(i in e)if(e[i]===t)return i;return null}return{RuntimeError:function(){function i(e,i){this.code=e,this.name=t(n,e),this.message=this.name+(i||": RuntimeError "+this.code)}var n={NOT_INIT_ERR:1,EXCEPTION_ERR:3,NOT_SUPPORTED_ERR:9,JS_ERR:4};return e.extend(i,n),i.prototype=Error.prototype,i}(),OperationNotAllowedException:function(){function t(e){this.code=e,this.name="OperationNotAllowedException"}return e.extend(t,{NOT_ALLOWED_ERR:1}),t.prototype=Error.prototype,t}(),ImageError:function(){function i(e){this.code=e,this.name=t(n,e),this.message=this.name+": ImageError "+this.code}var n={WRONG_FORMAT:1,MAX_RESOLUTION_ERR:2,INVALID_META_ERR:3};return e.extend(i,n),i.prototype=Error.prototype,i}(),FileException:function(){function i(e){this.code=e,this.name=t(n,e),this.message=this.name+": FileException "+this.code}var n={NOT_FOUND_ERR:1,SECURITY_ERR:2,ABORT_ERR:3,NOT_READABLE_ERR:4,ENCODING_ERR:5,NO_MODIFICATION_ALLOWED_ERR:6,INVALID_STATE_ERR:7,SYNTAX_ERR:8};return e.extend(i,n),i.prototype=Error.prototype,i}(),DOMException:function(){function i(e){this.code=e,this.name=t(n,e),this.message=this.name+": DOMException "+this.code}var n={INDEX_SIZE_ERR:1,DOMSTRING_SIZE_ERR:2,HIERARCHY_REQUEST_ERR:3,WRONG_DOCUMENT_ERR:4,INVALID_CHARACTER_ERR:5,NO_DATA_ALLOWED_ERR:6,NO_MODIFICATION_ALLOWED_ERR:7,NOT_FOUND_ERR:8,NOT_SUPPORTED_ERR:9,INUSE_ATTRIBUTE_ERR:10,INVALID_STATE_ERR:11,SYNTAX_ERR:12,INVALID_MODIFICATION_ERR:13,NAMESPACE_ERR:14,INVALID_ACCESS_ERR:15,VALIDATION_ERR:16,TYPE_MISMATCH_ERR:17,SECURITY_ERR:18,NETWORK_ERR:19,ABORT_ERR:20,URL_MISMATCH_ERR:21,QUOTA_EXCEEDED_ERR:22,TIMEOUT_ERR:23,INVALID_NODE_TYPE_ERR:24,DATA_CLONE_ERR:25};return e.extend(i,n),i.prototype=Error.prototype,i}(),EventException:function(){function t(e){this.code=e,this.name="EventException"}return e.extend(t,{UNSPECIFIED_EVENT_TYPE_ERR:0}),t.prototype=Error.prototype,t}()}}),n("moxie/core/utils/Dom",["moxie/core/utils/Env"],function(e){var t=function(e){return"string"!=typeof e?e:document.getElementById(e)},i=function(e,t){if(!e.className)return!1;var i=new RegExp("(^|\\s+)"+t+"(\\s+|$)");return i.test(e.className)},n=function(e,t){i(e,t)||(e.className=e.className?e.className.replace(/\s+$/,"")+" "+t:t)},r=function(e,t){if(e.className){var i=new RegExp("(^|\\s+)"+t+"(\\s+|$)");e.className=e.className.replace(i,function(e,t,i){return" "===t&&" "===i?" ":""})}},o=function(e,t){return e.currentStyle?e.currentStyle[t]:window.getComputedStyle?window.getComputedStyle(e,null)[t]:void 0},a=function(t,i){function n(e){var t,i,n=0,r=0;return e&&(i=e.getBoundingClientRect(),t="CSS1Compat"===c.compatMode?c.documentElement:c.body,n=i.left+t.scrollLeft,r=i.top+t.scrollTop),{x:n,y:r}}var r,o,a,s=0,u=0,c=document;if(t=t,i=i||c.body,t&&t.getBoundingClientRect&&"IE"===e.browser&&(!c.documentMode||c.documentMode<8))return o=n(t),a=n(i),{x:o.x-a.x,y:o.y-a.y};for(r=t;r&&r!=i&&r.nodeType;)s+=r.offsetLeft||0,u+=r.offsetTop||0,r=r.offsetParent;for(r=t.parentNode;r&&r!=i&&r.nodeType;)s-=r.scrollLeft||0,u-=r.scrollTop||0,r=r.parentNode;return{x:s,y:u}},s=function(e){return{w:e.offsetWidth||e.clientWidth,h:e.offsetHeight||e.clientHeight}};return{get:t,hasClass:i,addClass:n,removeClass:r,getStyle:o,getPos:a,getSize:s}}),n("moxie/core/EventTarget",["moxie/core/utils/Env","moxie/core/Exceptions","moxie/core/utils/Basic"],function(e,t,i){function n(){this.uid=i.guid()}var r={};return i.extend(n.prototype,{init:function(){this.uid||(this.uid=i.guid("uid_"))},addEventListener:function(e,t,n,o){var a,s=this;return this.hasOwnProperty("uid")||(this.uid=i.guid("uid_")),e=i.trim(e),/\s/.test(e)?(i.each(e.split(/\s+/),function(e){s.addEventListener(e,t,n,o)}),void 0):(e=e.toLowerCase(),n=parseInt(n,10)||0,a=r[this.uid]&&r[this.uid][e]||[],a.push({fn:t,priority:n,scope:o||this}),r[this.uid]||(r[this.uid]={}),r[this.uid][e]=a,void 0)},hasEventListener:function(e){var t;return e?(e=e.toLowerCase(),t=r[this.uid]&&r[this.uid][e]):t=r[this.uid],t?t:!1},removeEventListener:function(e,t){var n,o,a=this;if(e=e.toLowerCase(),/\s/.test(e))return i.each(e.split(/\s+/),function(e){a.removeEventListener(e,t)}),void 0;if(n=r[this.uid]&&r[this.uid][e]){if(t){for(o=n.length-1;o>=0;o--)if(n[o].fn===t){n.splice(o,1);break}}else n=[];n.length||(delete r[this.uid][e],i.isEmptyObj(r[this.uid])&&delete r[this.uid])}},removeAllEventListeners:function(){r[this.uid]&&delete r[this.uid]},dispatchEvent:function(e){var n,o,a,s,u,c={},l=!0;if("string"!==i.typeOf(e)){if(s=e,"string"!==i.typeOf(s.type))throw new t.EventException(t.EventException.UNSPECIFIED_EVENT_TYPE_ERR);e=s.type,s.total!==u&&s.loaded!==u&&(c.total=s.total,c.loaded=s.loaded),c.async=s.async||!1}if(-1!==e.indexOf("::")?function(t){n=t[0],e=t[1]}(e.split("::")):n=this.uid,e=e.toLowerCase(),o=r[n]&&r[n][e]){o.sort(function(e,t){return t.priority-e.priority}),a=[].slice.call(arguments),a.shift(),c.type=e,a.unshift(c);var d=[];i.each(o,function(e){a[0].target=e.scope,c.async?d.push(function(t){setTimeout(function(){t(e.fn.apply(e.scope,a)===!1)},1)}):d.push(function(t){t(e.fn.apply(e.scope,a)===!1)})}),d.length&&i.inSeries(d,function(e){l=!e})}return l},bindOnce:function(e,t,i,n){var r=this;r.bind.call(this,e,function o(){return r.unbind(e,o),t.apply(this,arguments)},i,n)},bind:function(){this.addEventListener.apply(this,arguments)},unbind:function(){this.removeEventListener.apply(this,arguments)},unbindAll:function(){this.removeAllEventListeners.apply(this,arguments)},trigger:function(){return this.dispatchEvent.apply(this,arguments)},handleEventProps:function(e){var t=this;this.bind(e.join(" "),function(e){var t="on"+e.type.toLowerCase();"function"===i.typeOf(this[t])&&this[t].apply(this,arguments)}),i.each(e,function(e){e="on"+e.toLowerCase(e),"undefined"===i.typeOf(t[e])&&(t[e]=null)})}}),n.instance=new n,n}),n("moxie/runtime/Runtime",["moxie/core/utils/Env","moxie/core/utils/Basic","moxie/core/utils/Dom","moxie/core/EventTarget"],function(e,t,i,n){function r(e,n,o,s,u){var c,l=this,d=t.guid(n+"_"),m=u||"browser";e=e||{},a[d]=this,o=t.extend({access_binary:!1,access_image_binary:!1,display_media:!1,do_cors:!1,drag_and_drop:!1,filter_by_extension:!0,resize_image:!1,report_upload_progress:!1,return_response_headers:!1,return_response_type:!1,return_status_code:!0,send_custom_headers:!1,select_file:!1,select_folder:!1,select_multiple:!0,send_binary_string:!1,send_browser_cookies:!0,send_multipart:!0,slice_blob:!1,stream_upload:!1,summon_file_dialog:!1,upload_filesize:!0,use_http_method:!0},o),e.preferred_caps&&(m=r.getMode(s,e.preferred_caps,m)),c=function(){var e={};return{exec:function(t,i,n,r){return c[i]&&(e[t]||(e[t]={context:this,instance:new c[i]}),e[t].instance[n])?e[t].instance[n].apply(this,r):void 0},removeInstance:function(t){delete e[t]},removeAllInstances:function(){var i=this;t.each(e,function(e,n){"function"===t.typeOf(e.instance.destroy)&&e.instance.destroy.call(e.context),i.removeInstance(n)})}}}(),t.extend(this,{initialized:!1,uid:d,type:n,mode:r.getMode(s,e.required_caps,m),shimid:d+"_container",clients:0,options:e,can:function(e,i){var n=arguments[2]||o;if("string"===t.typeOf(e)&&"undefined"===t.typeOf(i)&&(e=r.parseCaps(e)),"object"===t.typeOf(e)){for(var a in e)if(!this.can(a,e[a],n))return!1;return!0}return"function"===t.typeOf(n[e])?n[e].call(this,i):i===n[e]},getShimContainer:function(){var e,n=i.get(this.shimid);return n||(e=i.get(this.options.container)||document.body,n=document.createElement("div"),n.id=this.shimid,n.className="moxie-shim moxie-shim-"+this.type,t.extend(n.style,{position:"absolute",top:"0px",left:"0px",width:"1px",height:"1px",overflow:"hidden"}),e.appendChild(n),e=null),n},getShim:function(){return c},shimExec:function(e,t){var i=[].slice.call(arguments,2);return l.getShim().exec.call(this,this.uid,e,t,i)},exec:function(e,t){var i=[].slice.call(arguments,2);return l[e]&&l[e][t]?l[e][t].apply(this,i):l.shimExec.apply(this,arguments)},destroy:function(){if(l){var e=i.get(this.shimid);e&&e.parentNode.removeChild(e),c&&c.removeAllInstances(),this.unbindAll(),delete a[this.uid],this.uid=null,d=l=c=e=null}}}),this.mode&&e.required_caps&&!this.can(e.required_caps)&&(this.mode=!1)}var o={},a={};return r.order="html5,flash,silverlight,html4",r.getRuntime=function(e){return a[e]?a[e]:!1},r.addConstructor=function(e,t){t.prototype=n.instance,o[e]=t},r.getConstructor=function(e){return o[e]||null},r.getInfo=function(e){var t=r.getRuntime(e);return t?{uid:t.uid,type:t.type,mode:t.mode,can:function(){return t.can.apply(t,arguments)}}:null},r.parseCaps=function(e){var i={};return"string"!==t.typeOf(e)?e||{}:(t.each(e.split(","),function(e){i[e]=!0}),i)},r.can=function(e,t){var i,n,o=r.getConstructor(e);return o?(i=new o({required_caps:t}),n=i.mode,i.destroy(),!!n):!1},r.thatCan=function(e,t){var i=(t||r.order).split(/\s*,\s*/);for(var n in i)if(r.can(i[n],e))return i[n];return null},r.getMode=function(e,i,n){var r=null;if("undefined"===t.typeOf(n)&&(n="browser"),i&&!t.isEmptyObj(e)){if(t.each(i,function(i,n){if(e.hasOwnProperty(n)){var o=e[n](i);if("string"==typeof o&&(o=[o]),r){if(!(r=t.arrayIntersect(r,o)))return r=!1}else r=o}}),r)return-1!==t.inArray(n,r)?n:r[0];if(r===!1)return!1}return n},r.capTrue=function(){return!0},r.capFalse=function(){return!1},r.capTest=function(e){return function(){return!!e}},r}),n("moxie/runtime/RuntimeClient",["moxie/core/utils/Env","moxie/core/Exceptions","moxie/core/utils/Basic","moxie/runtime/Runtime"],function(e,t,i,n){return function(){var e;i.extend(this,{connectRuntime:function(r){function o(i){var a,u;return i.length?(a=i.shift().toLowerCase(),(u=n.getConstructor(a))?(e=new u(r),e.bind("Init",function(){e.initialized=!0,setTimeout(function(){e.clients++,s.ruid=e.uid,s.trigger("RuntimeInit",e)},1)}),e.bind("Error",function(){e.destroy(),o(i)}),e.bind("Exception",function(e,i){var n=i.name+"(#"+i.code+")"+(i.message?", from: "+i.message:"");s.trigger("RuntimeError",new t.RuntimeError(t.RuntimeError.EXCEPTION_ERR,n))}),e.mode?(e.init(),void 0):(e.trigger("Error"),void 0)):(o(i),void 0)):(s.trigger("RuntimeError",new t.RuntimeError(t.RuntimeError.NOT_INIT_ERR)),e=null,void 0)}var a,s=this;if("string"===i.typeOf(r)?a=r:"string"===i.typeOf(r.ruid)&&(a=r.ruid),a){if(e=n.getRuntime(a))return s.ruid=a,e.clients++,e;throw new t.RuntimeError(t.RuntimeError.NOT_INIT_ERR)}o((r.runtime_order||n.order).split(/\s*,\s*/))},disconnectRuntime:function(){e&&--e.clients<=0&&e.destroy(),e=null},getRuntime:function(){return e&&e.uid?e:e=null},exec:function(){return e?e.exec.apply(this,arguments):null},can:function(t){return e?e.can(t):!1}})}}),n("moxie/file/Blob",["moxie/core/utils/Basic","moxie/core/utils/Encode","moxie/runtime/RuntimeClient"],function(e,t,i){function n(o,a){function s(t,i,o){var a,s=r[this.uid];return"string"===e.typeOf(s)&&s.length?(a=new n(null,{type:o,size:i-t}),a.detach(s.substr(t,a.size)),a):null}i.call(this),o&&this.connectRuntime(o),a?"string"===e.typeOf(a)&&(a={data:a}):a={},e.extend(this,{uid:a.uid||e.guid("uid_"),ruid:o,size:a.size||0,type:a.type||"",slice:function(e,t,i){return this.isDetached()?s.apply(this,arguments):this.getRuntime().exec.call(this,"Blob","slice",this.getSource(),e,t,i)},getSource:function(){return r[this.uid]?r[this.uid]:null},detach:function(e){if(this.ruid&&(this.getRuntime().exec.call(this,"Blob","destroy"),this.disconnectRuntime(),this.ruid=null),e=e||"","data:"==e.substr(0,5)){var i=e.indexOf(";base64,");this.type=e.substring(5,i),e=t.atob(e.substring(i+8))}this.size=e.length,r[this.uid]=e},isDetached:function(){return!this.ruid&&"string"===e.typeOf(r[this.uid])},destroy:function(){this.detach(),delete r[this.uid]}}),a.data?this.detach(a.data):r[this.uid]=a}var r={};return n}),n("moxie/core/I18n",["moxie/core/utils/Basic"],function(e){var t={};return{addI18n:function(i){return e.extend(t,i)},translate:function(e){return t[e]||e},_:function(e){return this.translate(e)},sprintf:function(t){var i=[].slice.call(arguments,1);return t.replace(/%[a-z]/g,function(){var t=i.shift();return"undefined"!==e.typeOf(t)?t:""})}}}),n("moxie/core/utils/Mime",["moxie/core/utils/Basic","moxie/core/I18n"],function(e,t){var i="application/msword,doc dot,application/pdf,pdf,application/pgp-signature,pgp,application/postscript,ps ai eps,application/rtf,rtf,application/vnd.ms-excel,xls xlb,application/vnd.ms-powerpoint,ppt pps pot,application/zip,zip,application/x-shockwave-flash,swf swfl,application/vnd.openxmlformats-officedocument.wordprocessingml.document,docx,application/vnd.openxmlformats-officedocument.wordprocessingml.template,dotx,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,xlsx,application/vnd.openxmlformats-officedocument.presentationml.presentation,pptx,application/vnd.openxmlformats-officedocument.presentationml.template,potx,application/vnd.openxmlformats-officedocument.presentationml.slideshow,ppsx,application/x-javascript,js,application/json,json,audio/mpeg,mp3 mpga mpega mp2,audio/x-wav,wav,audio/x-m4a,m4a,audio/ogg,oga ogg,audio/aiff,aiff aif,audio/flac,flac,audio/aac,aac,audio/ac3,ac3,audio/x-ms-wma,wma,image/bmp,bmp,image/gif,gif,image/jpeg,jpg jpeg jpe,image/photoshop,psd,image/png,png,image/svg+xml,svg svgz,image/tiff,tiff tif,text/plain,asc txt text diff log,text/html,htm html xhtml,text/css,css,text/csv,csv,text/rtf,rtf,video/mpeg,mpeg mpg mpe m2v,video/quicktime,qt mov,video/mp4,mp4,video/x-m4v,m4v,video/x-flv,flv,video/x-ms-wmv,wmv,video/avi,avi,video/webm,webm,video/3gpp,3gpp 3gp,video/3gpp2,3g2,video/vnd.rn-realvideo,rv,video/ogg,ogv,video/x-matroska,mkv,application/vnd.oasis.opendocument.formula-template,otf,application/octet-stream,exe",n={mimes:{},extensions:{},addMimeType:function(e){var t,i,n,r=e.split(/,/);for(t=0;t<r.length;t+=2){for(n=r[t+1].split(/ /),i=0;i<n.length;i++)this.mimes[n[i]]=r[t];this.extensions[r[t]]=n}},extList2mimes:function(t,i){var n,r,o,a,s=this,u=[];for(r=0;r<t.length;r++)for(n=t[r].extensions.toLowerCase().split(/\s*,\s*/),o=0;o<n.length;o++){if("*"===n[o])return[];if(a=s.mimes[n[o]],i&&/^\w+$/.test(n[o]))u.push("."+n[o]);else if(a&&-1===e.inArray(a,u))u.push(a);else if(!a)return[]}return u},mimes2exts:function(t){var i=this,n=[];return e.each(t,function(t){if(t=t.toLowerCase(),"*"===t)return n=[],!1;var r=t.match(/^(\w+)\/(\*|\w+)$/);r&&("*"===r[2]?e.each(i.extensions,function(e,t){new RegExp("^"+r[1]+"/").test(t)&&[].push.apply(n,i.extensions[t])}):i.extensions[t]&&[].push.apply(n,i.extensions[t]))}),n},mimes2extList:function(i){var n=[],r=[];return"string"===e.typeOf(i)&&(i=e.trim(i).split(/\s*,\s*/)),r=this.mimes2exts(i),n.push({title:t.translate("Files"),extensions:r.length?r.join(","):"*"}),n.mimes=i,n},getFileExtension:function(e){var t=e&&e.match(/\.([^.]+)$/);return t?t[1].toLowerCase():""},getFileMime:function(e){return this.mimes[this.getFileExtension(e)]||""}};return n.addMimeType(i),n}),n("moxie/file/FileInput",["moxie/core/utils/Basic","moxie/core/utils/Env","moxie/core/utils/Mime","moxie/core/utils/Dom","moxie/core/Exceptions","moxie/core/EventTarget","moxie/core/I18n","moxie/runtime/Runtime","moxie/runtime/RuntimeClient"],function(e,t,i,n,r,o,a,s,u){function c(t){var o,c,d;if(-1!==e.inArray(e.typeOf(t),["string","node"])&&(t={browse_button:t}),c=n.get(t.browse_button),!c)throw new r.DOMException(r.DOMException.NOT_FOUND_ERR);d={accept:[{title:a.translate("All Files"),extensions:"*"}],multiple:!1,required_caps:!1,container:c.parentNode||document.body},t=e.extend({},d,t),"string"==typeof t.required_caps&&(t.required_caps=s.parseCaps(t.required_caps)),"string"==typeof t.accept&&(t.accept=i.mimes2extList(t.accept)),o=n.get(t.container),o||(o=document.body),"static"===n.getStyle(o,"position")&&(o.style.position="relative"),o=c=null,u.call(this),e.extend(this,{uid:e.guid("uid_"),ruid:null,shimid:null,files:null,init:function(){var i=this;i.bind("RuntimeInit",function(r,o){i.ruid=o.uid,i.shimid=o.shimid,i.bind("Ready",function(){i.trigger("Refresh")},999),i.bind("Refresh",function(){var i,r,a,s,u;a=n.get(t.browse_button),s=n.get(o.shimid),a&&(i=n.getPos(a,n.get(t.container)),r=n.getSize(a),u=parseInt(n.getStyle(a,"z-index"),10)||0,s&&e.extend(s.style,{top:i.y+"px",left:i.x+"px",width:r.w+"px",height:r.h+"px",zIndex:u+1})),s=a=null}),o.exec.call(i,"FileInput","init",t)}),i.connectRuntime(e.extend({},t,{required_caps:{select_file:!0}}))},getOption:function(e){return t[e]},setOption:function(e,n){if(t.hasOwnProperty(e)){var o=t[e];switch(e){case"accept":"string"==typeof n&&(n=i.mimes2extList(n));break;case"container":case"required_caps":throw new r.FileException(r.FileException.NO_MODIFICATION_ALLOWED_ERR)}t[e]=n,this.exec("FileInput","setOption",e,n),this.trigger("OptionChanged",e,n,o)}},disable:function(t){var i=this.getRuntime();i&&this.exec("FileInput","disable","undefined"===e.typeOf(t)?!0:t)},refresh:function(){this.trigger("Refresh")},destroy:function(){var t=this.getRuntime();t&&(t.exec.call(this,"FileInput","destroy"),this.disconnectRuntime()),"array"===e.typeOf(this.files)&&e.each(this.files,function(e){e.destroy()}),this.files=null,this.unbindAll()}}),this.handleEventProps(l)}var l=["ready","change","cancel","mouseenter","mouseleave","mousedown","mouseup"];return c.prototype=o.instance,c}),n("moxie/file/File",["moxie/core/utils/Basic","moxie/core/utils/Mime","moxie/file/Blob"],function(e,t,i){function n(n,r){r||(r={}),i.apply(this,arguments),this.type||(this.type=t.getFileMime(r.name));var o;if(r.name)o=r.name.replace(/\\/g,"/"),o=o.substr(o.lastIndexOf("/")+1);else if(this.type){var a=this.type.split("/")[0];o=e.guid((""!==a?a:"file")+"_"),t.extensions[this.type]&&(o+="."+t.extensions[this.type][0])}e.extend(this,{name:o||e.guid("file_"),relativePath:"",lastModifiedDate:r.lastModifiedDate||(new Date).toLocaleString()})}return n.prototype=i.prototype,n}),n("moxie/file/FileDrop",["moxie/core/I18n","moxie/core/utils/Dom","moxie/core/Exceptions","moxie/core/utils/Basic","moxie/core/utils/Env","moxie/file/File","moxie/runtime/RuntimeClient","moxie/core/EventTarget","moxie/core/utils/Mime"],function(e,t,i,n,r,o,a,s,u){function c(i){var r,o=this;"string"==typeof i&&(i={drop_zone:i}),r={accept:[{title:e.translate("All Files"),extensions:"*"}],required_caps:{drag_and_drop:!0}},i="object"==typeof i?n.extend({},r,i):r,i.container=t.get(i.drop_zone)||document.body,"static"===t.getStyle(i.container,"position")&&(i.container.style.position="relative"),"string"==typeof i.accept&&(i.accept=u.mimes2extList(i.accept)),a.call(o),n.extend(o,{uid:n.guid("uid_"),ruid:null,files:null,init:function(){o.bind("RuntimeInit",function(e,t){o.ruid=t.uid,t.exec.call(o,"FileDrop","init",i),o.dispatchEvent("ready")}),o.connectRuntime(i)},destroy:function(){var e=this.getRuntime();e&&(e.exec.call(this,"FileDrop","destroy"),this.disconnectRuntime()),this.files=null,this.unbindAll()}}),this.handleEventProps(l)}var l=["ready","dragenter","dragleave","drop","error"];return c.prototype=s.instance,c}),n("moxie/file/FileReader",["moxie/core/utils/Basic","moxie/core/utils/Encode","moxie/core/Exceptions","moxie/core/EventTarget","moxie/file/Blob","moxie/runtime/RuntimeClient"],function(e,t,i,n,r,o){function a(){function n(e,n){if(this.trigger("loadstart"),this.readyState===a.LOADING)return this.trigger("error",new i.DOMException(i.DOMException.INVALID_STATE_ERR)),this.trigger("loadend"),void 0;
if(!(n instanceof r))return this.trigger("error",new i.DOMException(i.DOMException.NOT_FOUND_ERR)),this.trigger("loadend"),void 0;if(this.result=null,this.readyState=a.LOADING,n.isDetached()){var o=n.getSource();switch(e){case"readAsText":case"readAsBinaryString":this.result=o;break;case"readAsDataURL":this.result="data:"+n.type+";base64,"+t.btoa(o)}this.readyState=a.DONE,this.trigger("load"),this.trigger("loadend")}else this.connectRuntime(n.ruid),this.exec("FileReader","read",e,n)}o.call(this),e.extend(this,{uid:e.guid("uid_"),readyState:a.EMPTY,result:null,error:null,readAsBinaryString:function(e){n.call(this,"readAsBinaryString",e)},readAsDataURL:function(e){n.call(this,"readAsDataURL",e)},readAsText:function(e){n.call(this,"readAsText",e)},abort:function(){this.result=null,-1===e.inArray(this.readyState,[a.EMPTY,a.DONE])&&(this.readyState===a.LOADING&&(this.readyState=a.DONE),this.exec("FileReader","abort"),this.trigger("abort"),this.trigger("loadend"))},destroy:function(){this.abort(),this.exec("FileReader","destroy"),this.disconnectRuntime(),this.unbindAll()}}),this.handleEventProps(s),this.bind("Error",function(e,t){this.readyState=a.DONE,this.error=t},999),this.bind("Load",function(){this.readyState=a.DONE},999)}var s=["loadstart","progress","load","abort","error","loadend"];return a.EMPTY=0,a.LOADING=1,a.DONE=2,a.prototype=n.instance,a}),n("moxie/core/utils/Url",["moxie/core/utils/Basic"],function(e){var t=function(i,n){var r,o=["source","scheme","authority","userInfo","user","pass","host","port","relative","path","directory","file","query","fragment"],a=o.length,s={http:80,https:443},u={},c=/^(?:([^:\/?#]+):)?(?:\/\/()(?:(?:()(?:([^:@\/]*):?([^:@\/]*))?@)?(\[[\da-fA-F:]+\]|[^:\/?#]*)(?::(\d*))?))?()(?:(()(?:(?:[^?#\/]*\/)*)()(?:[^?#]*))(?:\\?([^#]*))?(?:#(.*))?)/,l=c.exec(i||""),d=/^\/\/\w/.test(i);switch(e.typeOf(n)){case"undefined":n=t(document.location.href,!1);break;case"string":n=t(n,!1)}for(;a--;)l[a]&&(u[o[a]]=l[a]);if(r=!d&&!u.scheme,(d||r)&&(u.scheme=n.scheme),r){u.host=n.host,u.port=n.port;var m="";/^[^\/]/.test(u.path)&&(m=n.path,m=/\/[^\/]*\.[^\/]*$/.test(m)?m.replace(/\/[^\/]+$/,"/"):m.replace(/\/?$/,"/")),u.path=m+(u.path||"")}return u.port||(u.port=s[u.scheme]||80),u.port=parseInt(u.port,10),u.path||(u.path="/"),delete u.source,u},i=function(e){var i={http:80,https:443},n="object"==typeof e?e:t(e);return n.scheme+"://"+n.host+(n.port!==i[n.scheme]?":"+n.port:"")+n.path+(n.query?n.query:"")},n=function(e){function i(e){return[e.scheme,e.host,e.port].join("/")}return"string"==typeof e&&(e=t(e)),i(t())===i(e)};return{parseUrl:t,resolveUrl:i,hasSameOrigin:n}}),n("moxie/runtime/RuntimeTarget",["moxie/core/utils/Basic","moxie/runtime/RuntimeClient","moxie/core/EventTarget"],function(e,t,i){function n(){this.uid=e.guid("uid_"),t.call(this),this.destroy=function(){this.disconnectRuntime(),this.unbindAll()}}return n.prototype=i.instance,n}),n("moxie/file/FileReaderSync",["moxie/core/utils/Basic","moxie/runtime/RuntimeClient","moxie/core/utils/Encode"],function(e,t,i){return function(){function n(e,t){if(!t.isDetached()){var n=this.connectRuntime(t.ruid).exec.call(this,"FileReaderSync","read",e,t);return this.disconnectRuntime(),n}var r=t.getSource();switch(e){case"readAsBinaryString":return r;case"readAsDataURL":return"data:"+t.type+";base64,"+i.btoa(r);case"readAsText":for(var o="",a=0,s=r.length;s>a;a++)o+=String.fromCharCode(r[a]);return o}}t.call(this),e.extend(this,{uid:e.guid("uid_"),readAsBinaryString:function(e){return n.call(this,"readAsBinaryString",e)},readAsDataURL:function(e){return n.call(this,"readAsDataURL",e)},readAsText:function(e){return n.call(this,"readAsText",e)}})}}),n("moxie/xhr/FormData",["moxie/core/Exceptions","moxie/core/utils/Basic","moxie/file/Blob"],function(e,t,i){function n(){var e,n=[];t.extend(this,{append:function(r,o){var a=this,s=t.typeOf(o);o instanceof i?e={name:r,value:o}:"array"===s?(r+="[]",t.each(o,function(e){a.append(r,e)})):"object"===s?t.each(o,function(e,t){a.append(r+"["+t+"]",e)}):"null"===s||"undefined"===s||"number"===s&&isNaN(o)?a.append(r,"false"):n.push({name:r,value:o.toString()})},hasBlob:function(){return!!this.getBlob()},getBlob:function(){return e&&e.value||null},getBlobName:function(){return e&&e.name||null},each:function(i){t.each(n,function(e){i(e.value,e.name)}),e&&i(e.value,e.name)},destroy:function(){e=null,n=[]}})}return n}),n("moxie/xhr/XMLHttpRequest",["moxie/core/utils/Basic","moxie/core/Exceptions","moxie/core/EventTarget","moxie/core/utils/Encode","moxie/core/utils/Url","moxie/runtime/Runtime","moxie/runtime/RuntimeTarget","moxie/file/Blob","moxie/file/FileReaderSync","moxie/xhr/FormData","moxie/core/utils/Env","moxie/core/utils/Mime"],function(e,t,i,n,r,o,a,s,u,c,l,d){function m(){this.uid=e.guid("uid_")}function h(){function i(e,t){return I.hasOwnProperty(e)?1===arguments.length?l.can("define_property")?I[e]:A[e]:(l.can("define_property")?I[e]=t:A[e]=t,void 0):void 0}function u(t){function n(){R&&(R.destroy(),R=null),s.dispatchEvent("loadend"),s=null}function r(r){R.bind("LoadStart",function(e){i("readyState",h.LOADING),s.dispatchEvent("readystatechange"),s.dispatchEvent(e),L&&s.upload.dispatchEvent(e)}),R.bind("Progress",function(e){i("readyState")!==h.LOADING&&(i("readyState",h.LOADING),s.dispatchEvent("readystatechange")),s.dispatchEvent(e)}),R.bind("UploadProgress",function(e){L&&s.upload.dispatchEvent({type:"progress",lengthComputable:!1,total:e.total,loaded:e.loaded})}),R.bind("Load",function(t){i("readyState",h.DONE),i("status",Number(r.exec.call(R,"XMLHttpRequest","getStatus")||0)),i("statusText",f[i("status")]||""),i("response",r.exec.call(R,"XMLHttpRequest","getResponse",i("responseType"))),~e.inArray(i("responseType"),["text",""])?i("responseText",i("response")):"document"===i("responseType")&&i("responseXML",i("response")),U=r.exec.call(R,"XMLHttpRequest","getAllResponseHeaders"),s.dispatchEvent("readystatechange"),i("status")>0?(L&&s.upload.dispatchEvent(t),s.dispatchEvent(t)):(F=!0,s.dispatchEvent("error")),n()}),R.bind("Abort",function(e){s.dispatchEvent(e),n()}),R.bind("Error",function(e){F=!0,i("readyState",h.DONE),s.dispatchEvent("readystatechange"),M=!0,s.dispatchEvent(e),n()}),r.exec.call(R,"XMLHttpRequest","send",{url:x,method:v,async:T,user:w,password:y,headers:S,mimeType:D,encoding:O,responseType:s.responseType,withCredentials:s.withCredentials,options:k},t)}var s=this;E=(new Date).getTime(),R=new a,"string"==typeof k.required_caps&&(k.required_caps=o.parseCaps(k.required_caps)),k.required_caps=e.extend({},k.required_caps,{return_response_type:s.responseType}),t instanceof c&&(k.required_caps.send_multipart=!0),e.isEmptyObj(S)||(k.required_caps.send_custom_headers=!0),B||(k.required_caps.do_cors=!0),k.ruid?r(R.connectRuntime(k)):(R.bind("RuntimeInit",function(e,t){r(t)}),R.bind("RuntimeError",function(e,t){s.dispatchEvent("RuntimeError",t)}),R.connectRuntime(k))}function g(){i("responseText",""),i("responseXML",null),i("response",null),i("status",0),i("statusText",""),E=b=null}var x,v,w,y,E,b,R,_,A=this,I={timeout:0,readyState:h.UNSENT,withCredentials:!1,status:0,statusText:"",responseType:"",responseXML:null,responseText:null,response:null},T=!0,S={},O=null,D=null,N=!1,C=!1,L=!1,M=!1,F=!1,B=!1,P=null,H=null,k={},U="";e.extend(this,I,{uid:e.guid("uid_"),upload:new m,open:function(o,a,s,u,c){var l;if(!o||!a)throw new t.DOMException(t.DOMException.SYNTAX_ERR);if(/[\u0100-\uffff]/.test(o)||n.utf8_encode(o)!==o)throw new t.DOMException(t.DOMException.SYNTAX_ERR);if(~e.inArray(o.toUpperCase(),["CONNECT","DELETE","GET","HEAD","OPTIONS","POST","PUT","TRACE","TRACK"])&&(v=o.toUpperCase()),~e.inArray(v,["CONNECT","TRACE","TRACK"]))throw new t.DOMException(t.DOMException.SECURITY_ERR);if(a=n.utf8_encode(a),l=r.parseUrl(a),B=r.hasSameOrigin(l),x=r.resolveUrl(a),(u||c)&&!B)throw new t.DOMException(t.DOMException.INVALID_ACCESS_ERR);if(w=u||l.user,y=c||l.pass,T=s||!0,T===!1&&(i("timeout")||i("withCredentials")||""!==i("responseType")))throw new t.DOMException(t.DOMException.INVALID_ACCESS_ERR);N=!T,C=!1,S={},g.call(this),i("readyState",h.OPENED),this.dispatchEvent("readystatechange")},setRequestHeader:function(r,o){var a=["accept-charset","accept-encoding","access-control-request-headers","access-control-request-method","connection","content-length","cookie","cookie2","content-transfer-encoding","date","expect","host","keep-alive","origin","referer","te","trailer","transfer-encoding","upgrade","user-agent","via"];if(i("readyState")!==h.OPENED||C)throw new t.DOMException(t.DOMException.INVALID_STATE_ERR);if(/[\u0100-\uffff]/.test(r)||n.utf8_encode(r)!==r)throw new t.DOMException(t.DOMException.SYNTAX_ERR);return r=e.trim(r).toLowerCase(),~e.inArray(r,a)||/^(proxy\-|sec\-)/.test(r)?!1:(S[r]?S[r]+=", "+o:S[r]=o,!0)},hasRequestHeader:function(e){return e&&S[e.toLowerCase()]||!1},getAllResponseHeaders:function(){return U||""},getResponseHeader:function(t){return t=t.toLowerCase(),F||~e.inArray(t,["set-cookie","set-cookie2"])?null:U&&""!==U&&(_||(_={},e.each(U.split(/\r\n/),function(t){var i=t.split(/:\s+/);2===i.length&&(i[0]=e.trim(i[0]),_[i[0].toLowerCase()]={header:i[0],value:e.trim(i[1])})})),_.hasOwnProperty(t))?_[t].header+": "+_[t].value:null},overrideMimeType:function(n){var r,o;if(~e.inArray(i("readyState"),[h.LOADING,h.DONE]))throw new t.DOMException(t.DOMException.INVALID_STATE_ERR);if(n=e.trim(n.toLowerCase()),/;/.test(n)&&(r=n.match(/^([^;]+)(?:;\scharset\=)?(.*)$/))&&(n=r[1],r[2]&&(o=r[2])),!d.mimes[n])throw new t.DOMException(t.DOMException.SYNTAX_ERR);P=n,H=o},send:function(i,r){if(k="string"===e.typeOf(r)?{ruid:r}:r?r:{},this.readyState!==h.OPENED||C)throw new t.DOMException(t.DOMException.INVALID_STATE_ERR);if(i instanceof s)k.ruid=i.ruid,D=i.type||"application/octet-stream";else if(i instanceof c){if(i.hasBlob()){var o=i.getBlob();k.ruid=o.ruid,D=o.type||"application/octet-stream"}}else"string"==typeof i&&(O="UTF-8",D="text/plain;charset=UTF-8",i=n.utf8_encode(i));this.withCredentials||(this.withCredentials=k.required_caps&&k.required_caps.send_browser_cookies&&!B),L=!N&&this.upload.hasEventListener(),F=!1,M=!i,N||(C=!0),u.call(this,i)},abort:function(){if(F=!0,N=!1,~e.inArray(i("readyState"),[h.UNSENT,h.OPENED,h.DONE]))i("readyState",h.UNSENT);else{if(i("readyState",h.DONE),C=!1,!R)throw new t.DOMException(t.DOMException.INVALID_STATE_ERR);R.getRuntime().exec.call(R,"XMLHttpRequest","abort",M),M=!0}},destroy:function(){R&&("function"===e.typeOf(R.destroy)&&R.destroy(),R=null),this.unbindAll(),this.upload&&(this.upload.unbindAll(),this.upload=null)}}),this.handleEventProps(p.concat(["readystatechange"])),this.upload.handleEventProps(p)}var f={100:"Continue",101:"Switching Protocols",102:"Processing",200:"OK",201:"Created",202:"Accepted",203:"Non-Authoritative Information",204:"No Content",205:"Reset Content",206:"Partial Content",207:"Multi-Status",226:"IM Used",300:"Multiple Choices",301:"Moved Permanently",302:"Found",303:"See Other",304:"Not Modified",305:"Use Proxy",306:"Reserved",307:"Temporary Redirect",400:"Bad Request",401:"Unauthorized",402:"Payment Required",403:"Forbidden",404:"Not Found",405:"Method Not Allowed",406:"Not Acceptable",407:"Proxy Authentication Required",408:"Request Timeout",409:"Conflict",410:"Gone",411:"Length Required",412:"Precondition Failed",413:"Request Entity Too Large",414:"Request-URI Too Long",415:"Unsupported Media Type",416:"Requested Range Not Satisfiable",417:"Expectation Failed",422:"Unprocessable Entity",423:"Locked",424:"Failed Dependency",426:"Upgrade Required",500:"Internal Server Error",501:"Not Implemented",502:"Bad Gateway",503:"Service Unavailable",504:"Gateway Timeout",505:"HTTP Version Not Supported",506:"Variant Also Negotiates",507:"Insufficient Storage",510:"Not Extended"};m.prototype=i.instance;var p=["loadstart","progress","abort","error","load","timeout","loadend"];return h.UNSENT=0,h.OPENED=1,h.HEADERS_RECEIVED=2,h.LOADING=3,h.DONE=4,h.prototype=i.instance,h}),n("moxie/runtime/Transporter",["moxie/core/utils/Basic","moxie/core/utils/Encode","moxie/runtime/RuntimeClient","moxie/core/EventTarget"],function(e,t,i,n){function r(){function n(){l=d=0,c=this.result=null}function o(t,i){var n=this;u=i,n.bind("TransportingProgress",function(t){d=t.loaded,l>d&&-1===e.inArray(n.state,[r.IDLE,r.DONE])&&a.call(n)},999),n.bind("TransportingComplete",function(){d=l,n.state=r.DONE,c=null,n.result=u.exec.call(n,"Transporter","getAsBlob",t||"")},999),n.state=r.BUSY,n.trigger("TransportingStarted"),a.call(n)}function a(){var e,i=this,n=l-d;m>n&&(m=n),e=t.btoa(c.substr(d,m)),u.exec.call(i,"Transporter","receive",e,l)}var s,u,c,l,d,m;i.call(this),e.extend(this,{uid:e.guid("uid_"),state:r.IDLE,result:null,transport:function(t,i,r){var a=this;if(r=e.extend({chunk_size:204798},r),(s=r.chunk_size%3)&&(r.chunk_size+=3-s),m=r.chunk_size,n.call(this),c=t,l=t.length,"string"===e.typeOf(r)||r.ruid)o.call(a,i,this.connectRuntime(r));else{var u=function(e,t){a.unbind("RuntimeInit",u),o.call(a,i,t)};this.bind("RuntimeInit",u),this.connectRuntime(r)}},abort:function(){var e=this;e.state=r.IDLE,u&&(u.exec.call(e,"Transporter","clear"),e.trigger("TransportingAborted")),n.call(e)},destroy:function(){this.unbindAll(),u=null,this.disconnectRuntime(),n.call(this)}})}return r.IDLE=0,r.BUSY=1,r.DONE=2,r.prototype=n.instance,r}),n("moxie/image/Image",["moxie/core/utils/Basic","moxie/core/utils/Dom","moxie/core/Exceptions","moxie/file/FileReaderSync","moxie/xhr/XMLHttpRequest","moxie/runtime/Runtime","moxie/runtime/RuntimeClient","moxie/runtime/Transporter","moxie/core/utils/Env","moxie/core/EventTarget","moxie/file/Blob","moxie/file/File","moxie/core/utils/Encode"],function(e,t,i,n,r,o,a,s,u,c,l,d,m){function h(){function n(e){try{return e||(e=this.exec("Image","getInfo")),this.size=e.size,this.width=e.width,this.height=e.height,this.type=e.type,this.meta=e.meta,""===this.name&&(this.name=e.name),!0}catch(t){return this.trigger("error",t.code),!1}}function c(t){var n=e.typeOf(t);try{if(t instanceof h){if(!t.size)throw new i.DOMException(i.DOMException.INVALID_STATE_ERR);p.apply(this,arguments)}else if(t instanceof l){if(!~e.inArray(t.type,["image/jpeg","image/png"]))throw new i.ImageError(i.ImageError.WRONG_FORMAT);g.apply(this,arguments)}else if(-1!==e.inArray(n,["blob","file"]))c.call(this,new d(null,t),arguments[1]);else if("string"===n)"data:"===t.substr(0,5)?c.call(this,new l(null,{data:t}),arguments[1]):x.apply(this,arguments);else{if("node"!==n||"img"!==t.nodeName.toLowerCase())throw new i.DOMException(i.DOMException.TYPE_MISMATCH_ERR);c.call(this,t.src,arguments[1])}}catch(r){this.trigger("error",r.code)}}function p(t,i){var n=this.connectRuntime(t.ruid);this.ruid=n.uid,n.exec.call(this,"Image","loadFromImage",t,"undefined"===e.typeOf(i)?!0:i)}function g(t,i){function n(e){r.ruid=e.uid,e.exec.call(r,"Image","loadFromBlob",t)}var r=this;r.name=t.name||"",t.isDetached()?(this.bind("RuntimeInit",function(e,t){n(t)}),i&&"string"==typeof i.required_caps&&(i.required_caps=o.parseCaps(i.required_caps)),this.connectRuntime(e.extend({required_caps:{access_image_binary:!0,resize_image:!0}},i))):n(this.connectRuntime(t.ruid))}function x(e,t){var i,n=this;i=new r,i.open("get",e),i.responseType="blob",i.onprogress=function(e){n.trigger(e)},i.onload=function(){g.call(n,i.response,!0)},i.onerror=function(e){n.trigger(e)},i.onloadend=function(){i.destroy()},i.bind("RuntimeError",function(e,t){n.trigger("RuntimeError",t)}),i.send(null,t)}a.call(this),e.extend(this,{uid:e.guid("uid_"),ruid:null,name:"",size:0,width:0,height:0,type:"",meta:{},clone:function(){this.load.apply(this,arguments)},load:function(){c.apply(this,arguments)},resize:function(t){var n,r,o=this,a={x:0,y:0,width:o.width,height:o.height},s=e.extendIf({width:o.width,height:o.height,type:o.type||"image/jpeg",quality:90,crop:!1,fit:!0,preserveHeaders:!0,resample:"default",multipass:!0},t);try{if(!o.size)throw new i.DOMException(i.DOMException.INVALID_STATE_ERR);if(o.width>h.MAX_RESIZE_WIDTH||o.height>h.MAX_RESIZE_HEIGHT)throw new i.ImageError(i.ImageError.MAX_RESOLUTION_ERR);if(n=o.meta&&o.meta.tiff&&o.meta.tiff.Orientation||1,-1!==e.inArray(n,[5,6,7,8])){var u=s.width;s.width=s.height,s.height=u}if(s.crop){switch(r=Math.max(s.width/o.width,s.height/o.height),t.fit?(a.width=Math.min(Math.ceil(s.width/r),o.width),a.height=Math.min(Math.ceil(s.height/r),o.height),r=s.width/a.width):(a.width=Math.min(s.width,o.width),a.height=Math.min(s.height,o.height),r=1),"boolean"==typeof s.crop&&(s.crop="cc"),s.crop.toLowerCase().replace(/_/,"-")){case"rb":case"right-bottom":a.x=o.width-a.width,a.y=o.height-a.height;break;case"cb":case"center-bottom":a.x=Math.floor((o.width-a.width)/2),a.y=o.height-a.height;break;case"lb":case"left-bottom":a.x=0,a.y=o.height-a.height;break;case"lt":case"left-top":a.x=0,a.y=0;break;case"ct":case"center-top":a.x=Math.floor((o.width-a.width)/2),a.y=0;break;case"rt":case"right-top":a.x=o.width-a.width,a.y=0;break;case"rc":case"right-center":case"right-middle":a.x=o.width-a.width,a.y=Math.floor((o.height-a.height)/2);break;case"lc":case"left-center":case"left-middle":a.x=0,a.y=Math.floor((o.height-a.height)/2);break;case"cc":case"center-center":case"center-middle":default:a.x=Math.floor((o.width-a.width)/2),a.y=Math.floor((o.height-a.height)/2)}a.x=Math.max(a.x,0),a.y=Math.max(a.y,0)}else r=Math.min(s.width/o.width,s.height/o.height);this.exec("Image","resize",a,r,s)}catch(c){o.trigger("error",c.code)}},downsize:function(t){var i,n={width:this.width,height:this.height,type:this.type||"image/jpeg",quality:90,crop:!1,preserveHeaders:!0,resample:"default"};i="object"==typeof t?e.extend(n,t):e.extend(n,{width:arguments[0],height:arguments[1],crop:arguments[2],preserveHeaders:arguments[3]}),this.resize(i)},crop:function(e,t,i){this.downsize(e,t,!0,i)},getAsCanvas:function(){if(!u.can("create_canvas"))throw new i.RuntimeError(i.RuntimeError.NOT_SUPPORTED_ERR);return this.exec("Image","getAsCanvas")},getAsBlob:function(e,t){if(!this.size)throw new i.DOMException(i.DOMException.INVALID_STATE_ERR);return this.exec("Image","getAsBlob",e||"image/jpeg",t||90)},getAsDataURL:function(e,t){if(!this.size)throw new i.DOMException(i.DOMException.INVALID_STATE_ERR);return this.exec("Image","getAsDataURL",e||"image/jpeg",t||90)},getAsBinaryString:function(e,t){var i=this.getAsDataURL(e,t);return m.atob(i.substring(i.indexOf("base64,")+7))},embed:function(n,r){function o(t,r){var o=this;if(u.can("create_canvas")){var l=o.getAsCanvas();if(l)return n.appendChild(l),l=null,o.destroy(),c.trigger("embedded"),void 0}var d=o.getAsDataURL(t,r);if(!d)throw new i.ImageError(i.ImageError.WRONG_FORMAT);if(u.can("use_data_uri_of",d.length))n.innerHTML='<img src="'+d+'" width="'+o.width+'" height="'+o.height+'" />',o.destroy(),c.trigger("embedded");else{var h=new s;h.bind("TransportingComplete",function(){a=c.connectRuntime(this.result.ruid),c.bind("Embedded",function(){e.extend(a.getShimContainer().style,{top:"0px",left:"0px",width:o.width+"px",height:o.height+"px"}),a=null},999),a.exec.call(c,"ImageView","display",this.result.uid,width,height),o.destroy()}),h.transport(m.atob(d.substring(d.indexOf("base64,")+7)),t,{required_caps:{display_media:!0},runtime_order:"flash,silverlight",container:n})}}var a,c=this,l=e.extend({width:this.width,height:this.height,type:this.type||"image/jpeg",quality:90},r);try{if(!(n=t.get(n)))throw new i.DOMException(i.DOMException.INVALID_NODE_TYPE_ERR);if(!this.size)throw new i.DOMException(i.DOMException.INVALID_STATE_ERR);this.width>h.MAX_RESIZE_WIDTH||this.height>h.MAX_RESIZE_HEIGHT;var d=new h;return d.bind("Resize",function(){o.call(this,l.type,l.quality)}),d.bind("Load",function(){this.downsize(l)}),this.meta.thumb&&this.meta.thumb.width>=l.width&&this.meta.thumb.height>=l.height?d.load(this.meta.thumb.data):d.clone(this,!1),d}catch(f){this.trigger("error",f.code)}},destroy:function(){this.ruid&&(this.getRuntime().exec.call(this,"Image","destroy"),this.disconnectRuntime()),this.meta&&this.meta.thumb&&this.meta.thumb.data.destroy(),this.unbindAll()}}),this.handleEventProps(f),this.bind("Load Resize",function(){return n.call(this)},999)}var f=["progress","load","error","resize","embedded"];return h.MAX_RESIZE_WIDTH=8192,h.MAX_RESIZE_HEIGHT=8192,h.prototype=c.instance,h}),n("moxie/runtime/html5/Runtime",["moxie/core/utils/Basic","moxie/core/Exceptions","moxie/runtime/Runtime","moxie/core/utils/Env"],function(e,t,i,n){function o(t){var o=this,u=i.capTest,c=i.capTrue,l=e.extend({access_binary:u(window.FileReader||window.File&&window.File.getAsDataURL),access_image_binary:function(){return o.can("access_binary")&&!!s.Image},display_media:u((n.can("create_canvas")||n.can("use_data_uri_over32kb"))&&r("moxie/image/Image")),do_cors:u(window.XMLHttpRequest&&"withCredentials"in new XMLHttpRequest),drag_and_drop:u(function(){var e=document.createElement("div");return("draggable"in e||"ondragstart"in e&&"ondrop"in e)&&("IE"!==n.browser||n.verComp(n.version,9,">"))}()),filter_by_extension:u(function(){return!("Chrome"===n.browser&&n.verComp(n.version,28,"<")||"IE"===n.browser&&n.verComp(n.version,10,"<")||"Safari"===n.browser&&n.verComp(n.version,7,"<")||"Firefox"===n.browser&&n.verComp(n.version,37,"<"))}()),return_response_headers:c,return_response_type:function(e){return"json"===e&&window.JSON?!0:n.can("return_response_type",e)},return_status_code:c,report_upload_progress:u(window.XMLHttpRequest&&(new XMLHttpRequest).upload),resize_image:function(){return o.can("access_binary")&&n.can("create_canvas")},select_file:function(){return n.can("use_fileinput")&&window.File},select_folder:function(){return o.can("select_file")&&("Chrome"===n.browser&&n.verComp(n.version,21,">=")||"Firefox"===n.browser&&n.verComp(n.version,42,">="))},select_multiple:function(){return!(!o.can("select_file")||"Safari"===n.browser&&"Windows"===n.os||"iOS"===n.os&&n.verComp(n.osVersion,"7.0.0",">")&&n.verComp(n.osVersion,"8.0.0","<"))},send_binary_string:u(window.XMLHttpRequest&&((new XMLHttpRequest).sendAsBinary||window.Uint8Array&&window.ArrayBuffer)),send_custom_headers:u(window.XMLHttpRequest),send_multipart:function(){return!!(window.XMLHttpRequest&&(new XMLHttpRequest).upload&&window.FormData)||o.can("send_binary_string")},slice_blob:u(window.File&&(File.prototype.mozSlice||File.prototype.webkitSlice||File.prototype.slice)),stream_upload:function(){return o.can("slice_blob")&&o.can("send_multipart")},summon_file_dialog:function(){return o.can("select_file")&&("Firefox"===n.browser&&n.verComp(n.version,4,">=")||"Opera"===n.browser&&n.verComp(n.version,12,">=")||"IE"===n.browser&&n.verComp(n.version,10,">=")||!!~e.inArray(n.browser,["Chrome","Safari","Edge"]))},upload_filesize:c,use_http_method:c},arguments[2]);i.call(this,t,arguments[1]||a,l),e.extend(this,{init:function(){this.trigger("Init")},destroy:function(e){return function(){e.call(o),e=o=null}}(this.destroy)}),e.extend(this.getShim(),s)}var a="html5",s={};return i.addConstructor(a,o),s}),n("moxie/runtime/html5/file/Blob",["moxie/runtime/html5/Runtime","moxie/file/Blob"],function(e,t){function i(){function e(e,t,i){var n;if(!window.File.prototype.slice)return(n=window.File.prototype.webkitSlice||window.File.prototype.mozSlice)?n.call(e,t,i):null;try{return e.slice(),e.slice(t,i)}catch(r){return e.slice(t,i-t)}}this.slice=function(){return new t(this.getRuntime().uid,e.apply(this,arguments))}}return e.Blob=i}),n("moxie/core/utils/Events",["moxie/core/utils/Basic"],function(e){function t(){this.returnValue=!1}function i(){this.cancelBubble=!0}var n={},r="moxie_"+e.guid(),o=function(o,a,s,u){var c,l;a=a.toLowerCase(),o.addEventListener?(c=s,o.addEventListener(a,c,!1)):o.attachEvent&&(c=function(){var e=window.event;e.target||(e.target=e.srcElement),e.preventDefault=t,e.stopPropagation=i,s(e)},o.attachEvent("on"+a,c)),o[r]||(o[r]=e.guid()),n.hasOwnProperty(o[r])||(n[o[r]]={}),l=n[o[r]],l.hasOwnProperty(a)||(l[a]=[]),l[a].push({func:c,orig:s,key:u})},a=function(t,i,o){var a,s;if(i=i.toLowerCase(),t[r]&&n[t[r]]&&n[t[r]][i]){a=n[t[r]][i];for(var u=a.length-1;u>=0&&(a[u].orig!==o&&a[u].key!==o||(t.removeEventListener?t.removeEventListener(i,a[u].func,!1):t.detachEvent&&t.detachEvent("on"+i,a[u].func),a[u].orig=null,a[u].func=null,a.splice(u,1),o===s));u--);if(a.length||delete n[t[r]][i],e.isEmptyObj(n[t[r]])){delete n[t[r]];try{delete t[r]}catch(c){t[r]=s}}}},s=function(t,i){t&&t[r]&&e.each(n[t[r]],function(e,n){a(t,n,i)})};return{addEvent:o,removeEvent:a,removeAllEvents:s}}),n("moxie/runtime/html5/file/FileInput",["moxie/runtime/html5/Runtime","moxie/file/File","moxie/core/utils/Basic","moxie/core/utils/Dom","moxie/core/utils/Events","moxie/core/utils/Mime","moxie/core/utils/Env"],function(e,t,i,n,r,o,a){function s(){var e,s;i.extend(this,{init:function(u){var c,l,d,m,h,f,p=this,g=p.getRuntime();e=u,d=e.accept.mimes||o.extList2mimes(e.accept,g.can("filter_by_extension")),l=g.getShimContainer(),l.innerHTML='<input id="'+g.uid+'" type="file" style="font-size:999px;opacity:0;"'+(e.multiple&&g.can("select_multiple")?"multiple":"")+(e.directory&&g.can("select_folder")?"webkitdirectory directory":"")+(d?' accept="'+d.join(",")+'"':"")+" />",c=n.get(g.uid),i.extend(c.style,{position:"absolute",top:0,left:0,width:"100%",height:"100%"}),m=n.get(e.browse_button),s=n.getStyle(m,"z-index")||"auto",g.can("summon_file_dialog")&&("static"===n.getStyle(m,"position")&&(m.style.position="relative"),r.addEvent(m,"click",function(e){var t=n.get(g.uid);t&&!t.disabled&&t.click(),e.preventDefault()},p.uid),p.bind("Refresh",function(){h=parseInt(s,10)||1,n.get(e.browse_button).style.zIndex=h,this.getRuntime().getShimContainer().style.zIndex=h-1})),f=g.can("summon_file_dialog")?m:l,r.addEvent(f,"mouseover",function(){p.trigger("mouseenter")},p.uid),r.addEvent(f,"mouseout",function(){p.trigger("mouseleave")},p.uid),r.addEvent(f,"mousedown",function(){p.trigger("mousedown")},p.uid),r.addEvent(n.get(e.container),"mouseup",function(){p.trigger("mouseup")},p.uid),c.onchange=function x(){if(p.files=[],i.each(this.files,function(i){var n="";return e.directory&&"."==i.name?!0:(i.webkitRelativePath&&(n="/"+i.webkitRelativePath.replace(/^\//,"")),i=new t(g.uid,i),i.relativePath=n,p.files.push(i),void 0)}),"IE"!==a.browser&&"IEMobile"!==a.browser)this.value="";else{var n=this.cloneNode(!0);this.parentNode.replaceChild(n,this),n.onchange=x}p.files.length&&p.trigger("change")},p.trigger({type:"ready",async:!0}),l=null},setOption:function(e,t){var i=this.getRuntime(),r=n.get(i.uid);switch(e){case"accept":if(t){var a=t.mimes||o.extList2mimes(t,i.can("filter_by_extension"));r.setAttribute("accept",a.join(","))}else r.removeAttribute("accept");break;case"directory":t&&i.can("select_folder")?(r.setAttribute("directory",""),r.setAttribute("webkitdirectory","")):(r.removeAttribute("directory"),r.removeAttribute("webkitdirectory"));break;case"multiple":t&&i.can("select_multiple")?r.setAttribute("multiple",""):r.removeAttribute("multiple")}},disable:function(e){var t,i=this.getRuntime();(t=n.get(i.uid))&&(t.disabled=!!e)},destroy:function(){var t=this.getRuntime(),i=t.getShim(),o=t.getShimContainer(),a=e&&n.get(e.container),u=e&&n.get(e.browse_button);a&&r.removeAllEvents(a,this.uid),u&&(r.removeAllEvents(u,this.uid),u.style.zIndex=s),o&&(r.removeAllEvents(o,this.uid),o.innerHTML=""),i.removeInstance(this.uid),e=o=a=u=i=null}})}return e.FileInput=s}),n("moxie/runtime/html5/file/FileDrop",["moxie/runtime/html5/Runtime","moxie/file/File","moxie/core/utils/Basic","moxie/core/utils/Dom","moxie/core/utils/Events","moxie/core/utils/Mime"],function(e,t,i,n,r,o){function a(){function e(e){if(!e.dataTransfer||!e.dataTransfer.types)return!1;var t=i.toArray(e.dataTransfer.types||[]);return-1!==i.inArray("Files",t)||-1!==i.inArray("public.file-url",t)||-1!==i.inArray("application/x-moz-file",t)}function a(e,i){if(u(e)){var n=new t(f,e);n.relativePath=i||"",p.push(n)}}function s(e){for(var t=[],n=0;n<e.length;n++)[].push.apply(t,e[n].extensions.split(/\s*,\s*/));return-1===i.inArray("*",t)?t:[]}function u(e){if(!g.length)return!0;var t=o.getFileExtension(e.name);return!t||-1!==i.inArray(t,g)}function c(e,t){var n=[];i.each(e,function(e){var t=e.webkitGetAsEntry();t&&(t.isFile?a(e.getAsFile(),t.fullPath):n.push(t))}),n.length?l(n,t):t()}function l(e,t){var n=[];i.each(e,function(e){n.push(function(t){d(e,t)})}),i.inSeries(n,function(){t()})}function d(e,t){e.isFile?e.file(function(i){a(i,e.fullPath),t()},function(){t()}):e.isDirectory?m(e,t):t()}function m(e,t){function i(e){r.readEntries(function(t){t.length?([].push.apply(n,t),i(e)):e()},e)}var n=[],r=e.createReader();i(function(){l(n,t)})}var h,f,p=[],g=[];i.extend(this,{init:function(t){var n,o=this;h=t,f=o.ruid,g=s(h.accept),n=h.container,r.addEvent(n,"dragover",function(t){e(t)&&(t.preventDefault(),t.dataTransfer.dropEffect="copy")},o.uid),r.addEvent(n,"drop",function(t){e(t)&&(t.preventDefault(),p=[],t.dataTransfer.items&&t.dataTransfer.items[0].webkitGetAsEntry?c(t.dataTransfer.items,function(){o.files=p,o.trigger("drop")}):(i.each(t.dataTransfer.files,function(e){a(e)}),o.files=p,o.trigger("drop")))},o.uid),r.addEvent(n,"dragenter",function(){o.trigger("dragenter")},o.uid),r.addEvent(n,"dragleave",function(){o.trigger("dragleave")},o.uid)},destroy:function(){r.removeAllEvents(h&&n.get(h.container),this.uid),f=p=g=h=null}})}return e.FileDrop=a}),n("moxie/runtime/html5/file/FileReader",["moxie/runtime/html5/Runtime","moxie/core/utils/Encode","moxie/core/utils/Basic"],function(e,t,i){function n(){function e(e){return t.atob(e.substring(e.indexOf("base64,")+7))}var n,r=!1;i.extend(this,{read:function(t,o){var a=this;a.result="",n=new window.FileReader,n.addEventListener("progress",function(e){a.trigger(e)}),n.addEventListener("load",function(t){a.result=r?e(n.result):n.result,a.trigger(t)}),n.addEventListener("error",function(e){a.trigger(e,n.error)}),n.addEventListener("loadend",function(e){n=null,a.trigger(e)}),"function"===i.typeOf(n[t])?(r=!1,n[t](o.getSource())):"readAsBinaryString"===t&&(r=!0,n.readAsDataURL(o.getSource()))},abort:function(){n&&n.abort()},destroy:function(){n=null}})}return e.FileReader=n}),n("moxie/runtime/html5/xhr/XMLHttpRequest",["moxie/runtime/html5/Runtime","moxie/core/utils/Basic","moxie/core/utils/Mime","moxie/core/utils/Url","moxie/file/File","moxie/file/Blob","moxie/xhr/FormData","moxie/core/Exceptions","moxie/core/utils/Env"],function(e,t,i,n,r,o,a,s,u){function c(){function e(e,t){var i,n,r=this;i=t.getBlob().getSource(),n=new window.FileReader,n.onload=function(){t.append(t.getBlobName(),new o(null,{type:i.type,data:n.result})),f.send.call(r,e,t)},n.readAsBinaryString(i)}function c(){return!window.XMLHttpRequest||"IE"===u.browser&&u.verComp(u.version,8,"<")?function(){for(var e=["Msxml2.XMLHTTP.6.0","Microsoft.XMLHTTP"],t=0;t<e.length;t++)try{return new ActiveXObject(e[t])}catch(i){}}():new window.XMLHttpRequest}function l(e){var t=e.responseXML,i=e.responseText;return"IE"===u.browser&&i&&t&&!t.documentElement&&/[^\/]+\/[^\+]+\+xml/.test(e.getResponseHeader("Content-Type"))&&(t=new window.ActiveXObject("Microsoft.XMLDOM"),t.async=!1,t.validateOnParse=!1,t.loadXML(i)),t&&("IE"===u.browser&&0!==t.parseError||!t.documentElement||"parsererror"===t.documentElement.tagName)?null:t}function d(e){var t="----moxieboundary"+(new Date).getTime(),i="--",n="\r\n",r="",a=this.getRuntime();if(!a.can("send_binary_string"))throw new s.RuntimeError(s.RuntimeError.NOT_SUPPORTED_ERR);return m.setRequestHeader("Content-Type","multipart/form-data; boundary="+t),e.each(function(e,a){r+=e instanceof o?i+t+n+'Content-Disposition: form-data; name="'+a+'"; filename="'+unescape(encodeURIComponent(e.name||"blob"))+'"'+n+"Content-Type: "+(e.type||"application/octet-stream")+n+n+e.getSource()+n:i+t+n+'Content-Disposition: form-data; name="'+a+'"'+n+n+unescape(encodeURIComponent(e))+n}),r+=i+t+i+n}var m,h,f=this;t.extend(this,{send:function(i,r){var s=this,l="Mozilla"===u.browser&&u.verComp(u.version,4,">=")&&u.verComp(u.version,7,"<"),f="Android Browser"===u.browser,p=!1;
if(h=i.url.replace(/^.+?\/([\w\-\.]+)$/,"$1").toLowerCase(),m=c(),m.open(i.method,i.url,i.async,i.user,i.password),r instanceof o)r.isDetached()&&(p=!0),r=r.getSource();else if(r instanceof a){if(r.hasBlob())if(r.getBlob().isDetached())r=d.call(s,r),p=!0;else if((l||f)&&"blob"===t.typeOf(r.getBlob().getSource())&&window.FileReader)return e.call(s,i,r),void 0;if(r instanceof a){var g=new window.FormData;r.each(function(e,t){e instanceof o?g.append(t,e.getSource()):g.append(t,e)}),r=g}}m.upload?(i.withCredentials&&(m.withCredentials=!0),m.addEventListener("load",function(e){s.trigger(e)}),m.addEventListener("error",function(e){s.trigger(e)}),m.addEventListener("progress",function(e){s.trigger(e)}),m.upload.addEventListener("progress",function(e){s.trigger({type:"UploadProgress",loaded:e.loaded,total:e.total})})):m.onreadystatechange=function(){switch(m.readyState){case 1:break;case 2:break;case 3:var e,t;try{n.hasSameOrigin(i.url)&&(e=m.getResponseHeader("Content-Length")||0),m.responseText&&(t=m.responseText.length)}catch(r){e=t=0}s.trigger({type:"progress",lengthComputable:!!e,total:parseInt(e,10),loaded:t});break;case 4:m.onreadystatechange=function(){},0===m.status?s.trigger("error"):s.trigger("load")}},t.isEmptyObj(i.headers)||t.each(i.headers,function(e,t){m.setRequestHeader(t,e)}),""!==i.responseType&&"responseType"in m&&(m.responseType="json"!==i.responseType||u.can("return_response_type","json")?i.responseType:"text"),p?m.sendAsBinary?m.sendAsBinary(r):function(){for(var e=new Uint8Array(r.length),t=0;t<r.length;t++)e[t]=255&r.charCodeAt(t);m.send(e.buffer)}():m.send(r),s.trigger("loadstart")},getStatus:function(){try{if(m)return m.status}catch(e){}return 0},getResponse:function(e){var t=this.getRuntime();try{switch(e){case"blob":var n=new r(t.uid,m.response),o=m.getResponseHeader("Content-Disposition");if(o){var a=o.match(/filename=([\'\"'])([^\1]+)\1/);a&&(h=a[2])}return n.name=h,n.type||(n.type=i.getFileMime(h)),n;case"json":return u.can("return_response_type","json")?m.response:200===m.status&&window.JSON?JSON.parse(m.responseText):null;case"document":return l(m);default:return""!==m.responseText?m.responseText:null}}catch(s){return null}},getAllResponseHeaders:function(){try{return m.getAllResponseHeaders()}catch(e){}return""},abort:function(){m&&m.abort()},destroy:function(){f=h=null}})}return e.XMLHttpRequest=c}),n("moxie/runtime/html5/utils/BinaryReader",["moxie/core/utils/Basic"],function(e){function t(e){e instanceof ArrayBuffer?i.apply(this,arguments):n.apply(this,arguments)}function i(t){var i=new DataView(t);e.extend(this,{readByteAt:function(e){return i.getUint8(e)},writeByteAt:function(e,t){i.setUint8(e,t)},SEGMENT:function(e,n,r){switch(arguments.length){case 2:return t.slice(e,e+n);case 1:return t.slice(e);case 3:if(null===r&&(r=new ArrayBuffer),r instanceof ArrayBuffer){var o=new Uint8Array(this.length()-n+r.byteLength);e>0&&o.set(new Uint8Array(t.slice(0,e)),0),o.set(new Uint8Array(r),e),o.set(new Uint8Array(t.slice(e+n)),e+r.byteLength),this.clear(),t=o.buffer,i=new DataView(t);break}default:return t}},length:function(){return t?t.byteLength:0},clear:function(){i=t=null}})}function n(t){function i(e,i,n){n=3===arguments.length?n:t.length-i-1,t=t.substr(0,i)+e+t.substr(n+i)}e.extend(this,{readByteAt:function(e){return t.charCodeAt(e)},writeByteAt:function(e,t){i(String.fromCharCode(t),e,1)},SEGMENT:function(e,n,r){switch(arguments.length){case 1:return t.substr(e);case 2:return t.substr(e,n);case 3:i(null!==r?r:"",e,n);break;default:return t}},length:function(){return t?t.length:0},clear:function(){t=null}})}return e.extend(t.prototype,{littleEndian:!1,read:function(e,t){var i,n,r;if(e+t>this.length())throw new Error("You are trying to read outside the source boundaries.");for(n=this.littleEndian?0:-8*(t-1),r=0,i=0;t>r;r++)i|=this.readByteAt(e+r)<<Math.abs(n+8*r);return i},write:function(e,t,i){var n,r;if(e>this.length())throw new Error("You are trying to write outside the source boundaries.");for(n=this.littleEndian?0:-8*(i-1),r=0;i>r;r++)this.writeByteAt(e+r,255&t>>Math.abs(n+8*r))},BYTE:function(e){return this.read(e,1)},SHORT:function(e){return this.read(e,2)},LONG:function(e){return this.read(e,4)},SLONG:function(e){var t=this.read(e,4);return t>2147483647?t-4294967296:t},CHAR:function(e){return String.fromCharCode(this.read(e,1))},STRING:function(e,t){return this.asArray("CHAR",e,t).join("")},asArray:function(e,t,i){for(var n=[],r=0;i>r;r++)n[r]=this[e](t+r);return n}}),t}),n("moxie/runtime/html5/image/JPEGHeaders",["moxie/runtime/html5/utils/BinaryReader","moxie/core/Exceptions"],function(e,t){return function i(n){var r,o,a,s=[],u=0;if(r=new e(n),65496!==r.SHORT(0))throw r.clear(),new t.ImageError(t.ImageError.WRONG_FORMAT);for(o=2;o<=r.length();)if(a=r.SHORT(o),a>=65488&&65495>=a)o+=2;else{if(65498===a||65497===a)break;u=r.SHORT(o+2)+2,a>=65505&&65519>=a&&s.push({hex:a,name:"APP"+(15&a),start:o,length:u,segment:r.SEGMENT(o,u)}),o+=u}return r.clear(),{headers:s,restore:function(t){var i,n,r;for(r=new e(t),o=65504==r.SHORT(2)?4+r.SHORT(4):2,n=0,i=s.length;i>n;n++)r.SEGMENT(o,0,s[n].segment),o+=s[n].length;return t=r.SEGMENT(),r.clear(),t},strip:function(t){var n,r,o,a;for(o=new i(t),r=o.headers,o.purge(),n=new e(t),a=r.length;a--;)n.SEGMENT(r[a].start,r[a].length,"");return t=n.SEGMENT(),n.clear(),t},get:function(e){for(var t=[],i=0,n=s.length;n>i;i++)s[i].name===e.toUpperCase()&&t.push(s[i].segment);return t},set:function(e,t){var i,n,r,o=[];for("string"==typeof t?o.push(t):o=t,i=n=0,r=s.length;r>i&&(s[i].name===e.toUpperCase()&&(s[i].segment=o[n],s[i].length=o[n].length,n++),!(n>=o.length));i++);},purge:function(){this.headers=s=[]}}}}),n("moxie/runtime/html5/image/ExifParser",["moxie/core/utils/Basic","moxie/runtime/html5/utils/BinaryReader","moxie/core/Exceptions"],function(e,i,n){function r(o){function a(i,r){var o,a,s,u,c,m,h,f,p=this,g=[],x={},v={1:"BYTE",7:"UNDEFINED",2:"ASCII",3:"SHORT",4:"LONG",5:"RATIONAL",9:"SLONG",10:"SRATIONAL"},w={BYTE:1,UNDEFINED:1,ASCII:1,SHORT:2,LONG:4,RATIONAL:8,SLONG:4,SRATIONAL:8};for(o=p.SHORT(i),a=0;o>a;a++)if(g=[],h=i+2+12*a,s=r[p.SHORT(h)],s!==t){if(u=v[p.SHORT(h+=2)],c=p.LONG(h+=2),m=w[u],!m)throw new n.ImageError(n.ImageError.INVALID_META_ERR);if(h+=4,m*c>4&&(h=p.LONG(h)+d.tiffHeader),h+m*c>=this.length())throw new n.ImageError(n.ImageError.INVALID_META_ERR);"ASCII"!==u?(g=p.asArray(u,h,c),f=1==c?g[0]:g,x[s]=l.hasOwnProperty(s)&&"object"!=typeof f?l[s][f]:f):x[s]=e.trim(p.STRING(h,c).replace(/\0$/,""))}return x}function s(e,t,i){var n,r,o,a=0;if("string"==typeof t){var s=c[e.toLowerCase()];for(var u in s)if(s[u]===t){t=u;break}}n=d[e.toLowerCase()+"IFD"],r=this.SHORT(n);for(var l=0;r>l;l++)if(o=n+12*l+2,this.SHORT(o)==t){a=o+8;break}if(!a)return!1;try{this.write(a,i,4)}catch(m){return!1}return!0}var u,c,l,d,m,h;if(i.call(this,o),c={tiff:{274:"Orientation",270:"ImageDescription",271:"Make",272:"Model",305:"Software",34665:"ExifIFDPointer",34853:"GPSInfoIFDPointer"},exif:{36864:"ExifVersion",40961:"ColorSpace",40962:"PixelXDimension",40963:"PixelYDimension",36867:"DateTimeOriginal",33434:"ExposureTime",33437:"FNumber",34855:"ISOSpeedRatings",37377:"ShutterSpeedValue",37378:"ApertureValue",37383:"MeteringMode",37384:"LightSource",37385:"Flash",37386:"FocalLength",41986:"ExposureMode",41987:"WhiteBalance",41990:"SceneCaptureType",41988:"DigitalZoomRatio",41992:"Contrast",41993:"Saturation",41994:"Sharpness"},gps:{0:"GPSVersionID",1:"GPSLatitudeRef",2:"GPSLatitude",3:"GPSLongitudeRef",4:"GPSLongitude"},thumb:{513:"JPEGInterchangeFormat",514:"JPEGInterchangeFormatLength"}},l={ColorSpace:{1:"sRGB",0:"Uncalibrated"},MeteringMode:{0:"Unknown",1:"Average",2:"CenterWeightedAverage",3:"Spot",4:"MultiSpot",5:"Pattern",6:"Partial",255:"Other"},LightSource:{1:"Daylight",2:"Fliorescent",3:"Tungsten",4:"Flash",9:"Fine weather",10:"Cloudy weather",11:"Shade",12:"Daylight fluorescent (D 5700 - 7100K)",13:"Day white fluorescent (N 4600 -5400K)",14:"Cool white fluorescent (W 3900 - 4500K)",15:"White fluorescent (WW 3200 - 3700K)",17:"Standard light A",18:"Standard light B",19:"Standard light C",20:"D55",21:"D65",22:"D75",23:"D50",24:"ISO studio tungsten",255:"Other"},Flash:{0:"Flash did not fire",1:"Flash fired",5:"Strobe return light not detected",7:"Strobe return light detected",9:"Flash fired, compulsory flash mode",13:"Flash fired, compulsory flash mode, return light not detected",15:"Flash fired, compulsory flash mode, return light detected",16:"Flash did not fire, compulsory flash mode",24:"Flash did not fire, auto mode",25:"Flash fired, auto mode",29:"Flash fired, auto mode, return light not detected",31:"Flash fired, auto mode, return light detected",32:"No flash function",65:"Flash fired, red-eye reduction mode",69:"Flash fired, red-eye reduction mode, return light not detected",71:"Flash fired, red-eye reduction mode, return light detected",73:"Flash fired, compulsory flash mode, red-eye reduction mode",77:"Flash fired, compulsory flash mode, red-eye reduction mode, return light not detected",79:"Flash fired, compulsory flash mode, red-eye reduction mode, return light detected",89:"Flash fired, auto mode, red-eye reduction mode",93:"Flash fired, auto mode, return light not detected, red-eye reduction mode",95:"Flash fired, auto mode, return light detected, red-eye reduction mode"},ExposureMode:{0:"Auto exposure",1:"Manual exposure",2:"Auto bracket"},WhiteBalance:{0:"Auto white balance",1:"Manual white balance"},SceneCaptureType:{0:"Standard",1:"Landscape",2:"Portrait",3:"Night scene"},Contrast:{0:"Normal",1:"Soft",2:"Hard"},Saturation:{0:"Normal",1:"Low saturation",2:"High saturation"},Sharpness:{0:"Normal",1:"Soft",2:"Hard"},GPSLatitudeRef:{N:"North latitude",S:"South latitude"},GPSLongitudeRef:{E:"East longitude",W:"West longitude"}},d={tiffHeader:10},m=d.tiffHeader,u={clear:this.clear},e.extend(this,{read:function(){try{return r.prototype.read.apply(this,arguments)}catch(e){throw new n.ImageError(n.ImageError.INVALID_META_ERR)}},write:function(){try{return r.prototype.write.apply(this,arguments)}catch(e){throw new n.ImageError(n.ImageError.INVALID_META_ERR)}},UNDEFINED:function(){return this.BYTE.apply(this,arguments)},RATIONAL:function(e){return this.LONG(e)/this.LONG(e+4)},SRATIONAL:function(e){return this.SLONG(e)/this.SLONG(e+4)},ASCII:function(e){return this.CHAR(e)},TIFF:function(){return h||null},EXIF:function(){var t=null;if(d.exifIFD){try{t=a.call(this,d.exifIFD,c.exif)}catch(i){return null}if(t.ExifVersion&&"array"===e.typeOf(t.ExifVersion)){for(var n=0,r="";n<t.ExifVersion.length;n++)r+=String.fromCharCode(t.ExifVersion[n]);t.ExifVersion=r}}return t},GPS:function(){var t=null;if(d.gpsIFD){try{t=a.call(this,d.gpsIFD,c.gps)}catch(i){return null}t.GPSVersionID&&"array"===e.typeOf(t.GPSVersionID)&&(t.GPSVersionID=t.GPSVersionID.join("."))}return t},thumb:function(){if(d.IFD1)try{var e=a.call(this,d.IFD1,c.thumb);if("JPEGInterchangeFormat"in e)return this.SEGMENT(d.tiffHeader+e.JPEGInterchangeFormat,e.JPEGInterchangeFormatLength)}catch(t){}return null},setExif:function(e,t){return"PixelXDimension"!==e&&"PixelYDimension"!==e?!1:s.call(this,"exif",e,t)},clear:function(){u.clear(),o=c=l=h=d=u=null}}),65505!==this.SHORT(0)||"EXIF\0"!==this.STRING(4,5).toUpperCase())throw new n.ImageError(n.ImageError.INVALID_META_ERR);if(this.littleEndian=18761==this.SHORT(m),42!==this.SHORT(m+=2))throw new n.ImageError(n.ImageError.INVALID_META_ERR);d.IFD0=d.tiffHeader+this.LONG(m+=2),h=a.call(this,d.IFD0,c.tiff),"ExifIFDPointer"in h&&(d.exifIFD=d.tiffHeader+h.ExifIFDPointer,delete h.ExifIFDPointer),"GPSInfoIFDPointer"in h&&(d.gpsIFD=d.tiffHeader+h.GPSInfoIFDPointer,delete h.GPSInfoIFDPointer),e.isEmptyObj(h)&&(h=null);var f=this.LONG(d.IFD0+12*this.SHORT(d.IFD0)+2);f&&(d.IFD1=d.tiffHeader+f)}return r.prototype=i.prototype,r}),n("moxie/runtime/html5/image/JPEG",["moxie/core/utils/Basic","moxie/core/Exceptions","moxie/runtime/html5/image/JPEGHeaders","moxie/runtime/html5/utils/BinaryReader","moxie/runtime/html5/image/ExifParser"],function(e,t,i,n,r){function o(o){function a(e){var t,i,n=0;for(e||(e=c);n<=e.length();){if(t=e.SHORT(n+=2),t>=65472&&65475>=t)return n+=5,{height:e.SHORT(n),width:e.SHORT(n+=2)};i=e.SHORT(n+=2),n+=i-2}return null}function s(){var e,t,i=d.thumb();return i&&(e=new n(i),t=a(e),e.clear(),t)?(t.data=i,t):null}function u(){d&&l&&c&&(d.clear(),l.purge(),c.clear(),m=l=d=c=null)}var c,l,d,m;if(c=new n(o),65496!==c.SHORT(0))throw new t.ImageError(t.ImageError.WRONG_FORMAT);l=new i(o);try{d=new r(l.get("app1")[0])}catch(h){}m=a.call(this),e.extend(this,{type:"image/jpeg",size:c.length(),width:m&&m.width||0,height:m&&m.height||0,setExif:function(t,i){return d?("object"===e.typeOf(t)?e.each(t,function(e,t){d.setExif(t,e)}):d.setExif(t,i),l.set("app1",d.SEGMENT()),void 0):!1},writeHeaders:function(){return arguments.length?l.restore(arguments[0]):l.restore(o)},stripHeaders:function(e){return l.strip(e)},purge:function(){u.call(this)}}),d&&(this.meta={tiff:d.TIFF(),exif:d.EXIF(),gps:d.GPS(),thumb:s()})}return o}),n("moxie/runtime/html5/image/PNG",["moxie/core/Exceptions","moxie/core/utils/Basic","moxie/runtime/html5/utils/BinaryReader"],function(e,t,i){function n(n){function r(){var e,t;return e=a.call(this,8),"IHDR"==e.type?(t=e.start,{width:s.LONG(t),height:s.LONG(t+=4)}):null}function o(){s&&(s.clear(),n=l=u=c=s=null)}function a(e){var t,i,n,r;return t=s.LONG(e),i=s.STRING(e+=4,4),n=e+=4,r=s.LONG(e+t),{length:t,type:i,start:n,CRC:r}}var s,u,c,l;s=new i(n),function(){var t=0,i=0,n=[35152,20039,3338,6666];for(i=0;i<n.length;i++,t+=2)if(n[i]!=s.SHORT(t))throw new e.ImageError(e.ImageError.WRONG_FORMAT)}(),l=r.call(this),t.extend(this,{type:"image/png",size:s.length(),width:l.width,height:l.height,purge:function(){o.call(this)}}),o.call(this)}return n}),n("moxie/runtime/html5/image/ImageInfo",["moxie/core/utils/Basic","moxie/core/Exceptions","moxie/runtime/html5/image/JPEG","moxie/runtime/html5/image/PNG"],function(e,t,i,n){return function(r){var o,a=[i,n];o=function(){for(var e=0;e<a.length;e++)try{return new a[e](r)}catch(i){}throw new t.ImageError(t.ImageError.WRONG_FORMAT)}(),e.extend(this,{type:"",size:0,width:0,height:0,setExif:function(){},writeHeaders:function(e){return e},stripHeaders:function(e){return e},purge:function(){r=null}}),e.extend(this,o),this.purge=function(){o.purge(),o=null}}}),n("moxie/runtime/html5/image/ResizerCanvas",[],function(){function e(i,n){var r=i.width,o=Math.floor(r*n),a=!1;(.5>n||n>2)&&(n=.5>n?.5:2,a=!0);var s=t(i,n);return a?e(s,o/s.width):s}function t(e,t){var i=e.width,n=e.height,r=Math.floor(i*t),o=Math.floor(n*t),a=document.createElement("canvas");return a.width=r,a.height=o,a.getContext("2d").drawImage(e,0,0,i,n,0,0,r,o),e=null,a}return{scale:e}}),n("moxie/runtime/html5/image/Image",["moxie/runtime/html5/Runtime","moxie/core/utils/Basic","moxie/core/Exceptions","moxie/core/utils/Encode","moxie/file/Blob","moxie/file/File","moxie/runtime/html5/image/ImageInfo","moxie/runtime/html5/image/ResizerCanvas","moxie/core/utils/Mime","moxie/core/utils/Env"],function(e,t,i,n,r,o,a,s,u){function c(){function e(){if(!v&&!g)throw new i.ImageError(i.DOMException.INVALID_STATE_ERR);return v||g}function c(){var t=e();return"canvas"==t.nodeName.toLowerCase()?t:(v=document.createElement("canvas"),v.width=t.width,v.height=t.height,v.getContext("2d").drawImage(t,0,0),v)}function l(e){return n.atob(e.substring(e.indexOf("base64,")+7))}function d(e,t){return"data:"+(t||"")+";base64,"+n.btoa(e)}function m(e){var t=this;g=new Image,g.onerror=function(){p.call(this),t.trigger("error",i.ImageError.WRONG_FORMAT)},g.onload=function(){t.trigger("load")},g.src="data:"==e.substr(0,5)?e:d(e,y.type)}function h(e,t){var n,r=this;return window.FileReader?(n=new FileReader,n.onload=function(){t.call(r,this.result)},n.onerror=function(){r.trigger("error",i.ImageError.WRONG_FORMAT)},n.readAsDataURL(e),void 0):t.call(this,e.getAsDataURL())}function f(e,i){var n=Math.PI/180,r=document.createElement("canvas"),o=r.getContext("2d"),a=e.width,s=e.height;switch(t.inArray(i,[5,6,7,8])>-1?(r.width=s,r.height=a):(r.width=a,r.height=s),i){case 2:o.translate(a,0),o.scale(-1,1);break;case 3:o.translate(a,s),o.rotate(180*n);break;case 4:o.translate(0,s),o.scale(1,-1);break;case 5:o.rotate(90*n),o.scale(1,-1);break;case 6:o.rotate(90*n),o.translate(0,-s);break;case 7:o.rotate(90*n),o.translate(a,-s),o.scale(-1,1);break;case 8:o.rotate(-90*n),o.translate(-a,0)}return o.drawImage(e,0,0,a,s),r}function p(){x&&(x.purge(),x=null),w=g=v=y=null,b=!1}var g,x,v,w,y,E=this,b=!1,R=!0;t.extend(this,{loadFromBlob:function(e){var t=this.getRuntime(),n=arguments.length>1?arguments[1]:!0;if(!t.can("access_binary"))throw new i.RuntimeError(i.RuntimeError.NOT_SUPPORTED_ERR);return y=e,e.isDetached()?(w=e.getSource(),m.call(this,w),void 0):(h.call(this,e.getSource(),function(e){n&&(w=l(e)),m.call(this,e)}),void 0)},loadFromImage:function(e,t){this.meta=e.meta,y=new o(null,{name:e.name,size:e.size,type:e.type}),m.call(this,t?w=e.getAsBinaryString():e.getAsDataURL())},getInfo:function(){var t,i=this.getRuntime();return!x&&w&&i.can("access_image_binary")&&(x=new a(w)),t={width:e().width||0,height:e().height||0,type:y.type||u.getFileMime(y.name),size:w&&w.length||y.size||0,name:y.name||"",meta:null},R&&(t.meta=x&&x.meta||this.meta||{},!t.meta||!t.meta.thumb||t.meta.thumb.data instanceof r||(t.meta.thumb.data=new r(null,{type:"image/jpeg",data:t.meta.thumb.data}))),t},resize:function(t,i,n){var r=document.createElement("canvas");if(r.width=t.width,r.height=t.height,r.getContext("2d").drawImage(e(),t.x,t.y,t.width,t.height,0,0,r.width,r.height),v=s.scale(r,i),R=n.preserveHeaders,!R){var o=this.meta&&this.meta.tiff&&this.meta.tiff.Orientation||1;v=f(v,o)}this.width=v.width,this.height=v.height,b=!0,this.trigger("Resize")},getAsCanvas:function(){return v||(v=c()),v.id=this.uid+"_canvas",v},getAsBlob:function(e,t){return e!==this.type?(b=!0,new o(null,{name:y.name||"",type:e,data:E.getAsDataURL(e,t)})):new o(null,{name:y.name||"",type:e,data:E.getAsBinaryString(e,t)})},getAsDataURL:function(e){var t=arguments[1]||90;if(!b)return g.src;if(c(),"image/jpeg"!==e)return v.toDataURL("image/png");try{return v.toDataURL("image/jpeg",t/100)}catch(i){return v.toDataURL("image/jpeg")}},getAsBinaryString:function(e,t){if(!b)return w||(w=l(E.getAsDataURL(e,t))),w;if("image/jpeg"!==e)w=l(E.getAsDataURL(e,t));else{var i;t||(t=90),c();try{i=v.toDataURL("image/jpeg",t/100)}catch(n){i=v.toDataURL("image/jpeg")}w=l(i),x&&(w=x.stripHeaders(w),R&&(x.meta&&x.meta.exif&&x.setExif({PixelXDimension:this.width,PixelYDimension:this.height}),w=x.writeHeaders(w)),x.purge(),x=null)}return b=!1,w},destroy:function(){E=null,p.call(this),this.getRuntime().getShim().removeInstance(this.uid)}})}return e.Image=c}),n("moxie/runtime/flash/Runtime",["moxie/core/utils/Basic","moxie/core/utils/Env","moxie/core/utils/Dom","moxie/core/Exceptions","moxie/runtime/Runtime"],function(e,t,i,n,o){function a(){var e;try{e=navigator.plugins["Shockwave Flash"],e=e.description}catch(t){try{e=new ActiveXObject("ShockwaveFlash.ShockwaveFlash").GetVariable("$version")}catch(i){e="0.0"}}return e=e.match(/\d+/g),parseFloat(e[0]+"."+e[1])}function s(e){var n=i.get(e);n&&"OBJECT"==n.nodeName&&("IE"===t.browser?(n.style.display="none",function r(){4==n.readyState?u(e):setTimeout(r,10)}()):n.parentNode.removeChild(n))}function u(e){var t=i.get(e);if(t){for(var n in t)"function"==typeof t[n]&&(t[n]=null);t.parentNode.removeChild(t)}}function c(u){var c,m=this;u=e.extend({swf_url:t.swf_url},u),o.call(this,u,l,{access_binary:function(e){return e&&"browser"===m.mode},access_image_binary:function(e){return e&&"browser"===m.mode},display_media:o.capTest(r("moxie/image/Image")),do_cors:o.capTrue,drag_and_drop:!1,report_upload_progress:function(){return"client"===m.mode},resize_image:o.capTrue,return_response_headers:!1,return_response_type:function(t){return"json"===t&&window.JSON?!0:!e.arrayDiff(t,["","text","document"])||"browser"===m.mode},return_status_code:function(t){return"browser"===m.mode||!e.arrayDiff(t,[200,404])},select_file:o.capTrue,select_multiple:o.capTrue,send_binary_string:function(e){return e&&"browser"===m.mode},send_browser_cookies:function(e){return e&&"browser"===m.mode},send_custom_headers:function(e){return e&&"browser"===m.mode},send_multipart:o.capTrue,slice_blob:function(e){return e&&"browser"===m.mode},stream_upload:function(e){return e&&"browser"===m.mode},summon_file_dialog:!1,upload_filesize:function(t){return e.parseSizeStr(t)<=2097152||"client"===m.mode},use_http_method:function(t){return!e.arrayDiff(t,["GET","POST"])}},{access_binary:function(e){return e?"browser":"client"},access_image_binary:function(e){return e?"browser":"client"},report_upload_progress:function(e){return e?"browser":"client"},return_response_type:function(t){return e.arrayDiff(t,["","text","json","document"])?"browser":["client","browser"]},return_status_code:function(t){return e.arrayDiff(t,[200,404])?"browser":["client","browser"]},send_binary_string:function(e){return e?"browser":"client"},send_browser_cookies:function(e){return e?"browser":"client"},send_custom_headers:function(e){return e?"browser":"client"},slice_blob:function(e){return e?"browser":"client"},stream_upload:function(e){return e?"client":"browser"},upload_filesize:function(t){return e.parseSizeStr(t)>=2097152?"client":"browser"}},"client"),a()<11.3&&(this.mode=!1),e.extend(this,{getShim:function(){return i.get(this.uid)},shimExec:function(e,t){var i=[].slice.call(arguments,2);return m.getShim().exec(this.uid,e,t,i)},init:function(){var i,r,o;o=this.getShimContainer(),e.extend(o.style,{position:"absolute",top:"-8px",left:"-8px",width:"9px",height:"9px",overflow:"hidden"}),i='<object id="'+this.uid+'" type="application/x-shockwave-flash" data="'+u.swf_url+'" ',"IE"===t.browser&&(i+='classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" '),i+='width="100%" height="100%" style="outline:0"><param name="movie" value="'+u.swf_url+'" />'+'<param name="flashvars" value="uid='+escape(this.uid)+"&target="+t.global_event_dispatcher+'" />'+'<param name="wmode" value="transparent" />'+'<param name="allowscriptaccess" value="always" />'+"</object>","IE"===t.browser?(r=document.createElement("div"),o.appendChild(r),r.outerHTML=i,r=o=null):o.innerHTML=i,c=setTimeout(function(){m&&!m.initialized&&m.trigger("Error",new n.RuntimeError(n.RuntimeError.NOT_INIT_ERR))},5e3)},destroy:function(e){return function(){s(m.uid),e.call(m),clearTimeout(c),u=c=e=m=null}}(this.destroy)},d)}var l="flash",d={};return o.addConstructor(l,c),d}),n("moxie/runtime/flash/file/Blob",["moxie/runtime/flash/Runtime","moxie/file/Blob"],function(e,t){var i={slice:function(e,i,n,r){var o=this.getRuntime();return 0>i?i=Math.max(e.size+i,0):i>0&&(i=Math.min(i,e.size)),0>n?n=Math.max(e.size+n,0):n>0&&(n=Math.min(n,e.size)),e=o.shimExec.call(this,"Blob","slice",i,n,r||""),e&&(e=new t(o.uid,e)),e}};return e.Blob=i}),n("moxie/runtime/flash/file/FileInput",["moxie/runtime/flash/Runtime","moxie/file/File","moxie/core/utils/Basic"],function(e,t,i){var n={init:function(e){var n=this,r=this.getRuntime();this.bind("Change",function(){var e=r.shimExec.call(n,"FileInput","getFiles");n.files=[],i.each(e,function(e){n.files.push(new t(r.uid,e))})},999),this.getRuntime().shimExec.call(this,"FileInput","init",{accept:e.accept,multiple:e.multiple}),this.trigger("ready")}};return e.FileInput=n}),n("moxie/runtime/flash/file/FileReader",["moxie/runtime/flash/Runtime","moxie/core/utils/Encode"],function(e,t){function i(e,i){switch(i){case"readAsText":return t.atob(e,"utf8");case"readAsBinaryString":return t.atob(e);case"readAsDataURL":return e}return null}var n={read:function(e,t){var n=this;return n.result="","readAsDataURL"===e&&(n.result="data:"+(t.type||"")+";base64,"),n.bind("Progress",function(t,r){r&&(n.result+=i(r,e))},999),n.getRuntime().shimExec.call(this,"FileReader","readAsBase64",t.uid)}};return e.FileReader=n}),n("moxie/runtime/flash/file/FileReaderSync",["moxie/runtime/flash/Runtime","moxie/core/utils/Encode"],function(e,t){function i(e,i){switch(i){case"readAsText":return t.atob(e,"utf8");case"readAsBinaryString":return t.atob(e);case"readAsDataURL":return e}return null}var n={read:function(e,t){var n,r=this.getRuntime();return(n=r.shimExec.call(this,"FileReaderSync","readAsBase64",t.uid))?("readAsDataURL"===e&&(n="data:"+(t.type||"")+";base64,"+n),i(n,e,t.type)):null}};return e.FileReaderSync=n}),n("moxie/runtime/flash/runtime/Transporter",["moxie/runtime/flash/Runtime","moxie/file/Blob"],function(e,t){var i={getAsBlob:function(e){var i=this.getRuntime(),n=i.shimExec.call(this,"Transporter","getAsBlob",e);return n?new t(i.uid,n):null}};return e.Transporter=i}),n("moxie/runtime/flash/xhr/XMLHttpRequest",["moxie/runtime/flash/Runtime","moxie/core/utils/Basic","moxie/file/Blob","moxie/file/File","moxie/file/FileReaderSync","moxie/runtime/flash/file/FileReaderSync","moxie/xhr/FormData","moxie/runtime/Transporter","moxie/runtime/flash/runtime/Transporter"],function(e,t,i,n,r,o,a,s){var u={send:function(e,n){function r(){e.transport=l.mode,l.shimExec.call(c,"XMLHttpRequest","send",e,n)}function o(e,t){l.shimExec.call(c,"XMLHttpRequest","appendBlob",e,t.uid),n=null,r()}function u(e,t){var i=new s;i.bind("TransportingComplete",function(){t(this.result)}),i.transport(e.getSource(),e.type,{ruid:l.uid})}var c=this,l=c.getRuntime();if(t.isEmptyObj(e.headers)||t.each(e.headers,function(e,t){l.shimExec.call(c,"XMLHttpRequest","setRequestHeader",t,e.toString())}),n instanceof a){var d;if(n.each(function(e,t){e instanceof i?d=t:l.shimExec.call(c,"XMLHttpRequest","append",t,e)}),n.hasBlob()){var m=n.getBlob();m.isDetached()?u(m,function(e){m.destroy(),o(d,e)}):o(d,m)}else n=null,r()}else n instanceof i?n.isDetached()?u(n,function(e){n.destroy(),n=e.uid,r()}):(n=n.uid,r()):r()},getResponse:function(e){var i,o,a=this.getRuntime();if(o=a.shimExec.call(this,"XMLHttpRequest","getResponseAsBlob")){if(o=new n(a.uid,o),"blob"===e)return o;try{if(i=new r,~t.inArray(e,["","text"]))return i.readAsText(o);if("json"===e&&window.JSON)return JSON.parse(i.readAsText(o))}finally{o.destroy()}}return null},abort:function(){var e=this.getRuntime();e.shimExec.call(this,"XMLHttpRequest","abort"),this.dispatchEvent("readystatechange"),this.dispatchEvent("abort")}};return e.XMLHttpRequest=u}),n("moxie/runtime/flash/image/Image",["moxie/runtime/flash/Runtime","moxie/core/utils/Basic","moxie/runtime/Transporter","moxie/file/Blob","moxie/file/FileReaderSync"],function(e,t,i,n,r){var o={loadFromBlob:function(e){function t(e){r.shimExec.call(n,"Image","loadFromBlob",e.uid),n=r=null}var n=this,r=n.getRuntime();if(e.isDetached()){var o=new i;o.bind("TransportingComplete",function(){t(o.result.getSource())}),o.transport(e.getSource(),e.type,{ruid:r.uid})}else t(e.getSource())},loadFromImage:function(e){var t=this.getRuntime();return t.shimExec.call(this,"Image","loadFromImage",e.uid)},getInfo:function(){var e=this.getRuntime(),t=e.shimExec.call(this,"Image","getInfo");return t.meta&&t.meta.thumb&&t.meta.thumb.data&&!(e.meta.thumb.data instanceof n)&&(t.meta.thumb.data=new n(e.uid,t.meta.thumb.data)),t},getAsBlob:function(e,t){var i=this.getRuntime(),r=i.shimExec.call(this,"Image","getAsBlob",e,t);return r?new n(i.uid,r):null},getAsDataURL:function(){var e,t=this.getRuntime(),i=t.Image.getAsBlob.apply(this,arguments);return i?(e=new r,e.readAsDataURL(i)):null}};return e.Image=o}),n("moxie/runtime/silverlight/Runtime",["moxie/core/utils/Basic","moxie/core/utils/Env","moxie/core/utils/Dom","moxie/core/Exceptions","moxie/runtime/Runtime"],function(e,t,i,n,o){function a(e){var t,i,n,r,o,a=!1,s=null,u=0;try{try{s=new ActiveXObject("AgControl.AgControl"),s.IsVersionSupported(e)&&(a=!0),s=null}catch(c){var l=navigator.plugins["Silverlight Plug-In"];if(l){for(t=l.description,"1.0.30226.2"===t&&(t="2.0.30226.2"),i=t.split(".");i.length>3;)i.pop();for(;i.length<4;)i.push(0);for(n=e.split(".");n.length>4;)n.pop();do r=parseInt(n[u],10),o=parseInt(i[u],10),u++;while(u<n.length&&r===o);o>=r&&!isNaN(r)&&(a=!0)}}}catch(d){a=!1}return a}function s(s){var l,d=this;s=e.extend({xap_url:t.xap_url},s),o.call(this,s,u,{access_binary:o.capTrue,access_image_binary:o.capTrue,display_media:o.capTest(r("moxie/image/Image")),do_cors:o.capTrue,drag_and_drop:!1,report_upload_progress:o.capTrue,resize_image:o.capTrue,return_response_headers:function(e){return e&&"client"===d.mode},return_response_type:function(e){return"json"!==e?!0:!!window.JSON},return_status_code:function(t){return"client"===d.mode||!e.arrayDiff(t,[200,404])},select_file:o.capTrue,select_multiple:o.capTrue,send_binary_string:o.capTrue,send_browser_cookies:function(e){return e&&"browser"===d.mode},send_custom_headers:function(e){return e&&"client"===d.mode},send_multipart:o.capTrue,slice_blob:o.capTrue,stream_upload:!0,summon_file_dialog:!1,upload_filesize:o.capTrue,use_http_method:function(t){return"client"===d.mode||!e.arrayDiff(t,["GET","POST"])}},{return_response_headers:function(e){return e?"client":"browser"},return_status_code:function(t){return e.arrayDiff(t,[200,404])?"client":["client","browser"]},send_browser_cookies:function(e){return e?"browser":"client"},send_custom_headers:function(e){return e?"client":"browser"},use_http_method:function(t){return e.arrayDiff(t,["GET","POST"])?"client":["client","browser"]}}),a("2.0.31005.0")&&"Opera"!==t.browser||(this.mode=!1),e.extend(this,{getShim:function(){return i.get(this.uid).content.Moxie},shimExec:function(e,t){var i=[].slice.call(arguments,2);return d.getShim().exec(this.uid,e,t,i)},init:function(){var e;e=this.getShimContainer(),e.innerHTML='<object id="'+this.uid+'" data="data:application/x-silverlight," type="application/x-silverlight-2" width="100%" height="100%" style="outline:none;">'+'<param name="source" value="'+s.xap_url+'"/>'+'<param name="background" value="Transparent"/>'+'<param name="windowless" value="true"/>'+'<param name="enablehtmlaccess" value="true"/>'+'<param name="initParams" value="uid='+this.uid+",target="+t.global_event_dispatcher+'"/>'+"</object>",l=setTimeout(function(){d&&!d.initialized&&d.trigger("Error",new n.RuntimeError(n.RuntimeError.NOT_INIT_ERR))},"Windows"!==t.OS?1e4:5e3)},destroy:function(e){return function(){e.call(d),clearTimeout(l),s=l=e=d=null}}(this.destroy)},c)}var u="silverlight",c={};return o.addConstructor(u,s),c}),n("moxie/runtime/silverlight/file/Blob",["moxie/runtime/silverlight/Runtime","moxie/core/utils/Basic","moxie/runtime/flash/file/Blob"],function(e,t,i){return e.Blob=t.extend({},i)}),n("moxie/runtime/silverlight/file/FileInput",["moxie/runtime/silverlight/Runtime","moxie/file/File","moxie/core/utils/Basic"],function(e,t,i){function n(e){for(var t="",i=0;i<e.length;i++)t+=(""!==t?"|":"")+e[i].title+" | *."+e[i].extensions.replace(/,/g,";*.");return t}var r={init:function(e){var r=this,o=this.getRuntime();this.bind("Change",function(){var e=o.shimExec.call(r,"FileInput","getFiles");r.files=[],i.each(e,function(e){r.files.push(new t(o.uid,e))})},999),o.shimExec.call(this,"FileInput","init",n(e.accept),e.multiple),this.trigger("ready")},setOption:function(e,t){"accept"==e&&(t=n(t)),this.getRuntime().shimExec.call(this,"FileInput","setOption",e,t)}};return e.FileInput=r}),n("moxie/runtime/silverlight/file/FileDrop",["moxie/runtime/silverlight/Runtime","moxie/core/utils/Dom","moxie/core/utils/Events"],function(e,t,i){var n={init:function(){var e,n=this,r=n.getRuntime();return e=r.getShimContainer(),i.addEvent(e,"dragover",function(e){e.preventDefault(),e.stopPropagation(),e.dataTransfer.dropEffect="copy"},n.uid),i.addEvent(e,"dragenter",function(e){e.preventDefault();var i=t.get(r.uid).dragEnter(e);i&&e.stopPropagation()},n.uid),i.addEvent(e,"drop",function(e){e.preventDefault();var i=t.get(r.uid).dragDrop(e);i&&e.stopPropagation()},n.uid),r.shimExec.call(this,"FileDrop","init")}};return e.FileDrop=n}),n("moxie/runtime/silverlight/file/FileReader",["moxie/runtime/silverlight/Runtime","moxie/core/utils/Basic","moxie/runtime/flash/file/FileReader"],function(e,t,i){return e.FileReader=t.extend({},i)
}),n("moxie/runtime/silverlight/file/FileReaderSync",["moxie/runtime/silverlight/Runtime","moxie/core/utils/Basic","moxie/runtime/flash/file/FileReaderSync"],function(e,t,i){return e.FileReaderSync=t.extend({},i)}),n("moxie/runtime/silverlight/runtime/Transporter",["moxie/runtime/silverlight/Runtime","moxie/core/utils/Basic","moxie/runtime/flash/runtime/Transporter"],function(e,t,i){return e.Transporter=t.extend({},i)}),n("moxie/runtime/silverlight/xhr/XMLHttpRequest",["moxie/runtime/silverlight/Runtime","moxie/core/utils/Basic","moxie/runtime/flash/xhr/XMLHttpRequest","moxie/runtime/silverlight/file/FileReaderSync","moxie/runtime/silverlight/runtime/Transporter"],function(e,t,i){return e.XMLHttpRequest=t.extend({},i)}),n("moxie/runtime/silverlight/image/Image",["moxie/runtime/silverlight/Runtime","moxie/core/utils/Basic","moxie/file/Blob","moxie/runtime/flash/image/Image"],function(e,t,i,n){return e.Image=t.extend({},n,{getInfo:function(){var e=this.getRuntime(),n=["tiff","exif","gps","thumb"],r={meta:{}},o=e.shimExec.call(this,"Image","getInfo");return o.meta&&(t.each(n,function(e){var t,i,n,a,s=o.meta[e];if(s&&s.keys)for(r.meta[e]={},i=0,n=s.keys.length;n>i;i++)t=s.keys[i],a=s[t],a&&(/^(\d|[1-9]\d+)$/.test(a)?a=parseInt(a,10):/^\d*\.\d+$/.test(a)&&(a=parseFloat(a)),r.meta[e][t]=a)}),r.meta&&r.meta.thumb&&r.meta.thumb.data&&!(e.meta.thumb.data instanceof i)&&(r.meta.thumb.data=new i(e.uid,r.meta.thumb.data))),r.width=parseInt(o.width,10),r.height=parseInt(o.height,10),r.size=parseInt(o.size,10),r.type=o.type,r.name=o.name,r},resize:function(e,t,i){this.getRuntime().shimExec.call(this,"Image","resize",e.x,e.y,e.width,e.height,t,i.preserveHeaders,i.resample)}})}),n("moxie/runtime/html4/Runtime",["moxie/core/utils/Basic","moxie/core/Exceptions","moxie/runtime/Runtime","moxie/core/utils/Env"],function(e,t,i,n){function o(t){var o=this,u=i.capTest,c=i.capTrue;i.call(this,t,a,{access_binary:u(window.FileReader||window.File&&File.getAsDataURL),access_image_binary:!1,display_media:u((n.can("create_canvas")||n.can("use_data_uri_over32kb"))&&r("moxie/image/Image")),do_cors:!1,drag_and_drop:!1,filter_by_extension:u(function(){return!("Chrome"===n.browser&&n.verComp(n.version,28,"<")||"IE"===n.browser&&n.verComp(n.version,10,"<")||"Safari"===n.browser&&n.verComp(n.version,7,"<")||"Firefox"===n.browser&&n.verComp(n.version,37,"<"))}()),resize_image:function(){return s.Image&&o.can("access_binary")&&n.can("create_canvas")},report_upload_progress:!1,return_response_headers:!1,return_response_type:function(t){return"json"===t&&window.JSON?!0:!!~e.inArray(t,["text","document",""])},return_status_code:function(t){return!e.arrayDiff(t,[200,404])},select_file:function(){return n.can("use_fileinput")},select_multiple:!1,send_binary_string:!1,send_custom_headers:!1,send_multipart:!0,slice_blob:!1,stream_upload:function(){return o.can("select_file")},summon_file_dialog:function(){return o.can("select_file")&&("Firefox"===n.browser&&n.verComp(n.version,4,">=")||"Opera"===n.browser&&n.verComp(n.version,12,">=")||"IE"===n.browser&&n.verComp(n.version,10,">=")||!!~e.inArray(n.browser,["Chrome","Safari"]))},upload_filesize:c,use_http_method:function(t){return!e.arrayDiff(t,["GET","POST"])}}),e.extend(this,{init:function(){this.trigger("Init")},destroy:function(e){return function(){e.call(o),e=o=null}}(this.destroy)}),e.extend(this.getShim(),s)}var a="html4",s={};return i.addConstructor(a,o),s}),n("moxie/runtime/html4/file/FileInput",["moxie/runtime/html4/Runtime","moxie/file/File","moxie/core/utils/Basic","moxie/core/utils/Dom","moxie/core/utils/Events","moxie/core/utils/Mime","moxie/core/utils/Env"],function(e,t,i,n,r,o,a){function s(){function e(){var o,c,d,m,h,f,p=this,g=p.getRuntime();f=i.guid("uid_"),o=g.getShimContainer(),s&&(d=n.get(s+"_form"),d&&i.extend(d.style,{top:"100%"})),m=document.createElement("form"),m.setAttribute("id",f+"_form"),m.setAttribute("method","post"),m.setAttribute("enctype","multipart/form-data"),m.setAttribute("encoding","multipart/form-data"),i.extend(m.style,{overflow:"hidden",position:"absolute",top:0,left:0,width:"100%",height:"100%"}),h=document.createElement("input"),h.setAttribute("id",f),h.setAttribute("type","file"),h.setAttribute("accept",l.join(",")),i.extend(h.style,{fontSize:"999px",opacity:0}),m.appendChild(h),o.appendChild(m),i.extend(h.style,{position:"absolute",top:0,left:0,width:"100%",height:"100%"}),"IE"===a.browser&&a.verComp(a.version,10,"<")&&i.extend(h.style,{filter:"progid:DXImageTransform.Microsoft.Alpha(opacity=0)"}),h.onchange=function(){var i;if(this.value){if(this.files){if(i=this.files[0],0===i.size)return m.parentNode.removeChild(m),void 0}else i={name:this.value};i=new t(g.uid,i),this.onchange=function(){},e.call(p),p.files=[i],h.setAttribute("id",i.uid),m.setAttribute("id",i.uid+"_form"),p.trigger("change"),h=m=null}},g.can("summon_file_dialog")&&(c=n.get(u.browse_button),r.removeEvent(c,"click",p.uid),r.addEvent(c,"click",function(e){h&&!h.disabled&&h.click(),e.preventDefault()},p.uid)),s=f,o=d=c=null}var s,u,c,l=[];i.extend(this,{init:function(t){var i,a=this,s=a.getRuntime();u=t,l=t.accept.mimes||o.extList2mimes(t.accept,s.can("filter_by_extension")),i=s.getShimContainer(),function(){var e,o,l;e=n.get(t.browse_button),c=n.getStyle(e,"z-index")||"auto",s.can("summon_file_dialog")&&("static"===n.getStyle(e,"position")&&(e.style.position="relative"),a.bind("Refresh",function(){o=parseInt(c,10)||1,n.get(u.browse_button).style.zIndex=o,this.getRuntime().getShimContainer().style.zIndex=o-1})),l=s.can("summon_file_dialog")?e:i,r.addEvent(l,"mouseover",function(){a.trigger("mouseenter")},a.uid),r.addEvent(l,"mouseout",function(){a.trigger("mouseleave")},a.uid),r.addEvent(l,"mousedown",function(){a.trigger("mousedown")},a.uid),r.addEvent(n.get(t.container),"mouseup",function(){a.trigger("mouseup")},a.uid),e=null}(),e.call(this),i=null,a.trigger({type:"ready",async:!0})},setOption:function(e,t){var i,r=this.getRuntime();"accept"==e&&(l=t.mimes||o.extList2mimes(t,r.can("filter_by_extension"))),i=n.get(s),i&&i.setAttribute("accept",l.join(","))},disable:function(e){var t;(t=n.get(s))&&(t.disabled=!!e)},destroy:function(){var e=this.getRuntime(),t=e.getShim(),i=e.getShimContainer(),o=u&&n.get(u.container),a=u&&n.get(u.browse_button);o&&r.removeAllEvents(o,this.uid),a&&(r.removeAllEvents(a,this.uid),a.style.zIndex=c),i&&(r.removeAllEvents(i,this.uid),i.innerHTML=""),t.removeInstance(this.uid),s=l=u=i=o=a=t=null}})}return e.FileInput=s}),n("moxie/runtime/html4/file/FileReader",["moxie/runtime/html4/Runtime","moxie/runtime/html5/file/FileReader"],function(e,t){return e.FileReader=t}),n("moxie/runtime/html4/xhr/XMLHttpRequest",["moxie/runtime/html4/Runtime","moxie/core/utils/Basic","moxie/core/utils/Dom","moxie/core/utils/Url","moxie/core/Exceptions","moxie/core/utils/Events","moxie/file/Blob","moxie/xhr/FormData"],function(e,t,i,n,r,o,a,s){function u(){function e(e){var t,n,r,a,s=this,u=!1;if(l){if(t=l.id.replace(/_iframe$/,""),n=i.get(t+"_form")){for(r=n.getElementsByTagName("input"),a=r.length;a--;)switch(r[a].getAttribute("type")){case"hidden":r[a].parentNode.removeChild(r[a]);break;case"file":u=!0}r=[],u||n.parentNode.removeChild(n),n=null}setTimeout(function(){o.removeEvent(l,"load",s.uid),l.parentNode&&l.parentNode.removeChild(l);var t=s.getRuntime().getShimContainer();t.children.length||t.parentNode.removeChild(t),t=l=null,e()},1)}}var u,c,l;t.extend(this,{send:function(d,m){function h(){var i=w.getShimContainer()||document.body,r=document.createElement("div");r.innerHTML='<iframe id="'+f+'_iframe" name="'+f+'_iframe" src="javascript:&quot;&quot;" style="display:none"></iframe>',l=r.firstChild,i.appendChild(l),o.addEvent(l,"load",function(){var i;try{i=l.contentWindow.document||l.contentDocument||window.frames[l.id].document,/^4(0[0-9]|1[0-7]|2[2346])\s/.test(i.title)?u=i.title.replace(/^(\d+).*$/,"$1"):(u=200,c=t.trim(i.body.innerHTML),v.trigger({type:"progress",loaded:c.length,total:c.length}),x&&v.trigger({type:"uploadprogress",loaded:x.size||1025,total:x.size||1025}))}catch(r){if(!n.hasSameOrigin(d.url))return e.call(v,function(){v.trigger("error")}),void 0;u=404}e.call(v,function(){v.trigger("load")})},v.uid)}var f,p,g,x,v=this,w=v.getRuntime();if(u=c=null,m instanceof s&&m.hasBlob()){if(x=m.getBlob(),f=x.uid,g=i.get(f),p=i.get(f+"_form"),!p)throw new r.DOMException(r.DOMException.NOT_FOUND_ERR)}else f=t.guid("uid_"),p=document.createElement("form"),p.setAttribute("id",f+"_form"),p.setAttribute("method",d.method),p.setAttribute("enctype","multipart/form-data"),p.setAttribute("encoding","multipart/form-data"),w.getShimContainer().appendChild(p);p.setAttribute("target",f+"_iframe"),m instanceof s&&m.each(function(e,i){if(e instanceof a)g&&g.setAttribute("name",i);else{var n=document.createElement("input");t.extend(n,{type:"hidden",name:i,value:e}),g?p.insertBefore(n,g):p.appendChild(n)}}),p.setAttribute("action",d.url),h(),p.submit(),v.trigger("loadstart")},getStatus:function(){return u},getResponse:function(e){if("json"===e&&"string"===t.typeOf(c)&&window.JSON)try{return JSON.parse(c.replace(/^\s*<pre[^>]*>/,"").replace(/<\/pre>\s*$/,""))}catch(i){return null}return c},abort:function(){var t=this;l&&l.contentWindow&&(l.contentWindow.stop?l.contentWindow.stop():l.contentWindow.document.execCommand?l.contentWindow.document.execCommand("Stop"):l.src="about:blank"),e.call(this,function(){t.dispatchEvent("abort")})}})}return e.XMLHttpRequest=u}),n("moxie/runtime/html4/image/Image",["moxie/runtime/html4/Runtime","moxie/runtime/html5/image/Image"],function(e,t){return e.Image=t}),a(["moxie/core/utils/Basic","moxie/core/utils/Encode","moxie/core/utils/Env","moxie/core/Exceptions","moxie/core/utils/Dom","moxie/core/EventTarget","moxie/runtime/Runtime","moxie/runtime/RuntimeClient","moxie/file/Blob","moxie/core/I18n","moxie/core/utils/Mime","moxie/file/FileInput","moxie/file/File","moxie/file/FileDrop","moxie/file/FileReader","moxie/core/utils/Url","moxie/runtime/RuntimeTarget","moxie/xhr/FormData","moxie/xhr/XMLHttpRequest","moxie/runtime/Transporter","moxie/image/Image","moxie/core/utils/Events","moxie/runtime/html5/image/ResizerCanvas"])}(this)});
define("../libs/plupload/js/moxie.min", function(){});

/**
 * Plupload - multi-runtime File Uploader
 * v2.3.1
 *
 * Copyright 2013, Moxiecode Systems AB
 * Released under GPL License.
 *
 * License: http://www.plupload.com/license
 * Contributing: http://www.plupload.com/contributing
 *
 * Date: 2017-02-06
 */
!function(e,t){var i=function(){var e={};return t.apply(e,arguments),e.plupload};"function"==typeof define&&define.amd?define("plupload",["./moxie"],i):"object"==typeof module&&module.exports?module.exports=i(require("./moxie")):e.plupload=i(e.moxie)}(this||window,function(e){!function(e,t,i){function n(e){function t(e,t,i){var r={chunks:"slice_blob",jpgresize:"send_binary_string",pngresize:"send_binary_string",progress:"report_upload_progress",multi_selection:"select_multiple",dragdrop:"drag_and_drop",drop_element:"drag_and_drop",headers:"send_custom_headers",urlstream_upload:"send_binary_string",canSendBinary:"send_binary",triggerDialog:"summon_file_dialog"};r[e]?n[r[e]]=t:i||(n[e]=t)}var i=e.required_features,n={};return"string"==typeof i?l.each(i.split(/\s*,\s*/),function(e){t(e,!0)}):"object"==typeof i?l.each(i,function(e,i){t(i,e)}):i===!0&&(e.chunk_size&&e.chunk_size>0&&(n.slice_blob=!0),l.isEmptyObj(e.resize)&&e.multipart!==!1||(n.send_binary_string=!0),e.http_method&&(n.use_http_method=e.http_method),l.each(e,function(e,i){t(i,!!e,!0)})),n}var r=window.setTimeout,s={},a=t.core.utils,o=t.runtime.Runtime,l={VERSION:"2.3.1",STOPPED:1,STARTED:2,QUEUED:1,UPLOADING:2,FAILED:4,DONE:5,GENERIC_ERROR:-100,HTTP_ERROR:-200,IO_ERROR:-300,SECURITY_ERROR:-400,INIT_ERROR:-500,FILE_SIZE_ERROR:-600,FILE_EXTENSION_ERROR:-601,FILE_DUPLICATE_ERROR:-602,IMAGE_FORMAT_ERROR:-700,MEMORY_ERROR:-701,IMAGE_DIMENSIONS_ERROR:-702,mimeTypes:a.Mime.mimes,ua:a.Env,typeOf:a.Basic.typeOf,extend:a.Basic.extend,guid:a.Basic.guid,getAll:function(e){var t,i=[];"array"!==l.typeOf(e)&&(e=[e]);for(var n=e.length;n--;)t=l.get(e[n]),t&&i.push(t);return i.length?i:null},get:a.Dom.get,each:a.Basic.each,getPos:a.Dom.getPos,getSize:a.Dom.getSize,xmlEncode:function(e){var t={"<":"lt",">":"gt","&":"amp",'"':"quot","'":"#39"},i=/[<>&\"\']/g;return e?(""+e).replace(i,function(e){return t[e]?"&"+t[e]+";":e}):e},toArray:a.Basic.toArray,inArray:a.Basic.inArray,inSeries:a.Basic.inSeries,addI18n:t.core.I18n.addI18n,translate:t.core.I18n.translate,sprintf:a.Basic.sprintf,isEmptyObj:a.Basic.isEmptyObj,hasClass:a.Dom.hasClass,addClass:a.Dom.addClass,removeClass:a.Dom.removeClass,getStyle:a.Dom.getStyle,addEvent:a.Events.addEvent,removeEvent:a.Events.removeEvent,removeAllEvents:a.Events.removeAllEvents,cleanName:function(e){var t,i;for(i=[/[\300-\306]/g,"A",/[\340-\346]/g,"a",/\307/g,"C",/\347/g,"c",/[\310-\313]/g,"E",/[\350-\353]/g,"e",/[\314-\317]/g,"I",/[\354-\357]/g,"i",/\321/g,"N",/\361/g,"n",/[\322-\330]/g,"O",/[\362-\370]/g,"o",/[\331-\334]/g,"U",/[\371-\374]/g,"u"],t=0;t<i.length;t+=2)e=e.replace(i[t],i[t+1]);return e=e.replace(/\s+/g,"_"),e=e.replace(/[^a-z0-9_\-\.]+/gi,"")},buildUrl:function(e,t){var i="";return l.each(t,function(e,t){i+=(i?"&":"")+encodeURIComponent(t)+"="+encodeURIComponent(e)}),i&&(e+=(e.indexOf("?")>0?"&":"?")+i),e},formatSize:function(e){function t(e,t){return Math.round(e*Math.pow(10,t))/Math.pow(10,t)}if(e===i||/\D/.test(e))return l.translate("N/A");var n=Math.pow(1024,4);return e>n?t(e/n,1)+" "+l.translate("tb"):e>(n/=1024)?t(e/n,1)+" "+l.translate("gb"):e>(n/=1024)?t(e/n,1)+" "+l.translate("mb"):e>1024?Math.round(e/1024)+" "+l.translate("kb"):e+" "+l.translate("b")},parseSize:a.Basic.parseSizeStr,predictRuntime:function(e,t){var i,n;return i=new l.Uploader(e),n=o.thatCan(i.getOption().required_features,t||e.runtimes),i.destroy(),n},addFileFilter:function(e,t){s[e]=t}};l.addFileFilter("mime_types",function(e,t,i){e.length&&!e.regexp.test(t.name)?(this.trigger("Error",{code:l.FILE_EXTENSION_ERROR,message:l.translate("File extension error."),file:t}),i(!1)):i(!0)}),l.addFileFilter("max_file_size",function(e,t,i){var n;e=l.parseSize(e),t.size!==n&&e&&t.size>e?(this.trigger("Error",{code:l.FILE_SIZE_ERROR,message:l.translate("File size error."),file:t}),i(!1)):i(!0)}),l.addFileFilter("prevent_duplicates",function(e,t,i){if(e)for(var n=this.files.length;n--;)if(t.name===this.files[n].name&&t.size===this.files[n].size)return this.trigger("Error",{code:l.FILE_DUPLICATE_ERROR,message:l.translate("Duplicate file error."),file:t}),i(!1),void 0;i(!0)}),l.Uploader=function(e){function a(){var e,t,i=0;if(this.state==l.STARTED){for(t=0;t<x.length;t++)e||x[t].status!=l.QUEUED?i++:(e=x[t],this.trigger("BeforeUpload",e)&&(e.status=l.UPLOADING,this.trigger("UploadFile",e)));i==x.length&&(this.state!==l.STOPPED&&(this.state=l.STOPPED,this.trigger("StateChanged")),this.trigger("UploadComplete",x))}}function u(e){e.percent=e.size>0?Math.ceil(100*(e.loaded/e.size)):100,d()}function d(){var e,t,n,r=0;for(w.reset(),e=0;e<x.length;e++)t=x[e],t.size!==i?(w.size+=t.origSize,n=t.loaded*t.origSize/t.size,(!t.completeTimestamp||t.completeTimestamp>I)&&(r+=n),w.loaded+=n):w.size=i,t.status==l.DONE?w.uploaded++:t.status==l.FAILED?w.failed++:w.queued++;w.size===i?w.percent=x.length>0?Math.ceil(100*(w.uploaded/x.length)):0:(w.bytesPerSec=Math.ceil(r/((+new Date-I||1)/1e3)),w.percent=w.size>0?Math.ceil(100*(w.loaded/w.size)):0)}function c(){var e=U[0]||F[0];return e?e.getRuntime().uid:!1}function f(e,t){if(e.ruid){var i=o.getInfo(e.ruid);if(i)return i.can(t)}return!1}function p(){this.bind("FilesAdded FilesRemoved",function(e){e.trigger("QueueChanged"),e.refresh()}),this.bind("CancelUpload",y),this.bind("BeforeUpload",_),this.bind("UploadFile",E),this.bind("UploadProgress",v),this.bind("StateChanged",b),this.bind("QueueChanged",d),this.bind("Error",z),this.bind("FileUploaded",R),this.bind("Destroy",O)}function g(e,i){var n=this,r=0,s=[],a={runtime_order:e.runtimes,required_caps:e.required_features,preferred_caps:P,swf_url:e.flash_swf_url,xap_url:e.silverlight_xap_url};l.each(e.runtimes.split(/\s*,\s*/),function(t){e[t]&&(a[t]=e[t])}),e.browse_button&&l.each(e.browse_button,function(i){s.push(function(s){var u=new t.file.FileInput(l.extend({},a,{accept:e.filters.mime_types,name:e.file_data_name,multiple:e.multi_selection,container:e.container,browse_button:i}));u.onready=function(){var e=o.getInfo(this.ruid);l.extend(n.features,{chunks:e.can("slice_blob"),multipart:e.can("send_multipart"),multi_selection:e.can("select_multiple")}),r++,U.push(this),s()},u.onchange=function(){n.addFile(this.files)},u.bind("mouseenter mouseleave mousedown mouseup",function(t){A||(e.browse_button_hover&&("mouseenter"===t.type?l.addClass(i,e.browse_button_hover):"mouseleave"===t.type&&l.removeClass(i,e.browse_button_hover)),e.browse_button_active&&("mousedown"===t.type?l.addClass(i,e.browse_button_active):"mouseup"===t.type&&l.removeClass(i,e.browse_button_active)))}),u.bind("mousedown",function(){n.trigger("Browse")}),u.bind("error runtimeerror",function(){u=null,s()}),u.init()})}),e.drop_element&&l.each(e.drop_element,function(e){s.push(function(i){var s=new t.file.FileDrop(l.extend({},a,{drop_zone:e}));s.onready=function(){var e=o.getInfo(this.ruid);l.extend(n.features,{chunks:e.can("slice_blob"),multipart:e.can("send_multipart"),dragdrop:e.can("drag_and_drop")}),r++,F.push(this),i()},s.ondrop=function(){n.addFile(this.files)},s.bind("error runtimeerror",function(){s=null,i()}),s.init()})}),l.inSeries(s,function(){"function"==typeof i&&i(r)})}function h(e,n,r){var s=new t.image.Image;try{s.onload=function(){return n.width>this.width&&n.height>this.height&&n.quality===i&&n.preserve_headers&&!n.crop?(this.destroy(),r(e)):(s.downsize(n.width,n.height,n.crop,n.preserve_headers),void 0)},s.onresize=function(){r(this.getAsBlob(e.type,n.quality)),this.destroy()},s.onerror=function(){r(e)},s.load(e)}catch(a){r(e)}}function m(e,i,r){function s(e,i,n){var r=S[e];switch(e){case"max_file_size":"max_file_size"===e&&(S.max_file_size=S.filters.max_file_size=i);break;case"chunk_size":(i=l.parseSize(i))&&(S[e]=i,S.send_file_name=!0);break;case"multipart":S[e]=i,i||(S.send_file_name=!0);break;case"http_method":S[e]="PUT"===i.toUpperCase()?"PUT":"POST";break;case"unique_names":S[e]=i,i&&(S.send_file_name=!0);break;case"filters":"array"===l.typeOf(i)&&(i={mime_types:i}),n?l.extend(S.filters,i):S.filters=i,i.mime_types&&("string"===l.typeOf(i.mime_types)&&(i.mime_types=t.core.utils.Mime.mimes2extList(i.mime_types)),i.mime_types.regexp=function(e){var t=[];return l.each(e,function(e){l.each(e.extensions.split(/,/),function(e){/^\s*\*\s*$/.test(e)?t.push("\\.*"):t.push("\\."+e.replace(new RegExp("["+"/^$.*+?|()[]{}\\".replace(/./g,"\\$&")+"]","g"),"\\$&"))})}),new RegExp("("+t.join("|")+")$","i")}(i.mime_types),S.filters.mime_types=i.mime_types);break;case"resize":S.resize=i?l.extend({preserve_headers:!0,crop:!1},i):!1;break;case"prevent_duplicates":S.prevent_duplicates=S.filters.prevent_duplicates=!!i;break;case"container":case"browse_button":case"drop_element":i="container"===e?l.get(i):l.getAll(i);case"runtimes":case"multi_selection":case"flash_swf_url":case"silverlight_xap_url":S[e]=i,n||(u=!0);break;default:S[e]=i}n||a.trigger("OptionChanged",e,i,r)}var a=this,u=!1;"object"==typeof e?l.each(e,function(e,t){s(t,e,r)}):s(e,i,r),r?(S.required_features=n(l.extend({},S)),P=n(l.extend({},S,{required_features:!0}))):u&&(a.trigger("Destroy"),g.call(a,S,function(e){e?(a.runtime=o.getInfo(c()).type,a.trigger("Init",{runtime:a.runtime}),a.trigger("PostInit")):a.trigger("Error",{code:l.INIT_ERROR,message:l.translate("Init error.")})}))}function _(e,t){if(e.settings.unique_names){var i=t.name.match(/\.([^.]+)$/),n="part";i&&(n=i[1]),t.target_name=t.id+"."+n}}function E(e,i){function n(){c-->0?r(s,1e3):(i.loaded=g,e.trigger("Error",{code:l.HTTP_ERROR,message:l.translate("HTTP Error."),file:i,response:T.responseText,status:T.status,responseHeaders:T.getAllResponseHeaders()}))}function s(){var t,n,r={};i.status===l.UPLOADING&&e.state!==l.STOPPED&&(e.settings.send_file_name&&(r.name=i.target_name||i.name),d&&p.chunks&&o.size>d?(n=Math.min(d,o.size-g),t=o.slice(g,g+n)):(n=o.size,t=o),d&&p.chunks&&(e.settings.send_chunk_number?(r.chunk=Math.ceil(g/d),r.chunks=Math.ceil(o.size/d)):(r.offset=g,r.total=o.size)),e.trigger("BeforeChunkUpload",i,r,t,g)&&a(r,t,n))}function a(a,d,f){var h;T=new t.xhr.XMLHttpRequest,T.upload&&(T.upload.onprogress=function(t){i.loaded=Math.min(i.size,g+t.loaded),e.trigger("UploadProgress",i)}),T.onload=function(){return T.status>=400?(n(),void 0):(c=e.settings.max_retries,f<o.size?(d.destroy(),g+=f,i.loaded=Math.min(g,o.size),e.trigger("ChunkUploaded",i,{offset:i.loaded,total:o.size,response:T.responseText,status:T.status,responseHeaders:T.getAllResponseHeaders()}),"Android Browser"===l.ua.browser&&e.trigger("UploadProgress",i)):i.loaded=i.size,d=h=null,!g||g>=o.size?(i.size!=i.origSize&&(o.destroy(),o=null),e.trigger("UploadProgress",i),i.status=l.DONE,i.completeTimestamp=+new Date,e.trigger("FileUploaded",i,{response:T.responseText,status:T.status,responseHeaders:T.getAllResponseHeaders()})):r(s,1),void 0)},T.onerror=function(){n()},T.onloadend=function(){this.destroy(),T=null},e.settings.multipart&&p.multipart?(T.open(e.settings.http_method,u,!0),l.each(e.settings.headers,function(e,t){T.setRequestHeader(t,e)}),h=new t.xhr.FormData,l.each(l.extend(a,e.settings.multipart_params),function(e,t){h.append(t,e)}),h.append(e.settings.file_data_name,d),T.send(h,{runtime_order:e.settings.runtimes,required_caps:e.settings.required_features,preferred_caps:P,swf_url:e.settings.flash_swf_url,xap_url:e.settings.silverlight_xap_url})):(u=l.buildUrl(e.settings.url,l.extend(a,e.settings.multipart_params)),T.open(e.settings.http_method,u,!0),l.each(e.settings.headers,function(e,t){T.setRequestHeader(t,e)}),T.hasRequestHeader("Content-Type")||T.setRequestHeader("Content-Type","application/octet-stream"),T.send(d,{runtime_order:e.settings.runtimes,required_caps:e.settings.required_features,preferred_caps:P,swf_url:e.settings.flash_swf_url,xap_url:e.settings.silverlight_xap_url}))}var o,u=e.settings.url,d=e.settings.chunk_size,c=e.settings.max_retries,p=e.features,g=0;i.loaded&&(g=i.loaded=d?d*Math.floor(i.loaded/d):0),o=i.getSource(),!l.isEmptyObj(e.settings.resize)&&f(o,"send_binary_string")&&-1!==l.inArray(o.type,["image/jpeg","image/png"])?h.call(this,o,e.settings.resize,function(e){o=e,i.size=e.size,s()}):s()}function v(e,t){u(t)}function b(e){if(e.state==l.STARTED)I=+new Date;else if(e.state==l.STOPPED)for(var t=e.files.length-1;t>=0;t--)e.files[t].status==l.UPLOADING&&(e.files[t].status=l.QUEUED,d())}function y(){T&&T.abort()}function R(e){d(),r(function(){a.call(e)},1)}function z(e,t){t.code===l.INIT_ERROR?e.destroy():t.code===l.HTTP_ERROR&&(t.file.status=l.FAILED,t.file.completeTimestamp=+new Date,u(t.file),e.state==l.STARTED&&(e.trigger("CancelUpload"),r(function(){a.call(e)},1)))}function O(e){e.stop(),l.each(x,function(e){e.destroy()}),x=[],U.length&&(l.each(U,function(e){e.destroy()}),U=[]),F.length&&(l.each(F,function(e){e.destroy()}),F=[]),P={},A=!1,I=T=null,w.reset()}var S,I,w,T,D=l.guid(),x=[],P={},U=[],F=[],A=!1;S={chunk_size:0,file_data_name:"file",filters:{mime_types:[],prevent_duplicates:!1,max_file_size:0},flash_swf_url:"js/Moxie.swf",http_method:"POST",max_retries:0,multipart:!0,multi_selection:!0,resize:!1,runtimes:o.order,send_file_name:!0,send_chunk_number:!0,silverlight_xap_url:"js/Moxie.xap"},m.call(this,e,null,!0),w=new l.QueueProgress,l.extend(this,{id:D,uid:D,state:l.STOPPED,features:{},runtime:null,files:x,settings:S,total:w,init:function(){var e,t,i=this;return e=i.getOption("preinit"),"function"==typeof e?e(i):l.each(e,function(e,t){i.bind(t,e)}),p.call(i),l.each(["container","browse_button","drop_element"],function(e){return null===i.getOption(e)?(t={code:l.INIT_ERROR,message:l.sprintf(l.translate("%s specified, but cannot be found."),e)},!1):void 0}),t?i.trigger("Error",t):S.browse_button||S.drop_element?(g.call(i,S,function(e){var t=i.getOption("init");"function"==typeof t?t(i):l.each(t,function(e,t){i.bind(t,e)}),e?(i.runtime=o.getInfo(c()).type,i.trigger("Init",{runtime:i.runtime}),i.trigger("PostInit")):i.trigger("Error",{code:l.INIT_ERROR,message:l.translate("Init error.")})}),void 0):i.trigger("Error",{code:l.INIT_ERROR,message:l.translate("You must specify either browse_button or drop_element.")})},setOption:function(e,t){m.call(this,e,t,!this.runtime)},getOption:function(e){return e?S[e]:S},refresh:function(){U.length&&l.each(U,function(e){e.trigger("Refresh")}),this.trigger("Refresh")},start:function(){this.state!=l.STARTED&&(this.state=l.STARTED,this.trigger("StateChanged"),a.call(this))},stop:function(){this.state!=l.STOPPED&&(this.state=l.STOPPED,this.trigger("StateChanged"),this.trigger("CancelUpload"))},disableBrowse:function(){A=arguments[0]!==i?arguments[0]:!0,U.length&&l.each(U,function(e){e.disable(A)}),this.trigger("DisableBrowse",A)},getFile:function(e){var t;for(t=x.length-1;t>=0;t--)if(x[t].id===e)return x[t]},addFile:function(e,i){function n(e,t){var i=[];l.each(u.settings.filters,function(t,n){s[n]&&i.push(function(i){s[n].call(u,t,e,function(e){i(!e)})})}),l.inSeries(i,t)}function a(e){var s=l.typeOf(e);if(e instanceof t.file.File){if(!e.ruid&&!e.isDetached()){if(!o)return!1;e.ruid=o,e.connectRuntime(o)}a(new l.File(e))}else e instanceof t.file.Blob?(a(e.getSource()),e.destroy()):e instanceof l.File?(i&&(e.name=i),d.push(function(t){n(e,function(i){i||(x.push(e),f.push(e),u.trigger("FileFiltered",e)),r(t,1)})})):-1!==l.inArray(s,["file","blob"])?a(new t.file.File(null,e)):"node"===s&&"filelist"===l.typeOf(e.files)?l.each(e.files,a):"array"===s&&(i=null,l.each(e,a))}var o,u=this,d=[],f=[];o=c(),a(e),d.length&&l.inSeries(d,function(){f.length&&u.trigger("FilesAdded",f)})},removeFile:function(e){for(var t="string"==typeof e?e:e.id,i=x.length-1;i>=0;i--)if(x[i].id===t)return this.splice(i,1)[0]},splice:function(e,t){var n=x.splice(e===i?0:e,t===i?x.length:t),r=!1;return this.state==l.STARTED&&(l.each(n,function(e){return e.status===l.UPLOADING?(r=!0,!1):void 0}),r&&this.stop()),this.trigger("FilesRemoved",n),l.each(n,function(e){e.destroy()}),r&&this.start(),n},dispatchEvent:function(e){var t,i;if(e=e.toLowerCase(),t=this.hasEventListener(e)){t.sort(function(e,t){return t.priority-e.priority}),i=[].slice.call(arguments),i.shift(),i.unshift(this);for(var n=0;n<t.length;n++)if(t[n].fn.apply(t[n].scope,i)===!1)return!1}return!0},bind:function(e,t,i,n){l.Uploader.prototype.bind.call(this,e,t,n,i)},destroy:function(){this.trigger("Destroy"),S=w=null,this.unbindAll()}})},l.Uploader.prototype=t.core.EventTarget.instance,l.File=function(){function e(e){l.extend(this,{id:l.guid(),name:e.name||e.fileName,type:e.type||"",size:e.size||e.fileSize,origSize:e.size||e.fileSize,loaded:0,percent:0,status:l.QUEUED,lastModifiedDate:e.lastModifiedDate||(new Date).toLocaleString(),completeTimestamp:0,getNative:function(){var e=this.getSource().getSource();return-1!==l.inArray(l.typeOf(e),["blob","file"])?e:null},getSource:function(){return t[this.id]?t[this.id]:null},destroy:function(){var e=this.getSource();e&&(e.destroy(),delete t[this.id])}}),t[this.id]=e}var t={};return e}(),l.QueueProgress=function(){var e=this;e.size=0,e.loaded=0,e.uploaded=0,e.failed=0,e.queued=0,e.percent=0,e.bytesPerSec=0,e.reset=function(){e.size=e.loaded=e.uploaded=e.failed=e.queued=e.percent=e.bytesPerSec=0}},e.plupload=l}(this,e)});
define('upload',['jquery', 'bootstrap', 'plupload', 'template'], function ($, undefined, Plupload, Template) {
    var Upload = {
        list: {},
        config: {
            container: document.body,
            classname: '.plupload:not([initialized])',
            previewtpl: '<li class="col-xs-3"><a href="<%=fullurl%>" data-url="<%=url%>" target="_blank" class="thumbnail"><img src="<%=fullurl%>" class="img-responsive"></a><a href="javascript:;" class="btn btn-danger btn-xs btn-trash"><i class="fa fa-trash"></i></a></li>',
        },
        events: {
            //上传成功的回调
            onUploadSuccess: function (ret, onUploadSuccess, button) {
                var data = typeof ret.data !== 'undefined' ? ret.data : null;
                //上传成功后回调
                if (button) {
                    //如果有文本框则填充
                    var input_id = $(button).data("input-id") ? $(button).data("input-id") : "";
                    if (input_id) {
                        var urlArr = [];
                        var inputObj = $("#" + input_id);
                        if ($(button).data("multiple") && inputObj.val() !== "") {
                            urlArr.push(inputObj.val());
                        }
                        urlArr.push(data.url);
                        inputObj.val(urlArr.join(",")).trigger("change");
                    }
                    //如果有回调函数
                    var onDomUploadSuccess = $(button).data("upload-success");
                    if (onDomUploadSuccess) {
                        if (typeof onDomUploadSuccess !== 'function' && typeof Upload.api.custom[onDomUploadSuccess] === 'function') {
                            onDomUploadSuccess = Upload.api.custom[onDomUploadSuccess];
                        }
                        if (typeof onDomUploadSuccess === 'function') {
                            var result = onDomUploadSuccess.call(button, data, ret);
                            if (result === false)
                                return;
                        }
                    }
                }

                if (typeof onUploadSuccess === 'function') {
                    var result = onUploadSuccess.call(button, data, ret);
                    if (result === false)
                        return;
                }
            },
            //上传错误的回调
            onUploadError: function (ret, onUploadError, button) {
                var data = typeof ret.data !== 'undefined' ? ret.data : null;
                if (button) {
                    var onDomUploadError = $(button).data("upload-error");
                    if (onDomUploadError) {
                        if (typeof onDomUploadError !== 'function' && typeof Upload.api.custom[onDomUploadError] === 'function') {
                            onDomUploadError = Upload.api.custom[onDomUploadError];
                        }
                        if (typeof onDomUploadError === 'function') {
                            var result = onDomUploadError.call(button, data, ret);
                            if (result === false)
                                return;
                        }
                    }
                }

                if (typeof onUploadError === 'function') {
                    var result = onUploadError.call(button, data, ret);
                    if (result === false) {
                        return;
                    }
                }
                Toastr.error(ret.msg + "(code:" + ret.code + ")");
            },
            //服务器响应数据后
            onUploadResponse: function (response) {
                try {
                    var ret = typeof response === 'object' ? response : JSON.parse(response);
                    if (!ret.hasOwnProperty('code')) {
                        $.extend(ret, {code: -2, msg: response, data: null});
                    }
                } catch (e) {
                    var ret = {code: -1, msg: e.message, data: null};
                }
                return ret;
            }
        },
        api: {
            //Plupload上传
            plupload: function (element, onUploadSuccess, onUploadError) {
                element = typeof element === 'undefined' ? Upload.config.classname : element;
                $(element, Upload.config.container).each(function () {
                    $(this).attr("initialized", true);
                    var that = this;
                    var id = $(this).prop("id");
                    var url = $(this).data("url");
                    var maxsize = $(this).data("maxsize");
                    var mimetype = $(this).data("mimetype");
                    var multipart = $(this).data("multipart");
                    var multiple = $(this).data("multiple");

                    //填充ID
                    var input_id = $(that).data("input-id") ? $(that).data("input-id") : "";
                    //预览ID
                    var preview_id = $(that).data("preview-id") ? $(that).data("preview-id") : "";

                    //上传URL
                    url = url ? url : Config.upload.uploadurl;
                    url = Fast.api.fixurl(url);
                    //最大可上传
                    maxsize = typeof maxsize !== "undefined" ? maxsize : Config.upload.maxsize;
                    //文件类型
                    mimetype = typeof mimetype !== "undefined" ? mimetype : Config.upload.mimetype;
                    //请求的表单参数
                    multipart = typeof multipart !== "undefined" ? multipart : Config.upload.multipart;
                    //是否支持批量上传
                    multiple = typeof multiple !== "undefined" ? multiple : Config.upload.multiple;
                    //生成Plupload实例
                    Upload.list[id] = new Plupload.Uploader({
                        runtimes: 'html5,flash,silverlight,html4',
                        multi_selection: multiple, //是否允许多选批量上传
                        browse_button: id, // 浏览按钮的ID
                        container: $(this).parent().get(0), //取按钮的上级元素
                        flash_swf_url: '/assets/libs/plupload/js/Moxie.swf',
                        silverlight_xap_url: '/assets/libs/plupload/js/Moxie.xap',
                        filters: {
                            max_file_size: maxsize,
                            mime_types: mimetype
                        },
                        url: url,
                        multipart_params: multipart,
                        init: {
                            PostInit: function () {

                            },
                            FilesAdded: function (up, files) {
                                var button = up.settings.button;
                                $(button).data("bakup-html", $(button).html());
                                //添加后立即上传
                                setTimeout(function () {
                                    up.start();
                                }, 1);
                            },
                            UploadProgress: function (up, file) {
                                var button = up.settings.button;
                                //这里可以改成其它的表现形式
                                //document.getElementById(file.id).getElementsByTagName('b')[0].innerHTML = '<span>' + file.percent + "%</span>";
                                $(button).prop("disabled", true).html("<i class='fa fa-upload'></i> " + __('Upload') + file.percent + "%");
                            },
                            FileUploaded: function (up, file, info) {
                                var button = up.settings.button;
                                //还原按钮文字及状态
                                $(button).prop("disabled", false).html($(button).data("bakup-html"));
                                var ret = Upload.events.onUploadResponse(info.response);
                                if (ret.code === 1) {
                                    Upload.events.onUploadSuccess(ret, onUploadSuccess, button);
                                } else {
                                    Upload.events.onUploadError(ret, onUploadError, button);
                                }
                            },
                            Error: function (up, err) {
                                var button = up.settings.button;
                                $(button).prop("disabled", false).html($(button).data("bakup-html"));
                                var ret = {code: err.code, msg: err.message, data: null};
                                Upload.events.onUploadError(ret, onUploadError, button);
                            }
                        },
                        onUploadSuccess: onUploadSuccess,
                        onUploadError: onUploadError,
                        button: that
                    });

                    //拖动排序
                    if (preview_id && multiple) {
                        require(['dragsort'], function () {
                            $("#" + preview_id).dragsort({
                                dragSelector: "li",
                                dragEnd: function () {
                                    $("#" + preview_id).trigger("fa.preview.change");
                                },
                                placeHolderTemplate: '<li class="col-xs-3"></li>'
                            });
                        });
                    }
                    if (preview_id && input_id) {
                        $(document.body).on("keyup change", "#" + input_id, function () {
                            var inputStr = $("#" + input_id).val();
                            var inputArr = inputStr.split(/\,/);
                            $("#" + preview_id).empty();
                            var tpl = $("#" + preview_id).data("template") ? $("#" + preview_id).data("template") : "";
                            $.each(inputArr, function (i, j) {
                                if (!j) {
                                    return true;
                                }
                                var data = {url: j, fullurl: Fast.api.cdnurl(j), data: $(that).data()};
                                var html = tpl ? Template(tpl, data) : Template.render(Upload.config.previewtpl, data);
                                $("#" + preview_id).append(html);
                            });
                        });
                        $("#" + input_id).trigger("keyup");
                    }
                    if (preview_id) {
                        // 监听事件
                        $(document.body).on("fa.preview.change", "#" + preview_id, function () {
                            var urlArr = new Array();
                            $("#" + preview_id + " [data-url]").each(function (i, j) {
                                urlArr.push($(this).data("url"));
                            });
                            if (input_id) {
                                $("#" + input_id).val(urlArr.join(","));
                            }
                        });
                        // 移除按钮事件
                        $(document.body).on("click", "#" + preview_id + " .btn-trash", function () {
                            $(this).closest("li").remove();
                            $("#" + preview_id).trigger("fa.preview.change");
                        });
                    }
                    Upload.list[id].init();
                });
            },
            // AJAX异步上传
            send: function (file, onUploadSuccess, onUploadError) {
                var data = new FormData();
                data.append("file", file);
                $.each(Config.upload.multipart, function (k, v) {
                    data.append(k, v);
                });
                $.ajax({
                    url: Config.upload.uploadurl,
                    data: data,
                    cache: false,
                    contentType: false,
                    processData: false,
                    type: 'POST',
                    success: function (ret) {
                        ret = Upload.events.onUploadResponse(ret);
                        if (ret.code === 1) {
                            Upload.events.onUploadSuccess(ret, onUploadSuccess);
                        } else {
                            Upload.events.onUploadError(ret, onUploadError);
                        }
                    }, error: function (e) {
                        var ret = {code: 500, msg: e.message, data: null};
                        Upload.events.onUploadError(ret, onUploadError);
                    }
                });
            },
            custom: {
                //自定义上传完成回调
                afteruploadcallback: function (response) {
                    console.log(this, response);
                    alert("Custom Callback,Response URL:" + response.url);
                },
            },
        }
    };

    return Upload;
});
/*! nice-validator 1.1.1
 * (c) 2012-2017 Jony Zhang <niceue@live.com>, MIT Licensed
 * https://github.com/niceue/nice-validator
 */
;(function(factory) {
    typeof module === "object" && module.exports ? module.exports = factory( require( "jquery" ) ) :
    typeof define === 'function' && define.amd ? define('validator-core',['jquery'], factory) :
    factory(jQuery);
}(function($, undefined) {
    "use strict";

    var NS = 'validator',
        CLS_NS = '.' + NS,
        CLS_NS_RULE = '.rule',
        CLS_NS_FIELD = '.field',
        CLS_NS_FORM = '.form',
        CLS_WRAPPER = 'nice-' + NS,
        CLS_MSG_BOX = 'msg-box',
        ARIA_INVALID = 'aria-invalid',
        DATA_RULE = 'data-rule',
        DATA_MSG = 'data-msg',
        DATA_TIP = 'data-tip',
        DATA_OK = 'data-ok',
        DATA_TIMELY = 'data-timely',
        DATA_TARGET = 'data-target',
        DATA_DISPLAY = 'data-display',
        DATA_MUST = 'data-must',
        NOVALIDATE = 'novalidate',
        INPUT_SELECTOR = ':verifiable',

        rRules = /(&)?(!)?\b(\w+)(?:\[\s*(.*?\]?)\s*\]|\(\s*(.*?\)?)\s*\))?\s*(;|\|)?/g,
        rRule = /(\w+)(?:\[\s*(.*?\]?)\s*\]|\(\s*(.*?\)?)\s*\))?/,
        rDisplay = /(?:([^:;\(\[]*):)?(.*)/,
        rDoubleBytes = /[^\x00-\xff]/g,
        rPos = /top|right|bottom|left/,
        rAjaxType = /(?:(cors|jsonp):)?(?:(post|get):)?(.+)/i,
        rUnsafe = /[<>'"`\\]|&#x?\d+[A-F]?;?|%3[A-F]/gmi,

        noop = $.noop,
        proxy = $.proxy,
        trim = $.trim,
        isFunction = $.isFunction,
        isString = function(s) {
            return typeof s === 'string';
        },
        isObject = function(o) {
            return o && Object.prototype.toString.call(o) === '[object Object]';
        },
        isIE = document.documentMode || +(navigator.userAgent.match(/MSIE (\d+)/) && RegExp.$1),
        attr = function(el, key, value) {
            if (!el || !el.tagName) return null;
            if (value !== undefined) {
                if (value === null) el.removeAttribute(key);
                else el.setAttribute(key, '' + value);
            } else {
                return el.getAttribute(key);
            }
        },
        novalidateonce,
        preinitialized = {},

        defaults = {
            debug: 0,
            theme: 'default',
            ignore: '',
            focusInvalid: true,
            focusCleanup: false,
            stopOnError: false,
            beforeSubmit: null,
            valid: null,
            invalid: null,
            validation: null,
            formClass: 'n-default',
            validClass: 'n-valid',
            invalidClass: 'n-invalid',
            bindClassTo: null
        },
        fieldDefaults = {
            timely: 1,
            display: null,
            target: null,
            ignoreBlank: false,
            showOk: true,
            // Translate ajax response to validation result
            dataFilter: function (data) {
                if ( isString(data) || ( isObject(data) && ('error' in data || 'ok' in data) ) ) {
                    return data;
                }
            },
            msgMaker: function(opt) {
                var html;
                html = '<span role="alert" class="msg-wrap n-'+ opt.type + '">' + opt.arrow;
                if (opt.result) {
                    $.each(opt.result, function(i, obj){
                        html += '<span class="n-'+ obj.type +'">' + opt.icon + '<span class="n-msg">' + obj.msg + '</span></span>';
                    });
                } else {
                    html += opt.icon + '<span class="n-msg">' + opt.msg + '</span>';
                }
                html += '</span>';
                return html;
            },
            msgWrapper: 'span',
            msgArrow: '',
            msgIcon: '<span class="n-icon"></span>',
            msgClass: 'n-right',
            msgStyle: '',
            msgShow: null,
            msgHide: null
        },
        themes = {};

    /** jQuery Plugin
     * @param {Object} options
        debug         {Boolean}     0               Whether to enable debug mode
        timely        {Number}      1               Whether to enable timely validation
        theme         {String}     'default'        Theme name
        stopOnError   {Boolean}     false           Whether to stop validate when found an error input
        focusCleanup  {Boolean}     false           Whether to clean up the field message when focus the field
        focusInvalid  {Boolean}     true            Whether to focus the field that is invalid
        ignoreBlank   {Boolean}     false           When the field has no value, whether to ignore validation
        ignore        {jqSelector}    ''            Ignored fields (Using jQuery selector)

        beforeSubmit  {Function}                    Do something before submit form
        dataFilter    {Function}                    Convert ajax results
        valid         {Function}                    Triggered when the form is valid
        invalid       {Function}                    Triggered when the form is invalid
        validClass    {String}      'n-valid'       Add this class name to a valid field
        invalidClass  {String}      'n-invalid'     Add this class name to a invalid field
        bindClassTo   {jqSelector}  ':verifiable'   Which element should the className binding to

        display       {Function}                    Callback function to get dynamic display
        target        {Function}                    Callback function to get dynamic target
        msgShow       {Function}                    Trigger this callback when show message
        msgHide       {Function}                    Trigger this callback when hide message
        msgWrapper    {String}      'span'          Message wrapper tag name
        msgMaker      {Function}                    Callback function to make message HTML
        msgArrow      {String}                      Message arrow template
        msgIcon       {String}                      Message icon template
        msgStyle      {String}                      Custom message css style
        msgClass      {String}                      Additional added to the message class names
        formClass     {String}                      Additional added to the form class names

        messages      {Object}                      Custom messages for the current instance
        rules         {Object}                      Custom rules for the current instance
        fields        {Object}                      Field validation configuration
        {String}        key    name|#id
        {String|Object} value                       Rule string or an object which can pass more arguments

        fields[key][rule]       {String}            Rule string
        fields[key][display]    {String|Function}
        fields[key][tip]        {String}            Custom tip message
        fields[key][ok]         {String}            Custom success message
        fields[key][msg]        {Object}            Custom error message
        fields[key][msgStyle]   {String}            Custom message style
        fields[key][msgClass]   {String}            A className which added to message placeholder element
        fields[key][msgWrapper] {String}            Tag name of the message placeholder element
        fields[key][msgMaker]   {Function}          A function to custom message HTML
        fields[key][dataFilter] {Function}          A function to convert ajax results
        fields[key][valid]      {Function}          A function triggered when field is valid
        fields[key][invalid]    {Function}          A function triggered when field is invalid
        fields[key][must]       {Boolean}           If set true, we always check the field even has remote checking
        fields[key][timely]     {Boolean}           Whether to enable timely validation
        fields[key][target]     {jqSelector}        Define placement of a message
     */
    $.fn.validator = function(options) {
        var that = this,
            args = arguments;

        if (that.is(INPUT_SELECTOR)) return that;
        if (!that.is('form')) that = this.find('form');
        if (!that.length) that = this;

        that.each(function() {
            var instance = $(this).data(NS);

            if (instance) {
                if ( isString(options) ) {
                    if ( options.charAt(0) === '_' ) return;
                    instance[options].apply(instance, [].slice.call(args, 1));
                }
                else if (options) {
                    instance._reset(true);
                    instance._init(this, options);
                }
            } else {
                new Validator(this, options);
            }
        });

        return this;
    };


    // Validate a field, or an area
    $.fn.isValid = function(callback, hideMsg) {
        var me = _getInstance(this[0]),
            hasCallback = isFunction(callback),
            ret, opt;

        if (!me) return true;
        if (!hasCallback && hideMsg === undefined) hideMsg = callback;
        me.checkOnly = !!hideMsg;
        opt = me.options;

        ret = me._multiValidate(
            this.is(INPUT_SELECTOR) ? this : this.find(INPUT_SELECTOR),
            function(isValid){
                if (!isValid && opt.focusInvalid && !me.checkOnly) {
                    // navigate to the error element
                    me.$el.find('[' + ARIA_INVALID + ']:first').focus();
                }
                if (hasCallback) {
                    if (callback.length) {
                        callback(isValid);
                    } else if (isValid) {
                        callback();
                    }
                }
                me.checkOnly = false;
            }
        );

        // If you pass a callback, we maintain the jQuery object chain
        return hasCallback ? this : ret;
    };

    $.extend($.expr.pseudos || $.expr[':'], {
        // A faster selector than ":input:not(:submit,:button,:reset,:image,:disabled,[contenteditable])"
        verifiable: function(elem) {
            var name = elem.nodeName.toLowerCase();

            return ( name === 'input' && !({submit: 1, button: 1, reset: 1, image: 1})[elem.type] ||
                     name === 'select' ||
                     name === 'textarea' ||
                     elem.contentEditable === 'true'
                    ) && !elem.disabled;
        },
        // any value, but not only whitespace
        filled: function(elem) {
            return !!trim($(elem).val());
        }
    });

    /**
     * Creates a new Validator
     *
     * @class
     * @param {Element} element - form element
     * @param {Object}  options - options for validator
     */
    function Validator(element, options) {
        var me = this;

        if ( !(me instanceof Validator) ) {
            return new Validator(element, options);
        }

        if (Validator.pending) {
            $(window).on('validatorready', init);
        } else {
            init();
        }

        function init() {
            me.$el = $(element);
            if (me.$el.length) {
                me._init(me.$el[0], options);
            }
            else if (isString(element)) {
                preinitialized[element] = options;
            }
        }
    }

    Validator.prototype = {
        _init: function(element, options) {
            var me = this,
                opt, themeOpt, dataOpt;

            // Initialization options
            if ( isFunction(options) ) {
                options = {
                    valid: options
                };
            }
            options = me._opt = options || {};
            dataOpt = attr(element, 'data-'+ NS +'-option');
            dataOpt = me._dataOpt = dataOpt && dataOpt.charAt(0) === '{' ? (new Function("return " + dataOpt))() : {};
            themeOpt = me._themeOpt = themes[ options.theme || dataOpt.theme || defaults.theme ];
            opt = me.options = $.extend({}, defaults, fieldDefaults, themeOpt, me.options, options, dataOpt);

            me.rules = new Rules(opt.rules, true);
            me.messages = new Messages(opt.messages, true);
            me.Field = _createFieldFactory(me);
            me.elements = me.elements || {};
            me.deferred = {};
            me.errors = {};
            me.fields = {};
            // Initialization fields
            me._initFields(opt.fields);

            // Initialization events and make a cache
            if ( !me.$el.data(NS) ) {
                me.$el.data(NS, me).addClass(CLS_WRAPPER +' '+ opt.formClass)
                    .on('form-submit-validate', function(e, a, $form, opts, veto) {
                        me.vetoed = veto.veto = !me.isValid;
                        me.ajaxFormOptions = opts;
                    })
                    .on('submit'+ CLS_NS +' validate'+ CLS_NS, proxy(me, '_submit'))
                    .on('reset'+ CLS_NS, proxy(me, '_reset'))
                    .on('showmsg'+ CLS_NS, proxy(me, '_showmsg'))
                    .on('hidemsg'+ CLS_NS, proxy(me, '_hidemsg'))
                    .on('focusin'+ CLS_NS + ' click'+ CLS_NS, INPUT_SELECTOR, proxy(me, '_focusin'))
                    .on('focusout'+ CLS_NS +' validate'+ CLS_NS, INPUT_SELECTOR, proxy(me, '_focusout'))
                    .on('keyup'+ CLS_NS +' input'+ CLS_NS + ' compositionstart compositionend', INPUT_SELECTOR, proxy(me, '_focusout'))
                    .on('click'+ CLS_NS, ':radio,:checkbox', 'click', proxy(me, '_focusout'))
                    .on('change'+ CLS_NS, 'select,input[type="file"]', 'change', proxy(me, '_focusout'));

                // cache the novalidate attribute value
                me._NOVALIDATE = attr(element, NOVALIDATE);
                // Initialization is complete, stop off default HTML5 form validation
                // If use "jQuery.attr('novalidate')" in IE7 will complain: "SCRIPT3: Member not found."
                attr(element, NOVALIDATE, NOVALIDATE);
            }

            // Display all messages in target container
            if ( isString(opt.target) ) {
                me.$el.find(opt.target).addClass('msg-container');
            }
        },

        // Guess whether the form use ajax submit
        _guessAjax: function(form) {
            var me = this;

            if ( !(me.isAjaxSubmit = !!me.options.valid) ) {
                // if there is a "valid.form" event
                var events = ($._data || $.data)(form, "events");
                me.isAjaxSubmit = issetEvent(events, 'valid', 'form') || issetEvent(events, 'submit', 'form-plugin');
            }

            function issetEvent(events, name, namespace) {
                if ( events && events[name] &&
                     $.map(events[name], function(e){
                        return ~e.namespace.indexOf(namespace) ? 1 : null;
                     }).length
                ) {
                    return true;
                }
                return false;
            }
        },

        _initFields: function(fields) {
            var me = this, k, arr, i,
                clear = fields === null;

            // Processing field information
            if (clear) fields = me.fields;

            if ( isObject(fields) ) {
                for (k in fields) {
                    if (~k.indexOf(',')) {
                        arr = k.split(',');
                        i = arr.length;
                        while (i--) {
                            initField(trim(arr[i]), fields[k]);
                        }
                    } else {
                        initField(k, fields[k]);
                    }
                }
            }

            // Parsing DOM rules
            me.$el.find(INPUT_SELECTOR).each(function() {
                me._parse(this);
            });

            function initField(k, v) {
                // delete a field from settings
                if ( v === null || clear ) {
                    var el = me.elements[k];
                    if (el) me._resetElement(el, true);
                    delete me.fields[k];
                } else {
                    me.fields[k] = new me.Field(k, isString(v) ? {rule: v} : v, me.fields[k]);
                }
            }
        },

        // Parsing a field
        _parse: function(el) {
            var me = this,
                field,
                key = el.name,
                display,
                timely,
                dataRule = attr(el, DATA_RULE);

            dataRule && attr(el, DATA_RULE, null);

            // If the field has passed the key as id mode, or it doesn't has a name
            if ( el.id && (
                ('#' + el.id in me.fields) ||
                !key ||
                // If dataRule and element are diffrent from old's, we use ID mode.
                (dataRule !== null && (field = me.fields[key]) && dataRule !== field.rule && el.id !== field.key)
                )
            ) {
                key = '#' + el.id;
            }
            // Generate id
            if (!key) {
                key = '#' + (el.id = 'N' + String(Math.random()).slice(-12));
            }

            field = me.getField(key, true);
            // The priority of passing parameter by DOM is higher than by JS.
            field.rule = dataRule || field.rule;

            if (display = attr(el, DATA_DISPLAY)) {
                field.display = display;
            }
            if (field.rule) {
                if ( attr(el, DATA_MUST) !== null || /\b(?:match|checked)\b/.test(field.rule) ) {
                    field.must = true;
                }
                if ( /\brequired\b/.test(field.rule) ) {
                    field.required = true;
                }
                if (timely = attr(el, DATA_TIMELY)) {
                    field.timely = +timely;
                } else if (field.timely > 3) {
                    attr(el, DATA_TIMELY, field.timely);
                }
                me._parseRule(field);
                field.old = {};
            }
            if ( isString(field.target) ) {
                attr(el, DATA_TARGET, field.target);
            }
            if ( isString(field.tip) ) {
                attr(el, DATA_TIP, field.tip);
            }

            return me.fields[key] = field;
        },

        // Parsing field rules
        _parseRule: function(field) {
            var arr = rDisplay.exec(field.rule);

            if (!arr) return;
            // current rule index
            field._i = 0;
            if (arr[1]) {
                field.display = arr[1];
            }
            if (arr[2]) {
                field._rules = [];
                arr[2].replace(rRules, function(){
                    var args = arguments;
                    args[4] = args[4] || args[5];
                    field._rules.push({
                        and: args[1] === "&",
                        not: args[2] === "!",
                        or: args[6] === "|",
                        method: args[3],
                        params: args[4] ? $.map( args[4].split(', '), trim ) : undefined
                    });
                });
            }
        },

        // Verify a zone
        _multiValidate: function($inputs, doneCallback){
            var me = this,
                opt = me.options;

            me.hasError = false;

            if (opt.ignore) {
                $inputs = $inputs.not(opt.ignore);
            }

            $inputs.each(function() {
                me._validate(this);
                if (me.hasError && opt.stopOnError) {
                    // stop the validation
                    return false;
                }
            });

            // Need to wait for all fields validation complete, especially asynchronous validation
            if (doneCallback) {
                me.validating = true;
                $.when.apply(
                    null,
                    $.map(me.deferred, function(v){return v;})
                ).done(function(){
                    doneCallback.call(me, !me.hasError);
                    me.validating = false;
                });
            }

            // If the form does not contain asynchronous validation, the return value is correct.
            // Otherwise, you should detect form validation result through "doneCallback".
            return !$.isEmptyObject(me.deferred) ? undefined : !me.hasError;
        },

        // Validate the whole form
        _submit: function(e) {
            var me = this,
                opt = me.options,
                form = e.target,
                canSubmit = e.type === 'submit' && !e.isDefaultPrevented();

            e.preventDefault();

            if (
                novalidateonce && ~(novalidateonce = false) ||
                // Prevent duplicate submission
                me.submiting ||
                // Receive the "validate" event only from the form.
                e.type === 'validate' && me.$el[0] !== form ||
                // trigger the beforeSubmit callback.
                isFunction(opt.beforeSubmit) && opt.beforeSubmit.call(me, form) === false
            ) {
                return;
            }

            if (me.isAjaxSubmit === undefined) {
                me._guessAjax(form);
            }

            me._debug('log', '\n<<< event: ' + e.type);

            me._reset();
            me.submiting = true;

            me._multiValidate(
                me.$el.find(INPUT_SELECTOR),
                function(isValid){
                    var ret = (isValid || opt.debug === 2) ? 'valid' : 'invalid',
                        errors;

                    if (!isValid) {
                        if (opt.focusInvalid) {
                            // navigate to the error element
                            me.$el.find('[' + ARIA_INVALID + ']:first').focus();
                        }
                        errors = $.map(me.errors, function(err){return err;});
                    }

                    // releasing submit
                    me.submiting = false;
                    me.isValid = isValid;

                    // trigger callback and event
                    isFunction(opt[ret]) && opt[ret].call(me, form, errors);
                    me.$el.trigger(ret + CLS_NS_FORM, [form, errors]);

                    me._debug('log', '>>> ' + ret);

                    if (!isValid) return;
                    // For jquery.form plugin
                    if (me.vetoed) {
                        $(form).ajaxSubmit(me.ajaxFormOptions);
                    }
                    else if (canSubmit && !me.isAjaxSubmit) {
                        document.createElement('form').submit.call(form);
                    }
                }
            );
        },

        _reset: function(e) {
            var me = this;

            me.errors = {};
            if (e) {
                me.reseting = true;
                me.$el.find(INPUT_SELECTOR).each( function(){
                    me._resetElement(this);
                });
                delete me.reseting;
            }
        },

        _resetElement: function(el, all) {
            this._setClass(el, null);
            this.hideMsg(el);
        },

        // Handle events: "focusin/click"
        _focusin: function(e) {
            var me = this,
                opt = me.options,
                el = e.target,
                timely,
                msg;

            if ( me.validating || ( e.type==='click' && document.activeElement === el ) ) {
                return;
            }

            if (opt.focusCleanup) {
                if ( attr(el, ARIA_INVALID) === 'true' ) {
                    me._setClass(el, null);
                    me.hideMsg(el);
                }
            }

            msg = attr(el, DATA_TIP);

            if (msg) {
                me.showMsg(el, {
                    type: 'tip',
                    msg: msg
                });
            } else {
                if (attr(el, DATA_RULE)) {
                    me._parse(el);
                }
                if (timely = attr(el, DATA_TIMELY)) {
                    if ( timely === 8 || timely === 9 ) {
                        me._focusout(e);
                    }
                }
            }
        },

        // Handle events: "focusout/validate/keyup/click/change/input/compositionstart/compositionend"
        _focusout: function(e) {
            var me = this,
                opt = me.options,
                el = e.target,
                etype = e.type,
                etype0,
                focusin = etype === 'focusin',
                special = etype === 'validate',
                elem,
                field,
                old,
                value,
                timestamp,
                key, specialKey,
                timely,
                timer = 0;

            if (etype === 'compositionstart') {
                me.pauseValidate = true;
            }
            if (etype === 'compositionend') {
                me.pauseValidate = false;
            }
            if (me.pauseValidate) {
                return;
            }

            // For checkbox and radio
            elem = el.name && _checkable(el) ? me.$el.find('input[name="'+ el.name +'"]').get(0) : el;
            // Get field
            if (!(field = me.getField(elem)) || !field.rule) {
                return;
            }
            // Cache event type
            etype0 = field._e;
            field._e = etype;
            timely = field.timely;

            if (!special) {
                if (!timely || (_checkable(el) && etype !== 'click')) {
                    return;
                }

                value = field.getValue();

                // not validate field unless fill a value
                if ( field.ignoreBlank && !value && !focusin ) {
                    me.hideMsg(el);
                    return;
                }

                if ( etype === 'focusout' ) {
                    if (etype0 === 'change') {
                        return;
                    }
                    if ( timely === 2 || timely === 8 ) {
                        old = field.old;
                        if (value && old) {
                            if (field.isValid && !old.showOk) {
                                me.hideMsg(el);
                            } else {
                                me._makeMsg(el, field, old);
                            }
                        } else {
                            return;
                        }
                    }
                }
                else {
                    if ( timely < 2 && !e.data ) {
                        return;
                    }

                    // mark timestamp to reduce the frequency of the received event
                    timestamp = +new Date();
                    if ( timestamp - (el._ts || 0) < 100 ) {
                        return;
                    }
                    el._ts = timestamp;

                    // handle keyup
                    if ( etype === 'keyup' ) {
                        if (etype0 === 'input') {
                            return;
                        }
                        key = e.keyCode;
                        specialKey = {
                            8: 1,  // Backspace
                            9: 1,  // Tab
                            16: 1, // Shift
                            32: 1, // Space
                            46: 1  // Delete
                        };

                        // only gets focus, no validation
                        if ( key === 9 && !value ) {
                            return;
                        }

                        // do not validate, if triggered by these keys
                        if ( key < 48 && !specialKey[key] ) {
                            return;
                        }
                    }
                    if ( !focusin ) {
                        // keyboard events, reducing the frequency of validation
                        timer = timely <100 ?  (etype === 'click' || el.tagName === 'SELECT') ? 0 : 400 : timely;
                    }
                }
            }

            // if the current field is ignored
            if ( opt.ignore && $(el).is(opt.ignore) ) {
                return;
            }

            clearTimeout(field._t);

            if (timer) {
                field._t = setTimeout(function() {
                    me._validate(el, field);
                }, timer);
            } else {
                if (special) field.old = {};
                me._validate(el, field);
            }
        },

        _setClass: function(el, isValid) {
            var $el = $(el), opt = this.options;
            if (opt.bindClassTo) {
                $el = $el.closest(opt.bindClassTo);
            }
            $el.removeClass( opt.invalidClass + ' ' + opt.validClass );
            if (isValid !== null) {
                $el.addClass( isValid ? opt.validClass : opt.invalidClass );
            }
        },

        _showmsg: function(e, type, msg) {
            var me = this,
                el = e.target;

            if ( me.$el.is(el) ) {
                if (isObject(type)) {
                    me.showMsg(type)
                }
                else if ( type === 'tip' ) {
                    me.$el.find(INPUT_SELECTOR +"["+ DATA_TIP +"]", el).each(function(){
                        me.showMsg(this, {type: type, msg: msg});
                    });
                }
            }
            else {
                me.showMsg(el, {type: type, msg: msg});
            }
        },

        _hidemsg: function(e) {
            var $el = $(e.target);

            if ( $el.is(INPUT_SELECTOR) ) {
                this.hideMsg($el);
            }
        },

        // Validated a field
        _validatedField: function(el, field, ret) {
            var me = this,
                opt = me.options,
                isValid = field.isValid = ret.isValid = !!ret.isValid,
                callback = isValid ? 'valid' : 'invalid';

            ret.key = field.key;
            ret.ruleName = field._r;
            ret.id = el.id;
            ret.value = field.value;

            me.elements[field.key] = ret.element = el;
            me.isValid = me.$el[0].isValid = isValid ? me.isFormValid() : isValid;

            if (isValid) {
                ret.type = 'ok';
            } else {
                if (me.submiting) {
                    me.errors[field.key] = ret.msg;
                }
                me.hasError = true;
            }

            // cache result
            field.old = ret;

            // trigger callback
            isFunction(field[callback]) && field[callback].call(me, el, ret);
            isFunction(opt.validation) && opt.validation.call(me, el, ret);

            // trigger event
            $(el).attr( ARIA_INVALID, isValid ? null : true )
                 .trigger( callback + CLS_NS_FIELD, [ret, me] );
            me.$el.triggerHandler('validation', [ret, me]);

            if (me.checkOnly) return;
            // set className
            me._setClass(el, ret.skip || ret.type === 'tip' ? null : isValid);
            me._makeMsg.apply(me, arguments);
        },

        _makeMsg: function(el, field, ret) {
            // show or hide the message
            if (field.msgMaker) {
                ret = $.extend({}, ret);
                if (field._e === 'focusin') {
                    ret.type = 'tip';
                }
                this[ ret.showOk || ret.msg || ret.type === 'tip' ? 'showMsg' : 'hideMsg' ](el, ret, field);
            }
        },

        // Validated a rule
        _validatedRule: function(el, field, ret, msgOpt) {
            field = field || me.getField(el);
            msgOpt = msgOpt || {};

            var me = this,
                msg,
                rule,
                method = field._r,
                timely = field.timely,
                special = timely === 9 || timely === 8,
                transfer,
                temp,
                isValid = false;

            // use null to break validation from a field
            if (ret === null) {
                me._validatedField(el, field, {isValid: true, skip: true});
                field._i = 0;
                return;
            }
            else if (ret === undefined) {
                transfer = true;
            }
            else if (ret === true || ret === '') {
                isValid = true;
            }
            else if (isString(ret)) {
                msg = ret;
            }
            else if (isObject(ret)) {
                if (ret.error) {
                    msg = ret.error;
                } else {
                    msg = ret.ok;
                    isValid = true;
                }
            }

            rule = field._rules[field._i];
            if (rule.not) {
                msg = undefined;
                isValid = method === "required" || !isValid;
            }
            if (rule.or) {
                if (isValid) {
                    while ( field._i < field._rules.length && field._rules[field._i].or ) {
                        field._i++;
                    }
                } else {
                    transfer = true;
                }
            }
            else if (rule.and) {
                if (!field.isValid) transfer = true;
            }

            if (transfer) {
                isValid = true;
            }
            // message analysis, and throw rule level event
            else {
                if (isValid) {
                    if (field.showOk !== false) {
                        temp = attr(el, DATA_OK);
                        msg = temp === null ? isString(field.ok) ? field.ok : msg : temp;
                        if (!isString(msg) && isString(field.showOk)) {
                            msg = field.showOk;
                        }
                        if (isString(msg)) {
                            msgOpt.showOk = isValid;
                        }
                    }
                }
                if (!isValid || special) {
                    /* rule message priority:
                        1. custom DOM message
                        2. custom field message;
                        3. global defined message;
                        4. rule returned message;
                        5. default message;
                    */
                    msg = (_getDataMsg(el, field, msg || rule.msg || me.messages[method]) || me.messages.fallback).replace(/\{0\|?([^\}]*)\}/, function(m, defaultDisplay){
                        return me._getDisplay(el, field.display) || defaultDisplay || me.messages[0];
                    });
                }
                if (!isValid) field.isValid = isValid;
                msgOpt.msg = msg;
                $(el).trigger( (isValid ? 'valid' : 'invalid') + CLS_NS_RULE, [method, msg]);
            }

            if (special && (!transfer || rule.and)) {
                if (!isValid && !field._m) field._m = msg;
                field._v = field._v || [];
                field._v.push({
                    type: isValid ? !transfer ? 'ok' : 'tip' : 'error',
                    msg: msg || rule.msg
                });
            }

            me._debug('log', '   ' + field._i + ': ' + method + ' => ' + (isValid || msg));

            // the current rule has passed, continue to validate
            if ( (isValid || special) && field._i < field._rules.length - 1) {
                field._i++;
                me._checkRule(el, field);
            }
            // field was invalid, or all fields was valid
            else {
                field._i = 0;

                if (special) {
                    msgOpt.isValid = field.isValid;
                    msgOpt.result = field._v;
                    msgOpt.msg = field._m || '';
                    if (!field.value && (field._e === 'focusin')) {
                        msgOpt.type = 'tip';
                    }
                } else {
                    msgOpt.isValid = isValid;
                }

                me._validatedField(el, field, msgOpt);
                delete field._m;
                delete field._v;
            }
        },

        // Verify a rule form a field
        _checkRule: function(el, field) {
            var me = this,
                ret,
                fn,
                old,
                key = field.key,
                rule = field._rules[field._i],
                method = rule.method,
                params = rule.params;

            // request has been sent, wait it
            if (me.submiting && me.deferred[key]) {
                return;
            }
            old = field.old;
            field._r = method;

            if (old && !field.must && !rule.must && rule.result !== undefined &&
                 old.ruleName === method && old.id === el.id &&
                field.value && old.value === field.value )
            {
                // get result from cache
                ret = rule.result;
            }
            else {
                // get result from current rule
                fn = _getDataRule(el, method) || me.rules[method] || noop;
                ret = fn.call(field, el, params, field);
                if (fn.msg) rule.msg = fn.msg;
            }

            // asynchronous validation
            if (isObject(ret) && isFunction(ret.then)) {
                me.deferred[key] = ret;

                // whether the field valid is unknown
                field.isValid = undefined;

                // show loading message
                !me.checkOnly && me.showMsg(el, {
                    type: 'loading',
                    msg: me.messages.loading
                }, field);

                // waiting to parse the response data
                ret.then(
                    function(d, textStatus, jqXHR) {
                        var data = trim(jqXHR.responseText),
                            result,
                            dataFilter = field.dataFilter;

                        // detect if data is json or jsonp format
                        if (/jsonp?/.test(this.dataType)) {
                            data = d;
                        } else if (data.charAt(0) === '{') {
                            data = $.parseJSON(data);
                        }

                        // filter data
                        result = dataFilter.call(this, data, field);
                        if (result === undefined) result = dataFilter.call(this, data.data, field);

                        rule.data = this.data;
                        rule.result = field.old ? result : undefined;
                        me._validatedRule(el, field, result);
                    },
                    function(jqXHR, textStatus){
                        me._validatedRule(el, field, me.messages[textStatus] || textStatus);
                    }
                ).always(function(){
                    delete me.deferred[key];
                });
            }
            // other result
            else {
                me._validatedRule(el, field, ret);
            }
        },

        // Processing the validation
        _validate: function(el, field) {
            var me = this;

            // doesn't validate the element that has "disabled" or "novalidate" attribute
            if ( el.disabled || attr(el, NOVALIDATE) !== null ) {
                return;
            }

            field = field || me.getField(el);
            if (!field) return;
            if (!field._rules) me._parse(el);
            if (!field._rules) return;

            me._debug('info', field.key);

            field.isValid = true;
            field.element = el;
            // Cache the value
            field.value = field.getValue();

            // if the field is not required, and that has a blank value
            if (!field.required && !field.must && !field.value) {
                if (!_checkable(el)) {
                    me._validatedField(el, field, {isValid: true});
                    return true;
                }
            }

            me._checkRule(el, field);
            return field.isValid;
        },

        _debug: function(type, messages) {
            if (window.console && this.options.debug) {
                console[type](messages);
            }
        },

        /**
         * Detecting whether the value of an element that matches a rule
         *
         * @method test
         * @param {Element} el - input element
         * @param {String} rule - rule name
         */
        test: function(el, rule) {
            var me = this,
                ret,
                parts = rRule.exec(rule),
                field,
                method,
                params;

            if (parts) {
                method = parts[1];
                if (method in me.rules) {
                    params = parts[2] || parts[3];
                    params = params ? params.split(', ') : undefined;
                    field = me.getField(el, true);
                    field._r = method;
                    field.value = field.getValue();
                    ret = me.rules[method].call(field, el, params);
                }
            }

            return ret === true || ret === undefined || ret === null;
        },

        _getDisplay: function(el, str) {
            return !isString(str) ? isFunction(str) ? str.call(this, el) : '' : str;
        },

        _getMsgOpt: function(obj, field) {
            var opt = field ? field : this.options;
            return $.extend({
                type: 'error',
                pos: _getPos(opt.msgClass),
                target: opt.target,
                wrapper: opt.msgWrapper,
                style: opt.msgStyle,
                cls: opt.msgClass,
                arrow: opt.msgArrow,
                icon: opt.msgIcon
            }, isString(obj) ? {msg: obj} : obj);
        },

        _getMsgDOM: function(el, msgOpt) {
            var $el = $(el), $msgbox, datafor, tgt, container;

            if ( $el.is(INPUT_SELECTOR) ) {
                tgt = msgOpt.target || attr(el, DATA_TARGET);
                if (tgt) {
                    tgt = !isFunction(tgt) ? tgt.charAt(0) === '#' ? $(tgt) : this.$el.find(tgt) : tgt.call(this, el);
                    if (tgt.length) {
                        if ( tgt.is(INPUT_SELECTOR) ) {
                            $el = tgt
                            el = tgt.get(0);
                        } else if ( tgt.hasClass(CLS_MSG_BOX) ) {
                            $msgbox = tgt;
                        } else {
                            container = tgt;
                        }
                    }
                }
                if (!$msgbox) {
                    datafor = (!_checkable(el) || !el.name) && el.id ? el.id : el.name;
                    $msgbox = (container || this.$el).find(msgOpt.wrapper + '.' + CLS_MSG_BOX + '[for="' + datafor + '"]');
                }
            } else {
                $msgbox = $el;
            }

            // Create new message box
            if (!msgOpt.hide && !$msgbox.length) {
                $msgbox = $('<'+ msgOpt.wrapper + '>').attr({
                    'class': CLS_MSG_BOX + (msgOpt.cls ? ' ' + msgOpt.cls : ''),
                    'style': msgOpt.style || undefined,
                    'for': datafor
                });

                if ( _checkable(el) ) {
                    var $parent = $el.parent();
                    $msgbox.appendTo( $parent.is('label') ? $parent.parent() : $parent );
                } else {
                    if (container) {
                        $msgbox.appendTo(container);
                    } else {
                        $msgbox[!msgOpt.pos || msgOpt.pos === 'right' ? 'insertAfter' : 'insertBefore']($el);
                    }
                }
            }

            return $msgbox;
        },

        /**
         * Show validation message
         *
         * @method showMsg
         * @param {Element} el - input element
         * @param {Object} msgOpt
         */
        showMsg: function(el, msgOpt, /*INTERNAL*/ field) {
            if (!el) return;
            var me = this,
                opt = me.options,
                msgShow,
                msgMaker,
                temp,
                $msgbox;

            if (isObject(el) && !el.jquery && !msgOpt) {
                $.each(el, function(key, msg) {
                    var el = me.elements[key] || me.$el.find(_key2selector(key))[0];
                    me.showMsg(el, msg);
                });
                return;
            }

            if ($(el).is(INPUT_SELECTOR)) {
                field = field || me.getField(el);
            }

            if (!(msgMaker = (field || opt).msgMaker)) {
                return;
            }

            msgOpt = me._getMsgOpt(msgOpt, field);
            el = (el.name && _checkable(el) ? me.$el.find('input[name="'+ el.name +'"]') : $(el)).get(0);

            // ok or tip
            if (!msgOpt.msg && msgOpt.type !== 'error') {
                temp = attr(el, 'data-' + msgOpt.type);
                if (temp !== null) msgOpt.msg = temp;
            }

            if ( !isString(msgOpt.msg) ) {
                return;
            }

            $msgbox = me._getMsgDOM(el, msgOpt);

            !rPos.test($msgbox[0].className) && $msgbox.addClass(msgOpt.cls);
            if ( isIE === 6 && msgOpt.pos === 'bottom' ) {
                $msgbox[0].style.marginTop = $(el).outerHeight() + 'px';
            }
            $msgbox.html( msgMaker.call(me, msgOpt) )[0].style.display = '';

            if (isFunction(msgShow = field && field.msgShow || opt.msgShow)) {
                msgShow.call(me, $msgbox, msgOpt.type);
            }
        },

        /**
         * Hide validation message
         *
         * @method hideMsg
         * @param {Element} el - input element
         * @param {Object} msgOpt optional
         */
        hideMsg: function(el, msgOpt, /*INTERNAL*/ field) {
            var me = this,
                opt = me.options,
                msgHide,
                $msgbox;

            el = $(el).get(0);
            if ($(el).is(INPUT_SELECTOR)) {
                field = field || me.getField(el);
                if (field) {
                    if (field.isValid || me.reseting) attr(el, ARIA_INVALID, null);
                }
            }

            msgOpt = me._getMsgOpt(msgOpt, field);
            msgOpt.hide = true;

            $msgbox = me._getMsgDOM(el, msgOpt);
            if (!$msgbox.length) return;

            if ( isFunction(msgHide = field && field.msgHide || opt.msgHide) ) {
                msgHide.call(me, $msgbox, msgOpt.type);
            } else {
                $msgbox[0].style.display = 'none';
                $msgbox[0].innerHTML = null;
            }
        },

        /**
         * Get field information
         *
         * @method getField
         * @param {Element} - input element
         * @return {Object} field
         */
        getField: function(el, must) {
            var me = this,
                key,
                field;

            if (isString(el)) {
                key = el;
                el = undefined;
            } else {
                if (attr(el, DATA_RULE)) {
                    return me._parse(el);
                }
                if (el.id && '#' + el.id in me.fields || !el.name) {
                    key = '#' + el.id;
                } else {
                    key = el.name;
                }
            }

            if ( (field = me.fields[key]) || must && (field = new me.Field(key)) ) {
                field.element = el;
            }

            return field;
        },

        /**
         * Config a field
         *
         * @method: setField
         * @param {String} key
         * @param {Object} obj
         */
        setField: function(key, obj) {
            var fields = {};

            if (!key) return;

            // update this field
            if (isString(key)) {
                fields[key] = obj;
            }
            // update fields
            else {
                fields = key;
            }

            this._initFields(fields);
        },

        /**
         * Detecting whether the form is valid
         *
         * @method isFormValid
         * @return {Boolean}
         */
        isFormValid: function() {
            var fields = this.fields, k, field;
            for (k in fields) {
                field = fields[k];
                if (!field._rules || !field.required && !field.must && !field.value) continue;
                if (!field.isValid) return false;
            }
            return true;
        },

        /**
         * Prevent submission form
         *
         * @method holdSubmit
         * @param {Boolean} hold - If set to false, will release the hold
         */
        holdSubmit: function(hold) {
            this.submiting = hold === undefined || hold;
        },

        /**
         * Clean validation messages
         *
         * @method cleanUp
         */
        cleanUp: function() {
            this._reset(1);
        },

        /**
         * Destroy the validation
         *
         * @method destroy
         */
        destroy: function() {
            this._reset(1);
            this.$el.off(CLS_NS).removeData(NS);
            attr(this.$el[0], NOVALIDATE, this._NOVALIDATE);
        }
    };

    /**
     * Create Field Factory
     *
     * @class
     * @param  {Object}     context
     * @return {Function}   Factory
     */
    function _createFieldFactory(context) {
        function FieldFactory() {
            var options = this.options;
            for (var i in options) {
                if (i in fieldDefaults) this[i] = options[i];
            }
            $.extend(this, {
                _valHook: function() {
                    return this.element.contentEditable === 'true' ? 'text' : 'val';
                },
                getValue: function() {
                    var elem = this.element;
                    if (elem.type === "number" && elem.validity && elem.validity.badInput) {
                        return 'NaN';
                    }
                    return  $(elem)[this._valHook()]();
                },
                setValue: function(value) {
                    $(this.element)[this._valHook()](this.value = value);
                },
                // Get a range of validation messages
                getRangeMsg: function(value, params, suffix) {
                    if (!params) return;

                    var me = this,
                        msg = me.messages[me._r] || '',
                        result,
                        p = params[0].split('~'),
                        e = params[1] === 'false',
                        a = p[0],
                        b = p[1],
                        c = 'rg',
                        args = [''],
                        isNumber = trim(value) && +value === +value;

                    function compare(large, small) {
                        return !e ? large >= small : large > small;
                    }

                    if (p.length === 2) {
                        if (a && b) {
                            if (isNumber && compare(value, +a) && compare(+b, value)) {
                                result = true;
                            }
                            args = args.concat(p);
                            c = e ? 'gtlt' : 'rg';
                        }
                        else if (a && !b) {
                            if (isNumber && compare(value, +a)) {
                                result = true;
                            }
                            args.push(a);
                            c = e ? 'gt' : 'gte';
                        }
                        else if (!a && b) {
                            if (isNumber && compare(+b, value)) {
                                result = true;
                            }
                            args.push(b);
                            c = e ? 'lt' : 'lte';
                        }
                    }
                    else {
                        if (value === +a) {
                            result = true;
                        }
                        args.push(a);
                        c = 'eq';
                    }

                    if (msg) {
                        if (suffix && msg[c + suffix]) {
                            c += suffix;
                        }
                        args[0] = msg[c];
                    }

                    return result || me._rules && ( me._rules[me._i].msg = me.renderMsg.apply(null, args) );
                },
                // Render message template
                renderMsg: function() {
                    var args = arguments,
                        tpl = args[0],
                        i = args.length;

                    if (!tpl) return;

                    while (--i) {
                        tpl = tpl.replace('{' + i + '}', args[i]);
                    }

                    return tpl;
                }
            });
        }
        function Field(key, obj, oldField) {
            this.key = key;
            this.validator = context;
            $.extend(this, oldField, obj);
        }

        FieldFactory.prototype = context;
        Field.prototype = new FieldFactory();

        return Field;
    }

    /**
     * Create Rules
     *
     * @class
     * @param {Object} obj     rules
     * @param {Object} context context
     */
    function Rules(obj, context) {
        if (!isObject(obj)) return;

        var k, that = context ? context === true ? this : context : Rules.prototype;

        for (k in obj) {
            if (_checkRuleName(k))
                that[k] = _getRule(obj[k]);
        }
    }

    /**
     * Create Messages
     *
     * @class
     * @param {Object} obj     rules
     * @param {Object} context context
     */
    function Messages(obj, context) {
        if (!isObject(obj)) return;

        var k, that = context ? context === true ? this : context : Messages.prototype;

        for (k in obj) {
            that[k] = obj[k];
        }
    }

    // Rule converted factory
    function _getRule(fn) {
        switch ($.type(fn)) {
            case 'function':
                return fn;
            case 'array':
                var f = function() {
                    return fn[0].test(this.value) || fn[1] || false;
                };
                f.msg = fn[1];
                return f;
            case 'regexp':
                return function() {
                    return fn.test(this.value);
                };
        }
    }

    // Get instance by an element
    function _getInstance(el) {
        var wrap, k, options;

        if (!el || !el.tagName) return;

        switch (el.tagName) {
            case 'INPUT':
            case 'SELECT':
            case 'TEXTAREA':
            case 'BUTTON':
            case 'FIELDSET':
                wrap = el.form || $(el).closest('.' + CLS_WRAPPER);
                break;
            case 'FORM':
                wrap = el;
                break;
            default:
                wrap = $(el).closest('.' + CLS_WRAPPER);
        }

        for (k in preinitialized) {
            if ($(wrap).is(k)) {
                options = preinitialized[k];
                break;
            }
        }

        return $(wrap).data(NS) || $(wrap)[NS](options).data(NS);
    }

    // Get custom rules on the node
    function _getDataRule(el, method) {
        var fn = trim(attr(el, DATA_RULE + '-' + method));

        if ( fn && (fn = new Function("return " + fn)()) ) {
            return _getRule(fn);
        }
    }

    // Get custom messages on the node
    function _getDataMsg(el, field, m) {
        var msg = field.msg,
            item = field._r;

        if ( isObject(msg) ) msg = msg[item];
        if ( !isString(msg) ) {
            msg = attr(el, DATA_MSG + '-' + item) || attr(el, DATA_MSG) || ( m ? isString(m) ? m : m[item] : '');
        }

        return msg;
    }

    // Get message position
    function _getPos(str) {
        var pos;

        if (str) pos = rPos.exec(str);
        return pos && pos[0];
    }

    // Check whether the element is checkbox or radio
    function _checkable(el) {
        return el.tagName === 'INPUT' && el.type === 'checkbox' || el.type === 'radio';
    }

    // Parse date string to timestamp
    function _parseDate(str) {
        return Date.parse(str.replace(/\.|\-/g, '/'));
    }

    // Rule name only allows alphanumeric characters and underscores
    function _checkRuleName(name) {
        return /^\w+$/.test(name);
    }

    // Translate field key to jQuery selector.
    function _key2selector(key) {
        var isID = key.charAt(0) === "#";
        key = key.replace(/([:.{(|)}/\[\]])/g, "\\$1");
        return isID ? key : '[name="'+ key +'"]:first';
    }


    // Fixed a issue cause by refresh page in IE.
    $(window).on('beforeunload', function(){
        this.focus();
    });

    $(document)
    .on('click', ':submit', function(){
        var input = this, attrNode;
        if (!input.form) return;
        // Shim for "formnovalidate"
        attrNode = input.getAttributeNode('formnovalidate');
        if (attrNode && attrNode.nodeValue !== null || attr(input, NOVALIDATE)!== null) {
            novalidateonce = true;
        }
    })
    // Automatic initializing form validation
    .on('focusin submit validate', 'form,.'+CLS_WRAPPER, function(e) {
        if ( attr(this, NOVALIDATE) !== null ) return;
        var $form = $(this), me;

        if ( !$form.data(NS) && (me = _getInstance(this)) ) {
            if ( !$.isEmptyObject(me.fields) ) {
                // Execute event handler
                if (e.type === 'focusin') {
                    me._focusin(e);
                } else {
                    me._submit(e);
                }
            } else {
                attr(this, NOVALIDATE, NOVALIDATE);
                $form.off(CLS_NS).removeData(NS);
            }
        }
    });

    new Messages({
        fallback: "This field is not valid.",
        loading: 'Validating...'
    });


    // Built-in rules (global)
    new Rules({

        /**
         * required
         *
         * @example:
            required
            required(anotherRule)
            required(not, -1)
            required(from, .contact)
         */
        required: function(element, params) {
            var me = this,
                val = trim(me.value),
                isValid = true;

            if (params) {
                if ( params.length === 1 ) {
                    if ( !_checkRuleName(params[0]) ) {
                        if (!val && !$(params[0], me.$el).length ) {
                            return null;
                        }
                    }
                    else if ( me.rules[params[0]] ) {
                        if ( !val && !me.test(element, params[0]) ) {
                            return null;
                        }
                    }
                }
                else if ( params[0] === 'not' ) {
                    $.each(params.slice(1), function() {
                        return (isValid = val !== trim(this));
                    });
                }
                else if ( params[0] === 'from' ) {
                    var $elements = me.$el.find(params[1]),
                        VALIDATED = '_validated_',
                        ret;

                    isValid = $elements.filter(function(){
                        var field = me.getField(this);
                        return field && !!trim(field.getValue());
                    }).length >= (params[2] || 1);

                    if (isValid) {
                        if (!val) ret = null;
                    } else {
                        ret = _getDataMsg($elements[0], me) || false;
                    }

                    if ( !$(element).data(VALIDATED) ) {
                        $elements.data(VALIDATED, 1).each(function(){
                            if (element !== this) {
                                me._validate(this);
                            }
                        }).removeData(VALIDATED);
                    }

                    return ret;
                }
            }

            return isValid && !!val;
        },

        /**
         * integer
         *
         * @example:
            integer
            integer[+]
            integer[+0]
            integer[-]
            integer[-0]
         */
        integer: function(element, params) {
            var re, z = '0|',
                p = '[1-9]\\d*',
                key = params ? params[0] : '*';

            switch (key) {
                case '+':
                    re = p;
                    break;
                case '-':
                    re = '-' + p;
                    break;
                case '+0':
                    re = z + p;
                    break;
                case '-0':
                    re = z + '-' + p;
                    break;
                default:
                    re = z + '-?' + p;
            }
            re = '^(?:' + re + ')$';

            return new RegExp(re).test(this.value) || this.messages.integer[key];
        },

        /**
         * match another field
         *
         * @example:
            match[password]    Match the password field (two values ​​must be the same)
            match[eq, password]  Ditto
            match[neq, count]  The value must be not equal to the value of the count field
            match[lt, count]   The value must be less than the value of the count field
            match[lte, count]  The value must be less than or equal to the value of the count field
            match[gt, count]   The value must be greater than the value of the count field
            match[gte, count]  The value must be greater than or equal to the value of the count field
            match[gte, startDate, date]
            match[gte, startTime, time]
         **/
        match: function(element, params) {
            if (!params) return;

            var me = this,
                a, b,
                key, msg, type = 'eq', parser,
                selector2, elem2, field2;

            if (params.length === 1) {
                key = params[0];
            } else {
                type = params[0];
                key = params[1];
            }

            selector2 = _key2selector(key);
            elem2 = me.$el.find(selector2)[0];
            // If the compared field is not exist
            if (!elem2) return;
            field2 = me.getField(elem2);
            a = me.value;
            b = field2.getValue();

            if (!me._match) {
                me.$el.on('valid'+CLS_NS_FIELD+CLS_NS, selector2, function(){
                    $(element).trigger('validate');
                });
                me._match = field2._match = 1;
            }

            // If both fields are blank
            if (!me.required && a === "" && b === "") {
                return null;
            }

            parser = params[2];
            if (parser) {
                if (/^date(time)?$/i.test(parser)) {
                    a = _parseDate(a);
                    b = _parseDate(b);
                } else if (parser === 'time') {
                    a = +a.replace(/:/g, '');
                    b = +b.replace(/:/g, '');
                }
            }

            // If the compared field is incorrect, we only ensure that this field is correct.
            if (type !== "eq" && !isNaN(+a) && isNaN(+b)) {
                return true;
            }

            msg = me.messages.match[type].replace( '{1}', me._getDisplay( element, field2.display || key ) );

            switch (type) {
                case 'lt':
                    return (+a < +b) || msg;
                case 'lte':
                    return (+a <= +b) || msg;
                case 'gte':
                    return (+a >= +b) || msg;
                case 'gt':
                    return (+a > +b) || msg;
                case 'neq':
                    return (a !== b) || msg;
                default:
                    return (a === b) || msg;
            }
        },

        /**
         * range numbers
         *
         * @example:
            range[0~99]    Number 0-99
            range[0~]      Number greater than or equal to 0
            range[~100]    Number less than or equal to 100
         **/
        range: function(element, params) {
            return this.getRangeMsg(this.value, params);
        },

        /**
         * how many checkbox or radio inputs that checked
         *
         * @example:
            checked;       no empty, same to required
            checked[1~3]   1-3 items
            checked[1~]    greater than 1 item
            checked[~3]    less than 3 items
            checked[3]     3 items
         **/
        checked: function(element, params) {
            if ( !_checkable(element) ) return;

            var me = this,
                elem, count;

            if (element.name) {
                count = me.$el.find('input[name="' + element.name + '"]').filter(function() {
                    var el = this;
                    if (!elem && _checkable(el)) elem = el;
                    return !el.disabled && el.checked;
                }).length;
            } else {
                elem = element;
                count = elem.checked;
            }

            if (params) {
                return me.getRangeMsg(count, params);
            } else {
                return !!count || _getDataMsg(elem, me, '') || me.messages.required;
            }
        },

        /**
         * length of a characters (You can pass the second parameter "true", will calculate the length in bytes)
         *
         * @example:
            length[6~16]        6-16 characters
            length[6~]          Greater than 6 characters
            length[~16]         Less than 16 characters
            length[~16, true]   Less than 16 characters, non-ASCII characters calculating two-character
         **/
        length: function(element, params) {
            var value = this.value,
                len = (params[1] === 'true' ? value.replace(rDoubleBytes, 'xx') : value).length;

            return this.getRangeMsg(len, params, (params[1] ? '_2' : ''));
        },

        /**
         * remote validation
         *
         * @description
         *  remote([get:]url [, name1, [name2 ...]]);
         *  Adaptation three kinds of results (Front for the successful, followed by a failure):
                1. text:
                    ''  'Error Message'
                2. json:
                    {"ok": ""}  {"error": "Error Message"}
                3. json wrapper:
                    {"status": 1, "data": {"ok": ""}}  {"status": 1, "data": {"error": "Error Message"}}
         * @example
            The simplest:       remote(path/to/server);
            With parameters:    remote(path/to/server, name1, name2, ...);
            By GET:             remote(get:path/to/server, name1, name2, ...);
            Name proxy:         remote(path/to/server, name1, proxyname2:name2, proxyname3:#id3, ...)
            Query String        remote(path/to/server, foo=1&bar=2, name1, name2, ...)
         */
        remote: function(element, params) {
            if (!params) return;

            var me = this,
                arr = rAjaxType.exec(params[0]),
                rule = me._rules[me._i],
                data = {},
                queryString = '',
                url = arr[3],
                type = arr[2] || 'POST',            // GET / POST
                rType = (arr[1]||'').toLowerCase(), // CORS / JSONP
                dataType;

            rule.must = true;
            data[element.name] = me.value;

            // There are extra fields
            if (params[1]) {
                $.map(params.slice(1), function(name) {
                    var arr, key;
                    if (~name.indexOf('=')) {
                        queryString += '&' + name;
                    } else {
                        arr = name.split(':');
                        name = trim(arr[0]);
                        key = trim(arr[1]) || name;
                        data[ name ] = me.$el.find( _key2selector(key) ).val();
                    }
                });
            }

            data = $.param(data) + queryString;
            if (!me.must && rule.data && rule.data === data) {
                return rule.result;
            }

            // Cross-domain request, force jsonp dataType
            if (rType !== 'cors' && /^https?:/.test(url) && !~url.indexOf(location.host)) {
                dataType = 'jsonp';
            }

            // Asynchronous validation need return jqXHR objects
            return $.ajax({
                url: url,
                type: type,
                data: data,
                dataType: dataType
            });
        },

        /**
         * filter characters, direct filtration without prompting error (support custom regular expressions)
         *
         * @example
         *  filter          filtering unsafe characters
         *  filter(regexp)  filtering the "regexp" matched characters
         */
        filter: function(element, params) {
            var value = this.value,
                temp = value.replace( params ? (new RegExp("[" + params[0] + "]", "gm")) : rUnsafe, '' );
            if (temp !== value) this.setValue(temp);
        }
    });


    /**
     * Config global options
     *
     * @static  config
     * @param {Object} options
     */
    Validator.config = function(key, value) {
        if (isObject(key)) {
            $.each(key, _config);
        }
        else if (isString(key)) {
            _config(key, value);
        }

        function _config(k, o) {
            if (k === 'rules') {
                new Rules(o);
            }
            else if (k === 'messages') {
                new Messages(o);
            }
            else if (k in fieldDefaults) {
                fieldDefaults[k] = o;
            }
            else {
                defaults[k] = o;
            }
        }
    };

    /**
     * Config themes
     *
     * @static setTheme
     * @param {String|Object} name
     * @param {Object} obj
     * @example
        .setTheme( themeName, themeOptions )
        .setTheme( multiThemes )
     */
    Validator.setTheme = function(name, obj) {
        if ( isObject(name) ) {
            $.extend(true, themes, name);
        }
        else if ( isString(name) && isObject(obj) ) {
            themes[name] = $.extend(themes[name], obj);
        }
    };

    /**
     * Resource loader
     *
     * @static load
     * @param {String} str
     * @example
        .load('local=zh-CN')        // load: local/zh-CN.js and jquery.validator.css
        .load('local=zh-CN&css=')   // load: local/zh-CN.js
        .load('local&css')          // load: local/en.js (set <html lang="en">) and jquery.validator.css
        .load('local')              // dito
     */
    Validator.load = function(str) {
        if (!str) return;
        var doc = document,
            params = {},
            node = doc.scripts[0],
            dir, el, ONLOAD;

        str.replace(/([^?=&]+)=([^&#]*)/g, function(m, key, value){
            params[key] = value;
        });

        dir = params.dir || Validator.dir;

        if (!Validator.css && params.css !== '') {
            el = doc.createElement('link');
            el.rel = 'stylesheet';
            el.href = Validator.css = dir + 'jquery.validator.css';
            node.parentNode.insertBefore(el, node);
        }
        if (!Validator.local && ~str.indexOf('local') && params.local !== '') {
            Validator.local = (params.local || doc.documentElement.lang || 'en').replace('_','-');
            Validator.pending = 1;
            el = doc.createElement('script');
            el.src = dir + 'local/' + Validator.local + '.js';
            ONLOAD = 'onload' in el ? 'onload' : 'onreadystatechange';
            el[ONLOAD] = function() {
                if (!el.readyState || /loaded|complete/.test(el.readyState)) {
                    el = el[ONLOAD] = null;
                    delete Validator.pending;
                    $(window).triggerHandler('validatorready');
                }
            };
            node.parentNode.insertBefore(el, node);
        }
    };

    // Auto loading resources
    (function(){
        var scripts = document.scripts,
            i = scripts.length, node, arr,
            re = /(.*validator(?:\.min)?.js)(\?.*(?:local|css|dir)(?:=[\w\-]*)?)?/;

        while (i-- && !arr) {
            node = scripts[i];
            arr = (node.hasAttribute ? node.src : node.getAttribute('src',4)||'').match(re);
        }

        if (!arr) return;
        Validator.dir = arr[1].split('/').slice(0, -1).join('/')+'/';
        Validator.load(arr[2]);
    })();

    return $[NS] = Validator;
}));

/*********************************
 * Themes, rules, and i18n support
 * Locale: Chinese; 中文
 *********************************/
(function(factory) {
    typeof module === "object" && module.exports ? module.exports = factory( require( "jquery" ) ) :
    typeof define === 'function' && define.amd ? define('validator-lang',['jquery'], factory) :
    factory(jQuery);
}(function($) {

    /* Global configuration
     */
    $.validator.config({
        //stopOnError: true,
        //focusCleanup: true,
        //theme: 'yellow_right',
        //timely: 2,

        // Custom rules
        rules: {
            digits: [/^\d+$/, "请填写数字"]
            ,letters: [/^[a-z]+$/i, "请填写字母"]
            ,date: [/^\d{4}-\d{2}-\d{2}$/, "请填写有效的日期，格式:yyyy-mm-dd"]
            ,time: [/^([01]\d|2[0-3])(:[0-5]\d){1,2}$/, "请填写有效的时间，00:00到23:59之间"]
            ,email: [/^[\w\+\-]+(\.[\w\+\-]+)*@[a-z\d\-]+(\.[a-z\d\-]+)*\.([a-z]{2,4})$/i, "请填写有效的邮箱"]
            ,url: [/^(https?|s?ftp):\/\/\S+$/i, "请填写有效的网址"]
            ,qq: [/^[1-9]\d{4,}$/, "请填写有效的QQ号"]
            ,IDcard: [/^\d{6}(19|2\d)?\d{2}(0[1-9]|1[012])(0[1-9]|[12]\d|3[01])\d{3}(\d|X)?$/, "请填写正确的身份证号码"]
            ,tel: [/^(?:(?:0\d{2,3}[\- ]?[1-9]\d{6,7})|(?:[48]00[\- ]?[1-9]\d{6}))$/, "请填写有效的电话号码"]
            ,mobile: [/^1[3-9]\d{9}$/, "请填写有效的手机号"]
            ,zipcode: [/^\d{6}$/, "请检查邮政编码格式"]
            ,chinese: [/^[\u0391-\uFFE5]+$/, "请填写中文字符"]
            ,username: [/^\w{3,12}$/, "请填写3-12位数字、字母、下划线"]
            // ,username: [/^\w{3,12}$|^[\u4E00-\u9FA5]{1,6}$/, "请填写3-12位数字、字母、下划线或中文"]
            ,nickname: [/^[a-zA-Z0-9_\u4e00-\u9fa5]{3,12}$/, "请填写3-12位数字、字母、下划线、中文"] 
            ,password: [/^[\S]{6,16}$/, "请填写6-16位字符，不能包含空格"]
            ,accept: function (element, params){
                if (!params) return true;
                var ext = params[0],
                    value = $(element).val();
                return (ext === '*') ||
                       (new RegExp(".(?:" + ext + ")$", "i")).test(value) ||
                       this.renderMsg("只接受{1}后缀的文件", ext.replace(/\|/g, ','));
            }
            
        },

        // Default error messages
        messages: {
            0: "此处",
            fallback: "{0}格式不正确",
            loading: "正在验证...",
            error: "网络异常",
            timeout: "请求超时",
            required: "{0}不能为空",
            remote: "{0}已被使用",
            integer: {
                '*': "请填写整数",
                '+': "请填写正整数",
                '+0': "请填写正整数或0",
                '-': "请填写负整数",
                '-0': "请填写负整数或0"
            },
            match: {
                eq: "{0}与{1}不一致",
                neq: "{0}与{1}不能相同",
                lt: "{0}必须小于{1}",
                gt: "{0}必须大于{1}",
                lte: "{0}不能大于{1}",
                gte: "{0}不能小于{1}"
            },
            range: {
                rg: "请填写{1}到{2}的数",
                gte: "请填写不小于{1}的数",
                lte: "请填写最大{1}的数",
                gtlt: "请填写{1}到{2}之间的数",
                gt: "请填写大于{1}的数",
                lt: "请填写小于{1}的数"
            },
            checked: {
                eq: "请选择{1}项",
                rg: "请选择{1}到{2}项",
                gte: "请至少选择{1}项",
                lte: "请最多选择{1}项"
            },
            length: {
                eq: "请填写{1}个字符",
                rg: "请填写{1}到{2}个字符",
                gte: "请至少填写{1}个字符",
                lte: "请最多填写{1}个字符",
                eq_2: "",
                rg_2: "",
                gte_2: "",
                lte_2: ""
            }
        }
    });

    /* Themes
     */
    var TPL_ARROW = '<span class="n-arrow"><b>◆</b><i>◆</i></span>';
    $.validator.setTheme({
        'simple_right': {
            formClass: 'n-simple',
            msgClass: 'n-right'
        },
        'simple_bottom': {
            formClass: 'n-simple',
            msgClass: 'n-bottom'
        },
        'yellow_top': {
            formClass: 'n-yellow',
            msgClass: 'n-top',
            msgArrow: TPL_ARROW
        },
        'yellow_right': {
            formClass: 'n-yellow',
            msgClass: 'n-right',
            msgArrow: TPL_ARROW
        },
        'yellow_right_effect': {
            formClass: 'n-yellow',
            msgClass: 'n-right',
            msgArrow: TPL_ARROW,
            msgShow: function($msgbox, type){
                var $el = $msgbox.children();
                if ($el.is(':animated')) return;
                if (type === 'error') {
                    $el.css({left: '20px', opacity: 0})
                        .delay(100).show().stop()
                        .animate({left: '-4px', opacity: 1}, 150)
                        .animate({left: '3px'}, 80)
                        .animate({left: 0}, 80);
                } else {
                    $el.css({left: 0, opacity: 1}).fadeIn(200);
                }
            },
            msgHide: function($msgbox, type){
                var $el = $msgbox.children();
                $el.stop().delay(100).show()
                    .animate({left: '20px', opacity: 0}, 300, function(){
                        $msgbox.hide();
                    });
            }
        }
    });
}));

define('validator',['validator-core', 'validator-lang'], function (Validator, undefined) {
    return Validator;
});
define('form',['jquery', 'bootstrap', 'upload', 'validator'], function ($, undefined, Upload, Validator) {
    var Form = {
        config: {
        },
        events: {
            validator: function (form, success, error, submit) {
                //绑定表单事件
                form.validator($.extend({
                    validClass: 'has-success',
                    invalidClass: 'has-error',
                    bindClassTo: '.form-group',
                    formClass: 'n-default n-bootstrap',
                    msgClass: 'n-right',
                    stopOnError: true,
                    display: function (elem) {
                        return $(elem).closest('.form-group').find(".control-label").text().replace(/\:/, '');
                    },
                    target: function (input) {
                        var $formitem = $(input).closest('.form-group'),
                                $msgbox = $formitem.find('span.msg-box');
                        if (!$msgbox.length) {
                            return [];
                        }
                        return $msgbox;
                    },
                    valid: function (ret) {
                        //验证通过提交表单
                        Form.api.submit($(ret), function (data, ret) {
                            if (typeof success === 'function') {
                                if (!success.call($(this), data, ret)) {
                                    return false;
                                }
                            }
                            //提示及关闭当前窗口
                            var msg = ret.hasOwnProperty("msg") && ret.msg !== "" ? ret.msg : __('Operation completed');
                            parent.Toastr.success(msg);
                            parent.$(".btn-refresh").trigger("click");
                            var index = parent.Layer.getFrameIndex(window.name);
                            parent.Layer.close(index);
                        }, error, submit);
                        return false;
                    }
                }, form.data("validator-options") || {}));

                //移除提交按钮的disabled类
                $(".layer-footer .btn.disabled", form).removeClass("disabled");
            },
            selectpicker: function (form) {
                //绑定select元素事件
                if ($(".selectpicker", form).size() > 0) {
                    require(['bootstrap-select', 'bootstrap-select-lang'], function () {
                        $('.selectpicker', form).selectpicker();
                    });
                }
            },
            selectpage: function (form) {
                //绑定selectpage元素事件
                if ($(".selectpage", form).size() > 0) {
                    require(['selectpage'], function () {
                        $('.selectpage', form).selectPage({
                            source: 'ajax/selectpage',
                        });
                    });
                    //给隐藏的元素添加上validate验证触发事件
                    $(form).on("change", ".selectpage-input-hidden", function () {
                        $(this).trigger("validate");
                    });
                }
            },
            cxselect: function (form) {
                //绑定cxselect元素事件
                if ($("[data-toggle='cxselect']", form).size() > 0) {
                    require(['cxselect'], function () {
                        $.cxSelect.defaults.jsonName = 'name';
                        $.cxSelect.defaults.jsonValue = 'value';
                        $.cxSelect.defaults.jsonSpace = 'data';
                        $("[data-toggle='cxselect']", form).cxSelect();
                    });
                }
            },
            citypicker: function (form) {
                //绑定城市远程插件
                if ($("[data-toggle='city-picker']", form).size() > 0) {
                    require(['citypicker'], function () {});
                }
            },
            datetimepicker: function (form) {
                //绑定日期时间元素事件
                if ($(".datetimepicker", form).size() > 0) {
                    require(['bootstrap-datetimepicker'], function () {
                        var options = {
                            format: 'YYYY-MM-DD HH:mm:ss',
                            icons: {
                                time: 'fa fa-clock-o',
                                date: 'fa fa-calendar',
                                up: 'fa fa-chevron-up',
                                down: 'fa fa-chevron-down',
                                previous: 'fa fa-chevron-left',
                                next: 'fa fa-chevron-right',
                                today: 'fa fa-history',
                                clear: 'fa fa-trash',
                                close: 'fa fa-remove'
                            },
                            showTodayButton: true,
                            showClose: true
                        };
                        $('.datetimepicker', form).parent().css('position', 'relative');
                        $('.datetimepicker', form).datetimepicker(options);
                    });
                }
            },
            plupload: function (form) {
                //绑定plupload上传元素事件
                if ($(".plupload", form).size() > 0) {
                    Upload.api.plupload();
                }
            },
            faselect: function (form) {
                //绑定fachoose选择附件事件
                if ($(".fachoose", form).size() > 0) {
                    $(document).on('click', ".fachoose", function () {
                        var that = this;
                        var multiple = $(this).data("multiple") ? $(this).data("multiple") : false;
                        var mimetype = $(this).data("mimetype") ? $(this).data("mimetype") : '';
                        parent.Fast.api.open("general/attachment/select?element_id=" + $(this).attr("id") + "&multiple=" + multiple + "&mimetype=" + mimetype, __('Choose'), {
                            callback: function (data) {
                                var input_id = $("#" + $(that).attr("id")).data("input-id");
                                if (data.multiple) {
                                    var urlArr = [];
                                    var inputObj = $("#" + input_id);
                                    if (inputObj.val() !== "") {
                                        urlArr.push(inputObj.val());
                                    }
                                    urlArr.push(data.url);
                                    inputObj.val(urlArr.join(",")).trigger("change");
                                } else {
                                    $("#" + input_id).val(data.url).trigger("change");
                                }
                            }
                        });
                        return false;
                    });
                }
            },
            bindevent: function (form) {

            }
        },
        api: {
            submit: function (form, success, error, submit) {
                if (form.size() === 0)
                    return Toastr.error("表单未初始化完成,无法提交");
                if (typeof submit === 'function') {
                    if (!submit.call(form)) {
                        return false;
                    }
                }
                var type = form.attr("method").toUpperCase();
                type = type && (type === 'GET' || type === 'POST') ? type : 'GET';
                url = form.attr("action");
                url = url ? url : location.href;
                //调用Ajax请求方法
                Fast.api.ajax({
                    type: type,
                    url: url,
                    data: form.serialize(),
                    dataType: 'json'
                }, function (data, ret) {
                    $('.form-group', form).removeClass('has-feedback has-success has-error');
                    if (data && typeof data === 'object' && typeof data.token !== 'undefined') {
                        $("input[name='__token__']", form).val(data.token);
                    }
                    if (typeof success === 'function') {
                        if (!success.call(form, data, ret)) {
                            return false;
                        }
                    }
                }, function (data, ret) {
                    if (data && typeof data === 'object' && typeof data.token !== 'undefined') {
                        $("input[name='__token__']", form).val(data.token);
                    }
                    if (typeof error === 'function') {
                        if (!error.call(form, data, ret)) {
                            return false;
                        }
                    }
                });
                return false;
            },
            bindevent: function (form, success, error, submit) {

                form = typeof form === 'object' ? form : $(form);

                var events = Form.events;

                events.bindevent(form);

                events.validator(form, success, error, submit);

                events.selectpicker(form);

                events.selectpage(form);

                events.cxselect(form);

                events.citypicker(form);

                events.datetimepicker(form);

                events.plupload(form);

                events.faselect(form);
            },
            custom: {}
        },
    };
    return Form;
});
// jQuery List DragSort v0.5.2
// Website: http://dragsort.codeplex.com/
// License: http://dragsort.codeplex.com/license

(function($) {

	$.fn.dragsort = function(options) {
		if (options == "destroy") {
			$(this.selector).trigger("dragsort-uninit");
			return;
		}

		var opts = $.extend({}, $.fn.dragsort.defaults, options);
		var lists = [];
		var list = null, lastPos = null;

		this.each(function(i, cont) {

			//if list container is table, the browser automatically wraps rows in tbody if not specified so change list container to tbody so that children returns rows as user expected
			if ($(cont).is("table") && $(cont).children().size() == 1 && $(cont).children().is("tbody"))
				cont = $(cont).children().get(0);

			var newList = {
				draggedItem: null,
				placeHolderItem: null,
				pos: null,
				offset: null,
				offsetLimit: null,
				scroll: null,
				container: cont,

				init: function() {
					//set options to default values if not set
					opts.tagName = opts.tagName == "" ? ($(this.container).children().size() == 0 ? "li" : $(this.container).children().get(0).tagName.toLowerCase()) : opts.tagName;
					if (opts.itemSelector == "")
						opts.itemSelector = opts.tagName;
					if (opts.dragSelector == "")
						opts.dragSelector = opts.tagName;
					if (opts.placeHolderTemplate == "")
						opts.placeHolderTemplate = "<" + opts.tagName + ">&nbsp;</" + opts.tagName + ">";

					//listidx allows reference back to correct list variable instance
					$(this.container).attr("data-listidx", i).mousedown(this.grabItem).bind("dragsort-uninit", this.uninit);
					this.styleDragHandlers(true);
				},

				uninit: function() {
					var list = lists[$(this).attr("data-listidx")];
					$(list.container).unbind("mousedown", list.grabItem).unbind("dragsort-uninit");
					list.styleDragHandlers(false);
				},

				getItems: function() {
					return $(this.container).children(opts.itemSelector);
				},

				styleDragHandlers: function(cursor) {
					this.getItems().map(function() { return $(this).is(opts.dragSelector) ? this : $(this).find(opts.dragSelector).get(); }).css("cursor", cursor ? "pointer" : "");
				},

				grabItem: function(e) {
					var list = lists[$(this).attr("data-listidx")];
					var item = $(e.target).closest("[data-listidx] > " + opts.tagName).get(0);
					var insideMoveableItem = list.getItems().filter(function() { return this == item; }).size() > 0;

					//if not left click or if clicked on excluded element (e.g. text box) or not a moveable list item return
					if (e.which != 1 || $(e.target).is(opts.dragSelectorExclude) || $(e.target).closest(opts.dragSelectorExclude).size() > 0 || !insideMoveableItem)
						return;

					//prevents selection, stops issue on Fx where dragging hyperlink doesn't work and on IE where it triggers mousemove even though mouse hasn't moved,
					//does also stop being able to click text boxes hence dragging on text boxes by default is disabled in dragSelectorExclude
					//e.preventDefault();

					//change cursor to move while dragging
					var dragHandle = e.target;
					while (!$(dragHandle).is(opts.dragSelector)) {
						if (dragHandle == this) return;
						dragHandle = dragHandle.parentNode;
					}
					$(dragHandle).attr("data-cursor", $(dragHandle).css("cursor"));
					$(dragHandle).css("cursor", "move");

					//on mousedown wait for movement of mouse before triggering dragsort script (dragStart) to allow clicking of hyperlinks to work
					var listElem = this;
					var trigger = function() {
						list.dragStart.call(listElem, e);
						$(list.container).unbind("mousemove", trigger);
					};
					$(list.container).mousemove(trigger).mouseup(function() { $(list.container).unbind("mousemove", trigger); $(dragHandle).css("cursor", $(dragHandle).attr("data-cursor")); });
				},

				dragStart: function(e) {
					if (list != null && list.draggedItem != null)
						list.dropItem();

					list = lists[$(this).attr("data-listidx")];
					list.draggedItem = $(e.target).closest("[data-listidx] > " + opts.tagName)

					//record current position so on dragend we know if the dragged item changed position or not, not using getItems to allow dragsort to restore dragged item to original location in relation to fixed items
					list.draggedItem.attr("data-origpos", $(this).attr("data-listidx") + "-" + $(list.container).children().index(list.draggedItem));

					//calculate mouse offset relative to draggedItem
					var mt = parseInt(list.draggedItem.css("marginTop"));
					var ml = parseInt(list.draggedItem.css("marginLeft"));
					list.offset = list.draggedItem.offset();
					list.offset.top = e.pageY - list.offset.top + (isNaN(mt) ? 0 : mt) - 1;
					list.offset.left = e.pageX - list.offset.left + (isNaN(ml) ? 0 : ml) - 1;

					//calculate box the dragged item can't be dragged outside of
					if (!opts.dragBetween) {
						var containerHeight = $(list.container).outerHeight() == 0 ? Math.max(1, Math.round(0.5 + list.getItems().size() * list.draggedItem.outerWidth() / $(list.container).outerWidth())) * list.draggedItem.outerHeight() : $(list.container).outerHeight();
						list.offsetLimit = $(list.container).offset();
						list.offsetLimit.right = list.offsetLimit.left + $(list.container).outerWidth() - list.draggedItem.outerWidth();
						list.offsetLimit.bottom = list.offsetLimit.top + containerHeight - list.draggedItem.outerHeight();
					}

					//create placeholder item
					var h = list.draggedItem.height();
					var w = list.draggedItem.width();
					if (opts.tagName == "tr") {
						list.draggedItem.children().each(function() { $(this).width($(this).width()); });
						list.placeHolderItem = list.draggedItem.clone().attr("data-placeholder", true);
						list.draggedItem.after(list.placeHolderItem);
						//list.placeHolderItem.children().each(function() { $(this).css({ borderWidth:0, width: $(this).width() + 1, height: $(this).height() + 1 }).html("&nbsp;"); });
						list.placeHolderItem.children().each(function() { $(this).html("&nbsp;"); });
					} else {
						list.draggedItem.after(opts.placeHolderTemplate);
						list.placeHolderItem = list.draggedItem.next().css({ height: h, width: w }).attr("data-placeholder", true);
					}

					if (opts.tagName == "td") {
						var listTable = list.draggedItem.closest("table").get(0);
						$("<table id='" + listTable.id + "' style='border-width: 0px;' class='dragSortItem " + listTable.className + "'><tr></tr></table>").appendTo("body").children().append(list.draggedItem);
					}

					//style draggedItem while dragging
					var orig = list.draggedItem.attr("style");
					list.draggedItem.attr("data-origstyle", orig ? orig : "");
					list.draggedItem.css({ position: "absolute", opacity: 0.8, "z-index": 999, height: h, width: w });

					//auto-scroll setup
					list.scroll = { moveX: 0, moveY: 0, maxX: $(document).width() - $(window).width(), maxY: $(document).height() - $(window).height() };
					list.scroll.scrollY = window.setInterval(function() {
						if (opts.scrollContainer != window) {
							$(opts.scrollContainer).scrollTop($(opts.scrollContainer).scrollTop() + list.scroll.moveY);
							return;
						}
						var t = $(opts.scrollContainer).scrollTop();
						if (list.scroll.moveY > 0 && t < list.scroll.maxY || list.scroll.moveY < 0 && t > 0) {
							$(opts.scrollContainer).scrollTop(t + list.scroll.moveY);
							list.draggedItem.css("top", list.draggedItem.offset().top + list.scroll.moveY + 1);
						}
					}, 10);
					list.scroll.scrollX = window.setInterval(function() {
						if (opts.scrollContainer != window) {
							$(opts.scrollContainer).scrollLeft($(opts.scrollContainer).scrollLeft() + list.scroll.moveX);
							return;
						}
						var l = $(opts.scrollContainer).scrollLeft();
						if (list.scroll.moveX > 0 && l < list.scroll.maxX || list.scroll.moveX < 0 && l > 0) {
							$(opts.scrollContainer).scrollLeft(l + list.scroll.moveX);
							list.draggedItem.css("left", list.draggedItem.offset().left + list.scroll.moveX + 1);
						}
					}, 10);

					//misc
					$(lists).each(function(i, l) { l.createDropTargets(); l.buildPositionTable(); });
					list.setPos(e.pageX, e.pageY);
					$(document).bind("mousemove", list.swapItems);
					$(document).bind("mouseup", list.dropItem);
					if (opts.scrollContainer != window)
						$(window).bind("wheel", list.wheel);
				},

				//set position of draggedItem
				setPos: function(x, y) { 
					//remove mouse offset so mouse cursor remains in same place on draggedItem instead of top left corner
					var top = y - this.offset.top;
					var left = x - this.offset.left;

					//limit top, left to within box draggedItem can't be dragged outside of
					if (!opts.dragBetween) {
						top = Math.min(this.offsetLimit.bottom, Math.max(top, this.offsetLimit.top));
						left = Math.min(this.offsetLimit.right, Math.max(left, this.offsetLimit.left));
					}

					//adjust top & left calculations to parent offset
					var parent = this.draggedItem.offsetParent().not("body").offset(); //offsetParent returns body even when it's static, if not static offset is only factoring margin
					if (parent != null) {
						top -= parent.top;
						left -= parent.left;
					}

					//set x or y auto-scroll amount
					if (opts.scrollContainer == window) {
						y -= $(window).scrollTop();
						x -= $(window).scrollLeft();
						y = Math.max(0, y - $(window).height() + 5) + Math.min(0, y - 5);
						x = Math.max(0, x - $(window).width() + 5) + Math.min(0, x - 5);
					} else {
						var cont = $(opts.scrollContainer);
						var offset = cont.offset();
						y = Math.max(0, y - cont.height() - offset.top) + Math.min(0, y - offset.top);
						x = Math.max(0, x - cont.width() - offset.left) + Math.min(0, x - offset.left);
					}
					
					list.scroll.moveX = x == 0 ? 0 : x * opts.scrollSpeed / Math.abs(x);
					list.scroll.moveY = y == 0 ? 0 : y * opts.scrollSpeed / Math.abs(y);

					//move draggedItem to new mouse cursor location
					this.draggedItem.css({ top: top, left: left });
				},

				//if scroll container is a div allow mouse wheel to scroll div instead of window when mouse is hovering over
				wheel: function(e) {
					if (list && opts.scrollContainer != window) {
						var cont = $(opts.scrollContainer);
						var offset = cont.offset();
						e = e.originalEvent;
						if (e.clientX > offset.left && e.clientX < offset.left + cont.width() && e.clientY > offset.top && e.clientY < offset.top + cont.height()) {
							var deltaY = (e.deltaMode == 0 ? 1 : 10) * e.deltaY;
							cont.scrollTop(cont.scrollTop() + deltaY);
							e.preventDefault();
						}
					}
				},

				//build a table recording all the positions of the moveable list items
				buildPositionTable: function() {
					var pos = [];
					this.getItems().not([list.draggedItem[0], list.placeHolderItem[0]]).each(function(i) {
						var loc = $(this).offset();
						loc.right = loc.left + $(this).outerWidth();
						loc.bottom = loc.top + $(this).outerHeight();
						loc.elm = this;
						pos[i] = loc;
					});
					this.pos = pos;
				},

				dropItem: function() {
					if (list.draggedItem == null)
						return;

					//list.draggedItem.attr("style", "") doesn't work on IE8 and jQuery 1.5 or lower
					//list.draggedItem.removeAttr("style") doesn't work on chrome and jQuery 1.6 (works jQuery 1.5 or lower)
					var orig = list.draggedItem.attr("data-origstyle");
					list.draggedItem.attr("style", orig);
					if (orig == "")
						list.draggedItem.removeAttr("style");
					list.draggedItem.removeAttr("data-origstyle");

					list.styleDragHandlers(true);

					list.placeHolderItem.before(list.draggedItem);
					list.placeHolderItem.remove();

					$("[data-droptarget], .dragSortItem").remove();

					window.clearInterval(list.scroll.scrollY);
					window.clearInterval(list.scroll.scrollX);

					//if position changed call dragEnd
					if (list.draggedItem.attr("data-origpos") != $(lists).index(list) + "-" + $(list.container).children().index(list.draggedItem))
						if (opts.dragEnd.apply(list.draggedItem) == false) { //if dragEnd returns false revert order
							var pos = list.draggedItem.attr("data-origpos").split('-');
							var nextItem = $(lists[pos[0]].container).children().not(list.draggedItem).eq(pos[1]);
							if (nextItem.size() > 0)
								nextItem.before(list.draggedItem);
							else if (pos[1] == 0) //was the only item in list
								$(lists[pos[0]].container).prepend(list.draggedItem);
							else //was the last item in list
								$(lists[pos[0]].container).append(list.draggedItem);
						}
					list.draggedItem.removeAttr("data-origpos");

					list.draggedItem = null;
					$(document).unbind("mousemove", list.swapItems);
					$(document).unbind("mouseup", list.dropItem);
					if (opts.scrollContainer != window)
						$(window).unbind("wheel", list.wheel);
					return false;
				},

				//swap the draggedItem (represented visually by placeholder) with the list item the it has been dragged on top of
				swapItems: function(e) {
					if (list.draggedItem == null)
						return false;

					//move draggedItem to mouse location
					list.setPos(e.pageX, e.pageY);

					//retrieve list and item position mouse cursor is over
					var ei = list.findPos(e.pageX, e.pageY);
					var nlist = list;
					for (var i = 0; ei == -1 && opts.dragBetween && i < lists.length; i++) {
						ei = lists[i].findPos(e.pageX, e.pageY);
						nlist = lists[i];
					}

					//if not over another moveable list item return
					if (ei == -1)
						return false;

					//save fixed items locations
					var children = function() { return $(nlist.container).children().not(nlist.draggedItem); };
					var fixed = children().not(opts.itemSelector).each(function(i) { this.idx = children().index(this); });

					//if moving draggedItem up or left place placeHolder before list item the dragged item is hovering over otherwise place it after
					if (lastPos == null || lastPos.top > list.draggedItem.offset().top || lastPos.left > list.draggedItem.offset().left)
						$(nlist.pos[ei].elm).before(list.placeHolderItem);
					else
						$(nlist.pos[ei].elm).after(list.placeHolderItem);

					//restore fixed items location
					fixed.each(function() {
						var elm = children().eq(this.idx).get(0);
						if (this != elm && children().index(this) < this.idx)
							$(this).insertAfter(elm);
						else if (this != elm)
							$(this).insertBefore(elm);
					});

					//misc
					$(lists).each(function(i, l) { l.createDropTargets(); l.buildPositionTable(); });
					lastPos = list.draggedItem.offset();
					return false;
				},

				//returns the index of the list item the mouse is over
				findPos: function(x, y) {
					for (var i = 0; i < this.pos.length; i++) {
						if (this.pos[i].left < x && this.pos[i].right > x && this.pos[i].top < y && this.pos[i].bottom > y)
							return i;
					}
					return -1;
				},

				//create drop targets which are placeholders at the end of other lists to allow dragging straight to the last position
				createDropTargets: function() {
					if (!opts.dragBetween)
						return;

					$(lists).each(function() {
						var ph = $(this.container).find("[data-placeholder]");
						var dt = $(this.container).find("[data-droptarget]");
						if (ph.size() > 0 && dt.size() > 0)
							dt.remove();
						else if (ph.size() == 0 && dt.size() == 0) {
							if (opts.tagName == "td")
								$(opts.placeHolderTemplate).attr("data-droptarget", true).appendTo(this.container);
							else
								//list.placeHolderItem.clone().removeAttr("data-placeholder") crashes in IE7 and jquery 1.5.1 (doesn't in jquery 1.4.2 or IE8)
								$(this.container).append(list.placeHolderItem.removeAttr("data-placeholder").clone().attr("data-droptarget", true));
							
							list.placeHolderItem.attr("data-placeholder", true);
						}
					});
				}
			};

			newList.init();
			lists.push(newList);
		});

		return this;
	};

	$.fn.dragsort.defaults = {
                tagName:"",
		itemSelector: "",
		dragSelector: "",
		dragSelectorExclude: "input, textarea",
		dragEnd: function() { },
		dragBetween: false,
		placeHolderTemplate: "",
		scrollContainer: window,
		scrollSpeed: 5
	};

})(jQuery);

define("dragsort", function(){});

/*! 
 * jquery.event.drag - v 2.2
 * Copyright (c) 2010 Three Dub Media - http://threedubmedia.com
 * Open Source MIT License - http://threedubmedia.com/code/license
 */
;(function(e){e.fn.drag=function(k,g,j){var i=typeof k=="string"?k:"",h=e.isFunction(k)?k:e.isFunction(g)?g:null;if(i.indexOf("drag")!==0){i="drag"+i}j=(k==h?g:j)||{};return h?this.bind(i,j,h):this.trigger(i)};var b=e.event,a=b.special,d=a.drag={defaults:{which:1,distance:0,not:":input",handle:null,relative:false,drop:true,click:false},datakey:"dragdata",noBubble:true,add:function(i){var h=e.data(this,d.datakey),g=i.data||{};h.related+=1;e.each(d.defaults,function(j,k){if(g[j]!==undefined){h[j]=g[j]}})},remove:function(){e.data(this,d.datakey).related-=1},setup:function(){if(e.data(this,d.datakey)){return}var g=e.extend({related:0},d.defaults);e.data(this,d.datakey,g);b.add(this,"touchstart mousedown",d.init,g);if(this.attachEvent){this.attachEvent("ondragstart",d.dontstart)}},teardown:function(){var g=e.data(this,d.datakey)||{};if(g.related){return}e.removeData(this,d.datakey);b.remove(this,"touchstart mousedown",d.init);d.textselect(true);if(this.detachEvent){this.detachEvent("ondragstart",d.dontstart)}},init:function(i){if(d.touched){return}var g=i.data,h;if(i.which!=0&&g.which>0&&i.which!=g.which){return}if(e(i.target).is(g.not)){return}if(g.handle&&!e(i.target).closest(g.handle,i.currentTarget).length){return}d.touched=i.type=="touchstart"?this:null;g.propagates=1;g.mousedown=this;g.interactions=[d.interaction(this,g)];g.target=i.target;g.pageX=i.pageX;g.pageY=i.pageY;g.dragging=null;h=d.hijack(i,"draginit",g);if(!g.propagates){return}h=d.flatten(h);if(h&&h.length){g.interactions=[];e.each(h,function(){g.interactions.push(d.interaction(this,g))})}g.propagates=g.interactions.length;if(g.drop!==false&&a.drop){a.drop.handler(i,g)}d.textselect(false);if(d.touched){b.add(d.touched,"touchmove touchend",d.handler,g)}else{b.add(document,"mousemove mouseup",d.handler,g)}if(!d.touched||g.live){return false}},interaction:function(h,g){var i=e(h)[g.relative?"position":"offset"]()||{top:0,left:0};return{drag:h,callback:new d.callback(),droppable:[],offset:i}},handler:function(h){var g=h.data;switch(h.type){case !g.dragging&&"touchmove":h.preventDefault();case !g.dragging&&"mousemove":if(Math.pow(h.pageX-g.pageX,2)+Math.pow(h.pageY-g.pageY,2)<Math.pow(g.distance,2)){break}h.target=g.target;d.hijack(h,"dragstart",g);if(g.propagates){g.dragging=true}case"touchmove":h.preventDefault();case"mousemove":if(g.dragging){d.hijack(h,"drag",g);if(g.propagates){if(g.drop!==false&&a.drop){a.drop.handler(h,g)}break}h.type="mouseup"}case"touchend":case"mouseup":default:if(d.touched){b.remove(d.touched,"touchmove touchend",d.handler)}else{b.remove(document,"mousemove mouseup",d.handler)}if(g.dragging){if(g.drop!==false&&a.drop){a.drop.handler(h,g)}d.hijack(h,"dragend",g)}d.textselect(true);if(g.click===false&&g.dragging){e.data(g.mousedown,"suppress.click",new Date().getTime()+5)}g.dragging=d.touched=false;break}},hijack:function(h,o,r,p,k){if(!r){return}var q={event:h.originalEvent,type:h.type},m=o.indexOf("drop")?"drag":"drop",t,l=p||0,j,g,s,n=!isNaN(p)?p:r.interactions.length;h.type=o;h.originalEvent=null;r.results=[];do{if(j=r.interactions[l]){if(o!=="dragend"&&j.cancelled){continue}s=d.properties(h,r,j);j.results=[];e(k||j[m]||r.droppable).each(function(u,i){s.target=i;h.isPropagationStopped=function(){return false};t=i?b.dispatch.call(i,h,s):null;if(t===false){if(m=="drag"){j.cancelled=true;r.propagates-=1}if(o=="drop"){j[m][u]=null}}else{if(o=="dropinit"){j.droppable.push(d.element(t)||i)}}if(o=="dragstart"){j.proxy=e(d.element(t)||j.drag)[0]}j.results.push(t);delete h.result;if(o!=="dropinit"){return t}});r.results[l]=d.flatten(j.results);if(o=="dropinit"){j.droppable=d.flatten(j.droppable)}if(o=="dragstart"&&!j.cancelled){s.update()}}}while(++l<n);h.type=q.type;h.originalEvent=q.event;return d.flatten(r.results)},properties:function(i,g,h){var j=h.callback;j.drag=h.drag;j.proxy=h.proxy||h.drag;j.startX=g.pageX;j.startY=g.pageY;j.deltaX=i.pageX-g.pageX;j.deltaY=i.pageY-g.pageY;j.originalX=h.offset.left;j.originalY=h.offset.top;j.offsetX=j.originalX+j.deltaX;j.offsetY=j.originalY+j.deltaY;j.drop=d.flatten((h.drop||[]).slice());j.available=d.flatten((h.droppable||[]).slice());return j},element:function(g){if(g&&(g.jquery||g.nodeType==1)){return g}},flatten:function(g){return e.map(g,function(h){return h&&h.jquery?e.makeArray(h):h&&h.length?d.flatten(h):h})},textselect:function(g){e(document)[g?"unbind":"bind"]("selectstart",d.dontstart).css("MozUserSelect",g?"":"none");document.unselectable=g?"off":"on"},dontstart:function(){return false},callback:function(){}};d.callback.prototype={update:function(){if(a.drop&&this.available.length){e.each(this.available,function(g){a.drop.locate(this,g)})}}};var f=b.dispatch;b.dispatch=function(g){if(e.data(this,"suppress."+g.type)-new Date().getTime()>0){e.removeData(this,"suppress."+g.type);return}return f.apply(this,arguments)};var c=b.fixHooks.touchstart=b.fixHooks.touchmove=b.fixHooks.touchend=b.fixHooks.touchcancel={props:"clientX clientY pageX pageY screenX screenY".split(" "),filter:function(h,i){if(i){var g=(i.touches&&i.touches[0])||(i.changedTouches&&i.changedTouches[0])||null;if(g){e.each(c.props,function(j,k){h[k]=g[k]})}}return h}};a.draginit=a.dragstart=a.dragend=d})(jQuery);
define("drag", function(){});

/*! 
 * jquery.event.drop - v 2.2
 * Copyright (c) 2010 Three Dub Media - http://threedubmedia.com
 * Open Source MIT License - http://threedubmedia.com/code/license
 */
;(function(d){d.fn.drop=function(i,e,h){var g=typeof i=="string"?i:"",f=d.isFunction(i)?i:d.isFunction(e)?e:null;if(g.indexOf("drop")!==0){g="drop"+g}h=(i==f?e:h)||{};return f?this.bind(g,h,f):this.trigger(g)};d.drop=function(e){e=e||{};b.multi=e.multi===true?Infinity:e.multi===false?1:!isNaN(e.multi)?e.multi:b.multi;b.delay=e.delay||b.delay;b.tolerance=d.isFunction(e.tolerance)?e.tolerance:e.tolerance===null?null:b.tolerance;b.mode=e.mode||b.mode||"intersect"};var c=d.event,a=c.special,b=d.event.special.drop={multi:1,delay:20,mode:"overlap",targets:[],datakey:"dropdata",noBubble:true,add:function(f){var e=d.data(this,b.datakey);e.related+=1},remove:function(){d.data(this,b.datakey).related-=1},setup:function(){if(d.data(this,b.datakey)){return}var e={related:0,active:[],anyactive:0,winner:0,location:{}};d.data(this,b.datakey,e);b.targets.push(this);return false},teardown:function(){var f=d.data(this,b.datakey)||{};if(f.related){return}d.removeData(this,b.datakey);var e=this;b.targets=d.grep(b.targets,function(g){return(g!==e)})},handler:function(g,e){var f,h;if(!e){return}switch(g.type){case"mousedown":case"touchstart":h=d(b.targets);if(typeof e.drop=="string"){h=h.filter(e.drop)}h.each(function(){var i=d.data(this,b.datakey);i.active=[];i.anyactive=0;i.winner=0});e.droppable=h;a.drag.hijack(g,"dropinit",e);break;case"mousemove":case"touchmove":b.event=g;if(!b.timer){b.tolerate(e)}break;case"mouseup":case"touchend":b.timer=clearTimeout(b.timer);if(e.propagates){a.drag.hijack(g,"drop",e);a.drag.hijack(g,"dropend",e)}break}},locate:function(k,h){var l=d.data(k,b.datakey),g=d(k),i=g.offset()||{},e=g.outerHeight(),j=g.outerWidth(),f={elem:k,width:j,height:e,top:i.top,left:i.left,right:i.left+j,bottom:i.top+e};if(l){l.location=f;l.index=h;l.elem=k}return f},contains:function(e,f){return((f[0]||f.left)>=e.left&&(f[0]||f.right)<=e.right&&(f[1]||f.top)>=e.top&&(f[1]||f.bottom)<=e.bottom)},modes:{intersect:function(f,e,g){return this.contains(g,[f.pageX,f.pageY])?1000000000:this.modes.overlap.apply(this,arguments)},overlap:function(f,e,g){return Math.max(0,Math.min(g.bottom,e.bottom)-Math.max(g.top,e.top))*Math.max(0,Math.min(g.right,e.right)-Math.max(g.left,e.left))},fit:function(f,e,g){return this.contains(g,e)?1:0},middle:function(f,e,g){return this.contains(g,[e.left+e.width*0.5,e.top+e.height*0.5])?1:0}},sort:function(f,e){return(e.winner-f.winner)||(f.index-e.index)},tolerate:function(q){var k,e,n,j,l,m,g,p=0,f,h=q.interactions.length,r=[b.event.pageX,b.event.pageY],o=b.tolerance||b.modes[b.mode];do{if(f=q.interactions[p]){if(!f){return}f.drop=[];l=[];m=f.droppable.length;if(o){n=b.locate(f.proxy)}k=0;do{if(g=f.droppable[k]){j=d.data(g,b.datakey);e=j.location;if(!e){continue}j.winner=o?o.call(b,b.event,n,e):b.contains(e,r)?1:0;l.push(j)}}while(++k<m);l.sort(b.sort);k=0;do{if(j=l[k]){if(j.winner&&f.drop.length<b.multi){if(!j.active[p]&&!j.anyactive){if(a.drag.hijack(b.event,"dropstart",q,p,j.elem)[0]!==false){j.active[p]=1;j.anyactive+=1}else{j.winner=0}}if(j.winner){f.drop.push(j.elem)}}else{if(j.active[p]&&j.anyactive==1){a.drag.hijack(b.event,"dropend",q,p,j.elem);j.active[p]=0;j.anyactive-=1}}}}while(++k<m)}}while(++p<h);if(b.last&&r[0]==b.last.pageX&&r[1]==b.last.pageY){delete b.timer}else{b.timer=setTimeout(function(){b.tolerate(q)},b.delay)}b.last=b.event}};a.dropinit=a.dropstart=a.dropend=b})(jQuery);
define("drop", function(){});

/**
 * http://git.oschina.net/hbbcs/bootStrap-addTabs
 * Created by joe on 2015-12-19.
 * @param {type} options {
 * content string||html 直接指定内容
 * close bool 是否可以关闭
 * monitor 监视的区域
 * }
 *
 * @returns
 */
$.fn.addtabs = function (options) {
    var obj = $(this);
    options = $.extend({
        content: '', //直接指定所有页面TABS内容
        close: true, //是否可以关闭
        monitor: 'body', //监视的区域
        nav: '.nav-addtabs',
        tab: '.tab-addtabs',
        iframeUse: true, //使用iframe还是ajax
        iframeHeight: $(window).height() - 50, //固定TAB中IFRAME高度,根据需要自己修改
        callback: function () {
            //关闭后回调函数
        }
    }, options || {});
    var navobj = $(options.nav);
    var tabobj = $(options.tab);
    if (history.pushState) {
        //浏览器前进后退事件
        $(window).on("popstate", function (e) {
            var state = e.originalEvent.state;
            if (state) {
                $("a[addtabs=" + state.id + "]", options.monitor).data("pushstate", true).trigger("click");
            }
        });
    }
    $(options.monitor).on('click', '[addtabs]', function (e) {
        if ($(this).attr('url').indexOf("javascript") !== 0) {
            if ($(this).is("a")) {
                e.preventDefault();
            }
            var id = $(this).attr('addtabs');
            var title = $(this).attr('title') ? $(this).attr('title') : $.trim($(this).text());
            var url = $(this).attr('url');
            var content = options.content ? options.content : $(this).attr('content');
            var ajax = $(this).attr('ajax') ? true : false;
            var state = ({
                url: url, title: title, id: id, content: content, ajax: ajax
            });

            document.title = title;
            if (history.pushState && !$(this).data("pushstate")) {
                var pushurl = url.indexOf("ref=addtabs") == -1 ? (url + (url.indexOf("?") > -1 ? "&" : "?") + "ref=addtabs") : url;
                window.history.pushState(state, title, pushurl);
            }
            $(this).data("pushstate", null);
            _add({
                id: id,
                title: $(this).attr('title') ? $(this).attr('title') : $(this).html(),
                content: content,
                url: url,
                ajax: ajax
            });
        }
    });

    navobj.on('click', '.close-tab', function (e) {
        id = $(this).prev("a").attr("aria-controls");
        _close(id);
        return false;
    });
    navobj.on('dblclick', 'li[role=presentation]', function (e) {
        $(this).find(".close-tab").trigger("click");
    });
    navobj.on('click', 'li[role=presentation]', function (e) {
        $("a[addtabs=" + $("a", this).attr("node-id") + "]").trigger("click");
    });

    $(window).resize(function () {
        $("#nav").width($("#header > .navbar").width() - $(".sidebar-toggle").outerWidth() - $(".navbar-custom-menu").outerWidth() - 20);
        _drop();
    });

    _add = function (opts) {
        id = 'tab_' + opts.id;
        url = opts.url;
        url += (opts.url.indexOf("?") > -1 ? "&addtabs=1" : "?addtabs=1");
        navobj.find("[role='presentation']").removeClass('active');
        tabobj.find("[role='tabpanel']").removeClass('active');
        //如果TAB不存在，创建一个新的TAB
        if ($("#" + id).size() == 0) {
            //创建新TAB的title
            title = $('<li role="presentation" id="tab_' + id + '"><a href="#' + id + '" node-id="' + opts.id + '" aria-controls="' + id + '" role="tab" data-toggle="tab">' + opts.title + '</a></li>');
            //是否允许关闭
            if (options.close && $("li", navobj).size() > 0) {
                title.append(' <i class="close-tab fa fa-remove"></i>');
            }
            //创建新TAB的内容
            content = $('<div role="tabpanel" class="tab-pane" id="' + id + '"></div>');
            //是否指定TAB内容
            if (opts.content) {
                content.append(opts.content);
            } else if (options.iframeUse && !opts.ajax) {//没有内容，使用IFRAME打开链接
                var height = options.iframeHeight;
                content.append('<iframe src="' + url + '" width="100%" height="' + height + '" frameborder="no" border="0" marginwidth="0" marginheight="0" scrolling-x="no" scrolling-y="auto" allowtransparency="yes"></iframe></div>');
            } else {
                $.get(url, function (data) {
                    content.append(data);
                });
            }
            //加入TABS
            if ($('.tabdrop li').size() > 0) {
                $('.tabdrop ul').append(title);
            } else {
                navobj.append(title);
            }
            tabobj.append(content);
        }

        //激活TAB
        $("#tab_" + id).addClass('active');
        $("#" + id).addClass("active");
        _drop();
    };

    _close = function (id) {
        //如果关闭的是当前激活的TAB，激活他的前一个TAB
        if (obj.find("li.active").attr('id') == "tab_" + id) {
            if ($("#tab_" + id).prev().not(".tabdrop").size() > 0) {
                $("#tab_" + id).prev().not(".tabdrop").find("a").trigger("click");
            } else if ($("#tab_" + id).next().size() > 0) {
                $("#tab_" + id).next().trigger("click");
            }
        }
        //关闭TAB
        $("#tab_" + id).remove();
        $("#" + id).remove();
        _drop();
        options.callback();
    };

    _drop = function () {
        //创建下拉标签
        var dropdown = $('<li class="dropdown pull-right hide tabdrop"><a class="dropdown-toggle" data-toggle="dropdown" href="javascript:;">' +
                '<i class="glyphicon glyphicon-align-justify"></i>' +
                ' <b class="caret"></b></a><ul class="dropdown-menu"></ul></li>');
        //检测是否已增加
        if (!$('.tabdrop').html()) {
            dropdown.prependTo(navobj);
        } else {
            dropdown = navobj.find('.tabdrop');
        }
        //检测是否有下拉样式
        if (navobj.parent().is('.tabs-below')) {
            dropdown.addClass('dropup');
        }
        var collection = 0;

        var maxwidth = navobj.width() - 60;

        var liwidth = 0;
        //检查超过一行的标签页
        var litabs = navobj.append(dropdown.find('li')).find('>li').not('.tabdrop');
        var lisize = litabs.size();
        litabs.each(function (i, j) {
            liwidth += $(this).width();
            if (collection == 0 && i == lisize - 1 && liwidth <= navobj.width()) {
                return true;
            }
            if (liwidth > maxwidth) {
                dropdown.find('ul').append($(this));
                collection++;
            }
        });
        //如果有超出的，显示下拉标签
        if (collection > 0) {
            dropdown.removeClass('hide');
            if (dropdown.find('.active').length == 1) {
                dropdown.addClass('active');
            } else {
                dropdown.removeClass('active');
            }
        } else {
            dropdown.addClass('hide');
        }
    };
};

define("addtabs", function(){});

/**
 * @file jQuery Plugin: SelectPage
 * @version 0.1
 * @author TerryZeng
 * @license MIT License
 * 
 * 插件的部分功能使用、借鉴了
 * jQuery Plugin: jquery.ajax-combobox
 * 作者：Yuusaku Miyazaki <toumin.m7@gmail.com>(宮崎 雄策)
 * 
 */
;
(function (factory) {
    if (typeof define === "function" && define.amd) {
        // AMD模式
        define('selectpage',["jquery"], factory);
    } else {
        // 全局模式
        factory(jQuery);
    }
}(function ($) {
    "use strict";
    /**
     * 内部使用常量
     */
    var constants = {
        dataKey: 'selectPageObject', //插件缓存内部对象的KEY
        objStatusKey: 'selectPage-self-mark', //全局范围设置当前点击是否为插件自身的标识
        objStatusIndex: 'selectPage-self-index'          //全局范围设置当前点击的selectpage的索引下标
    };
    /**
     * @desc 下拉分页查询控件初始化入口
     * @global
     * @memberof jQuery
     * @param {string|Object} source - 数据源（String：Ajax查询的URL|Object：JSON格式的数据源）
     * @param {Object} option - 初始化参数集
     * 
     * 
     * option参数集内容
     * @param {boolean} [option.instance] - 是否返回插件内部对象（true：返回内部对象，false：返回输入框自身）
     * @param {string} [option.lang='cn'] - 插件显示语言 ('en','cn'等)
     * @param {boolean} [option.multiple] - 是否为多选模式（多选模式下，选中的项目将会以Tag标签的形式展现）
     * @param {string} [option.field='name'] - 结果集中用于显示的属性名
     * @param {string} [option.search_field=option.field] - 使用ajax作为数据源情况下的查询字段指定。 (e.g.: 'id, name, job')
     * @param {string|Array} [option.order_by=option.search_field] - 排序字段指定，若不指定则自动使用查询字段 (e.g.: 'name DESC' or ['name ASC', 'age DESC'])
     * @param {number} [option.per_page=10] - 每页显示的记录数
     * @param {string} [option.init_record=false] - 插件初始值指定，该值会与option.primary_key字段进行匹配，若匹配到，则自动设置选中并高亮
     * @param {string} [option.bind_to] - 指定事件名称，用于在选择项目后进行触发（必须已自行绑定好事件）
     * @param {string} [option.and_or='AND'] - 查询方式 ('AND' or 'OR')
     * @param {boolean} [option.select_only=false] - 仅选择，不能输入查询关键字
     * @param {string} [option.primary_key='id'] - 值字段，通常该字段的内容会自动保存在隐藏域中
     */
    $.fn.selectPage = function (option) {
        var arr = [];
        this.each(function () {
            arr.push(new SelectPage(this, option));
        });
        return (option !== undefined && option.instance !== undefined && option.instance) ? $(arr) : this;
    };

    /**
     * @global
     * @constructor
     * @classdesc 插件初始化
     * @param {Object} combo_input - 插件的初始化输入框元素。
     * @param {string|Object} source - 数据来源
     *         string：服务端请求的URL地址
     *         Object：JSON格式数组，推荐格式：[{a:1,b:2,c:3},{...}]
     * @param {Object} option - 初始化参数
     */
    function SelectPage(combo_input, option) {
        var option = $.extend({}, option, $(combo_input).data());
        option = this._setCamelcaseToUnderscore(option);
        this._setOption(option);
        this._setLanguage();
        this._setCssClass();
        this._setProp();
        this._setElem(combo_input, option);

        this._setButtonAttrDefault();
        this._setInitRecord();

        this._ehButton();
        this._ehComboInput();
        this._ehWhole();

        //缓存内部对象
        $(this.elem.container).data(constants.dataKey, this);
    }

    /**
     * 内部函数定义
     */
    $.extend(SelectPage.prototype, {
        /**
         * @private
         * @desc 参数初始化
         * @param {string|Object} source - 数据源
         * @param {Object} option - 参数集
         */
        _setOption: function (option) {
            option = this._setOption1st(option);
            option = this._setOption2nd(option);
            this.option = option;
        },

        /**
         * @private
         * @desc 参数使用默认值进行初始化与合并
         * @param {Object} option - 初始化参数
         * @return {Object} - 合并后的参数
         */
        _setOption1st: function (option) {
            option = $.extend({
                lang: 'cn',
                multiple: false,
                init_record: false,
                field: 'name',
                and_or: 'AND',
                per_page: 10,
                primary_key: 'id',
                bind_to: false,
                /**
                 * 是否在输入框获得焦点时，展开下拉窗口
                 * 默认:true
                 */
                focus_drop_list: true,
                /**
                 * 是否自动选择列表中的第一项内容(输入关键字查询模式，直接使用鼠标下拉并不触发)
                 * 默认：false
                 */
                auto_select_first: false,
                /**
                 * 是否自动填充内容
                 * 若有列表项目被高亮显示，在焦点离开控件后，自动设置该项为选中内容
                 * 默认：false
                 */
                auto_fill_result: false,
                /**
                 * 是否清空输入关键字
                 * 在输入框中输入内容进行查询，但没有匹配的内容返回，在焦点离开控件后，自动清空输入框输入的内容
                 * 默认：false
                 */
                no_result_clean: false,
                /**
                 * 列表项目显示内容格式化
                 * 参数类型：function
                 * 参数1：data，行数据object格式
                 * 返回：string
                 */
                format_item: false,
                /**
                 * 是否使用Formatter的text文本来填充文本框
                 */
                format_fill: false,
                /**
                 * 只选择模式
                 */
                select_only: true,
            }, option);
            //特殊字段处理
            $.each({data: 'source', key_field: 'primary_key', show_field: 'field', init_code: 'init_record', page_size: 'per_page'}, function (i, j) {
                if (typeof option[i] !== 'undefined') {
                    option[j] = option[i];
                    delete option[i];
                }
            });
            return option;
        },

        /**
         * @private
         * @desc 参数初始化后的调整
         * @param {Object} option - 参数集
         * @return {Object} - 处理后的参数集
         */
        _setOption2nd: function (option) {
            //若没有设置搜索字段，则使用显示字段作为搜索字段
            option.search_field = (option.search_field === undefined) ? option.field : option.search_field;

            //统一大写
            option.and_or = option.and_or.toUpperCase();

            var arr = ['hide_field', 'show_field', 'search_field'];
            for (var i = 0; i < arr.length; i++) {
                option[arr[i]] = this._strToArray(option[arr[i]]);
            }

            //设置排序字段
            option.order_by = (option.order_by === undefined) ? option.search_field : option.order_by;

            //设置多字段排序
            //例:  [ ['id', 'ASC'], ['name', 'DESC'] ]
            option.order_by = this._setOrderbyOption(option.order_by, option.field);

            return option;
        },

        /**
         * 设置峰驼至下划线
         * @param {object} option
         * @returns {object}
         */
        _setCamelcaseToUnderscore: function (option) {
            $.each(option, function (i, j) {
                var field = i.replace(/([a-z])([A-Z])/g, '$1_$2').toLowerCase();
                if (field !== i) {
                    option[field] = j;
                    delete option[i];
                }
            });
            return option;
        },

        /**
         * @private
         * @desc 字符串转换为数组
         * @param {string} str - 字符串
         * @return {Array} - 数组
         */
        _strToArray: function (str) {
            if (!str)
                return '';
            return str.replace(/[\s　]+/g, '').split(',');
        },

        /**
         * @private
         * @desc 设置多字段排序
         * @param {Array} arg_order - 排序顺序
         * @param {string} arg_field - 字段
         * @return {Array} - 处理后的排序字段内容
         */
        _setOrderbyOption: function (arg_order, arg_field) {
            var arr = [], orders = [];
            if (typeof arg_order == 'object') {
                for (var i = 0; i < arg_order.length; i++) {
                    orders = $.trim(arg_order[i]).split(' ');
                    arr[i] = (orders.length == 2) ? orders : [orders[0], 'ASC'];
                }
            } else {
                orders = $.trim(arg_order).split(' ');
                arr[0] = (orders.length == 2) ? orders : (orders[0].match(/^(ASC|DESC)$/i)) ? [arg_field, orders[0]] : [orders[0], 'ASC'];
            }
            return arr;
        },

        /**
         * @private
         * @desc 界面文字国际化
         */
        _setLanguage: function () {
            var message;
            switch (this.option.lang) {
                // English
                case 'en':
                    message = {
                        add_btn: 'Add button',
                        add_title: 'add a box',
                        del_btn: 'Del button',
                        del_title: 'delete a box',
                        next: 'Next',
                        next_title: 'Next' + this.option.per_page + ' (Right key)',
                        prev: 'Prev',
                        prev_title: 'Prev' + this.option.per_page + ' (Left key)',
                        first_title: 'First (Shift + Left key)',
                        last_title: 'Last (Shift + Right key)',
                        get_all_btn: 'Get All (Down key)',
                        get_all_alt: '(button)',
                        close_btn: 'Close (Tab key)',
                        close_alt: '(button)',
                        loading: 'loading...',
                        loading_alt: '(loading)',
                        page_info: 'num_page_top - num_page_end of cnt_whole',
                        select_ng: 'Attention : Please choose from among the list.',
                        select_ok: 'OK : Correctly selected.',
                        not_found: 'not found',
                        ajax_error: 'An error occurred while connecting to server. (ajax-combobox)'
                    };
                    break;

                    // 中文
                case 'cn':
                    message = {
                        add_btn: '添加按钮',
                        add_title: '添加区域',
                        del_btn: '删除按钮',
                        del_title: '删除区域',
                        next: '下一页',
                        next_title: '下' + this.option.per_page + ' (→)',
                        prev: '上一页',
                        prev_title: '上' + this.option.per_page + ' (←)',
                        first_title: '首页 (Shift + ←)',
                        last_title: '尾页 (Shift + →)',
                        get_all_btn: '获得全部 (↓)',
                        get_all_alt: '(按钮)',
                        close_btn: '关闭 (Tab键)',
                        close_alt: '(按钮)',
                        loading: '读取中...',
                        loading_alt: '(读取中)',
                        page_info: 'num_page_top - num_page_end (共 cnt_whole)',
                        select_ng: '请注意：请从列表中选择.',
                        select_ok: 'OK : 已经选择.',
                        not_found: '无查询结果',
                        ajax_error: '连接到服务器时发生错误！'
                    };
                    break;
            }
            this.message = message;
        },

        /**
         * @private
         * @desc CSS样式表名称字义
         */
        _setCssClass: function () {
            var css_class = {
                container: 'sp_container',
                // SelectPage最外层DIV的打开状态
                container_open: 'sp_container_open',
                selected: 'sp_selected',
                re_area: 'sp_result_area',
                //标签及输入框的
                element_box: 'sp_element_box',
                // 分页导航
                navi: 'pagination',
                // 下拉结果列表
                results: 'sp_results',
                re_off: 'sp_results_off',
                select: 'sp_over',
                select_ok: 'sp_select_ok',
                select_ng: 'sp_select_ng',
                input_off: 'sp_input_off',

                button: 'sp_button',
                btn_on: 'sp_btn_on',
                btn_out: 'sp_btn_out',
                input: 'sp_input'
            };
            this.css_class = css_class;
        },

        /**
         * @private
         * @desc 设置属性默认值
         */
        _setProp: function () {
            this.prop = {
                is_suggest: false,
                //当前页
                page_all: 1,
                page_suggest: 1,
                //总页数
                max_all: 1,
                max_suggest: 1,
                //正在分页状态
                is_paging: false,
                //是否正在Ajax请求
                is_loading: false,
                xhr: false,
                //使用键盘进行分页
                key_paging: false,
                //使用键盘进行选择
                key_select: false,
                //上一个选择的项目值
                prev_value: ''
            };
            this.template = {
                tag: {
                    content: '<li class="selected_tag" itemvalue="#item_value#">#item_text#<span class="tag_close">&times;</span></li>',
                    textKey: '#item_text#',
                    valueKey: '#item_value#'
                }
            };
        },

        /**
         * @private
         * @desc 插件HTML结构生成
         * @param {Object} combo_input - 输入框源对象
         */
        _setElem: function (combo_input, option) {
            //如果当前元素有默认值,且init_record为false时自动从元素取
            var value = $(combo_input).val();
            if (value && !option.init_record) {
                this.option.init_record = $.isArray(value) ? value.join(',') : value;
            }
            // 1. 生成、替换DOM对象
            var elem = {};//本体
            //将原始输入框中，用户设置的样式提取，并放到最外层的容器中,'class':''
            var outerWidth = $(combo_input).outerWidth();
            elem.combo_input = $(combo_input).attr({'autocomplete': 'off'}).addClass(this.css_class.input).wrap('<div>'); // This "div" is "container".
            elem.container = $(elem.combo_input).parent().addClass(this.css_class.container);

            elem.button = $('<div>').addClass(this.css_class.button);
            //更换为bootstrap风格的向下三角箭头
            elem.img = $('<span class="bs-caret"><span class="caret"></span></span>');

            //多选模式下带标签显示及文本输入的组合框
            elem.element_box = $('<ul>').addClass(this.css_class.element_box);

            //结果集列表
            elem.result_area = $('<div>').addClass(this.css_class.re_area);
            //分页栏
            elem.navi = $('<ul>').addClass(this.css_class.navi).addClass("hide");
            //elem.navi_info   = $('<div>').addClass('info');
            //elem.navi_p      = $('<p>');
            elem.results = $('<ul>').addClass(this.css_class.results);

            /**
             * 将原输入框的Name交换到Hidden中，因为具体需要保存传递到后端的是ID，而非Title
             */
            var namePrefix = '_text';
            //将primary_key的值放入"input:hidden"
            var input_id = ($(elem.combo_input).attr('id') !== undefined) ? $(elem.combo_input).attr('id') : $(elem.combo_input).attr('name');
            var input_name = ($(elem.combo_input).attr('name') !== undefined) ? $(elem.combo_input).attr('name') : 'selectPage';
            var hidden_name = input_name,
                    hidden_id = input_id;

            if (input_name.match(/\]$/))
                input_name = input_name.replace(/\]?$/, namePrefix);
            else
                input_name += namePrefix;
            if (input_id.match(/\]$/))
                input_id = input_id.replace(/\]?$/, namePrefix);
            else
                input_id += namePrefix;

            if ($(elem.combo_input).is("select")) {
                //将输入框的Name与Hidden的Name进行交换，使得可以将项目的具体ID被保存到后端进行处理
                elem.hidden = $('<select class="hide" />').attr({
                    name: hidden_name,
                    id: hidden_id
                }).prop("multiple", $(elem.combo_input).prop("multiple")).empty();
                var newinput = $('<input type="text" class="form-control" />').attr({
                    name: input_name,
                    id: input_id
                }).addClass(this.css_class.input);
                if (!option.source) {
                    var source = [];
                    $("option", elem.combo_input).each(function (i, j) {
                        source.push({id: i, name: $(this).text()});
                    });
                    this.option.source = source;
                }
                $(elem.combo_input).replaceWith(newinput);
                elem.combo_input = newinput;
            } else {
                //将输入框的Name与Hidden的Name进行交换，使得可以将项目的具体ID被保存到后端进行处理
                elem.hidden = $('<input type="hidden" class="hide" />').attr({
                    name: hidden_name,
                    id: hidden_id
                }).val('');
                $(elem.combo_input).attr({
                    name: input_name,
                    id: input_id
                });
            }
            $(elem.hidden).addClass("selectpage-input-hidden");
            // 2. DOM内容放置
            $(elem.container).append(elem.button).append(elem.result_area).append(elem.hidden);
            $(elem.button).append(elem.img);
            $(elem.result_area).append(elem.results).append(elem.navi);

            //多选模式下的特殊处理
            if (option.multiple) {
                $(elem.container).addClass('sp_container_combo');
                $(elem.combo_input).addClass('sp_combo_input').before($(elem.element_box));
                var li = $('<li>').addClass('input_box');
                $(li).append($(elem.combo_input));
                $(elem.element_box).append($(li));
                if ($(elem.combo_input).attr('placeholder'))
                    $(elem.combo_input).attr('placeholder_bak', $(elem.combo_input).attr('placeholder'));
            }
            if ($(elem.container).parent().hasClass("input-group")) {
                $(elem.container).height($(elem.container).parent().height());
            }

            this.elem = elem;
        },

        /**
         * @private
         * @desc 将控件的部分内容设置为默认状态
         */
        _setButtonAttrDefault: function () {
            if (this.option.select_only) {
                if ($(this.elem.combo_input).val() !== '') {
                    if ($(this.elem.hidden).val()) {
                        //选择条件
                        $(this.elem.combo_input).prop('title', this.message.select_ok).removeClass(this.css_class.select_ng).addClass(this.css_class.select_ok);
                    } else {
                        //输入方式
                        $(this.elem.combo_input).prop('title', this.message.select_ng).removeClass(this.css_class.select_ok).addClass(this.css_class.select_ng);
                    }
                } else {
                    $(this.elem.combo_input).prop('title', '').removeClass(this.css_class.select_ng);
                }
            }
            $(this.elem.button).prop('title', this.message.get_all_btn);
        },

        /**
         * @private
         * @desc 为插件设置初始化的选中值（若有指定的话）
         */
        _setInitRecord: function () {
            var self = this;
            //初始化外框宽度
            //$(self.elem.container).width($(self.elem.combo_input).outerWidth());
            if (this.option.init_record === false)
                return;
            // 初始的KEY值放入隐藏域
            self._setHiddenValue(self.option.init_record);

            //将初始值放入控件
            if (typeof self.option.source == 'object') {
                var data = new Array();
                var keyarr = self.option.init_record.split(',');
                $.each(keyarr, function (index, row) {
                    for (var i = 0; i < self.option.source.length; i++) {
                        if (self.option.source[i][self.option.primary_key] == row) {
                            data.push(self.option.source[i]);
                            break;
                        }
                    }
                });
                //在单选模式下，若使用了多选模式的初始化值（“key1,key2,...”多选方式），则不进行初始化选中操作
                if (!self.option.multiple && data.length > 1)
                    data = null;
                self._afterInit(self, data);
            } else {
                var _paramsFunc = self.option.params;
                var _params = {};
                //原始参数
                var _orgParams = {
                    field: self.option.field,
                    order_by: self.option.order_by,
                    pkey_name: self.option.primary_key,
                    pkey_value: self.option.init_record
                };
                if (_paramsFunc) {
                    var result = $.isFunction(_paramsFunc) ? _paramsFunc() : _paramsFunc;
                    if (result && $.isPlainObject(result)) {
                        _params = $.extend({}, _orgParams, result);
                    } else {
                        _params = _orgParams;
                    }
                } else {
                    _params = _orgParams;
                }
                $.ajax({
                    dataType: 'json',
                    url: self.option.source,
                    data: _params,
                    success: function (returnData) {
                        if (typeof returnData.list === 'undefined' || !$.isArray(returnData.list)) {
                            if (typeof returnData.rows === 'undefined' || !$.isArray(returnData.rows)) {
                                self.prop.xhr = null;
                                self._notFoundSearch(self);
                                return;
                            } else {
                                returnData.list = returnData.rows;
                            }
                        }
                        if (typeof returnData.total === 'undefined') {
                            returnData.total = returnData.list.length;
                        }
                        self._afterInit(self, returnData.list);
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        self._ajaxErrorNotify(self, errorThrown);
                    }
                });
            }
        },

        /**
         * @private
         * @desc 初始化后的处理
         * @param {Object} self - 插件的内部对象
         * @param {Object} data - 列表数据
         */
        _afterInit: function (self, data) {
            if (!data)
                return;
            if (!$.isArray(data))
                data = [data];
            if (self.option.multiple) {//多选模式初始化
                $(self.elem.combo_input).val('');
                $.each(data, function (i, row) {
                    var item = {text: row[self.option.field], value: row[self.option.primary_key]};
                    if (!self._isAlreadySelected(self, item))
                        self._addNewTag(self, item);
                });
                self._tagValuesSet(self);
                self._inputResize(self);
            } else {//单选模式初始化
                var row = data[0];
                $(self.elem.combo_input).val(row[self.option.field]);
                self._setHiddenValue(row[self.option.primary_key]);
                self.prop.prev_value = row[self.option.field];
                if (self.option.select_only) {
                    $(self.elem.combo_input).attr('title', self.message.select_ok).removeClass(self.css_class.select_ng).addClass(self.css_class.select_ok);
                }
            }
        },

        /**
         * @private
         * @desc 下拉按钮的事件处理
         */
        _ehButton: function () {
            var self = this;

            $(self.elem.button).mouseup(function (ev) {
                if ($(self.elem.result_area).is(':hidden') && !$(self.elem.combo_input).prop('disabled')) {
                    //self.prop.is_suggest = false;
                    //self._checkValue(self);
                    //self._suggest(self);
                    $(self.elem.combo_input).focus();
                } else {
                    self._hideResults(self);
                }
                ev.stopPropagation();
            }).mouseover(function () {
                $(self.elem.button).addClass(self.css_class.btn_on).removeClass(self.css_class.btn_out);
            }).mouseout(function () {
                $(self.elem.button).addClass(self.css_class.btn_out).removeClass(self.css_class.btn_on);
            }).mouseout(); // default: mouseout
        },

        /**
         * @private
         * @desc 输入框的事件绑定
         */
        _ehComboInput: function () {
            var self = this;
            $(self.elem.combo_input).keyup(function (ev) {
                self._processKey(self, ev);
            }).keydown(function (e) {
                if (e.keyCode === 8 && self.option.multiple && $(self.elem.combo_input).val() == '') {
                    $(self.elem.combo_input).parent().prev().find(".tag_close").trigger("click");
                }
            }).focus(function (e) {
                //增加输入框获得焦点后，显示数据列表
                if ($(self.elem.result_area).is(':hidden')) {
                    e.stopPropagation();
                    self.elem.navi.addClass("hide");
                    self.prop.first_show = true;
                    self.prop.page_move = false;
                    //self._checkValue(self);
                    self.prop.is_suggest = false;
                    self._suggest(self);
                    //self.prop.first_show = false;
                }
            }).click(function () {
                self._setCssFocusedInput(self);
                //该句会去除当前高亮项目的显示，暂时屏蔽
                //$(self.elem.results).children('li').removeClass(self.css_class.select);
            }).blur(function () {
                $(self.elem.hidden).trigger("blur");
            });
            if (self.option.multiple) {
                $(self.elem.element_box).click(function (e) {
                    var srcEl = e.target || e.srcElement;
                    if ($(srcEl).is('ul'))
                        $(self.elem.combo_input).focus();
                });
                //标签关闭操作
                //关闭同时需要将该标签的key从已保存的隐藏域中删除
                $(self.elem.element_box).on('click', 'span.tag_close', function () {
                    var li = $(this).closest('li');
                    var key = $(li).attr('itemvalue');
                    var keys = $(self.elem.hidden).val();
                    //从已保存的key列表中删除该标签对应的项目
                    if ($.type(key) != 'undefined' && keys) {
                        var keyarr = $.isArray(keys) ? keys : (keys ? keys.split(',') : new Array());
                        var index = $.inArray(key.toString(), keyarr);
                        if (index != -1) {
                            keyarr.splice(index, 1);
                            self._setHiddenValue(keyarr.toString());
                        }
                    }
                    $(li).remove();
                    self._inputResize(self);
                    $("li[pkey='" + key + "']", self.elem.results).removeClass(self.css_class.selected);
                });
                self._inputResize(self);
            }
        },

        /**
         * 设置隐藏元素的值
         * @param {type} value
         * @returns {undefined}
         */
        _setHiddenValue: function (value) {
            var self = this;
            if ($(self.elem.hidden).is("select")) {
                $(self.elem.hidden).empty();
                value = $.isArray(value) ? value : ($(self.elem.hidden).prop("multiple") ? (value + "").split(",") : value);
                $.each(value, function (i, j) {
                    $(self.elem.hidden).append($("<option />").prop("selected", true).val(j).text(j));
                });
            } else {
                value = $.isArray(value) ? value.join(",") : value;
                $(self.elem.hidden).val(value);
            }
        },

        /**
         * @private
         * @desc 插件整体的事件处理
         */
        _ehWhole: function () {
            var self = this;
            //如果是点击了控件本身则不响应外部鼠标点击事件
            $(self.elem.container).mousedown(function () {
                var thisindex = $('div.sp_container').index(this);
                var lastindex = $(document.body).data(constants.objStatusIndex);
                if (lastindex != undefined && thisindex != lastindex) {
                    $(document.body).data(constants.objStatusKey, false);
                } else {
                    $(document.body).data(constants.objStatusKey, true);
                }
                $(document.body).data(constants.objStatusIndex, thisindex);
            });
            //控件外部的鼠标点击事件处理
            $(document).off('mousedown.selectPage').on('mousedown.selectPage', function (e) {
                if ($(document.body).data(constants.objStatusKey))
                    $(document.body).data(constants.objStatusKey, false);
                else {
                    //清除内容
                    var cleanContent = function (obj) {
                        $(obj.elem.combo_input).val('');
                        if (!obj.option.multiple)
                            $(obj.elem.hidden).val('');
                    };
                    $('div.sp_container').each(function () {
                        var d = $(this).data(constants.dataKey);
                        //列表是打开的状态
                        if ($(this).hasClass(d.css_class.container_open)) {
                            //若控件已有选中的的项目，而文本输入框中清空了关键字，则清空控件已选中的项目
                            if (!$(d.elem.combo_input).val() && $(d.elem.hidden).val() && !d.option.multiple) {
                                d.prop.page_all = 1;//重置当前页为1
                                cleanContent(d);
                                d._hideResults(d);
                                return true;
                            }
                            //匹配项且高亮时，下拉分页控件失去焦点后，自动选择该项目
                            if ($('li', $(d.elem.results)).size() > 0) {
                                if (d.option.auto_fill_result) {//打开自动内容填充功能
                                    //若已有选中项目，则直接隐藏列表
                                    if ($('li.sp_selected', $(d.elem.results)).size() > 0) {
                                        d._hideResults(d);
                                    } else if ($('li.sp_over', $(d.elem.results)).size() > 0) {
                                        //若控件已有选中的值，则忽略高亮的项目
                                        if ($(d.elem.hidden).val())
                                            d._hideResults(d);
                                        //若没有已选中的项目，且列表中有高亮项目时，选中当前高亮的行
                                        else
                                            d._selectCurrentLine(d, true);
                                    } else if (d.option.auto_select_first) {
                                        //若控件已有选中的值，则忽略自动选择第一项的功能
                                        if ($(d.elem.hidden).val())
                                            d._hideResults(d);
                                        else {
                                            //对于没有选中，没有高亮的情况，若插件设置了自动选中第一项时，则选中第一项
                                            d._nextLine(d);
                                            //self._nextLine(self);
                                            d._selectCurrentLine(d, true);
                                        }
                                    } else {
                                        d._hideResults(d);
                                    }
                                } else
                                    d._hideResults(d);
                            } else {
                                //无匹配项目时，自动清空用户输入的关键词
                                if (d.option.no_result_clean)
                                    cleanContent(d);
                                else {
                                    if (!d.option.multiple)
                                        $(d.elem.hidden).val('');
                                }

                                d._hideResults(d);
                            }
                        }
                    });
                }
            });
        },

        /**
         * @private
         * @desc 结果列表的事件处理
         */
        _ehResults: function () {
            var self = this;
            $(self.elem.results).children('li').mouseover(function () {
                if (self.prop.key_select) {
                    self.prop.key_select = false;
                    return;
                }

                $(self.elem.results).children('li').removeClass(self.css_class.select);
                $(this).addClass(self.css_class.select);
                self._setCssFocusedResults(self);
            }).click(function (e) {
                if (self.prop.key_select) {
                    self.prop.key_select = false;
                    return;
                }
                e.preventDefault();
                e.stopPropagation();
                self._selectCurrentLine(self, false);
            });
        },

        /**
         * @private
         * @desc 分页导航按钮的事件处理
         * @param {Object} self - 插件内部对象
         */
        _ehNaviPaging: function (self) {
            $('li.csFirstPage', $(self.elem.navi)).off('click').on('click', function (ev) {
                $(self.elem.combo_input).focus();
                ev.preventDefault();
                self._firstPage(self);
            });

            $('li.csPreviousPage', $(self.elem.navi)).off('click').on('click', function (ev) {
                $(self.elem.combo_input).focus();
                ev.preventDefault();
                self._prevPage(self);
            });

            // the number of page
            /*
             $(self.elem.navi).find('.navi_page').mouseup(function(ev) {
             $(self.elem.combo_input).focus();
             ev.preventDefault();
             
             if (!self.prop.is_suggest) self.prop.page_all     = parseInt($(this).text(), 10);
             else                       self.prop.page_suggest = parseInt($(this).text(), 10);
             
             self.prop.is_paging = true;
             self._suggest(self);
             });
             */

            $('li.csNextPage', $(self.elem.navi)).off('click').on('click', function (ev) {
                $(self.elem.combo_input).focus();
                ev.preventDefault();
                self._nextPage(self);
            });

            $('li.csLastPage', $(self.elem.navi)).off('click').on('click', function (ev) {
                $(self.elem.combo_input).focus();
                ev.preventDefault();
                self._lastPage(self);
            });
        },

        /**
         * @private
         * @desc Ajax请求失败的处理
         * @param {Object} self - 插件内部对象
         * @errorThrom {string} errorThrown - Ajax的错误输出内容
         */
        _ajaxErrorNotify: function (self, errorThrown) {
            console.log(self.message.ajax_error);
        },

        /**
         * @private
         * @desc 窗口滚动处理
         * @param {Object} self - 插件内部对象
         * @param {boolean} enforce - 是否定位到输入框的位置
         */
        _scrollWindow: function (self, enforce) {
            var current_result = self._getCurrentLine(self);

            var target_top = (current_result && !enforce) ? current_result.offset().top : $(self.elem.container).offset().top;

            var target_size;

            self.prop.size_li = $(self.elem.results).children('li:first').outerHeight();
            target_size = self.prop.size_li;

            var client_height = $(window).height();
            var scroll_top = $(window).scrollTop();
            var scroll_bottom = scroll_top + client_height - target_size;

            // 滚动处理
            var gap;
            if ($(current_result).length) {
                if (target_top < scroll_top || target_size > client_height) {
                    //滚动到顶部
                    gap = target_top - scroll_top;
                } else if (target_top > scroll_bottom) {
                    //向下滚动
                    gap = target_top - scroll_bottom;
                } else {
                    //不进行滚动
                    return;
                }
            } else if (target_top < scroll_top)
                gap = target_top - scroll_top;
            window.scrollBy(0, gap);
        },

        /**
         * @private
         * @desc 输入框获得焦点的样式设置
         * @param {Object} self - 插件内部对象
         */
        _setCssFocusedInput: function (self) {
            $(self.elem.results).addClass(self.css_class.re_off);
            $(self.elem.combo_input).removeClass(self.css_class.input_off);
        },

        /**
         * @private
         * @desc 设置结果列表高亮，输入框失去焦点
         * @param {Object} self - 插件内部对象
         */
        _setCssFocusedResults: function (self) {
            $(self.elem.results).removeClass(self.css_class.re_off);
            $(self.elem.combo_input).addClass(self.css_class.input_off);
        },

        /**
         * @private
         * @desc 输入框输入值的变化监控
         * @param {Object} self - 插件内部对象
         */
        _checkValue: function (self) {
            var now_value = $(self.elem.combo_input).val();
            if (now_value != self.prop.prev_value) {
                self.prop.prev_value = now_value;
                self.prop.first_show = false;

                //原来的设计是在值变化时，都会清空隐藏域的内容，目前去除该功能
                //if (self.option.plugin_type != 'textarea') $(self.elem.hidden).val('');

                if (self.option.select_only)
                    self._setButtonAttrDefault();


                //重置页数
                self.prop.page_suggest = 1;
                self.prop.is_suggest = true;
                self._suggest(self);
            }
        },

        /**
         * @private
         * @desc 文本输入框键盘事件处理
         * @param {Object} self - 插件内部对象
         * @param {Object} e - 事件event对象
         */
        _processKey: function (self, e) {
            if (($.inArray(e.keyCode, [27, 38, 40, 9]) > -1 && $(self.elem.result_area).is(':visible')) || ($.inArray(e.keyCode, [37, 39, 13, 9]) > -1 && self._getCurrentLine(self)) || (e.keyCode == 40)) {
                e.preventDefault();
                e.stopPropagation();
                e.cancelBubble = true;
                e.returnValue = false;
                switch (e.keyCode) {
                    case 37:
                        // left
                        if (e.shiftKey)
                            self._firstPage(self);
                        else
                            self._prevPage(self);
                        break;

                    case 38:
                        // up
                        self.prop.key_select = true;
                        self._prevLine(self);
                        break;

                    case 39:
                        // right
                        if (e.shiftKey)
                            self._lastPage(self);
                        else
                            self._nextPage(self);
                        break;

                    case 40:
                        // down
                        if ($(self.elem.results).children('li').length) {
                            self.prop.key_select = true;
                            self._nextLine(self);
                        } else {
                            self.prop.is_suggest = false;
                            self._suggest(self);
                        }
                        break;

                    case 9:
                        // tab
                        self.prop.key_paging = true;
                        self._selectCurrentLine(self, true);
                        //self._hideResults(self);
                        break;

                    case 13:
                        // return
                        self._selectCurrentLine(self, true);
                        break;

                    case 27:
                        //  escape
                        self.prop.key_paging = true;
                        self._hideResults(self);
                        break;
                }
            } else {
                if (e.keyCode != 16)
                    self._setCssFocusedInput(self); // except Shift(16)
                self._inputResize(self);
                self._checkValue(self);
            }
        },

        /**
         * @private
         * @desc 中断Ajax请求
         * @param {Object} self - 插件内部对象
         */
        _abortAjax: function (self) {
            if (self.prop.xhr) {
                self.prop.xhr.abort();
                self.prop.xhr = false;
            }
        },

        /**
         * @private
         * @desc 数据查询
         * @param {Object} self - 插件内部对象
         */
        _suggest: function (self) {
            //搜索关键字
            var q_word;

            //q_word = (self.prop.is_suggest) ? $.trim($(self.elem.combo_input).val()) : '';
            //q_word = $.trim($(self.elem.combo_input).val());
            q_word = (self.prop.first_show) ? '' : $.trim($(self.elem.combo_input).val());
            /*
             //取消在输入状态时，判断到输入框里内容为空时，隐藏下拉列表的操作
             if (q_word.length < 1 && self.prop.is_suggest) {
             self._hideResults(self);
             return;
             }
             */
            q_word = q_word.split(/[\s　]+/);

            self._abortAjax(self);
            self._setLoading(self);
            if (self.prop.is_paging) {
                var obj = self._getCurrentLine(self);
                self.prop.is_paging = (obj) ? $(self.elem.results).children('li').index(obj) : -1;
            } else if (!self.prop.is_suggest) {
                self.prop.is_paging = 0;
            }
            //var which_page_num = (self.prop.is_suggest) ? self.prop.page_suggest: self.prop.page_all;
            //var which_page_num = (q_word == '' && !self.prop.page_move) ? 1 : self.prop.page_all;
            //var which_page_num = (self.prop.first_show) ? 1 : self.prop.page_all;
            var which_page_num = self.prop.page_all;
            // 数据查询
            if (typeof self.option.source == 'object')
                self._searchForJson(self, q_word, which_page_num);
            else
                self._searchForDb(self, q_word, which_page_num);
        },

        /**
         * @private
         * @desc 读取中状态显示
         * @param {Object} self - 插件内部对象
         */
        _setLoading: function (self) {
            $(self.elem.navi_info).text(self.message.loading);
            if ($(self.elem.results).html() === '') {
                $(self.elem.navi).children('p').empty();
                self._calcWidthResults(self);
                $(self.elem.container).addClass(self.css_class.container_open);
            }
        },

        /**
         * @private
         * @desc 服务端数据查询
         * @param {Object} self - 插件内部对象
         * @param {Array} q_word - 查询关键字
         * @param {number} which_page_num - 目标页
         */
        _searchForDb: function (self, q_word, which_page_num) {
            /**
             * 增加自定义查询参数
             */
            var _paramsFunc = self.option.params;
            var _params = {};
            //原始参数
            var searchKey = self.option.search_field;
            var _orgParams = {
                q_word: q_word,
                page: which_page_num,
                per_page: self.option.per_page,
                and_or: self.option.and_or,
                order_by: self.option.order_by,
                field: self.option.field,
                pkey_name: self.option.primary_key,
                search_field: searchKey
            };
            _orgParams[searchKey] = q_word[0];
            if (_paramsFunc) {
                var result = $.isFunction(_paramsFunc) ? _paramsFunc() : _paramsFunc;
                if (result && $.isPlainObject(result)) {
                    _params = $.extend({}, _orgParams, result);
                } else {
                    _params = _orgParams;
                }
            } else {
                _params = _orgParams;
            }
            //增加自定义查询参数End
            self.prop.xhr = $.ajax({
                dataType: 'json',
                url: self.option.source,
                data: _params,
                success: function (returnData) {
                    if (typeof returnData.list === 'undefined' || !$.isArray(returnData.list)) {
                        if (typeof returnData.rows === 'undefined' || !$.isArray(returnData.rows)) {
                            self.prop.xhr = null;
                            self._notFoundSearch(self);
                            return;
                        } else {
                            returnData.list = returnData.rows;
                        }
                    }
                    if (typeof returnData.total === 'undefined') {
                        returnData.total = returnData.list.length;
                    }
                    //数据结构处理
                    var json = {};
                    json.originalResult = returnData.list;
                    json.cnt_whole = returnData.total;
                    json.candidate = [];
                    json.primary_key = [];
                    json.cnt_page = json.originalResult.length;
                    for (var i = 0; i < json.cnt_page; i++) {
                        for (var key in json.originalResult[i]) {
                            if (key == self.option.primary_key) {
                                json.primary_key.push(json.originalResult[i][key]);
                            }
                            if (key == self.option.field) {
                                json.candidate.push(json.originalResult[i][key]);
                            }
                        }
                    }
                    self._prepareResults(self, json, q_word, which_page_num);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    if (textStatus != 'abort') {
                        self._hideResults(self);
                        self._ajaxErrorNotify(self, errorThrown);
                    }
                },
                complete: function () {
                    self.prop.xhr = null;
                }
            });
        },

        /**
         * @private
         * @desc 对JSON源数据进行搜索
         * @param {Object} self - 插件内部对象
         * @param {Array} q_word - 搜索关键字
         * @param {number} which_page_num - 目标页数
         */
        _searchForJson: function (self, q_word, which_page_num) {
            var matched = [];
            var esc_q = [];
            var sorted = [];
            var json = {};
            var i = 0;
            var arr_reg = [];

            //查询条件过滤
            do {
                //'/\W/g'正则代表全部不是字母，数字，下划线，汉字的字符
                //将非法字符进行转义
                esc_q[i] = q_word[i].replace(/\W/g, '\\$&').toString();
                arr_reg[i] = new RegExp(esc_q[i], 'gi');
                i++;
            } while (i < q_word.length);

            // SELECT * FROM source WHERE field LIKE q_word;
            for (i = 0; i < self.option.source.length; i++) {
                var flag = false;
                var row = self.option.source[i];
                for (var j = 0; j < arr_reg.length; j++) {
                    var itemText = row[self.option.field];//默认获取showField字段的文本
                    if (self.option.format_item && $.isFunction(self.option.format_item))
                        itemText = self.option.format_item(row);
                    if (itemText.match(arr_reg[j])) {
                        flag = true;
                        if (self.option.and_or == 'OR')
                            break;
                    } else {
                        flag = false;
                        if (self.option.and_or == 'AND')
                            break;
                    }
                }
                if (flag)
                    matched.push(row);
            }

            //若没有匹配项目，则结束搜索
            if (matched.length === undefined) {
                self._notFoundSearch(self);
                return;
            }
            json.cnt_whole = matched.length;

            // (CASE WHEN ...) 然后 く order 指定列
            var reg1 = new RegExp('^' + esc_q[0] + '$', 'gi');
            var reg2 = new RegExp('^' + esc_q[0], 'gi');
            var matched1 = [];
            var matched2 = [];
            var matched3 = [];
            for (i = 0; i < matched.length; i++) {
                if (matched[i][self.option.order_by[0][0]].match(reg1)) {
                    matched1.push(matched[i]);
                } else if (matched[i][self.option.order_by[0][0]].match(reg2)) {
                    matched2.push(matched[i]);
                } else {
                    matched3.push(matched[i]);
                }
            }

            if (self.option.order_by[0][1].match(/^asc$/i)) {
                matched1 = self._sortAsc(self, matched1);
                matched2 = self._sortAsc(self, matched2);
                matched3 = self._sortAsc(self, matched3);
            } else {
                matched1 = self._sortDesc(self, matched1);
                matched2 = self._sortDesc(self, matched2);
                matched3 = self._sortDesc(self, matched3);
            }
            sorted = sorted.concat(matched1).concat(matched2).concat(matched3);

            //page_move参数用于区别数据加载是在初始化列表还是在进行分页的翻页操作
            if (!self.prop.page_move) {
                //仅单选模式进行选中项目定位页功能
                if (!self.option.multiple) {
                    //若控件当前已有选中值，则获得该项目所在的页数，并跳转到该页进行显示
                    var currentValue = $(self.elem.hidden).val();
                    if (typeof (currentValue) != 'undefined' && $.trim(currentValue) != '') {
                        var index = 0;
                        $.each(sorted, function (i, row) {
                            if (row[self.option.primary_key] == currentValue) {
                                index = i + 1;
                                return false;
                            }
                        });
                        which_page_num = Math.ceil(index / self.option.per_page);
                        if (which_page_num < 1)
                            which_page_num = 1;
                        self.prop.page_all = which_page_num;
                    }
                }
            } else {
                //过滤后的数据个数不足一页显示的个数时，强制设置页码
                if (sorted.length <= ((which_page_num - 1) * self.option.per_page)) {
                    which_page_num = 1;
                    self.prop.page_all = 1;
                }
            }

            // LIMIT xx OFFSET xx
            var start = (which_page_num - 1) * self.option.per_page;
            var end = start + self.option.per_page;
            //储存原始行数据，包括所有属性
            json.originalResult = [];

            // 查询后的数据处理
            for (i = start; i < end; i++) {
                if (sorted[i] === undefined)
                    break;
                json.originalResult.push(sorted[i]);
                for (var key in sorted[i]) {
                    if (key == self.option.primary_key) {
                        if (json.primary_key === undefined)
                            json.primary_key = [];
                        json.primary_key.push(sorted[i][key]);
                    }
                    if (key == self.option.field) {
                        if (json.candidate === undefined)
                            json.candidate = [];
                        json.candidate.push(sorted[i][key]);
                    }
                }
            }
            if (json.candidate === undefined)
                json.candidate = [];
            json.cnt_page = json.candidate.length;
            self._prepareResults(self, json, q_word, which_page_num);
        },

        /**
         * @private
         * @desc 升充排序
         * @param {Object} self - 插件内部对象
         * @param {Array} arr - 结果集数组
         */
        _sortAsc: function (self, arr) {
            arr.sort(function (a, b) {
                return a[self.option.order_by[0][0]].localeCompare(b[self.option.order_by[0][0]]);
            });
            return arr;
        },

        /**
         * @private
         * @desc 降充排序
         * @param {Object} self - 插件内部对象
         * @param {Array} arr - 结果集数组
         */
        _sortDesc: function (self, arr) {
            arr.sort(function (a, b) {
                return b[self.option.order_by[0][0]].localeCompare(a[self.option.order_by[0][0]]);
            });
            return arr;
        },

        /**
         * @private
         * @desc 查询无结果的处理
         * @param {Object} self - 插件内部对象
         */
        _notFoundSearch: function (self) {
            $(self.elem.navi_info).text(self.message.not_found);
            $(self.elem.navi_p).hide();
            $(self.elem.results).empty();
            self._calcWidthResults(self);
            $(self.elem.container).addClass(self.css_class.container_open);
            self._setCssFocusedInput(self);
        },

        /**
         * @private
         * @desc 查询结果处理
         * @param {Object} self - 插件内部对象
         * @param {Object} json - 数据结果
         * @param {Array} q_word - 查询关键字
         * @param {number} which_page_num - 目标页
         */
        _prepareResults: function (self, json, q_word, which_page_num) {
            //处理分页栏
            self._setNavi(self, json.cnt_whole, json.cnt_page, which_page_num);

            if (!json.primary_key)
                json.primary_key = false;

            //仅选择模式
            if (self.option.select_only && json.candidate.length === 1 && json.candidate[0] == q_word[0]) {
                self._setHiddenValue(json.primary_key[0]);
                this._setButtonAttrDefault();
            }
            //是否是输入关键词进行查找
            var is_query = false;
            if (q_word && q_word.length > 0 && q_word[0])
                is_query = true;
            //显示结果列表
            self._displayResults(self, json, is_query);
            if (self.prop.is_paging === false)
                self._setCssFocusedInput(self);
            else {
                //修复单选模式下如果有选择项，则高亮选中项，而不是高亮第一项
                if (!self.option.multiple) {
                    var liSelected = $('li.sp_selected', $(self.elem.results));
                    if ($(liSelected).size() == 0) {
                        var idx = self.prop.is_paging;
                        var limit = $(self.elem.results).children('li').length - 1;
                        if (idx > limit)
                            idx = limit;
                        var obj = $(self.elem.results).children('li').eq(idx);
                        $(obj).addClass(self.css_class.select);
                    } else
                        $(liSelected).addClass(self.css_class.select);
                }

                self.prop.is_paging = false; //重置参数，为下次做准备
                self._setCssFocusedResults(self);
            }
        },

        /**
         * @private
         * @desc 生成分页栏
         * @param {Object} self - 插件内部对象
         * @param {number} cnt_whole - 数据总条数
         * @param {number} cnt_page - 页面显示记录数
         * @param {number} page_num - 当前页数
         */
        _setNavi: function (self, cnt_whole, cnt_page, page_num) {
            /**
             * 生成分页条
             */
            var buildPageNav = function (self, pagebar, page_num, last_page) {
                if ($('li', $(pagebar)).size() == 0) {
                    $(pagebar).empty();
                    //处理当当前页码为1时，首页和上一页按钮不允许点击
                    var btnclass = '', isNewFontAwesome = true;
                    //判断是否使用了font-awesome3.2.1
                    $.each(document.styleSheets, function (i, n) {
                        if (n && n.href && n.href.indexOf('font-awesome-3.2.1') != -1) {
                            isNewFontAwesome = false;
                            return false;
                        }
                    });
                    //为不同版本图标设置样式
                    var iconFist = 'fa fa-step-backward', iconPrev = 'fa fa-backward', iconNext = 'fa fa-forward', iconLast = 'fa fa-step-forward';
                    if (!isNewFontAwesome) {
                        iconFist = 'icon-step-backward';
                        iconPrev = 'icon-backward';
                        iconNext = 'icon-forward';
                        iconLast = 'icon-step-forward';
                    }

                    if (page_num == 1)
                        btnclass = ' disabled ';
                    //首页
                    $(pagebar).append('<li class="csFirstPage' + btnclass + '" title="' + self.message.first_title + '" ><a href="javascript:void(0);"> <i class="' + iconFist + '"></i> </a></li>');
                    //上一页
                    $(pagebar).append('<li class="csPreviousPage' + btnclass + '" title="' + self.message.prev_title + '" ><a href="javascript:void(0);"><i class="' + iconPrev + '"></i></a></li>');
                    var pageInfo = '第 ' + page_num + ' 页(共' + last_page + '页)';
                    //设置分页信息
                    $(pagebar).append('<li class="disabled pageInfoBox"><a href="javascript:void(0);"> ' + pageInfo + ' </a></li>');

                    if (page_num == last_page)
                        btnclass = ' disabled ';
                    else
                        btnclass = '';
                    //首页
                    $(pagebar).append('<li class="csNextPage' + btnclass + '" title="' + self.message.next_title + '" ><a href="javascript:void(0);"><i class="' + iconNext + '"></i></a></li>');
                    //上一页
                    $(pagebar).append('<li class="csLastPage' + btnclass + '" title="' + self.message.last_title + '" ><a href="javascript:void(0);"> <i class="' + iconLast + '"></i> </a></li>');
                }
            };

            var pagebar = $(self.elem.navi);
            pagebar.toggleClass("hide", cnt_whole < self.option.per_page)
            var last_page = Math.ceil(cnt_whole / self.option.per_page); //计算总页数
            if (last_page == 0)
                page_num = 0;
            else {
                if (page_num == 0)
                    page_num = 1;
            }
            buildPageNav(self, pagebar, page_num, last_page);
            //刷新分页信息
            var pageInfoBox = $('li.pageInfoBox', $(pagebar));

            var pageInfo = '第 ' + page_num + ' 页(共' + last_page + '页)';
            $(pageInfoBox).html('<a href="javascript:void(0);"> ' + pageInfo + ' </a>');
            //更新分页样式
            var dClass = 'disabled';
            var first = $('li.csFirstPage', $(pagebar));
            var previous = $('li.csPreviousPage', $(pagebar));
            var next = $('li.csNextPage', $(pagebar));
            var last = $('li.csLastPage', $(pagebar));
            //处理首页，上一页按钮样式
            if (page_num == 1 || page_num == 0) {
                if (!$(first).hasClass(dClass))
                    $(first).addClass(dClass);
                if (!$(previous).hasClass(dClass))
                    $(previous).addClass(dClass);
            } else {
                if ($(first).hasClass(dClass))
                    $(first).removeClass(dClass);
                if ($(previous).hasClass(dClass))
                    $(previous).removeClass(dClass);
            }
            //处理下一页，最后一页按钮的样式
            if (page_num == last_page || last_page == 0) {
                if (!$(next).hasClass(dClass))
                    $(next).addClass(dClass);
                if (!$(last).hasClass(dClass))
                    $(last).addClass(dClass);
            } else {
                if ($(next).hasClass(dClass))
                    $(next).removeClass(dClass);
                if ($(last).hasClass(dClass))
                    $(last).removeClass(dClass);
            }

            if (last_page > 1) {
                if (self.prop.is_suggest)
                    self.prop.max_suggest = last_page;
                else
                    self.prop.max_all = last_page;

                self._ehNaviPaging(self); // 导航按钮的事件设置
            } else {
                //$(self.elem.navi).hide();
            }
        },

        /**
         * @private
         * @desc 显示结果集列表
         * @param {Object} self - 插件内部对象
         * @param {Object} json 源数据
         * @param {boolean} is_query - 是否是通过关键字搜索（用于区分是鼠标点击下拉还是输入框输入关键字进行查找）
         */
        _displayResults: function (self, json, is_query) {
            $(self.elem.results).empty();
            var arr_candidate = json.candidate;
            var arr_primary_key = json.primary_key;
            var keystr = $(self.elem.hidden).val();
            var keyArr = $.isArray(keystr) ? keystr : (keystr ? keyArr = keystr.split(',') : new Array());
            for (var i = 0; i < arr_candidate.length; i++) {
                var itemText = '';
                if (self.option.format_item && $.isFunction(self.option.format_item)) {
                    try {
                        itemText = self.option.format_item(json.originalResult[i]);
                    } catch (e) {
                        console.error('formatItem内容格式化函数内容设置不正确！');
                        itemText = arr_candidate[i];
                    }
                } else
                    itemText = arr_candidate[i];
                //XSS対策
                var list = $('<li>').html(itemText).attr({
                    pkey: arr_primary_key[i],
                    pvalue: arr_candidate[i],
                    title: itemText
                });

                if (typeof arr_primary_key[i] !== 'undefined' && $.inArray(arr_primary_key[i].toString(), keyArr) != -1) {
                    $(list).addClass(self.css_class.selected);
                }
                //缓存原始行对象
                $(list).data('dataObj', json.originalResult[i]);
                $(self.elem.results).append(list);
            }
            //显示结果集列表并调整位置
            self._calcWidthResults(self);

            $(self.elem.container).addClass(self.css_class.container_open);

            //结果集列表事件绑定
            self._ehResults();
            //若是键盘输入关键字进行查询且有内容时，列表自动选中第一行(auto_select_first为true时)
            if (is_query && arr_candidate.length > 0 && self.option.auto_select_first)
                self._nextLine(self);
            //按钮的title属性修改
            $(self.elem.button).attr('title', self.message.close_btn);
        },

        /**
         * @private
         * @desc 处理结果列表尺寸及位置
         * @param {Object} self - 插件内部对象
         */
        _calcWidthResults: function (self) {
            //若列表已展现，则不再处理
            //###代码已禁用###若启用，在翻页时，会不计算位置，导致数据量不一致时列表不会自动定位
            //if($(self.elem.result_area).is(":visible")) return;

            //设置下拉列表与输入框同宽
            //###代码已禁用###因为分页栏是固定宽度的原因，列表与输入框宽度同步功能不能再使用
            /*
             $(self.elem.result_area)
             .width(
             $(self.elem.container).width() -
             ($(self.elem.result_area).outerWidth() - $(self.elem.result_area).innerWidth())
             )
             .show();
             */
            $(self.elem.result_area).show(1, function () {
                /*
                 //设置外框宽度，只需要初始化时设置即可，不需要在每次展开列表时进行设置，这里暂时屏蔽该功能
                 //单选模式下，设置外框与输入框尺寸一致
                 if(!self.option.multiple){
                 $(self.elem.container).width($(self.elem.combo_input).outerWidth());
                 }
                 */
                if ($(self.elem.container).css('position') == 'static') {
                    // position: static
                    var offset = $(self.elem.combo_input).offset();
                    $(self.elem.result_area).css({
                        top: offset.top + $(self.elem.combo_input).outerHeight() + 'px',
                        left: offset.left + 'px'
                    });
                } else {
                    //在展示下拉列表时，判断默认与输入框左对齐的列表是否会超出屏幕边界，是则右对齐，否则默认左对齐
                    var browserWidth = document.body.clientWidth;
                    var browserHeight = document.body.clientHeight;
                    var offset = $(self.elem.container).offset();
                    var listWidth = $(self.elem.result_area).outerWidth();
                    //当前状态，列表并未被显示，数据未被填充，列表并未展现最终高度，所以只能使用默认一页显示10条数据的固定高度进行计算
                    var listHeight = $(self.elem.result_area).outerHeight();
                    //默认方向的坐标，在多选模式下，因为外框架是DIV，所以需要向左靠一个像素
                    var defaultLeft = self.option.multiple ? -1 : 0;
                    var left = (offset.left + listWidth) > browserWidth ? -(listWidth - $(self.elem.container).outerWidth()) : defaultLeft;
                    //控件在当前可视区域中的高度
                    var screenTop = offset.top - $(window).scrollTop();
                    var top = 0;
                    if ((screenTop + $(self.elem.container).outerHeight() + listHeight) > browserHeight) {
                        top = -(listHeight + 1);
                        $(self.elem.result_area).removeClass('shadowUp shadowDown').addClass('shadowUp');
                    } else {
                        top = self.option.multiple ? $(self.elem.container).innerHeight() + 1 : $(self.elem.container).outerHeight();
                        $(self.elem.result_area).removeClass('shadowUp shadowDown').addClass('shadowDown');
                    }
                    // position: relative, absolute, fixed
                    $(self.elem.result_area).css({
                        top: top + 'px',
                        left: left + 'px'
                    });
                }
            });
        },

        /**
         * @private
         * @desc 隐藏结果列表
         * @param {Object} self - 插件内部对象
         */
        _hideResults: function (self) {
            if (self.prop.key_paging) {
                self._scrollWindow(self, true);
                self.prop.key_paging = false;
            }
            self._setCssFocusedInput(self);

            if (self.option.auto_fill_result) {
                //self._selectCurrentLine(self, true);
            }

            $(self.elem.results).empty();
            $(self.elem.result_area).hide();
            $(self.elem.container).removeClass(self.css_class.container_open);

            self._abortAjax(self);
            self._setButtonAttrDefault(); // 按钮title属性初期化
        },

        /**
         * @private
         * @desc 跳转到首页
         * @param {Object} self - 插件内部对象
         */
        _firstPage: function (self) {
            if (self.prop.page_all > 1) {
                self.prop.page_all = 1;
                self.prop.is_paging = true;
                self.prop.page_move = true;
                self._suggest(self);
            }
            /*
             if (!self.prop.is_suggest) {
             if (self.prop.page_all > 1) {
             self.prop.page_all = 1;
             self.prop.is_paging = true;
             self.prop.page_move = true;
             self._suggest(self);
             }
             } else {
             if (self.prop.page_suggest > 1) {
             self.prop.page_suggest = 1;
             self.prop.is_paging = true;
             self._suggest(self);
             }
             }
             */
        },

        /**
         * @private
         * @desc 跳转到上一页
         * @param {Object} self - 插件内部对象
         */
        _prevPage: function (self) {
            if (self.prop.page_all > 1) {
                self.prop.page_all--;
                self.prop.is_paging = true;
                self.prop.page_move = true;
                self._suggest(self);
            }
            /*
             if (!self.prop.is_suggest) {
             if (self.prop.page_all > 1) {
             self.prop.page_all--;
             self.prop.is_paging = true;
             self.prop.page_move = true;
             self._suggest(self);
             }
             } else {
             if (self.prop.page_suggest > 1) {
             self.prop.page_suggest--;
             self.prop.is_paging = true;
             self._suggest(self);
             }
             }
             */
        },

        /**
         * @private
         * @desc 跳转到下一页
         * @param {Object} self - 插件内部对象
         */
        _nextPage: function (self) {
            if (self.prop.page_all < self.prop.max_all) {
                self.prop.page_all++;
                self.prop.is_paging = true;
                self.prop.page_move = true;
                self._suggest(self);
            }
            /*
             if (self.prop.is_suggest) {
             if (self.prop.page_suggest < self.prop.max_suggest) {
             self.prop.page_suggest++;
             self.prop.is_paging = true;
             self._suggest(self);
             }
             } else {
             if (self.prop.page_all < self.prop.max_all) {
             self.prop.page_all++;
             self.prop.is_paging = true;
             self.prop.page_move = true;
             self._suggest(self);
             }
             }
             */
        },

        /**
         * @private
         * @desc 跳转到尾页
         * @param {Object} self - 插件内部对象
         */
        _lastPage: function (self) {
            if (self.prop.page_all < self.prop.max_all) {
                self.prop.page_all = self.prop.max_all;
                self.prop.is_paging = true;
                self.prop.page_move = true;
                self._suggest(self);
            }
            /*
             if (!self.prop.is_suggest) {
             if (self.prop.page_all < self.prop.max_all) {
             self.prop.page_all = self.prop.max_all;
             self.prop.is_paging = true;
             self.prop.page_move = true;
             self._suggest(self);
             }
             } else {
             if (self.prop.page_suggest < self.prop.max_suggest) {
             self.prop.page_suggest = self.prop.max_suggest;
             self.prop.is_paging = true;
             self._suggest(self);
             }
             }
             */
        },
        /**
         * @private
         * @desc 跳转到指定页
         * @param {Object} self
         * @param {number} page 目标页数
         */
        _goPage: function (self, page) {
            if (typeof (page) == 'undefined')
                page = 1;
            if (self.prop.page_all < self.prop.max_all) {
                self.prop.page_all = page;
                self.prop.is_paging = true;
                self.prop.page_move = true;
                self._suggest(self);
            }
            /*
             if (self.prop.is_suggest) {
             if (self.prop.page_suggest < self.prop.max_suggest) {
             self.prop.page_suggest = page;
             self.prop.is_paging = true;
             self._suggest(self);
             }
             } else {
             if (self.prop.page_all < self.prop.max_all) {
             self.prop.page_all = page;
             self.prop.is_paging = true;
             self.prop.page_move = true;
             self._suggest(self);
             }
             }
             */
        },

        /**
         * @private
         * @desc 选择当前行
         * @param {Object} self - 插件内部对象
         * @param {boolean} is_enter_key - 是否为回车键
         */
        _selectCurrentLine: function (self, is_enter_key) {
            self._scrollWindow(self, true);

            var current = self._getCurrentLine(self);
            if (current) {
                var pkey = $(current).attr('pkey');
                var text = self.option.format_fill ? $(current).text() : $(current).attr("pvalue");
                if (!self.option.multiple) {
                    $(self.elem.combo_input).val(text);
                    self._setHiddenValue(pkey);
                } else {
                    //多选模式的项目选择处理
                    $(self.elem.combo_input).val('');
                    var item = {text: text, value: pkey};
                    if (!self._isAlreadySelected(self, item)) {
                        self._addNewTag(self, item);
                        self._tagValuesSet(self);
                    }
                }

                if (self.option.select_only)
                    self._setButtonAttrDefault();
                //触发元素的事件
                $(self.elem.hidden).trigger("change");

                //回调函数触发
                if (typeof self.option.callback === 'function') {
                    self.option.callback.call(self, $(current).data('dataObj'));
                }
                //触发指定事件
                if (self.option.bind_to) {
                    $(self.elem.combo_input).trigger(self.option.bind_to, $(current).data('dataObj'));
                }

                self.prop.prev_value = $(self.elem.combo_input).val();
                self._hideResults(self);
            }

            //$(self.elem.combo_input).focus();
            $(self.elem.combo_input).trigger("change");
            $(self.elem.combo_input).trigger("blur");
            self._setCssFocusedInput(self);
            self._inputResize(self);
        },

        /**
         * @private
         * @desc 获得当前行对象
         * @param {Object} self - 插件内部对象
         */
        _getCurrentLine: function (self) {
            if ($(self.elem.result_area).is(':hidden'))
                return false;
            var obj = $(self.elem.results).children('li.' + self.css_class.select);
            if ($(obj).length)
                return obj;
            else
                return false;
        },

        /**
         * @private
         * @desc 多选模式下判断当前选中项目是否已经存在已选中列表中
         * @param {Object} self - 插件内部对象
         * @param {Object} item - 选中行对象
         */
        _isAlreadySelected: function (self, item) {
            var isExist = false;
            if (item.value) {
                var keys = $(self.elem.hidden).val();
                if (keys) {
                    var karr = $.isArray(keys) ? keys : (keys ? keys.split(',') : new Array());
                    if (karr && karr.length > 0 && $.inArray(item.value, karr) != -1)
                        isExist = true;
                }
            }
            return isExist;
        },

        /**
         * @private
         * @desc 多选模式下增加一个标签
         * @param {Object} self - 插件内部对象
         * @param {Object} item - 选中行对象
         */
        _addNewTag: function (self, item) {
            if (!self.option.multiple || !item)
                return;
            var tmp = self.template.tag.content;
            tmp = tmp.replace(self.template.tag.textKey, item.text);
            tmp = tmp.replace(self.template.tag.valueKey, item.value);
            $(self.elem.combo_input).closest('li').before($(tmp));
        },

        /**
         * @private
         * @desc 多选模式下标签结果值放入隐藏域
         * @param {Object} self - 插件内部对象
         */
        _tagValuesSet: function (self) {
            if (!self.option.multiple)
                return;
            var tags = $('li.selected_tag', $(self.elem.element_box));
            if (tags && $(tags).size() > 0) {
                var result = new Array();
                $.each(tags, function (i, li) {
                    var v = $(li).attr('itemvalue');
                    if ($.type(v) != 'undefined')
                        result.push(v);
                });
                if (result.length > 0) {
                    self._setHiddenValue(result);
                }

            }
        },

        /**
         * @private
         * @desc 多选模式下输入框根据输入内容调整输入框宽度
         * @param {Object} self - 插件内部对象
         */
        _inputResize: function (self) {
            if (!self.option.multiple)
                return;
            var width = '';
            var inputLi = self.elem.combo_input.closest('li');
            //设置默认宽度
            var setDefaultSize = function (self, inputLi) {
                inputLi.removeClass('full_width');
                var minimumWidth = self.elem.combo_input.val().length + 1;
                var width = (minimumWidth * 0.75) + 'em';
                self.elem.combo_input.css('width', width);
                self.elem.combo_input.removeAttr('placeholder');
            };
            if ($('li.selected_tag', $(self.elem.element_box)).size() == 0) {
                if (self.elem.combo_input.attr('placeholder_bak')) {
                    if (!inputLi.hasClass('full_width'))
                        inputLi.addClass('full_width');
                    self.elem.combo_input.attr('placeholder', self.elem.combo_input.attr('placeholder_bak'));
                } else
                    setDefaultSize(self, inputLi);
            } else
                setDefaultSize(self, inputLi);
        },

        /**
         * @private
         * @desc 选择下一行
         * @param {Object} self - 插件内部对象
         */
        _nextLine: function (self) {
            var obj = self._getCurrentLine(self);
            var idx;
            if (!obj)
                idx = -1;
            else {
                idx = $(self.elem.results).children('li').index(obj);
                $(obj).removeClass(self.css_class.select);
            }
            idx++;
            if (idx < $(self.elem.results).children('li').length) {
                var next = $(self.elem.results).children('li').eq(idx);
                $(next).addClass(self.css_class.select);
                self._setCssFocusedResults(self);
            } else
                self._setCssFocusedInput(self);
            self._scrollWindow(self, false);
        },

        /**
         * @private
         * @desc 选择上一行
         * @param {Object} self - 插件内部对象
         */
        _prevLine: function (self) {
            var obj = self._getCurrentLine(self);
            var idx;
            if (!obj)
                idx = $(self.elem.results).children('li').length;
            else {
                idx = $(self.elem.results).children('li').index(obj);
                $(obj).removeClass(self.css_class.select);
            }
            idx--;
            if (idx > -1) {
                var prev = $(self.elem.results).children('li').eq(idx);
                $(prev).addClass(self.css_class.select);
                self._setCssFocusedResults(self);
            } else
                self._setCssFocusedInput(self);
            self._scrollWindow(self, false);
        }
    });
    return SelectPage;
}));

