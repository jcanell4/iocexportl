<?php
/**
 * Figure Syntax Plugin
 * @author     Marc Català <mcatala@ioc.cat>
 * 	::figure:id
  	  :title:
  	  :footer:
  	  :copyright:
  	  :license:
	:::
 */
if(!defined('DOKU_INC')) die();
if(!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');
require_once(DOKU_PLUGIN.'syntax.php');
require_once(DOKU_PLUGIN.'iocexportl/lib/renderlib.php');

class syntax_plugin_iocexportl_iocfigure extends DokuWiki_Syntax_Plugin {

    var $footer;

    function getInfo(){
        return array(
            'author' => 'Marc Català',
            'email'  => 'mcatala@ioc.cat',
            'date'   => '2011-03-17',
            'name'   => 'IOC figure Plugin',
            'desc'   => 'Plugin to parse figure tags',
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
        $this->Lexer->addEntryPattern('^::figure:.*?\n(?=.*?\n:::)', $mode, 'plugin_iocexportl_iocfigure');
    }

    function postConnect() {
        $this->Lexer->addExitPattern('^:::', 'plugin_iocexportl_iocfigure');
    }

    /**
     * Handle the match
     */
    function handle($match, $state, $pos, Doku_Handler $handler){
        $matches = array();
        $id = '';
        $params = array();
        switch ($state) {
            case DOKU_LEXER_ENTER :
                if (preg_match('/::figure:(.*?$)/', $match, $matches)){
                    $id = trim($matches[1]);
                    if (strpos($id,"_") !== FALSE) {
                        //throw new Exception("ERROR: No es pot generar un arxiu PDF si el ID de la figura conté el caracter '_'");
                    }
                }
                break;
            case DOKU_LEXER_UNMATCHED :
                preg_match_all('/\s{2}:(\w+):(.*?)\n/', $match, $matches, PREG_SET_ORDER);
                foreach($matches as $m){
                    $params[$m[1]] = $m[2];
                }
                $match = preg_replace('/\s{2}:\w+:.*?\n/', '',  $match);
                break;
            case DOKU_LEXER_EXIT :
                break;
        }
        return array($state, $match, $id, $params);
    }

    /**
     * Create output
     */
    function render($mode, Doku_Renderer $renderer, $data) {
        if ($mode === 'wikiiocmodel_psdom'){
            list ($state, $text, $id, $params) = $data;
            switch ($state) {
                case DOKU_LEXER_ENTER :
                    $renderer->setTableTypes("");
                    $id = trim($id);
                    $node = new FigureFrame(FigureFrame::FRAME_TYPE_FIGURE, $id);
                    $renderer->getCurrentNode()->addContent($node);
                    $renderer->setCurrentNode($node);
                    break;
                case DOKU_LEXER_UNMATCHED:
                    if (!empty($params)) {
                        $node = $renderer->getCurrentNode();
                        $node->setNodeParams($params['title'], $params['footer'], $renderer->isBorderTypeTable());
                    }
                    $instructions = get_latex_instructions($text);
                    //delete document_start and document_end instructions
                    if ($instructions[0][0] === "document_start") {
                        array_shift($instructions);
                        array_pop($instructions);
                    }
                    // Loop through the instructions
                    foreach ( $instructions as $instruction ) {
                        // Execute the callback against the Renderer
                        call_user_func_array(array(&$renderer, $instruction[0]),$instruction[1]);
                    }
                    break;
                case DOKU_LEXER_EXIT :
                    $renderer->setCurrentNode($renderer->getCurrentNode()->getOwner());
                    $renderer->setTableTypes("");
                    break;
            }
            return TRUE;
        }
        elseif ($mode === 'iocxhtml' || $mode === 'wikiiocmodel_ptxhtml'){
            list ($state, $text, $id, $params) = $data;
            switch ($state) {
                case DOKU_LEXER_ENTER :
                    $id = trim($id);                    
                    $_SESSION['figure_references'][]= $id;
                    $renderer->doc .= '<div class="iocfigure">';
                    $renderer->doc .= '<a name="'.$id.'"></a>';
                    break;
                case DOKU_LEXER_UNMATCHED :
                    if (isset($params['title'])){
                        $instructions = get_latex_instructions($params['title']);
                        $_SESSION['fig_title'] = preg_replace('/(<p>)(.*?)(<\/p>)/s', '$2', p_latex_render($mode, $instructions, $info));
                    }
                    if (isset($params['description'])){
                        $instructions = get_latex_instructions($params['description']);
                        $_SESSION['fig_description'] = trim(preg_replace('/(<p>)(.*?)(<\/p>)/s', '$2', p_latex_render($mode, $instructions, $info)));
                    }
                    $_SESSION['figure'] = TRUE;
                    $instructions = get_latex_instructions($text);
                    //delete document_start and document_end instructions
                    if ($instructions[0][0] === "document_start") {
                        array_shift($instructions);
                        array_pop($instructions);
                    }
                    //delete p_open and p_close instructions
                    if ($instructions[0][0] === "p_open") {
                        array_shift($instructions);
                        array_pop($instructions);
                    }
                    $renderer->doc .= p_latex_render($mode, $instructions, $info);
                    $_SESSION['figure'] = FALSE;
                    $_SESSION['fig_title'] = '';
                    $_SESSION['fig_description'] = "";
                    if (isset($params['footer'])){
                        $renderer->doc .= '<div class="footfigure">'.$params['footer'].'</div>';
                    }
                    break;
                case DOKU_LEXER_EXIT :
                    $renderer->doc .= '</div>';
                    break;
            }
            return TRUE;
        }
        elseif ($mode === 'ioccounter'){
            list ($state, $text, $id, $params) = $data;
            switch ($state) {
                case DOKU_LEXER_ENTER : break;
                case DOKU_LEXER_UNMATCHED :
                    $_SESSION['figure'] = TRUE;
                    $renderer->doc .= (isset($params['title']))?$params['title']:'';
                    $renderer->doc .= (isset($params['footer']))?$params['footer']:'';
                    $instructions = get_latex_instructions($text);
                    $renderer->doc .= p_latex_render($mode, $instructions, $info);
                    $_SESSION['figure'] = FALSE;
                    break;
                case DOKU_LEXER_EXIT :
                    break;
            }
            return TRUE;
        }
        elseif ($mode === 'iocexportl'){
            list ($state, $text, $id, $params) = $data;
            switch ($state) {
                case DOKU_LEXER_ENTER :
                    $_SESSION['figlabel'] = trim($renderer->_xmlEntities($id));
                    break;
                case DOKU_LEXER_UNMATCHED :
                    $_SESSION['figure'] = TRUE;
                    $_SESSION['figtitle'] = (isset($params['title']))?$params['title']:'';
                    $_SESSION['figlarge'] = (isset($params['large']));
                    //Transform quotes
                    $_SESSION['figtitle'] = preg_replace('/(")([^"]+)(")/', '``$2\'\'', $_SESSION['figtitle']);
                    $_SESSION['figfooter'] = (isset($params['footer']))?$params['footer']:'';
                    //Transform quotes
                    $_SESSION['figfooter'] = preg_replace('/(")([^"]+)(")/', '``$2\'\'', $_SESSION['figfooter']);
                    $instructions = get_latex_instructions($text);
                    //[Rafa] Este $new_mode hardcoded debe ser revisado, no solo aquí, si no en un ámbito más amplio
                    $new_mode = (get_class($renderer)==="renderer_plugin_wikiiocmodel_basiclatex") ? "wikiiocmodel_basiclatex" : $mode;
                    $renderer->doc .= p_latex_render($new_mode, $instructions, $info);
                    $_SESSION['figure'] = FALSE;
                    $_SESSION['figlabel'] = '';
                    $_SESSION['figtitle'] = '';
                    $_SESSION['figlarge'] = FALSE;
                    $_SESSION['figfooter'] = '';
                    break;
                case DOKU_LEXER_EXIT :
                    break;
            }
            return TRUE;
        }
        elseif ($mode === 'xhtml'){
            list ($state, $text, $id, $params) = $data;
            switch ($state) {
                case DOKU_LEXER_ENTER :
                    $renderer->doc .= '<div class="iocfigure">';
                    $renderer->doc .= '<div class="iocinfo">';
                    $renderer->doc .= '<a name="'.$id.'">';
                    $renderer->doc .= '<strong>ID:</strong> '.$id.'<br />';
                    $renderer->doc .= '</a>';
                    break;
                case DOKU_LEXER_UNMATCHED :
                    if (isset($params['title'])){
                        $instructions = p_get_instructions($params['title']);
                        $title = preg_replace('/(<p>)(.*?)(<\/p>)/s','<span>$2</span>', p_render($mode, $instructions, $info));
                        $renderer->doc .= '<strong>T&iacute;tol:</strong> '.$title.'<br />';
                    }
                    if (isset($params['footer'])){
                        $renderer->doc .= '<strong>Peu:</strong> '.$params['footer'].'<br />';
                    }
                    $renderer->doc .= '</div>';
                    $instructions = p_get_instructions($text);
                    $renderer->doc .= p_render($mode, $instructions, $info);
                    break;
                case DOKU_LEXER_EXIT :
                    $renderer->doc .= '</div>';
                    break;
            }
            return TRUE;
        }
        return FALSE;
    }
}
