<?php
/**
 * Plugin ioctodo: etiqueta TODO: marca el texto en amarillo
 * @culpable Rafael
 * @Sintax: [TODO: texto]
*/
if (!defined('DOKU_INC')) die();
if (!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN', DOKU_INC.'lib/plugins/');
if (!defined('DOKU_PLUGIN_TEMPLATES')) define('DOKU_PLUGIN_TEMPLATES', DOKU_PLUGIN.'iocexportl/templates/');

require_once(DOKU_PLUGIN.'syntax.php');
require_once(DOKU_PLUGIN.'iocexportl/lib/renderlib.php');

class syntax_plugin_iocexportl_ioctodo extends DokuWiki_Syntax_Plugin {

    function getInfo(){
        return array(
            'name' => 'IOC TODO syntax plugin',
            'desc' => 'Plugin to parse TODO tag: mark yellow text background',
            'sintax' => '[##TODO: text##]',
            'url'  => 'http://ioc.gencat.cat/'
        );
    }
    function getType()  {return 'substition';}  //tipo de sintaxis (container,substition,formatting,protected,paragraphs)
    function getPType() {return 'normal';}      //tipo de párrafo (stack, block, normal)
    function getSort()  {return 40;}

    /**
     * Connect pattern to lexer
     */
    function connectTo($mode) {
        $this->Lexer->addEntryPattern("(?:\[##TODO\:).*?(?=.*?##\])", $mode, 'plugin_iocexportl_ioctodo');
    }

    function postConnect() {
        $this->Lexer->addExitPattern("##\]", 'plugin_iocexportl_ioctodo');
    }

    /**
     * Tratamiento de la estructura $match
     */
    function handle($match, $state, $pos, &$handler){
        return array($state, $match);
    }

   /**
    * output
    */
    function render($mode, &$renderer, $data) {
        $ret = TRUE;
        switch ($mode) {
            case 'wikiiocmodel_psdom':
            case 'ioccounter':
            case 'iocxhtml':
            case 'wikiiocmodel_ptxhtml':
            case 'iocexportl':
                break;
            case 'xhtml':
                $this->renderWiki($renderer, $data);
                break;
            default:
                $ret = FALSE;
        }
        return $ret;
    }

    function renderPsdom(&$renderer, $data) {
        list($state, $text) = $data;
        switch ($state) {
            case DOKU_LEXER_ENTER :
                $node = new SpecialBlockNodeDoc(SpecialBlockNodeDoc::PROTECTED_TYPE);
                $renderer->getCurrentNode()->addContent($node);
                $renderer->setCurrentNode($node);
                break;
            case DOKU_LEXER_UNMATCHED:
                $instructions = get_latex_instructions($text);
                //delete document_start and document_end instructions
                array_shift($instructions);
                array_pop($instructions);
                //delete p_open and p_close instructions
                array_shift($instructions);
                array_pop($instructions);
                foreach ( $instructions as $instruction ) {
                    call_user_func_array(array(&$renderer, $instruction[0]),$instruction[1]);
                }
                break;
            case DOKU_LEXER_EXIT:
                $renderer->setCurrentNode($renderer->getCurrentNode()->getOwner());
                break;
        }
    }

    function renderGeneral(&$renderer, $data) {
        list ($state, $text) = $data;
        if ($state === DOKU_LEXER_UNMATCHED) {
            $renderer->doc .= $text;
        }
    }

    function renderWiki(&$renderer, $data) {
        list ($state, $text) = $data;
        switch ($state) {
            case DOKU_LEXER_ENTER :
                $renderer->doc .= '<span class="ioctodogroc"><span class="ioctodoboldgroc">(TODO:</span></span>';
                break;
            case DOKU_LEXER_UNMATCHED:
                preg_match_all("/(\[\[EX[AE]MPLE).*?(EX[AE]MPLE\]\])/s", $text, $matches, PREG_OFFSET_CAPTURE);
                $pos = 0;
                for($i=0; $i<count($matches[0]);$i++){
                    $len = $matches[1][$i][1]-$pos;
                    $stexti = substr($text, $pos, $len);
                    $this->_renderHhtml($renderer, $stexti);
                    $pos += $len + 9;
                    $len = $matches[2][$i][1]-$pos;
                    $stexto = substr($text, $pos, $len);
                    $renderer->doc .= '<pre class="ioctodogroc">';
                    $renderer->doc .= $stexto;
                    $renderer->doc .= '</pre>';
                    $pos += $len + 9;
                }
                $stexti = substr($text, $pos, strlen($text)-$pos);
                $this->_renderHhtml($renderer, $stexti);
                break;
            case DOKU_LEXER_EXIT :
                $renderer->doc .= '<span class="ioctodogroc"><span class="ioctodoboldgroc">)</span>';
                $renderer->doc .= "</span>\n";
                break;
        }
    }
    
    private function _renderHhtml(&$renderer, $texti){
        if($texti){
            $instructions = p_get_instructions(str_replace("\\\\", "<br>", $texti));
            array_shift($instructions);
            array_shift($instructions);
            array_pop($instructions);
            array_pop($instructions);
            $renderer->doc .= '<span class="ioctodogroc">';        
            $renderer->doc .= p_render("xhtml", $instructions, $info);        
            $renderer->doc .= '</span>';        
        }
    }

}
