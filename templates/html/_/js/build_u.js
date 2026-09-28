
/*
 RequireJS 2.1.4 Copyright (c) 2010-2012, The Dojo Foundation All Rights Reserved.
 Available via the MIT or new BSD license.
 see: http://github.com/jrburke/requirejs for details
*/
var requirejs,require,define;
(function(Y){function I(b){return"[object Function]"===L.call(b)}function J(b){return"[object Array]"===L.call(b)}function x(b,c){if(b){var d;for(d=0;d<b.length&&(!b[d]||!c(b[d],d,b));d+=1);}}function M(b,c){if(b){var d;for(d=b.length-1;-1<d&&(!b[d]||!c(b[d],d,b));d-=1);}}function r(b,c){return da.call(b,c)}function i(b,c){return r(b,c)&&b[c]}function E(b,c){for(var d in b)if(r(b,d)&&c(b[d],d))break}function Q(b,c,d,i){c&&E(c,function(c,h){if(d||!r(b,h))i&&"string"!==typeof c?(b[h]||(b[h]={}),Q(b[h],
c,d,i)):b[h]=c});return b}function t(b,c){return function(){return c.apply(b,arguments)}}function Z(b){if(!b)return b;var c=Y;x(b.split("."),function(b){c=c[b]});return c}function F(b,c,d,i){c=Error(c+"\nhttp://requirejs.org/docs/errors.html#"+b);c.requireType=b;c.requireModules=i;d&&(c.originalError=d);return c}function ea(b){function c(a,f,v){var e,n,b,c,d,k,g,h=f&&f.split("/");e=h;var l=m.map,j=l&&l["*"];if(a&&"."===a.charAt(0))if(f){e=i(m.pkgs,f)?h=[f]:h.slice(0,h.length-1);f=a=e.concat(a.split("/"));
for(e=0;f[e];e+=1)if(n=f[e],"."===n)f.splice(e,1),e-=1;else if(".."===n)if(1===e&&(".."===f[2]||".."===f[0]))break;else 0<e&&(f.splice(e-1,2),e-=2);e=i(m.pkgs,f=a[0]);a=a.join("/");e&&a===f+"/"+e.main&&(a=f)}else 0===a.indexOf("./")&&(a=a.substring(2));if(v&&(h||j)&&l){f=a.split("/");for(e=f.length;0<e;e-=1){b=f.slice(0,e).join("/");if(h)for(n=h.length;0<n;n-=1)if(v=i(l,h.slice(0,n).join("/")))if(v=i(v,b)){c=v;d=e;break}if(c)break;!k&&(j&&i(j,b))&&(k=i(j,b),g=e)}!c&&k&&(c=k,d=g);c&&(f.splice(0,d,
c),a=f.join("/"))}return a}function d(a){z&&x(document.getElementsByTagName("script"),function(f){if(f.getAttribute("data-requiremodule")===a&&f.getAttribute("data-requirecontext")===k.contextName)return f.parentNode.removeChild(f),!0})}function y(a){var f=i(m.paths,a);if(f&&J(f)&&1<f.length)return d(a),f.shift(),k.require.undef(a),k.require([a]),!0}function g(a){var f,b=a?a.indexOf("!"):-1;-1<b&&(f=a.substring(0,b),a=a.substring(b+1,a.length));return[f,a]}function h(a,f,b,e){var n,u,d=null,h=f?f.name:
null,l=a,m=!0,j="";a||(m=!1,a="_@r"+(L+=1));a=g(a);d=a[0];a=a[1];d&&(d=c(d,h,e),u=i(p,d));a&&(d?j=u&&u.normalize?u.normalize(a,function(a){return c(a,h,e)}):c(a,h,e):(j=c(a,h,e),a=g(j),d=a[0],j=a[1],b=!0,n=k.nameToUrl(j)));b=d&&!u&&!b?"_unnormalized"+(M+=1):"";return{prefix:d,name:j,parentMap:f,unnormalized:!!b,url:n,originalName:l,isDefine:m,id:(d?d+"!"+j:j)+b}}function q(a){var f=a.id,b=i(j,f);b||(b=j[f]=new k.Module(a));return b}function s(a,f,b){var e=a.id,n=i(j,e);if(r(p,e)&&(!n||n.defineEmitComplete))"defined"===
f&&b(p[e]);else q(a).on(f,b)}function A(a,f){var b=a.requireModules,e=!1;if(f)f(a);else if(x(b,function(f){if(f=i(j,f))f.error=a,f.events.error&&(e=!0,f.emit("error",a))}),!e)l.onError(a)}function w(){R.length&&(fa.apply(G,[G.length-1,0].concat(R)),R=[])}function B(a,f,b){var e=a.map.id;a.error?a.emit("error",a.error):(f[e]=!0,x(a.depMaps,function(e,c){var d=e.id,h=i(j,d);h&&(!a.depMatched[c]&&!b[d])&&(i(f,d)?(a.defineDep(c,p[d]),a.check()):B(h,f,b))}),b[e]=!0)}function C(){var a,f,b,e,n=(b=1E3*m.waitSeconds)&&
k.startTime+b<(new Date).getTime(),c=[],h=[],g=!1,l=!0;if(!T){T=!0;E(j,function(b){a=b.map;f=a.id;if(b.enabled&&(a.isDefine||h.push(b),!b.error))if(!b.inited&&n)y(f)?g=e=!0:(c.push(f),d(f));else if(!b.inited&&(b.fetched&&a.isDefine)&&(g=!0,!a.prefix))return l=!1});if(n&&c.length)return b=F("timeout","Load timeout for modules: "+c,null,c),b.contextName=k.contextName,A(b);l&&x(h,function(a){B(a,{},{})});if((!n||e)&&g)if((z||$)&&!U)U=setTimeout(function(){U=0;C()},50);T=!1}}function D(a){r(p,a[0])||
q(h(a[0],null,!0)).init(a[1],a[2])}function H(a){var a=a.currentTarget||a.srcElement,b=k.onScriptLoad;a.detachEvent&&!V?a.detachEvent("onreadystatechange",b):a.removeEventListener("load",b,!1);b=k.onScriptError;(!a.detachEvent||V)&&a.removeEventListener("error",b,!1);return{node:a,id:a&&a.getAttribute("data-requiremodule")}}function K(){var a;for(w();G.length;){a=G.shift();if(null===a[0])return A(F("mismatch","Mismatched anonymous define() module: "+a[a.length-1]));D(a)}}var T,W,k,N,U,m={waitSeconds:7,
baseUrl:"./",paths:{},pkgs:{},shim:{},map:{},config:{}},j={},X={},G=[],p={},S={},L=1,M=1;N={require:function(a){return a.require?a.require:a.require=k.makeRequire(a.map)},exports:function(a){a.usingExports=!0;if(a.map.isDefine)return a.exports?a.exports:a.exports=p[a.map.id]={}},module:function(a){return a.module?a.module:a.module={id:a.map.id,uri:a.map.url,config:function(){return m.config&&i(m.config,a.map.id)||{}},exports:p[a.map.id]}}};W=function(a){this.events=i(X,a.id)||{};this.map=a;this.shim=
i(m.shim,a.id);this.depExports=[];this.depMaps=[];this.depMatched=[];this.pluginMaps={};this.depCount=0};W.prototype={init:function(a,b,c,e){e=e||{};if(!this.inited){this.factory=b;if(c)this.on("error",c);else this.events.error&&(c=t(this,function(a){this.emit("error",a)}));this.depMaps=a&&a.slice(0);this.errback=c;this.inited=!0;this.ignore=e.ignore;e.enabled||this.enabled?this.enable():this.check()}},defineDep:function(a,b){this.depMatched[a]||(this.depMatched[a]=!0,this.depCount-=1,this.depExports[a]=
b)},fetch:function(){if(!this.fetched){this.fetched=!0;k.startTime=(new Date).getTime();var a=this.map;if(this.shim)k.makeRequire(this.map,{enableBuildCallback:!0})(this.shim.deps||[],t(this,function(){return a.prefix?this.callPlugin():this.load()}));else return a.prefix?this.callPlugin():this.load()}},load:function(){var a=this.map.url;S[a]||(S[a]=!0,k.load(this.map.id,a))},check:function(){if(this.enabled&&!this.enabling){var a,b,c=this.map.id;b=this.depExports;var e=this.exports,n=this.factory;
if(this.inited)if(this.error)this.emit("error",this.error);else{if(!this.defining){this.defining=!0;if(1>this.depCount&&!this.defined){if(I(n)){if(this.events.error)try{e=k.execCb(c,n,b,e)}catch(d){a=d}else e=k.execCb(c,n,b,e);this.map.isDefine&&((b=this.module)&&void 0!==b.exports&&b.exports!==this.exports?e=b.exports:void 0===e&&this.usingExports&&(e=this.exports));if(a)return a.requireMap=this.map,a.requireModules=[this.map.id],a.requireType="define",A(this.error=a)}else e=n;this.exports=e;if(this.map.isDefine&&
!this.ignore&&(p[c]=e,l.onResourceLoad))l.onResourceLoad(k,this.map,this.depMaps);delete j[c];this.defined=!0}this.defining=!1;this.defined&&!this.defineEmitted&&(this.defineEmitted=!0,this.emit("defined",this.exports),this.defineEmitComplete=!0)}}else this.fetch()}},callPlugin:function(){var a=this.map,b=a.id,d=h(a.prefix);this.depMaps.push(d);s(d,"defined",t(this,function(e){var n,d;d=this.map.name;var v=this.map.parentMap?this.map.parentMap.name:null,g=k.makeRequire(a.parentMap,{enableBuildCallback:!0});
if(this.map.unnormalized){if(e.normalize&&(d=e.normalize(d,function(a){return c(a,v,!0)})||""),e=h(a.prefix+"!"+d,this.map.parentMap),s(e,"defined",t(this,function(a){this.init([],function(){return a},null,{enabled:!0,ignore:!0})})),d=i(j,e.id)){this.depMaps.push(e);if(this.events.error)d.on("error",t(this,function(a){this.emit("error",a)}));d.enable()}}else n=t(this,function(a){this.init([],function(){return a},null,{enabled:!0})}),n.error=t(this,function(a){this.inited=!0;this.error=a;a.requireModules=
[b];E(j,function(a){0===a.map.id.indexOf(b+"_unnormalized")&&delete j[a.map.id]});A(a)}),n.fromText=t(this,function(e,c){var d=a.name,u=h(d),v=O;c&&(e=c);v&&(O=!1);q(u);r(m.config,b)&&(m.config[d]=m.config[b]);try{l.exec(e)}catch(j){return A(F("fromtexteval","fromText eval for "+b+" failed: "+j,j,[b]))}v&&(O=!0);this.depMaps.push(u);k.completeLoad(d);g([d],n)}),e.load(a.name,g,n,m)}));k.enable(d,this);this.pluginMaps[d.id]=d},enable:function(){this.enabling=this.enabled=!0;x(this.depMaps,t(this,function(a,
b){var c,e;if("string"===typeof a){a=h(a,this.map.isDefine?this.map:this.map.parentMap,!1,!this.skipMap);this.depMaps[b]=a;if(c=i(N,a.id)){this.depExports[b]=c(this);return}this.depCount+=1;s(a,"defined",t(this,function(a){this.defineDep(b,a);this.check()}));this.errback&&s(a,"error",this.errback)}c=a.id;e=j[c];!r(N,c)&&(e&&!e.enabled)&&k.enable(a,this)}));E(this.pluginMaps,t(this,function(a){var b=i(j,a.id);b&&!b.enabled&&k.enable(a,this)}));this.enabling=!1;this.check()},on:function(a,b){var c=
this.events[a];c||(c=this.events[a]=[]);c.push(b)},emit:function(a,b){x(this.events[a],function(a){a(b)});"error"===a&&delete this.events[a]}};k={config:m,contextName:b,registry:j,defined:p,urlFetched:S,defQueue:G,Module:W,makeModuleMap:h,nextTick:l.nextTick,configure:function(a){a.baseUrl&&"/"!==a.baseUrl.charAt(a.baseUrl.length-1)&&(a.baseUrl+="/");var b=m.pkgs,c=m.shim,e={paths:!0,config:!0,map:!0};E(a,function(a,b){e[b]?"map"===b?Q(m[b],a,!0,!0):Q(m[b],a,!0):m[b]=a});a.shim&&(E(a.shim,function(a,
b){J(a)&&(a={deps:a});if((a.exports||a.init)&&!a.exportsFn)a.exportsFn=k.makeShimExports(a);c[b]=a}),m.shim=c);a.packages&&(x(a.packages,function(a){a="string"===typeof a?{name:a}:a;b[a.name]={name:a.name,location:a.location||a.name,main:(a.main||"main").replace(ga,"").replace(aa,"")}}),m.pkgs=b);E(j,function(a,b){!a.inited&&!a.map.unnormalized&&(a.map=h(b))});if(a.deps||a.callback)k.require(a.deps||[],a.callback)},makeShimExports:function(a){return function(){var b;a.init&&(b=a.init.apply(Y,arguments));
return b||a.exports&&Z(a.exports)}},makeRequire:function(a,d){function g(e,c,u){var i,m;d.enableBuildCallback&&(c&&I(c))&&(c.__requireJsBuild=!0);if("string"===typeof e){if(I(c))return A(F("requireargs","Invalid require call"),u);if(a&&r(N,e))return N[e](j[a.id]);if(l.get)return l.get(k,e,a);i=h(e,a,!1,!0);i=i.id;return!r(p,i)?A(F("notloaded",'Module name "'+i+'" has not been loaded yet for context: '+b+(a?"":". Use require([])"))):p[i]}K();k.nextTick(function(){K();m=q(h(null,a));m.skipMap=d.skipMap;
m.init(e,c,u,{enabled:!0});C()});return g}d=d||{};Q(g,{isBrowser:z,toUrl:function(b){var d,f=b.lastIndexOf("."),h=b.split("/")[0];if(-1!==f&&(!("."===h||".."===h)||1<f))d=b.substring(f,b.length),b=b.substring(0,f);b=k.nameToUrl(c(b,a&&a.id,!0),d||".fake");return d?b:b.substring(0,b.length-5)},defined:function(b){return r(p,h(b,a,!1,!0).id)},specified:function(b){b=h(b,a,!1,!0).id;return r(p,b)||r(j,b)}});a||(g.undef=function(b){w();var c=h(b,a,!0),d=i(j,b);delete p[b];delete S[c.url];delete X[b];
d&&(d.events.defined&&(X[b]=d.events),delete j[b])});return g},enable:function(a){i(j,a.id)&&q(a).enable()},completeLoad:function(a){var b,c,d=i(m.shim,a)||{},h=d.exports;for(w();G.length;){c=G.shift();if(null===c[0]){c[0]=a;if(b)break;b=!0}else c[0]===a&&(b=!0);D(c)}c=i(j,a);if(!b&&!r(p,a)&&c&&!c.inited){if(m.enforceDefine&&(!h||!Z(h)))return y(a)?void 0:A(F("nodefine","No define call for "+a,null,[a]));D([a,d.deps||[],d.exportsFn])}C()},nameToUrl:function(a,b){var c,d,h,g,k,j;if(l.jsExtRegExp.test(a))g=
a+(b||"");else{c=m.paths;d=m.pkgs;g=a.split("/");for(k=g.length;0<k;k-=1)if(j=g.slice(0,k).join("/"),h=i(d,j),j=i(c,j)){J(j)&&(j=j[0]);g.splice(0,k,j);break}else if(h){c=a===h.name?h.location+"/"+h.main:h.location;g.splice(0,k,c);break}g=g.join("/");g+=b||(/\?/.test(g)?"":".js");g=("/"===g.charAt(0)||g.match(/^[\w\+\.\-]+:/)?"":m.baseUrl)+g}return m.urlArgs?g+((-1===g.indexOf("?")?"?":"&")+m.urlArgs):g},load:function(a,b){l.load(k,a,b)},execCb:function(a,b,c,d){return b.apply(d,c)},onScriptLoad:function(a){if("load"===
a.type||ha.test((a.currentTarget||a.srcElement).readyState))P=null,a=H(a),k.completeLoad(a.id)},onScriptError:function(a){var b=H(a);if(!y(b.id))return A(F("scripterror","Script error",a,[b.id]))}};k.require=k.makeRequire();return k}var l,w,B,D,s,H,P,K,ba,ca,ia=/(\/\*([\s\S]*?)\*\/|([^:]|^)\/\/(.*)$)/mg,ja=/[^.]\s*require\s*\(\s*["']([^'"\s]+)["']\s*\)/g,aa=/\.js$/,ga=/^\.\//;w=Object.prototype;var L=w.toString,da=w.hasOwnProperty,fa=Array.prototype.splice,z=!!("undefined"!==typeof window&&navigator&&
document),$=!z&&"undefined"!==typeof importScripts,ha=z&&"PLAYSTATION 3"===navigator.platform?/^complete$/:/^(complete|loaded)$/,V="undefined"!==typeof opera&&"[object Opera]"===opera.toString(),C={},q={},R=[],O=!1;if("undefined"===typeof define){if("undefined"!==typeof requirejs){if(I(requirejs))return;q=requirejs;requirejs=void 0}"undefined"!==typeof require&&!I(require)&&(q=require,require=void 0);l=requirejs=function(b,c,d,y){var g,h="_";!J(b)&&"string"!==typeof b&&(g=b,J(c)?(b=c,c=d,d=y):b=[]);
g&&g.context&&(h=g.context);(y=i(C,h))||(y=C[h]=l.s.newContext(h));g&&y.configure(g);return y.require(b,c,d)};l.config=function(b){return l(b)};l.nextTick="undefined"!==typeof setTimeout?function(b){setTimeout(b,4)}:function(b){b()};require||(require=l);l.version="2.1.4";l.jsExtRegExp=/^\/|:|\?|\.js$/;l.isBrowser=z;w=l.s={contexts:C,newContext:ea};l({});x(["toUrl","undef","defined","specified"],function(b){l[b]=function(){var c=C._;return c.require[b].apply(c,arguments)}});if(z&&(B=w.head=document.getElementsByTagName("head")[0],
D=document.getElementsByTagName("base")[0]))B=w.head=D.parentNode;l.onError=function(b){throw b;};l.load=function(b,c,d){var i=b&&b.config||{},g;if(z)return g=i.xhtml?document.createElementNS("http://www.w3.org/1999/xhtml","html:script"):document.createElement("script"),g.type=i.scriptType||"text/javascript",g.charset="utf-8",g.async=!0,g.setAttribute("data-requirecontext",b.contextName),g.setAttribute("data-requiremodule",c),g.attachEvent&&!(g.attachEvent.toString&&0>g.attachEvent.toString().indexOf("[native code"))&&
!V?(O=!0,g.attachEvent("onreadystatechange",b.onScriptLoad)):(g.addEventListener("load",b.onScriptLoad,!1),g.addEventListener("error",b.onScriptError,!1)),g.src=d,K=g,D?B.insertBefore(g,D):B.appendChild(g),K=null,g;$&&(importScripts(d),b.completeLoad(c))};z&&M(document.getElementsByTagName("script"),function(b){B||(B=b.parentNode);if(s=b.getAttribute("data-main"))return q.baseUrl||(H=s.split("/"),ba=H.pop(),ca=H.length?H.join("/")+"/":"./",q.baseUrl=ca,s=ba),s=s.replace(aa,""),q.deps=q.deps?q.deps.concat(s):
[s],!0});define=function(b,c,d){var i,g;"string"!==typeof b&&(d=c,c=b,b=null);J(c)||(d=c,c=[]);!c.length&&I(d)&&d.length&&(d.toString().replace(ia,"").replace(ja,function(b,d){c.push(d)}),c=(1===d.length?["require"]:["require","exports","module"]).concat(c));if(O){if(!(i=K))P&&"interactive"===P.readyState||M(document.getElementsByTagName("script"),function(b){if("interactive"===b.readyState)return P=b}),i=P;i&&(b||(b=i.getAttribute("data-requiremodule")),g=C[i.getAttribute("data-requirecontext")])}(g?
g.defQueue:R).push([b,c,d])};define.amd={jQuery:!0};l.exec=function(b){return eval(b)};l(q)}})(this);

define("requireLib", function(){});

/*! jQuery v1.7.2 jquery.com | jquery.org/license */
(function(a,b){function cy(a){return f.isWindow(a)?a:a.nodeType===9?a.defaultView||a.parentWindow:!1}function cu(a){if(!cj[a]){var b=c.body,d=f("<"+a+">").appendTo(b),e=d.css("display");d.remove();if(e==="none"||e===""){ck||(ck=c.createElement("iframe"),ck.frameBorder=ck.width=ck.height=0),b.appendChild(ck);if(!cl||!ck.createElement)cl=(ck.contentWindow||ck.contentDocument).document,cl.write((f.support.boxModel?"<!doctype html>":"")+"<html><body>"),cl.close();d=cl.createElement(a),cl.body.appendChild(d),e=f.css(d,"display"),b.removeChild(ck)}cj[a]=e}return cj[a]}function ct(a,b){var c={};f.each(cp.concat.apply([],cp.slice(0,b)),function(){c[this]=a});return c}function cs(){cq=b}function cr(){setTimeout(cs,0);return cq=f.now()}function ci(){try{return new a.ActiveXObject("Microsoft.XMLHTTP")}catch(b){}}function ch(){try{return new a.XMLHttpRequest}catch(b){}}function cb(a,c){a.dataFilter&&(c=a.dataFilter(c,a.dataType));var d=a.dataTypes,e={},g,h,i=d.length,j,k=d[0],l,m,n,o,p;for(g=1;g<i;g++){if(g===1)for(h in a.converters)typeof h=="string"&&(e[h.toLowerCase()]=a.converters[h]);l=k,k=d[g];if(k==="*")k=l;else if(l!=="*"&&l!==k){m=l+" "+k,n=e[m]||e["* "+k];if(!n){p=b;for(o in e){j=o.split(" ");if(j[0]===l||j[0]==="*"){p=e[j[1]+" "+k];if(p){o=e[o],o===!0?n=p:p===!0&&(n=o);break}}}}!n&&!p&&f.error("No conversion from "+m.replace(" "," to ")),n!==!0&&(c=n?n(c):p(o(c)))}}return c}function ca(a,c,d){var e=a.contents,f=a.dataTypes,g=a.responseFields,h,i,j,k;for(i in g)i in d&&(c[g[i]]=d[i]);while(f[0]==="*")f.shift(),h===b&&(h=a.mimeType||c.getResponseHeader("content-type"));if(h)for(i in e)if(e[i]&&e[i].test(h)){f.unshift(i);break}if(f[0]in d)j=f[0];else{for(i in d){if(!f[0]||a.converters[i+" "+f[0]]){j=i;break}k||(k=i)}j=j||k}if(j){j!==f[0]&&f.unshift(j);return d[j]}}function b_(a,b,c,d){if(f.isArray(b))f.each(b,function(b,e){c||bD.test(a)?d(a,e):b_(a+"["+(typeof e=="object"?b:"")+"]",e,c,d)});else if(!c&&f.type(b)==="object")for(var e in b)b_(a+"["+e+"]",b[e],c,d);else d(a,b)}function b$(a,c){var d,e,g=f.ajaxSettings.flatOptions||{};for(d in c)c[d]!==b&&((g[d]?a:e||(e={}))[d]=c[d]);e&&f.extend(!0,a,e)}function bZ(a,c,d,e,f,g){f=f||c.dataTypes[0],g=g||{},g[f]=!0;var h=a[f],i=0,j=h?h.length:0,k=a===bS,l;for(;i<j&&(k||!l);i++)l=h[i](c,d,e),typeof l=="string"&&(!k||g[l]?l=b:(c.dataTypes.unshift(l),l=bZ(a,c,d,e,l,g)));(k||!l)&&!g["*"]&&(l=bZ(a,c,d,e,"*",g));return l}function bY(a){return function(b,c){typeof b!="string"&&(c=b,b="*");if(f.isFunction(c)){var d=b.toLowerCase().split(bO),e=0,g=d.length,h,i,j;for(;e<g;e++)h=d[e],j=/^\+/.test(h),j&&(h=h.substr(1)||"*"),i=a[h]=a[h]||[],i[j?"unshift":"push"](c)}}}function bB(a,b,c){var d=b==="width"?a.offsetWidth:a.offsetHeight,e=b==="width"?1:0,g=4;if(d>0){if(c!=="border")for(;e<g;e+=2)c||(d-=parseFloat(f.css(a,"padding"+bx[e]))||0),c==="margin"?d+=parseFloat(f.css(a,c+bx[e]))||0:d-=parseFloat(f.css(a,"border"+bx[e]+"Width"))||0;return d+"px"}d=by(a,b);if(d<0||d==null)d=a.style[b];if(bt.test(d))return d;d=parseFloat(d)||0;if(c)for(;e<g;e+=2)d+=parseFloat(f.css(a,"padding"+bx[e]))||0,c!=="padding"&&(d+=parseFloat(f.css(a,"border"+bx[e]+"Width"))||0),c==="margin"&&(d+=parseFloat(f.css(a,c+bx[e]))||0);return d+"px"}function bo(a){var b=c.createElement("div");bh.appendChild(b),b.innerHTML=a.outerHTML;return b.firstChild}function bn(a){var b=(a.nodeName||"").toLowerCase();b==="input"?bm(a):b!=="script"&&typeof a.getElementsByTagName!="undefined"&&f.grep(a.getElementsByTagName("input"),bm)}function bm(a){if(a.type==="checkbox"||a.type==="radio")a.defaultChecked=a.checked}function bl(a){return typeof a.getElementsByTagName!="undefined"?a.getElementsByTagName("*"):typeof a.querySelectorAll!="undefined"?a.querySelectorAll("*"):[]}function bk(a,b){var c;b.nodeType===1&&(b.clearAttributes&&b.clearAttributes(),b.mergeAttributes&&b.mergeAttributes(a),c=b.nodeName.toLowerCase(),c==="object"?b.outerHTML=a.outerHTML:c!=="input"||a.type!=="checkbox"&&a.type!=="radio"?c==="option"?b.selected=a.defaultSelected:c==="input"||c==="textarea"?b.defaultValue=a.defaultValue:c==="script"&&b.text!==a.text&&(b.text=a.text):(a.checked&&(b.defaultChecked=b.checked=a.checked),b.value!==a.value&&(b.value=a.value)),b.removeAttribute(f.expando),b.removeAttribute("_submit_attached"),b.removeAttribute("_change_attached"))}function bj(a,b){if(b.nodeType===1&&!!f.hasData(a)){var c,d,e,g=f._data(a),h=f._data(b,g),i=g.events;if(i){delete h.handle,h.events={};for(c in i)for(d=0,e=i[c].length;d<e;d++)f.event.add(b,c,i[c][d])}h.data&&(h.data=f.extend({},h.data))}}function bi(a,b){return f.nodeName(a,"table")?a.getElementsByTagName("tbody")[0]||a.appendChild(a.ownerDocument.createElement("tbody")):a}function U(a){var b=V.split("|"),c=a.createDocumentFragment();if(c.createElement)while(b.length)c.createElement(b.pop());return c}function T(a,b,c){b=b||0;if(f.isFunction(b))return f.grep(a,function(a,d){var e=!!b.call(a,d,a);return e===c});if(b.nodeType)return f.grep(a,function(a,d){return a===b===c});if(typeof b=="string"){var d=f.grep(a,function(a){return a.nodeType===1});if(O.test(b))return f.filter(b,d,!c);b=f.filter(b,d)}return f.grep(a,function(a,d){return f.inArray(a,b)>=0===c})}function S(a){return!a||!a.parentNode||a.parentNode.nodeType===11}function K(){return!0}function J(){return!1}function n(a,b,c){var d=b+"defer",e=b+"queue",g=b+"mark",h=f._data(a,d);h&&(c==="queue"||!f._data(a,e))&&(c==="mark"||!f._data(a,g))&&setTimeout(function(){!f._data(a,e)&&!f._data(a,g)&&(f.removeData(a,d,!0),h.fire())},0)}function m(a){for(var b in a){if(b==="data"&&f.isEmptyObject(a[b]))continue;if(b!=="toJSON")return!1}return!0}function l(a,c,d){if(d===b&&a.nodeType===1){var e="data-"+c.replace(k,"-$1").toLowerCase();d=a.getAttribute(e);if(typeof d=="string"){try{d=d==="true"?!0:d==="false"?!1:d==="null"?null:f.isNumeric(d)?+d:j.test(d)?f.parseJSON(d):d}catch(g){}f.data(a,c,d)}else d=b}return d}function h(a){var b=g[a]={},c,d;a=a.split(/\s+/);for(c=0,d=a.length;c<d;c++)b[a[c]]=!0;return b}var c=a.document,d=a.navigator,e=a.location,f=function(){function J(){if(!e.isReady){try{c.documentElement.doScroll("left")}catch(a){setTimeout(J,1);return}e.ready()}}var e=function(a,b){return new e.fn.init(a,b,h)},f=a.jQuery,g=a.$,h,i=/^(?:[^#<]*(<[\w\W]+>)[^>]*$|#([\w\-]*)$)/,j=/\S/,k=/^\s+/,l=/\s+$/,m=/^<(\w+)\s*\/?>(?:<\/\1>)?$/,n=/^[\],:{}\s]*$/,o=/\\(?:["\\\/bfnrt]|u[0-9a-fA-F]{4})/g,p=/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g,q=/(?:^|:|,)(?:\s*\[)+/g,r=/(webkit)[ \/]([\w.]+)/,s=/(opera)(?:.*version)?[ \/]([\w.]+)/,t=/(msie) ([\w.]+)/,u=/(mozilla)(?:.*? rv:([\w.]+))?/,v=/-([a-z]|[0-9])/ig,w=/^-ms-/,x=function(a,b){return(b+"").toUpperCase()},y=d.userAgent,z,A,B,C=Object.prototype.toString,D=Object.prototype.hasOwnProperty,E=Array.prototype.push,F=Array.prototype.slice,G=String.prototype.trim,H=Array.prototype.indexOf,I={};e.fn=e.prototype={constructor:e,init:function(a,d,f){var g,h,j,k;if(!a)return this;if(a.nodeType){this.context=this[0]=a,this.length=1;return this}if(a==="body"&&!d&&c.body){this.context=c,this[0]=c.body,this.selector=a,this.length=1;return this}if(typeof a=="string"){a.charAt(0)!=="<"||a.charAt(a.length-1)!==">"||a.length<3?g=i.exec(a):g=[null,a,null];if(g&&(g[1]||!d)){if(g[1]){d=d instanceof e?d[0]:d,k=d?d.ownerDocument||d:c,j=m.exec(a),j?e.isPlainObject(d)?(a=[c.createElement(j[1])],e.fn.attr.call(a,d,!0)):a=[k.createElement(j[1])]:(j=e.buildFragment([g[1]],[k]),a=(j.cacheable?e.clone(j.fragment):j.fragment).childNodes);return e.merge(this,a)}h=c.getElementById(g[2]);if(h&&h.parentNode){if(h.id!==g[2])return f.find(a);this.length=1,this[0]=h}this.context=c,this.selector=a;return this}return!d||d.jquery?(d||f).find(a):this.constructor(d).find(a)}if(e.isFunction(a))return f.ready(a);a.selector!==b&&(this.selector=a.selector,this.context=a.context);return e.makeArray(a,this)},selector:"",jquery:"1.7.2",length:0,size:function(){return this.length},toArray:function(){return F.call(this,0)},get:function(a){return a==null?this.toArray():a<0?this[this.length+a]:this[a]},pushStack:function(a,b,c){var d=this.constructor();e.isArray(a)?E.apply(d,a):e.merge(d,a),d.prevObject=this,d.context=this.context,b==="find"?d.selector=this.selector+(this.selector?" ":"")+c:b&&(d.selector=this.selector+"."+b+"("+c+")");return d},each:function(a,b){return e.each(this,a,b)},ready:function(a){e.bindReady(),A.add(a);return this},eq:function(a){a=+a;return a===-1?this.slice(a):this.slice(a,a+1)},first:function(){return this.eq(0)},last:function(){return this.eq(-1)},slice:function(){return this.pushStack(F.apply(this,arguments),"slice",F.call(arguments).join(","))},map:function(a){return this.pushStack(e.map(this,function(b,c){return a.call(b,c,b)}))},end:function(){return this.prevObject||this.constructor(null)},push:E,sort:[].sort,splice:[].splice},e.fn.init.prototype=e.fn,e.extend=e.fn.extend=function(){var a,c,d,f,g,h,i=arguments[0]||{},j=1,k=arguments.length,l=!1;typeof i=="boolean"&&(l=i,i=arguments[1]||{},j=2),typeof i!="object"&&!e.isFunction(i)&&(i={}),k===j&&(i=this,--j);for(;j<k;j++)if((a=arguments[j])!=null)for(c in a){d=i[c],f=a[c];if(i===f)continue;l&&f&&(e.isPlainObject(f)||(g=e.isArray(f)))?(g?(g=!1,h=d&&e.isArray(d)?d:[]):h=d&&e.isPlainObject(d)?d:{},i[c]=e.extend(l,h,f)):f!==b&&(i[c]=f)}return i},e.extend({noConflict:function(b){a.$===e&&(a.$=g),b&&a.jQuery===e&&(a.jQuery=f);return e},isReady:!1,readyWait:1,holdReady:function(a){a?e.readyWait++:e.ready(!0)},ready:function(a){if(a===!0&&!--e.readyWait||a!==!0&&!e.isReady){if(!c.body)return setTimeout(e.ready,1);e.isReady=!0;if(a!==!0&&--e.readyWait>0)return;A.fireWith(c,[e]),e.fn.trigger&&e(c).trigger("ready").off("ready")}},bindReady:function(){if(!A){A=e.Callbacks("once memory");if(c.readyState==="complete")return setTimeout(e.ready,1);if(c.addEventListener)c.addEventListener("DOMContentLoaded",B,!1),a.addEventListener("load",e.ready,!1);else if(c.attachEvent){c.attachEvent("onreadystatechange",B),a.attachEvent("onload",e.ready);var b=!1;try{b=a.frameElement==null}catch(d){}c.documentElement.doScroll&&b&&J()}}},isFunction:function(a){return e.type(a)==="function"},isArray:Array.isArray||function(a){return e.type(a)==="array"},isWindow:function(a){return a!=null&&a==a.window},isNumeric:function(a){return!isNaN(parseFloat(a))&&isFinite(a)},type:function(a){return a==null?String(a):I[C.call(a)]||"object"},isPlainObject:function(a){if(!a||e.type(a)!=="object"||a.nodeType||e.isWindow(a))return!1;try{if(a.constructor&&!D.call(a,"constructor")&&!D.call(a.constructor.prototype,"isPrototypeOf"))return!1}catch(c){return!1}var d;for(d in a);return d===b||D.call(a,d)},isEmptyObject:function(a){for(var b in a)return!1;return!0},error:function(a){throw new Error(a)},parseJSON:function(b){if(typeof b!="string"||!b)return null;b=e.trim(b);if(a.JSON&&a.JSON.parse)return a.JSON.parse(b);if(n.test(b.replace(o,"@").replace(p,"]").replace(q,"")))return(new Function("return "+b))();e.error("Invalid JSON: "+b)},parseXML:function(c){if(typeof c!="string"||!c)return null;var d,f;try{a.DOMParser?(f=new DOMParser,d=f.parseFromString(c,"text/xml")):(d=new ActiveXObject("Microsoft.XMLDOM"),d.async="false",d.loadXML(c))}catch(g){d=b}(!d||!d.documentElement||d.getElementsByTagName("parsererror").length)&&e.error("Invalid XML: "+c);return d},noop:function(){},globalEval:function(b){b&&j.test(b)&&(a.execScript||function(b){a.eval.call(a,b)})(b)},camelCase:function(a){return a.replace(w,"ms-").replace(v,x)},nodeName:function(a,b){return a.nodeName&&a.nodeName.toUpperCase()===b.toUpperCase()},each:function(a,c,d){var f,g=0,h=a.length,i=h===b||e.isFunction(a);if(d){if(i){for(f in a)if(c.apply(a[f],d)===!1)break}else for(;g<h;)if(c.apply(a[g++],d)===!1)break}else if(i){for(f in a)if(c.call(a[f],f,a[f])===!1)break}else for(;g<h;)if(c.call(a[g],g,a[g++])===!1)break;return a},trim:G?function(a){return a==null?"":G.call(a)}:function(a){return a==null?"":(a+"").replace(k,"").replace(l,"")},makeArray:function(a,b){var c=b||[];if(a!=null){var d=e.type(a);a.length==null||d==="string"||d==="function"||d==="regexp"||e.isWindow(a)?E.call(c,a):e.merge(c,a)}return c},inArray:function(a,b,c){var d;if(b){if(H)return H.call(b,a,c);d=b.length,c=c?c<0?Math.max(0,d+c):c:0;for(;c<d;c++)if(c in b&&b[c]===a)return c}return-1},merge:function(a,c){var d=a.length,e=0;if(typeof c.length=="number")for(var f=c.length;e<f;e++)a[d++]=c[e];else while(c[e]!==b)a[d++]=c[e++];a.length=d;return a},grep:function(a,b,c){var d=[],e;c=!!c;for(var f=0,g=a.length;f<g;f++)e=!!b(a[f],f),c!==e&&d.push(a[f]);return d},map:function(a,c,d){var f,g,h=[],i=0,j=a.length,k=a instanceof e||j!==b&&typeof j=="number"&&(j>0&&a[0]&&a[j-1]||j===0||e.isArray(a));if(k)for(;i<j;i++)f=c(a[i],i,d),f!=null&&(h[h.length]=f);else for(g in a)f=c(a[g],g,d),f!=null&&(h[h.length]=f);return h.concat.apply([],h)},guid:1,proxy:function(a,c){if(typeof c=="string"){var d=a[c];c=a,a=d}if(!e.isFunction(a))return b;var f=F.call(arguments,2),g=function(){return a.apply(c,f.concat(F.call(arguments)))};g.guid=a.guid=a.guid||g.guid||e.guid++;return g},access:function(a,c,d,f,g,h,i){var j,k=d==null,l=0,m=a.length;if(d&&typeof d=="object"){for(l in d)e.access(a,c,l,d[l],1,h,f);g=1}else if(f!==b){j=i===b&&e.isFunction(f),k&&(j?(j=c,c=function(a,b,c){return j.call(e(a),c)}):(c.call(a,f),c=null));if(c)for(;l<m;l++)c(a[l],d,j?f.call(a[l],l,c(a[l],d)):f,i);g=1}return g?a:k?c.call(a):m?c(a[0],d):h},now:function(){return(new Date).getTime()},uaMatch:function(a){a=a.toLowerCase();var b=r.exec(a)||s.exec(a)||t.exec(a)||a.indexOf("compatible")<0&&u.exec(a)||[];return{browser:b[1]||"",version:b[2]||"0"}},sub:function(){function a(b,c){return new a.fn.init(b,c)}e.extend(!0,a,this),a.superclass=this,a.fn=a.prototype=this(),a.fn.constructor=a,a.sub=this.sub,a.fn.init=function(d,f){f&&f instanceof e&&!(f instanceof a)&&(f=a(f));return e.fn.init.call(this,d,f,b)},a.fn.init.prototype=a.fn;var b=a(c);return a},browser:{}}),e.each("Boolean Number String Function Array Date RegExp Object".split(" "),function(a,b){I["[object "+b+"]"]=b.toLowerCase()}),z=e.uaMatch(y),z.browser&&(e.browser[z.browser]=!0,e.browser.version=z.version),e.browser.webkit&&(e.browser.safari=!0),j.test(" ")&&(k=/^[\s\xA0]+/,l=/[\s\xA0]+$/),h=e(c),c.addEventListener?B=function(){c.removeEventListener("DOMContentLoaded",B,!1),e.ready()}:c.attachEvent&&(B=function(){c.readyState==="complete"&&(c.detachEvent("onreadystatechange",B),e.ready())});return e}(),g={};f.Callbacks=function(a){a=a?g[a]||h(a):{};var c=[],d=[],e,i,j,k,l,m,n=function(b){var d,e,g,h,i;for(d=0,e=b.length;d<e;d++)g=b[d],h=f.type(g),h==="array"?n(g):h==="function"&&(!a.unique||!p.has(g))&&c.push(g)},o=function(b,f){f=f||[],e=!a.memory||[b,f],i=!0,j=!0,m=k||0,k=0,l=c.length;for(;c&&m<l;m++)if(c[m].apply(b,f)===!1&&a.stopOnFalse){e=!0;break}j=!1,c&&(a.once?e===!0?p.disable():c=[]:d&&d.length&&(e=d.shift(),p.fireWith(e[0],e[1])))},p={add:function(){if(c){var a=c.length;n(arguments),j?l=c.length:e&&e!==!0&&(k=a,o(e[0],e[1]))}return this},remove:function(){if(c){var b=arguments,d=0,e=b.length;for(;d<e;d++)for(var f=0;f<c.length;f++)if(b[d]===c[f]){j&&f<=l&&(l--,f<=m&&m--),c.splice(f--,1);if(a.unique)break}}return this},has:function(a){if(c){var b=0,d=c.length;for(;b<d;b++)if(a===c[b])return!0}return!1},empty:function(){c=[];return this},disable:function(){c=d=e=b;return this},disabled:function(){return!c},lock:function(){d=b,(!e||e===!0)&&p.disable();return this},locked:function(){return!d},fireWith:function(b,c){d&&(j?a.once||d.push([b,c]):(!a.once||!e)&&o(b,c));return this},fire:function(){p.fireWith(this,arguments);return this},fired:function(){return!!i}};return p};var i=[].slice;f.extend({Deferred:function(a){var b=f.Callbacks("once memory"),c=f.Callbacks("once memory"),d=f.Callbacks("memory"),e="pending",g={resolve:b,reject:c,notify:d},h={done:b.add,fail:c.add,progress:d.add,state:function(){return e},isResolved:b.fired,isRejected:c.fired,then:function(a,b,c){i.done(a).fail(b).progress(c);return this},always:function(){i.done.apply(i,arguments).fail.apply(i,arguments);return this},pipe:function(a,b,c){return f.Deferred(function(d){f.each({done:[a,"resolve"],fail:[b,"reject"],progress:[c,"notify"]},function(a,b){var c=b[0],e=b[1],g;f.isFunction(c)?i[a](function(){g=c.apply(this,arguments),g&&f.isFunction(g.promise)?g.promise().then(d.resolve,d.reject,d.notify):d[e+"With"](this===i?d:this,[g])}):i[a](d[e])})}).promise()},promise:function(a){if(a==null)a=h;else for(var b in h)a[b]=h[b];return a}},i=h.promise({}),j;for(j in g)i[j]=g[j].fire,i[j+"With"]=g[j].fireWith;i.done(function(){e="resolved"},c.disable,d.lock).fail(function(){e="rejected"},b.disable,d.lock),a&&a.call(i,i);return i},when:function(a){function m(a){return function(b){e[a]=arguments.length>1?i.call(arguments,0):b,j.notifyWith(k,e)}}function l(a){return function(c){b[a]=arguments.length>1?i.call(arguments,0):c,--g||j.resolveWith(j,b)}}var b=i.call(arguments,0),c=0,d=b.length,e=Array(d),g=d,h=d,j=d<=1&&a&&f.isFunction(a.promise)?a:f.Deferred(),k=j.promise();if(d>1){for(;c<d;c++)b[c]&&b[c].promise&&f.isFunction(b[c].promise)?b[c].promise().then(l(c),j.reject,m(c)):--g;g||j.resolveWith(j,b)}else j!==a&&j.resolveWith(j,d?[a]:[]);return k}}),f.support=function(){var b,d,e,g,h,i,j,k,l,m,n,o,p=c.createElement("div"),q=c.documentElement;p.setAttribute("className","t"),p.innerHTML="   <link/><table></table><a href='/a' style='top:1px;float:left;opacity:.55;'>a</a><input type='checkbox'/>",d=p.getElementsByTagName("*"),e=p.getElementsByTagName("a")[0];if(!d||!d.length||!e)return{};g=c.createElement("select"),h=g.appendChild(c.createElement("option")),i=p.getElementsByTagName("input")[0],b={leadingWhitespace:p.firstChild.nodeType===3,tbody:!p.getElementsByTagName("tbody").length,htmlSerialize:!!p.getElementsByTagName("link").length,style:/top/.test(e.getAttribute("style")),hrefNormalized:e.getAttribute("href")==="/a",opacity:/^0.55/.test(e.style.opacity),cssFloat:!!e.style.cssFloat,checkOn:i.value==="on",optSelected:h.selected,getSetAttribute:p.className!=="t",enctype:!!c.createElement("form").enctype,html5Clone:c.createElement("nav").cloneNode(!0).outerHTML!=="<:nav></:nav>",submitBubbles:!0,changeBubbles:!0,focusinBubbles:!1,deleteExpando:!0,noCloneEvent:!0,inlineBlockNeedsLayout:!1,shrinkWrapBlocks:!1,reliableMarginRight:!0,pixelMargin:!0},f.boxModel=b.boxModel=c.compatMode==="CSS1Compat",i.checked=!0,b.noCloneChecked=i.cloneNode(!0).checked,g.disabled=!0,b.optDisabled=!h.disabled;try{delete p.test}catch(r){b.deleteExpando=!1}!p.addEventListener&&p.attachEvent&&p.fireEvent&&(p.attachEvent("onclick",function(){b.noCloneEvent=!1}),p.cloneNode(!0).fireEvent("onclick")),i=c.createElement("input"),i.value="t",i.setAttribute("type","radio"),b.radioValue=i.value==="t",i.setAttribute("checked","checked"),i.setAttribute("name","t"),p.appendChild(i),j=c.createDocumentFragment(),j.appendChild(p.lastChild),b.checkClone=j.cloneNode(!0).cloneNode(!0).lastChild.checked,b.appendChecked=i.checked,j.removeChild(i),j.appendChild(p);if(p.attachEvent)for(n in{submit:1,change:1,focusin:1})m="on"+n,o=m in p,o||(p.setAttribute(m,"return;"),o=typeof p[m]=="function"),b[n+"Bubbles"]=o;j.removeChild(p),j=g=h=p=i=null,f(function(){var d,e,g,h,i,j,l,m,n,q,r,s,t,u=c.getElementsByTagName("body")[0];!u||(m=1,t="padding:0;margin:0;border:",r="position:absolute;top:0;left:0;width:1px;height:1px;",s=t+"0;visibility:hidden;",n="style='"+r+t+"5px solid #000;",q="<div "+n+"display:block;'><div style='"+t+"0;display:block;overflow:hidden;'></div></div>"+"<table "+n+"' cellpadding='0' cellspacing='0'>"+"<tr><td></td></tr></table>",d=c.createElement("div"),d.style.cssText=s+"width:0;height:0;position:static;top:0;margin-top:"+m+"px",u.insertBefore(d,u.firstChild),p=c.createElement("div"),d.appendChild(p),p.innerHTML="<table><tr><td style='"+t+"0;display:none'></td><td>t</td></tr></table>",k=p.getElementsByTagName("td"),o=k[0].offsetHeight===0,k[0].style.display="",k[1].style.display="none",b.reliableHiddenOffsets=o&&k[0].offsetHeight===0,a.getComputedStyle&&(p.innerHTML="",l=c.createElement("div"),l.style.width="0",l.style.marginRight="0",p.style.width="2px",p.appendChild(l),b.reliableMarginRight=(parseInt((a.getComputedStyle(l,null)||{marginRight:0}).marginRight,10)||0)===0),typeof p.style.zoom!="undefined"&&(p.innerHTML="",p.style.width=p.style.padding="1px",p.style.border=0,p.style.overflow="hidden",p.style.display="inline",p.style.zoom=1,b.inlineBlockNeedsLayout=p.offsetWidth===3,p.style.display="block",p.style.overflow="visible",p.innerHTML="<div style='width:5px;'></div>",b.shrinkWrapBlocks=p.offsetWidth!==3),p.style.cssText=r+s,p.innerHTML=q,e=p.firstChild,g=e.firstChild,i=e.nextSibling.firstChild.firstChild,j={doesNotAddBorder:g.offsetTop!==5,doesAddBorderForTableAndCells:i.offsetTop===5},g.style.position="fixed",g.style.top="20px",j.fixedPosition=g.offsetTop===20||g.offsetTop===15,g.style.position=g.style.top="",e.style.overflow="hidden",e.style.position="relative",j.subtractsBorderForOverflowNotVisible=g.offsetTop===-5,j.doesNotIncludeMarginInBodyOffset=u.offsetTop!==m,a.getComputedStyle&&(p.style.marginTop="1%",b.pixelMargin=(a.getComputedStyle(p,null)||{marginTop:0}).marginTop!=="1%"),typeof d.style.zoom!="undefined"&&(d.style.zoom=1),u.removeChild(d),l=p=d=null,f.extend(b,j))});return b}();var j=/^(?:\{.*\}|\[.*\])$/,k=/([A-Z])/g;f.extend({cache:{},uuid:0,expando:"jQuery"+(f.fn.jquery+Math.random()).replace(/\D/g,""),noData:{embed:!0,object:"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000",applet:!0},hasData:function(a){a=a.nodeType?f.cache[a[f.expando]]:a[f.expando];return!!a&&!m(a)},data:function(a,c,d,e){if(!!f.acceptData(a)){var g,h,i,j=f.expando,k=typeof c=="string",l=a.nodeType,m=l?f.cache:a,n=l?a[j]:a[j]&&j,o=c==="events";if((!n||!m[n]||!o&&!e&&!m[n].data)&&k&&d===b)return;n||(l?a[j]=n=++f.uuid:n=j),m[n]||(m[n]={},l||(m[n].toJSON=f.noop));if(typeof c=="object"||typeof c=="function")e?m[n]=f.extend(m[n],c):m[n].data=f.extend(m[n].data,c);g=h=m[n],e||(h.data||(h.data={}),h=h.data),d!==b&&(h[f.camelCase(c)]=d);if(o&&!h[c])return g.events;k?(i=h[c],i==null&&(i=h[f.camelCase(c)])):i=h;return i}},removeData:function(a,b,c){if(!!f.acceptData(a)){var d,e,g,h=f.expando,i=a.nodeType,j=i?f.cache:a,k=i?a[h]:h;if(!j[k])return;if(b){d=c?j[k]:j[k].data;if(d){f.isArray(b)||(b in d?b=[b]:(b=f.camelCase(b),b in d?b=[b]:b=b.split(" ")));for(e=0,g=b.length;e<g;e++)delete d[b[e]];if(!(c?m:f.isEmptyObject)(d))return}}if(!c){delete j[k].data;if(!m(j[k]))return}f.support.deleteExpando||!j.setInterval?delete j[k]:j[k]=null,i&&(f.support.deleteExpando?delete a[h]:a.removeAttribute?a.removeAttribute(h):a[h]=null)}},_data:function(a,b,c){return f.data(a,b,c,!0)},acceptData:function(a){if(a.nodeName){var b=f.noData[a.nodeName.toLowerCase()];if(b)return b!==!0&&a.getAttribute("classid")===b}return!0}}),f.fn.extend({data:function(a,c){var d,e,g,h,i,j=this[0],k=0,m=null;if(a===b){if(this.length){m=f.data(j);if(j.nodeType===1&&!f._data(j,"parsedAttrs")){g=j.attributes;for(i=g.length;k<i;k++)h=g[k].name,h.indexOf("data-")===0&&(h=f.camelCase(h.substring(5)),l(j,h,m[h]));f._data(j,"parsedAttrs",!0)}}return m}if(typeof a=="object")return this.each(function(){f.data(this,a)});d=a.split(".",2),d[1]=d[1]?"."+d[1]:"",e=d[1]+"!";return f.access(this,function(c){if(c===b){m=this.triggerHandler("getData"+e,[d[0]]),m===b&&j&&(m=f.data(j,a),m=l(j,a,m));return m===b&&d[1]?this.data(d[0]):m}d[1]=c,this.each(function(){var b=f(this);b.triggerHandler("setData"+e,d),f.data(this,a,c),b.triggerHandler("changeData"+e,d)})},null,c,arguments.length>1,null,!1)},removeData:function(a){return this.each(function(){f.removeData(this,a)})}}),f.extend({_mark:function(a,b){a&&(b=(b||"fx")+"mark",f._data(a,b,(f._data(a,b)||0)+1))},_unmark:function(a,b,c){a!==!0&&(c=b,b=a,a=!1);if(b){c=c||"fx";var d=c+"mark",e=a?0:(f._data(b,d)||1)-1;e?f._data(b,d,e):(f.removeData(b,d,!0),n(b,c,"mark"))}},queue:function(a,b,c){var d;if(a){b=(b||"fx")+"queue",d=f._data(a,b),c&&(!d||f.isArray(c)?d=f._data(a,b,f.makeArray(c)):d.push(c));return d||[]}},dequeue:function(a,b){b=b||"fx";var c=f.queue(a,b),d=c.shift(),e={};d==="inprogress"&&(d=c.shift()),d&&(b==="fx"&&c.unshift("inprogress"),f._data(a,b+".run",e),d.call(a,function(){f.dequeue(a,b)},e)),c.length||(f.removeData(a,b+"queue "+b+".run",!0),n(a,b,"queue"))}}),f.fn.extend({queue:function(a,c){var d=2;typeof a!="string"&&(c=a,a="fx",d--);if(arguments.length<d)return f.queue(this[0],a);return c===b?this:this.each(function(){var b=f.queue(this,a,c);a==="fx"&&b[0]!=="inprogress"&&f.dequeue(this,a)})},dequeue:function(a){return this.each(function(){f.dequeue(this,a)})},delay:function(a,b){a=f.fx?f.fx.speeds[a]||a:a,b=b||"fx";return this.queue(b,function(b,c){var d=setTimeout(b,a);c.stop=function(){clearTimeout(d)}})},clearQueue:function(a){return this.queue(a||"fx",[])},promise:function(a,c){function m(){--h||d.resolveWith(e,[e])}typeof a!="string"&&(c=a,a=b),a=a||"fx";var d=f.Deferred(),e=this,g=e.length,h=1,i=a+"defer",j=a+"queue",k=a+"mark",l;while(g--)if(l=f.data(e[g],i,b,!0)||(f.data(e[g],j,b,!0)||f.data(e[g],k,b,!0))&&f.data(e[g],i,f.Callbacks("once memory"),!0))h++,l.add(m);m();return d.promise(c)}});var o=/[\n\t\r]/g,p=/\s+/,q=/\r/g,r=/^(?:button|input)$/i,s=/^(?:button|input|object|select|textarea)$/i,t=/^a(?:rea)?$/i,u=/^(?:autofocus|autoplay|async|checked|controls|defer|disabled|hidden|loop|multiple|open|readonly|required|scoped|selected)$/i,v=f.support.getSetAttribute,w,x,y;f.fn.extend({attr:function(a,b){return f.access(this,f.attr,a,b,arguments.length>1)},removeAttr:function(a){return this.each(function(){f.removeAttr(this,a)})},prop:function(a,b){return f.access(this,f.prop,a,b,arguments.length>1)},removeProp:function(a){a=f.propFix[a]||a;return this.each(function(){try{this[a]=b,delete this[a]}catch(c){}})},addClass:function(a){var b,c,d,e,g,h,i;if(f.isFunction(a))return this.each(function(b){f(this).addClass(a.call(this,b,this.className))});if(a&&typeof a=="string"){b=a.split(p);for(c=0,d=this.length;c<d;c++){e=this[c];if(e.nodeType===1)if(!e.className&&b.length===1)e.className=a;else{g=" "+e.className+" ";for(h=0,i=b.length;h<i;h++)~g.indexOf(" "+b[h]+" ")||(g+=b[h]+" ");e.className=f.trim(g)}}}return this},removeClass:function(a){var c,d,e,g,h,i,j;if(f.isFunction(a))return this.each(function(b){f(this).removeClass(a.call(this,b,this.className))});if(a&&typeof a=="string"||a===b){c=(a||"").split(p);for(d=0,e=this.length;d<e;d++){g=this[d];if(g.nodeType===1&&g.className)if(a){h=(" "+g.className+" ").replace(o," ");for(i=0,j=c.length;i<j;i++)h=h.replace(" "+c[i]+" "," ");g.className=f.trim(h)}else g.className=""}}return this},toggleClass:function(a,b){var c=typeof a,d=typeof b=="boolean";if(f.isFunction(a))return this.each(function(c){f(this).toggleClass(a.call(this,c,this.className,b),b)});return this.each(function(){if(c==="string"){var e,g=0,h=f(this),i=b,j=a.split(p);while(e=j[g++])i=d?i:!h.hasClass(e),h[i?"addClass":"removeClass"](e)}else if(c==="undefined"||c==="boolean")this.className&&f._data(this,"__className__",this.className),this.className=this.className||a===!1?"":f._data(this,"__className__")||""})},hasClass:function(a){var b=" "+a+" ",c=0,d=this.length;for(;c<d;c++)if(this[c].nodeType===1&&(" "+this[c].className+" ").replace(o," ").indexOf(b)>-1)return!0;return!1},val:function(a){var c,d,e,g=this[0];{if(!!arguments.length){e=f.isFunction(a);return this.each(function(d){var g=f(this),h;if(this.nodeType===1){e?h=a.call(this,d,g.val()):h=a,h==null?h="":typeof h=="number"?h+="":f.isArray(h)&&(h=f.map(h,function(a){return a==null?"":a+""})),c=f.valHooks[this.type]||f.valHooks[this.nodeName.toLowerCase()];if(!c||!("set"in c)||c.set(this,h,"value")===b)this.value=h}})}if(g){c=f.valHooks[g.type]||f.valHooks[g.nodeName.toLowerCase()];if(c&&"get"in c&&(d=c.get(g,"value"))!==b)return d;d=g.value;return typeof d=="string"?d.replace(q,""):d==null?"":d}}}}),f.extend({valHooks:{option:{get:function(a){var b=a.attributes.value;return!b||b.specified?a.value:a.text}},select:{get:function(a){var b,c,d,e,g=a.selectedIndex,h=[],i=a.options,j=a.type==="select-one";if(g<0)return null;c=j?g:0,d=j?g+1:i.length;for(;c<d;c++){e=i[c];if(e.selected&&(f.support.optDisabled?!e.disabled:e.getAttribute("disabled")===null)&&(!e.parentNode.disabled||!f.nodeName(e.parentNode,"optgroup"))){b=f(e).val();if(j)return b;h.push(b)}}if(j&&!h.length&&i.length)return f(i[g]).val();return h},set:function(a,b){var c=f.makeArray(b);f(a).find("option").each(function(){this.selected=f.inArray(f(this).val(),c)>=0}),c.length||(a.selectedIndex=-1);return c}}},attrFn:{val:!0,css:!0,html:!0,text:!0,data:!0,width:!0,height:!0,offset:!0},attr:function(a,c,d,e){var g,h,i,j=a.nodeType;if(!!a&&j!==3&&j!==8&&j!==2){if(e&&c in f.attrFn)return f(a)[c](d);if(typeof a.getAttribute=="undefined")return f.prop(a,c,d);i=j!==1||!f.isXMLDoc(a),i&&(c=c.toLowerCase(),h=f.attrHooks[c]||(u.test(c)?x:w));if(d!==b){if(d===null){f.removeAttr(a,c);return}if(h&&"set"in h&&i&&(g=h.set(a,d,c))!==b)return g;a.setAttribute(c,""+d);return d}if(h&&"get"in h&&i&&(g=h.get(a,c))!==null)return g;g=a.getAttribute(c);return g===null?b:g}},removeAttr:function(a,b){var c,d,e,g,h,i=0;if(b&&a.nodeType===1){d=b.toLowerCase().split(p),g=d.length;for(;i<g;i++)e=d[i],e&&(c=f.propFix[e]||e,h=u.test(e),h||f.attr(a,e,""),a.removeAttribute(v?e:c),h&&c in a&&(a[c]=!1))}},attrHooks:{type:{set:function(a,b){if(r.test(a.nodeName)&&a.parentNode)f.error("type property can't be changed");else if(!f.support.radioValue&&b==="radio"&&f.nodeName(a,"input")){var c=a.value;a.setAttribute("type",b),c&&(a.value=c);return b}}},value:{get:function(a,b){if(w&&f.nodeName(a,"button"))return w.get(a,b);return b in a?a.value:null},set:function(a,b,c){if(w&&f.nodeName(a,"button"))return w.set(a,b,c);a.value=b}}},propFix:{tabindex:"tabIndex",readonly:"readOnly","for":"htmlFor","class":"className",maxlength:"maxLength",cellspacing:"cellSpacing",cellpadding:"cellPadding",rowspan:"rowSpan",colspan:"colSpan",usemap:"useMap",frameborder:"frameBorder",contenteditable:"contentEditable"},prop:function(a,c,d){var e,g,h,i=a.nodeType;if(!!a&&i!==3&&i!==8&&i!==2){h=i!==1||!f.isXMLDoc(a),h&&(c=f.propFix[c]||c,g=f.propHooks[c]);return d!==b?g&&"set"in g&&(e=g.set(a,d,c))!==b?e:a[c]=d:g&&"get"in g&&(e=g.get(a,c))!==null?e:a[c]}},propHooks:{tabIndex:{get:function(a){var c=a.getAttributeNode("tabindex");return c&&c.specified?parseInt(c.value,10):s.test(a.nodeName)||t.test(a.nodeName)&&a.href?0:b}}}}),f.attrHooks.tabindex=f.propHooks.tabIndex,x={get:function(a,c){var d,e=f.prop(a,c);return e===!0||typeof e!="boolean"&&(d=a.getAttributeNode(c))&&d.nodeValue!==!1?c.toLowerCase():b},set:function(a,b,c){var d;b===!1?f.removeAttr(a,c):(d=f.propFix[c]||c,d in a&&(a[d]=!0),a.setAttribute(c,c.toLowerCase()));return c}},v||(y={name:!0,id:!0,coords:!0},w=f.valHooks.button={get:function(a,c){var d;d=a.getAttributeNode(c);return d&&(y[c]?d.nodeValue!=="":d.specified)?d.nodeValue:b},set:function(a,b,d){var e=a.getAttributeNode(d);e||(e=c.createAttribute(d),a.setAttributeNode(e));return e.nodeValue=b+""}},f.attrHooks.tabindex.set=w.set,f.each(["width","height"],function(a,b){f.attrHooks[b]=f.extend(f.attrHooks[b],{set:function(a,c){if(c===""){a.setAttribute(b,"auto");return c}}})}),f.attrHooks.contenteditable={get:w.get,set:function(a,b,c){b===""&&(b="false"),w.set(a,b,c)}}),f.support.hrefNormalized||f.each(["href","src","width","height"],function(a,c){f.attrHooks[c]=f.extend(f.attrHooks[c],{get:function(a){var d=a.getAttribute(c,2);return d===null?b:d}})}),f.support.style||(f.attrHooks.style={get:function(a){return a.style.cssText.toLowerCase()||b},set:function(a,b){return a.style.cssText=""+b}}),f.support.optSelected||(f.propHooks.selected=f.extend(f.propHooks.selected,{get:function(a){var b=a.parentNode;b&&(b.selectedIndex,b.parentNode&&b.parentNode.selectedIndex);return null}})),f.support.enctype||(f.propFix.enctype="encoding"),f.support.checkOn||f.each(["radio","checkbox"],function(){f.valHooks[this]={get:function(a){return a.getAttribute("value")===null?"on":a.value}}}),f.each(["radio","checkbox"],function(){f.valHooks[this]=f.extend(f.valHooks[this],{set:function(a,b){if(f.isArray(b))return a.checked=f.inArray(f(a).val(),b)>=0}})});var z=/^(?:textarea|input|select)$/i,A=/^([^\.]*)?(?:\.(.+))?$/,B=/(?:^|\s)hover(\.\S+)?\b/,C=/^key/,D=/^(?:mouse|contextmenu)|click/,E=/^(?:focusinfocus|focusoutblur)$/,F=/^(\w*)(?:#([\w\-]+))?(?:\.([\w\-]+))?$/,G=function(
a){var b=F.exec(a);b&&(b[1]=(b[1]||"").toLowerCase(),b[3]=b[3]&&new RegExp("(?:^|\\s)"+b[3]+"(?:\\s|$)"));return b},H=function(a,b){var c=a.attributes||{};return(!b[1]||a.nodeName.toLowerCase()===b[1])&&(!b[2]||(c.id||{}).value===b[2])&&(!b[3]||b[3].test((c["class"]||{}).value))},I=function(a){return f.event.special.hover?a:a.replace(B,"mouseenter$1 mouseleave$1")};f.event={add:function(a,c,d,e,g){var h,i,j,k,l,m,n,o,p,q,r,s;if(!(a.nodeType===3||a.nodeType===8||!c||!d||!(h=f._data(a)))){d.handler&&(p=d,d=p.handler,g=p.selector),d.guid||(d.guid=f.guid++),j=h.events,j||(h.events=j={}),i=h.handle,i||(h.handle=i=function(a){return typeof f!="undefined"&&(!a||f.event.triggered!==a.type)?f.event.dispatch.apply(i.elem,arguments):b},i.elem=a),c=f.trim(I(c)).split(" ");for(k=0;k<c.length;k++){l=A.exec(c[k])||[],m=l[1],n=(l[2]||"").split(".").sort(),s=f.event.special[m]||{},m=(g?s.delegateType:s.bindType)||m,s=f.event.special[m]||{},o=f.extend({type:m,origType:l[1],data:e,handler:d,guid:d.guid,selector:g,quick:g&&G(g),namespace:n.join(".")},p),r=j[m];if(!r){r=j[m]=[],r.delegateCount=0;if(!s.setup||s.setup.call(a,e,n,i)===!1)a.addEventListener?a.addEventListener(m,i,!1):a.attachEvent&&a.attachEvent("on"+m,i)}s.add&&(s.add.call(a,o),o.handler.guid||(o.handler.guid=d.guid)),g?r.splice(r.delegateCount++,0,o):r.push(o),f.event.global[m]=!0}a=null}},global:{},remove:function(a,b,c,d,e){var g=f.hasData(a)&&f._data(a),h,i,j,k,l,m,n,o,p,q,r,s;if(!!g&&!!(o=g.events)){b=f.trim(I(b||"")).split(" ");for(h=0;h<b.length;h++){i=A.exec(b[h])||[],j=k=i[1],l=i[2];if(!j){for(j in o)f.event.remove(a,j+b[h],c,d,!0);continue}p=f.event.special[j]||{},j=(d?p.delegateType:p.bindType)||j,r=o[j]||[],m=r.length,l=l?new RegExp("(^|\\.)"+l.split(".").sort().join("\\.(?:.*\\.)?")+"(\\.|$)"):null;for(n=0;n<r.length;n++)s=r[n],(e||k===s.origType)&&(!c||c.guid===s.guid)&&(!l||l.test(s.namespace))&&(!d||d===s.selector||d==="**"&&s.selector)&&(r.splice(n--,1),s.selector&&r.delegateCount--,p.remove&&p.remove.call(a,s));r.length===0&&m!==r.length&&((!p.teardown||p.teardown.call(a,l)===!1)&&f.removeEvent(a,j,g.handle),delete o[j])}f.isEmptyObject(o)&&(q=g.handle,q&&(q.elem=null),f.removeData(a,["events","handle"],!0))}},customEvent:{getData:!0,setData:!0,changeData:!0},trigger:function(c,d,e,g){if(!e||e.nodeType!==3&&e.nodeType!==8){var h=c.type||c,i=[],j,k,l,m,n,o,p,q,r,s;if(E.test(h+f.event.triggered))return;h.indexOf("!")>=0&&(h=h.slice(0,-1),k=!0),h.indexOf(".")>=0&&(i=h.split("."),h=i.shift(),i.sort());if((!e||f.event.customEvent[h])&&!f.event.global[h])return;c=typeof c=="object"?c[f.expando]?c:new f.Event(h,c):new f.Event(h),c.type=h,c.isTrigger=!0,c.exclusive=k,c.namespace=i.join("."),c.namespace_re=c.namespace?new RegExp("(^|\\.)"+i.join("\\.(?:.*\\.)?")+"(\\.|$)"):null,o=h.indexOf(":")<0?"on"+h:"";if(!e){j=f.cache;for(l in j)j[l].events&&j[l].events[h]&&f.event.trigger(c,d,j[l].handle.elem,!0);return}c.result=b,c.target||(c.target=e),d=d!=null?f.makeArray(d):[],d.unshift(c),p=f.event.special[h]||{};if(p.trigger&&p.trigger.apply(e,d)===!1)return;r=[[e,p.bindType||h]];if(!g&&!p.noBubble&&!f.isWindow(e)){s=p.delegateType||h,m=E.test(s+h)?e:e.parentNode,n=null;for(;m;m=m.parentNode)r.push([m,s]),n=m;n&&n===e.ownerDocument&&r.push([n.defaultView||n.parentWindow||a,s])}for(l=0;l<r.length&&!c.isPropagationStopped();l++)m=r[l][0],c.type=r[l][1],q=(f._data(m,"events")||{})[c.type]&&f._data(m,"handle"),q&&q.apply(m,d),q=o&&m[o],q&&f.acceptData(m)&&q.apply(m,d)===!1&&c.preventDefault();c.type=h,!g&&!c.isDefaultPrevented()&&(!p._default||p._default.apply(e.ownerDocument,d)===!1)&&(h!=="click"||!f.nodeName(e,"a"))&&f.acceptData(e)&&o&&e[h]&&(h!=="focus"&&h!=="blur"||c.target.offsetWidth!==0)&&!f.isWindow(e)&&(n=e[o],n&&(e[o]=null),f.event.triggered=h,e[h](),f.event.triggered=b,n&&(e[o]=n));return c.result}},dispatch:function(c){c=f.event.fix(c||a.event);var d=(f._data(this,"events")||{})[c.type]||[],e=d.delegateCount,g=[].slice.call(arguments,0),h=!c.exclusive&&!c.namespace,i=f.event.special[c.type]||{},j=[],k,l,m,n,o,p,q,r,s,t,u;g[0]=c,c.delegateTarget=this;if(!i.preDispatch||i.preDispatch.call(this,c)!==!1){if(e&&(!c.button||c.type!=="click")){n=f(this),n.context=this.ownerDocument||this;for(m=c.target;m!=this;m=m.parentNode||this)if(m.disabled!==!0){p={},r=[],n[0]=m;for(k=0;k<e;k++)s=d[k],t=s.selector,p[t]===b&&(p[t]=s.quick?H(m,s.quick):n.is(t)),p[t]&&r.push(s);r.length&&j.push({elem:m,matches:r})}}d.length>e&&j.push({elem:this,matches:d.slice(e)});for(k=0;k<j.length&&!c.isPropagationStopped();k++){q=j[k],c.currentTarget=q.elem;for(l=0;l<q.matches.length&&!c.isImmediatePropagationStopped();l++){s=q.matches[l];if(h||!c.namespace&&!s.namespace||c.namespace_re&&c.namespace_re.test(s.namespace))c.data=s.data,c.handleObj=s,o=((f.event.special[s.origType]||{}).handle||s.handler).apply(q.elem,g),o!==b&&(c.result=o,o===!1&&(c.preventDefault(),c.stopPropagation()))}}i.postDispatch&&i.postDispatch.call(this,c);return c.result}},props:"attrChange attrName relatedNode srcElement altKey bubbles cancelable ctrlKey currentTarget eventPhase metaKey relatedTarget shiftKey target timeStamp view which".split(" "),fixHooks:{},keyHooks:{props:"char charCode key keyCode".split(" "),filter:function(a,b){a.which==null&&(a.which=b.charCode!=null?b.charCode:b.keyCode);return a}},mouseHooks:{props:"button buttons clientX clientY fromElement offsetX offsetY pageX pageY screenX screenY toElement".split(" "),filter:function(a,d){var e,f,g,h=d.button,i=d.fromElement;a.pageX==null&&d.clientX!=null&&(e=a.target.ownerDocument||c,f=e.documentElement,g=e.body,a.pageX=d.clientX+(f&&f.scrollLeft||g&&g.scrollLeft||0)-(f&&f.clientLeft||g&&g.clientLeft||0),a.pageY=d.clientY+(f&&f.scrollTop||g&&g.scrollTop||0)-(f&&f.clientTop||g&&g.clientTop||0)),!a.relatedTarget&&i&&(a.relatedTarget=i===a.target?d.toElement:i),!a.which&&h!==b&&(a.which=h&1?1:h&2?3:h&4?2:0);return a}},fix:function(a){if(a[f.expando])return a;var d,e,g=a,h=f.event.fixHooks[a.type]||{},i=h.props?this.props.concat(h.props):this.props;a=f.Event(g);for(d=i.length;d;)e=i[--d],a[e]=g[e];a.target||(a.target=g.srcElement||c),a.target.nodeType===3&&(a.target=a.target.parentNode),a.metaKey===b&&(a.metaKey=a.ctrlKey);return h.filter?h.filter(a,g):a},special:{ready:{setup:f.bindReady},load:{noBubble:!0},focus:{delegateType:"focusin"},blur:{delegateType:"focusout"},beforeunload:{setup:function(a,b,c){f.isWindow(this)&&(this.onbeforeunload=c)},teardown:function(a,b){this.onbeforeunload===b&&(this.onbeforeunload=null)}}},simulate:function(a,b,c,d){var e=f.extend(new f.Event,c,{type:a,isSimulated:!0,originalEvent:{}});d?f.event.trigger(e,null,b):f.event.dispatch.call(b,e),e.isDefaultPrevented()&&c.preventDefault()}},f.event.handle=f.event.dispatch,f.removeEvent=c.removeEventListener?function(a,b,c){a.removeEventListener&&a.removeEventListener(b,c,!1)}:function(a,b,c){a.detachEvent&&a.detachEvent("on"+b,c)},f.Event=function(a,b){if(!(this instanceof f.Event))return new f.Event(a,b);a&&a.type?(this.originalEvent=a,this.type=a.type,this.isDefaultPrevented=a.defaultPrevented||a.returnValue===!1||a.getPreventDefault&&a.getPreventDefault()?K:J):this.type=a,b&&f.extend(this,b),this.timeStamp=a&&a.timeStamp||f.now(),this[f.expando]=!0},f.Event.prototype={preventDefault:function(){this.isDefaultPrevented=K;var a=this.originalEvent;!a||(a.preventDefault?a.preventDefault():a.returnValue=!1)},stopPropagation:function(){this.isPropagationStopped=K;var a=this.originalEvent;!a||(a.stopPropagation&&a.stopPropagation(),a.cancelBubble=!0)},stopImmediatePropagation:function(){this.isImmediatePropagationStopped=K,this.stopPropagation()},isDefaultPrevented:J,isPropagationStopped:J,isImmediatePropagationStopped:J},f.each({mouseenter:"mouseover",mouseleave:"mouseout"},function(a,b){f.event.special[a]={delegateType:b,bindType:b,handle:function(a){var c=this,d=a.relatedTarget,e=a.handleObj,g=e.selector,h;if(!d||d!==c&&!f.contains(c,d))a.type=e.origType,h=e.handler.apply(this,arguments),a.type=b;return h}}}),f.support.submitBubbles||(f.event.special.submit={setup:function(){if(f.nodeName(this,"form"))return!1;f.event.add(this,"click._submit keypress._submit",function(a){var c=a.target,d=f.nodeName(c,"input")||f.nodeName(c,"button")?c.form:b;d&&!d._submit_attached&&(f.event.add(d,"submit._submit",function(a){a._submit_bubble=!0}),d._submit_attached=!0)})},postDispatch:function(a){a._submit_bubble&&(delete a._submit_bubble,this.parentNode&&!a.isTrigger&&f.event.simulate("submit",this.parentNode,a,!0))},teardown:function(){if(f.nodeName(this,"form"))return!1;f.event.remove(this,"._submit")}}),f.support.changeBubbles||(f.event.special.change={setup:function(){if(z.test(this.nodeName)){if(this.type==="checkbox"||this.type==="radio")f.event.add(this,"propertychange._change",function(a){a.originalEvent.propertyName==="checked"&&(this._just_changed=!0)}),f.event.add(this,"click._change",function(a){this._just_changed&&!a.isTrigger&&(this._just_changed=!1,f.event.simulate("change",this,a,!0))});return!1}f.event.add(this,"beforeactivate._change",function(a){var b=a.target;z.test(b.nodeName)&&!b._change_attached&&(f.event.add(b,"change._change",function(a){this.parentNode&&!a.isSimulated&&!a.isTrigger&&f.event.simulate("change",this.parentNode,a,!0)}),b._change_attached=!0)})},handle:function(a){var b=a.target;if(this!==b||a.isSimulated||a.isTrigger||b.type!=="radio"&&b.type!=="checkbox")return a.handleObj.handler.apply(this,arguments)},teardown:function(){f.event.remove(this,"._change");return z.test(this.nodeName)}}),f.support.focusinBubbles||f.each({focus:"focusin",blur:"focusout"},function(a,b){var d=0,e=function(a){f.event.simulate(b,a.target,f.event.fix(a),!0)};f.event.special[b]={setup:function(){d++===0&&c.addEventListener(a,e,!0)},teardown:function(){--d===0&&c.removeEventListener(a,e,!0)}}}),f.fn.extend({on:function(a,c,d,e,g){var h,i;if(typeof a=="object"){typeof c!="string"&&(d=d||c,c=b);for(i in a)this.on(i,c,d,a[i],g);return this}d==null&&e==null?(e=c,d=c=b):e==null&&(typeof c=="string"?(e=d,d=b):(e=d,d=c,c=b));if(e===!1)e=J;else if(!e)return this;g===1&&(h=e,e=function(a){f().off(a);return h.apply(this,arguments)},e.guid=h.guid||(h.guid=f.guid++));return this.each(function(){f.event.add(this,a,e,d,c)})},one:function(a,b,c,d){return this.on(a,b,c,d,1)},off:function(a,c,d){if(a&&a.preventDefault&&a.handleObj){var e=a.handleObj;f(a.delegateTarget).off(e.namespace?e.origType+"."+e.namespace:e.origType,e.selector,e.handler);return this}if(typeof a=="object"){for(var g in a)this.off(g,c,a[g]);return this}if(c===!1||typeof c=="function")d=c,c=b;d===!1&&(d=J);return this.each(function(){f.event.remove(this,a,d,c)})},bind:function(a,b,c){return this.on(a,null,b,c)},unbind:function(a,b){return this.off(a,null,b)},live:function(a,b,c){f(this.context).on(a,this.selector,b,c);return this},die:function(a,b){f(this.context).off(a,this.selector||"**",b);return this},delegate:function(a,b,c,d){return this.on(b,a,c,d)},undelegate:function(a,b,c){return arguments.length==1?this.off(a,"**"):this.off(b,a,c)},trigger:function(a,b){return this.each(function(){f.event.trigger(a,b,this)})},triggerHandler:function(a,b){if(this[0])return f.event.trigger(a,b,this[0],!0)},toggle:function(a){var b=arguments,c=a.guid||f.guid++,d=0,e=function(c){var e=(f._data(this,"lastToggle"+a.guid)||0)%d;f._data(this,"lastToggle"+a.guid,e+1),c.preventDefault();return b[e].apply(this,arguments)||!1};e.guid=c;while(d<b.length)b[d++].guid=c;return this.click(e)},hover:function(a,b){return this.mouseenter(a).mouseleave(b||a)}}),f.each("blur focus focusin focusout load resize scroll unload click dblclick mousedown mouseup mousemove mouseover mouseout mouseenter mouseleave change select submit keydown keypress keyup error contextmenu".split(" "),function(a,b){f.fn[b]=function(a,c){c==null&&(c=a,a=null);return arguments.length>0?this.on(b,null,a,c):this.trigger(b)},f.attrFn&&(f.attrFn[b]=!0),C.test(b)&&(f.event.fixHooks[b]=f.event.keyHooks),D.test(b)&&(f.event.fixHooks[b]=f.event.mouseHooks)}),function(){function x(a,b,c,e,f,g){for(var h=0,i=e.length;h<i;h++){var j=e[h];if(j){var k=!1;j=j[a];while(j){if(j[d]===c){k=e[j.sizset];break}if(j.nodeType===1){g||(j[d]=c,j.sizset=h);if(typeof b!="string"){if(j===b){k=!0;break}}else if(m.filter(b,[j]).length>0){k=j;break}}j=j[a]}e[h]=k}}}function w(a,b,c,e,f,g){for(var h=0,i=e.length;h<i;h++){var j=e[h];if(j){var k=!1;j=j[a];while(j){if(j[d]===c){k=e[j.sizset];break}j.nodeType===1&&!g&&(j[d]=c,j.sizset=h);if(j.nodeName.toLowerCase()===b){k=j;break}j=j[a]}e[h]=k}}}var a=/((?:\((?:\([^()]+\)|[^()]+)+\)|\[(?:\[[^\[\]]*\]|['"][^'"]*['"]|[^\[\]'"]+)+\]|\\.|[^ >+~,(\[\\]+)+|[>+~])(\s*,\s*)?((?:.|\r|\n)*)/g,d="sizcache"+(Math.random()+"").replace(".",""),e=0,g=Object.prototype.toString,h=!1,i=!0,j=/\\/g,k=/\r\n/g,l=/\W/;[0,0].sort(function(){i=!1;return 0});var m=function(b,d,e,f){e=e||[],d=d||c;var h=d;if(d.nodeType!==1&&d.nodeType!==9)return[];if(!b||typeof b!="string")return e;var i,j,k,l,n,q,r,t,u=!0,v=m.isXML(d),w=[],x=b;do{a.exec(""),i=a.exec(x);if(i){x=i[3],w.push(i[1]);if(i[2]){l=i[3];break}}}while(i);if(w.length>1&&p.exec(b))if(w.length===2&&o.relative[w[0]])j=y(w[0]+w[1],d,f);else{j=o.relative[w[0]]?[d]:m(w.shift(),d);while(w.length)b=w.shift(),o.relative[b]&&(b+=w.shift()),j=y(b,j,f)}else{!f&&w.length>1&&d.nodeType===9&&!v&&o.match.ID.test(w[0])&&!o.match.ID.test(w[w.length-1])&&(n=m.find(w.shift(),d,v),d=n.expr?m.filter(n.expr,n.set)[0]:n.set[0]);if(d){n=f?{expr:w.pop(),set:s(f)}:m.find(w.pop(),w.length===1&&(w[0]==="~"||w[0]==="+")&&d.parentNode?d.parentNode:d,v),j=n.expr?m.filter(n.expr,n.set):n.set,w.length>0?k=s(j):u=!1;while(w.length)q=w.pop(),r=q,o.relative[q]?r=w.pop():q="",r==null&&(r=d),o.relative[q](k,r,v)}else k=w=[]}k||(k=j),k||m.error(q||b);if(g.call(k)==="[object Array]")if(!u)e.push.apply(e,k);else if(d&&d.nodeType===1)for(t=0;k[t]!=null;t++)k[t]&&(k[t]===!0||k[t].nodeType===1&&m.contains(d,k[t]))&&e.push(j[t]);else for(t=0;k[t]!=null;t++)k[t]&&k[t].nodeType===1&&e.push(j[t]);else s(k,e);l&&(m(l,h,e,f),m.uniqueSort(e));return e};m.uniqueSort=function(a){if(u){h=i,a.sort(u);if(h)for(var b=1;b<a.length;b++)a[b]===a[b-1]&&a.splice(b--,1)}return a},m.matches=function(a,b){return m(a,null,null,b)},m.matchesSelector=function(a,b){return m(b,null,null,[a]).length>0},m.find=function(a,b,c){var d,e,f,g,h,i;if(!a)return[];for(e=0,f=o.order.length;e<f;e++){h=o.order[e];if(g=o.leftMatch[h].exec(a)){i=g[1],g.splice(1,1);if(i.substr(i.length-1)!=="\\"){g[1]=(g[1]||"").replace(j,""),d=o.find[h](g,b,c);if(d!=null){a=a.replace(o.match[h],"");break}}}}d||(d=typeof b.getElementsByTagName!="undefined"?b.getElementsByTagName("*"):[]);return{set:d,expr:a}},m.filter=function(a,c,d,e){var f,g,h,i,j,k,l,n,p,q=a,r=[],s=c,t=c&&c[0]&&m.isXML(c[0]);while(a&&c.length){for(h in o.filter)if((f=o.leftMatch[h].exec(a))!=null&&f[2]){k=o.filter[h],l=f[1],g=!1,f.splice(1,1);if(l.substr(l.length-1)==="\\")continue;s===r&&(r=[]);if(o.preFilter[h]){f=o.preFilter[h](f,s,d,r,e,t);if(!f)g=i=!0;else if(f===!0)continue}if(f)for(n=0;(j=s[n])!=null;n++)j&&(i=k(j,f,n,s),p=e^i,d&&i!=null?p?g=!0:s[n]=!1:p&&(r.push(j),g=!0));if(i!==b){d||(s=r),a=a.replace(o.match[h],"");if(!g)return[];break}}if(a===q)if(g==null)m.error(a);else break;q=a}return s},m.error=function(a){throw new Error("Syntax error, unrecognized expression: "+a)};var n=m.getText=function(a){var b,c,d=a.nodeType,e="";if(d){if(d===1||d===9||d===11){if(typeof a.textContent=="string")return a.textContent;if(typeof a.innerText=="string")return a.innerText.replace(k,"");for(a=a.firstChild;a;a=a.nextSibling)e+=n(a)}else if(d===3||d===4)return a.nodeValue}else for(b=0;c=a[b];b++)c.nodeType!==8&&(e+=n(c));return e},o=m.selectors={order:["ID","NAME","TAG"],match:{ID:/#((?:[\w\u00c0-\uFFFF\-]|\\.)+)/,CLASS:/\.((?:[\w\u00c0-\uFFFF\-]|\\.)+)/,NAME:/\[name=['"]*((?:[\w\u00c0-\uFFFF\-]|\\.)+)['"]*\]/,ATTR:/\[\s*((?:[\w\u00c0-\uFFFF\-]|\\.)+)\s*(?:(\S?=)\s*(?:(['"])(.*?)\3|(#?(?:[\w\u00c0-\uFFFF\-]|\\.)*)|)|)\s*\]/,TAG:/^((?:[\w\u00c0-\uFFFF\*\-]|\\.)+)/,CHILD:/:(only|nth|last|first)-child(?:\(\s*(even|odd|(?:[+\-]?\d+|(?:[+\-]?\d*)?n\s*(?:[+\-]\s*\d+)?))\s*\))?/,POS:/:(nth|eq|gt|lt|first|last|even|odd)(?:\((\d*)\))?(?=[^\-]|$)/,PSEUDO:/:((?:[\w\u00c0-\uFFFF\-]|\\.)+)(?:\((['"]?)((?:\([^\)]+\)|[^\(\)]*)+)\2\))?/},leftMatch:{},attrMap:{"class":"className","for":"htmlFor"},attrHandle:{href:function(a){return a.getAttribute("href")},type:function(a){return a.getAttribute("type")}},relative:{"+":function(a,b){var c=typeof b=="string",d=c&&!l.test(b),e=c&&!d;d&&(b=b.toLowerCase());for(var f=0,g=a.length,h;f<g;f++)if(h=a[f]){while((h=h.previousSibling)&&h.nodeType!==1);a[f]=e||h&&h.nodeName.toLowerCase()===b?h||!1:h===b}e&&m.filter(b,a,!0)},">":function(a,b){var c,d=typeof b=="string",e=0,f=a.length;if(d&&!l.test(b)){b=b.toLowerCase();for(;e<f;e++){c=a[e];if(c){var g=c.parentNode;a[e]=g.nodeName.toLowerCase()===b?g:!1}}}else{for(;e<f;e++)c=a[e],c&&(a[e]=d?c.parentNode:c.parentNode===b);d&&m.filter(b,a,!0)}},"":function(a,b,c){var d,f=e++,g=x;typeof b=="string"&&!l.test(b)&&(b=b.toLowerCase(),d=b,g=w),g("parentNode",b,f,a,d,c)},"~":function(a,b,c){var d,f=e++,g=x;typeof b=="string"&&!l.test(b)&&(b=b.toLowerCase(),d=b,g=w),g("previousSibling",b,f,a,d,c)}},find:{ID:function(a,b,c){if(typeof b.getElementById!="undefined"&&!c){var d=b.getElementById(a[1]);return d&&d.parentNode?[d]:[]}},NAME:function(a,b){if(typeof b.getElementsByName!="undefined"){var c=[],d=b.getElementsByName(a[1]);for(var e=0,f=d.length;e<f;e++)d[e].getAttribute("name")===a[1]&&c.push(d[e]);return c.length===0?null:c}},TAG:function(a,b){if(typeof b.getElementsByTagName!="undefined")return b.getElementsByTagName(a[1])}},preFilter:{CLASS:function(a,b,c,d,e,f){a=" "+a[1].replace(j,"")+" ";if(f)return a;for(var g=0,h;(h=b[g])!=null;g++)h&&(e^(h.className&&(" "+h.className+" ").replace(/[\t\n\r]/g," ").indexOf(a)>=0)?c||d.push(h):c&&(b[g]=!1));return!1},ID:function(a){return a[1].replace(j,"")},TAG:function(a,b){return a[1].replace(j,"").toLowerCase()},CHILD:function(a){if(a[1]==="nth"){a[2]||m.error(a[0]),a[2]=a[2].replace(/^\+|\s*/g,"");var b=/(-?)(\d*)(?:n([+\-]?\d*))?/.exec(a[2]==="even"&&"2n"||a[2]==="odd"&&"2n+1"||!/\D/.test(a[2])&&"0n+"+a[2]||a[2]);a[2]=b[1]+(b[2]||1)-0,a[3]=b[3]-0}else a[2]&&m.error(a[0]);a[0]=e++;return a},ATTR:function(a,b,c,d,e,f){var g=a[1]=a[1].replace(j,"");!f&&o.attrMap[g]&&(a[1]=o.attrMap[g]),a[4]=(a[4]||a[5]||"").replace(j,""),a[2]==="~="&&(a[4]=" "+a[4]+" ");return a},PSEUDO:function(b,c,d,e,f){if(b[1]==="not")if((a.exec(b[3])||"").length>1||/^\w/.test(b[3]))b[3]=m(b[3],null,null,c);else{var g=m.filter(b[3],c,d,!0^f);d||e.push.apply(e,g);return!1}else if(o.match.POS.test(b[0])||o.match.CHILD.test(b[0]))return!0;return b},POS:function(a){a.unshift(!0);return a}},filters:{enabled:function(a){return a.disabled===!1&&a.type!=="hidden"},disabled:function(a){return a.disabled===!0},checked:function(a){return a.checked===!0},selected:function(a){a.parentNode&&a.parentNode.selectedIndex;return a.selected===!0},parent:function(a){return!!a.firstChild},empty:function(a){return!a.firstChild},has:function(a,b,c){return!!m(c[3],a).length},header:function(a){return/h\d/i.test(a.nodeName)},text:function(a){var b=a.getAttribute("type"),c=a.type;return a.nodeName.toLowerCase()==="input"&&"text"===c&&(b===c||b===null)},radio:function(a){return a.nodeName.toLowerCase()==="input"&&"radio"===a.type},checkbox:function(a){return a.nodeName.toLowerCase()==="input"&&"checkbox"===a.type},file:function(a){return a.nodeName.toLowerCase()==="input"&&"file"===a.type},password:function(a){return a.nodeName.toLowerCase()==="input"&&"password"===a.type},submit:function(a){var b=a.nodeName.toLowerCase();return(b==="input"||b==="button")&&"submit"===a.type},image:function(a){return a.nodeName.toLowerCase()==="input"&&"image"===a.type},reset:function(a){var b=a.nodeName.toLowerCase();return(b==="input"||b==="button")&&"reset"===a.type},button:function(a){var b=a.nodeName.toLowerCase();return b==="input"&&"button"===a.type||b==="button"},input:function(a){return/input|select|textarea|button/i.test(a.nodeName)},focus:function(a){return a===a.ownerDocument.activeElement}},setFilters:{first:function(a,b){return b===0},last:function(a,b,c,d){return b===d.length-1},even:function(a,b){return b%2===0},odd:function(a,b){return b%2===1},lt:function(a,b,c){return b<c[3]-0},gt:function(a,b,c){return b>c[3]-0},nth:function(a,b,c){return c[3]-0===b},eq:function(a,b,c){return c[3]-0===b}},filter:{PSEUDO:function(a,b,c,d){var e=b[1],f=o.filters[e];if(f)return f(a,c,b,d);if(e==="contains")return(a.textContent||a.innerText||n([a])||"").indexOf(b[3])>=0;if(e==="not"){var g=b[3];for(var h=0,i=g.length;h<i;h++)if(g[h]===a)return!1;return!0}m.error(e)},CHILD:function(a,b){var c,e,f,g,h,i,j,k=b[1],l=a;switch(k){case"only":case"first":while(l=l.previousSibling)if(l.nodeType===1)return!1;if(k==="first")return!0;l=a;case"last":while(l=l.nextSibling)if(l.nodeType===1)return!1;return!0;case"nth":c=b[2],e=b[3];if(c===1&&e===0)return!0;f=b[0],g=a.parentNode;if(g&&(g[d]!==f||!a.nodeIndex)){i=0;for(l=g.firstChild;l;l=l.nextSibling)l.nodeType===1&&(l.nodeIndex=++i);g[d]=f}j=a.nodeIndex-e;return c===0?j===0:j%c===0&&j/c>=0}},ID:function(a,b){return a.nodeType===1&&a.getAttribute("id")===b},TAG:function(a,b){return b==="*"&&a.nodeType===1||!!a.nodeName&&a.nodeName.toLowerCase()===b},CLASS:function(a,b){return(" "+(a.className||a.getAttribute("class"))+" ").indexOf(b)>-1},ATTR:function(a,b){var c=b[1],d=m.attr?m.attr(a,c):o.attrHandle[c]?o.attrHandle[c](a):a[c]!=null?a[c]:a.getAttribute(c),e=d+"",f=b[2],g=b[4];return d==null?f==="!=":!f&&m.attr?d!=null:f==="="?e===g:f==="*="?e.indexOf(g)>=0:f==="~="?(" "+e+" ").indexOf(g)>=0:g?f==="!="?e!==g:f==="^="?e.indexOf(g)===0:f==="$="?e.substr(e.length-g.length)===g:f==="|="?e===g||e.substr(0,g.length+1)===g+"-":!1:e&&d!==!1},POS:function(a,b,c,d){var e=b[2],f=o.setFilters[e];if(f)return f(a,c,b,d)}}},p=o.match.POS,q=function(a,b){return"\\"+(b-0+1)};for(var r in o.match)o.match[r]=new RegExp(o.match[r].source+/(?![^\[]*\])(?![^\(]*\))/.source),o.leftMatch[r]=new RegExp(/(^(?:.|\r|\n)*?)/.source+o.match[r].source.replace(/\\(\d+)/g,q));o.match.globalPOS=p;var s=function(a,b){a=Array.prototype.slice.call(a,0);if(b){b.push.apply(b,a);return b}return a};try{Array.prototype.slice.call(c.documentElement.childNodes,0)[0].nodeType}catch(t){s=function(a,b){var c=0,d=b||[];if(g.call(a)==="[object Array]")Array.prototype.push.apply(d,a);else if(typeof a.length=="number")for(var e=a.length;c<e;c++)d.push(a[c]);else for(;a[c];c++)d.push(a[c]);return d}}var u,v;c.documentElement.compareDocumentPosition?u=function(a,b){if(a===b){h=!0;return 0}if(!a.compareDocumentPosition||!b.compareDocumentPosition)return a.compareDocumentPosition?-1:1;return a.compareDocumentPosition(b)&4?-1:1}:(u=function(a,b){if(a===b){h=!0;return 0}if(a.sourceIndex&&b.sourceIndex)return a.sourceIndex-b.sourceIndex;var c,d,e=[],f=[],g=a.parentNode,i=b.parentNode,j=g;if(g===i)return v(a,b);if(!g)return-1;if(!i)return 1;while(j)e.unshift(j),j=j.parentNode;j=i;while(j)f.unshift(j),j=j.parentNode;c=e.length,d=f.length;for(var k=0;k<c&&k<d;k++)if(e[k]!==f[k])return v(e[k],f[k]);return k===c?v(a,f[k],-1):v(e[k],b,1)},v=function(a,b,c){if(a===b)return c;var d=a.nextSibling;while(d){if(d===b)return-1;d=d.nextSibling}return 1}),function(){var a=c.createElement("div"),d="script"+(new Date).getTime(),e=c.documentElement;a.innerHTML="<a name='"+d+"'/>",e.insertBefore(a,e.firstChild),c.getElementById(d)&&(o.find.ID=function(a,c,d){if(typeof c.getElementById!="undefined"&&!d){var e=c.getElementById(a[1]);return e?e.id===a[1]||typeof e.getAttributeNode!="undefined"&&e.getAttributeNode("id").nodeValue===a[1]?[e]:b:[]}},o.filter.ID=function(a,b){var c=typeof a.getAttributeNode!="undefined"&&a.getAttributeNode("id");return a.nodeType===1&&c&&c.nodeValue===b}),e.removeChild(a),e=a=null}(),function(){var a=c.createElement("div");a.appendChild(c.createComment("")),a.getElementsByTagName("*").length>0&&(o.find.TAG=function(a,b){var c=b.getElementsByTagName(a[1]);if(a[1]==="*"){var d=[];for(var e=0;c[e];e++)c[e].nodeType===1&&d.push(c[e]);c=d}return c}),a.innerHTML="<a href='#'></a>",a.firstChild&&typeof a.firstChild.getAttribute!="undefined"&&a.firstChild.getAttribute("href")!=="#"&&(o.attrHandle.href=function(a){return a.getAttribute("href",2)}),a=null}(),c.querySelectorAll&&function(){var a=m,b=c.createElement("div"),d="__sizzle__";b.innerHTML="<p class='TEST'></p>";if(!b.querySelectorAll||b.querySelectorAll(".TEST").length!==0){m=function(b,e,f,g){e=e||c;if(!g&&!m.isXML(e)){var h=/^(\w+$)|^\.([\w\-]+$)|^#([\w\-]+$)/.exec(b);if(h&&(e.nodeType===1||e.nodeType===9)){if(h[1])return s(e.getElementsByTagName(b),f);if(h[2]&&o.find.CLASS&&e.getElementsByClassName)return s(e.getElementsByClassName(h[2]),f)}if(e.nodeType===9){if(b==="body"&&e.body)return s([e.body],f);if(h&&h[3]){var i=e.getElementById(h[3]);if(!i||!i.parentNode)return s([],f);if(i.id===h[3])return s([i],f)}try{return s(e.querySelectorAll(b),f)}catch(j){}}else if(e.nodeType===1&&e.nodeName.toLowerCase()!=="object"){var k=e,l=e.getAttribute("id"),n=l||d,p=e.parentNode,q=/^\s*[+~]/.test(b);l?n=n.replace(/'/g,"\\$&"):e.setAttribute("id",n),q&&p&&(e=e.parentNode);try{if(!q||p)return s(e.querySelectorAll("[id='"+n+"'] "+b),f)}catch(r){}finally{l||k.removeAttribute("id")}}}return a(b,e,f,g)};for(var e in a)m[e]=a[e];b=null}}(),function(){var a=c.documentElement,b=a.matchesSelector||a.mozMatchesSelector||a.webkitMatchesSelector||a.msMatchesSelector;if(b){var d=!b.call(c.createElement("div"),"div"),e=!1;try{b.call(c.documentElement,"[test!='']:sizzle")}catch(f){e=!0}m.matchesSelector=function(a,c){c=c.replace(/\=\s*([^'"\]]*)\s*\]/g,"='$1']");if(!m.isXML(a))try{if(e||!o.match.PSEUDO.test(c)&&!/!=/.test(c)){var f=b.call(a,c);if(f||!d||a.document&&a.document.nodeType!==11)return f}}catch(g){}return m(c,null,null,[a]).length>0}}}(),function(){var a=c.createElement("div");a.innerHTML="<div class='test e'></div><div class='test'></div>";if(!!a.getElementsByClassName&&a.getElementsByClassName("e").length!==0){a.lastChild.className="e";if(a.getElementsByClassName("e").length===1)return;o.order.splice(1,0,"CLASS"),o.find.CLASS=function(a,b,c){if(typeof b.getElementsByClassName!="undefined"&&!c)return b.getElementsByClassName(a[1])},a=null}}(),c.documentElement.contains?m.contains=function(a,b){return a!==b&&(a.contains?a.contains(b):!0)}:c.documentElement.compareDocumentPosition?m.contains=function(a,b){return!!(a.compareDocumentPosition(b)&16)}:m.contains=function(){return!1},m.isXML=function(a){var b=(a?a.ownerDocument||a:0).documentElement;return b?b.nodeName!=="HTML":!1};var y=function(a,b,c){var d,e=[],f="",g=b.nodeType?[b]:b;while(d=o.match.PSEUDO.exec(a))f+=d[0],a=a.replace(o.match.PSEUDO,"");a=o.relative[a]?a+"*":a;for(var h=0,i=g.length;h<i;h++)m(a,g[h],e,c);return m.filter(f,e)};m.attr=f.attr,m.selectors.attrMap={},f.find=m,f.expr=m.selectors,f.expr[":"]=f.expr.filters,f.unique=m.uniqueSort,f.text=m.getText,f.isXMLDoc=m.isXML,f.contains=m.contains}();var L=/Until$/,M=/^(?:parents|prevUntil|prevAll)/,N=/,/,O=/^.[^:#\[\.,]*$/,P=Array.prototype.slice,Q=f.expr.match.globalPOS,R={children:!0,contents:!0,next:!0,prev:!0};f.fn.extend({find:function(a){var b=this,c,d;if(typeof a!="string")return f(a).filter(function(){for(c=0,d=b.length;c<d;c++)if(f.contains(b[c],this))return!0});var e=this.pushStack("","find",a),g,h,i;for(c=0,d=this.length;c<d;c++){g=e.length,f.find(a,this[c],e);if(c>0)for(h=g;h<e.length;h++)for(i=0;i<g;i++)if(e[i]===e[h]){e.splice(h--,1);break}}return e},has:function(a){var b=f(a);return this.filter(function(){for(var a=0,c=b.length;a<c;a++)if(f.contains(this,b[a]))return!0})},not:function(a){return this.pushStack(T(this,a,!1),"not",a)},filter:function(a){return this.pushStack(T(this,a,!0),"filter",a)},is:function(a){return!!a&&(typeof a=="string"?Q.test(a)?f(a,this.context).index(this[0])>=0:f.filter(a,this).length>0:this.filter(a).length>0)},closest:function(a,b){var c=[],d,e,g=this[0];if(f.isArray(a)){var h=1;while(g&&g.ownerDocument&&g!==b){for(d=0;d<a.length;d++)f(g).is(a[d])&&c.push({selector:a[d],elem:g,level:h});g=g.parentNode,h++}return c}var i=Q.test(a)||typeof a!="string"?f(a,b||this.context):0;for(d=0,e=this.length;d<e;d++){g=this[d];while(g){if(i?i.index(g)>-1:f.find.matchesSelector(g,a)){c.push(g);break}g=g.parentNode;if(!g||!g.ownerDocument||g===b||g.nodeType===11)break}}c=c.length>1?f.unique(c):c;return this.pushStack(c,"closest",a)},index:function(a){if(!a)return this[0]&&this[0].parentNode?this.prevAll().length:-1;if(typeof a=="string")return f.inArray(this[0],f(a));return f.inArray(a.jquery?a[0]:a,this)},add:function(a,b){var c=typeof a=="string"?f(a,b):f.makeArray(a&&a.nodeType?[a]:a),d=f.merge(this.get(),c);return this.pushStack(S(c[0])||S(d[0])?d:f.unique(d))},andSelf:function(){return this.add(this.prevObject)}}),f.each({parent:function(a){var b=a.parentNode;return b&&b.nodeType!==11?b:null},parents:function(a){return f.dir(a,"parentNode")},parentsUntil:function(a,b,c){return f.dir(a,"parentNode",c)},next:function(a){return f.nth(a,2,"nextSibling")},prev:function(a){return f.nth(a,2,"previousSibling")},nextAll:function(a){return f.dir(a,"nextSibling")},prevAll:function(a){return f.dir(a,"previousSibling")},nextUntil:function(a,b,c){return f.dir(a,"nextSibling",c)},prevUntil:function(a,b,c){return f.dir(a,"previousSibling",c)},siblings:function(a){return f.sibling((a.parentNode||{}).firstChild,a)},children:function(a){return f.sibling(a.firstChild)},contents:function(a){return f.nodeName(a,"iframe")?a.contentDocument||a.contentWindow.document:f.makeArray(a.childNodes)}},function(a,b){f.fn[a]=function(c,d){var e=f.map(this,b,c);L.test(a)||(d=c),d&&typeof d=="string"&&(e=f.filter(d,e)),e=this.length>1&&!R[a]?f.unique(e):e,(this.length>1||N.test(d))&&M.test(a)&&(e=e.reverse());return this.pushStack(e,a,P.call(arguments).join(","))}}),f.extend({filter:function(a,b,c){c&&(a=":not("+a+")");return b.length===1?f.find.matchesSelector(b[0],a)?[b[0]]:[]:f.find.matches(a,b)},dir:function(a,c,d){var e=[],g=a[c];while(g&&g.nodeType!==9&&(d===b||g.nodeType!==1||!f(g).is(d)))g.nodeType===1&&e.push(g),g=g[c];return e},nth:function(a,b,c,d){b=b||1;var e=0;for(;a;a=a[c])if(a.nodeType===1&&++e===b)break;return a},sibling:function(a,b){var c=[];for(;a;a=a.nextSibling)a.nodeType===1&&a!==b&&c.push(a);return c}});var V="abbr|article|aside|audio|bdi|canvas|data|datalist|details|figcaption|figure|footer|header|hgroup|mark|meter|nav|output|progress|section|summary|time|video",W=/ jQuery\d+="(?:\d+|null)"/g,X=/^\s+/,Y=/<(?!area|br|col|embed|hr|img|input|link|meta|param)(([\w:]+)[^>]*)\/>/ig,Z=/<([\w:]+)/,$=/<tbody/i,_=/<|&#?\w+;/,ba=/<(?:script|style)/i,bb=/<(?:script|object|embed|option|style)/i,bc=new RegExp("<(?:"+V+")[\\s/>]","i"),bd=/checked\s*(?:[^=]|=\s*.checked.)/i,be=/\/(java|ecma)script/i,bf=/^\s*<!(?:\[CDATA\[|\-\-)/,bg={option:[1,"<select multiple='multiple'>","</select>"],legend:[1,"<fieldset>","</fieldset>"],thead:[1,"<table>","</table>"],tr:[2,"<table><tbody>","</tbody></table>"],td:[3,"<table><tbody><tr>","</tr></tbody></table>"],col:[2,"<table><tbody></tbody><colgroup>","</colgroup></table>"],area:[1,"<map>","</map>"],_default:[0,"",""]},bh=U(c);bg.optgroup=bg.option,bg.tbody=bg.tfoot=bg.colgroup=bg.caption=bg.thead,bg.th=bg.td,f.support.htmlSerialize||(bg._default=[1,"div<div>","</div>"]),f.fn.extend({text:function(a){return f.access(this,function(a){return a===b?f.text(this):this.empty().append((this[0]&&this[0].ownerDocument||c).createTextNode(a))},null,a,arguments.length)},wrapAll:function(a){if(f.isFunction(a))return this.each(function(b){f(this).wrapAll(a.call(this,b))});if(this[0]){var b=f(a,this[0].ownerDocument).eq(0).clone(!0);this[0].parentNode&&b.insertBefore(this[0]),b.map(function(){var a=this;while(a.firstChild&&a.firstChild.nodeType===1)a=a.firstChild;return a}).append(this)}return this},wrapInner:function(a){if(f.isFunction(a))return this.each(function(b){f(this).wrapInner(a.call(this,b))});return this.each(function(){var b=f(this),c=b.contents();c.length?c.wrapAll(a):b.append(a)})},wrap:function(a){var b=f.isFunction(a);return this.each(function(c){f(this).wrapAll(b?a.call(this,c):a)})},unwrap:function(){return this.parent().each(function(){f.nodeName(this,"body")||f(this).replaceWith(this.childNodes)}).end()},append:function(){return this.domManip(arguments,!0,function(a){this.nodeType===1&&this.appendChild(a)})},prepend:function(){return this.domManip(arguments,!0,function(a){this.nodeType===1&&this.insertBefore(a,this.firstChild)})},before:function(){if(this[0]&&this[0].parentNode)return this.domManip(arguments,!1,function(a){this.parentNode.insertBefore(a,this)});if(arguments.length){var a=f
.clean(arguments);a.push.apply(a,this.toArray());return this.pushStack(a,"before",arguments)}},after:function(){if(this[0]&&this[0].parentNode)return this.domManip(arguments,!1,function(a){this.parentNode.insertBefore(a,this.nextSibling)});if(arguments.length){var a=this.pushStack(this,"after",arguments);a.push.apply(a,f.clean(arguments));return a}},remove:function(a,b){for(var c=0,d;(d=this[c])!=null;c++)if(!a||f.filter(a,[d]).length)!b&&d.nodeType===1&&(f.cleanData(d.getElementsByTagName("*")),f.cleanData([d])),d.parentNode&&d.parentNode.removeChild(d);return this},empty:function(){for(var a=0,b;(b=this[a])!=null;a++){b.nodeType===1&&f.cleanData(b.getElementsByTagName("*"));while(b.firstChild)b.removeChild(b.firstChild)}return this},clone:function(a,b){a=a==null?!1:a,b=b==null?a:b;return this.map(function(){return f.clone(this,a,b)})},html:function(a){return f.access(this,function(a){var c=this[0]||{},d=0,e=this.length;if(a===b)return c.nodeType===1?c.innerHTML.replace(W,""):null;if(typeof a=="string"&&!ba.test(a)&&(f.support.leadingWhitespace||!X.test(a))&&!bg[(Z.exec(a)||["",""])[1].toLowerCase()]){a=a.replace(Y,"<$1></$2>");try{for(;d<e;d++)c=this[d]||{},c.nodeType===1&&(f.cleanData(c.getElementsByTagName("*")),c.innerHTML=a);c=0}catch(g){}}c&&this.empty().append(a)},null,a,arguments.length)},replaceWith:function(a){if(this[0]&&this[0].parentNode){if(f.isFunction(a))return this.each(function(b){var c=f(this),d=c.html();c.replaceWith(a.call(this,b,d))});typeof a!="string"&&(a=f(a).detach());return this.each(function(){var b=this.nextSibling,c=this.parentNode;f(this).remove(),b?f(b).before(a):f(c).append(a)})}return this.length?this.pushStack(f(f.isFunction(a)?a():a),"replaceWith",a):this},detach:function(a){return this.remove(a,!0)},domManip:function(a,c,d){var e,g,h,i,j=a[0],k=[];if(!f.support.checkClone&&arguments.length===3&&typeof j=="string"&&bd.test(j))return this.each(function(){f(this).domManip(a,c,d,!0)});if(f.isFunction(j))return this.each(function(e){var g=f(this);a[0]=j.call(this,e,c?g.html():b),g.domManip(a,c,d)});if(this[0]){i=j&&j.parentNode,f.support.parentNode&&i&&i.nodeType===11&&i.childNodes.length===this.length?e={fragment:i}:e=f.buildFragment(a,this,k),h=e.fragment,h.childNodes.length===1?g=h=h.firstChild:g=h.firstChild;if(g){c=c&&f.nodeName(g,"tr");for(var l=0,m=this.length,n=m-1;l<m;l++)d.call(c?bi(this[l],g):this[l],e.cacheable||m>1&&l<n?f.clone(h,!0,!0):h)}k.length&&f.each(k,function(a,b){b.src?f.ajax({type:"GET",global:!1,url:b.src,async:!1,dataType:"script"}):f.globalEval((b.text||b.textContent||b.innerHTML||"").replace(bf,"/*$0*/")),b.parentNode&&b.parentNode.removeChild(b)})}return this}}),f.buildFragment=function(a,b,d){var e,g,h,i,j=a[0];b&&b[0]&&(i=b[0].ownerDocument||b[0]),i.createDocumentFragment||(i=c),a.length===1&&typeof j=="string"&&j.length<512&&i===c&&j.charAt(0)==="<"&&!bb.test(j)&&(f.support.checkClone||!bd.test(j))&&(f.support.html5Clone||!bc.test(j))&&(g=!0,h=f.fragments[j],h&&h!==1&&(e=h)),e||(e=i.createDocumentFragment(),f.clean(a,i,e,d)),g&&(f.fragments[j]=h?e:1);return{fragment:e,cacheable:g}},f.fragments={},f.each({appendTo:"append",prependTo:"prepend",insertBefore:"before",insertAfter:"after",replaceAll:"replaceWith"},function(a,b){f.fn[a]=function(c){var d=[],e=f(c),g=this.length===1&&this[0].parentNode;if(g&&g.nodeType===11&&g.childNodes.length===1&&e.length===1){e[b](this[0]);return this}for(var h=0,i=e.length;h<i;h++){var j=(h>0?this.clone(!0):this).get();f(e[h])[b](j),d=d.concat(j)}return this.pushStack(d,a,e.selector)}}),f.extend({clone:function(a,b,c){var d,e,g,h=f.support.html5Clone||f.isXMLDoc(a)||!bc.test("<"+a.nodeName+">")?a.cloneNode(!0):bo(a);if((!f.support.noCloneEvent||!f.support.noCloneChecked)&&(a.nodeType===1||a.nodeType===11)&&!f.isXMLDoc(a)){bk(a,h),d=bl(a),e=bl(h);for(g=0;d[g];++g)e[g]&&bk(d[g],e[g])}if(b){bj(a,h);if(c){d=bl(a),e=bl(h);for(g=0;d[g];++g)bj(d[g],e[g])}}d=e=null;return h},clean:function(a,b,d,e){var g,h,i,j=[];b=b||c,typeof b.createElement=="undefined"&&(b=b.ownerDocument||b[0]&&b[0].ownerDocument||c);for(var k=0,l;(l=a[k])!=null;k++){typeof l=="number"&&(l+="");if(!l)continue;if(typeof l=="string")if(!_.test(l))l=b.createTextNode(l);else{l=l.replace(Y,"<$1></$2>");var m=(Z.exec(l)||["",""])[1].toLowerCase(),n=bg[m]||bg._default,o=n[0],p=b.createElement("div"),q=bh.childNodes,r;b===c?bh.appendChild(p):U(b).appendChild(p),p.innerHTML=n[1]+l+n[2];while(o--)p=p.lastChild;if(!f.support.tbody){var s=$.test(l),t=m==="table"&&!s?p.firstChild&&p.firstChild.childNodes:n[1]==="<table>"&&!s?p.childNodes:[];for(i=t.length-1;i>=0;--i)f.nodeName(t[i],"tbody")&&!t[i].childNodes.length&&t[i].parentNode.removeChild(t[i])}!f.support.leadingWhitespace&&X.test(l)&&p.insertBefore(b.createTextNode(X.exec(l)[0]),p.firstChild),l=p.childNodes,p&&(p.parentNode.removeChild(p),q.length>0&&(r=q[q.length-1],r&&r.parentNode&&r.parentNode.removeChild(r)))}var u;if(!f.support.appendChecked)if(l[0]&&typeof (u=l.length)=="number")for(i=0;i<u;i++)bn(l[i]);else bn(l);l.nodeType?j.push(l):j=f.merge(j,l)}if(d){g=function(a){return!a.type||be.test(a.type)};for(k=0;j[k];k++){h=j[k];if(e&&f.nodeName(h,"script")&&(!h.type||be.test(h.type)))e.push(h.parentNode?h.parentNode.removeChild(h):h);else{if(h.nodeType===1){var v=f.grep(h.getElementsByTagName("script"),g);j.splice.apply(j,[k+1,0].concat(v))}d.appendChild(h)}}}return j},cleanData:function(a){var b,c,d=f.cache,e=f.event.special,g=f.support.deleteExpando;for(var h=0,i;(i=a[h])!=null;h++){if(i.nodeName&&f.noData[i.nodeName.toLowerCase()])continue;c=i[f.expando];if(c){b=d[c];if(b&&b.events){for(var j in b.events)e[j]?f.event.remove(i,j):f.removeEvent(i,j,b.handle);b.handle&&(b.handle.elem=null)}g?delete i[f.expando]:i.removeAttribute&&i.removeAttribute(f.expando),delete d[c]}}}});var bp=/alpha\([^)]*\)/i,bq=/opacity=([^)]*)/,br=/([A-Z]|^ms)/g,bs=/^[\-+]?(?:\d*\.)?\d+$/i,bt=/^-?(?:\d*\.)?\d+(?!px)[^\d\s]+$/i,bu=/^([\-+])=([\-+.\de]+)/,bv=/^margin/,bw={position:"absolute",visibility:"hidden",display:"block"},bx=["Top","Right","Bottom","Left"],by,bz,bA;f.fn.css=function(a,c){return f.access(this,function(a,c,d){return d!==b?f.style(a,c,d):f.css(a,c)},a,c,arguments.length>1)},f.extend({cssHooks:{opacity:{get:function(a,b){if(b){var c=by(a,"opacity");return c===""?"1":c}return a.style.opacity}}},cssNumber:{fillOpacity:!0,fontWeight:!0,lineHeight:!0,opacity:!0,orphans:!0,widows:!0,zIndex:!0,zoom:!0},cssProps:{"float":f.support.cssFloat?"cssFloat":"styleFloat"},style:function(a,c,d,e){if(!!a&&a.nodeType!==3&&a.nodeType!==8&&!!a.style){var g,h,i=f.camelCase(c),j=a.style,k=f.cssHooks[i];c=f.cssProps[i]||i;if(d===b){if(k&&"get"in k&&(g=k.get(a,!1,e))!==b)return g;return j[c]}h=typeof d,h==="string"&&(g=bu.exec(d))&&(d=+(g[1]+1)*+g[2]+parseFloat(f.css(a,c)),h="number");if(d==null||h==="number"&&isNaN(d))return;h==="number"&&!f.cssNumber[i]&&(d+="px");if(!k||!("set"in k)||(d=k.set(a,d))!==b)try{j[c]=d}catch(l){}}},css:function(a,c,d){var e,g;c=f.camelCase(c),g=f.cssHooks[c],c=f.cssProps[c]||c,c==="cssFloat"&&(c="float");if(g&&"get"in g&&(e=g.get(a,!0,d))!==b)return e;if(by)return by(a,c)},swap:function(a,b,c){var d={},e,f;for(f in b)d[f]=a.style[f],a.style[f]=b[f];e=c.call(a);for(f in b)a.style[f]=d[f];return e}}),f.curCSS=f.css,c.defaultView&&c.defaultView.getComputedStyle&&(bz=function(a,b){var c,d,e,g,h=a.style;b=b.replace(br,"-$1").toLowerCase(),(d=a.ownerDocument.defaultView)&&(e=d.getComputedStyle(a,null))&&(c=e.getPropertyValue(b),c===""&&!f.contains(a.ownerDocument.documentElement,a)&&(c=f.style(a,b))),!f.support.pixelMargin&&e&&bv.test(b)&&bt.test(c)&&(g=h.width,h.width=c,c=e.width,h.width=g);return c}),c.documentElement.currentStyle&&(bA=function(a,b){var c,d,e,f=a.currentStyle&&a.currentStyle[b],g=a.style;f==null&&g&&(e=g[b])&&(f=e),bt.test(f)&&(c=g.left,d=a.runtimeStyle&&a.runtimeStyle.left,d&&(a.runtimeStyle.left=a.currentStyle.left),g.left=b==="fontSize"?"1em":f,f=g.pixelLeft+"px",g.left=c,d&&(a.runtimeStyle.left=d));return f===""?"auto":f}),by=bz||bA,f.each(["height","width"],function(a,b){f.cssHooks[b]={get:function(a,c,d){if(c)return a.offsetWidth!==0?bB(a,b,d):f.swap(a,bw,function(){return bB(a,b,d)})},set:function(a,b){return bs.test(b)?b+"px":b}}}),f.support.opacity||(f.cssHooks.opacity={get:function(a,b){return bq.test((b&&a.currentStyle?a.currentStyle.filter:a.style.filter)||"")?parseFloat(RegExp.$1)/100+"":b?"1":""},set:function(a,b){var c=a.style,d=a.currentStyle,e=f.isNumeric(b)?"alpha(opacity="+b*100+")":"",g=d&&d.filter||c.filter||"";c.zoom=1;if(b>=1&&f.trim(g.replace(bp,""))===""){c.removeAttribute("filter");if(d&&!d.filter)return}c.filter=bp.test(g)?g.replace(bp,e):g+" "+e}}),f(function(){f.support.reliableMarginRight||(f.cssHooks.marginRight={get:function(a,b){return f.swap(a,{display:"inline-block"},function(){return b?by(a,"margin-right"):a.style.marginRight})}})}),f.expr&&f.expr.filters&&(f.expr.filters.hidden=function(a){var b=a.offsetWidth,c=a.offsetHeight;return b===0&&c===0||!f.support.reliableHiddenOffsets&&(a.style&&a.style.display||f.css(a,"display"))==="none"},f.expr.filters.visible=function(a){return!f.expr.filters.hidden(a)}),f.each({margin:"",padding:"",border:"Width"},function(a,b){f.cssHooks[a+b]={expand:function(c){var d,e=typeof c=="string"?c.split(" "):[c],f={};for(d=0;d<4;d++)f[a+bx[d]+b]=e[d]||e[d-2]||e[0];return f}}});var bC=/%20/g,bD=/\[\]$/,bE=/\r?\n/g,bF=/#.*$/,bG=/^(.*?):[ \t]*([^\r\n]*)\r?$/mg,bH=/^(?:color|date|datetime|datetime-local|email|hidden|month|number|password|range|search|tel|text|time|url|week)$/i,bI=/^(?:about|app|app\-storage|.+\-extension|file|res|widget):$/,bJ=/^(?:GET|HEAD)$/,bK=/^\/\//,bL=/\?/,bM=/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/gi,bN=/^(?:select|textarea)/i,bO=/\s+/,bP=/([?&])_=[^&]*/,bQ=/^([\w\+\.\-]+:)(?:\/\/([^\/?#:]*)(?::(\d+))?)?/,bR=f.fn.load,bS={},bT={},bU,bV,bW=["*/"]+["*"];try{bU=e.href}catch(bX){bU=c.createElement("a"),bU.href="",bU=bU.href}bV=bQ.exec(bU.toLowerCase())||[],f.fn.extend({load:function(a,c,d){if(typeof a!="string"&&bR)return bR.apply(this,arguments);if(!this.length)return this;var e=a.indexOf(" ");if(e>=0){var g=a.slice(e,a.length);a=a.slice(0,e)}var h="GET";c&&(f.isFunction(c)?(d=c,c=b):typeof c=="object"&&(c=f.param(c,f.ajaxSettings.traditional),h="POST"));var i=this;f.ajax({url:a,type:h,dataType:"html",data:c,complete:function(a,b,c){c=a.responseText,a.isResolved()&&(a.done(function(a){c=a}),i.html(g?f("<div>").append(c.replace(bM,"")).find(g):c)),d&&i.each(d,[c,b,a])}});return this},serialize:function(){return f.param(this.serializeArray())},serializeArray:function(){return this.map(function(){return this.elements?f.makeArray(this.elements):this}).filter(function(){return this.name&&!this.disabled&&(this.checked||bN.test(this.nodeName)||bH.test(this.type))}).map(function(a,b){var c=f(this).val();return c==null?null:f.isArray(c)?f.map(c,function(a,c){return{name:b.name,value:a.replace(bE,"\r\n")}}):{name:b.name,value:c.replace(bE,"\r\n")}}).get()}}),f.each("ajaxStart ajaxStop ajaxComplete ajaxError ajaxSuccess ajaxSend".split(" "),function(a,b){f.fn[b]=function(a){return this.on(b,a)}}),f.each(["get","post"],function(a,c){f[c]=function(a,d,e,g){f.isFunction(d)&&(g=g||e,e=d,d=b);return f.ajax({type:c,url:a,data:d,success:e,dataType:g})}}),f.extend({getScript:function(a,c){return f.get(a,b,c,"script")},getJSON:function(a,b,c){return f.get(a,b,c,"json")},ajaxSetup:function(a,b){b?b$(a,f.ajaxSettings):(b=a,a=f.ajaxSettings),b$(a,b);return a},ajaxSettings:{url:bU,isLocal:bI.test(bV[1]),global:!0,type:"GET",contentType:"application/x-www-form-urlencoded; charset=UTF-8",processData:!0,async:!0,accepts:{xml:"application/xml, text/xml",html:"text/html",text:"text/plain",json:"application/json, text/javascript","*":bW},contents:{xml:/xml/,html:/html/,json:/json/},responseFields:{xml:"responseXML",text:"responseText"},converters:{"* text":a.String,"text html":!0,"text json":f.parseJSON,"text xml":f.parseXML},flatOptions:{context:!0,url:!0}},ajaxPrefilter:bY(bS),ajaxTransport:bY(bT),ajax:function(a,c){function w(a,c,l,m){if(s!==2){s=2,q&&clearTimeout(q),p=b,n=m||"",v.readyState=a>0?4:0;var o,r,u,w=c,x=l?ca(d,v,l):b,y,z;if(a>=200&&a<300||a===304){if(d.ifModified){if(y=v.getResponseHeader("Last-Modified"))f.lastModified[k]=y;if(z=v.getResponseHeader("Etag"))f.etag[k]=z}if(a===304)w="notmodified",o=!0;else try{r=cb(d,x),w="success",o=!0}catch(A){w="parsererror",u=A}}else{u=w;if(!w||a)w="error",a<0&&(a=0)}v.status=a,v.statusText=""+(c||w),o?h.resolveWith(e,[r,w,v]):h.rejectWith(e,[v,w,u]),v.statusCode(j),j=b,t&&g.trigger("ajax"+(o?"Success":"Error"),[v,d,o?r:u]),i.fireWith(e,[v,w]),t&&(g.trigger("ajaxComplete",[v,d]),--f.active||f.event.trigger("ajaxStop"))}}typeof a=="object"&&(c=a,a=b),c=c||{};var d=f.ajaxSetup({},c),e=d.context||d,g=e!==d&&(e.nodeType||e instanceof f)?f(e):f.event,h=f.Deferred(),i=f.Callbacks("once memory"),j=d.statusCode||{},k,l={},m={},n,o,p,q,r,s=0,t,u,v={readyState:0,setRequestHeader:function(a,b){if(!s){var c=a.toLowerCase();a=m[c]=m[c]||a,l[a]=b}return this},getAllResponseHeaders:function(){return s===2?n:null},getResponseHeader:function(a){var c;if(s===2){if(!o){o={};while(c=bG.exec(n))o[c[1].toLowerCase()]=c[2]}c=o[a.toLowerCase()]}return c===b?null:c},overrideMimeType:function(a){s||(d.mimeType=a);return this},abort:function(a){a=a||"abort",p&&p.abort(a),w(0,a);return this}};h.promise(v),v.success=v.done,v.error=v.fail,v.complete=i.add,v.statusCode=function(a){if(a){var b;if(s<2)for(b in a)j[b]=[j[b],a[b]];else b=a[v.status],v.then(b,b)}return this},d.url=((a||d.url)+"").replace(bF,"").replace(bK,bV[1]+"//"),d.dataTypes=f.trim(d.dataType||"*").toLowerCase().split(bO),d.crossDomain==null&&(r=bQ.exec(d.url.toLowerCase()),d.crossDomain=!(!r||r[1]==bV[1]&&r[2]==bV[2]&&(r[3]||(r[1]==="http:"?80:443))==(bV[3]||(bV[1]==="http:"?80:443)))),d.data&&d.processData&&typeof d.data!="string"&&(d.data=f.param(d.data,d.traditional)),bZ(bS,d,c,v);if(s===2)return!1;t=d.global,d.type=d.type.toUpperCase(),d.hasContent=!bJ.test(d.type),t&&f.active++===0&&f.event.trigger("ajaxStart");if(!d.hasContent){d.data&&(d.url+=(bL.test(d.url)?"&":"?")+d.data,delete d.data),k=d.url;if(d.cache===!1){var x=f.now(),y=d.url.replace(bP,"$1_="+x);d.url=y+(y===d.url?(bL.test(d.url)?"&":"?")+"_="+x:"")}}(d.data&&d.hasContent&&d.contentType!==!1||c.contentType)&&v.setRequestHeader("Content-Type",d.contentType),d.ifModified&&(k=k||d.url,f.lastModified[k]&&v.setRequestHeader("If-Modified-Since",f.lastModified[k]),f.etag[k]&&v.setRequestHeader("If-None-Match",f.etag[k])),v.setRequestHeader("Accept",d.dataTypes[0]&&d.accepts[d.dataTypes[0]]?d.accepts[d.dataTypes[0]]+(d.dataTypes[0]!=="*"?", "+bW+"; q=0.01":""):d.accepts["*"]);for(u in d.headers)v.setRequestHeader(u,d.headers[u]);if(d.beforeSend&&(d.beforeSend.call(e,v,d)===!1||s===2)){v.abort();return!1}for(u in{success:1,error:1,complete:1})v[u](d[u]);p=bZ(bT,d,c,v);if(!p)w(-1,"No Transport");else{v.readyState=1,t&&g.trigger("ajaxSend",[v,d]),d.async&&d.timeout>0&&(q=setTimeout(function(){v.abort("timeout")},d.timeout));try{s=1,p.send(l,w)}catch(z){if(s<2)w(-1,z);else throw z}}return v},param:function(a,c){var d=[],e=function(a,b){b=f.isFunction(b)?b():b,d[d.length]=encodeURIComponent(a)+"="+encodeURIComponent(b)};c===b&&(c=f.ajaxSettings.traditional);if(f.isArray(a)||a.jquery&&!f.isPlainObject(a))f.each(a,function(){e(this.name,this.value)});else for(var g in a)b_(g,a[g],c,e);return d.join("&").replace(bC,"+")}}),f.extend({active:0,lastModified:{},etag:{}});var cc=f.now(),cd=/(\=)\?(&|$)|\?\?/i;f.ajaxSetup({jsonp:"callback",jsonpCallback:function(){return f.expando+"_"+cc++}}),f.ajaxPrefilter("json jsonp",function(b,c,d){var e=typeof b.data=="string"&&/^application\/x\-www\-form\-urlencoded/.test(b.contentType);if(b.dataTypes[0]==="jsonp"||b.jsonp!==!1&&(cd.test(b.url)||e&&cd.test(b.data))){var g,h=b.jsonpCallback=f.isFunction(b.jsonpCallback)?b.jsonpCallback():b.jsonpCallback,i=a[h],j=b.url,k=b.data,l="$1"+h+"$2";b.jsonp!==!1&&(j=j.replace(cd,l),b.url===j&&(e&&(k=k.replace(cd,l)),b.data===k&&(j+=(/\?/.test(j)?"&":"?")+b.jsonp+"="+h))),b.url=j,b.data=k,a[h]=function(a){g=[a]},d.always(function(){a[h]=i,g&&f.isFunction(i)&&a[h](g[0])}),b.converters["script json"]=function(){g||f.error(h+" was not called");return g[0]},b.dataTypes[0]="json";return"script"}}),f.ajaxSetup({accepts:{script:"text/javascript, application/javascript, application/ecmascript, application/x-ecmascript"},contents:{script:/javascript|ecmascript/},converters:{"text script":function(a){f.globalEval(a);return a}}}),f.ajaxPrefilter("script",function(a){a.cache===b&&(a.cache=!1),a.crossDomain&&(a.type="GET",a.global=!1)}),f.ajaxTransport("script",function(a){if(a.crossDomain){var d,e=c.head||c.getElementsByTagName("head")[0]||c.documentElement;return{send:function(f,g){d=c.createElement("script"),d.async="async",a.scriptCharset&&(d.charset=a.scriptCharset),d.src=a.url,d.onload=d.onreadystatechange=function(a,c){if(c||!d.readyState||/loaded|complete/.test(d.readyState))d.onload=d.onreadystatechange=null,e&&d.parentNode&&e.removeChild(d),d=b,c||g(200,"success")},e.insertBefore(d,e.firstChild)},abort:function(){d&&d.onload(0,1)}}}});var ce=a.ActiveXObject?function(){for(var a in cg)cg[a](0,1)}:!1,cf=0,cg;f.ajaxSettings.xhr=a.ActiveXObject?function(){return!this.isLocal&&ch()||ci()}:ch,function(a){f.extend(f.support,{ajax:!!a,cors:!!a&&"withCredentials"in a})}(f.ajaxSettings.xhr()),f.support.ajax&&f.ajaxTransport(function(c){if(!c.crossDomain||f.support.cors){var d;return{send:function(e,g){var h=c.xhr(),i,j;c.username?h.open(c.type,c.url,c.async,c.username,c.password):h.open(c.type,c.url,c.async);if(c.xhrFields)for(j in c.xhrFields)h[j]=c.xhrFields[j];c.mimeType&&h.overrideMimeType&&h.overrideMimeType(c.mimeType),!c.crossDomain&&!e["X-Requested-With"]&&(e["X-Requested-With"]="XMLHttpRequest");try{for(j in e)h.setRequestHeader(j,e[j])}catch(k){}h.send(c.hasContent&&c.data||null),d=function(a,e){var j,k,l,m,n;try{if(d&&(e||h.readyState===4)){d=b,i&&(h.onreadystatechange=f.noop,ce&&delete cg[i]);if(e)h.readyState!==4&&h.abort();else{j=h.status,l=h.getAllResponseHeaders(),m={},n=h.responseXML,n&&n.documentElement&&(m.xml=n);try{m.text=h.responseText}catch(a){}try{k=h.statusText}catch(o){k=""}!j&&c.isLocal&&!c.crossDomain?j=m.text?200:404:j===1223&&(j=204)}}}catch(p){e||g(-1,p)}m&&g(j,k,m,l)},!c.async||h.readyState===4?d():(i=++cf,ce&&(cg||(cg={},f(a).unload(ce)),cg[i]=d),h.onreadystatechange=d)},abort:function(){d&&d(0,1)}}}});var cj={},ck,cl,cm=/^(?:toggle|show|hide)$/,cn=/^([+\-]=)?([\d+.\-]+)([a-z%]*)$/i,co,cp=[["height","marginTop","marginBottom","paddingTop","paddingBottom"],["width","marginLeft","marginRight","paddingLeft","paddingRight"],["opacity"]],cq;f.fn.extend({show:function(a,b,c){var d,e;if(a||a===0)return this.animate(ct("show",3),a,b,c);for(var g=0,h=this.length;g<h;g++)d=this[g],d.style&&(e=d.style.display,!f._data(d,"olddisplay")&&e==="none"&&(e=d.style.display=""),(e===""&&f.css(d,"display")==="none"||!f.contains(d.ownerDocument.documentElement,d))&&f._data(d,"olddisplay",cu(d.nodeName)));for(g=0;g<h;g++){d=this[g];if(d.style){e=d.style.display;if(e===""||e==="none")d.style.display=f._data(d,"olddisplay")||""}}return this},hide:function(a,b,c){if(a||a===0)return this.animate(ct("hide",3),a,b,c);var d,e,g=0,h=this.length;for(;g<h;g++)d=this[g],d.style&&(e=f.css(d,"display"),e!=="none"&&!f._data(d,"olddisplay")&&f._data(d,"olddisplay",e));for(g=0;g<h;g++)this[g].style&&(this[g].style.display="none");return this},_toggle:f.fn.toggle,toggle:function(a,b,c){var d=typeof a=="boolean";f.isFunction(a)&&f.isFunction(b)?this._toggle.apply(this,arguments):a==null||d?this.each(function(){var b=d?a:f(this).is(":hidden");f(this)[b?"show":"hide"]()}):this.animate(ct("toggle",3),a,b,c);return this},fadeTo:function(a,b,c,d){return this.filter(":hidden").css("opacity",0).show().end().animate({opacity:b},a,c,d)},animate:function(a,b,c,d){function g(){e.queue===!1&&f._mark(this);var b=f.extend({},e),c=this.nodeType===1,d=c&&f(this).is(":hidden"),g,h,i,j,k,l,m,n,o,p,q;b.animatedProperties={};for(i in a){g=f.camelCase(i),i!==g&&(a[g]=a[i],delete a[i]);if((k=f.cssHooks[g])&&"expand"in k){l=k.expand(a[g]),delete a[g];for(i in l)i in a||(a[i]=l[i])}}for(g in a){h=a[g],f.isArray(h)?(b.animatedProperties[g]=h[1],h=a[g]=h[0]):b.animatedProperties[g]=b.specialEasing&&b.specialEasing[g]||b.easing||"swing";if(h==="hide"&&d||h==="show"&&!d)return b.complete.call(this);c&&(g==="height"||g==="width")&&(b.overflow=[this.style.overflow,this.style.overflowX,this.style.overflowY],f.css(this,"display")==="inline"&&f.css(this,"float")==="none"&&(!f.support.inlineBlockNeedsLayout||cu(this.nodeName)==="inline"?this.style.display="inline-block":this.style.zoom=1))}b.overflow!=null&&(this.style.overflow="hidden");for(i in a)j=new f.fx(this,b,i),h=a[i],cm.test(h)?(q=f._data(this,"toggle"+i)||(h==="toggle"?d?"show":"hide":0),q?(f._data(this,"toggle"+i,q==="show"?"hide":"show"),j[q]()):j[h]()):(m=cn.exec(h),n=j.cur(),m?(o=parseFloat(m[2]),p=m[3]||(f.cssNumber[i]?"":"px"),p!=="px"&&(f.style(this,i,(o||1)+p),n=(o||1)/j.cur()*n,f.style(this,i,n+p)),m[1]&&(o=(m[1]==="-="?-1:1)*o+n),j.custom(n,o,p)):j.custom(n,h,""));return!0}var e=f.speed(b,c,d);if(f.isEmptyObject(a))return this.each(e.complete,[!1]);a=f.extend({},a);return e.queue===!1?this.each(g):this.queue(e.queue,g)},stop:function(a,c,d){typeof a!="string"&&(d=c,c=a,a=b),c&&a!==!1&&this.queue(a||"fx",[]);return this.each(function(){function h(a,b,c){var e=b[c];f.removeData(a,c,!0),e.stop(d)}var b,c=!1,e=f.timers,g=f._data(this);d||f._unmark(!0,this);if(a==null)for(b in g)g[b]&&g[b].stop&&b.indexOf(".run")===b.length-4&&h(this,g,b);else g[b=a+".run"]&&g[b].stop&&h(this,g,b);for(b=e.length;b--;)e[b].elem===this&&(a==null||e[b].queue===a)&&(d?e[b](!0):e[b].saveState(),c=!0,e.splice(b,1));(!d||!c)&&f.dequeue(this,a)})}}),f.each({slideDown:ct("show",1),slideUp:ct("hide",1),slideToggle:ct("toggle",1),fadeIn:{opacity:"show"},fadeOut:{opacity:"hide"},fadeToggle:{opacity:"toggle"}},function(a,b){f.fn[a]=function(a,c,d){return this.animate(b,a,c,d)}}),f.extend({speed:function(a,b,c){var d=a&&typeof a=="object"?f.extend({},a):{complete:c||!c&&b||f.isFunction(a)&&a,duration:a,easing:c&&b||b&&!f.isFunction(b)&&b};d.duration=f.fx.off?0:typeof d.duration=="number"?d.duration:d.duration in f.fx.speeds?f.fx.speeds[d.duration]:f.fx.speeds._default;if(d.queue==null||d.queue===!0)d.queue="fx";d.old=d.complete,d.complete=function(a){f.isFunction(d.old)&&d.old.call(this),d.queue?f.dequeue(this,d.queue):a!==!1&&f._unmark(this)};return d},easing:{linear:function(a){return a},swing:function(a){return-Math.cos(a*Math.PI)/2+.5}},timers:[],fx:function(a,b,c){this.options=b,this.elem=a,this.prop=c,b.orig=b.orig||{}}}),f.fx.prototype={update:function(){this.options.step&&this.options.step.call(this.elem,this.now,this),(f.fx.step[this.prop]||f.fx.step._default)(this)},cur:function(){if(this.elem[this.prop]!=null&&(!this.elem.style||this.elem.style[this.prop]==null))return this.elem[this.prop];var a,b=f.css(this.elem,this.prop);return isNaN(a=parseFloat(b))?!b||b==="auto"?0:b:a},custom:function(a,c,d){function h(a){return e.step(a)}var e=this,g=f.fx;this.startTime=cq||cr(),this.end=c,this.now=this.start=a,this.pos=this.state=0,this.unit=d||this.unit||(f.cssNumber[this.prop]?"":"px"),h.queue=this.options.queue,h.elem=this.elem,h.saveState=function(){f._data(e.elem,"fxshow"+e.prop)===b&&(e.options.hide?f._data(e.elem,"fxshow"+e.prop,e.start):e.options.show&&f._data(e.elem,"fxshow"+e.prop,e.end))},h()&&f.timers.push(h)&&!co&&(co=setInterval(g.tick,g.interval))},show:function(){var a=f._data(this.elem,"fxshow"+this.prop);this.options.orig[this.prop]=a||f.style(this.elem,this.prop),this.options.show=!0,a!==b?this.custom(this.cur(),a):this.custom(this.prop==="width"||this.prop==="height"?1:0,this.cur()),f(this.elem).show()},hide:function(){this.options.orig[this.prop]=f._data(this.elem,"fxshow"+this.prop)||f.style(this.elem,this.prop),this.options.hide=!0,this.custom(this.cur(),0)},step:function(a){var b,c,d,e=cq||cr(),g=!0,h=this.elem,i=this.options;if(a||e>=i.duration+this.startTime){this.now=this.end,this.pos=this.state=1,this.update(),i.animatedProperties[this.prop]=!0;for(b in i.animatedProperties)i.animatedProperties[b]!==!0&&(g=!1);if(g){i.overflow!=null&&!f.support.shrinkWrapBlocks&&f.each(["","X","Y"],function(a,b){h.style["overflow"+b]=i.overflow[a]}),i.hide&&f(h).hide();if(i.hide||i.show)for(b in i.animatedProperties)f.style(h,b,i.orig[b]),f.removeData(h,"fxshow"+b,!0),f.removeData(h,"toggle"+b,!0);d=i.complete,d&&(i.complete=!1,d.call(h))}return!1}i.duration==Infinity?this.now=e:(c=e-this.startTime,this.state=c/i.duration,this.pos=f.easing[i.animatedProperties[this.prop]](this.state,c,0,1,i.duration),this.now=this.start+(this.end-this.start)*this.pos),this.update();return!0}},f.extend(f.fx,{tick:function(){var a,b=f.timers,c=0;for(;c<b.length;c++)a=b[c],!a()&&b[c]===a&&b.splice(c--,1);b.length||f.fx.stop()},interval:13,stop:function(){clearInterval(co),co=null},speeds:{slow:600,fast:200,_default:400},step:{opacity:function(a){f.style(a.elem,"opacity",a.now)},_default:function(a){a.elem.style&&a.elem.style[a.prop]!=null?a.elem.style[a.prop]=a.now+a.unit:a.elem[a.prop]=a.now}}}),f.each(cp.concat.apply([],cp),function(a,b){b.indexOf("margin")&&(f.fx.step[b]=function(a){f.style(a.elem,b,Math.max(0,a.now)+a.unit)})}),f.expr&&f.expr.filters&&(f.expr.filters.animated=function(a){return f.grep(f.timers,function(b){return a===b.elem}).length});var cv,cw=/^t(?:able|d|h)$/i,cx=/^(?:body|html)$/i;"getBoundingClientRect"in c.documentElement?cv=function(a,b,c,d){try{d=a.getBoundingClientRect()}catch(e){}if(!d||!f.contains(c,a))return d?{top:d.top,left:d.left}:{top:0,left:0};var g=b.body,h=cy(b),i=c.clientTop||g.clientTop||0,j=c.clientLeft||g.clientLeft||0,k=h.pageYOffset||f.support.boxModel&&c.scrollTop||g.scrollTop,l=h.pageXOffset||f.support.boxModel&&c.scrollLeft||g.scrollLeft,m=d.top+k-i,n=d.left+l-j;return{top:m,left:n}}:cv=function(a,b,c){var d,e=a.offsetParent,g=a,h=b.body,i=b.defaultView,j=i?i.getComputedStyle(a,null):a.currentStyle,k=a.offsetTop,l=a.offsetLeft;while((a=a.parentNode)&&a!==h&&a!==c){if(f.support.fixedPosition&&j.position==="fixed")break;d=i?i.getComputedStyle(a,null):a.currentStyle,k-=a.scrollTop,l-=a.scrollLeft,a===e&&(k+=a.offsetTop,l+=a.offsetLeft,f.support.doesNotAddBorder&&(!f.support.doesAddBorderForTableAndCells||!cw.test(a.nodeName))&&(k+=parseFloat(d.borderTopWidth)||0,l+=parseFloat(d.borderLeftWidth)||0),g=e,e=a.offsetParent),f.support.subtractsBorderForOverflowNotVisible&&d.overflow!=="visible"&&(k+=parseFloat(d.borderTopWidth)||0,l+=parseFloat(d.borderLeftWidth)||0),j=d}if(j.position==="relative"||j.position==="static")k+=h.offsetTop,l+=h.offsetLeft;f.support.fixedPosition&&j.position==="fixed"&&(k+=Math.max(c.scrollTop,h.scrollTop),l+=Math.max(c.scrollLeft,h.scrollLeft));return{top:k,left:l}},f.fn.offset=function(a){if(arguments.length)return a===b?this:this.each(function(b){f.offset.setOffset(this,a,b)});var c=this[0],d=c&&c.ownerDocument;if(!d)return null;if(c===d.body)return f.offset.bodyOffset(c);return cv(c,d,d.documentElement)},f.offset={bodyOffset:function(a){var b=a.offsetTop,c=a.offsetLeft;f.support.doesNotIncludeMarginInBodyOffset&&(b+=parseFloat(f.css(a,"marginTop"))||0,c+=parseFloat(f.css(a,"marginLeft"))||0);return{top:b,left:c}},setOffset:function(a,b,c){var d=f.css(a,"position");d==="static"&&(a.style.position="relative");var e=f(a),g=e.offset(),h=f.css(a,"top"),i=f.css(a,"left"),j=(d==="absolute"||d==="fixed")&&f.inArray("auto",[h,i])>-1,k={},l={},m,n;j?(l=e.position(),m=l.top,n=l.left):(m=parseFloat(h)||0,n=parseFloat(i)||0),f.isFunction(b)&&(b=b.call(a,c,g)),b.top!=null&&(k.top=b.top-g.top+m),b.left!=null&&(k.left=b.left-g.left+n),"using"in b?b.using.call(a,k):e.css(k)}},f.fn.extend({position:function(){if(!this[0])return null;var a=this[0],b=this.offsetParent(),c=this.offset(),d=cx.test(b[0].nodeName)?{top:0,left:0}:b.offset();c.top-=parseFloat(f.css(a,"marginTop"))||0,c.left-=parseFloat(f.css(a,"marginLeft"))||0,d.top+=parseFloat(f.css(b[0],"borderTopWidth"))||0,d.left+=parseFloat(f.css(b[0],"borderLeftWidth"))||0;return{top:c.top-d.top,left:c.left-d.left}},offsetParent:function(){return this.map(function(){var a=this.offsetParent||c.body;while(a&&!cx.test(a.nodeName)&&f.css(a,"position")==="static")a=a.offsetParent;return a})}}),f.each({scrollLeft:"pageXOffset",scrollTop:"pageYOffset"},function(a,c){var d=/Y/.test(c);f.fn[a]=function(e){return f.access(this,function(a,e,g){var h=cy(a);if(g===b)return h?c in h?h[c]:f.support.boxModel&&h.document.documentElement[e]||h.document.body[e]:a[e];h?h.scrollTo(d?f(h).scrollLeft():g,d?g:f(h).scrollTop()):a[e]=g},a,e,arguments.length,null)}}),f.each({Height:"height",Width:"width"},function(a,c){var d="client"+a,e="scroll"+a,g="offset"+a;f.fn["inner"+a]=function(){var a=this[0];return a?a.style?parseFloat(f.css(a,c,"padding")):this[c]():null},f.fn["outer"+a]=function(a){var b=this[0];return b?b.style?parseFloat(f.css(b,c,a?"margin":"border")):this[c]():null},f.fn[c]=function(a){return f.access(this,function(a,c,h){var i,j,k,l;if(f.isWindow(a)){i=a.document,j=i.documentElement[d];return f.support.boxModel&&j||i.body&&i.body[d]||j}if(a.nodeType===9){i=a.documentElement;if(i[d]>=i[e])return i[d];return Math.max(a.body[e],i[e],a.body[g],i[g])}if(h===b){k=f.css(a,c),l=parseFloat(k);return f.isNumeric(l)?l:k}f(a).css(c,h)},c,a,arguments.length,null)}}),a.jQuery=a.$=f,typeof define=="function"&&define.amd&&define.amd.jQuery&&define("jquery",[],function(){return f})})(window);
define("jquery.min", function(){});

/*!
 * jQuery UI 1.8.16
 *
 * Copyright 2011, AUTHORS.txt (http://jqueryui.com/about)
 * Dual licensed under the MIT or GPL Version 2 licenses.
 * http://jquery.org/license
 *
 * http://docs.jquery.com/UI
 */
(function(c,j){function k(a,b){var d=a.nodeName.toLowerCase();if("area"===d){b=a.parentNode;d=b.name;if(!a.href||!d||b.nodeName.toLowerCase()!=="map")return false;a=c("img[usemap=#"+d+"]")[0];return!!a&&l(a)}return(/input|select|textarea|button|object/.test(d)?!a.disabled:"a"==d?a.href||b:b)&&l(a)}function l(a){return!c(a).parents().andSelf().filter(function(){return c.curCSS(this,"visibility")==="hidden"||c.expr.filters.hidden(this)}).length}c.ui=c.ui||{};if(!c.ui.version){c.extend(c.ui,{version:"1.8.16",
keyCode:{ALT:18,BACKSPACE:8,CAPS_LOCK:20,COMMA:188,COMMAND:91,COMMAND_LEFT:91,COMMAND_RIGHT:93,CONTROL:17,DELETE:46,DOWN:40,END:35,ENTER:13,ESCAPE:27,HOME:36,INSERT:45,LEFT:37,MENU:93,NUMPAD_ADD:107,NUMPAD_DECIMAL:110,NUMPAD_DIVIDE:111,NUMPAD_ENTER:108,NUMPAD_MULTIPLY:106,NUMPAD_SUBTRACT:109,PAGE_DOWN:34,PAGE_UP:33,PERIOD:190,RIGHT:39,SHIFT:16,SPACE:32,TAB:9,UP:38,WINDOWS:91}});c.fn.extend({propAttr:c.fn.prop||c.fn.attr,_focus:c.fn.focus,focus:function(a,b){return typeof a==="number"?this.each(function(){var d=
this;setTimeout(function(){c(d).focus();b&&b.call(d)},a)}):this._focus.apply(this,arguments)},scrollParent:function(){var a;a=c.browser.msie&&/(static|relative)/.test(this.css("position"))||/absolute/.test(this.css("position"))?this.parents().filter(function(){return/(relative|absolute|fixed)/.test(c.curCSS(this,"position",1))&&/(auto|scroll)/.test(c.curCSS(this,"overflow",1)+c.curCSS(this,"overflow-y",1)+c.curCSS(this,"overflow-x",1))}).eq(0):this.parents().filter(function(){return/(auto|scroll)/.test(c.curCSS(this,
"overflow",1)+c.curCSS(this,"overflow-y",1)+c.curCSS(this,"overflow-x",1))}).eq(0);return/fixed/.test(this.css("position"))||!a.length?c(document):a},zIndex:function(a){if(a!==j)return this.css("zIndex",a);if(this.length){a=c(this[0]);for(var b;a.length&&a[0]!==document;){b=a.css("position");if(b==="absolute"||b==="relative"||b==="fixed"){b=parseInt(a.css("zIndex"),10);if(!isNaN(b)&&b!==0)return b}a=a.parent()}}return 0},disableSelection:function(){return this.bind((c.support.selectstart?"selectstart":
"mousedown")+".ui-disableSelection",function(a){a.preventDefault()})},enableSelection:function(){return this.unbind(".ui-disableSelection")}});c.each(["Width","Height"],function(a,b){function d(f,g,m,n){c.each(e,function(){g-=parseFloat(c.curCSS(f,"padding"+this,true))||0;if(m)g-=parseFloat(c.curCSS(f,"border"+this+"Width",true))||0;if(n)g-=parseFloat(c.curCSS(f,"margin"+this,true))||0});return g}var e=b==="Width"?["Left","Right"]:["Top","Bottom"],h=b.toLowerCase(),i={innerWidth:c.fn.innerWidth,innerHeight:c.fn.innerHeight,
outerWidth:c.fn.outerWidth,outerHeight:c.fn.outerHeight};c.fn["inner"+b]=function(f){if(f===j)return i["inner"+b].call(this);return this.each(function(){c(this).css(h,d(this,f)+"px")})};c.fn["outer"+b]=function(f,g){if(typeof f!=="number")return i["outer"+b].call(this,f);return this.each(function(){c(this).css(h,d(this,f,true,g)+"px")})}});c.extend(c.expr[":"],{data:function(a,b,d){return!!c.data(a,d[3])},focusable:function(a){return k(a,!isNaN(c.attr(a,"tabindex")))},tabbable:function(a){var b=c.attr(a,
"tabindex"),d=isNaN(b);return(d||b>=0)&&k(a,!d)}});c(function(){var a=document.body,b=a.appendChild(b=document.createElement("div"));c.extend(b.style,{minHeight:"100px",height:"auto",padding:0,borderWidth:0});c.support.minHeight=b.offsetHeight===100;c.support.selectstart="onselectstart"in b;a.removeChild(b).style.display="none"});c.extend(c.ui,{plugin:{add:function(a,b,d){a=c.ui[a].prototype;for(var e in d){a.plugins[e]=a.plugins[e]||[];a.plugins[e].push([b,d[e]])}},call:function(a,b,d){if((b=a.plugins[b])&&
a.element[0].parentNode)for(var e=0;e<b.length;e++)a.options[b[e][0]]&&b[e][1].apply(a.element,d)}},contains:function(a,b){return document.compareDocumentPosition?a.compareDocumentPosition(b)&16:a!==b&&a.contains(b)},hasScroll:function(a,b){if(c(a).css("overflow")==="hidden")return false;b=b&&b==="left"?"scrollLeft":"scrollTop";var d=false;if(a[b]>0)return true;a[b]=1;d=a[b]>0;a[b]=0;return d},isOverAxis:function(a,b,d){return a>b&&a<b+d},isOver:function(a,b,d,e,h,i){return c.ui.isOverAxis(a,d,h)&&
c.ui.isOverAxis(b,e,i)}})}})(jQuery);
;/*!
 * jQuery UI Widget 1.8.16
 *
 * Copyright 2011, AUTHORS.txt (http://jqueryui.com/about)
 * Dual licensed under the MIT or GPL Version 2 licenses.
 * http://jquery.org/license
 *
 * http://docs.jquery.com/UI/Widget
 */
(function(b,j){if(b.cleanData){var k=b.cleanData;b.cleanData=function(a){for(var c=0,d;(d=a[c])!=null;c++)try{b(d).triggerHandler("remove")}catch(e){}k(a)}}else{var l=b.fn.remove;b.fn.remove=function(a,c){return this.each(function(){if(!c)if(!a||b.filter(a,[this]).length)b("*",this).add([this]).each(function(){try{b(this).triggerHandler("remove")}catch(d){}});return l.call(b(this),a,c)})}}b.widget=function(a,c,d){var e=a.split(".")[0],f;a=a.split(".")[1];f=e+"-"+a;if(!d){d=c;c=b.Widget}b.expr[":"][f]=
function(h){return!!b.data(h,a)};b[e]=b[e]||{};b[e][a]=function(h,g){arguments.length&&this._createWidget(h,g)};c=new c;c.options=b.extend(true,{},c.options);b[e][a].prototype=b.extend(true,c,{namespace:e,widgetName:a,widgetEventPrefix:b[e][a].prototype.widgetEventPrefix||a,widgetBaseClass:f},d);b.widget.bridge(a,b[e][a])};b.widget.bridge=function(a,c){b.fn[a]=function(d){var e=typeof d==="string",f=Array.prototype.slice.call(arguments,1),h=this;d=!e&&f.length?b.extend.apply(null,[true,d].concat(f)):
d;if(e&&d.charAt(0)==="_")return h;e?this.each(function(){var g=b.data(this,a),i=g&&b.isFunction(g[d])?g[d].apply(g,f):g;if(i!==g&&i!==j){h=i;return false}}):this.each(function(){var g=b.data(this,a);g?g.option(d||{})._init():b.data(this,a,new c(d,this))});return h}};b.Widget=function(a,c){arguments.length&&this._createWidget(a,c)};b.Widget.prototype={widgetName:"widget",widgetEventPrefix:"",options:{disabled:false},_createWidget:function(a,c){b.data(c,this.widgetName,this);this.element=b(c);this.options=
b.extend(true,{},this.options,this._getCreateOptions(),a);var d=this;this.element.bind("remove."+this.widgetName,function(){d.destroy()});this._create();this._trigger("create");this._init()},_getCreateOptions:function(){return b.metadata&&b.metadata.get(this.element[0])[this.widgetName]},_create:function(){},_init:function(){},destroy:function(){this.element.unbind("."+this.widgetName).removeData(this.widgetName);this.widget().unbind("."+this.widgetName).removeAttr("aria-disabled").removeClass(this.widgetBaseClass+
"-disabled ui-state-disabled")},widget:function(){return this.element},option:function(a,c){var d=a;if(arguments.length===0)return b.extend({},this.options);if(typeof a==="string"){if(c===j)return this.options[a];d={};d[a]=c}this._setOptions(d);return this},_setOptions:function(a){var c=this;b.each(a,function(d,e){c._setOption(d,e)});return this},_setOption:function(a,c){this.options[a]=c;if(a==="disabled")this.widget()[c?"addClass":"removeClass"](this.widgetBaseClass+"-disabled ui-state-disabled").attr("aria-disabled",
c);return this},enable:function(){return this._setOption("disabled",false)},disable:function(){return this._setOption("disabled",true)},_trigger:function(a,c,d){var e=this.options[a];c=b.Event(c);c.type=(a===this.widgetEventPrefix?a:this.widgetEventPrefix+a).toLowerCase();d=d||{};if(c.originalEvent){a=b.event.props.length;for(var f;a;){f=b.event.props[--a];c[f]=c.originalEvent[f]}}this.element.trigger(c,d);return!(b.isFunction(e)&&e.call(this.element[0],c,d)===false||c.isDefaultPrevented())}}})(jQuery);
;/*!
 * jQuery UI Mouse 1.8.16
 *
 * Copyright 2011, AUTHORS.txt (http://jqueryui.com/about)
 * Dual licensed under the MIT or GPL Version 2 licenses.
 * http://jquery.org/license
 *
 * http://docs.jquery.com/UI/Mouse
 *
 * Depends:
 *	jquery.ui.widget.js
 */
(function(b){var d=false;b(document).mouseup(function(){d=false});b.widget("ui.mouse",{options:{cancel:":input,option",distance:1,delay:0},_mouseInit:function(){var a=this;this.element.bind("mousedown."+this.widgetName,function(c){return a._mouseDown(c)}).bind("click."+this.widgetName,function(c){if(true===b.data(c.target,a.widgetName+".preventClickEvent")){b.removeData(c.target,a.widgetName+".preventClickEvent");c.stopImmediatePropagation();return false}});this.started=false},_mouseDestroy:function(){this.element.unbind("."+
this.widgetName)},_mouseDown:function(a){if(!d){this._mouseStarted&&this._mouseUp(a);this._mouseDownEvent=a;var c=this,f=a.which==1,g=typeof this.options.cancel=="string"&&a.target.nodeName?b(a.target).closest(this.options.cancel).length:false;if(!f||g||!this._mouseCapture(a))return true;this.mouseDelayMet=!this.options.delay;if(!this.mouseDelayMet)this._mouseDelayTimer=setTimeout(function(){c.mouseDelayMet=true},this.options.delay);if(this._mouseDistanceMet(a)&&this._mouseDelayMet(a)){this._mouseStarted=
this._mouseStart(a)!==false;if(!this._mouseStarted){a.preventDefault();return true}}true===b.data(a.target,this.widgetName+".preventClickEvent")&&b.removeData(a.target,this.widgetName+".preventClickEvent");this._mouseMoveDelegate=function(e){return c._mouseMove(e)};this._mouseUpDelegate=function(e){return c._mouseUp(e)};b(document).bind("mousemove."+this.widgetName,this._mouseMoveDelegate).bind("mouseup."+this.widgetName,this._mouseUpDelegate);a.preventDefault();return d=true}},_mouseMove:function(a){if(b.browser.msie&&
!(document.documentMode>=9)&&!a.button)return this._mouseUp(a);if(this._mouseStarted){this._mouseDrag(a);return a.preventDefault()}if(this._mouseDistanceMet(a)&&this._mouseDelayMet(a))(this._mouseStarted=this._mouseStart(this._mouseDownEvent,a)!==false)?this._mouseDrag(a):this._mouseUp(a);return!this._mouseStarted},_mouseUp:function(a){b(document).unbind("mousemove."+this.widgetName,this._mouseMoveDelegate).unbind("mouseup."+this.widgetName,this._mouseUpDelegate);if(this._mouseStarted){this._mouseStarted=
false;a.target==this._mouseDownEvent.target&&b.data(a.target,this.widgetName+".preventClickEvent",true);this._mouseStop(a)}return false},_mouseDistanceMet:function(a){return Math.max(Math.abs(this._mouseDownEvent.pageX-a.pageX),Math.abs(this._mouseDownEvent.pageY-a.pageY))>=this.options.distance},_mouseDelayMet:function(){return this.mouseDelayMet},_mouseStart:function(){},_mouseDrag:function(){},_mouseStop:function(){},_mouseCapture:function(){return true}})})(jQuery);
;/*
 * jQuery UI Slider 1.8.16
 *
 * Copyright 2011, AUTHORS.txt (http://jqueryui.com/about)
 * Dual licensed under the MIT or GPL Version 2 licenses.
 * http://jquery.org/license
 *
 * http://docs.jquery.com/UI/Slider
 *
 * Depends:
 *	jquery.ui.core.js
 *	jquery.ui.mouse.js
 *	jquery.ui.widget.js
 */
(function(d){d.widget("ui.slider",d.ui.mouse,{widgetEventPrefix:"slide",options:{animate:false,distance:0,max:100,min:0,orientation:"horizontal",range:false,step:1,value:0,values:null},_create:function(){var a=this,b=this.options,c=this.element.find(".ui-slider-handle").addClass("ui-state-default ui-corner-all"),f=b.values&&b.values.length||1,e=[];this._mouseSliding=this._keySliding=false;this._animateOff=true;this._handleIndex=null;this._detectOrientation();this._mouseInit();this.element.addClass("ui-slider ui-slider-"+
this.orientation+" ui-widget ui-widget-content ui-corner-all"+(b.disabled?" ui-slider-disabled ui-disabled":""));this.range=d([]);if(b.range){if(b.range===true){if(!b.values)b.values=[this._valueMin(),this._valueMin()];if(b.values.length&&b.values.length!==2)b.values=[b.values[0],b.values[0]]}this.range=d("<div></div>").appendTo(this.element).addClass("ui-slider-range ui-widget-header"+(b.range==="min"||b.range==="max"?" ui-slider-range-"+b.range:""))}for(var j=c.length;j<f;j+=1)e.push("<a class='ui-slider-handle ui-state-default ui-corner-all' href='#'></a>");
this.handles=c.add(d(e.join("")).appendTo(a.element));this.handle=this.handles.eq(0);this.handles.add(this.range).filter("a").click(function(g){g.preventDefault()}).hover(function(){b.disabled||d(this).addClass("ui-state-hover")},function(){d(this).removeClass("ui-state-hover")}).focus(function(){if(b.disabled)d(this).blur();else{d(".ui-slider .ui-state-focus").removeClass("ui-state-focus");d(this).addClass("ui-state-focus")}}).blur(function(){d(this).removeClass("ui-state-focus")});this.handles.each(function(g){d(this).data("index.ui-slider-handle",
g)});this.handles.keydown(function(g){var k=true,l=d(this).data("index.ui-slider-handle"),i,h,m;if(!a.options.disabled){switch(g.keyCode){case d.ui.keyCode.HOME:case d.ui.keyCode.END:case d.ui.keyCode.PAGE_UP:case d.ui.keyCode.PAGE_DOWN:case d.ui.keyCode.UP:case d.ui.keyCode.RIGHT:case d.ui.keyCode.DOWN:case d.ui.keyCode.LEFT:k=false;if(!a._keySliding){a._keySliding=true;d(this).addClass("ui-state-active");i=a._start(g,l);if(i===false)return}break}m=a.options.step;i=a.options.values&&a.options.values.length?
(h=a.values(l)):(h=a.value());switch(g.keyCode){case d.ui.keyCode.HOME:h=a._valueMin();break;case d.ui.keyCode.END:h=a._valueMax();break;case d.ui.keyCode.PAGE_UP:h=a._trimAlignValue(i+(a._valueMax()-a._valueMin())/5);break;case d.ui.keyCode.PAGE_DOWN:h=a._trimAlignValue(i-(a._valueMax()-a._valueMin())/5);break;case d.ui.keyCode.UP:case d.ui.keyCode.RIGHT:if(i===a._valueMax())return;h=a._trimAlignValue(i+m);break;case d.ui.keyCode.DOWN:case d.ui.keyCode.LEFT:if(i===a._valueMin())return;h=a._trimAlignValue(i-
m);break}a._slide(g,l,h);return k}}).keyup(function(g){var k=d(this).data("index.ui-slider-handle");if(a._keySliding){a._keySliding=false;a._stop(g,k);a._change(g,k);d(this).removeClass("ui-state-active")}});this._refreshValue();this._animateOff=false},destroy:function(){this.handles.remove();this.range.remove();this.element.removeClass("ui-slider ui-slider-horizontal ui-slider-vertical ui-slider-disabled ui-widget ui-widget-content ui-corner-all").removeData("slider").unbind(".slider");this._mouseDestroy();
return this},_mouseCapture:function(a){var b=this.options,c,f,e,j,g;if(b.disabled)return false;this.elementSize={width:this.element.outerWidth(),height:this.element.outerHeight()};this.elementOffset=this.element.offset();c=this._normValueFromMouse({x:a.pageX,y:a.pageY});f=this._valueMax()-this._valueMin()+1;j=this;this.handles.each(function(k){var l=Math.abs(c-j.values(k));if(f>l){f=l;e=d(this);g=k}});if(b.range===true&&this.values(1)===b.min){g+=1;e=d(this.handles[g])}if(this._start(a,g)===false)return false;
this._mouseSliding=true;j._handleIndex=g;e.addClass("ui-state-active").focus();b=e.offset();this._clickOffset=!d(a.target).parents().andSelf().is(".ui-slider-handle")?{left:0,top:0}:{left:a.pageX-b.left-e.width()/2,top:a.pageY-b.top-e.height()/2-(parseInt(e.css("borderTopWidth"),10)||0)-(parseInt(e.css("borderBottomWidth"),10)||0)+(parseInt(e.css("marginTop"),10)||0)};this.handles.hasClass("ui-state-hover")||this._slide(a,g,c);return this._animateOff=true},_mouseStart:function(){return true},_mouseDrag:function(a){var b=
this._normValueFromMouse({x:a.pageX,y:a.pageY});this._slide(a,this._handleIndex,b);return false},_mouseStop:function(a){this.handles.removeClass("ui-state-active");this._mouseSliding=false;this._stop(a,this._handleIndex);this._change(a,this._handleIndex);this._clickOffset=this._handleIndex=null;return this._animateOff=false},_detectOrientation:function(){this.orientation=this.options.orientation==="vertical"?"vertical":"horizontal"},_normValueFromMouse:function(a){var b;if(this.orientation==="horizontal"){b=
this.elementSize.width;a=a.x-this.elementOffset.left-(this._clickOffset?this._clickOffset.left:0)}else{b=this.elementSize.height;a=a.y-this.elementOffset.top-(this._clickOffset?this._clickOffset.top:0)}b=a/b;if(b>1)b=1;if(b<0)b=0;if(this.orientation==="vertical")b=1-b;a=this._valueMax()-this._valueMin();return this._trimAlignValue(this._valueMin()+b*a)},_start:function(a,b){var c={handle:this.handles[b],value:this.value()};if(this.options.values&&this.options.values.length){c.value=this.values(b);
c.values=this.values()}return this._trigger("start",a,c)},_slide:function(a,b,c){var f;if(this.options.values&&this.options.values.length){f=this.values(b?0:1);if(this.options.values.length===2&&this.options.range===true&&(b===0&&c>f||b===1&&c<f))c=f;if(c!==this.values(b)){f=this.values();f[b]=c;a=this._trigger("slide",a,{handle:this.handles[b],value:c,values:f});this.values(b?0:1);a!==false&&this.values(b,c,true)}}else if(c!==this.value()){a=this._trigger("slide",a,{handle:this.handles[b],value:c});
a!==false&&this.value(c)}},_stop:function(a,b){var c={handle:this.handles[b],value:this.value()};if(this.options.values&&this.options.values.length){c.value=this.values(b);c.values=this.values()}this._trigger("stop",a,c)},_change:function(a,b){if(!this._keySliding&&!this._mouseSliding){var c={handle:this.handles[b],value:this.value()};if(this.options.values&&this.options.values.length){c.value=this.values(b);c.values=this.values()}this._trigger("change",a,c)}},value:function(a){if(arguments.length){this.options.value=
this._trimAlignValue(a);this._refreshValue();this._change(null,0)}else return this._value()},values:function(a,b){var c,f,e;if(arguments.length>1){this.options.values[a]=this._trimAlignValue(b);this._refreshValue();this._change(null,a)}else if(arguments.length)if(d.isArray(arguments[0])){c=this.options.values;f=arguments[0];for(e=0;e<c.length;e+=1){c[e]=this._trimAlignValue(f[e]);this._change(null,e)}this._refreshValue()}else return this.options.values&&this.options.values.length?this._values(a):
this.value();else return this._values()},_setOption:function(a,b){var c,f=0;if(d.isArray(this.options.values))f=this.options.values.length;d.Widget.prototype._setOption.apply(this,arguments);switch(a){case "disabled":if(b){this.handles.filter(".ui-state-focus").blur();this.handles.removeClass("ui-state-hover");this.handles.propAttr("disabled",true);this.element.addClass("ui-disabled")}else{this.handles.propAttr("disabled",false);this.element.removeClass("ui-disabled")}break;case "orientation":this._detectOrientation();
this.element.removeClass("ui-slider-horizontal ui-slider-vertical").addClass("ui-slider-"+this.orientation);this._refreshValue();break;case "value":this._animateOff=true;this._refreshValue();this._change(null,0);this._animateOff=false;break;case "values":this._animateOff=true;this._refreshValue();for(c=0;c<f;c+=1)this._change(null,c);this._animateOff=false;break}},_value:function(){var a=this.options.value;return a=this._trimAlignValue(a)},_values:function(a){var b,c;if(arguments.length){b=this.options.values[a];
return b=this._trimAlignValue(b)}else{b=this.options.values.slice();for(c=0;c<b.length;c+=1)b[c]=this._trimAlignValue(b[c]);return b}},_trimAlignValue:function(a){if(a<=this._valueMin())return this._valueMin();if(a>=this._valueMax())return this._valueMax();var b=this.options.step>0?this.options.step:1,c=(a-this._valueMin())%b;a=a-c;if(Math.abs(c)*2>=b)a+=c>0?b:-b;return parseFloat(a.toFixed(5))},_valueMin:function(){return this.options.min},_valueMax:function(){return this.options.max},_refreshValue:function(){var a=
this.options.range,b=this.options,c=this,f=!this._animateOff?b.animate:false,e,j={},g,k,l,i;if(this.options.values&&this.options.values.length)this.handles.each(function(h){e=(c.values(h)-c._valueMin())/(c._valueMax()-c._valueMin())*100;j[c.orientation==="horizontal"?"left":"bottom"]=e+"%";d(this).stop(1,1)[f?"animate":"css"](j,b.animate);if(c.options.range===true)if(c.orientation==="horizontal"){if(h===0)c.range.stop(1,1)[f?"animate":"css"]({left:e+"%"},b.animate);if(h===1)c.range[f?"animate":"css"]({width:e-
g+"%"},{queue:false,duration:b.animate})}else{if(h===0)c.range.stop(1,1)[f?"animate":"css"]({bottom:e+"%"},b.animate);if(h===1)c.range[f?"animate":"css"]({height:e-g+"%"},{queue:false,duration:b.animate})}g=e});else{k=this.value();l=this._valueMin();i=this._valueMax();e=i!==l?(k-l)/(i-l)*100:0;j[c.orientation==="horizontal"?"left":"bottom"]=e+"%";this.handle.stop(1,1)[f?"animate":"css"](j,b.animate);if(a==="min"&&this.orientation==="horizontal")this.range.stop(1,1)[f?"animate":"css"]({width:e+"%"},
b.animate);if(a==="max"&&this.orientation==="horizontal")this.range[f?"animate":"css"]({width:100-e+"%"},{queue:false,duration:b.animate});if(a==="min"&&this.orientation==="vertical")this.range.stop(1,1)[f?"animate":"css"]({height:e+"%"},b.animate);if(a==="max"&&this.orientation==="vertical")this.range[f?"animate":"css"]({height:100-e+"%"},{queue:false,duration:b.animate})}}});d.extend(d.ui.slider,{version:"1.8.16"})})(jQuery);
;

define("jquery-ui.min", ["jquery.min"], function(){});

/*!
 * jQuery imagesLoaded plugin v1.2.4
 * http://github.com/desandro/imagesloaded
 *
 * MIT License. by Paul Irish et al.
 */
;(function($, undefined) {

// blank image data-uri
var BLANK = 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==';

// $('#my-container').imagesLoaded(myFunction)
// or
// $('img').imagesLoaded(myFunction)

// execute a callback when all images have loaded.
// needed because .load() doesn't work on cached images

// callback is executed when all images has fineshed loading
// callback function arguments: $all_images, $proper_images, $broken_images
// `this` is the jQuery wrapped container

// returns previous jQuery wrapped container extended with deferred object
// done method arguments: .done( function( $all_images ){ ... } )
// fail method arguments: .fail( function( $all_images, $proper_images, $broken_images ){ ... } )
// progress method arguments: .progress( function( images_count, loaded_count, proper_count, broken_count )

$.fn.imagesLoaded = function( callback ) {
	var $this = this,
		deferred = $.isFunction($.Deferred) ? $.Deferred() : 0,
		hasNotify = $.isFunction(deferred.notify),
		$images = $this.find('img').add( $this.filter('img') ),
		len = $images.length,
		loaded = [],
		proper = [],
		broken = [];

	function doneLoading() {
		var $proper = $(proper),
			$broken = $(broken);

		if ( deferred ) {
			if ( broken.length ) {
				deferred.reject( $images, $proper, $broken );
			} else {
				deferred.resolve( $images );
			}
		}

		if ( $.isFunction( callback ) ) {
			callback.call( $this, $images, $proper, $broken );
		}
	}

	function imgLoaded( event ) {
		var img = event.target;

		// dont proceed if img src is blank or if img is already loaded
		if ( img.src === BLANK || $.inArray( img, loaded ) !== -1 ) {
			return;
		}

		// store element in loaded images array
		loaded.push( img );

		// keep track of broken and properly loaded images
		if ( event.type === 'error' ) {
			broken.push( img );
		} else {
			proper.push( img );
		}

		// cache event type in element data for future calls
		$.data( img, 'imagesLoaded', { event: event.type, src: img.src } );

		if ( hasNotify ) {
			deferred.notify( $images.length, loaded.length, proper.length, broken.length );
		}

		if ( --len <= 0 ){
			setTimeout( doneLoading );
			$images.unbind( '.imagesLoaded', imgLoaded );
		}
	}

	// if no images, trigger immediately
	if ( !len ) {
		doneLoading();
	}

	$images.bind( 'load.imagesLoaded error.imagesLoaded', imgLoaded ).each( function() {
		var src = this.src;
		// find out if this image has been already checked for status
		var cached = $.data( this, 'imagesLoaded' );
		// if it was, and src has not changed, trigger the corresponding event
		if ( cached && cached.src === src ) {
			$(this).triggerHandler( cached.event );
			return;
		}
		// cached images don't fire load sometimes, so we reset src.
		// webkit hack from http://groups.google.com/group/jquery-dev/browse_thread/thread/eee6ab7b2da50e1f
		// data uri bypasses webkit log warning (thx doug jones)
		this.src = BLANK;
		this.src = src;
	});

	return deferred ? deferred.promise( $this ) : $this;
};

})(jQuery);

define("jquery.imagesloaded", ["jquery.min"], (function (global) {
    return function () {
        var ret, fn;
        return ret || global.jQuery.fn.imagesLoaded;
    };
}(this)));

define('render',[],function() {
	jQuery.expr[':'].parents = function(a,i,m){
	    return jQuery(a).closest(m[3]).length < 1;
	};

	var infoFigure = function(){
		jQuery('figure img').each(function(key, value){
			var img = jQuery(this);
			var width = img.width();
			var info = img.parents('figure').children().filter('figcaption');
			var foot = img.parents('.iocfigure').children().filter('.footfigure');
			info.css('width',width);
			if (img.closest('.iocexample').length === 0){
				info.css('max-width','75%');
				foot.css('max-width','75%');
			}
			foot.css('width',width);
		});
	};

	var infoTable = function(){
		jQuery('div.ioctable table, div.iocaccounting table').each(function(key, value){
			var table = jQuery(this);
			var width = table.width();
			var info = table.parents('.iocaccounting,.ioctable').children().filter('.titletable');
			var foot = table.parents('.iocaccounting,.ioctable').children().filter('.foottable');
			info.css('width',width);
			info.css('max-width','100%');
			foot.css('width',width);
			foot.css('max-width','100%');
		});
	};
	
	var thTable = function(){
		jQuery('div.ioctable table th').each(function(key, value){
			var th = jQuery(this);
			th.closest("tr").addClass("borderth");
		});
	};
	
	var previewImage = function(img){
		var src = $(img).attr('src');
		$('#back_preview').removeClass("hidden");
		$('#preview .prevcontent').empty();
		$('#preview .prevcontent').html('<span class="closepreview"></span><img src="'+ src +'" alt="Image preview" />')
					 .closest('#preview')
					 	.removeClass()
					 	.addClass('zoomout')
					 .end()
					 .imagesLoaded(function(){
						var $img = $(this).find('img'); 
						height = $img.height();
						width = $img.width();
						if (height > $(window).height()){
							height = $(window).height() - 50;
							$(this).css('height', height);
							$img.css('max-height', height);
						}
						if (width > $(window).width() - 50){
							width = $(window).width() - 50;
							$(this).css('width', width);
							$img.css('max-width', width);
						}
						$(this).find('.closepreview').css('margin-right',-($img.width()/2)-16);
						$(this).css('margin-top',-(height/2));
		});
	};
	
	return {"infoTable":infoTable,
		    "infoFigure":infoFigure,
		    "thTable":thTable,
		    "previewImage":previewImage};
});

define ('functions',["render"],function(render){
    var disableshortcuts = false;
    var ltoc = parseInt($("#toc").css('left'),10);
    var lbridge = parseInt($("#bridge").css('left'),10);
    var lfavcount = parseInt($("#favcounter").css('left'),10);
    var lfavorites = parseInt($("#favorites").css('left'),10);
    var topheader = parseInt($("#header").css('height'),10);
    var paddingarticle = parseInt($("article").css('padding-bottom'),10);
    var defaultsettings = '{"menu":[{"mvisible":1}],"toc":[{"tvisible":0}],"settings":[{"fontsize":2,"contrast":0,"alignment":0,"hyphen":0,"width":2,"mimages":1,"scontent":1}]}';
    var defaultbookmarks = '{"fav":[{"urls":"0"}]}';
    var defaultbookquizzes = '{"quiz":[{"urls":"0"}]}';
    var showtooltips = false;
    var cookiesufix = '@IOCCOOKIENAME@';
    var cookiegeneral = 'ioc_settings';//Always same settings for all materials
    var cookiefavorites = 'ioc_'+cookiesufix+'_bookmarks';
    var cookiequizzes = 'ioc_'+cookiesufix+'_quizzes';

    $('#menu li').click(function(e) {
        setmenu(this);
    });

    $('#menu li div img').hover(
        function(e){
            if(showtooltips){
                showhelp($(this).closest("li"),true,false);
            }
        },
        function(e){
            if(showtooltips){
                showhelp($(this).closest("li"),false,false);
            }
        }
    );

    $('#sidebar-hide img').hover(
        function(e){
            if(showtooltips){
                showhelp($(this).parent(),true,false);
            }
        },
        function(e){
            if(showtooltips){
                showhelp($(this).parent(),false,false);
            }
        }
    );

    $('#search > form > input').hover(
        function(e){
            if(showtooltips){
                showhelp($(this).closest("div"),true,false);
            }
        },
        function(e){
            if(showtooltips){
                showhelp($(this).closest("div"),false,false);
            }
        }
    );

    $('#favcounter span').hover(
        function(e){
            if(showtooltips){
                showhelp($(this).parent(),true,false);
            }
        },
        function(e){
            if(showtooltips){
                showhelp($(this).parent(),false,false);
            }
        }
    );

    $('#content').on("click", function(e) {
            setmenu(null);
            $("#help").addClass("hidden");
    });

    $('#sidebar-hide').click(function(e) {
        e.preventDefault();
        $("#aside").animate({
                left: parseInt($("#aside").css('left'),10) == 0 ?
                -$("#aside").outerWidth() :
                0
        });
        $("#toc").animate({
                left: ((parseInt($("#toc").css('left'),10) == ltoc) && ($("#toc").css('display') !== 'none')) ?
                -$("#aside").outerWidth()-$("#toc").outerWidth():
                ltoc
        });
        $("#settings").animate({
                left: ((parseInt($("#settings").css('left'),10) == ltoc) && ($("#settings").css('display') !== 'none')) ?
                -$("#aside").outerWidth()-$("#settings").outerWidth():
                ltoc
        });

        $("#bridge").animate({
                left: ((parseInt($("#bridge").css('left'),10) == lbridge) && ($("#bridge").css('display') !== 'none')) ?
                -$("#aside").outerWidth()-$("#bridge").outerWidth():
                lbridge
        });


        $("#favcounter").animate({
                left: ((parseInt($("#favcounter").css('left'),10) == lfavcount) && ($("#favcounter").css('display') !== 'none')) ?
                -$("#aside").outerWidth()-$("#favcounter").outerWidth():
                lfavcount
        });

        $("#favorites").animate({
                left: ((parseInt($("#favorites").css('left'),10) == lfavorites) && ($("#favorites").css('display') !== 'none')) ?
                -$("#aside").outerWidth()-$("#favorites").outerWidth():
                lfavorites
        });
        if (!$("#favorites").hasClass("hidden")){
                $("#bridge").toggleClass("hidden");
        }

        setbackground(($("body").css('background-position') !== '0px 0px'));
        var info=getcookie(cookiegeneral);
        var patt=/"mvisible":0/g;
        if (patt.test(info)){
            setCookieProperty(cookiegeneral,'mvisible', 1);
        }else{
            setCookieProperty(cookiegeneral,'mvisible', 0);
        }
    });

    //Contrast
    $('#style-newspaper').click(function() {
        setContrast(0);
        setCookieProperty(cookiegeneral,'contrast', 0);
    });

    $('#style-novel').click(function() {
    	setContrast(1);
        setCookieProperty(cookiegeneral,'contrast', 1);
    });

    $('#style-ebook').click(function() {
    	setContrast(2);
        setCookieProperty(cookiegeneral,'contrast', 2);
    });

    $('#style-inverse').click(function() {
    	setContrast(3);
        setCookieProperty(cookiegeneral,'contrast', 3);
    });

    $('#style-athelas').click(function() {
    	setContrast(4);
        setCookieProperty(cookiegeneral,'contrast', 4);
    });

    //Alignment
    $('#text-alignment').click(function() {
        if ($(this).attr("checked")){
                setAlignment(1);
                setCookieProperty(cookiegeneral,'alignment', 1);
        }else{
        setAlignment(0);
                setCookieProperty(cookiegeneral,'alignment', 0);
        }
    });

    //Hyphenation
    $('#text-hyphen').click(function() {
        if ($(this).attr("checked")){
        setHyphenation(0);
                setCookieProperty(cookiegeneral,'hyphen', 0);
        }else{
        setHyphenation(1);
                setCookieProperty(cookiegeneral,'hyphen', 1);
        }
    });


    //Show or hide secondary content
    $('#main-images').click(function() {
        if ($(this).attr("checked")){
                setMainFig(1);
                render.infoFigure();
                setCookieProperty(cookiegeneral,'mimages', 1);
        }else{
                setMainFig(0);
                setCookieProperty(cookiegeneral,'mimages', 0);
        }
    });


    //Show or hide secondary content
    $('#secondary-content').click(function() {
        if ($(this).attr("checked")){
        setSecContent(1);
                setCookieProperty(cookiegeneral,'scontent', 1);
        }else{
                setSecContent(0);
                setCookieProperty(cookiegeneral,'scontent', 0);
        }
    });


    $('#upbutton').click(function() {
        $("html,body").animate({
                scrollTop: 0
        }, 500);
    });

    $('#navmenu').click(function() {
        setCookieProperty(cookiegeneral,'selected','null');
    });

    $('#favcounter').click(function() {
        var info = getcookie(cookiefavorites);
        var object = $.parseJSON(info);
        if ($("#favorites").hasClass('hidden')){
                setFavUrls(object.fav[0].urls);
        }else{
                $("#favorites").addClass('hidden');
                $("#bridge").addClass('hidden');
        }
    });


    $("#frmsearch input[name='q']").on('keypress', function (event){
            if (event.which === 13){
                    $("#frmsearch").submit();
            }
    });

    $("#frmsearch").focusin(function(){
            disableshortcuts = true;
    });

    $("#frmsearch").focusout(function(){
            disableshortcuts = false;
    });

    $(".mediavideo").focusin(function(){
            disableshortcuts = true;
    });

    $(".mediavideo").focusout(function(){
            disableshortcuts = false;
    });

    $(window).on('keyup', function (event){
            if(ispageIndex() || ispageSearch()){
                    return;
            }
            //ESC
            if (event.keyCode === 27){
                    event.preventDefault();
                    $("#help, #back_preview, #preview").addClass("hidden");
                    setmenu(null);
            }
    });

    $(window).on('keypress', function (event){
            if(ispageIndex() || ispageSearch()){
                    return;
            }
            if (!disableshortcuts){
                    switch(event.which){
                            //?
                            case 63:$("#help").toggleClass("hidden");
                                     break;
                            //b
                            case 98:var top;
                                            top = $(".footer").offset().top;
                                            $(window).scrollTop($(window).scrollTop()+top);
                                     break;
                            //g
                            case 103:setmenu($("#menu li[name='toc']"));
                                             break;
                            //h
                            case 104:url = $("#prevpage a").attr("href");
                                             if (url){
                                                     document.location.href = url;
                                             }
                                             break;
                            //i
                            case 105:document.location.href = $("#navmenu ul > li > a").attr("href");
                                             break;
                            //j
                            case 106:$(window).scrollTop($(window).scrollTop()+100)
                                             break;
                            //k
                            case 107:$(window).scrollTop($(window).scrollTop()-100);
                                             break;
                            //l
                            case 108:url = $("#nextpage a").attr("href");
                                             if (url){
                                                     document.location.href = url;
                                             }
                                             break;
                            //o
                            case 111:setmenu($("#menu li[name='settings']"));
                                             break;
                            //p
                            case 112:setmenu($("#menu li[name='printer']"));
                                             break;
                            //s
                            case 115:setmenu($("#menu li[name='favorites']"));
                                             break;
                            //t
                            case 116:$(window).scrollTop(0);
                                             break;
                    }
            }
    });

    var setFontsize = (function (info){
            var options=new Array("text-tiny","text-small","text-normal","text-big","text-huge");
            $("article").addClass(options[info]);
            $(".pnpage").addClass(options[info]);
            for (i=0;i<options.length;i++){
                    if (i==info){
                            continue;
                    }
                    $("article").removeClass(options[i]);
                    $(".pnpage").removeClass(options[i]);
            }
            if (info === options.length-1){
                    $("article").css("padding-bottom","8em");
            }else{
                    $("article").css("padding-bottom",paddingarticle);
            }
            render.infoTable();
            render.infoFigure();
            setCookieProperty(cookiegeneral,'fontsize', info);
    });

    var setContrast = (function (info){
            var options=new Array("style-newspaper","style-novel","style-ebook","style-inverse","style-athelas");
            $('#'+options[info]).addClass('active');
            $("body").addClass(options[info]);
            for (i=0;i<options.length;i++){
                    if (i==info){
                            continue;
                    }
                    $('#'+options[i]).removeClass('active');
                    $("body").removeClass(options[i]);
            }
    });

    var setAlignment = (function (info){
            var options=new Array("text-left","text-justify");
            other = (info==0)?1:0;
            $("article").addClass(options[info]);
    $("article").removeClass(options[other]);
    });

    var setHyphenation = (function (info){
            var state = (info==0)?false:true;
            Hyphenator.doHyphenation = state;
            Hyphenator.toggleHyphenation();
    });

    var setMainFig = (function (show){
            if (show == 1){
                    $("article .iocfigure").removeClass("hidden");
            }else{
                    $("article .iocfigure").addClass("hidden");
            }
    });

    var setSecContent = (function (show){
            var elements = new Array("iocfigurec", "iocnote", "ioctext", "iocreference");
            for (i=0;i<elements.length;i++){
                    if (show == 1){
                            $("article ."+elements[i]).removeClass("hidden");
                    }else{
                            $("article ."+elements[i]).addClass("hidden");
                    }
            }
    });

    var setArticleWidth = (function (info){
            var options=new Array("x-narrow","narrow","medium","wide","x-wide","x-extra-wide");
            $("article").addClass(options[info]);
            for (i=0;i<options.length;i++){
                    if (i==info){
                            continue;
                    }
                    $("article").removeClass(options[i]);
            }
            var width = parseInt($("article").outerWidth(true)) + 20;
            $(document).ready(setpnpage());
            render.infoTable();
            render.infoFigure();
            setCookieProperty(cookiegeneral,'width', info);
    });

    var setpnpage = (function (){
        var width = parseInt($("article").outerWidth(true)) + 20;
        if (isIE() || isChrome() || isNaN(width)){
                width = parseInt($("article").css('width')) + 80;
        }
        $(".pnpage").css({"width":width, "margin-left":-(width/2)});
    });

    var setWidthSlider = (function (value){
            $("#slider-width").slider("option", "value", value);
    });

    var setFontSlider = (function (value){
            $("#slider-font").slider("option", "value", value);
    });

    var setCookieProperty = (function (name, n, value){
            var info=getcookie(name);
            if (info){
                    if (typeof value == 'number'){
                            var patt = new RegExp("\""+n+"\":\\d+", 'g');
                            info=info.replace(patt, "\""+n+"\":"+value);
                    }else{
                            var patt = new RegExp("\""+n+"\":\".*?\"", 'g');
                            info=info.replace(patt, "\""+n+"\":\""+value+"\"");
                    }
                    setcookie(name,info);
            }
    });

    var settings = (function (info){
        if (ispageIndex()){
                $("body").removeClass().addClass('index');
        }else{
                setContrast(info.settings[0]['contrast']);
                setFontsize(info.settings[0]['fontsize']);
                setFontSlider(info.settings[0]['fontsize']);
                setAlignment(info.settings[0]['alignment']);
                setHyphenation(info.settings[0]['hyphen']);
                setArticleWidth(info.settings[0]['width']);
                setWidthSlider(info.settings[0]['width']);
                setMainFig(info.settings[0]['mimages']);
                setSecContent(info.settings[0]['scontent']);
                setCheckboxes(info);
                render.thTable();
                postohashword();
        }
    });

    var setCheckboxes = (function (info){
            if(info.settings[0]['alignment']==1){
                    $("#text-alignment").attr("checked","checked");
            }else{
                    $("#text-alignment").removeAttr("checked");
            }
            if(info.settings[0]['hyphen']==0){
                    $("#text-hyphen").attr("checked","checked");
            }else{
                    $("#text-hyphen").removeAttr("checked");
            }
            if(info.settings[0]['mimages']==1){
                    $("#main-images").attr("checked","checked");
            }else{
                    $("#main-images").removeAttr("checked");
            }
            if(info.settings[0]['scontent']==1){
                    $("#secondary-content").attr("checked","checked");
            }else{
                    $("#secondary-content").removeAttr("checked");
            }
    });

    var sidemenu = (function (info){
        if (!ispageIndex()){
            $("#toc").css('left', ltoc);
            $("#settings").css('left', ltoc);
            if (info.menu[0]['mvisible']==1){
                    $("#aside").css('left','0px');
                    setbackground(true);
            }else{
                    $("#aside").css('left',-$("#aside").outerWidth());
                    $("#favcounter").css('left',-$("#aside").outerWidth()-$("#favcounter").outerWidth());
                    setbackground(false);
            }
            info = getcookie(cookiefavorites);
            if (info){
                    var object = $.parseJSON(info);
                    setFavCounter(object.fav[0]['urls']);
            }
        }
    });

    var setmenu = (function (obj){
        hideMenuOptions();
        if (!obj){
            $("#menu ul").children().each(function(i){
                $(this).removeClass('menuselected');
            });
            return;
        }
        var show = true;
        var type = $(obj).attr("name");
        $(obj).closest('ul').children().each(function(i){
            if (type === $(this).attr("name") && $(obj).hasClass('menuselected')){show=false;}
            $(this).removeClass('menuselected');
        });
        if (show){
            if (type === 'toc'){
                $(obj).addClass('menuselected');
                $('#toc').removeClass('hidden');
                $("#bridge").removeClass('hidden');
                $("#bridge").css('top',$("#toc").css('top'));
                enablemenuoption();
            }else{
                if(type === 'settings'){
                   $(obj).addClass('menuselected');
                   $('#settings').removeClass('hidden');
                   $("#bridge").removeClass('hidden');
                   $("#bridge").css('top',$("#settings").css('top'));
                }else{
                    if(type === 'printer'){
                        window.print();
                    }else{
                        if(type === 'favorites'){
                            var url = $(obj).find('div>img').attr('src');
                            if (/favorites/.test(url)){
                                url = url.replace(/favorites/, 'fav_ok');
                            }else{
                                url = url.replace(/fav_ok/, 'favorites');
                            }
                            $(obj).find('div>img').attr('src', url);
                            editFavorite(document.location.pathname,false);
                        }else{
                            if(type === 'help_icon'){
                                var url = $(obj).find('div>img').attr('src');
                                if (/help_icon\./.test(url)){
                                    url = url.replace(/help_icon/, 'help_icon_active');
                                    showtooltips = true;
                                }else{
                                    url = url.replace(/help_icon_active/, 'help_icon');
                                    hidetooltips();
                                    showtooltips = false;
                                }
                                $(obj).find('div>img').attr('src', url);
                            }
                        }
                    }
                }
            }
        }
    });

    var calpostooltips = (function (){
            $("#menu li").each(function(){
                    var type = $(this).attr("name");
                    var tooltip = $('#help-'+type);
                    var item_pos = $(this).offset();
                    tooltip.css({'visibility':'hidden'}).removeClass('hidden');
                    tooltip.css({'top':(item_pos.top-(tooltip.outerHeight(true)/2) + 18),
                                             'visibility':'visible'})
                                            .addClass('hidden');
            });
            calposfavcountooltip();
            var item = $("#sidebar-hide").offset();
            var tooltip = $("#help-sidebar-hide");
            tooltip.css({'visibility':'hidden'}).removeClass('hidden');
            tooltip.css({top:(item.top-(tooltip.outerHeight(true)/2) + 12),
                                     'visibility':'visible'})
                                    .addClass('hidden');
            item = $("#search input[type='text']");
            var item_pos = item.offset();
            tooltip = $("#help-search");
            tooltip.css({'visibility':'hidden'}).removeClass('hidden');
            tooltip.css({top:item_pos.top + (tooltip.outerHeight(true)/2) + 15,
                                 left:(item_pos.left + (item.outerWidth(true)/2) - (tooltip.outerWidth(true)/2) - 18),
                                 'visibility':'visible'})
                                            .addClass('hidden');
            tooltip = $("#help-search");
            tooltip.css({'visibility':'hidden'}).removeClass('hidden');
            tooltip.css({top:item_pos.top + (tooltip.outerHeight(true)/2) + 15,
                                 left:(item_pos.left + (item.outerWidth(true)/2) - (tooltip.outerWidth(true)/2) - 18),
                                 'visibility':'visible'})
                                            .addClass('hidden');
    });

    var calposfavcountooltip = (function (){
            var item = $("#favcounter").offset();
            var tooltip = $("#help-favcounter");
            tooltip.css({'visibility':'hidden'}).removeClass('hidden');
            tooltip.css({'top':(item.top-(tooltip.outerHeight(true)/2) + 10),
                                     'visibility':'visible'})
                                    .addClass('hidden');
    });

    var hidetooltips = (function (){
            $("#help-tooltips > div").each(function(){
                    $(this).addClass("hidden");
            });
    });


    var showhelp = (function (obj, show, header){
            var type = (header)?'header':$(obj).attr("name");
            var tooltip = $('#help-'+type);
            if(show){
                    tooltip.removeClass('hidden');
                    tooltip.fadeTo("fast", 0.8);
            }else{
                    tooltip.fadeTo("fast", 0, function(){tooltip.addClass('hidden');});
            }

            if (header && show){
                    var item_pos = $(obj).offset();
                    tooltip.css({top:item_pos.top - (tooltip.outerHeight()/2) + 15,
                                            left:item_pos.left + $(obj).width() + 40
                    });
            }
    });

    var enablemenuoption = (function(){
        var url = document.location.pathname;
        var dir = "WebContent/";
        //Comprovar si estem en les pàgines d'introduccio
        var patt = new RegExp(dir,'g');
        if (!patt.test(url)){
            var node = url.match(/\w+(?=\.html)/);
        }else{
            url = url.slice(url.indexOf(dir)+dir.length,url.length);
            var elements = url.split("/");
            var node = '';
            var parent_node = '';
            for (i=0;i<elements.length;i++) {
                if (elements[i].indexOf('html')==-1){
                    parent_node+=elements[i];
                }
                node+=elements[i];
            }
            //Remove html extension
            node = node.replace(/\.html/,'');

            if (parent_node != ''){
                $('#'+parent_node).parent().each(function(key, value) {
                    $(this).show();
                });
                $('#'+parent_node+"> ul").show();
                $('#'+parent_node+"> p > .buttonexp").addClass("tocdown");
            }
        }
        if (node != ''){
            $('#'+node).addClass("optselected");
        }
    });

    var setbackground = (function(show){
            if(!show){
                    $("body").css("background-position","-60px 0");
            }else{
                    $("body").css("background-position","0 0");
            }
    });

    var ispageIndex = (function (){
            var url = document.location.pathname;
            return /index\.html|.*?\/(?!.*?\.html$)/.test(url);
    });

    var ispageSearch = (function (){
            var url = document.location.pathname;
            return /search\.html/.test(url);
    });

    var ispageExercise = (function (){
            var url = document.location.pathname;
            return /activitats\.html|exercicis\.html/.test(url);
    });

    var ispagenoHeader = (function (){
            var url = document.location.pathname;
            var patt = new RegExp("/a\\d+/","g")
            return !patt.test(url);
    });

    var islocalChrome = (function (){
            return (/Chrome/.test(navigator.userAgent) && /file/.test(document.location.protocol));
    });

    var isChrome = (function (){
            return (/Chrome/.test(navigator.userAgent));
    });

    var isIE = (function (){
            return (/MSIE/.test(navigator.userAgent));
    });

    var postohashword = (function (){
            var url = document.location.hash;
            if (url){
                    url = url.replace(/#/,'');
                    var offset = $("a[id='"+url+"']").offset();
                    if (offset !== null){
                            $(window).scrollTop(offset.top-110);
                    }
            }
    });

    var postosearchword = (function (){
            var url = document.location.search;
            if (/highlight/.test(url)){
                            var offset = $(".highlight:first").offset();
                            if (offset !== null){
                                    $(window).scrollTop(offset.top-80);
                            }
            }
    });

    var indexToc = (function(show){
            if (show){
                    var mtop = parseInt($(".meta").outerHeight(true))+parseInt($(".meta img").outerHeight(true));
                    $(".meta").css('margin-top',-mtop);
                    $(".metainfobc").addClass('hidden');
                    $(".metainfobr").addClass('hidden');
                    $("#header .head").css('margin-top', '0px');
                    $("#header .headdocument").removeClass('hidden');
                    $(".headtoc h1 img").hide();
                    $(".headtoc").css('margin-top', -$("#content").outerHeight(true)+$(".headtoc h1").outerHeight(true)-40);
                    $(".headtoc").css('min-height','100%');
                    $(".headtoc").removeClass("headtocdown").addClass("headtocup");
                    $(".headtoc h1").removeClass("hover");
                    $(".indextoc").show();
                    $("#button_start").show();
            }else{
                    $(".meta").css('margin-top','5px');
                    $(".metainfobc").removeClass('hidden');
                    $(".metainfobr").removeClass('hidden');
                $(".metainfobc").css('margin-top',$(window).height()-$(".headtoc h1").outerHeight(true)-$(".metainfobc").outerHeight()-5);
                $(".metainfobr").css('margin-top',$(window).height()-$(".headtoc h1").outerHeight(true)-$(".metainfobr").outerHeight()-5);
                var htop = parseInt($(window).height()-$(".headtoc h1").outerHeight(true)-40);
                if (htop < 0){
                    htop = 0;
                }
                    $("#header .head").css('margin-top', htop);
                    $("#header .headdocument").addClass('hidden');
                    $(".headtoc h1 img").show();
                    $(".headtoc").css('margin-top', -$(".headtoc h1").outerHeight(true));
                    $(".headtoc").css('min-height','0');
                    $(".indextoc").hide();
                    $(".headtoc h1").addClass("hover");
                    $("#button_start").hide();
            }
    });

    //Add or remove a url inside cookie
    var editFavorite = (function(url,idheader){
            var info = getcookie(cookiefavorites);
            if (info){
                    var object = $.parseJSON(info);
                    var urls = [];
                    //escape parentheses
                    var escapedurl = url.replace(/\(|\)/g,'\\$&');
                    var patt = new RegExp(";;"+escapedurl+"\\|.*?(?=$|;;)", 'g');
                    if(patt.test(object.fav[0]['urls'])){
                            urls = object.fav[0]['urls'].replace(patt, "");
                            setCookieProperty(cookiefavorites,'urls', urls);
                            setFavCounter(urls);
                    }else{
                            var title;
                            if (idheader){
                                    title = $("h2,h3,h4").children("a").filter("a[id="+idheader+"]").parent().text();
                            }else{
                                    title = $("h1").text();
                                    title = title.replace(/ /,"");
                            }
                            if (title == ""){
                                    title = url;
                            }
                            url = object.fav[0]['urls'] + ";;" + url + "|" + title;
                            setCookieProperty(cookiefavorites,'urls', url);
                            setFavCounter(url);
                    }
            }
    });

    var setFavCounter = (function (urls){
            var patt = new RegExp(";;", 'g');
            var result = urls.match(patt);
            if(result && result.length > 0){
                    $("#favcounter").removeClass("hidden");
                    $("#favcounter span").html(result.length);
                    if (result.length === 1){
                            calposfavcountooltip();
                    }
            }else{
                    $("#favcounter").addClass("hidden");
            }
    });

    var setFavButton = (function (info){
            if (info){
                    var url = document.location.pathname;
                    var patt = new RegExp(";;"+url+"\\|.*?(?=$|;;)", 'g');
                    if(patt.test(info.fav[0]['urls'])){
                            var obj = $("#menu li[name=favorites]").find('div>img')
                            var src = $(obj).attr('src');
                            src = src.replace(/\w+(?=\.png)/, 'fav_ok');
                            $(obj).attr('src', src);
                    }
            }
    });

    var setFavHeaders = (function (info){
            if (info){
                    var url = document.location.pathname;
                    var patt;
                    $("h2,h3,h4").each(function(i){
                            patt = new RegExp(";;"+url+"#"+$(this).children("a").attr("id")+"\\|.*?(?=$|;;)", 'g');
                            if(patt.test(info.fav[0]['urls'])){
                                    $(this).children("span[name='star']").removeClass().addClass("starmarked").show();
                            }
                    });
            }
    });

    var setCheckExercises = (function (info){
            if (info){
                    var url = document.location.pathname;
                    var patt;
                    $("h2").each(function(i){
                            patt = new RegExp(";;"+url+"\\|"+$(this).children("a").attr("id"), 'g');
                            if(patt.test(info.quiz[0]['urls'])){
                                    $(this).children("span[name='check']").addClass("check").css('display','inline-block');
                            }
                    });
            }
    });

    var editCheckExercise = (function(url,idheader){
            var info = getcookie(cookiequizzes);
            if (info){
                    var object = $.parseJSON(info);
                    var urls = [];
                    var patt = new RegExp(";;"+url+"\\|"+idheader, 'g');
                    if(!patt.test(object.quiz[0]['urls'])){
                            url = object.quiz[0]['urls'] + ";;" + url + "|" + idheader;
                            $("h2 > a[id='"+idheader+"']").siblings("span[name='check']").addClass("check").css('display','inline-block');
                            setCookieProperty(cookiequizzes,'urls', url);
                    }
            }
    });

    var setFavUrls = (function (urls){
            var patt = new RegExp("[^;|]+\\|.*?(?=$|;;)", 'g');
            var result = urls.match(patt);
            hideMenuOptions();
            if(result && result.length > 0){
                    var pattu = new RegExp('u\\d+','g');
                    var patts = new RegExp('a\\d+','g');
                    var pattae = new RegExp('activitats(?=\.html)|exercicis(?=\.html)','g');
                    var unit = '';
                    var section = '';
                    var info = '';
                    var list = '<div class="menucontent">';
                    var type;
                    var style = '';
                    list += '<ul class="favlist">';
                    result.sort();
                    for (url in result){
                            style = 'favcontent';
                            data = result[url].split("|");
                            type = data[0].match(pattae);
                            if (type){
                                    if(type == 'activitats'){
                                            style = 'favactivity';
                                    }else{
                                            style = 'favexercise';
                                    }
                            }
                            unit = result[url].match(pattu);
                            unit = (unit)?unit + "<span></span>":'';
                            section = result[url].match(patts);
                            section = (section)?section+"<span></span>":''
                            info =  unit + section;
                            list += '<li class="'+style+'"><a href="'+data[0]+'">'+info+' '+data[1]+'</a></li>';
                    }
                    list += '</ul>';
                    list += '</div>';
                    $("#favorites").html(list);
                    $("#favorites").removeClass("hidden");
                    $("#bridge").removeClass('hidden').addClass("tinybridge");
                    $("#bridge").css('top',$("#favorites").css('top'));
            }
    });

    var setNumberHeader = (function (){
            var url = document.location.pathname;
            var patt = new RegExp('/a\\d+(?=/)','g');
            var result = url.match(patt);
            if (result){
                    patt = new RegExp('\\d+','g');
                    result = parseInt(result[0].match(patt));
                    $("article").css("counter-reset","counth1 "+ (result-1));
            }
    });

    var setNumFigRef = (function (){
            $("article .iocfigure > a").each(function(i){
                    $(".figref > a[href=\"#"+$(this).attr("name")+"\"]").append("."+(i+1));
            });
    });

    var setNumTabRef = (function (){
            $("article .ioctable .titletable > a, article .iocaccounting .titletable > a").each(function(i){
                    $(".tabref > a[href=\"#"+$(this).attr("name")+"\"]").append("."+(i+1));
            });
    });

    var hideMenuOptions = (function (){
            $('#toc').addClass('hidden');
            $('#settings').addClass('hidden');
            $("#favorites").addClass('hidden');
            $("#bridge").removeClass("tinybridge").addClass('hidden');
    });

    //Set params into our cookie
    var setcookie = (function(name, value){
            document.cookie = name+'=; expires=Thu, 01-Jan-70 00:00:01 GMT;';
            document.cookie = name+"=" + escape(value) + "; path=/;";
    });

    //get params from our cookie
    var getcookie = (function(name){
            var i,x,y,ARRcookies=document.cookie.split(";");
            for (i=0;i<ARRcookies.length;i++)
            {
              x=ARRcookies[i].substr(0,ARRcookies[i].indexOf("="));
              y=ARRcookies[i].substr(ARRcookies[i].indexOf("=")+1);
              x=x.replace(/^\s+|\s+$/g,"");
              if (x==name){
                    return unescape(y);
              }
            }
    });

    var get_params = (function(reset){
            var info = getcookie(cookiegeneral);
            if (info!=null && info!="" && !reset){
                    //Get and apply stored options
                    var object = $.parseJSON(info);
                    settings(object);
                    if(!ispageSearch()){
                            sidemenu(object);
                            if(ispageIndex()){
                                    indexToc(object.toc[0]['tvisible']==1);
                            }
                            info = getcookie(cookiefavorites);
                            if (info!=null && info!=""){
                                    object = $.parseJSON(info);
                                    setFavButton(object);
                                    setFavHeaders(object);
                            }else{
                                    setcookie(cookiefavorites,defaultbookmarks);
                            }
                            info = getcookie(cookiequizzes);
                            if (info!=null && info!=""){
                                    object = $.parseJSON(info);
                                    setCheckExercises(object);
                            }else{
                                    setcookie(cookiequizzes,defaultbookquizzes);
                            }
                    }
            }else{
                    if(ispageIndex()){
                            indexToc(!cookiesOK());
                    }
                    //Save default options
                    var object = $.parseJSON(defaultsettings);
                    settings(object);
                    sidemenu(object);
                    setcookie(cookiegeneral,defaultsettings);
                    setcookie(cookiefavorites,defaultbookmarks);
                    setcookie(cookiequizzes,defaultbookquizzes);
            }
    });

    var cookiesOK = (function(){
            document.cookie = 'ioc_html_test="test";';
            if (/ioc_html_test/.test(document.cookie)){
                    document.cookie = 'ioc_html_test=; expires=Thu, 01-Jan-70 00:00:01 GMT;';
                    return true;
            }
            return false;
    });


    function basename(path) {
            return path.replace(/\\/g,'/').replace( /.*\//, '' );
    }

    //Header shadow
    $(window).scroll(function () {
            if ($(window).scrollTop() > 30){
                    $("header").addClass("header-shadow");
                    $("#upbutton").show("slow");
            }else{
                    $("header").removeClass("header-shadow");
                    $("#upbutton").hide("slow");
            }
    });

    jQuery(window).resize(function() {
            if (ispageIndex()){
                    indexToc($(".indextoc").css('display') !== 'none');
            }else{
                    if(!ispageSearch()){
                            calpostooltips();
                            setpnpage();
                    }
            }
    });

    //Show and hide list elements
    $(".expander ul").hide();
    $(".expander ul.toc_list").show();
    $(".expander .tocsection .buttonexp").on("click", function() {
        $("#toc .buttonexp").removeClass("tocdown");
        var parent = $(this).closest("li");
        if ($(this).closest("p").siblings("ul").css('display') == 'none'){
            $(this).addClass("tocdown");
        }
        if ($(parent).children("ul").css('display') != 'none'){
            $(parent).children("ul").hide('fast');
        }else{
            $(parent).children("ul").show('fast');
        }
        $(parent).siblings().children().filter('ul').hide('fast');
    });

    $(document).on("click", "a[href^='#']", function(e) {
        e.preventDefault();
        //An anchor
        if ($(this).attr("href") !== '#'){
            var url = $(this).attr("href");
            url = url.replace(/#/,'');
            var offset = $("article a[name='"+url+"']").offset();
            if (offset !== null){
                $(window).scrollTop($(window).scrollTop()+offset.top-60);
            }
        }
    });

    $.expr[':'].parents = function(a,i,m){
        return $(a).parents(m[3]).children('ul').length < 1;
    };

    //TOC
    $(".indextoc").hide();
    $("#button_start").hide();
    $(".headtoc").css('margin-top', -$(".headtoc h1").outerHeight(true)-$(".metainfobc").outerHeight());
    $(".headtoc h1").on("click", function() {
        if ($(".indextoc").css('display') == 'none'){
            var mtop = 0;
            if (parseInt($(".meta img").outerHeight(true)) > $(".meta").outerHeight(true)){
                mtop = parseInt($(".meta img").outerHeight(true));
            }else{
                mtop = parseInt($(".meta").outerHeight(true));
            }
            $(".meta").animate({
                'margin-top': parseInt($(".meta").css('margin-top'),10) == 5 ?
                        -mtop :
                        '5px',
                'visible':'inline'
            },1500);
            $("#header .head").animate({
                    'margin-top': '0px'
            },1500);
            $("#header .headdocument").removeClass('hidden');
            $(".headtoc").animate({
                    'margin-top': -$("#content").outerHeight(true)+$(".headtoc h1").outerHeight()-40
            },1500,function(){
                                    $(".metainfobc").addClass('hidden');
                                    $(".metainfobr").addClass('hidden');
                            }
            );
            $(".headtoc h1 img").slideUp("slow");
            $(".headtoc").css('min-height','100%');
            $(".headtoc").removeClass("headtocdown").addClass("headtocup");
            $(".headtoc h1").removeClass("hover");
            $(".indextoc").show();
            $("#button_start").slideDown("slow");
            setCookieProperty(cookiegeneral,'tvisible', 1);
        }
    });

    $("#button_start").on("click", function() {
            var mtop = 0;
            if (parseInt($(".meta img").outerHeight(true)) > $(".meta").outerHeight(true)){
                    mtop = parseInt($(".meta img").outerHeight(true));
            }else{
                    mtop = parseInt($(".meta").outerHeight(true));
            }
            $(".meta").animate({
                            'margin-top': parseInt($(".meta").css('margin-top'),10) == 5 ?
                                    -mtop :
                                    '5px',
                            'visible':'inline'
            },1500);
            $("#header .head").animate({
                    'margin-top': $(window).height()-$(".headtoc h1").outerHeight(true)-$(".head").outerHeight(true)
            },1500);
            $("#header .headdocument").addClass('hidden');
            $(".metainfobc").removeClass('hidden');
            $(".metainfobr").removeClass('hidden');
            $(".headtoc").animate({
                    'margin-top': -$(".headtoc h1").outerHeight(true)
            },1500, function(){
                                    $(".indextoc").hide();
                                    $(".headtoc").css('min-height','0');
                            }
            );
            $(this).slideUp("slow");
            $(".headtoc").removeClass("headtocup").addClass("headtocdown");
            $(".headtoc h1 img").slideDown("slow");
            $(".headtoc h1").addClass("hover");
            setCookieProperty(cookiegeneral,'tvisible', 0);
    });

    $("#slider-width").slider({
                            min:0,
                            max:5,
                            step:1,
                            range:'min',
                            animate:true,
                            slide: function( event, ui ) {
                               setArticleWidth(ui.value);
              }
    });
    $("#slider-font").slider({
                            min:0,
                            max:4,
                            step:1,
                            range:'min',
                            animate:true,
                            slide: function( event, ui ) {
                               setFontsize(ui.value);
              }
    });

    $("h2,h3,h4").each(function(i){
            if(ispageExercise()){
                    $(this).append('<span name="check"></span>');
            }
            $(this).append('<span class="star" name="star"></span>').children("span").hide();
    });

    $("h1 > a").hover(
            function(){
                    if(showtooltips){
                            showhelp($(this),true,true);
                    }
            },
            function(){
                    if(showtooltips){
                            showhelp($(this),false,true);
                    }
            }
    );

    $("h2 > a,h3 > a,h4 > a").hover(
            function(){
                    if ($(this).siblings("span[name='star']").hasClass("star") && !islocalChrome()){
                            $(this).siblings("span[name='star']").css('display', 'inline-block');
                    }
                    if(showtooltips){
                            showhelp($(this),true,true);
                    }
            },
            function(){
                    if ($(this).siblings("span[name='star']").hasClass("star") && !islocalChrome()){
                            $(this).siblings("span[name='star']").hide();
                    }
                    if(showtooltips){
                            showhelp($(this),false,true);
                    }
            }
    );

    $(document).on("click", "h2 > a,h3 > a,h4 > a", function(){
            if (islocalChrome()){
                    return;
            }
            var id = $(this).attr("id");
            editFavorite(document.location.pathname+"#"+id,id);
            if ($(this).siblings("span[name='star']").hasClass("star")){
                    $(this).siblings("span[name='star']").removeClass().addClass("starmarked");
                    $(this).siblings("span[name='star']").show();
            }else{
                    $(this).siblings("span[name='star']").removeClass().addClass("star");
            }
    });

    $(document).on("click", "article figure img", function(){
            render.previewImage($(this));
    });

    // Afegit zoom per les imatges laterals
    $(document).on("click", ".imgB", function(){
            var jq_node = $(this);
            jq_node.css('cursor', 'zoom-in')
            render.previewImage(jq_node);
    });

    $(document).on("click", ".closepreview",function(){
            $('#back_preview, #preview').addClass('hidden');
    });

    $(document).on("click", "#preview", function(){
            $('#back_preview, #preview').addClass('hidden');
    });

    //Initialize menu and settings params
    get_params();
    setNumberHeader();
    setNumFigRef();
    setNumTabRef();


    if(ispagenoHeader()){
            $("h1,h2,h3").each(function(i){
                    $(this).addClass('nocount');
            });
    }
    if(ispageExercise()){
            $("h2").each(function(i){
                    $(this).addClass('nocount');
            });
    }
    if (islocalChrome()){
            $("#search").addClass("hidden");
            if(ispageIndex() || ispageSearch()){
                    $(".infomessage").removeClass("hidden");
            }else{
                    $("#menu li[name='favorites']").addClass("hidden");
            }
    }
    if(!ispageIndex() && !ispageSearch()){
        calpostooltips();
    }
    return {"editCheckExercise":editCheckExercise,
            "ispageSearch":ispageSearch,
            "postosearchword":postosearchword};
});

define ('doctools',["functions"],function(functions){
/// XXX: make it cross browser
	var DOCUMENTATION_OPTIONS = {
			  URL_ROOT: '',
			  VERSION: '2.7.2',
			  COLLAPSE_MODINDEX: false,
			  FILE_SUFFIX: '.html',
			  HAS_SOURCE: true
			};
/**
 * make the code below compatible with browsers without
 * an installed firebug like debugger
 */
if (!window.console || !console.firebug) {
  var names = ["log", "debug", "info", "warn", "error", "assert", "dir", "dirxml",
      "group", "groupEnd", "time", "timeEnd", "count", "trace", "profile", "profileEnd"];
  window.console = {};
  for (var i = 0; i < names.length; ++i)
    window.console[names[i]] = function() {}
}

/**
 * small helper function to urldecode strings
 */
jQuery.urldecode = function(x) {
  return decodeURIComponent(x).replace(/\+/g, ' ');
}

/**
 * small helper function to urlencode strings
 */
jQuery.urlencode = encodeURIComponent;

/**
 * This function returns the parsed url parameters of the
 * current request. Multiple values per key are supported,
 * it will always return arrays of strings for the value parts.
 */
jQuery.getQueryParameters = function(s) {
  if (typeof s == 'undefined')
    s = document.location.search;
  var parts = s.substr(s.indexOf('?') + 1).split('&');
  var result = {};
  for (var i = 0; i < parts.length; i++) {
    var tmp = parts[i].split('=', 2);
    var key = jQuery.urldecode(tmp[0]);
    var value = jQuery.urldecode(tmp[1]);
    if (key in result)
      result[key].push(value);
    else
      result[key] = [value];
  }
  return result;
}

/**
 * small function to check if an array contains
 * a given item.
 */
jQuery.contains = function(arr, item) {
  for (var i = 0; i < arr.length; i++) {
    if (arr[i] == item)
      return true;
  }
  return false;
}

/**
 * highlight a given string on a jquery object by wrapping it in
 * span elements with the given class name.
 */
jQuery.fn.highlightText = function(text, className) {
  function highlight(node) {
    if (node.nodeType == 3) {
      var val = node.nodeValue;
      var pos = val.toLowerCase().indexOf(text);
      if (pos >= 0 && !jQuery(node.parentNode).hasClass(className)) {
        var span = document.createElement("span");
        span.className = className;
        span.appendChild(document.createTextNode(val.substr(pos, text.length)));
        node.parentNode.insertBefore(span, node.parentNode.insertBefore(
          document.createTextNode(val.substr(pos + text.length)),
          node.nextSibling));
        node.nodeValue = val.substr(0, pos);
      }
    }
    else if (!jQuery(node).is("button, select, textarea")) {
      jQuery.each(node.childNodes, function() {
        highlight(this)
      });
    }
  }
  return this.each(function() {
    highlight(this);
  });
}


var highlightSearchWords = function() {
  var params = jQuery.getQueryParameters();
  var terms = (params.highlight) ? params.highlight[0].split(/\s+/) : [];
  if (terms.length) {
    var body = jQuery('#content');
	var hyphen = false;
	//Trick to solve problem with Chrome and IE
    window.setTimeout(function() {
      jQuery.each(terms, function() {
		if(Hyphenator.doHyphenation){
			Hyphenator.toggleHyphenation();
			hyphen = true;
		}
        body.highlightText(this.toLowerCase(), 'highlight');
		if(hyphen){
			Hyphenator.doHyphenation = false;
			Hyphenator.toggleHyphenation();
		}
        functions.postosearchword();
      });
    }, 10);
  }
}

return highlightSearchWords;
});

define ('quiz',["functions","render"],function(func,render){
	var checkquiz = function(e,func){
	  var target = jQuery(e);
	  var form = target.parents('form');
	  var quiz = form.parent();
	  var div = quiz.parent();
	  var quiztype = form.find("input[name='qtype']").val();
	  var numitems = form.find("input[name='qnum']").val();
	  var solution = form.find("input[name='qsol']").val();
	  var solutions = solution.split(',');
	  var ok = false;
	  
	  if (quiztype == 'vf'){
		var values = [];
	
		form.find('td.ko').removeClass('ko');
		form.find('td.ok').removeClass('ok');
		for (var i=1; i<=numitems; i++){
		  var radio = form.find('input[name="sol_' + i + '"]:checked');
		  var value = radio.val();
		  if(value == solutions[i-1]){
		    radio.parent().addClass('ok');
		    values.push(value);
		  }else{
		    radio.parent().addClass('ko');
		    values.push('');
		  }
		}
	  }else if(quiztype == 'choice'){
		var values = true;
	
		form.find('td.ko').removeClass('ko');
		form.find('td.ok').removeClass('ok');
		for (var i=1; i<=numitems; i++){
		  var checkbox = form.find('input[name="sol_' + i + '"]');
		  
		  if(checkbox.is(':checked')){
		    if(inArray(i, solutions)){
		      checkbox.parent().addClass('ok');
		    }else{
		      checkbox.parent().addClass('ko');
		  	  values = false;
		    }
		  }else{
			  if(!inArray(i, solutions)){
		        checkbox.parent().addClass('ok');
		      }else{
		        checkbox.parent().addClass('ko');
		        values = false;
		      }
		  }
		}
	  }
	  var res = '';
	  
	  if(quiztype !== 'vf'){
		if (values){
		  res = '<p class="ok">@IOCOK@</p>';
		  ok = true;
		  func.editCheckExercise(document.location.pathname,div.prev('h2').children('a').attr('id'));
		}else{
		  res = '<p class="ko">@IOCWRONG@</p>';
		  ok = false;
		}
	  }else{
		  var resp = values.join(',');
		  if (solution == resp){
			res = '<p class="ok">@IOCOK@</p>';
			ok = true;
			func.editCheckExercise(document.location.pathname,div.prev('h2').children('a').attr('id'));
		  }else{
			res = '<p class="ko">@IOCWRONG@</p>';
			ok = false;
		  }
	  }
	  showsolution(target, res, ok);
	}

	var checkquiz2 = function(e){
	  var target = jQuery(e);
	  var form = target.parents('form');
	  var quiz = form.parent();
	  var div = quiz.parent();
	  var solutions = [];
	  var ok = false;
	  
	  form.find('select').each(function(){
		  var select = jQuery(this);
		  select.find('option:selected').each(function(){
			  var option = jQuery(this);
			  if (option.attr('value') == select.attr('name')){
				  select.removeClass('ko');
				  select.addClass('ok');
				  //select.next().find('img').attr('src','../../css/img/ok.png');
				  solutions.push('V');
			  }else{
				  select.removeClass('ok');
				  select.addClass('ko');
				  //select.next().find('img').attr('src','../../css/img/error.png');
				  solutions.push('F');
			  }
		  });
	  });
	  var resp = '';
	  for(i=0, l=solutions.length; i<l; i++){
		  if (solutions[i] == 'F'){
			  resp = '<p class="ko">@IOCWRONG@</p>';
			  ok = false;
			  break;
		  }
	  }
	  if (resp == ''){
		  resp = '<p class="ok">@IOCOK@</p>';
		  ok = true;
		  func.editCheckExercise(document.location.pathname,div.prev('h2').children('a').attr('id'));
	  }
	  showsolution(target, resp, ok);
	} 

	var showsolution = function(target, text, ok){
	  var form = target.parents('form');
	  var quiz = target.parents('div');
	  
	  if (quiz.is('div.quiz')){
		if (ok){
			jQuery(form).parent().children(".quiz_result").removeClass("quiz_ko").addClass("quiz_ok");
		}else{
			jQuery(form).parent().children(".quiz_result").removeClass("quiz_ok").addClass("quiz_ko");
		}
		jQuery(form).parent().children(".quiz_result").hide().fadeOut("slow").html(text).fadeIn("slow");
	  }else{
		alert(text);
	  }
	} 

	var showsol = function(target,render){
		var form = jQuery(target).parents('form');
		if (jQuery(form).children(".solution").css('display') == 'block' ){
			jQuery(form).children(".solution").slideUp("slow");
			jQuery(target).attr('value','@IOCSHOW@');
		}else{
			jQuery(form).children(".solution").slideDown("slow");
			jQuery(target).attr('value','@IOCHIDE@');
			jQuery('article').css('height','auto');
			render.infoTable();
			render.infoFigure();
		}
	}

	var inArray = function(needle, haystack){
		for(var key in haystack)
		{
			needle = needle + '';//to String
		    if(needle === haystack[key])
		    {
		        return true;
		    }
		}
		return false;
	}
	jQuery('.btn_solution').on("click",function (){
		checkquiz(this,func);
	});
	
	jQuery('.btn_solution2').on("click",function (){
		checkquiz2(this);
	});
	
	jQuery('.btn_solution3').on("click",function (){
		showsol(this,render);
	});
	
	return this;
});
define ('searchtools',["doctools"],function(){

	var DOCUMENTATION_OPTIONS = {
			  URL_ROOT: '',
			  VERSION: '2.7.2',
			  COLLAPSE_MODINDEX: false,
			  FILE_SUFFIX: '.html',
			  HAS_SOURCE: true
			};
/**
 * helper function to return a node containing the
 * search summary for a given text. keywords is a list
 * of stemmed words, hlwords is the list of normal, unstemmed
 * words. the first one is used to find the occurance, the
 * latter for highlighting it.
 */

jQuery.makeSearchSummary = function(text, keywords, hlwords) {
  var textLower = text.toLowerCase();
  var start = 0;
  $.each(keywords, function() {
    var i = textLower.indexOf(this.toLowerCase());
    if (i > -1)
      start = i;
  });
  start = Math.max(start - 120, 0);
  var excerpt = ((start > 0) ? '...' : '') +
  $.trim(text.substr(start, 240)) +
  ((start + 240 - text.length) ? '...' : '');
  var rv = $('<div class="summary"></div>').text(excerpt);
  $.each(hlwords, function() {
    rv = rv.highlightText(this, 'highlight');
  });
  return rv;
}


/**
 * Porter Stemmer
 */
var PorterStemmer = function() {

  var step2list = {
    ational: 'ate',
    tional: 'tion',
    enci: 'ence',
    anci: 'ance',
    izer: 'ize',
    bli: 'ble',
    alli: 'al',
    entli: 'ent',
    eli: 'e',
    ousli: 'ous',
    ization: 'ize',
    ation: 'ate',
    ator: 'ate',
    alism: 'al',
    iveness: 'ive',
    fulness: 'ful',
    ousness: 'ous',
    aliti: 'al',
    iviti: 'ive',
    biliti: 'ble',
    logi: 'log'
  };

  var step3list = {
    icate: 'ic',
    ative: '',
    alize: 'al',
    iciti: 'ic',
    ical: 'ic',
    ful: '',
    ness: ''
  };

  var c = "[^aeiou]";          // consonant
  var v = "[aeiouy]";          // vowel
  var C = c + "[^aeiouy]*";    // consonant sequence
  var V = v + "[aeiou]*";      // vowel sequence

  var mgr0 = "^(" + C + ")?" + V + C;                      // [C]VC... is m>0
  var meq1 = "^(" + C + ")?" + V + C + "(" + V + ")?$";    // [C]VC[V] is m=1
  var mgr1 = "^(" + C + ")?" + V + C + V + C;              // [C]VCVC... is m>1
  var s_v   = "^(" + C + ")?" + v;                         // vowel in stem

  this.stemWord = function (w) {
    var stem;
    var suffix;
    var firstch;
    var origword = w;

    if (w.length < 3)
      return w;

    var re;
    var re2;
    var re3;
    var re4;

    firstch = w.substr(0,1);
    if (firstch == "y")
      w = firstch.toUpperCase() + w.substr(1);

    // Step 1a
    re = /^(.+?)(ss|i)es$/;
    re2 = /^(.+?)([^s])s$/;

    if (re.test(w))
      w = w.replace(re,"$1$2");
    else if (re2.test(w))
      w = w.replace(re2,"$1$2");

    // Step 1b
    re = /^(.+?)eed$/;
    re2 = /^(.+?)(ed|ing)$/;
    if (re.test(w)) {
      var fp = re.exec(w);
      re = new RegExp(mgr0);
      if (re.test(fp[1])) {
        re = /.$/;
        w = w.replace(re,"");
      }
    }
    else if (re2.test(w)) {
      var fp = re2.exec(w);
      stem = fp[1];
      re2 = new RegExp(s_v);
      if (re2.test(stem)) {
        w = stem;
        re2 = /(at|bl|iz)$/;
        re3 = new RegExp("([^aeiouylsz])\\1$");
        re4 = new RegExp("^" + C + v + "[^aeiouwxy]$");
        if (re2.test(w))
          w = w + "e";
        else if (re3.test(w)) {
          re = /.$/;
          w = w.replace(re,"");
        }
        else if (re4.test(w))
          w = w + "e";
      }
    }

    // Step 1c
    re = /^(.+?)y$/;
    if (re.test(w)) {
      var fp = re.exec(w);
      stem = fp[1];
      re = new RegExp(s_v);
      if (re.test(stem))
        w = stem + "i";
    }

    // Step 2
    re = /^(.+?)(ational|tional|enci|anci|izer|bli|alli|entli|eli|ousli|ization|ation|ator|alism|iveness|fulness|ousness|aliti|iviti|biliti|logi)$/;
    if (re.test(w)) {
      var fp = re.exec(w);
      stem = fp[1];
      suffix = fp[2];
      re = new RegExp(mgr0);
      if (re.test(stem))
        w = stem + step2list[suffix];
    }

    // Step 3
    re = /^(.+?)(icate|ative|alize|iciti|ical|ful|ness)$/;
    if (re.test(w)) {
      var fp = re.exec(w);
      stem = fp[1];
      suffix = fp[2];
      re = new RegExp(mgr0);
      if (re.test(stem))
        w = stem + step3list[suffix];
    }

    // Step 4
    re = /^(.+?)(al|ance|ence|er|ic|able|ible|ant|ement|ment|ent|ou|ism|ate|iti|ous|ive|ize)$/;
    re2 = /^(.+?)(s|t)(ion)$/;
    if (re.test(w)) {
      var fp = re.exec(w);
      stem = fp[1];
      re = new RegExp(mgr1);
      if (re.test(stem))
        w = stem;
    }
    else if (re2.test(w)) {
      var fp = re2.exec(w);
      stem = fp[1] + fp[2];
      re2 = new RegExp(mgr1);
      if (re2.test(stem))
        w = stem;
    }

    // Step 5
    re = /^(.+?)e$/;
    if (re.test(w)) {
      var fp = re.exec(w);
      stem = fp[1];
      re = new RegExp(mgr1);
      re2 = new RegExp(meq1);
      re3 = new RegExp("^" + C + v + "[^aeiouwxy]$");
      if (re.test(stem) || (re2.test(stem) && !(re3.test(stem))))
        w = stem;
    }
    re = /ll$/;
    re2 = new RegExp(mgr1);
    if (re.test(w) && re2.test(w)) {
      re = /.$/;
      w = w.replace(re,"");
    }

    // and turn initial Y back to y
    if (firstch == "y")
      w = firstch.toLowerCase() + w.substr(1);
    return w;
  }
}


/**
 * Search Module
 */
var Search = {

  _pulse_status : -1,

  init : function() {
      var params = $.getQueryParameters();
      if (params.q) {
          var query = params.q[0];
          $('input[name="q"]')[0].value = query;
          this.performSearch(query);
      }
  },

  stopPulse : function() {
      this._pulse_status = 0;
  },

  startPulse : function() {
    if (this._pulse_status >= 0)
        return;
    function pulse() {
      Search._pulse_status = (Search._pulse_status + 1) % 4;
      var dotString = '';
      for (var i = 0; i < Search._pulse_status; i++)
        dotString += '.';
      Search.dots.text(dotString);
      if (Search._pulse_status > -1)
        window.setTimeout(pulse, 500);
    };
    pulse();
  },

  /**
   * perform a search for something
   */
  performSearch : function(query) {
    // create the required interface elements
    this.out = $('#search-results');
    this.title = $('<h2 class="nocount">' + '@IOCSEARCHING@' + '</h2>').appendTo(this.out);
    this.dots = $('<span></span>').appendTo(this.title);
    this.status = $('<p style="display: none"></p>').appendTo(this.out);
    this.output = $('<ul class="search-result"/>').appendTo(this.out);

    $('#search-progress').text('@PREPARINGSEARCH@');
    this.startPulse();
    this.query(query);
  },

  query : function(query) {
    // stem the searchterms and add them to the
    // correct list
    var stemmer = new PorterStemmer();
    var searchterms = [];
    var excluded = [];
    var hlterms = [];
    var tmp = query.split(/\s+/);
    var object = (tmp.length == 1) ? tmp[0].toLowerCase() : null;
    for (var i = 0; i < tmp.length; i++) {
      // ignore leading/trailing whitespace
      if (tmp[i] == "")
        continue;
      // stem the word
      var word = stemmer.stemWord(tmp[i]).toLowerCase();
      // select the correct list
      if (word[0] == '-') {
        var toAppend = excluded;
        word = word.substr(1);
      }
      else {
        var toAppend = searchterms;
        hlterms.push(tmp[i].toLowerCase());
      }
      // only add if not already in the list
      if (!$.contains(toAppend, word))
        toAppend.push(word);
    };
    var highlightstring = '?highlight=' + $.urlencode(hlterms.join(" "));

//    console.debug('SEARCH: searching for:');
//    console.info('required: ', searchterms);
//    console.info('excluded: ', excluded);

    // prepare search
    $('#search-progress').empty();
    var filenames = new Array("@IOCFILENAMES@")
	getContent(filenames);

	function getContent(filenames){
		var patt1=new RegExp('<article[^>]*>(\n.*)*</article>',"gim");
		var patt2=new RegExp(searchterms.join('|'),"ig");
		var patt3=new RegExp(excluded.join('|'),"ig");
		var patt4=new RegExp('<h1>(?:<a[^>]+>)?(.*?)(?:</a>)?</h1>',"gim");
		file = filenames.pop();
		if (file){
			$.ajax({
			    type: "GET",
			    url: DOCUMENTATION_OPTIONS.URL_ROOT + file,
			    dataType: "html",
			    success: function(data) { 
					var txt = patt1.exec(data);
					var title = patt4.exec(txt);
					if (title && title.length>1){
						title = title[1];
					}
					txt = txt[0].replace(/<[^>]+>|\n|\s{2,}|&#\d+;/gi, ' ');
					if (patt2.test(txt)){
						if (excluded.length > 0 && patt3.test(txt)){
							displayNextItem('', '', filenames.length, '');	
						}else{
							displayNextItem(this.url, txt, filenames.length, title);
						}
					}else{
						displayNextItem('', '', filenames.length, '');
					}
					getContent(filenames);
			    },
			    error: function(x) { alert(x.responseText); }
			});
		}
	}

    function displayNextItem(item, data, pos, title) {
	    var listItem = $('<li style="display:none"></li>');
		if (data){
		    listItem.append($('<a/>').attr(
		      'href', item + highlightstring).html(title));
		    var url = item.replace(/\?\w+=\w+/,'')
		    listItem.append('<div class="urlpath">'+url.replace(/WebContent\//,'...')+'</div>');
		    listItem.append($.makeSearchSummary(data, searchterms, hlterms));
		    Search.output.append(listItem);
		    listItem.slideDown(5);
		}
		if (pos < 1) {
			Search.stopPulse();
			Search.title.text('@IOCSEARCHRESULTS@');

			var elems = jQuery('.search-result').children().length;
			if (elems < 1){
			  Search.status.text('@IOCNOSEARCHRESULTS@');
			}else{
				var text = '@IOCSEARCHFINISHED@';
				if (elems > 1){
					text = text.replace(/\w\//g,'');
				}else{
					text = text.replace(/\/\w+/g,'');
				}
				Search.status.text(text.replace('%s', elems));
			}
			Search.status.fadeIn(500);
		}
    }
  }
}

return Search;
});

define('mediaScript',[],function(){
   var _ehjkhjkl=function(node, data, img, h, w /*, Tooltip*/){
        node.innerHTML ="<img src='" + img 
        + "' alt='"+data+"' " 
        + "height='"+h+"' width='"+w+"' title='"+data+"'/>";
        /*
        new Tooltip({
             connectId: ["@ID_DIV@"],
             label: data
        });
        */
    }
    
    var _dhjdksab=function (node, data, id, h, w){
        node.innerHTML = '<object seamlesstabbing="undefined" '
       + 'class="BrightcoveExperience" id="objvi'+id+'" data="' 
       + data + '&videoID='+id+'" type="application/x-shockwave-flash" '
       + 'height="'+h+'" width="'+w+'">'
       + ' <param value="always" name="allowScriptAccess"/>'
       + ' <param value="true" name="allowFullScreen"/>'
       + ' <param value="false" name="seamlessTabbing"/>'
       + ' <param value="true" name="swliveconnect"/>'
       + ' <param value="window" name="wmode"/>'
       + ' <param value="low" name="quality"/>'
       + ' <param value="#FFFFFF" name="bgcolor"/>'
       + ' <param value="false" name="play"/>'
       + '</object>';
    }
    
    var _shjtyvxi=function (node, img, q, id, h, w){   
        jQuery.ajax({
            url: "//ioc.xtec.cat/secretaria/ioc/materials/videoService.php?type="
                        +q+"&callback=?",
            crossDomain:true,
            dataType: "jsonp",
            success:function(/*PlainObjectData*/ text
                                , /*String*/ status
                                , /*jgXHR*/ jgXHR ){
                if(text.type=='data'){
                     _dhjdksab(node, text.value, id, h, w);
                }else{
                     _ehjkhjkl(node, text.value, img, h, w);
                }
            },
            error: function(/*jgXHR*/ jgXHR, /*String*/ error, /*String*/ ex){
                _ehjkhjkl(node, "ERROR EN CARREGAR EL VÍDEO.", img, h, w /*, Tooltip*/);
            }            
        });
    }
    
    var ret=function (prot, node, img, q, id, h, w){   
        if(prot=="file:"){
            _ehjkhjkl(node, 'Per veure el vídeo cal estar connectat al campus', 
                        img, h, w);
        }else{
            _shjtyvxi(node, img, q, id, h, w);
        }
    }
    return ret;
});





require(["jquery.min","jquery-ui.min","jquery.imagesloaded","render","functions","doctools","quiz","searchtools", "mediaScript"], function(jQuery,jUi,jIl,render,func,Highlight,quiz,Search, mediaScript){
	$("article").imagesLoaded(function(){
		render.infoTable();
		render.infoFigure();
	});
	Highlight();
	if (func.ispageSearch()){
		Search.init();
	}


});

jQuery(document).ready(function () {
	// $(".iocnote, .iocreference, .ioctext, .iocfigurec").toBColumn({debug: true, forceIcons: true});
	$(".iocnote, .iocreference, .ioctext, .iocfigurec").toBColumn();
});
define("main", function(){});

/** @license Hyphenator 3.3.0 - client side hyphenation for webbrowsers
 *  Copyright (C) 2011  Mathias Nater, Zürich (mathias at mnn dot ch)
 *  Project and Source hosted on http://code.google.com/p/hyphenator/
 * 
 *  This JavaScript code is free software: you can redistribute
 *  it and/or modify it under the terms of the GNU Lesser
 *  General Public License (GNU LGPL) as published by the Free Software
 *  Foundation, either version 3 of the License, or (at your option)
 *  any later version.  The code is distributed WITHOUT ANY WARRANTY;
 *  without even the implied warranty of MERCHANTABILITY or FITNESS
 *  FOR A PARTICULAR PURPOSE.  See the GNU GPL for more details.
 *
 *  As additional permission under GNU GPL version 3 section 7, you
 *  may distribute non-source (e.g., minimized or compacted) forms of
 *  that code without the copy of the GNU GPL normally required by
 *  section 4, provided you include this license notice and a URL
 *  through which recipients can access the Corresponding Source.
 */
 
/* 
 *  Comments are jsdoctoolkit formatted. See http://code.google.com/p/jsdoc-toolkit/
 */
 
/* The following comment is for JSLint: */
/*global window, ActiveXObject, unescape */
/*jslint white: true, browser: true, onevar: true, undef: true, nomen: true, eqeqeq: true, regexp: true, sub: true, newcap: true, immed: true, evil: true, eqeqeq: false */


/**
 * @constructor
 * @description Provides all functionality to do hyphenation, except the patterns that are loaded
 * externally.
 * @author Mathias Nater, <a href = "mailto:mathias@mnn.ch">mathias@mnn.ch</a>
 * @version 3.3.0
 * @namespace Holds all methods and properties
 * @example
 * &lt;script src = "Hyphenator.js" type = "text/javascript"&gt;&lt;/script&gt;
 * &lt;script type = "text/javascript"&gt;
 *   Hyphenator.run();
 * &lt;/script&gt;
 */
var Hyphenator = (function (window) {

	var
	/**
	 * @name Hyphenator-supportedLang
	 * @description
	 * A key-value object that stores supported languages.
	 * The key is the bcp47 code of the language and the value
	 * is the (abbreviated) filename of the pattern file.
	 * @type {Object.<string, string>}
	 * @private
	 * @example
	 * Check if language lang is supported:
	 * if (supportedLang.hasOwnProperty(lang))
	 */
	supportedLang = {
		'ca': 'ca.js',
		'en': 'en-us.js'
	},

	/**
	 * @name Hyphenator-languageHint
	 * @description
	 * An automatically generated string to be displayed in a prompt if the language can't be guessed.
	 * The string is generated using the supportedLang-object.
	 * @see Hyphenator-supportedLang
	 * @type {string}
	 * @private
	 * @see Hyphenator-autoSetMainLanguage
	 */

	languageHint = (function () {
		var k, r = '';
		for (k in supportedLang) {
			if (supportedLang.hasOwnProperty(k)) {
				r += k + ', ';
			}
		}
		r = r.substring(0, r.length - 2);
		return r;
	}()),
	
	/**
	 * @name Hyphenator-prompterStrings
	 * @description
	 * A key-value object holding the strings to be displayed if the language can't be guessed
	 * If you add hyphenation patterns change this string.
	 * @type {Object.<string,string>}
	 * @private
	 * @see Hyphenator-autoSetMainLanguage
	 */	
	prompterStrings = {
		'be': 'Мова гэтага сайта не можа быць вызначаны аўтаматычна. Калі ласка пакажыце мову:',
		'cs': 'Jazyk této internetové stránky nebyl automaticky rozpoznán. Určete prosím její jazyk:',
		'da': 'Denne websides sprog kunne ikke bestemmes. Angiv venligst sprog:',
		'de': 'Die Sprache dieser Webseite konnte nicht automatisch bestimmt werden. Bitte Sprache angeben:',
		'en': 'The language of this website could not be determined automatically. Please indicate the main language:',
		'es': 'El idioma del sitio no pudo determinarse autom%E1ticamente. Por favor, indique el idioma principal:',
		'fi': 'Sivun kielt%E4 ei tunnistettu automaattisesti. M%E4%E4rit%E4 sivun p%E4%E4kieli:',
		'fr': 'La langue de ce site n%u2019a pas pu %EAtre d%E9termin%E9e automatiquement. Veuillez indiquer une langue, s.v.p.%A0:',
		'hu': 'A weboldal nyelvét nem sikerült automatikusan megállapítani. Kérem adja meg a nyelvet:',
		'hy': 'Չհաջողվեց հայտնաբերել այս կայքի լեզուն։ Խնդրում ենք նշեք հիմնական լեզուն՝',
		'it': 'Lingua del sito sconosciuta. Indicare una lingua, per favore:',
		'kn': 'ಜಾಲ ತಾಣದ ಭಾಷೆಯನ್ನು ನಿರ್ಧರಿಸಲು ಸಾಧ್ಯವಾಗುತ್ತಿಲ್ಲ. ದಯವಿಟ್ಟು ಮುಖ್ಯ ಭಾಷೆಯನ್ನು ಸೂಚಿಸಿ:',
		'lt': 'Nepavyko automatiškai nustatyti šios svetainės kalbos. Prašome įvesti kalbą:',
		'lv': 'Šīs lapas valodu nevarēja noteikt automātiski. Lūdzu norādiet pamata valodu:',
		'ml': 'ഈ വെ%u0D2C%u0D4D%u200Cസൈറ്റിന്റെ ഭാഷ കണ്ടുപിടിയ്ക്കാ%u0D28%u0D4D%u200D കഴിഞ്ഞില്ല. ഭാഷ ഏതാണെന്നു തിരഞ്ഞെടുക്കുക:',
		'nl': 'De taal van deze website kan niet automatisch worden bepaald. Geef de hoofdtaal op:',
		'no': 'Nettstedets språk kunne ikke finnes automatisk. Vennligst oppgi språk:',
		'pt': 'A língua deste site não pôde ser determinada automaticamente. Por favor indique a língua principal:',
		'ru': 'Язык этого сайта не может быть определен автоматически. Пожалуйста укажите язык:',
		'sl': 'Jezika te spletne strani ni bilo mogoče samodejno določiti. Prosim navedite jezik:',
		'sv': 'Spr%E5ket p%E5 den h%E4r webbplatsen kunde inte avg%F6ras automatiskt. V%E4nligen ange:',
		'tr': 'Bu web sitesinin dili otomatik olarak tespit edilememiştir. Lütfen dökümanın dilini seçiniz%A0:',
		'uk': 'Мова цього веб-сайту не може бути визначена автоматично. Будь ласка, вкажіть головну мову:'
	},
	
	/**
	 * @name Hyphenator-basePath
	 * @description
	 * A string storing the basepath from where Hyphenator.js was loaded.
	 * This is used to load the patternfiles.
	 * The basepath is determined dynamically by searching all script-tags for Hyphenator.js
	 * If the path cannot be determined http://hyphenator.googlecode.com/svn/trunk/ is used as fallback.
	 * @type {string}
	 * @private
	 * @see Hyphenator-loadPatterns
	 */
	basePath = (function () {
		var s = document.getElementsByTagName('script'), i = 0, p, src, t;
		while (!!(t = s[i++])) {
			if (!t.src) {
				continue;
			}
			src = t.src;
			p = src.indexOf('Hyphenator.js');
			if (p !== -1) {
				return src.substring(0, p);
			}
		}
		return 'http://hyphenator.googlecode.com/svn/trunk/';
	}()),

	/**
	 * @name Hyphenator-isLocal
	 * @description
	 * isLocal is true, if Hyphenator is loaded from the same domain, as the webpage, but false, if
	 * it's loaded from an external source (i.e. directly from google.code)
	 */
	isLocal = (function () {
		var re = false;
		if (window.location.href.indexOf(basePath) !== -1) {
			re = true;
		}
		return re;
	}()),
	
	/**
	 * @name Hyphenator-documentLoaded
	 * @description
	 * documentLoaded is true, when the DOM has been loaded. This is set by runOnContentLoaded
	 */
	documentLoaded = false,
	documentCount = 0,
	
	/**
	 * @name Hyphenator-persistentConfig
	 * @description
	 * if persistentConfig is set to true (defaults to false), config options and the state of the 
	 * toggleBox are stored in DOM-storage (according to the storage-setting). So they haven't to be
	 * set for each page.
	 */	
	persistentConfig = false,	

	/**
	 * @name Hyphenator-contextWindow
	 * @description
	 * contextWindow stores the window for the document to be hyphenated.
	 * If there are frames this will change.
	 * So use contextWindow instead of window!
	 */
	contextWindow = window,

	/**
	 * @name Hyphenator-doFrames
	 * @description
	 * switch to control if frames/iframes should be hyphenated, too
	 * defaults to false (frames are a bag of hurt!)
	 */
	doFrames = false,
	
	/**
	 * @name Hyphenator-dontHyphenate
	 * @description
	 * A key-value object containing all html-tags whose content should not be hyphenated
	 * @type {Object.<string,boolean>}
	 * @private
	 * @see Hyphenator-hyphenateElement
	 */
	dontHyphenate = {'script': true, 'code': true, 'pre': true, 'img': true, 'br': true, 'samp': true, 'kbd': true, 'var': true, 'abbr': true, 'acronym': true, 'sub': true, 'sup': true, 'button': true, 'option': true, 'label': true, 'textarea': true, 'input': true},

	/**
	 * @name Hyphenator-enableCache
	 * @description
	 * A variable to set if caching is enabled or not
	 * @type boolean
	 * @default true
	 * @private
	 * @see Hyphenator.config
	 * @see hyphenateWord
	 */
	enableCache = true,

	/**
	 * @name Hyphenator-storageType
	 * @description
	 * A variable to define what html5-DOM-Storage-Method is used ('none', 'local' or 'session')
	 * @type {string}
	 * @default 'none'
	 * @private
	 * @see Hyphenator.config
	 */	
	storageType = 'local',

	/**
	 * @name Hyphenator-storage
	 * @description
	 * An alias to the storage-Method defined in storageType.
	 * Set by Hyphenator.run()
	 * @type {Object|undefined}
	 * @default null
	 * @private
	 * @see Hyphenator.run
	 */	
	storage,
	
	/**
	 * @name Hyphenator-enableReducedPatternSet
	 * @description
	 * A variable to set if storing the used patterns is set
	 * @type boolean
	 * @default false
	 * @private
	 * @see Hyphenator.config
	 * @see hyphenateWord
	 * @see Hyphenator.getRedPatternSet
	 */	
	enableReducedPatternSet = false,
	
	/**
	 * @name Hyphenator-enableRemoteLoading
	 * @description
	 * A variable to set if pattern files should be loaded remotely or not
	 * @type boolean
	 * @default true
	 * @private
	 * @see Hyphenator.config
	 * @see Hyphenator-loadPatterns
	 */
	enableRemoteLoading = true,
	
	/**
	 * @name Hyphenator-displayToggleBox
	 * @description
	 * A variable to set if the togglebox should be displayed or not
	 * @type boolean
	 * @default false
	 * @private
	 * @see Hyphenator.config
	 * @see Hyphenator-toggleBox
	 */
	displayToggleBox = false,
	
	/**
	 * @name Hyphenator-hyphenateClass
	 * @description
	 * A string containing the css-class-name for the hyphenate class
	 * @type {string}
	 * @default 'hyphenate'
	 * @private
	 * @example
	 * &lt;p class = "hyphenate"&gt;Text&lt;/p&gt;
	 * @see Hyphenator.config
	 */
	hyphenateClass = 'hyphenate',

	/**
	 * @name Hyphenator-dontHyphenateClass
	 * @description
	 * A string containing the css-class-name for elements that should not be hyphenated
	 * @type {string}
	 * @default 'donthyphenate'
	 * @private
	 * @example
	 * &lt;p class = "donthyphenate"&gt;Text&lt;/p&gt;
	 * @see Hyphenator.config
	 */
	dontHyphenateClass = 'donthyphenate',
	
	/**
	 * @name Hyphenator-min
	 * @description
	 * A number wich indicates the minimal length of words to hyphenate.
	 * @type {number}
	 * @default 6
	 * @private
	 * @see Hyphenator.config
	 */	
	min = 6,
	
	/**
	 * @name Hyphenator-orphanControl
	 * @description
	 * Control how the last words of a line are handled:
	 * level 1 (default): last word is hyphenated
	 * level 2: last word is not hyphenated
	 * level 3: last word is not hyphenated and last space is non breaking
	 * @type {number}
	 * @default 1
	 * @private
	 */
	orphanControl = 1,
	
	/**
	 * @name Hyphenator-isBookmarklet
	 * @description
	 * Indicates if Hyphanetor runs as bookmarklet or not.
	 * @type boolean
	 * @default false
	 * @private
	 */	
	isBookmarklet = (function () {
		var loc = null, re = false, jsArray = document.getElementsByTagName('script'), i, l;
		for (i = 0, l = jsArray.length; i < l; i++) {
			if (!!jsArray[i].getAttribute('src')) {
				loc = jsArray[i].getAttribute('src');
			}
			if (!loc) {
				continue;
			} else if (loc.indexOf('Hyphenator.js?bm=true') !== -1) {
				re = true;
			}
		}
		return re;
	}()),
	
	/**
	 * @name Hyphenator-mainLanguage
	 * @description
	 * The general language of the document. In contrast to {@link Hyphenator-defaultLanguage},
	 * mainLanguage is defined by the client (i.e. by the html or by a prompt).
	 * @type {string|null}
	 * @private
	 * @see Hyphenator-autoSetMainLanguage
	 */	
	mainLanguage = null,

	/**
	 * @name Hyphenator-defaultLanguage
	 * @description
	 * The language defined by the developper. This language setting is defined by a config option.
	 * It is overwritten by any html-lang-attribute and only taken in count, when no such attribute can
	 * be found (i.e. just before the prompt).
	 * @type {string|null}
	 * @private
	 * @see Hyphenator-autoSetMainLanguage
	 */	
	defaultLanguage = '',

	/**
	 * @name Hyphenator-elements
	 * @description
	 * An array holding all elements that have to be hyphenated. This var is filled by
	 * {@link Hyphenator-gatherDocumentInfos}
	 * @type {Array}
	 * @private
	 */	
	elements = [],
	
	/**
	 * @name Hyphenator-exceptions
	 * @description
	 * An object containing exceptions as comma separated strings for each language.
	 * When the language-objects are loaded, their exceptions are processed, copied here and then deleted.
	 * @see Hyphenator-prepareLanguagesObj
	 * @type {Object}
	 * @private
	 */	
	exceptions = {},
	
	countObjProps = function (obj) {
		var k, l = 0;
		for (k in obj) {
			if (obj.hasOwnProperty(k)) {
				l++;
			}
		}
		return l;
	},
	/**
	 * @name Hyphenator-docLanguages
	 * @description
	 * An object holding all languages used in the document. This is filled by
	 * {@link Hyphenator-gatherDocumentInfos}
	 * @type {Object}
	 * @private
	 */	
	docLanguages = {},


	/**
	 * @name Hyphenator-state
	 * @description
	 * A number that inidcates the current state of the script
	 * 0: not initialized
	 * 1: loading patterns
	 * 2: ready
	 * 3: hyphenation done
	 * 4: hyphenation removed
	 * @type {number}
	 * @private
	 */	
	state = 0,

	/**
	 * @name Hyphenator-url
	 * @description
	 * A string containing a RegularExpression to match URL's
	 * @type {string}
	 * @private
	 */	
	url = '(\\w*:\/\/)?((\\w*:)?(\\w*)@)?((([\\d]{1,3}\\.){3}([\\d]{1,3}))|((www\\.|[a-zA-Z]\\.)?[a-zA-Z0-9\\-\\.]+\\.([a-z]{2,4})))(:\\d*)?(\/[\\w#!:\\.?\\+=&%@!\\-]*)*',
	//      protocoll     usr     pwd                    ip               or                          host                 tld        port               path
	/**
	 * @name Hyphenator-mail
	 * @description
	 * A string containing a RegularExpression to match mail-adresses
	 * @type {string}
	 * @private
	 */	
	mail = '[\\w-\\.]+@[\\w\\.]+',

	/**
	 * @name Hyphenator-urlRE
	 * @description
	 * A RegularExpressions-Object for url- and mail adress matching
	 * @type {RegExp}
	 * @private
	 */		
	urlOrMailRE = new RegExp('(' + url + ')|(' + mail + ')', 'i'),

	/**
	 * @name Hyphenator-zeroWidthSpace
	 * @description
	 * A string that holds a char.
	 * Depending on the browser, this is the zero with space or an empty string.
	 * zeroWidthSpace is used to break URLs
	 * @type {string}
	 * @private
	 */		
	zeroWidthSpace = (function () {
		var zws, ua = navigator.userAgent.toLowerCase();
		zws = String.fromCharCode(8203); //Unicode zero width space
		if (ua.indexOf('msie 6') !== -1) {
			zws = ''; //IE6 doesn't support zws
		}
		if (ua.indexOf('opera') !== -1 && ua.indexOf('version/10.00') !== -1) {
			zws = ''; //opera 10 on XP doesn't support zws
		}
		return zws;
	}()),
	
	/**
	 * @name Hyphenator-createElem
	 * @description
	 * A function alias to document.createElementNS or document.createElement
	 * @type {function(string, Object)}
	 * @private
	 */		
	createElem = function (tagname, context) {
		context = context || contextWindow;
		if (document.createElementNS) {
			return context.document.createElementNS('http://www.w3.org/1999/xhtml', tagname);
		} else if (document.createElement) {
			return context.document.createElement(tagname);
		}
	},
	
	/**
	 * @name Hyphenator-onHyphenationDone
	 * @description
	 * A method to be called, when the last element has been hyphenated or the hyphenation has been
	 * removed from the last element.
	 * @see Hyphenator.config
	 * @type {function()}
	 * @private
	 */		
	onHyphenationDone = function () {},

	/**
	 * @name Hyphenator-onError
	 * @description
	 * A function that can be called upon an error.
	 * @see Hyphenator.config
	 * @type {function(Object)}
	 * @private
	 */		
	onError = function (e) {
		window.alert("Hyphenator.js says:\n\nAn Error ocurred:\n" + e.message);
	},

	/**
	 * @name Hyphenator-selectorFunction
	 * @description
	 * A function that has to return a HTMLNodeList of Elements to be hyphenated.
	 * By default it uses the classname ('hyphenate') to select the elements.
	 * @see Hyphenator.config
	 * @type {function()}
	 * @private
	 */		
	selectorFunction = function () {
		var tmp, el = [], i, l;
		if (document.getElementsByClassName) {
			el = contextWindow.document.getElementsByClassName(hyphenateClass);
		} else {
			tmp = contextWindow.document.getElementsByTagName('*');
			l = tmp.length;
			for (i = 0; i < l; i++)
			{
				if (tmp[i].className.indexOf(hyphenateClass) !== -1 && tmp[i].className.indexOf(dontHyphenateClass) === -1) {
					el.push(tmp[i]);
				}
			}
		}
		return el;
	},

	/**
	 * @name Hyphenator-intermediateState
	 * @description
	 * The value of style.visibility of the text while it is hyphenated.
	 * @see Hyphenator.config
	 * @type {string}
	 * @private
	 */		
	intermediateState = 'hidden',
	
	/**
	 * @name Hyphenator-hyphen
	 * @description
	 * A string containing the character for in-word-hyphenation
	 * @type {string}
	 * @default the soft hyphen
	 * @private
	 * @see Hyphenator.config
	 */
	hyphen = String.fromCharCode(173),
	
	/**
	 * @name Hyphenator-urlhyphen
	 * @description
	 * A string containing the character for url/mail-hyphenation
	 * @type {string}
	 * @default the zero width space
	 * @private
	 * @see Hyphenator.config
	 * @see Hyphenator-zeroWidthSpace
	 */
	urlhyphen = zeroWidthSpace,

	/**
	 * @name Hyphenator-safeCopy
	 * @description
	 * Defines wether work-around for copy issues is active or not
	 * Not supported by Opera (no onCopy handler)
	 * @type boolean
	 * @default true
	 * @private
	 * @see Hyphenator.config
	 * @see Hyphenator-registerOnCopy
	 */
	safeCopy = true,
	
	/**
	 * @name Hyphenator-Expando
	 * @description
	 * This custom object stores data for elements: storing data directly in elements
	 * (DomElement.customData = foobar;) isn't a good idea. It would lead to conflicts
	 * in form elements, when the form has a child with name="foobar". Therefore, this
	 * solution follows the approach of jQuery: the data is stored in an object and
	 * referenced by a unique attribute of the element. The attribute has a name that 
	 * is built by the prefix "HyphenatorExpando_" and a random number, so if the very
	 * very rare case occurs, that there's already an attribute with the same name, a
	 * simple reload is enough to make it function.
	 * @private
	 */		
	Expando = (function () {
		var container = {},
			name = "HyphenatorExpando_" + Math.random(),
			uuid = 0;
		return {
			getDataForElem : function (elem) {
				return container[elem[name].id];
			},
			setDataForElem : function (elem, data) {
				var id;
				if (elem[name] && elem[name].id !== '') {
					id = elem[name].id;
				} else {
					id = uuid++;
					elem[name] = {'id': id}; //object needed, otherways it is reflected in HTML in IE
				}
				container[id] = data;
			},
			appendDataForElem : function (elem, data) {
				var k;
				for (k in data) {
					if (data.hasOwnProperty(k)) {
						container[elem[name].id][k] = data[k];
					}
				}
			},
			delDataOfElem : function (elem) {
				delete container[elem[name]];
			}
		};
	}()),
		
	/*
	 * runOnContentLoaded is based od jQuery.bindReady()
	 * see
	 * jQuery JavaScript Library v1.3.2
	 * http://jquery.com/
	 *
	 * Copyright (c) 2009 John Resig
	 * Dual licensed under the MIT and GPL licenses.
	 * http://docs.jquery.com/License
	 *
	 * Date: 2009-02-19 17:34:21 -0500 (Thu, 19 Feb 2009)
	 * Revision: 6246
	 */
	/**
	 * @name Hyphenator-runOnContentLoaded
	 * @description
	 * A crossbrowser solution for the DOMContentLoaded-Event based on jQuery
	 * <a href = "http://jquery.com/</a>
	 * I added some functionality: e.g. support for frames and iframes…
	 * @param {Object} w the window-object
	 * @param {function()} f the function to call onDOMContentLoaded
	 * @private
	 */
	runOnContentLoaded = function (w, f) {
		var DOMContentLoaded = function () {}, toplevel, hyphRunForThis = {};
		if (documentLoaded && !hyphRunForThis[w.location.href]) {
			f();
			hyphRunForThis[w.location.href] = true;
			return;
		}
		function init(context) {
			contextWindow = context || window;
			if (!hyphRunForThis[contextWindow.location.href] && (!documentLoaded || contextWindow != window.parent)) {
				documentLoaded = true;
				f();
				hyphRunForThis[contextWindow.location.href] = true;
			}
		}
		
		function doScrollCheck() {
			try {
				// If IE is used, use the trick by Diego Perini
				// http://javascript.nwbox.com/IEContentLoaded/
				document.documentElement.doScroll("left");
			} catch (error) {
				setTimeout(doScrollCheck, 1);
				return;
			}
		
			// and execute any waiting functions
			init(window);
		}

		function doOnLoad() {
			var i, haveAccess, fl = window.frames.length;
			if (doFrames && fl > 0) {
				for (i = 0; i < fl; i++) {
					haveAccess = undefined;
					//try catch isn't enough for webkit
					try {
						//opera throws only on document.toString-access
						haveAccess = window.frames[i].document.toString();
					} catch (e) {
						haveAccess = undefined;
					}
					if (!!haveAccess) {
						init(window.frames[i]);
					}
				}
				contextWindow = window;
				f();
				hyphRunForThis[window.location.href] = true;
			} else {
				init(window);
			}
		}
		
		// Cleanup functions for the document ready method
		if (document.addEventListener) {
			DOMContentLoaded = function () {
				document.removeEventListener("DOMContentLoaded", DOMContentLoaded, false);
				if (doFrames && window.frames.length > 0) {
					//we are in a frameset, so do nothing but wait for onload to fire
					return;
				} else {
					init(window);
				}
			};
		
		} else if (document.attachEvent) {
			DOMContentLoaded = function () {
				// Make sure body exists, at least, in case IE gets a little overzealous (ticket #5443).
				if (document.readyState === "complete") {
					document.detachEvent("onreadystatechange", DOMContentLoaded);
					if (doFrames && window.frames.length > 0) {
						//we are in a frameset, so do nothing but wait for onload to fire
						return;
					} else {
						init(window);
					}
				}
			};
		}

		// Mozilla, Opera and webkit nightlies currently support this event
		if (document.addEventListener) {
			// Use the handy event callback
			document.addEventListener("DOMContentLoaded", DOMContentLoaded, false);
			
			// A fallback to window.onload, that will always work
			window.addEventListener("load", doOnLoad, false);

		// If IE event model is used
		} else if (document.attachEvent) {
			// ensure firing before onload,
			// maybe late but safe also for iframes
			document.attachEvent("onreadystatechange", DOMContentLoaded);
			
			// A fallback to window.onload, that will always work
			window.attachEvent("onload", doOnLoad);

			// If IE and not a frame
			// continually check to see if the document is ready
			toplevel = false;
			try {
				toplevel = window.frameElement === null;
			} catch (e) {}

			if (document.documentElement.doScroll && toplevel) {
				doScrollCheck();
			}
		}

	},



	/**
	 * @name Hyphenator-getLang
	 * @description
	 * Gets the language of an element. If no language is set, it may use the {@link Hyphenator-mainLanguage}.
	 * @param {Object} el The first parameter is an DOM-Element-Object
	 * @param {boolean} fallback The second parameter is a boolean to tell if the function should return the {@link Hyphenator-mainLanguage}
	 * if there's no language found for the element.
	 * @private
	 */
	getLang = function (el, fallback) {
		if (!!el.getAttribute('lang')) {
			return el.getAttribute('lang').toLowerCase();
		}
		// The following doesn't work in IE due to a bug when getAttribute('xml:lang') in a table
		/*if (!!el.getAttribute('xml:lang')) {
			return el.getAttribute('xml:lang').substring(0, 2);
		}*/
		//instead, we have to do this (thanks to borgzor):
		try {
			if (!!el.getAttribute('xml:lang')) {
				return el.getAttribute('xml:lang').toLowerCase();
			}
		} catch (ex) {}
		if (el.tagName !== 'HTML') {
			return getLang(el.parentNode, true);
		}
		if (fallback) {
			return mainLanguage;
		}
		return null;
	},
	
	/**
	 * @name Hyphenator-autoSetMainLanguage
	 * @description
	 * Retrieves the language of the document from the DOM.
	 * The function looks in the following places:
	 * <ul>
	 * <li>lang-attribute in the html-tag</li>
	 * <li>&lt;meta http-equiv = "content-language" content = "xy" /&gt;</li>
	 * <li>&lt;meta name = "DC.Language" content = "xy" /&gt;</li>
	 * <li>&lt;meta name = "language" content = "xy" /&gt;</li>
	 * </li>
	 * If nothing can be found a prompt using {@link Hyphenator-languageHint} and {@link Hyphenator-prompterStrings} is displayed.
	 * If the retrieved language is in the object {@link Hyphenator-supportedLang} it is copied to {@link Hyphenator-mainLanguage}
	 * @private
	 */		
	autoSetMainLanguage = function (w) {
		w = w || contextWindow;
		var el = w.document.getElementsByTagName('html')[0],
			m = w.document.getElementsByTagName('meta'),
			i, text, e, ul;
		mainLanguage = getLang(el, false);
		if (!mainLanguage) {
			for (i = 0; i < m.length; i++) {
				//<meta http-equiv = "content-language" content="xy">	
				if (!!m[i].getAttribute('http-equiv') && (m[i].getAttribute('http-equiv').toLowerCase() === 'content-language')) {
					mainLanguage = m[i].getAttribute('content').toLowerCase();
				}
				//<meta name = "DC.Language" content="xy">
				if (!!m[i].getAttribute('name') && (m[i].getAttribute('name').toLowerCase() === 'dc.language')) {
					mainLanguage = m[i].getAttribute('content').toLowerCase();
				}			
				//<meta name = "language" content = "xy">
				if (!!m[i].getAttribute('name') && (m[i].getAttribute('name').toLowerCase() === 'language')) {
					mainLanguage = m[i].getAttribute('content').toLowerCase();
				}
			}
		}
		//get lang for frame from enclosing document
		if (!mainLanguage && doFrames && contextWindow != window.parent) {
			autoSetMainLanguage(window.parent);
		}
		//fallback to defaultLang if set
		if (!mainLanguage && defaultLanguage !== '') {
			mainLanguage = defaultLanguage;
		}
		//ask user for lang
		if (!mainLanguage) {
			text = '';
			ul = navigator.language ? navigator.language : navigator.userLanguage;
			ul = ul.substring(0, 2);
			if (prompterStrings.hasOwnProperty(ul)) {
				text = prompterStrings[ul];
			} else {
				text = prompterStrings.en;
			}
			text += ' (ISO 639-1)\n\n' + languageHint;
			mainLanguage = window.prompt(unescape(text), ul).toLowerCase();
		}
		if (!supportedLang.hasOwnProperty(mainLanguage)) {
			if (supportedLang.hasOwnProperty(mainLanguage.split('-')[0])) { //try subtag
				mainLanguage = mainLanguage.split('-')[0];
			} else {
				e = new Error('The language "' + mainLanguage + '" is not yet supported.');
				throw e;
			}
		}
	},
    
	/**
	 * @name Hyphenator-gatherDocumentInfos
	 * @description
	 * This method runs through the DOM and executes the process()-function on:
	 * - every node returned by the {@link Hyphenator-selectorFunction}.
	 * The process()-function copies the element to the elements-variable, sets its visibility
	 * to intermediateState, retrieves its language and recursivly descends the DOM-tree until
	 * the child-Nodes aren't of type 1
	 * @private
	 */		
	gatherDocumentInfos = function () {
		var elToProcess, tmp, i = 0,
		process = function (el, hide, lang) {
			var n, i = 0, hyphenatorSettings = {};
			if (hide && intermediateState === 'hidden') {
				if (!!el.getAttribute('style')) {
					hyphenatorSettings.hasOwnStyle = true;
				} else {
					hyphenatorSettings.hasOwnStyle = false;					
				}
				hyphenatorSettings.isHidden = true;
				el.style.visibility = 'hidden';
			}
			if (el.lang && typeof(el.lang) === 'string') {
				hyphenatorSettings.language = el.lang.toLowerCase(); //copy attribute-lang to internal lang
			} else if (lang) {
				hyphenatorSettings.language = lang.toLowerCase();
			} else {
				hyphenatorSettings.language = getLang(el, true);
			}
			lang = hyphenatorSettings.language;
			if (supportedLang[lang]) {
				docLanguages[lang] = true;
			} else {
				if (supportedLang.hasOwnProperty(lang.split('-')[0])) { //try subtag
					lang = lang.split('-')[0];
					hyphenatorSettings.language = lang;
				} else if (!isBookmarklet) {
					onError(new Error('Language ' + lang + ' is not yet supported.'));
				}
			}
			Expando.setDataForElem(el, hyphenatorSettings);			
			
			elements.push(el);
			while (!!(n = el.childNodes[i++])) {
				if (n.nodeType === 1 && !dontHyphenate[n.nodeName.toLowerCase()] &&
					n.className.indexOf(dontHyphenateClass) === -1 && !(n in elToProcess)) {
					process(n, false, lang);
				}
			}
		};
		if (isBookmarklet) {
			elToProcess = contextWindow.document.getElementsByTagName('body')[0];
			process(elToProcess, false, mainLanguage);
		} else {
			elToProcess = selectorFunction();
			while (!!(tmp = elToProcess[i++]))
			{
				process(tmp, true, '');
			}			
		}
		if (!Hyphenator.languages.hasOwnProperty(mainLanguage)) {
			docLanguages[mainLanguage] = true;
		} else if (!Hyphenator.languages[mainLanguage].prepared) {
			docLanguages[mainLanguage] = true;
		}
		if (elements.length > 0) {
			Expando.appendDataForElem(elements[elements.length - 1], {isLast : true});
		}
	},
		 
	/**
	 * @name Hyphenator-convertPatterns
	 * @description
	 * Converts the patterns from string '_a6' to object '_a':'_a6'.
	 * The result is stored in the {@link Hyphenator-patterns}-object.
	 * @private
	 * @param {string} lang the language whose patterns shall be converted
	 */		
	convertPatterns = function (lang) {
		var plen, anfang, ende, pats, pat, key, tmp = {};
		pats = Hyphenator.languages[lang].patterns;
		for (plen in pats) {
			if (pats.hasOwnProperty(plen)) {
				plen = parseInt(plen, 10);
				anfang = 0;
				ende = plen;
				while (!!(pat = pats[plen].substring(anfang, ende))) {
					key = pat.replace(/\d/g, '');
					tmp[key] = pat;
					anfang = ende;
					ende += plen;
				}
			}
		}
		Hyphenator.languages[lang].patterns = tmp;
		Hyphenator.languages[lang].patternsConverted = true;
	},

	/**
	 * @name Hyphenator-convertExceptionsToObject
	 * @description
	 * Converts a list of comma seprated exceptions to an object:
	 * 'Fortran,Hy-phen-a-tion' -> {'Fortran':'Fortran','Hyphenation':'Hy-phen-a-tion'}
	 * @private
	 * @param {string} exc a comma separated string of exceptions (without spaces)
	 */		
	convertExceptionsToObject = function (exc) {
		var w = exc.split(', '),
			r = {},
			i, l, key;
		for (i = 0, l = w.length; i < l; i++) {
			key = w[i].replace(/-/g, '');
			if (!r.hasOwnProperty(key)) {
				r[key] = w[i];
			}
		}
		return r;
	},
	
	/**
	 * @name Hyphenator-loadPatterns
	 * @description
	 * Adds a &lt;script&gt;-Tag to the DOM to load an externeal .js-file containing patterns and settings for the given language.
	 * If the given language is not in the {@link Hyphenator-supportedLang}-Object it returns.
	 * One may ask why we are not using AJAX to load the patterns. The XMLHttpRequest-Object 
	 * has a same-origin-policy. This makes the isBookmarklet-functionality impossible.
	 * @param {string} lang The language to load the patterns for
	 * @private
	 * @see Hyphenator-basePath
	 */
	loadPatterns = function (lang) {
		var url, xhr, head, script;
		if (supportedLang[lang] && !Hyphenator.languages[lang]) {
	        url = basePath + 'patterns/' + supportedLang[lang];
		} else {
			return;
		}
		if (isLocal && !isBookmarklet) {
			//check if 'url' is available:
			xhr = null;
			if (typeof XMLHttpRequest !== 'undefined') {
				xhr = new XMLHttpRequest();
			}
			if (!xhr) {
				try {
					xhr  = new ActiveXObject("Msxml2.XMLHTTP");
				} catch (e) {
					xhr  = null;
				}
			}
			if (xhr) {
				xhr.open('HEAD', url, false);
				xhr.setRequestHeader('Cache-Control', 'no-cache');
				xhr.send(null);
				if (xhr.status === 404) {
					onError(new Error('Could not load\n' + url));
					delete docLanguages[lang];
					return;
				}
			}
		}
		if (createElem) {
			head = window.document.getElementsByTagName('head').item(0);
			script = createElem('script', window);
			script.src = url;
			script.type = 'text/javascript';
			head.appendChild(script);
		}
	},
	
	/**
	 * @name Hyphenator-prepareLanguagesObj
	 * @description
	 * Adds a cache to each language and converts the exceptions-list to an object.
	 * If storage is active the object is stored there.
	 * @private
	 * @param {string} lang the language ob the lang-obj
	 */		
	prepareLanguagesObj = function (lang) {
		var lo = Hyphenator.languages[lang], wrd;
		if (!lo.prepared) {	
			if (enableCache) {
				lo.cache = {};
				//Export
				lo['cache'] = lo.cache;
			}
			if (enableReducedPatternSet) {
				lo.redPatSet = {};
			}
			//add exceptions from the pattern file to the local 'exceptions'-obj
			if (lo.hasOwnProperty('exceptions')) {
				Hyphenator.addExceptions(lang, lo.exceptions);
				delete lo.exceptions;
			}
			//copy global exceptions to the language specific exceptions
			if (exceptions.hasOwnProperty('global')) {
				if (exceptions.hasOwnProperty(lang)) {
					exceptions[lang] += ', ' + exceptions.global;
				} else {
					exceptions[lang] = exceptions.global;
				}
			}
			//move exceptions from the the local 'exceptions'-obj to the 'language'-object
			if (exceptions.hasOwnProperty(lang)) {
				lo.exceptions = convertExceptionsToObject(exceptions[lang]);
				delete exceptions[lang];
			} else {
				lo.exceptions = {};
			}
			convertPatterns(lang);
			wrd = '[\\w' + lo.specialChars + '@' + String.fromCharCode(173) + String.fromCharCode(8204) + '-]{' + min + ',}';
			lo.genRegExp = new RegExp('(' + url + ')|(' + mail + ')|(' + wrd + ')', 'gi');
			lo.prepared = true;
		}
		if (!!storage) {
			try {
				storage.setItem('Hyphenator_' + lang, window.JSON.stringify(lo));
			} catch (e) {
				//onError(e);
			}
		}
		
	},
	
	/**
	 * @name Hyphenator-prepare
	 * @description
	 * This funtion prepares the Hyphenator-Object: If RemoteLoading is turned off, it assumes
	 * that the patternfiles are loaded, all conversions are made and the callback is called.
	 * If storage is active the object is retrieved there.
	 * If RemoteLoading is on (default), it loads the pattern files and waits until they are loaded,
	 * by repeatedly checking Hyphenator.languages. If a patterfile is loaded the patterns are
	 * converted to their object style and the lang-object extended.
	 * Finally the callback is called.
	 * @param {function()} callback to call, when all patterns are loaded
	 * @private
	 */
	prepare = function (callback) {
		var lang, interval, tmp1, tmp2;
		if (!enableRemoteLoading) {
			for (lang in Hyphenator.languages) {
				if (Hyphenator.languages.hasOwnProperty(lang)) {
					prepareLanguagesObj(lang);
				}
			}
			state = 2;
			callback();
			return;
		}
		// get all languages that are used and preload the patterns
		state = 1;
		for (lang in docLanguages) {
			if (docLanguages.hasOwnProperty(lang)) {
				if (!!storage && storage.getItem('Hyphenator_' + lang)) {
					Hyphenator.languages[lang] = window.JSON.parse(storage.getItem('Hyphenator_' + lang));
					if (exceptions.hasOwnProperty('global')) {
						tmp1 = convertExceptionsToObject(exceptions.global);
						for (tmp2 in tmp1) {
							if (tmp1.hasOwnProperty(tmp2)) {
								Hyphenator.languages[lang].exceptions[tmp2] = tmp1[tmp2];
							}
						}
					}
					//Replace exceptions since they may have been changed:
					if (exceptions.hasOwnProperty(lang)) {
						tmp1 = convertExceptionsToObject(exceptions[lang]);
						for (tmp2 in tmp1) {
							if (tmp1.hasOwnProperty(tmp2)) {
								Hyphenator.languages[lang].exceptions[tmp2] = tmp1[tmp2];
							}
						}
						delete exceptions[lang];
					}
					//Replace genRegExp since it may have been changed:
					tmp1 = '[\\w' + Hyphenator.languages[lang].specialChars + '@' + String.fromCharCode(173) + String.fromCharCode(8204) + '-]{' + min + ',}';
					Hyphenator.languages[lang].genRegExp = new RegExp('(' + url + ')|(' + mail + ')|(' + tmp1 + ')', 'gi');
					
					delete docLanguages[lang];
					continue;
				} else {
					loadPatterns(lang);
				}
			}
		}
		// if all patterns are loaded from storage: callback
		if (countObjProps(docLanguages) === 0) {
			state = 2;
			callback();
			return;
		}
		// else async wait until patterns are loaded, then callback
		interval = window.setInterval(function () {
			var finishedLoading = true, lang;
			for (lang in docLanguages) {
				if (docLanguages.hasOwnProperty(lang)) {
					finishedLoading = false;
					if (!!Hyphenator.languages[lang]) {
						delete docLanguages[lang];
						//do conversion while other patterns are loading:
						prepareLanguagesObj(lang);
					}
				}
			}
			if (finishedLoading) {
				//console.log('callig callback for ' + contextWindow.location.href);
				window.clearInterval(interval);
				state = 2;
				callback();
			}
		}, 100);
	},

	/**
	 * @name Hyphenator-switchToggleBox
	 * @description
	 * Creates or hides the toggleBox: a small button to turn off/on hyphenation on a page.
	 * @see Hyphenator.config
	 * @private
	 */		
	toggleBox = function () {
		var myBox, bdy, myIdAttribute, myTextNode, myClassAttribute,
		text = (Hyphenator.doHyphenation ? 'Hy-phen-a-tion' : 'Hyphenation');
		if (!!(myBox = contextWindow.document.getElementById('HyphenatorToggleBox'))) {
			myBox.firstChild.data = text;
		} else {
			bdy = contextWindow.document.getElementsByTagName('body')[0];
			myBox = createElem('div', contextWindow);
			myIdAttribute = contextWindow.document.createAttribute('id');
			myIdAttribute.nodeValue = 'HyphenatorToggleBox';
			myClassAttribute = contextWindow.document.createAttribute('class');
			myClassAttribute.nodeValue = dontHyphenateClass;
			myTextNode = contextWindow.document.createTextNode(text);
			myBox.appendChild(myTextNode);
			myBox.setAttributeNode(myIdAttribute);
			myBox.setAttributeNode(myClassAttribute);
			myBox.onclick =  Hyphenator.toggleHyphenation;
			myBox.style.position = 'absolute';
			myBox.style.top = '0px';
			myBox.style.right = '0px';
			myBox.style.margin = '0';
			myBox.style.backgroundColor = '#AAAAAA';
			myBox.style.color = '#FFFFFF';
			myBox.style.font = '6pt Arial';
			myBox.style.letterSpacing = '0.2em';
			myBox.style.padding = '3px';
			myBox.style.cursor = 'pointer';
			myBox.style.WebkitBorderBottomLeftRadius = '4px';
			myBox.style.MozBorderRadiusBottomleft = '4px';
			if (bdy){//Avoid errors related with problems when is no load object
				bdy.appendChild(myBox);
			}
		}
	},

	/**
	 * @name Hyphenator-hyphenateWord
	 * @description
	 * This function is the heart of Hyphenator.js. It returns a hyphenated word.
	 *
	 * If there's already a {@link Hyphenator-hypen} in the word, the word is returned as it is.
	 * If the word is in the exceptions list or in the cache, it is retrieved from it.
	 * If there's a '-' put a zeroWidthSpace after the '-' and hyphenate the parts.
	 * @param {string} lang The language of the word
	 * @param {string} word The word
	 * @returns string The hyphenated word
	 * @public
	 */	
	hyphenateWord = function (lang, word) {
		var lo = Hyphenator.languages[lang],
			parts, i, l, w, wl, s, hypos, p, maxwins, win, pat = false, patk, c, t, n, numb3rs, inserted, hyphenatedword, val, subst, ZWNJpos = [];
		if (word === '') {
			return '';
		}
		if (word.indexOf(hyphen) !== -1) {
			//word already contains shy; -> leave at it is!
			return word;
		}
		if (enableCache && lo.cache.hasOwnProperty(word)) { //the word is in the cache
			return lo.cache[word];
		}
		if (lo.exceptions.hasOwnProperty(word)) { //the word is in the exceptions list
			return lo.exceptions[word].replace(/-/g, hyphen);
		}
		if (word.indexOf('-') !== -1) {
			//word contains '-' -> hyphenate the parts separated with '-'
			parts = word.split('-');
			for (i = 0, l = parts.length; i < l; i++) {
				parts[i] = hyphenateWord(lang, parts[i]);
			}
			return parts.join('-');
		}
		w = '_' + word + '_';
		if (word.indexOf(String.fromCharCode(8204)) !== -1) {
			parts = w.split(String.fromCharCode(8204));
			w = parts.join('');
			for (i = 0, l = parts.length; i < l; i++) {
				parts[i] = parts[i].length.toString();
			}
			parts.pop();
			ZWNJpos = parts;
		}
		wl = w.length;
		s = w.split('');
		if (!!lo.charSubstitution) {
			for (subst in lo.charSubstitution) {
				if (lo.charSubstitution.hasOwnProperty(subst)) {
					w = w.replace(new RegExp(subst, 'g'), lo.charSubstitution[subst]);
				}
			}
		}
		if (word.indexOf("'") !== -1) {
			w = w.toLowerCase().replace("'", "’"); //replace APOSTROPHE with RIGHT SINGLE QUOTATION MARK (since the latter is used in the patterns)
		} else {
			w = w.toLowerCase();
		}
		//finally the core hyphenation algorithm
		hypos = [];
		numb3rs = {'0': 0, '1': 1, '2': 2, '3': 3, '4': 4, '5': 5, '6': 6, '7': 7, '8': 8, '9': 9}; //check for member is faster then isFinite()
		n = wl - lo.shortestPattern;
		for (p = 0; p <= n; p++) {
			maxwins = Math.min((wl - p), lo.longestPattern);
			for (win = lo.shortestPattern; win <= maxwins; win++) {
				if (lo.patterns.hasOwnProperty(patk = w.substring(p, p + win))) {
					pat = lo.patterns[patk];
					if (enableReducedPatternSet && (typeof pat === 'string')) {
						lo.redPatSet[patk] = pat;
					}
					if (typeof pat === 'string') {
						//convert from string 'a5b' to array [1,5] (pos,value)
						t = 0;
						val = [];
						for (i = 0; i < pat.length; i++) {
							if (!!(c = numb3rs[pat.charAt(i)])) {
								val.push(i - t, c);
								t++;								
							}
						}
						pat = lo.patterns[patk] = val;
					}
				} else {
					continue;
				}
				for (i = 0; i < pat.length; i++) {
					c = p - 1 + pat[i];
					if (!hypos[c] || hypos[c] < pat[i + 1]) {
						hypos[c] = pat[i + 1];
					}
					i++;
				}
			}
		}
		inserted = 0;
		for (i = lo.leftmin; i <= (wl - 2 - lo.rightmin); i++) {
			if (ZWNJpos.length > 0 && ZWNJpos[0] === i) {
				ZWNJpos.shift();
				s.splice(i + inserted - 1, 0, String.fromCharCode(8204));
				inserted++;
			}			
			if (!!(hypos[i] & 1)) {
				s.splice(i + inserted + 1, 0, hyphen);
				inserted++;
			}
		}
		hyphenatedword = s.slice(1, -1).join('');
		if (enableCache) {
			lo.cache[word] = hyphenatedword;
		}
		return hyphenatedword;
	},
		
	/**
	 * @name Hyphenator-hyphenateURL
	 * @description
	 * Puts {@link Hyphenator-urlhyphen} after each no-alphanumeric char that my be in a URL.
	 * @param {string} url to hyphenate
	 * @returns string the hyphenated URL
	 * @public
	 */
	hyphenateURL = function (url) {
		return url.replace(/([:\/\.\?#&_,;!@]+)/gi, '$&' + urlhyphen);
	},

	/**
	 * @name Hyphenator-removeHyphenationFromElement
	 * @description
	 * Removes all hyphens from the element. If there are other elements, the function is
	 * called recursively.
	 * Removing hyphens is usefull if you like to copy text. Some browsers are buggy when the copy hyphenated texts.
	 * @param {Object} el The element where to remove hyphenation.
	 * @public
	 */
	removeHyphenationFromElement = function (el) {
		var h, i = 0, n;
		switch (hyphen) {
		case '|':
			h = '\\|';
			break;
		case '+':
			h = '\\+';
			break;
		case '*':
			h = '\\*';
			break;
		default:
			h = hyphen;
		}
		while (!!(n = el.childNodes[i++])) {
			if (n.nodeType === 3) {
				n.data = n.data.replace(new RegExp(h, 'g'), '');
				n.data = n.data.replace(new RegExp(zeroWidthSpace, 'g'), '');
			} else if (n.nodeType === 1) {
				removeHyphenationFromElement(n);
			}
		}
	},
	
	
	/**
	 * @name Hyphenator-registerOnCopy
	 * @description
	 * Huge work-around for browser-inconsistency when it comes to
	 * copying of hyphenated text.
	 * The idea behind this code has been provided by http://github.com/aristus/sweet-justice
	 * sweet-justice is under BSD-License
	 * @private
	 */
	registerOnCopy = function (el) {
		var body = el.ownerDocument.getElementsByTagName('body')[0],
		shadow,
		selection,
		range,
		rangeShadow,
		restore,
		oncopyHandler = function (e) {
			e = e || window.event;
			var target = e.target || e.srcElement,
			currDoc = target.ownerDocument,
			body = currDoc.getElementsByTagName('body')[0],
			targetWindow = 'defaultView' in currDoc ? currDoc.defaultView : currDoc.parentWindow;
			if (target.tagName && dontHyphenate[target.tagName.toLowerCase()]) {
				//Safari needs this
				return;
			}
			//create a hidden shadow element
			shadow = currDoc.createElement('div');
			shadow.style.overflow = 'hidden';
			shadow.style.position = 'absolute';
			shadow.style.top = '-5000px';
			shadow.style.height = '1px';
			body.appendChild(shadow);
			if (!!window.getSelection) {
				//FF3, Webkit
				selection = targetWindow.getSelection();
				range = selection.getRangeAt(0);
				shadow.appendChild(range.cloneContents());
				removeHyphenationFromElement(shadow);
				selection.selectAllChildren(shadow);
				restore = function () {
					shadow.parentNode.removeChild(shadow);
					selection.addRange(range);
				};
			} else {
				// IE
				selection = targetWindow.document.selection;
				range = selection.createRange();
				shadow.innerHTML = range.htmlText;
				removeHyphenationFromElement(shadow);
				rangeShadow = body.createTextRange();
				rangeShadow.moveToElementText(shadow);
				rangeShadow.select();
				restore = function () {
					shadow.parentNode.removeChild(shadow);
					if (range.text !== "") {
						range.select();
					}
				};
			}
			window.setTimeout(restore, 0);
		};
		if (!body) {
			return;
		}
		el = el || body;
		if (window.addEventListener) {
			el.addEventListener("copy", oncopyHandler, false);
		} else {
			el.attachEvent("oncopy", oncopyHandler);
		}
	},
	

	/**
	 * @name Hyphenator-hyphenateElement
	 * @description
	 * Takes the content of the given element and - if there's text - replaces the words
	 * by hyphenated words. If there's another element, the function is called recursively.
	 * When all words are hyphenated, the visibility of the element is set to 'visible'.
	 * @param {Object} el The element to hyphenate
	 * @private
	 */
	hyphenateElement = function (el) {
		var hyphenatorSettings = Expando.getDataForElem(el),
			lang = hyphenatorSettings.language, hyphenate, n, i,
			controlOrphans = function (part) {
				var h, r;
				switch (hyphen) {
				case '|':
					h = '\\|';
					break;
				case '+':
					h = '\\+';
					break;
				case '*':
					h = '\\*';
					break;
				default:
					h = hyphen;
				}
				if (orphanControl >= 2) {
					//remove hyphen points from last word
					r = part.split(' ');
					r[1] = r[1].replace(new RegExp(h, 'g'), '');
					r[1] = r[1].replace(new RegExp(zeroWidthSpace, 'g'), '');
					r = r.join(' ');
				}
				if (orphanControl === 3) {
					//replace spaces by non breaking spaces
					r = r.replace(/[ ]+/g, String.fromCharCode(160));
				}
				return r;
			};
		if (Hyphenator.languages.hasOwnProperty(lang)) {
			hyphenate = function (word) {
				if (!Hyphenator.doHyphenation) {
					return word;
				} else if (urlOrMailRE.test(word)) {
					return hyphenateURL(word);
				} else {
					return hyphenateWord(lang, word);
				}
			};
			if (safeCopy && (el.tagName.toLowerCase() !== 'body')) {
				registerOnCopy(el);
			}
			i = 0;
			while (!!(n = el.childNodes[i++])) {
				if (n.nodeType === 3 && n.data.length >= min) { //type 3 = #text -> hyphenate!
					n.data = n.data.replace(Hyphenator.languages[lang].genRegExp, hyphenate);
					if (orphanControl !== 1) {
						n.data = n.data.replace(/[\S]+ [\S]+$/, controlOrphans);
					}
				}
			}
		}
		if (hyphenatorSettings.isHidden && intermediateState === 'hidden') {
			el.style.visibility = 'visible';
			if (!hyphenatorSettings.hasOwnStyle) {
				el.setAttribute('style', ''); // without this, removeAttribute doesn't work in Safari (thanks to molily)
				el.removeAttribute('style');
			} else {
				if (el.style.removeProperty) {
					el.style.removeProperty('visibility');
				} else if (el.style.removeAttribute) { // IE
					el.style.removeAttribute('visibility');
				}  
			}
		}
		if (hyphenatorSettings.isLast) {
			state = 3;
			documentCount--;
			if (documentCount > (-1000) && documentCount <= 0) {
				documentCount = (-2000);
				onHyphenationDone();
			}
		}
	},
	

	/**
	 * @name Hyphenator-hyphenateDocument
	 * @description
	 * Calls hyphenateElement() for all members of elements. This is done with a setTimout
	 * to prevent a "long running Script"-alert when hyphenating large pages.
	 * Therefore a tricky bind()-function was necessary.
	 * @private
	 */
	hyphenateDocument = function () {
		function bind(fun, arg) {
			return function () {
				return fun(arg);
			};
		}
		var i = 0, el;
		while (!!(el = elements[i++])) {
			if (el.ownerDocument.location.href === contextWindow.location.href) {
				window.setTimeout(bind(hyphenateElement, el), 0);
			}
		}
	},

	/**
	 * @name Hyphenator-removeHyphenationFromDocument
	 * @description
	 * Does what it says ;-)
	 * @private
	 */
	removeHyphenationFromDocument = function () {
		var i = 0, el;
		while (!!(el = elements[i++])) {
			removeHyphenationFromElement(el);
		}
		state = 4;
	},
		
	/**
	 * @name Hyphenator-createStorage
	 * @description
	 * inits the private var storage depending of the setting in storageType
	 * and the supported features of the system.
	 * @private
	 */
	createStorage = function () {
		try {
			if (storageType !== 'none' &&
				typeof(window.localStorage) !== 'undefined' &&
				typeof(window.sessionStorage) !== 'undefined' &&
				typeof(window.JSON.stringify) !== 'undefined' &&
				typeof(window.JSON.parse) !== 'undefined') {
				switch (storageType) {
				case 'session':
					storage = window.sessionStorage;
					break;
				case 'local':
					storage = window.localStorage;
					break;
				default:
					storage = undefined;
					break;
				}
			}
		} catch (f) {
			//FF throws an error if DOM.storage.enabled is set to false
		}
	},
	
	/**
	 * @name Hyphenator-storeConfiguration
	 * @description
	 * Stores the current config-options in DOM-Storage
	 * @private
	 */
	storeConfiguration = function () {
		if (!storage) {
			return;
		}
		var settings = {
			'STORED': true,
			'classname': hyphenateClass,
			'donthyphenateclassname': dontHyphenateClass,
			'minwordlength': min,
			'hyphenchar': hyphen,
			'urlhyphenchar': urlhyphen,
			'togglebox': toggleBox,
			'displaytogglebox': displayToggleBox,
			'remoteloading': enableRemoteLoading,
			'enablecache': enableCache,
			'onhyphenationdonecallback': onHyphenationDone,
			'onerrorhandler': onError,
			'intermediatestate': intermediateState,
			'selectorfunction': selectorFunction,
			'safecopy': safeCopy,
			'doframes': doFrames,
			'storagetype': storageType,
			'orphancontrol': orphanControl,
			'dohyphenation': Hyphenator.doHyphenation,
			'persistentconfig': persistentConfig,
			'defaultlanguage': defaultLanguage
		};
		storage.setItem('Hyphenator_config', window.JSON.stringify(settings));
	},
	
	/**
	 * @name Hyphenator-restoreConfiguration
	 * @description
	 * Retrieves config-options from DOM-Storage and does configuration accordingly
	 * @private
	 */
	restoreConfiguration = function () {
		var settings;
		if (storage.getItem('Hyphenator_config')) {
			settings = window.JSON.parse(storage.getItem('Hyphenator_config'));
			Hyphenator.config(settings);
		}
	};

	return {
		
		/**
		 * @name Hyphenator.version
		 * @memberOf Hyphenator
		 * @description
		 * String containing the actual version of Hyphenator.js
		 * [major release].[minor releas].[bugfix release]
		 * major release: new API, new Features, big changes
		 * minor release: new languages, improvements
		 * @public
         */		
		version: '3.3.0',

		/**
		 * @name Hyphenator.doHyphenation
		 * @description
		 * If doHyphenation is set to false (defaults to true), hyphenateDocument() isn't called.
		 * All other actions are performed.
		 */		
		doHyphenation: true,
		
		/**
		 * @name Hyphenator.languages
		 * @memberOf Hyphenator
		 * @description
		 * Objects that holds key-value pairs, where key is the language and the value is the
		 * language-object loaded from (and set by) the pattern file.
		 * The language object holds the following members:
		 * <table>
		 * <tr><th>key</th><th>desc></th></tr>
		 * <tr><td>leftmin</td><td>The minimum of chars to remain on the old line</td></tr>
		 * <tr><td>rightmin</td><td>The minimum of chars to go on the new line</td></tr>
		 * <tr><td>shortestPattern</td><td>The shortes pattern (numbers don't count!)</td></tr>
		 * <tr><td>longestPattern</td><td>The longest pattern (numbers don't count!)</td></tr>
		 * <tr><td>specialChars</td><td>Non-ASCII chars in the alphabet.</td></tr>
		 * <tr><td>patterns</td><td>the patterns</td></tr>
		 * </table>
		 * And optionally (or after prepareLanguagesObj() has been called):
		 * <table>
		 * <tr><td>exceptions</td><td>Excpetions for the secified language</td></tr>
		 * </table>
		 * @public
         */		
		languages: {},
		

		/**
		 * @name Hyphenator.config
			 * @description
		 * Config function that takes an object as an argument. The object contains key-value-pairs
		 * containig Hyphenator-settings. This is a shortcut for calling Hyphenator.set...-Methods.
		 * @param {Object} obj <table>
		 * <tr><th>key</th><th>values</th><th>default</th></tr>
		 * <tr><td>classname</td><td>string</td><td>'hyphenate'</td></tr>
		 * <tr><td>donthyphenateclassname</td><td>string</td><td>''</td></tr>
		 * <tr><td>minwordlength</td><td>integer</td><td>6</td></tr>
		 * <tr><td>hyphenchar</td><td>string</td><td>'&amp;shy;'</td></tr>
		 * <tr><td>urlhyphenchar</td><td>string</td><td>'zero with space'</td></tr>
		 * <tr><td>togglebox</td><td>function</td><td>see code</td></tr>
		 * <tr><td>displaytogglebox</td><td>boolean</td><td>false</td></tr>
		 * <tr><td>remoteloading</td><td>boolean</td><td>true</td></tr>
		 * <tr><td>enablecache</td><td>boolean</td><td>true</td></tr>
		 * <tr><td>enablereducedpatternset</td><td>boolean</td><td>false</td></tr>
		 * <tr><td>onhyphenationdonecallback</td><td>function</td><td>empty function</td></tr>
		 * <tr><td>onerrorhandler</td><td>function</td><td>alert(onError)</td></tr>
		 * <tr><td>intermediatestate</td><td>string</td><td>'hidden'</td></tr>
		 * <tr><td>selectorfunction</td><td>function</td><td>[…]</td></tr>
		 * <tr><td>safecopy</td><td>boolean</td><td>true</td></tr>
		 * <tr><td>doframes</td><td>boolean</td><td>false</td></tr>
		 * <tr><td>storagetype</td><td>string</td><td>'none'</td></tr>
		 * </table>
		 * @public
		 * @example &lt;script src = "Hyphenator.js" type = "text/javascript"&gt;&lt;/script&gt;
         * &lt;script type = "text/javascript"&gt;
         *     Hyphenator.config({'minwordlength':4,'hyphenchar':'|'});
         *     Hyphenator.run();
         * &lt;/script&gt;
         */
		config: function (obj) {
			var assert = function (name, type) {
					if (typeof obj[name] === type) {
						return true;
					} else {
						onError(new Error('Config onError: ' + name + ' must be of type ' + type));
						return false;
					}
				},
				key;

			if (obj.hasOwnProperty('storagetype')) {
				if (assert('storagetype', 'string')) {
					storageType = obj.storagetype;
				}
				if (!storage) {
					createStorage();
				}			
			}
			if (!obj.hasOwnProperty('STORED') && storage && obj.hasOwnProperty('persistentconfig') && obj.persistentconfig === true) {
				restoreConfiguration();
			}
			
			for (key in obj) {
				if (obj.hasOwnProperty(key)) {
					switch (key) {
					case 'STORED':
						break;
					case 'classname':
						if (assert('classname', 'string')) {
							hyphenateClass = obj[key];
						}
						break;
					case 'donthyphenateclassname':
						if (assert('donthyphenateclassname', 'string')) {
							dontHyphenateClass = obj[key];
						}						
						break;
					case 'minwordlength':
						if (assert('minwordlength', 'number')) {
							min = obj[key];
						}
						break;
					case 'hyphenchar':
						if (assert('hyphenchar', 'string')) {
							if (obj.hyphenchar === '&shy;') {
								obj.hyphenchar = String.fromCharCode(173);
							}
							hyphen = obj[key];
						}
						break;
					case 'urlhyphenchar':
						if (obj.hasOwnProperty('urlhyphenchar')) {
							if (assert('urlhyphenchar', 'string')) {
								urlhyphen = obj[key];
							}
						}
						break;
					case 'togglebox':
						if (assert('togglebox', 'function')) {
							toggleBox = obj[key];
						}
						break;
					case 'displaytogglebox':
						if (assert('displaytogglebox', 'boolean')) {
							displayToggleBox = obj[key];
						}
						break;
					case 'remoteloading':
						if (assert('remoteloading', 'boolean')) {
							enableRemoteLoading = obj[key];
						}
						break;
					case 'enablecache':
						if (assert('enablecache', 'boolean')) {
							enableCache = obj[key];
						}
						break;
					case 'enablereducedpatternset':
						if (assert('enablereducedpatternset', 'boolean')) {
							enableReducedPatternSet = obj[key];
						}
						break;
					case 'onhyphenationdonecallback':
						if (assert('onhyphenationdonecallback', 'function')) {
							onHyphenationDone = obj[key];
						}
						break;
					case 'onerrorhandler':
						if (assert('onerrorhandler', 'function')) {
							onError = obj[key];
						}
						break;
					case 'intermediatestate':
						if (assert('intermediatestate', 'string')) {
							intermediateState = obj[key];
						}
						break;
					case 'selectorfunction':
						if (assert('selectorfunction', 'function')) {
							selectorFunction = obj[key];
						}
						break;
					case 'safecopy':
						if (assert('safecopy', 'boolean')) {
							safeCopy = obj[key];
						}
						break;
					case 'doframes':
						if (assert('doframes', 'boolean')) {
							doFrames = obj[key];
						}
						break;
					case 'storagetype':
						if (assert('storagetype', 'string')) {
							storageType = obj[key];
						}						
						break;
					case 'orphancontrol':
						if (assert('orphancontrol', 'number')) {
							orphanControl = obj[key];
						}
						break;
					case 'dohyphenation':
						if (assert('dohyphenation', 'boolean')) {
							Hyphenator.doHyphenation = obj[key];
						}
						break;
					case 'persistentconfig':
						if (assert('persistentconfig', 'boolean')) {
							persistentConfig = obj[key];
						}
						break;
					case 'defaultlanguage':
						if (assert('defaultlanguage', 'string')) {
							defaultLanguage = obj[key];
						}
						break;
					default:
						onError(new Error('Hyphenator.config: property ' + key + ' not known.'));
					}
				}
			}
			if (storage && persistentConfig) {
				storeConfiguration();
			}
		},

		/**
		 * @name Hyphenator.run
			 * @description
		 * Bootstrap function that starts all hyphenation processes when called.
		 * @public
		 * @example &lt;script src = "Hyphenator.js" type = "text/javascript"&gt;&lt;/script&gt;
         * &lt;script type = "text/javascript"&gt;
         *   Hyphenator.run();
         * &lt;/script&gt;
         */
		run: function () {
			documentCount = 0;
			var process = function () {
				try {
					if (contextWindow.document.getElementsByTagName('frameset').length > 0) {
						return; //we are in a frameset
					}
					documentCount++;
					autoSetMainLanguage(undefined);
					gatherDocumentInfos();
					//console.log('preparing for ' + contextWindow.location.href);
					prepare(hyphenateDocument);
					if (displayToggleBox) {
						toggleBox();
					}
				} catch (e) {
					onError(e);
				}
			}, i, haveAccess, fl = window.frames.length;
			
			if (!storage) {
				createStorage();
			}
			if (!documentLoaded && !isBookmarklet) {
				runOnContentLoaded(window, process);
			}
			if (isBookmarklet || documentLoaded) {
				if (doFrames && fl > 0) {
					for (i = 0; i < fl; i++) {
						haveAccess = undefined;
						//try catch isn't enough for webkit
						try {
							//opera throws only on document.toString-access
							haveAccess = window.frames[i].document.toString();
						} catch (e) {
							haveAccess = undefined;
						}
						if (!!haveAccess) {
							contextWindow = window.frames[i];
							process();
						}						
					}
				}
				contextWindow = window;
				process();
			}
		},
		
		/**
		 * @name Hyphenator.addExceptions
			 * @description
		 * Adds the exceptions from the string to the appropriate language in the 
		 * {@link Hyphenator-languages}-object
		 * @param {string} lang The language
		 * @param {string} words A comma separated string of hyphenated words WITH spaces.
		 * @public
		 * @example &lt;script src = "Hyphenator.js" type = "text/javascript"&gt;&lt;/script&gt;
         * &lt;script type = "text/javascript"&gt;
         *   Hyphenator.addExceptions('de','ziem-lich, Wach-stube');
         *   Hyphenator.run();
         * &lt;/script&gt;
         */
		addExceptions: function (lang, words) {
			if (lang === '') {
				lang = 'global';
			}
			if (exceptions.hasOwnProperty(lang)) {
				exceptions[lang] += ", " + words;
			} else {
				exceptions[lang] = words;
			}
		},
		
		/**
		 * @name Hyphenator.hyphenate
			 * @public
		 * @description
		 * Hyphenates the target. The language patterns must be loaded.
		 * If the target is a string, the hyphenated string is returned,
		 * if it's an object, the values are hyphenated directly.
		 * @param {string|Object} target the target to be hyphenated
		 * @param {string} lang the language of the target
		 * @returns string
		 * @example &lt;script src = "Hyphenator.js" type = "text/javascript"&gt;&lt;/script&gt;
		 * &lt;script src = "patterns/en.js" type = "text/javascript"&gt;&lt;/script&gt;
         * &lt;script type = "text/javascript"&gt;
		 * var t = Hyphenator.hyphenate('Hyphenation', 'en'); //Hy|phen|ation
		 * &lt;/script&gt;
		 */
		hyphenate: function (target, lang) {
			var hyphenate, n, i;
			if (Hyphenator.languages.hasOwnProperty(lang)) {
				if (!Hyphenator.languages[lang].prepared) {
					prepareLanguagesObj(lang);
				}
				hyphenate = function (word) {
					if (urlOrMailRE.test(word)) {
						return hyphenateURL(word);
					} else {
						return hyphenateWord(lang, word);
					}
				};
				if (typeof target === 'string' || target.constructor === String) {
					return target.replace(Hyphenator.languages[lang].genRegExp, hyphenate);
				} else if (typeof target === 'object') {
					i = 0;
					while (!!(n = target.childNodes[i++])) {
						if (n.nodeType === 3 && n.data.length >= min) { //type 3 = #text -> hyphenate!
							n.data = n.data.replace(Hyphenator.languages[lang].genRegExp, hyphenate);
						} else if (n.nodeType === 1) {
							if (n.lang !== '') {
								Hyphenator.hyphenate(n, n.lang);
							} else {
								Hyphenator.hyphenate(n, lang);
							}
						}
					}
				}
			} else {
				onError(new Error('Language "' + lang + '" is not loaded.'));
			}
		},
		
		/**
		 * @name Hyphenator.getRedPatternSet
			 * @description
		 * Returns {@link Hyphenator-isBookmarklet}.
		 * @param {string} lang the language patterns are stored for
		 * @returns object {'patk': pat}
		 * @public
         */
		getRedPatternSet: function (lang) {
			return Hyphenator.languages[lang].redPatSet;
		},
		
		/**
		 * @name Hyphenator.isBookmarklet
			 * @description
		 * Returns {@link Hyphenator-isBookmarklet}.
		 * @returns boolean
		 * @public
         */
		isBookmarklet: function () {
			return isBookmarklet;
		},

		getConfigFromURI: function () {
			var loc = null, re = {}, jsArray = document.getElementsByTagName('script'), i, j, l, s, gp, option;
			for (i = 0, l = jsArray.length; i < l; i++) {
				if (!!jsArray[i].getAttribute('src')) {
					loc = jsArray[i].getAttribute('src');
				}
				if (!loc) {
					continue;
				} else {
					s = loc.indexOf('Hyphenator.js?');
					if (s === -1) {
						continue;
					}
					gp = loc.substring(s + 14).split('&');
					for (j = 0; j < gp.length; j++) {
						option = gp[j].split('=');
						if (option[0] === 'bm') {
							continue;
						}
						if (option[1] === 'true') {
							re[option[0]] = true;
							continue;
						}
						if (option[1] === 'false') {
							re[option[0]] = false;
							continue;
						}
						if (isFinite(option[1])) {
							re[option[0]] = parseInt(option[1], 10);
							continue;
						}
						if (option[0] === 'onhyphenationdonecallback') {
							re[option[0]] = new Function('', option[1]);
							continue;
						}
						re[option[0]] = option[1];
					}
					break;
				}
			}
			return re;
		},

		/**
		 * @name Hyphenator.toggleHyphenation
			 * @description
		 * Checks the current state of the ToggleBox and removes or does hyphenation.
		 * @public
         */
		toggleHyphenation: function () {
			if (Hyphenator.doHyphenation) {
				removeHyphenationFromDocument();
				Hyphenator.doHyphenation = false;
				storeConfiguration();
				toggleBox();
			} else {
				hyphenateDocument();
				Hyphenator.doHyphenation = true;
				storeConfiguration();
				toggleBox();
			}
		}
	};
}(window));

//Export properties/methods (for google closure compiler)
Hyphenator['languages'] = Hyphenator.languages;
Hyphenator['config'] = Hyphenator.config;
Hyphenator['run'] = Hyphenator.run;
Hyphenator['addExceptions'] = Hyphenator.addExceptions;
Hyphenator['hyphenate'] = Hyphenator.hyphenate;
Hyphenator['getRedPatternSet'] = Hyphenator.getRedPatternSet;
Hyphenator['isBookmarklet'] = Hyphenator.isBookmarklet;
Hyphenator['getConfigFromURI'] = Hyphenator.getConfigFromURI;
Hyphenator['toggleHyphenation'] = Hyphenator.toggleHyphenation;
window['Hyphenator'] = Hyphenator;

if (Hyphenator.isBookmarklet()) {
	Hyphenator.config({displaytogglebox: true, intermediatestate: 'visible', doframes: true});
	Hyphenator.config(Hyphenator.getConfigFromURI());
	Hyphenator.run();
}
;
define("hyphenator", function(){});

// Modernizr v1.7  www.modernizr.com
window.Modernizr=function(a,b,c){function G(){e.input=function(a){for(var b=0,c=a.length;b<c;b++)t[a[b]]=!!(a[b]in l);return t}("autocomplete autofocus list placeholder max min multiple pattern required step".split(" ")),e.inputtypes=function(a){for(var d=0,e,f,h,i=a.length;d<i;d++)l.setAttribute("type",f=a[d]),e=l.type!=="text",e&&(l.value=m,l.style.cssText="position:absolute;visibility:hidden;",/^range$/.test(f)&&l.style.WebkitAppearance!==c?(g.appendChild(l),h=b.defaultView,e=h.getComputedStyle&&h.getComputedStyle(l,null).WebkitAppearance!=="textfield"&&l.offsetHeight!==0,g.removeChild(l)):/^(search|tel)$/.test(f)||(/^(url|email)$/.test(f)?e=l.checkValidity&&l.checkValidity()===!1:/^color$/.test(f)?(g.appendChild(l),g.offsetWidth,e=l.value!=m,g.removeChild(l)):e=l.value!=m)),s[a[d]]=!!e;return s}("search tel url email datetime date month week time datetime-local number range color".split(" "))}function F(a,b){var c=a.charAt(0).toUpperCase()+a.substr(1),d=(a+" "+p.join(c+" ")+c).split(" ");return!!E(d,b)}function E(a,b){for(var d in a)if(k[a[d]]!==c&&(!b||b(a[d],j)))return!0}function D(a,b){return(""+a).indexOf(b)!==-1}function C(a,b){return typeof a===b}function B(a,b){return A(o.join(a+";")+(b||""))}function A(a){k.cssText=a}var d="1.7",e={},f=!0,g=b.documentElement,h=b.head||b.getElementsByTagName("head")[0],i="modernizr",j=b.createElement(i),k=j.style,l=b.createElement("input"),m=":)",n=Object.prototype.toString,o=" -webkit- -moz- -o- -ms- -khtml- ".split(" "),p="Webkit Moz O ms Khtml".split(" "),q={svg:"http://www.w3.org/2000/svg"},r={},s={},t={},u=[],v,w=function(a){var c=b.createElement("style"),d=b.createElement("div"),e;c.textContent=a+"{#modernizr{height:3px}}",h.appendChild(c),d.id="modernizr",g.appendChild(d),e=d.offsetHeight===3,c.parentNode.removeChild(c),d.parentNode.removeChild(d);return!!e},x=function(){function d(d,e){e=e||b.createElement(a[d]||"div");var f=(d="on"+d)in e;f||(e.setAttribute||(e=b.createElement("div")),e.setAttribute&&e.removeAttribute&&(e.setAttribute(d,""),f=C(e[d],"function"),C(e[d],c)||(e[d]=c),e.removeAttribute(d))),e=null;return f}var a={select:"input",change:"input",submit:"form",reset:"form",error:"img",load:"img",abort:"img"};return d}(),y=({}).hasOwnProperty,z;C(y,c)||C(y.call,c)?z=function(a,b){return b in a&&C(a.constructor.prototype[b],c)}:z=function(a,b){return y.call(a,b)},r.flexbox=function(){function c(a,b,c,d){a.style.cssText=o.join(b+":"+c+";")+(d||"")}function a(a,b,c,d){b+=":",a.style.cssText=(b+o.join(c+";"+b)).slice(0,-b.length)+(d||"")}var d=b.createElement("div"),e=b.createElement("div");a(d,"display","box","width:42px;padding:0;"),c(e,"box-flex","1","width:10px;"),d.appendChild(e),g.appendChild(d);var f=e.offsetWidth===42;d.removeChild(e),g.removeChild(d);return f},r.canvas=function(){var a=b.createElement("canvas");return a.getContext&&a.getContext("2d")},r.canvastext=function(){return e.canvas&&C(b.createElement("canvas").getContext("2d").fillText,"function")},r.webgl=function(){return!!a.WebGLRenderingContext},r.touch=function(){return"ontouchstart"in a||w("@media ("+o.join("touch-enabled),(")+"modernizr)")},r.geolocation=function(){return!!navigator.geolocation},r.postmessage=function(){return!!a.postMessage},r.websqldatabase=function(){var b=!!a.openDatabase;return b},r.indexedDB=function(){for(var b=-1,c=p.length;++b<c;){var d=p[b].toLowerCase();if(a[d+"_indexedDB"]||a[d+"IndexedDB"])return!0}return!1},r.hashchange=function(){return x("hashchange",a)&&(b.documentMode===c||b.documentMode>7)},r.history=function(){return !!(a.history&&history.pushState)},r.draganddrop=function(){return x("dragstart")&&x("drop")},r.websockets=function(){return"WebSocket"in a},r.rgba=function(){A("background-color:rgba(150,255,150,.5)");return D(k.backgroundColor,"rgba")},r.hsla=function(){A("background-color:hsla(120,40%,100%,.5)");return D(k.backgroundColor,"rgba")||D(k.backgroundColor,"hsla")},r.multiplebgs=function(){A("background:url(//:),url(//:),red url(//:)");return(new RegExp("(url\\s*\\(.*?){3}")).test(k.background)},r.backgroundsize=function(){return F("backgroundSize")},r.borderimage=function(){return F("borderImage")},r.borderradius=function(){return F("borderRadius","",function(a){return D(a,"orderRadius")})},r.boxshadow=function(){return F("boxShadow")},r.textshadow=function(){return b.createElement("div").style.textShadow===""},r.opacity=function(){B("opacity:.55");return/^0.55$/.test(k.opacity)},r.cssanimations=function(){return F("animationName")},r.csscolumns=function(){return F("columnCount")},r.cssgradients=function(){var a="background-image:",b="gradient(linear,left top,right bottom,from(#9f9),to(white));",c="linear-gradient(left top,#9f9, white);";A((a+o.join(b+a)+o.join(c+a)).slice(0,-a.length));return D(k.backgroundImage,"gradient")},r.cssreflections=function(){return F("boxReflect")},r.csstransforms=function(){return!!E(["transformProperty","WebkitTransform","MozTransform","OTransform","msTransform"])},r.csstransforms3d=function(){var a=!!E(["perspectiveProperty","WebkitPerspective","MozPerspective","OPerspective","msPerspective"]);a&&"webkitPerspective"in g.style&&(a=w("@media ("+o.join("transform-3d),(")+"modernizr)"));return a},r.csstransitions=function(){return F("transitionProperty")},r.fontface=function(){var a,c,d=h||g,e=b.createElement("style"),f=b.implementation||{hasFeature:function(){return!1}};e.type="text/css",d.insertBefore(e,d.firstChild),a=e.sheet||e.styleSheet;var i=f.hasFeature("CSS2","")?function(b){if(!a||!b)return!1;var c=!1;try{a.insertRule(b,0),c=/src/i.test(a.cssRules[0].cssText),a.deleteRule(a.cssRules.length-1)}catch(d){}return c}:function(b){if(!a||!b)return!1;a.cssText=b;return a.cssText.length!==0&&/src/i.test(a.cssText)&&a.cssText.replace(/\r+|\n+/g,"").indexOf(b.split(" ")[0])===0};c=i('@font-face { font-family: "font"; src: url(data:,); }'),d.removeChild(e);return c},r.video=function(){var a=b.createElement("video"),c=!!a.canPlayType;if(c){c=new Boolean(c),c.ogg=a.canPlayType('video/ogg; codecs="theora"');var d='video/mp4; codecs="avc1.42E01E';c.h264=a.canPlayType(d+'"')||a.canPlayType(d+', mp4a.40.2"'),c.webm=a.canPlayType('video/webm; codecs="vp8, vorbis"')}return c},r.audio=function(){var a=b.createElement("audio"),c=!!a.canPlayType;c&&(c=new Boolean(c),c.ogg=a.canPlayType('audio/ogg; codecs="vorbis"'),c.mp3=a.canPlayType("audio/mpeg;"),c.wav=a.canPlayType('audio/wav; codecs="1"'),c.m4a=a.canPlayType("audio/x-m4a;")||a.canPlayType("audio/aac;"));return c},r.localstorage=function(){try{return!!localStorage.getItem}catch(a){return!1}},r.sessionstorage=function(){try{return!!sessionStorage.getItem}catch(a){return!1}},r.webWorkers=function(){return!!a.Worker},r.applicationcache=function(){return!!a.applicationCache},r.svg=function(){return!!b.createElementNS&&!!b.createElementNS(q.svg,"svg").createSVGRect},r.inlinesvg=function(){var a=b.createElement("div");a.innerHTML="<svg/>";return(a.firstChild&&a.firstChild.namespaceURI)==q.svg},r.smil=function(){return!!b.createElementNS&&/SVG/.test(n.call(b.createElementNS(q.svg,"animate")))},r.svgclippaths=function(){return!!b.createElementNS&&/SVG/.test(n.call(b.createElementNS(q.svg,"clipPath")))};for(var H in r)z(r,H)&&(v=H.toLowerCase(),e[v]=r[H](),u.push((e[v]?"":"no-")+v));e.input||G(),e.crosswindowmessaging=e.postmessage,e.historymanagement=e.history,e.addTest=function(a,b){a=a.toLowerCase();if(!e[a]){b=!!b(),g.className+=" "+(b?"":"no-")+a,e[a]=b;return e}},A(""),j=l=null,f&&a.attachEvent&&function(){var a=b.createElement("div");a.innerHTML="<elem></elem>";return a.childNodes.length!==1}()&&function(a,b){function p(a,b){var c=-1,d=a.length,e,f=[];while(++c<d)e=a[c],(b=e.media||b)!="screen"&&f.push(p(e.imports,b),e.cssText);return f.join("")}function o(a){var b=-1;while(++b<e)a.createElement(d[b])}var c="abbr|article|aside|audio|canvas|details|figcaption|figure|footer|header|hgroup|mark|meter|nav|output|progress|section|summary|time|video",d=c.split("|"),e=d.length,f=new RegExp("(^|\\s)("+c+")","gi"),g=new RegExp("<(/*)("+c+")","gi"),h=new RegExp("(^|[^\\n]*?\\s)("+c+")([^\\n]*)({[\\n\\w\\W]*?})","gi"),i=b.createDocumentFragment(),j=b.documentElement,k=j.firstChild,l=b.createElement("body"),m=b.createElement("style"),n;o(b),o(i),k.insertBefore(m,k.firstChild),m.media="print",a.attachEvent("onbeforeprint",function(){var a=-1,c=p(b.styleSheets,"all"),k=[],o;n=n||b.body;while((o=h.exec(c))!=null)k.push((o[1]+o[2]+o[3]).replace(f,"$1.iepp_$2")+o[4]);m.styleSheet.cssText=k.join("\n");while(++a<e){var q=b.getElementsByTagName(d[a]),r=q.length,s=-1;while(++s<r)q[s].className.indexOf("iepp_")<0&&(q[s].className+=" iepp_"+d[a])}i.appendChild(n),j.appendChild(l),l.className=n.className,l.innerHTML=n.innerHTML.replace(g,"<$1font")}),a.attachEvent("onafterprint",function(){l.innerHTML="",j.removeChild(l),j.appendChild(n),m.styleSheet.cssText=""})}(a,b),e._enableHTML5=f,e._version=d,g.className=g.className.replace(/\bno-js\b/,"")+" js "+u.join(" ");return e}(this,this.document);
define("modernizr-1.7", function(){});

/**
 * Aquest plugin permet desplaçar elements a la columna B, alineant-los a dreta o esquerra segons la configuració.
 * L'alineació lateral només es fiable perls continguts centrals de ipus paràgraf i llistes.
 *
 * En el cas de dispositius mòbils es mostrarà només una icona per mostrar i ocultar-lo.
 *
 * Es pot configurar mitjançant un objecte de configuració amb les següents opcions:
 *     forceIcons: true, // true | false. Si és true mostrarà sempre la icona desplegable i amagarà la columna b
 *     columnAlign: 'right', // left | right
 *     beforeContainer: 'p, ul, ol', // selectors per col·locar l'element abans per amplada d'escriptori
 *     defaultIcon: '../../../img/iocinclude.png', // Icona per defecte per desplegar els elements en
 *                                                 // amplada mòbil. Si no s'especifica s'intenta utilitzar el fons
 *                                                 // del contenidor de la columna (la nota, el text, etc.)
 *     icon: false  // Utilitza aquesta URL com a icon, pot ser una simple url o un objecte on s'assigna una
 *                  // icona a cada classe css (la classe de l'element que va a la columna b, per exemple
 *                  // {"iocreference" : "../../../img/iocreference.png)}
 *     lateralMargin: '2em', // Marge lateral de la columna
 *     debug: false, // Si és cert mostra un requadre vermell o verd segons si s'ha desplaçat el bloc
 *                               // de contingut (la nota, la imatge lateral, el text, etc.) o no.
 *     forceClear: true, // força el reemplaçament de la propietat clear, accepta una query com a valor
 *     class: undefined, // afegeix aquesta classe a l'element de la columna b,
 *     maxTitleLength: 60 // mida màxima dels tìtols que es mostren en fer mouseover sobre les icones (replegades)
 *
 * @example
 *     $(".iocnote, .iocreference, .ioctext, .iocfigurec").toBColumn({debug: true, columnAlign: 'left', forceClear: true});
 *     $(".iocnote, .iocreference, .ioctext, .iocfigurec").toBColumn({debug: true, columnAlign: 'left', forceClear: {query: 'p'}});
 *     $(".iocnote, .iocreference, .ioctext, .iocfigurec").toBColumn({minWidth: 0});
 *     jQuery(".iocfigurec").toBColumn();
 *
 * @known_issues
 *     - No tots els elements permeten l'alinació de columnes, encara que s'apliqui la opció forceClear, per exemple
 *       les figures principals
 *     - Per forçar la inclusió del CSS ho afegim com un node style directament al final del document, això fa que
 *     - sempre sobrescriguin regles anteriors.
 *
 *
 *
 * @author Xavier Garcia <xaviergaro.dev@gmail.com>
 */
(function ($) {

    // Afegim el node style
//    var css =
//        ".column-b {\n" +
//        "    margin-bottom: 1em;\n" +
//        "}\n" +
//        "\n" +
//        ".column-b.right {\n" +
//        "    margin-left: 2em;\n" +
//        "    float: right;\n" +
//        "    clear: right;\n" +
//        "}\n" +
//        "\n" +
//        ".column-b.left {\n" +
//        "    float: left;\n" +
//        "    clear: left;\n" +
//        "    margin-right: 2em;\n" +
//        "}\n" +
//        "\n" +
//        ".column-b {\n" +
//        "    display:none;\n" +
//        "}\n" +
//        "\n" +
//        ".column-b.mobile {\n" +
//        "    float: none;" +
//        "    margin-left: 0;" +
//        "    margin-right: 0;" +
//        "    display:block;\n" +
//        "}\n" +
//        "\n" +
//        "img.right {\n" +
//        "    float: right;\n" +
//        "}\n" +
//        "\n" +
//        ".column-b.right.mobile, .column-b.left.mobile {\n" +
//        "    clear: inherit;\n" +
//        "}" +
//        "img.left {\n" +
//        "    float: left;\n" +
//        "}\n" +
//        ".debug {\n" +
//        "    background-color: #d4edda;\n" +
//        "    border: 1px solid #c3e6cb;\n" +
//        "}\n" +
//        "\n" +
//        "@media (min-width: 992px) {\n" +
//        "    .column-b {\n" +
//        "        display:block;\n" +
//        "    }\n" +
//        "\n" +
//        "    .column-b.mobile {\n" +
//        "        display:none;\n" +
//        "    }\n" +
//        "}" +
//        ".column-b .hide {\n" +
//        "    display: none\n" +
//        "}\n" +
//        ".column-b .iocfigurec {\n" + // això és un arreglo forçat per la imatge lateral, queda massa petita
//        "    max-width:inherit;\n" +
//        "}";
//
//
//     //Ho desactivem, estem provant a integrar el fitxer css amb la build
//     var $style = jQuery('<style>');
//     $style.text(css);
//     jQuery('html').append($style);

    $.fn.toBColumn = function (options) {
        var settings = $.extend({
            forceIcons: false, // si mostrarà sempre les icones, ocultant els blocs
            columnAlign: 'right', // left | right
            beforeContainer: 'p, ul, ol', // selectors per col·locar l'element abans per amplada d'escriptori
            icon: false, // si s'assigna un icon es farà servir aquest, en lloc d'intentar cercar-lo
            defaultIcon: 'img/iocinclude.png', // Icona per defecte per desplegar els elements en amplada mòbil
            lateralMargin: '2em', // Marge lateral de la columna
            debug: false, // Si és cert mostra un requadre vermell o verd segons si s'ha desplaçat el bloc
                          // de contingut (la nota, la imatge lateral, el text, etc.) o no.
            forceClear: true, // força el reemplaçament de la propietat clear, accepta una query com a valor
            class: undefined, // classe CSS per aplicar a la columna
            maxTitleLength: 60 // mida màxima dels tìtols que es mostren sobre les icones

        }, options);


        this.each(function () {


            // PROVA: creem dos nous divs, amb classe column-b i column-b mobile.
            var $columnb = jQuery('<div class="column-b"></div>');
            $columnb.addClass(settings.columnAlign);


            var $content = jQuery(this);

            // $this.css('float', settings.columnAlign);
            // $this.css('clear', settings.columnAlign);

            // if (settings.columnAlign === 'right') {
            //     $content.css('margin-left', settings.lateralMargin);
            //     $content.css('margin-right', 0);
            // } else {
            //     $content.css('margin-left', 0);
            //     $content.css('margin-right', settings.lateralMargin);
            // }

            if (settings.class) {
                // $content.addClass(settings.class);
                $columnb.addClass(settings.class);
            }

            var $targetBlock = $content.prevAll(settings.beforeContainer).first();

            // Hem de cercar el targetblock previ adequat
            while ($targetBlock.length > 0 && $targetBlock.children().length === 0 && $targetBlock.text().trim().length === 0) {
                $targetBlock = $targetBlock.prevAll(settings.beforeContainer).first();
            }

            // Si no s'ha trobat no cal moure el content
            if ($targetBlock.length > 0) {
                $targetBlock.css('clear', 'inherit');
                $content.insertBefore($targetBlock);

                // Això és necessari perquè segons el css (això passa amb el del iocexport) pot ser que els
                // paràgrafs del bloc facin un clear de les columnes, llavors no s'alinea la columna b pels subsegüents paràgrafs
                if (settings.forceClear === true) {
                    $targetBlock.siblings('p').css('clear', 'inherit');
                } else if (settings.forceClear) {
                    $targetBlock.siblings(settings.forceClear.query).css('clear', 'inherit');
                }
            }


            if (settings.debug === true) {
                $targetBlock.addClass('debug');
                // TODO: Afegir classe 'debug'
                // No hi ha previ, no cal recol·locar
                // $target.css('background-color', '#d4edda');
                // $target.css('border', '1px solid #c3e6cb');
            }


            $columnb.insertBefore($content);


            // if (settings.minWidth > 0 && window.matchMedia('(min-width: ' + settings.minWidth + 'px)').matches) {
            // S'ha de recol·locar a sobre
            // var $target = $content.prevAll(settings.beforeContainer).first();

            // Ens assegurem que s'insereix abans d'un contenidor no buit
            // while ($targetBlock.length > 0 && $targetBlock.children().length === 0 && $targetBlock.text().trim().length === 0) {
            //     $targetBlock = $targetBlock.prevAll(settings.beforeContainer).first();
            // }

            // Només cal recol·locar si s'ha trobat un node previ
            // if ($targetBlock.length > 0) {
            //     $content.insertBefore($targetBlock);

            // if (settings.debug === true) {
            //     $targetBlock.css('background-color', '#f8d7da');
            //     $targetBlock.css('border', '1px solid #f5c6cb');
            // }

            // Reiniciem el clear perquè sinò no s'alineen correctament les columens
            // (el default és clear: left)
            // $targetBlock.css('clear', 'inherit');

            // El problema amb el clear és que si no s'afegeix només es produirà l'alineament correcte del
            // primer paràgraf (perquè forcem el clear), però no hi ha garantia de que aquest
            // sistema sigui correcte en tots els casos, per tant l'afegim com a opció
            // Aquest clear sembla que només s'aplica als paràgraf, i es fa pel CSS (als UL i LI no els
            // afecta)
            // if (settings.forceClear === true) {
            //     $targetBlock.siblings('p').css('clear', 'inherit');
            // } else if (settings.forceClear) {
            //     $targetBlock.siblings(settings.forceClear.query).css('clear', 'inherit');
            // }

            // }
            // } else if (settings.debug === true) {
            // 	// TODO: Afegir classe 'debug'
            //     // No hi ha previ, no cal recol·locar
            //     $target.css('background-color', '#d4edda');
            //     $target.css('border', '1px solid #c3e6cb');
            // }

            // } else {

            // Això s'ha de fer sempre, clonem el node
            var $columnbMobile = $columnb.clone();
            $columnbMobile.addClass('mobile');
            var $contentMobile = $content.clone();
            // TODO: Considerar moure això al css
            $contentMobile.css('cursor', 'pointer');
            // La x només és decorativa, ciclar en qualsevol punt tancará el desplegable
            var $close = jQuery("<span style='color: #5C5C5C; font-weight: bold; float: right; margin-top: -5px'>x</span>");
            $contentMobile.prepend($close);

            // Afegiem el contingut desprès de fer el clone per no duplicar-lo
            // de manera que a columnb queda lligat $content i a columnbMobile queda lligat $contentMobile.
            $columnb.append($content);

            // Alerta! Això és concret pel iocexportl, el zoom s'afegeix dins de functions mitjanaçant
            // previewImage. Només afegim la icona.


            // Ho converti'm en un clicable que es desplega

            // S'ha de mostrar quan es clica
            // var $node = jQuery('<div style="float:' + settings.columnAlign + '"></div>');

            var pattern = /url\("(.*)"\)?/gm;

            // TODO: Comprovar si settings.icon és un objecte i si és així
            // comprovar si hi ha correspondència amb la classe o alguna de les classes

            // ALERTA! La icona s'agafa del content original, el content clonat en aquest punt
            // sembla que no té el css assignat, encara que quan carrega la pàgina sí que hi és
            var icon = '';
            if (settings.icon) {
                icon = settings.icon;
            } else if ($content.css('background-image')) {

                var matches = pattern.exec($content.css('background-image'))
                icon = matches ? matches[1] : false;
            }

            // Si arribat a aquest punt sense icona fem servir el valor per defecte
            if (!icon) {
                console.warn("Using default icon");
                icon = settings.defaultIcon;
            }

            var $img = jQuery('<img src="' + icon + '" style="cursor: pointer">');
            $columnbMobile.append($img);
            $img.addClass(settings.columnAlign);

            // Títol
            var text = $content.text().trim();
            if (text.length>settings.maxTitleLength) {
                text = text.substr(0, settings.maxTitleLength) + "...";
            }

            // Es mostra a la icona
            $img.attr('title', text);

            // Les diferents configuracions es mostraran només en condicions alternes, segons l'amplada de la pantalla
            // i es gestiona mitjançant css mediaqueries
            $columnbMobile.insertAfter($columnb);
            $contentMobile.appendTo($columnbMobile);

            $contentMobile.addClass('hide');

            var toggle = false;

            // Mostra o amaga l'element
            $columnbMobile.on('click', function () {
                toggle = !toggle;

                // ALERTA! L'alineació de la columna oberta no la estem controlant per classe
                if (toggle) {
                    // $columnbMobile.removeClass('hide');
                    $contentMobile.removeClass('hide');
                    $columnbMobile.css('float', 'none');
                    $img.addClass('hide');
                } else {
                    // $columnbMobile.addClass('hide');
                    $columnbMobile.css('float', settings.columnAlign);
                    $contentMobile.addClass('hide');
                    $img.removeClass('hide');
                }


            });

            if (settings.forceIcons) {
                console.log("Forcing icons?", settings.forceIcons);
                $columnb.css('display', 'none');
                $columnbMobile.css('display', 'block');
            }

        });


        return this;
    };

}(jQuery));

define("jquery.ioc-tools", function(){});
