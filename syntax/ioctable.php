<?php
/**
 * Table Syntax Plugin
 * @author     Marc Català <mcatala@ioc.cat>
 * syntax
 * 	::table:id
   	  :title:
   	  :footer:
      :large: (bool)
	:::
 */

if(!defined('DOKU_INC')) define('DOKU_INC',realpath(dirname(__FILE__).'/../../').'/');
if(!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');
require_once(DOKU_PLUGIN.'syntax.php');
require_once(DOKU_PLUGIN.'iocexportl/lib/renderlib.php');

class syntax_plugin_iocexportl_ioctable extends DokuWiki_Syntax_Plugin {

    var $footer;
    var $id;
    var $type;
    var $vertical;
    /**
     * return some info
     */
    function getInfo(){
        return array(
            'author' => 'Marc Català',
            'email'  => 'mcatala@ioc.cat',
            'date'   => '2011-01-28',
            'name'   => 'IOC latex Plugin',
            'desc'   => 'Plugin to parse latex tags',
            'url'    => 'http://ioc.gencat.cat/',
        );
    }

    /**
     * What kind of syntax are we?
     */
    function getType(){
        return 'container';
    }

    /**
     * What about paragraphs?
     */
    function getPType(){
        return 'block';
    }

    /**
     * Where to sort in?
     */
    function getSort(){
        return 513;
    }

    /**
     * Connect pattern to lexer
     */
    function connectTo($mode) {
        $this->Lexer->addEntryPattern('^::(?:table|accounting):.*?\n(?=\S[^:].*?\n:::)', $mode, 'plugin_iocexportl_ioctable');
    }

    function postConnect() {
        $this->Lexer->addExitPattern('^:::', 'plugin_iocexportl_ioctable');
    }


    /**
     * Handle the match
     */
    function handle($match, $state, $pos, &$handler){
        $matches = array();
		$id = '';
		$params = array();
        switch ($state) {
            case DOKU_LEXER_ENTER :
                if (preg_match('/::(table|accounting):(.*?)\n/', $match, $matches)){
					$id = trim($matches[2]);
                }
                preg_match_all('/\s{2}:(\w+):(.*?)\n/', $match, $matches, PREG_SET_ORDER);
                foreach($matches as $m){
                    $params[$m[1]] = $m[2];
                }
                break;
            case DOKU_LEXER_UNMATCHED :
                break;
            case DOKU_LEXER_EXIT :
                break;
        }
        return array($state, $match, $id, $params);
    }

    /**
     * Create output
     */
    function render($mode, &$renderer, $data) {
        if ($mode === 'ioccounter'){
            list ($state, $text, $id, $params) = $data;
            switch ($state) {
                case DOKU_LEXER_ENTER :
                    $renderer->doc .= (isset($params['title']))?$params['title']:'';
                    $renderer->doc .= (isset($params['footer']))?$params['footer']:'';
                    break;
                case DOKU_LEXER_UNMATCHED :
                    $instructions = get_latex_instructions($text);
                    $renderer->doc .= p_latex_render($mode, $instructions, $info);
                    break;
                case DOKU_LEXER_EXIT :
                    break;
            }
            return TRUE;
        }elseif ($mode === 'iocexportl'){
            list ($state, $text, $id, $params) = $data;
            switch ($state) {
                case DOKU_LEXER_ENTER :
                    $_SESSION['table_id'] = trim($id);
                    preg_match('/::([^:]*):/', $text, $matches);
                    $this->type = (isset($matches[1]))?$matches[1]:'';
                    $_SESSION['accounting'] = ($this->type === 'accounting');
                    $this->vertical = (isset($params['vertical']))?$params['vertical']:FALSE;
                    $_SESSION['table_title'] = (isset($params['title']))?$params['title']:'';
                    //Transform quotes
                    $_SESSION['table_title'] = preg_replace('/(")([^"]+)(")/', '``$2\'\'', $_SESSION['table_title']);
                    $_SESSION['table_footer'] = (isset($params['footer']))?trim($renderer->_xmlEntities($params['footer'])):'';
                    if (!empty($_SESSION['table_footer'])){
                        $_SESSION['onemoreparsing'] = TRUE;
                    }
                    if (isset($params['large'])){
                        $renderer->doc .= '\checkoddpage\ifthenelse{\boolean{oddpage}}{}{\hspace*{-\marginparwidth}\hspace*{-11mm}}'.DOKU_LF;
                        $renderer->doc .= '\parbox[c]{\marginparwidth+\marginparsep}{'.DOKU_LF;
                        $_SESSION['table_large'] = TRUE;
                    }elseif (isset($params['small'])){
                        $_SESSION['table_small'] = TRUE;
                        $renderer->doc .= '\begin{SCtable}[1][h]'.DOKU_LF;
                    }elseif($this->vertical){
                        $renderer->doc .= '\begin{landscape}'.DOKU_LF;
                    }
                    if ($_SESSION['accounting']){
                        $renderer->doc .= '\begin{center}'.DOKU_LF;
                        $renderer->doc .= '\parbox[t]{\linewidth}{'.DOKU_LF;
                    }
                    if (isset($params['widths'])) {
                        $_SESSION['table_widths'] = explode(',', $params['widths']);
                    }
                    break;
                case DOKU_LEXER_UNMATCHED :
                    $instructions = get_latex_instructions($text);
                    $renderer->doc .= p_latex_render($mode, $instructions, $info);
                    break;
                case DOKU_LEXER_EXIT :
                    if ($_SESSION['accounting']){
                        $renderer->doc .= '}'.DOKU_LF;
                        $renderer->doc .= '\end{center}'.DOKU_LF;
                    }
                    if ($_SESSION['table_footer'] && $_SESSION['table_large']) {
                        $hspace = '[\textwidth+\marginparwidth+10mm]';
                        $renderer->doc .=  '\tablefooterlarge'.$hspace.'{'.$_SESSION['table_footer'].'}';
                    }
                    if ($_SESSION['table_large']){
                        $renderer->doc .= '}'.DOKU_LF;
                    }elseif ($this->vertical){
                        $renderer->doc .= '\end{landscape}'.DOKU_LF;
                    }elseif ($_SESSION['table_small']){
                        $renderer->doc .= '\end{SCtable}'.DOKU_LF;
                    }
                    if (!$_SESSION['table_large']){
                        $renderer->doc .= '\vspace{-2ex}';
                    }
                    $renderer->doc .= '\par'.DOKU_LF;
                    $_SESSION['table_id'] = '';
                    $_SESSION['table_title'] = '';
                    $_SESSION['table_footer'] = '';
                    $_SESSION['table_large'] = FALSE;
                    $_SESSION['table_small'] = FALSE;
                    $_SESSION['accounting'] = FALSE;
                    $_SESSION['table_widths'] = '';
                    $this->type = '';
                    break;
            }
            return TRUE;
        }elseif ($mode === 'xhtml'){
            list ($state, $text, $id, $params) = $data;
            switch ($state) {
                    case DOKU_LEXER_ENTER :
                        preg_match('/::([^:]*):/', $text, $matches);
                        $this->type = (isset($matches[1]))?$matches[1]:'';
                        $renderer->doc .= $this->_getDivClass($params['type']);
                        $renderer->doc .= '<div class="iocinfo">';
                        $renderer->doc .= '<a name="'.$id.'">';
                        $renderer->doc .= '<strong>ID:</strong> '.$id.'<br />';
                        $renderer->doc .= '</a>';
                        if (isset($params['title'])){
                            $instructions = p_get_instructions($params['title']);
                            $title = preg_replace('/(<p>)(.*?)(<\/p>)/s','<span>$2</span>',p_render($mode, $instructions, $info));
                            $renderer->doc .= '<strong>T&iacute;tol:</strong> '.$title.'<br />';
                        }
                        if (isset($params['footer'])){
                            $renderer->doc .= '<strong>Peu:</strong> '.$params['footer'].'<br />';
                        }
                        if (isset($params['widths'])){
                            $renderer->doc .= '<strong>Amplada columnes:</strong> '.$params['widths'].'<br />';
                        }
                        $renderer->doc .= '</div>';
                        break;
                    case DOKU_LEXER_UNMATCHED :
                        $instructions = p_get_instructions($text);
                        $renderer->doc .= p_render($mode, $instructions, $info);
                        break;
                    case DOKU_LEXER_EXIT :
                        $renderer->doc .= '</div>';
                        $this->type = '';
                        break;
                }
            return TRUE;
        }elseif ($mode === 'iocxhtml'){
            list ($state, $text, $id, $params) = $data;
            switch ($state) {
                    case DOKU_LEXER_ENTER :
                        preg_match('/::([^:]*):/', $text, $matches);
                        $this->type = (isset($matches[1]))?$matches[1]:'';
                        $renderer->doc .= $this->_getDivClass($params['type']);
                        $this->footer = (isset($params['footer']))?$params['footer']:'';
                        $renderer->doc .= '<div class="titletable"><a name="'.$id.'">';
                        $renderer->doc .= '<span>Taula</span>';
                        $renderer->doc .= '</a>';
                        if (isset($params['title'])){
                            $instructions = get_latex_instructions($params['title']);
                            $renderer->doc .= preg_replace('/(<p>)(.*?)(<\/p>)/s','$2',p_latex_render($mode, $instructions, $info));
                        }
                        $renderer->doc .= '</div>';
                        break;
                    case DOKU_LEXER_UNMATCHED :
                        $instructions = get_latex_instructions($text);
                        $renderer->doc .= p_latex_render($mode, $instructions, $info);
                        break;
                    case DOKU_LEXER_EXIT :
                        if (!empty($this->footer)){
                            $renderer->doc .= '<div class="foottable">'.$this->footer.'</div>';
                        }
                        $renderer->doc .= '</div>';
                        $this->type = '';
                        break;
                }
            return TRUE;
        }
        return FALSE;
    }
    
    function _getDivClass($type=NULL){
        if($this->type === 'table'){
            $divclass = '<div class="ioctable';
        }else{
            $divclass = '<div class="iocaccounting';
        }
        $type = str_replace(",", " ", $type);
        if(isset($type)){
            $divclass .= ' '. $type . '">';
        }else{
            $divclass .= '">';
        }
        return $divclass;        
    }
}
