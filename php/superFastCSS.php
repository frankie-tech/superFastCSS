<?php
/* 
 * MPL v2.0
 * Mozilla Public License
 *
 */

$links = $modx->getOption('links',$scriptProperties,'');
$ids = $modx->getOption('ids',$scriptProperties,'');
$cdn = $modx->getOption('cdn', $scriptProperties, '');

$cdndomain = preg_match("/http(s)?\:\/\/.*.com\//", $cdn, $matches);
$cdn = "<link rel='dns-prefetch' href='${cdndomain}'/>" . "<link rel='preconnect' href='${cdndomain}'/>";


if (!is_null($links) && is_string($links)) {
    $links = explode(',', $links);
}

if (!is_null($ids)) {
    $ids = explode(',', $links);
    foreach($ids as &$id) {
        $id = $this->$modx->getObject('modResource',$id);

    }
}
$noscripts = '<noscript>';

foreach($links as &$link) {
    if (intval($link) !== 0) {
        $link = $modx->makeUrl($link);
    }
    $noscripts = $noscripts . "<link href='${link}' rel=stylesheet/>";
    $link = "<link href='${link}' rel=preload as=style onload='this.onload=null;this.rel=\"stylesheet\"'/>";
}

$noscripts = $noscripts . '</noscript>';

$links = implode('',$links);

$polyfill = '<script>/*! loadCSS. [c]2017 Filament Group, Inc. MIT License */!function(t){"use strict";t.loadCSS||(t.loadCSS=function(){});var e=loadCSS.relpreload={};if(e.support=function(){var e;try{e=t.document.createElement("link").relList.supports("preload")}catch(t){e=!1}return function(){return e}}(),e.bindMediaToggle=function(t){var e=t.media||"all";function a(){t.addEventListener?t.removeEventListener("load",a):t.attachEvent&&t.detachEvent("onload",a),t.setAttribute("onload",null),t.media=e}t.addEventListener?t.addEventListener("load",a):t.attachEvent&&t.attachEvent("onload",a),setTimeout(function(){t.rel="stylesheet",t.media="only x"}),setTimeout(a,3e3)},e.poly=function(){if(!e.support())for(var a=t.document.getElementsByTagName("link"),n=0;n<a.length;n++){var o=a[n];"preload"!==o.rel||"style"!==o.getAttribute("as")||o.getAttribute("data-loadcss")||(o.setAttribute("data-loadcss",!0),e.bindMediaToggle(o))}},!e.support()){e.poly();var a=t.setInterval(e.poly,500);t.addEventListener?t.addEventListener("load",function(){e.poly(),t.clearInterval(a)}):t.attachEvent&&t.attachEvent("onload",function(){e.poly(),t.clearInterval(a)})}"undefined"!=typeof exports?exports.loadCSS=loadCSS:t.loadCSS=loadCSS}("undefined"!=typeof global?global:this)</script>';

$output = $cdn . $links . $noscripts . $polyfill; 
return $output;
