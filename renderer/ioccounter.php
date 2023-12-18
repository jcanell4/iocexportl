<?php
/**
 * LaTeX Plugin: Character counter
 *
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author Marc Català <mcatala@ioc.cat>
 */

// must be run within Dokuwiki
if(!defined('DOKU_INC')) die();
require_once DOKU_INC.'inc/parser/renderer.php';

/**
 * The Renderer
 */
class renderer_plugin_ioccounter extends Doku_Renderer {
    var $levelDiff=0;

    /**
     * Return version info
     */
    function getInfo(){
        return confToHash(dirname(__FILE__).'/info.txt');
    }

    /**
     * Returns the format produced by this renderer.
     */
    function getFormat(){
        return "ioccounter";
    }

    /**
     * Make multiple instances of this class
     */
    function isSingleton(){
        return FALSE;
    }

    function reset(){
        $this->doc = '';
    }

    /**
     * Initialize the rendering
     */
    function document_start() {
        header("Content-Type: application/force-download; charset=iso-8859-1");
        header("Content-Disposition: inline; filename=\"text.txt\"");
    }

    /**
     * Closes the document
     */
    function document_end(){
        $this->doc = preg_replace('/\n/',' ',$this->doc);//Replace line breaks
        $this->doc = preg_replace('/ {2,}/',' ',$this->doc);//Replace two or more spaces for one
        $this->doc = preg_replace('/ $/','',$this->doc);//Remove last space
    }

    function smiley($smiley) {}

    function render_TOC() { return ''; }

    function toc_additem($id, $text, $level) {}

    function cdata($text) {
        $this->doc .= $text;
    }

    function p_open(){}

    function p_close(){
        $this->doc .= " ";//One space when closing paragraph
    }

    function header($text, $level, $pos){
        $this->doc .= $text;
    }

    function hr() {}

    function linebreak() {
        $this->doc .= " ";//One space for each linebreak
    }

    function strong_open() {}

    function strong_close() {}

    function emphasis_open() {}

    function emphasis_close() {}

    function underline_open() {}

    function underline_close() {}

    function monospace_open() {}

    function monospace_close() {}

    function subscript_open() {}

    function subscript_close() {}

    function superscript_open() {}

    function superscript_close() {}

    function deleted_open() {}

    function deleted_close() {}

    function table_open($maxcols=NULL, $numrows=NULL, $pos=NULL){}

    function table_close($pos=NULL){}

    function tablerow_open(){}

    function tablerow_close(){}

    function tableheader_open($colspan = 1, $align = NULL, $rowspan = 1){}

    function tableheader_close(){}

    function tablecell_open($colspan = 1, $align = NULL, $rowspan = 1){}

    function tablecell_close(){}

    function footnote_open() {}

    function footnote_close() {}

    function listu_open() {}

    function listu_close() {}

    function listo_open() {}

    function listo_close() {}

    function listitem_open($level, $node=false) {}

    function listitem_close() {}

    function listcontent_open() {}

    function listcontent_close() {}

    function unformatted($text) {
        $this->doc .= $text;
    }

    function acronym($acronym) {
        $this->doc .= $acronym;
    }

    function entity($entity) {
        $this->doc .= $entity;
    }

    function multiplyentity($x, $y) {
        $this->doc .= $x.$y;
    }

    function singlequoteopening() {
        $this->doc .= "'";
    }

    function singlequoteclosing() {
        $this->doc .= "'";
    }

    function apostrophe() {
        $this->doc .= "'";
    }

    function doublequoteopening() {
        $this->doc .= '"';
    }

    function doublequoteclosing() {
        $this->doc .= '"';
    }

    function php($text, $wrapper='dummy') {
        $this->doc .= $text;
    }

    function phpblock($text) {
        $this->doc .= $text;
    }

    function html($text, $wrapper='dummy') {
        $this->doc .= $text;
    }

    function htmlblock($text) {
        $this->doc .= $text;
    }

    function preformatted($text) {
        $this->doc .= $text;
    }

    function file($text, $lang=NULL, $file=NULL) {
        $this->doc .= $text;
    }

    function quote_open() {}

    function quote_close() {}

    function code($text, $language=null, $filename=null) {
        $this->doc .= $text;
    }

    function internalmedia ($src, $title=null, $align=null, $width=null,
                            $height=null, $cache=null, $linking=null) {
        if(!$_SESSION['figure']){
            $this->doc .= $title;
        }
    }

    function externalmedia ($src, $title=NULL, $align=NULL, $width=NULL,
                            $height=NULL, $cache=NULL, $linking=NULL) {
        $this->doc .= $title;
    }

    function camelcaselink($link) {}

    function internallink($id, $name = NULL) {}

    /**
     * Add external link
     */
    function externallink($url, $title = NULL) {
        $this->doc .= $title;
    }

    function locallink($hash, $name = NULL){}

    function interwikilink($match, $name = NULL, $wikiName, $wikiUri) {}

    function windowssharelink($url, $name = NULL) {}

    function emaillink($address, $name = NULL) {}

    function rss ($url,$params){}
}
