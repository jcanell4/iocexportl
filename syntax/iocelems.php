<?php
/**
 * Plugin iocelems : add a IOC class to a content
 *
 * Syntax: ::elem:
 *          :key:value
 *          content
 *         :::
 *
 * @author     Marc Català <mcatala@ioc.cat>
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @version    27/04/2011
 */

if(!defined('DOKU_INC')) die();

if(!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');
require_once(DOKU_PLUGIN.'syntax.php');
require_once(DOKU_PLUGIN.'iocexportl/lib/renderlib.php');


class syntax_plugin_iocexportl_iocelems extends DokuWiki_Syntax_Plugin {

    /**
    * Get an associative array with plugin info.
    */
    function getInfo(){
        return array(
            'author' => 'Marc Català',
            'email'  => 'mcatala@ioc.cat',
            'date'   => '2011-01-27',
            'name'   => 'IOC elems Plugin',
            'desc'   => 'Plugin to parse style elems',
            'url'    => 'http://ioc.gencat.cat/',
        );
    }

    function getType(){
        return 'container';
    }

    function getPType(){
        return 'block';
    } //stack, block, normal

    function getSort(){
        return 514;
    }

    function accepts($mode) {
        $valid = array('plugin_iocexportl_ioctable', 'plugin_iocexportl_iocfigure', 'plugin_iocexportl_iocmedia');
        if (in_array($mode, $valid)){
            return true;
        }
        return parent::accepts($mode);
    }

    /**
     * Connect pattern to lexer
     */
    function connectTo($mode) {
        $this->Lexer->addEntryPattern('^::(?:text|note|reference|quote|important|example|include):.*?\n(?:\s{2}:\w+:.*?\n)*(?=.*?\n:::)',$mode,'plugin_iocexportl_iocelems');
    }
    function postConnect() {
        $this->Lexer->addExitPattern('^:::','plugin_iocexportl_iocelems');
    }

    /**
     * Handle the match
     */

    function handle($match, $state, $pos, Doku_Handler $handler){
        $matches = array();
        $params = array();
        switch ($state) {
            case DOKU_LEXER_ENTER :
                if (preg_match('/::(text|note|reference):(.*?)\n/', $match, $matches)){
                    $id = trim($matches[2]);
                    if (!empty($id)){
                        $params['id'] = $id;
                    }
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
        return array($state, $match, $params);
    }

   /**
    * output
    */
    function render($mode, Doku_Renderer $renderer, $indata) {
        $matches = array();
        if ($mode === 'ioccounter'){
            list($state, $text, $params) = $indata;
            switch ($state) {
                  case DOKU_LEXER_ENTER :
                        $renderer->doc .= (isset($params['title']))?$params['title']:'';
                        break;
                  case DOKU_LEXER_UNMATCHED :
                        $instructions = get_latex_instructions($text);
                        $renderer->doc .= p_latex_render($mode,$instructions,$info);
                        break;
                  case DOKU_LEXER_EXIT :
                        break;
            }
            return TRUE;
        }elseif ($mode === 'iocexportl'){
            list($state, $data, $params) = $indata;
            switch ($state) {
                case DOKU_LEXER_ENTER :
                    //avoid hyphenation
                    $renderer->doc .= '\hyphenpenalty=100000'.DOKU_LF;
                    preg_match('/::([^:]*):/', $data, $matches);
                    $type = (isset($matches[1])) ? $matches[1] : '';
                    //IMPORTANT
                    if ($type === 'important'){
                        $renderer->doc .= '\iocimportant{';
                    //TEXT
                    }elseif($type === 'text'){
                        $type = (isset($params['large'])) ? 'ioctextl' : 'ioctext';
                        $title = (isset($params['title'])) ? $renderer->_xmlEntities($params['title']) : '';
                        $offset = (isset($params['offset'])) ? '['.$params['offset'].'mm]' : '';
                        $renderer->doc .= '\\'.$type.$offset.'{'.$title.'}{';
                    //NOTE
                    }elseif($type === 'note'){
                        $offset = (isset($params['offset']))?'['.$params['offset'].'mm]':'';
                        $renderer->doc .= '\iocnote'.$offset.'{';
                    //QUOTE
                    }elseif($type === 'quote'){
                        $renderer->doc .= '\iocquote{';
                    //EXAMPLE
                    }elseif($type === 'example'){
                        $title = (isset($params['title']))?$renderer->_xmlEntities($params['title']):'';
                        $renderer->doc .= '\iocexample{'.$title.'}{';
                    //REFERENCE
                    }elseif($type === 'reference'){
                        $offset = (isset($params['offset']))?'['.$params['offset'].'mm]':'';
                        $renderer->doc .= '\iocreference'.$offset.'{';
                    //INCLUDE
                    }elseif($type === 'include'){
                        $renderer->doc .= '\iocinclude{';
                    }
                    $_SESSION['iocelem'] = (in_array($type, ["ioctextl","quote","important","example","include"], true)) ? 'textl' : TRUE;
                    break;
                case DOKU_LEXER_UNMATCHED :
                    $renderer->doc .= $this->_parse($data, $mode);
                    break;
                case DOKU_LEXER_EXIT :
                    $renderer->doc .= '}';
                    //allow hyphenation
                    $renderer->doc .= '\hyphenpenalty=1000'.DOKU_LF;
                    $_SESSION['iocelem'] = FALSE;
                    break;
            }
            return TRUE;
        }elseif ($mode === 'xhtml'){
            list($state, $data, $params) = $indata;
            switch ($state) {
                case DOKU_LEXER_ENTER :
                    preg_match('/::([^:]*):/', $data, $matches);
                    $type = (isset($matches[1])) ? $matches[1] : '';
                    //TEXT LARGE
                    if ($type === 'text' && isset($params['large'])){
                        $type = 'textl';
                    }
                    $idatt = (isset($params["id"])) ? " id={$params["id"]} " : "";
                    $renderer->doc .= "<div$idatt class=\"ioc$type\">";
                    $renderer->doc .= '<div class="ioccontent">';
                    $title = (isset($params['title'])) ? $renderer->_xmlEntities($params['title']) : '';
                    if (!empty($title)){
                        $renderer->doc .= '<p class="ioctitle">'.$title.'</p>';
                    }
                    if($type==="include"){
                        $_SESSION["include_element"]=true;
                    }
                    break;
                case DOKU_LEXER_UNMATCHED :
                    $_SESSION['iocelem'] = TRUE;   
                    if ($_SESSION["include_element"]){
                        $_SESSION["include_element"] = false;
                        $instructions = get_latex_instructions($data);
                        $this->renderUnmatchedIncludeInstructions($instructions, $renderer);
                    }else{
                        $instructions = p_get_instructions($data);
                        $renderer->doc .= p_render($mode, $instructions, $info);
                    }
                    $_SESSION['iocelem'] = FALSE;
                    break;
                case DOKU_LEXER_EXIT :
                    $renderer->doc .= '</div>';
                    $renderer->doc .= '</div>';
                    break;
            }
            return TRUE;
        }elseif ($mode === 'iocxhtml' || $mode === 'wikiiocmodel_ptxhtml'){
            list($state, $data, $params) = $indata;
            switch ($state) {
                case DOKU_LEXER_ENTER :
                    preg_match('/::([^:]*):/', $data, $matches);
                    $type = (isset($matches[1])) ? $matches[1] : '';
                    //TEXT LARGE
                    if ($type === 'text' && isset($params['large'])){
                        $type = 'textl';
                    }
                    $renderer->tmpData["type"] = $type;
                    if ($type == 'include') {
                        
                    }
                    $html = '<div class="ioc'.$type.'">';
                    $html .= '<div class="ioccontent">';
                    $title = (isset($params['title']))?$renderer->_xmlEntities($params['title']):'';
                    if (!empty($title)){
                        $html.= '<p class="ioctitle">'.$title.'</p>';
                    }
                    if($type == "include"){
                        $renderer->tmpData["include_element"]=true;
                        $renderer->tmpData["include_element_state"]=$state;
                        $renderer->doc .= $html;
                    }elseif (in_array($type, ["text","note","reference"], true)){
                        if(isset($params["id"])){                                
                            $renderer->currentBIocElemsType = renderer_plugin_wikiiocmodel_psdom::REFERRED_B_IOC_ELEMS_TYPE;
                            $renderer->tmpData["id"] = $params["id"];
                        }else{
                            $renderer->currentBIocElemsType = renderer_plugin_wikiiocmodel_psdom::UNREFERRED_B_IOC_ELEMS_TYPE;
                            $renderer->tmpData["id"]= count($renderer->bIocElems[$renderer->currentBIocElemsType]);
                            $renderer->tmpData["renderIocElems"] = FALSE;
                        }   
                        $renderer->storeCurrent();
                        $renderer->doc = $html;
                    }else{
                        $renderer->doc .= $html;
                        $renderer->openForContentB("iocelem");
                    } 
                    break;
                case DOKU_LEXER_UNMATCHED :
                    $_SESSION['iocelem'] = TRUE;
                    if ($renderer->tmpData["include_element_state"]== DOKU_LEXER_ENTER){
                        $renderer->tmpData["include_element_state"] = DOKU_LEXER_UNMATCHED;
                        $instructions = get_latex_instructions($data);
                        $this->renderUnmatchedIncludeInstructions($instructions, $renderer);
                    }else{
                        $instructions = p_get_instructions($data);
                        $html = p_latex_render($mode, $instructions, $info);
                        $renderer->doc .= $html;
                    }
                    $_SESSION['iocelem'] = FALSE;
                    break;
                case DOKU_LEXER_EXIT :
                    $html =  '</div>';
                    $html .= '</div>';
                    $type = $renderer->tmpData["type"];
                    if((!isset($type) || $type=="include") && $renderer->tmpData["include_element"]){
                        $renderer->tmpData["include_element"]=false;
                        $renderer->doc .= $html;                        
                    }elseif (in_array($type, ["text","note","reference"], true)) {
                        $renderer->doc .= $html;
                        $renderer->bIocElems[$renderer->currentBIocElemsType][$renderer->tmpData["id"]] = $renderer->doc;
                        $renderer->currentBIocElemsType = renderer_plugin_wikiiocmodel_psdom::UNEXISTENT_B_IOC_ELEMS_TYPE;
                        $renderer->tmpData["renderIocElems"]=TRUE;
                        $renderer->restoreCurrent();
                        unset($renderer->tmpData["id"]);
                    }else{
                        $renderer->doc .= $html;
                        $renderer->closeForContentB("iocelem");
                    }   
                    unset($renderer->tmpData["type"]);
                    break;
            }
            return TRUE;
       }elseif ($mode === 'wikiiocmodel_psdom'){
            list ($state, $data, $params) = $indata;
            switch ($state) {
                case DOKU_LEXER_ENTER :
                    preg_match('/::([^:]*):/', $data, $matches);
                    $type = (isset($matches[1])) ? $matches[1] : '';
                    //TEXT LARGE
                    if ($type === 'text' && isset($params['large'])){
                        $type = 'textl';
                    }
                    $renderer->tmpData["type"] = $type;
                    $title = (isset($params['title']))?$renderer->_xmlEntities($params['title']):'';
                    $offset = (isset($params['offset']))?$params['offset']:false;
                    $width = (isset($params['width']))?$params['width']:false;
                    $node = new IocElemNodeDoc($type, $title, $offset, $width, $renderer->actualLevel);
                    if (in_array($type, ["text","note","reference"], true)) {
                        if(isset($params["id"])){                                
                            $renderer->currentBIocElemsType = renderer_plugin_wikiiocmodel_psdom::REFERRED_B_IOC_ELEMS_TYPE;
                            $renderer->tmpData["id"] = $params["id"];
                        }else{
                            $renderer->currentBIocElemsType = renderer_plugin_wikiiocmodel_psdom::UNREFERRED_B_IOC_ELEMS_TYPE;
                            $renderer->tmpData["id"] = count($renderer->bIocElems[$renderer->currentBIocElemsType]);
                            $renderer->tmpData["renderIocElems"] = FALSE;
                        }   
                        $renderer->storeCurrent();
                        $renderer->setCurrentNode($node);
                    }elseif ($type === "include") {
                        $renderer->include++;
                        $renderer->storeCurrent();
                        $renderer->setCurrentNode($node);
                    }else{
                        $renderer->getCurrentNode()->addContent($node);
                        $renderer->setCurrentNode($node);
                        $renderer->openForContentB("iocelem");
                    }
                    break;
                case DOKU_LEXER_UNMATCHED:
                    $instructions = get_latex_instructions($data);
                    //delete document_start and document_end instructions
                    if ($instructions[0][0] === "document_start") {
                        array_shift($instructions);
                        array_pop($instructions);
                    }
                    // Loop through the instructions
                    foreach ( $instructions as $instruction ) {
                        // Execute the callback against the Renderer
                        call_user_func_array(array(&$renderer, $instruction[0]), $instruction[1]);
                    }
                    break;
                case DOKU_LEXER_EXIT :
                    if (in_array($renderer->tmpData["type"], ["text","note","reference"])) {
                        $renderer->bIocElems[$renderer->currentBIocElemsType][$renderer->tmpData["id"]] = $renderer->getCurrentNode();
                        $renderer->currentBIocElemsType = renderer_plugin_wikiiocmodel_psdom::UNEXISTENT_B_IOC_ELEMS_TYPE;
                        $renderer->tmpData["renderIocElems"] = TRUE;
                        $renderer->restoreCurrent();
                        unset($renderer->tmpData["id"]);
//                    }elseif ($renderer->tmpData["type"] === "include") {
                    }elseif ($renderer->include > 0) {
                        $renderer->include--;
                        $renderer->restoreCurrent();
                    }elseif ($renderer->getCurrentNode()->getOwner()){
                        $renderer->setCurrentNode($renderer->getCurrentNode()->getOwner());
                        $renderer->closeForContentB("iocelem");
                    }          
                    unset($renderer->tmpData["type"]);
                    break;
            }
            return TRUE;
        }
       return FALSE;
    }

    function _parse($text, $mode){
        $info = array();
        $instructions = get_latex_instructions($text);
        $text = p_latex_render($mode, $instructions, $info);
        return preg_replace('/(.*?)(\n*)$/', '$1', $text);
    }

    function updatLevel(&$instructions, $lastlevel) {
        $num = count($instructions);
        $level = $lastlevel;
        for ($i=0; $i<$num; $i++) {
            switch($instructions[$i][0]) {
                case 'document_start':
                case 'document_end':
//                case 'section_edit':
                    unset($instructions[$i]);
                    break;            
                case 'plugin':
                switch($instructions[$i][1][0]) {
                    case 'include_include':
                        $instructions[$i][1][1][4] = $level;
                        break;
                }
                break;
            case 'section_open':
                $level = $instructions[$i][1][0];
                break;
            }
        }
        return $lastlevel;
    }
    
    function renderUnmatchedIncludeInstructions($instructions, &$renderer){
        $aux = $renderer->doc;
        $renderer->doc = "";
        $lastlevel = $this->updatLevel($instructions, $renderer->lastlevel);
        $renderer->nest($instructions);
        $renderer->lastlevel = $lastlevel;
        $replaced = preg_replace('/\n\<\/div\>(?:\n\<\/section\>)?(.*)\<div class="level."\>\n/s', '$1', $renderer->doc);
        $aux .= $replaced;
        $renderer->doc = $aux;        
    }
}
