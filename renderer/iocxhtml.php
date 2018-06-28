<?php
/**
 * LaTeX Plugin: Export content to HTML
 *
 * @license GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author  Marc Català <mcatala@ioc.cat>
 */
// must be run within Dokuwiki
if(!defined('DOKU_INC')) die();
if(!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');
if(!defined('DOKU_PLUGIN_LATEX_TMP')) define('DOKU_PLUGIN_LATEX_TMP',DOKU_PLUGIN.'tmp/latex/');
require_once DOKU_INC.'inc/parser/renderer.php';
require_once(DOKU_PLUGIN.'iocexportl/lib/renderlib.php');

/**
 * The Renderer
 */
class renderer_plugin_iocxhtml extends Doku_Renderer {

	/**
	 * 	XHTML variables
	 */
    // @access public
    var $doc = '';        // will contain the whole document
    var $toc = array();   // will contain the Table of Contents

    private $sectionedits = array(); // A stack of section edit data

    var $headers = array();
    var $footnotes = array();
    var $lastlevel = 0;
    var $node = array(0,0,0,0,0);
    var $store = '';

    var $_counter   = array(); // used as global counter, introduced for table classes
    var $_codeblock = 0; // counts the code and file blocks, used to provide download links


    var $id = '';
    var $monospace = FALSE;
    var $table = FALSE;
    var $tmp_dir = 0;//Value of temp dir


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
        return "iocxhtml";
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
        global $USERINFO;
        global $conf;

        //reset some internals
        $this->toc     = array();
        $this->headers = array();

		$this->id = getID();
        //Check whether user can export
		$exportallowed = (isset($conf['plugin']['iocexportl']['allowexport']) && $conf['plugin']['iocexportl']['allowexport']);
        if (!$exportallowed && !auth_isadmin()) die;

        //Global variables
        $this->_initialize_globals();
    }

    /**
     * Closes the document
     */
    function document_end(){
    }

    /**
     * _getMediaLinkConf is a helperfunction to internalmedia() and externalmedia()
     * which returns a basic link to a media.
     *
     * @author Pierre Spring <pierre.spring@liip.ch>
     * @param string $src
     * @param string $title
     * @param string $align
     * @param string $width
     * @param string $height
     * @param string $cache
     * @param string $render
     * @access protected
     * @return array
     */
    function _getMediaLinkConf($src, $title, $align, $width, $height, $cache, $render)
    {
        global $conf;

        $link = array();
        $link['class']  = 'media';
        $link['style']  = '';
        $link['pre']    = '';
        $link['suf']    = '';
        $link['more']   = '';
        $link['target'] = $conf['target']['media'];
        $link['title']  = $this->_xmlEntities($src);
        $link['name']   = $this->_media($src, $title, $align, $width, $height, $cache, $render);

        return $link;
    }

	/**
     * Creates a linkid from a headline
     *
     * @param string  $title   The headline title
     * @param boolean $create  Create a new unique ID?
     * @author Andreas Gohr <andi@splitbrain.org>
     */
    function _headerToLink($title,$create=false) {
        if($create){
            return sectionID($title,$this->headers);
        }else{
            $check = false;
            return sectionID($title,$check);
        }
    }

	/**
     * NOVA
     */
    function _initialize_globals(){
        if (!isset($_SESSION['accounting'])){
            $_SESSION['accounting'] = FALSE;
        }
        if (!isset($_SESSION['activities_header'])){
            $_SESSION['activities_header'] = FALSE;
        }
        if (!isset($_SESSION['activities'])){
            $_SESSION['activities'] = FALSE;
        }
        if (!isset($_SESSION['chapter'])){
            $_SESSION['chapter'] = 1;
        }
        if (!isset($_SESSION['createbook'])){
            $_SESSION['createbook'] = FALSE;
        }
        if (!isset($_SESSION['draft'])){
            $_SESSION['draft'] = FALSE;
        }
        if (!isset($_SESSION['export_html'])){
            $_SESSION['export_html'] = FALSE;
        }
        if (!isset($_SESSION['figfooter'])){
            $_SESSION['figfooter'] = '';
        }
        if (!isset($_SESSION['figlabel'])){
            $_SESSION['figlabel'] = '';
        }
        if (!isset($_SESSION['figtitle'])){
            $_SESSION['figtitle'] = '';
        }
        if (!isset($_SESSION['figure'])){
            $_SESSION['figure'] = FALSE;
        }
        if (!isset($_SESSION['iocelem'])){
            $_SESSION['iocelem'] = FALSE;
        }
        if (!isset($_SESSION['imgB'])){
            $_SESSION['imgB'] = FALSE;
        }
        if (!isset($_SESSION['qrcode'])){
            $_SESSION['qrcode'] = FALSE;
        }
        if (!isset($_SESSION['quizmode'])){
            $_SESSION['quizmode'] = FALSE;
        }
        if (!isset($_SESSION['table'])){
            $_SESSION['table'] = FALSE;
        }
        if (!isset($_SESSION['table_id'])){
            $_SESSION['table_id'] = '';
        }
        if (!isset($_SESSION['table_footer'])){
            $_SESSION['table_footer'] = '';
        }
        if (!isset($_SESSION['table_large'])){
            $_SESSION['table_large'] = FALSE;
        }
        if (!isset($_SESSION['table_title'])){
            $_SESSION['table_title'] = '';
        }
        if (!isset($_SESSION['u0'])){
            $_SESSION['u0'] = FALSE;
        }
        if (!isset($_SESSION['video_url'])){
            $_SESSION['video_url'] = FALSE;
        }
    }


    /**
     * Use GeSHi to highlight language syntax in code and file blocks
     *
     * @author Andreas Gohr <andi@splitbrain.org>
     */
    function _highlight($type, $text, $language=null, $filename=null) {
        global $conf;
        global $ID;
        global $lang;

        if($filename){
            // add icon
            list($ext) = mimetype($filename,false);
            $class = preg_replace('/[^_\-a-z0-9]+/i','_',$ext);
            $class = 'mediafile mf_'.$class;

            $this->doc .= '<dl class="'.$type.'">'.DOKU_LF;
            $this->doc .= '<dt><a href="'.exportlink($ID,'code',array('codeblock'=>$this->_codeblock)).'" title="'.$lang['download'].'" class="'.$class.'">';
            $this->doc .= hsc($filename);
            $this->doc .= '</a></dt>'.DOKU_LF.'<dd>';
        }

        if ($text{0} == "\n") {
            $text = substr($text, 1);
        }
        if (substr($text, -1) == "\n") {
            $text = substr($text, 0, -1);
        }
        if ( is_null($language) ) {
            $this->doc .= '<pre class="'.$type.'">'.$this->_xmlEntities($text).'</pre>'.DOKU_LF;
        } else {
            $class = 'code'; //we always need the code class to make the syntax highlighting apply
            if($type != 'code') $class .= ' '.$type;
            $this->doc .= "<pre class=\"$class $language\">".$this->p_iocxhtml_cached_geshi($text, $language, '').'</pre>'.DOKU_LF;
        }

        if($filename){
            $this->doc .= '</dd></dl>'.DOKU_LF;
        }

        $this->_codeblock++;
    }

    /**
     * Returns an HTML code for images used in link titles
     *
     * @todo Resolve namespace on internal images
     * @author Andreas Gohr <andi@splitbrain.org>
     */
    function _imageTitle($img) {
        global $ID;

        // some fixes on $img['src']
        // see internalmedia() and externalmedia()
        list($img['src'],$hash) = explode('#',$img['src'],2);
        if ($img['type'] == 'internalmedia') {
            resolve_mediaid(getNS($ID),$img['src'],$exists);
        }

        return $this->_media($img['src'],
                              $img['title'],
                              $img['align'],
                              $img['width'],
                              $img['height'],
                              $img['cache']);
    }

    /**
     * NOVA
     */
    function _format_text($text){
        $text = $this->_ttEntities(trim($text));//Remove extended symbols
        $this->doc .= $text . DOKU_LF;
    }

    /**
     * NOVA
     */
    function label_document() { //For links
        if (isset($this->info['current_file_id'])) {
          $cleanid = $this->info['current_file_id'];
        }
        else {
          $cleanid = noNS(cleanID($this->info['current_id'], TRUE));
        }
        $this->doc .= "\label{" . md5($cleanid) . "}";
        if (isset($this->info['current_file_id'])){
          $this->doc .= "%%Start: " . $cleanid . ' => '
    		   . $this->info['current_file_id'].DOKU_LF;
        } else {
          $this->doc .= "%%Start: " . $cleanid . ' => ' . wikiFN($cleanid).DOKU_LF;
        }
      }

     /**
     * NOVA
     */
    function _latexEntities($string, $ent=null) {
        return $this->_xmlEntities($string);
    }

    /**
     * NOVA
     */
    function smiley($smiley) {
        if ( array_key_exists($smiley, $this->smileys) ) {
            $title = $this->_xmlEntities($this->smileys[$smiley]);
            $this->doc .= '<img src="'.DOKU_BASE.'lib/images/smileys/'.$this->smileys[$smiley].
                '" class="middle" alt="'.
                    $this->_xmlEntities($smiley).'" />';
        } else {
            $this->doc .= $this->_xmlEntities($smiley);
        }
      }


    function render_TOC() {
         return '';
    }

    function toc_additem($id, $text, $level) {
    global $conf;

        //handle TOC
        if($level >= $conf['toptoclevel'] && $level <= $conf['maxtoclevel']){
            $this->toc[] = html_mktocitem($id, $text, $level-$conf['toptoclevel']+1);
        }
    }

    function section_open($level) {
        $this->doc .= '<div class="level' . $level . '">' . DOKU_LF;
    }

    function section_close() {
        $this->doc .= DOKU_LF.'</div>'.DOKU_LF;
    }

    function cdata($text) {
        if ($this->monospace){
            $text = preg_replace('/\n/', '<br />', $text);
        }
        $this->doc .= $this->_xmlEntities($text);
    }

    function p_open(){
        $this->doc .= DOKU_LF.'<p>'.DOKU_LF;
    }

    function p_close(){
        $this->doc .= DOKU_LF.'</p>'.DOKU_LF;
    }

    function header($text, $level, $pos){
        global $conf;

        if(!$text) return; //skip empty headlines

        $hid = $this->_headerToLink($text,true);

        // write the header
        $this->doc .= DOKU_LF.'<h'.$level;
        $this->doc .= '><a id="'.$hid.'" >';
        $this->doc .= $this->_xmlEntities($text);
        $this->doc .= "</a></h$level>".DOKU_LF;
    }

    function hr() {
    }

    function linebreak() {
        $this->doc .= '<br/>'.DOKU_LF;
    }

    function strong_open() {
        $this->doc .= '<strong>';
    }

    function strong_close() {
        $this->doc .= '</strong>';
    }

    function emphasis_open() {
        $this->doc .= '<em>';
    }

    function emphasis_close() {
        $this->doc .= '</em>';
    }

    function underline_open() {
        $this->doc .= '<em class="u">';
    }

    function underline_close() {
        $this->doc .= '</em>';
    }

    function monospace_open() {
       $this->doc .= '<code>';
    }

    function monospace_close() {
       $this->doc .= '</code>';
    }

    function subscript_open() {
        $this->doc .= '<sub>';
    }

    function subscript_close() {
        $this->doc .= '</sub>';
    }

    function superscript_open() {
        $this->doc .= '<sup>';
    }

    function superscript_close() {
        $this->doc .= '</sup>';
    }

    function deleted_open() {
        $this->doc .= '<del>';
    }

    function deleted_close() {
        $this->doc .= '</del>';
    }

    /*
     * Tables
     */
    function table_open($maxcols = NULL, $numrows = NULL){
        global $lang;
        // initialize the row counter used for classes
        $this->_counter['row_counter'] = 0;
        $class = 'table';
        if ($_SESSION['activity']){
            $class .= ' tabminheight';
        }
        $this->doc .= '<div class="'.$class.'"><table class="inline">'.DOKU_LF;
        $this->table = TRUE;
    }

    function table_close(){
        $this->doc .= '</table></div>'.DOKU_LF;
        $this->table = FALSE;
    }

    function tablerow_open(){
        // initialize the cell counter used for classes
        $this->_counter['cell_counter'] = 0;
        $class = 'row' . $this->_counter['row_counter']++;
        $this->doc .= DOKU_TAB . '<tr class="'.$class.'">' . DOKU_LF . DOKU_TAB . DOKU_TAB;
    }

    function tablerow_close(){
        $this->doc .= DOKU_LF . DOKU_TAB . '</tr>' . DOKU_LF;
    }

    function tableheader_open($colspan = 1, $align = NULL, $rowspan = 1){
        $class = 'class="col' . $this->_counter['cell_counter']++;
        if ( !is_null($align) ) {
            $class .= ' '.$align.'align';
        }
        $class .= '"';
        $this->doc .= '<th ' . $class;
        if ( $colspan > 1 ) {
            $this->_counter['cell_counter'] += $colspan-1;
            $this->doc .= ' colspan="'.$colspan.'"';
        }
        if ( $rowspan > 1 ) {
            $this->doc .= ' rowspan="'.$rowspan.'"';
        }
        $this->doc .= '>';
    }

    function tableheader_close(){
        $this->doc .= '</th>';
    }

    function tablecell_open($colspan = 1, $align = NULL, $rowspan = 1){
        $class = 'class="col' . $this->_counter['cell_counter']++;
        if ( !is_null($align) ) {
            $class .= ' '.$align.'align';
        }
        $class .= '"';
        $this->doc .= '<td '.$class;
        if ( $colspan > 1 ) {
            $this->_counter['cell_counter'] += $colspan-1;
            $this->doc .= ' colspan="'.$colspan.'"';
        }
        if ( $rowspan > 1 ) {
            $this->doc .= ' rowspan="'.$rowspan.'"';
        }
        //Esta función no es llamada, en su lugar se usa: dokuwiki_30/inc/parser/hhtml.php
        if (!empty($_SESSION['table_widths'])) {
            $this->doc .= ' width="'.$_SESSION['table_widths'][0].'%"';
            array_shift($_SESSION['table_widths']);
        }
        $this->doc .= '>';
    }

    function tablecell_close(){
        $this->doc .= '</td>';
    }

    function footnote_open() {}

    function footnote_close() {}

    function listu_open() {
        $this->doc .= '<ul>'.DOKU_LF;
    }

    function listu_close() {
        $this->doc .= '</ul>'.DOKU_LF;
    }

    function listo_open() {
        $this->doc .= '<ol>'.DOKU_LF;
    }

    function listo_close() {
        $this->doc .= '</ol>'.DOKU_LF;
    }

    function listitem_open($level) {
        $this->doc .= '<li class="level'.$level.'">';
    }

    function listitem_close() {
        $this->doc .= '</li>'.DOKU_LF;
    }

    function listcontent_open() {
        $this->doc .= '<div class="li">';
    }

    function listcontent_close() {
        $this->doc .= '</div>'.DOKU_LF;
    }

    function unformatted($text) {
        $this->doc .= $this->_xmlEntities($text);
    }

    function acronym($acronym) {
        if ( array_key_exists($acronym, $this->acronyms) ) {

            $title = $this->_xmlEntities($this->acronyms[$acronym]);

            $this->doc .= '<acronym title="'.$title
                .'">'.$this->_xmlEntities($acronym).'</acronym>';

        } else {
            $this->doc .= $this->_xmlEntities($acronym);
        }
    }

    function entity($entity) {
        if ( array_key_exists($entity, $this->entities) ) {
            $this->doc .= $this->entities[$entity];
        } else {
            $this->doc .= $this->_xmlEntities($entity);
        }
    }

    function multiplyentity($x, $y) {
        $this->doc .= "$x&times;$y";
    }

    function singlequoteopening() {
        global $lang;
        $this->doc .= $lang['singlequoteopening'];
    }

    function singlequoteclosing() {
        global $lang;
        $this->doc .= $lang['singlequoteclosing'];
    }

    function apostrophe() {
        global $lang;
        $this->doc .= $lang['apostrophe'];
    }

    function doublequoteopening() {
        global $lang;
        $this->doc .= $lang['doublequoteopening'];
    }

    function doublequoteclosing() {
        global $lang;
        $this->doc .= $lang['doublequoteclosing'];
    }

    function php($text, $wrapper='dummy') {
        global $conf;

        if($conf['phpok']){
          ob_start();
          eval($text);
          $this->doc .= ob_get_contents();
          ob_end_clean();
        } else {
          $this->doc .= p_xhtml_cached_geshi($text, 'php', $wrapper);
        }
    }

    function phpblock($text) {
        $this->php($text, 'pre');
    }

    function html($text, $wrapper='dummy') {
        global $conf;

        if($conf['htmlok']){
          $this->doc .= $text;
        } else {
          $this->doc .= p_xhtml_cached_geshi($text, 'html4strict', $wrapper);
        }
    }

    function htmlblock($text) {
        $this->html($text, 'pre');
    }

    function preformatted($text) {
        $this->doc .= '<pre class="code">' . trim($this->_xmlEntities($text),"\n\r") . '</pre>'. DOKU_LF;
    }

    function file($text) {
        $this->_highlight('file',$text,$language,$filename);
    }

    function quote_open() {
        $this->doc .= '<blockquote><div class="no">'.DOKU_LF;
    }

    function quote_close() {
        $this->doc .= '</div></blockquote>'.DOKU_LF;
    }

    function code($text, $language=null, $filename=null) {
        $this->_highlight('code',$text,$language,$filename);
    }

    function internalmedia ($src, $title=null, $align=null, $width=null,
                            $height=null, $cache=null, $linking=null) {
        global $ID;
        list($src,$hash) = explode('#',$src,2);
        resolve_mediaid(getNS($ID),$src, $exists);

        $noLink = false;
        $render = ($linking == 'linkonly') ? false : true;
        $link = $this->_getMediaLinkConf($src, $title, $align, $width, $height, $cache, $render);

        list($ext,$mime,$dl) = mimetype($src,false);
        if(substr($mime,0,5) == 'image' && $render){
            $link['url'] = ml($src,array('id'=>$ID,'cache'=>$cache),($linking=='direct'));
        }elseif($mime == 'application/x-shockwave-flash' && $render){
            // don't link flash movies
            $noLink = true;
        }else{
            // add file icons
            $class = preg_replace('/[^_\-a-z0-9]+/i','_',$ext);
            $link['class'] .= ' mediafile mf_'.$class;
            $link['url'] = ml($src,array('id'=>$ID,'cache'=>$cache),true);
        }

        if($hash) $link['url'] .= '#'.$hash;

        //markup non existing files
        if (!$exists)
          $link['class'] .= ' wikilink2';

        //output formatted
        //if ($linking == 'nolink' || $noLink) $this->doc .= $link['name'];
        //else $this->doc .= $this->_formatLink($link);
        $this->doc .= $link['name'];
    }

    function externalmedia ($src, $title=NULL, $align=NULL, $width=NULL,
                            $height=NULL, $cache=NULL, $linking=NULL) {
        global $conf;
        list($ext,$mime) = mimetype($src);
        if(substr($mime,0,5) == 'image'){
            $tmp_name = tempnam(DOKU_PLUGIN_LATEX_TMP.$this->tmp_dir.'/media', 'ext');
            $client = new DokuHTTPClient;
            $img = $client->get($src);
            if (!$img) {
                $this->externallink($src, $title);
            } else {
                $tmp_img = fopen($tmp_name, "w") or die("Can't create temp file $tmp_img");
                fwrite($tmp_img, $img);
                fclose($tmp_img);
				//Add and convert image to pdf
				$this->_media($tmp_name, $title, NULL, $width, $height);
            }
        }else{
            $this->externallink($src, $title);
        }
    }

    function camelcaselink($link) {
        $this->internallink($link,$link);
    }

    /**
     * Render an internal Wiki Link
     */
    function internallink($id, $name = NULL) {
        global $conf;
        global $ID;

        $params = '';
        $parts = explode('?', $id, 2);
        if (count($parts) === 2) {
            $id = $parts[0];
            $params = $parts[1];
        }

        // For empty $id we need to know the current $ID
        // We need this check because _simpleTitle needs
        // correct $id and resolve_pageid() use cleanID($id)
        // (some things could be lost)
        if ($id === '') {
            $id = $ID;
        }

        // default name is based on $id as given
        $default = $this->_simpleTitle($id);

        // now first resolve and clean up the $id
        resolve_pageid(getNS($ID),$id,$exists);

        $name = $this->_getLinkTitle($name, $default, $isImage, $id, $linktype);

        if ( !$isImage ) {
            if ( $exists ) {
                $class='wikilink1';
            } else {
                $class='wikilink2';
                $link['rel']='nofollow';
            }
        } else {
            $class='media';
        }

        //keep hash anchor
        list($id,$hash) = explode('#',$id,2);
        if(!empty($hash)) $hash = $this->_headerToLink($hash);

        //prepare for formating
        $link['target'] = $conf['target']['wiki'];
        $link['style']  = '';
        $link['pre']    = '';
        $link['suf']    = '';
        // highlight link to current page
        if ($id == $ID) {
            $link['pre']    = '<span class="curid">';
            $link['suf']    = '</span>';
        }
        $link['more']   = '';
        $link['class']  = $class;
        $link['url']    = wl($id, $params);
        $link['name']   = $name;
        $link['title']  = $id;
        //add search string
        if($search){
            ($conf['userewrite']) ? $link['url'].='?' : $link['url'].='&amp;';
            if(is_array($search)){
                $search = array_map('rawurlencode',$search);
                $link['url'] .= 's[]='.join('&amp;s[]=',$search);
            }else{
                $link['url'] .= 's='.rawurlencode($search);
            }
        }

        //keep hash
        if($hash) $link['url'].='#'.$hash;

        //output formatted
        if($returnonly){
            return $this->_formatLink($link);
        }else{
            $this->doc .= $this->_formatLink($link);
        }
    }

    /**
     * Add external link
     */
    function externallink($url, $title = NULL) {
        global $conf;

        $name = $this->_getLinkTitle($title, $url, $isImage);

        // url might be an attack vector, only allow registered protocols
        if(is_null($this->schemes)) $this->schemes = getSchemes();
        list($scheme) = explode('://',$url);
        $scheme = strtolower($scheme);
        if(!in_array($scheme,$this->schemes)) $url = '';

        // is there still an URL?
        if(!$url){
            $this->doc .= $name;
            return;
        }

        // set class
        if ( !$isImage ) {
            $class='urlextern';
        } else {
            $class='media';
        }

        //prepare for formating
        $link['target'] = $conf['target']['extern'];
        $link['style']  = '';
        $link['pre']    = '';
        $link['suf']    = '';
        $link['more']   = '';
        $link['class']  = $class;
        $link['url']    = $url;

        $link['name']   = $name;
        $link['title']  = $this->_xmlEntities($url);
        if($conf['relnofollow']) $link['more'] .= ' rel="nofollow"';

        //output formatted
        $this->doc .= $this->_formatLink($link);
   }

    /**
     * Just print local links
     *
     * @fixme add image handling
     */
    function locallink($hash, $name = NULL){
        global $ID;
        $name  = $this->_getLinkTitle($name, $hash, $isImage);
        $hash  = $this->_headerToLink($hash);
        $title = $ID.' &crarr;';
        $this->doc .= '<a href="#'.$hash.'" title="'.$title.'" class="wikilink1">';
        $this->doc .= $name;
        $this->doc .= '</a>';
    }

    /**
     * InterWiki links
     */
    function interwikilink($match, $name = NULL, $wikiName, $wikiUri) {
        global $conf;
        $link = array();
        $link['target'] = $conf['target']['interwiki'];
        $link['pre']    = '';
        $link['suf']    = '';
        $link['more']   = '';
        $link['name']   = $this->_getLinkTitle($name, $wikiUri, $isImage);

        //get interwiki URL
        $url = $this->_resolveInterWiki($wikiName,$wikiUri);

        if ( !$isImage ) {
            $class = preg_replace('/[^_\-a-z0-9]+/i','_',$wikiName);
            $link['class'] = "interwiki iw_$class";
        } else {
            $link['class'] = 'media';
        }

        //do we stay at the same server? Use local target
        if( strpos($url,DOKU_URL) === 0 ){
            $link['target'] = $conf['target']['wiki'];
        }

        $link['url'] = $url;
        $link['title'] = htmlspecialchars($link['url']);

        //output formatted
        $this->doc .= $this->_formatLink($link);
    }

    /**
     * Just print WindowsShare links
     *
     * @fixme add image handling
     */
    function windowssharelink($url, $name = NULL) {
        global $conf;
        global $lang;
        //simple setup
        $link['target'] = $conf['target']['windows'];
        $link['pre']    = '';
        $link['suf']   = '';
        $link['style']  = '';

        $link['name'] = $this->_getLinkTitle($name, $url, $isImage);
        if ( !$isImage ) {
            $link['class'] = 'windows';
        } else {
            $link['class'] = 'media';
        }


        $link['title'] = $this->_xmlEntities($url);
        $url = str_replace('\\','/',$url);
        $url = 'file:///'.$url;
        $link['url'] = $url;

        //output formatted
        $this->doc .= $this->_formatLink($link);
    }

    /**
     * Just print email links
     *
     * @fixme add image handling
     */
    function emaillink($address, $name = NULL) {
        global $conf;
        //simple setup
        $link = array();
        $link['target'] = '';
        $link['pre']    = '';
        $link['suf']   = '';
        $link['style']  = '';
        $link['more']   = '';

        $name = $this->_getLinkTitle($name, '', $isImage);
        if ( !$isImage ) {
            $link['class']='mail';
        } else {
            $link['class']='media';
        }

        $address = $this->_xmlEntities($address);
        $address = obfuscate($address);
        $title   = $address;

        if(empty($name)){
            $name = $address;
        }

        if($conf['mailguard'] == 'visible') $address = rawurlencode($address);

        $link['url']   = 'mailto:'.$address;
        $link['name']  = $name;
        $link['title'] = $title;

        //output formatted
        $this->doc .= $this->_formatLink($link);
    }

    /**
     * Construct a title and handle images in titles
     *
     * @author Harry Fuecks <hfuecks@gmail.com>
     */
    function _getLinkTitle($title, $default, & $isImage, $id=null) {
        global $conf;

        $isImage = false;
        if ( is_array($title) ) {
            $isImage = true;
            return $this->_imageTitle($title);
        } elseif ( is_null($title) || trim($title)=='') {
            if (useHeading($linktype) && $id) {
                $heading = p_get_first_heading($id);
                if ($heading) {
                    return $this->_xmlEntities($heading);
                }
            }
            return $this->_xmlEntities($default);
        } else {
            return $this->_xmlEntities($title);
        }
    }

    function _xmlEntities($value) {
        //$value = $this->_latexElements($value);
        return htmlspecialchars($value,ENT_QUOTES,'UTF-8');
    }

    function _ttEntities($value) {
        global $symbols;
        return str_ireplace($symbols, ' (Invalid character) ', $value);
    }

    function rss ($url,$params){
        global $lang;
        global $conf;

        require_once(DOKU_INC.'inc/FeedParser.php');
        $feed = new FeedParser();
        $feed->feed_url($url);

        //disable warning while fetching
        if (!defined('DOKU_E_LEVEL')) { $elvl = error_reporting(E_ERROR); }
        $rc = $feed->init();
        if (!defined('DOKU_E_LEVEL')) { error_reporting($elvl); }

        //decide on start and end
        if($params['reverse']){
            $mod = -1;
            $start = $feed->get_item_quantity()-1;
            $end   = $start - ($params['max']);
            $end   = ($end < -1) ? -1 : $end;
        }else{
            $mod   = 1;
            $start = 0;
            $end   = $feed->get_item_quantity();
            $end   = ($end > $params['max']) ? $params['max'] : $end;;
        }

        $this->listu_open();
        if($rc){
            for ($x = $start; $x != $end; $x += $mod) {
                $item = $feed->get_item($x);
                $this->listitem_open(0);
                $this->listcontent_open();
                $this->externallink($item->get_permalink(),
                                    $item->get_title());
                if($params['author']){
                    $author = $item->get_author(0);
                    if($author){
                        $name = $author->get_name();
                        if(!$name) $name = $author->get_email();
                        if($name) $this->cdata(' '.$lang['by'].' '.$name);
                    }
                }
                if($params['date']){
                    $this->cdata(' ('.$item->get_date($conf['dformat']).')');
                }
                if($params['details']){
                    $this->cdata(strip_tags($item->get_description()));
                }
                $this->listcontent_close();
                $this->listitem_close();
            }
        }else{
            $this->listitem_open(0);
            $this->listcontent_open();
            $this->emphasis_open();
            $this->cdata($lang['rssfailed']);
            $this->emphasis_close();
            $this->externallink($url);
            $this->listcontent_close();
            $this->listitem_close();
        }
        $this->listu_close();
    }


/*************************************
 * 				UTILS				 *
**************************************/


    /**
     * Build a link
     *
     * Assembles all parts defined in $link returns HTML for the link
     *
     * @author Andreas Gohr <andi@splitbrain.org>
     */
    function _formatLink($link){
        //make sure the url is XHTML compliant (skip mailto)
        if(substr($link['url'],0,7) != 'mailto:'){
            $link['url'] = str_replace('&','&amp;',$link['url']);
            $link['url'] = str_replace('&amp;amp;','&amp;',$link['url']);
        }
        //remove double encodings in titles
        $link['title'] = str_replace('&amp;amp;','&amp;',$link['title']);

        // be sure there are no bad chars in url or title
        // (we can't do this for name because it can contain an img tag)
        $link['url']   = strtr($link['url'],array('>'=>'%3E','<'=>'%3C','"'=>'%22'));
        $link['title'] = strtr($link['title'],array('>'=>'&gt;','<'=>'&lt;','"'=>'&quot;'));

        $ret  = '';
        $ret .= $link['pre'];
        $ret .= '<a href="'.$link['url'].'"';
        if(!empty($link['class']))  $ret .= ' class="'.$link['class'].'"';
        if(!empty($link['target'])) $ret .= ' target="'.$link['target'].'"';
        if(!empty($link['title']))  $ret .= ' title="'.$link['title'].'"';
        if(!empty($link['style']))  $ret .= ' style="'.$link['style'].'"';
        if(!empty($link['rel']))    $ret .= ' rel="'.$link['rel'].'"';
        if(!empty($link['more']))   $ret .= ' '.$link['more'];
        $ret .= '>';
        $ret .= $link['name'];
        $ret .= '</a>';
        $ret .= $link['suf'];
        return $ret;
    }

    /**
     * Renders internal and external media
     *
     * @author Andreas Gohr <andi@splitbrain.org>
     */
    function _media ($src, $title=NULL, $align=NULL, $width=NULL,
                      $height=NULL, $cache=NULL, $render = true) {

        static $documents = array('application/vnd.oasis.opendocument.text',
                        'application/vnd.oasis.opendocument.spreadsheet',
                        'application/vnd.oasis.opendocument.presentation',
                        'application/vnd.oasis.opendocument.graphics',
                        'application/vnd.ms-excel',
                        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                        'application/vnd.ms-powerpoint',
                        'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                        'application/msword',
                        'application/vnd.openxmlformats-officedocument.wordprocessingml.document'

        );
        $ret = '';
        $path = '';
        if ($_SESSION['activities']){
            $path = '../';
        }
        //attach url media file
        array_push($_SESSION['media_files'], $src);

        list($ext,$mime,$dl) = mimetype($src);
        if(substr($mime,0,5) == 'image'){
            $icon = FALSE;
            if ($width || $height){
                $icon = (($width && $width < 49) || ($height && $height < 49));
            }
            $imgb = (!$icon && !$this->table && $_SESSION['export_html']);
            // first get the $title
            if (!is_null($title)) {
                $title  = $this->_xmlEntities($title);
            }elseif($ext == 'jpg' || $ext == 'jpeg'){
                //try to use the caption from IPTC/EXIF
                require_once(DOKU_INC.'inc/JpegMeta.php');
                $jpeg =new JpegMeta(mediaFN($src));
                if($jpeg !== false) $cap = $jpeg->getTitle();
                if($cap){
                    $title = $this->_xmlEntities($cap);
                }
            }
            if (!$render) {
                // if the picture is not supposed to be rendered
                // return the title of the picture
                if (!$title) {
                    // just show the sourcename
                    $title = $this->_xmlEntities(basename(noNS($src)));
                }
                return $title;
            }
            if ($_SESSION['figure']){
                $ret .= '<figure>'.DOKU_LF;
                $figtitle = '<span class="figuretitle">Figura</span>'.$_SESSION['fig_title'];
                $ret .= '<figcaption>'.$figtitle.'</figcaption>';
            }elseif($_SESSION['iocelem']){
                $ret .= '<div class="imgelem">'.DOKU_LF;
            }elseif($imgb){
                $ret .= '<div class="iocfigurec">'.DOKU_LF;
                $ret .= '<ul>'.DOKU_LF;
                $ret .= '<li>'.DOKU_LF;
            }
            //add image tag
            if ($_SESSION['export_html']){
                $ret .= '<img src="'.$path.'media/'.basename(str_replace(':', '/', $src)).'"';
            }else{
                $ret .= '<img src="'.ml($src,array('w'=>$width,'h'=>$height,'cache'=>$cache)).'"';
            }
            if($this->table && $width){
                $ret .= ' width="'.$width.'"';
            }

            if (!$icon && !$_SESSION['figure'] && !$_SESSION['iocelem'] && !$this->table){
                $ret .= ' class="imgB"';
            }

            // make left/right alignment for no-CSS view work (feeds)
            if($align == 'right') $ret .= ' align="right"';
            if($align == 'left')  $ret .= ' align="left"';

            if ($title) {
                $ret .= ' title="' . $title . '"';
                $ret .= ' alt="'   . $title .'"';
            }else{
                $ret .= ' alt=""';
            }

            $ret .= ' />';
            if ($_SESSION['figure']){
                $ret .= '</figure>'.DOKU_LF;
            }elseif($_SESSION['iocelem']){
                $ret .= '</div>'.DOKU_LF;
            }elseif($imgb){
                $ret .= '</li>';
                if ($title) {
                    $title = preg_replace('/\/[+-]?\d+$/', '', $title);
                    $ret .= '<li><small>'.$title.'</small></li>'.DOKU_LF;
                }
                $ret .= '</ul>';
                $ret .= '</div>'.DOKU_LF;
            }

        }elseif($mime == 'application/x-shockwave-flash'){
            if (!$render) {
                // if the flash is not supposed to be rendered
                // return the title of the flash
                if (!$title) {
                    // just show the sourcename
                    $title = basename(noNS($src));
                }
                return $this->_xmlEntities($title);
            }

            $att = array();
            $att['class'] = "media$align";
            if($align == 'right') $att['align'] = 'right';
            if($align == 'left')  $att['align'] = 'left';
            $src = $path.'media/'.basename(str_replace(':', '/', $src));
            $ret .= '<div class="mediaflash">';
            $ret .= html_flashobject($src,$width,$height,
                                    array('quality' => 'high',
                                        "allowFullScreen" => "true"),
                                    null,
                                    $att,
                                    $this->_xmlEntities($title));
            $ret .= '</div>';
        }elseif($dl){
            resolve_mediaid(getNS($src),$src,$exists);
            if ($exists){
                $filesize = filesize(mediaFN($src));
                $filesize = ' ( '.filesize_h($filesize) .' )';
            }
            $filename = basename(str_replace(':', '/', $src));
            // well at least we have a title to display
            if (!is_null($title) && !empty($title)) {
                $title  = $this->_xmlEntities($title);
            }else{
                $title = $filename;
            }
            $src = $path.'media/'.$filename;
            $ret .= '<div class="mediaf file'.$ext.'">';
            $ret .= '<div class="mediacontent">';
            $ret .= '<a href="'.$path.'media/'.basename(str_replace(':', '/', $src)).'">'.$title.'</a>'.
                    '<span>'.$filesize.'</span>';
            $ret .= '</div>';
            $ret .= '</div>';
        }elseif($title){
            // well at least we have a title to display
            $ret .= $this->_xmlEntities($title);
        }else{
            // just show the sourcename
            $ret .= $this->_xmlEntities(basename(noNS($src)));
        }

        return $ret;
    }

    function p_iocxhtml_cached_geshi($code, $language, $wrapper='pre') {
        global $conf, $config_cascade;
        $language = strtolower($language);

        // remove any leading or trailing blank lines
        $code = preg_replace('/^\s*?\n|\s*?\n$/','',$code);

        $cache = getCacheName($language.$code,".code");
        $ctime = @filemtime($cache);
        if($ctime && !$_REQUEST['purge'] &&
        $ctime > filemtime(DOKU_INC.'inc/geshi.php') &&                 // geshi changed
        $ctime > @filemtime(DOKU_INC.'inc/geshi/'.$language.'.php') &&  // language syntax definition changed
        $ctime > filemtime(reset($config_cascade['main']['default']))){
            // dokuwiki changed
            $highlighted_code = io_readFile($cache, false);

        } else {

            $geshi = new GeSHi($code, $language, DOKU_INC . 'inc/geshi');
            $geshi->set_encoding('utf-8');
            $geshi->enable_classes();
            $geshi->enable_line_numbers(GESHI_NORMAL_LINE_NUMBERS);
            $geshi->set_line_style('background: #fcfcfc;');
            $geshi->set_header_type(GESHI_HEADER_PRE);
            $geshi->set_link_target($conf['target']['extern']);

            // remove GeSHi's wrapper element (we'll replace it with our own later)
            // we need to use a GeSHi wrapper to avoid <BR> throughout the highlighted text
            $highlighted_code = trim(preg_replace('!^<pre[^>]*>|</pre>$!','',$geshi->parse_code()),"\n\r");
            io_saveFile($cache,$highlighted_code);
        }

        // add a wrapper element if required
        if ($wrapper) {
            return "<$wrapper class=\"code $language\">$highlighted_code</$wrapper>";
        } else {
            return $highlighted_code;
        }
    }
}
