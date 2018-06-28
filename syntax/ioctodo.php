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
            'sintax' => '[TODO: text]',
            'url'  => 'http://ioc.gencat.cat/'
        );
    }

    function getType(){
        return 'container'; //tipo de sintaxis (container,substition,formatting,protected,paragraphs)
    }

    function getPType(){
        return 'stack';  //tipo de párrafo (stack, block, normal)
    }

    function getSort(){
        return 40; //dokuwiki has 320 priority
    }

    /**
     * Connect pattern to lexer
     */
    function connectTo($mode) {
        $this->Lexer->addEntryPattern("(?:\[TODO:).*?(?=.*?\])", $mode, 'plugin_iocexportl_ioctodo');
    }

    function postConnect() {
        $this->Lexer->addExitPattern("\]", 'plugin_iocexportl_ioctodo');
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
        switch ($mode) {
            case 'ioccounter':
                $this->renderCounter($mode, $renderer, $data);
                break;
            case 'xhtml':
                $this->renderWiki($renderer, $data);
                break;
            case 'iocxhtml':
                $this->renderHtmlExport($mode, $renderer, $data);
                break;
            case 'iocexportl':
                $this->renderPdfExport($mode, $renderer, $data);
                break;
            default:
                return FALSE;
        }
        return TRUE;
    }

    function renderWiki(&$renderer, $data) {
        list ($state, $text) = $data;
        switch ($state) {
            case DOKU_LEXER_ENTER :
                $renderer->doc .= '<div style="background-color:yellow;">';
                break;
            case DOKU_LEXER_UNMATCHED:
                $renderer->doc .= str_replace("\\\\", "<br>", $text);
                break;
            case DOKU_LEXER_EXIT :
                $renderer->doc .= "</div>\n";
                break;
        }
    }

    function renderPdfExport($mode, &$renderer, $data) {
    }

    function renderHtmlExport($mode, &$renderer, $data) {
    }

    function renderCounter($mode, &$renderer, $data) {
    }

}
