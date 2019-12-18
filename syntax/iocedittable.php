<?php
/**
 * Plugin iocedittable: etiqueta <edittable>
 * @culpable Rafael
 * @Sintax: <edittable></edittable>
*/
if (!defined('DOKU_INC')) die();
if (!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN', DOKU_INC.'lib/plugins/');
if (!defined('DOKU_PLUGIN_TEMPLATES')) define('DOKU_PLUGIN_TEMPLATES', DOKU_PLUGIN.'iocexportl/templates/');

require_once(DOKU_PLUGIN.'syntax.php');
require_once(DOKU_PLUGIN.'iocexportl/lib/renderlib.php');

class syntax_plugin_iocexportl_iocedittable extends DokuWiki_Syntax_Plugin {

    function getInfo(){
        return array(
            'name' => 'IOC <edittable> syntax plugin',
            'desc' => 'Plugin to parse <edittable> tag',
            'sintax' => '<edittable></edittable>',
            'url'  => 'http://ioc.gencat.cat/'
        );
    }

    function getType(){
        return 'formatting'; //tipo de sintaxis (container,substition,formatting,protected,paragraphs)
    }

    function getPType(){
        return 'block';  //tipo de párrafo (stack, block, normal)
    }

    function getSort(){
        return 40; //dokuwiki has 320 priority
    }

    /**
     * Connect pattern to lexer
     */
    function connectTo($mode) {
        $this->Lexer->addEntryPattern("<edittable>(?=.*?</edittable>)", $mode, 'plugin_iocexportl_iocedittable');
    }

    function postConnect() {
        $this->Lexer->addExitPattern("</edittable>", 'plugin_iocexportl_iocedittable');
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
        list ($state, $text) = $data;
        switch ($mode) {
            case 'ioccounter':
                $this->renderCounter($mode, $renderer, $state, $text);
                break;
                // ALERTA! la taula no es renderitza amb això perque no es fa el parse de la taula, només s'afegeix un div al voltant
//            case 'xhtml':
//                $this->renderWiki($renderer, $state, $text);
//                break;
            case 'wikiiocmodel_psdom':
                $this->renderPsdomExport($mode, $renderer, $state, $text);
                break;
            case 'xhtml':
            case 'iocxhtml':
            case 'wikiiocmodel_ptxhtml':
                $this->renderHtmlExport($mode, $renderer, $state, $text);
                break;
            case 'iocexportl':
                $this->renderPdfExport($mode, $renderer, $state, $text);
                break;
            default:
//                // Alerta, aquest es al que arriba quan es carrega la visualització
//                $this->renderHtmlExport('xhtml', $renderer, $state, $text);
//                break;
                return FALSE;
        }
        return TRUE;
    }

    function renderWiki(&$renderer, $state, $text) {
        switch ($state) {
            case DOKU_LEXER_ENTER :
                $renderer->doc .= '<div>';
                break;
            case DOKU_LEXER_UNMATCHED:
                $renderer->doc .= str_replace("\\\\", "<br>", $text);
                break;
            case DOKU_LEXER_EXIT :
                $renderer->doc .= "</div>\n";
                break;
        }
    }

    function renderPdfExport($mode, &$renderer, $state, $text) {
        switch ($state) {
            case DOKU_LEXER_ENTER:
                break;
            case DOKU_LEXER_UNMATCHED:
                $instructions = get_latex_instructions($text);
                $renderer->doc .= p_latex_render($mode, $instructions, $info);
                break;
            case DOKU_LEXER_EXIT:
                break;
        }
    }

    function renderHtmlExport($mode, &$renderer, $state, $text) {
        switch ($state) {
            case DOKU_LEXER_ENTER :
                break;
            case DOKU_LEXER_UNMATCHED :
                $instructions = p_get_instructions($text);
                $renderer->doc .= p_render($mode, $instructions, $info);
                break;
            case DOKU_LEXER_EXIT :
                break;
        }
    }

    function renderPsdomExport($mode, &$renderer, $state, $text) {
        switch ($state) {
            case DOKU_LEXER_ENTER :
                $node = new SpecialBlockNodeDoc(SpecialBlockNodeDoc::EDITTABLE_TYPE);
                $renderer->getCurrentNode()->addContent($node);
                $renderer->setCurrentNode($node);
                break;
            case DOKU_LEXER_UNMATCHED :
                $instructions = p_get_instructions($text);
                //delete document_start and document_end instructions
                if ($instructions[0][0] == "document_start") {
                    array_shift($instructions);
                    array_pop($instructions);
                }
                //delete p_open and p_close instructions
                if ($instructions[0][0] == "p_open") {
                    array_shift($instructions);
                    array_pop($instructions);
                }
                foreach ( $instructions as $instruction ) {
                    call_user_func_array(array(&$renderer, $instruction[0]),$instruction[1]);
                }
                break;
            case DOKU_LEXER_EXIT :
                break;
        }
    }

    function renderCounter($mode, &$renderer, $state, $text) {
        switch ($state) {
            case DOKU_LEXER_ENTER:
                break;
            case DOKU_LEXER_UNMATCHED:
                $instructions = get_latex_instructions($text);
                $renderer->doc .= p_latex_render($mode, $instructions, $info);
                break;
            case DOKU_LEXER_EXIT:
                break;
        }
    }

}
