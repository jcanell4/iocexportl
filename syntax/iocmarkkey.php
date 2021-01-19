<?php
/**
 * lang Syntax Plugin
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 */
if(!defined('DOKU_INC')) die();
if(!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');
require_once(DOKU_PLUGIN.'syntax.php');
require_once(DOKU_PLUGIN.'iocexportl/lib/renderlib.php');

class syntax_plugin_iocexportl_iocmarkkey extends DokuWiki_Syntax_Plugin {

   /**
    * Get an associative array with plugin info.
    */
    function getInfo(){
        return array(
            'author' => 'Josep Cañellas',
            'email'  => 'jcanell4@ioc.cat',
            'date'   => '2018-06-15',
            'name'   => 'IOC replace keys',
            'desc'   => 'Plugin to parse identifiable keys and replace them for content',
            'url'    => 'http://ioc.gencat.cat/');
    }
    function getType(){ return 'formatting'; }
    function getPType(){ return 'normal'; }    //'container','substition','protected','disabled','baseonly','formatting','paragraphs'
    function getSort() { return 40; }

    /**
     * Connect pattern to lexer
     */
    function connectTo($mode) {
        $this->Lexer->addSpecialPattern('@@[_|A-Z]+?@@', $mode, 'plugin_iocexportl_iocmarkkey');
    }

    /**
     * Handle the match
     */
    function handle($match, $state, $pos, Doku_Handler $handler){
        return array($state, $match);
    }

   /**
    * output
    */
    function render($mode, Doku_Renderer $renderer, $data) {
        list(, $text) = $data;

        switch ($mode) {
            case 'wikiiocmodel_psdom':
                $text = trim($text, "@");
                $renderer->getCurrentNode()->addContent(new TextNodeDoc(TextNodeDoc::PLAIN_TEXT_TYPE, $text));
                $ret = TRUE;
                break;
            case 'iocxhtml':
            case 'wikiiocmodel_ptxhtml':
                $text = trim($text, "@");
                $renderer->doc .= $text;
                $ret = TRUE;
                break;
            case 'ioccounter':
            case 'iocexportl':
                $renderer->doc .= $text;
                $ret = TRUE;
                break;
            case 'xhtml':
                $renderer->doc .= "<span class='iocmarkkey'>$text</span>";
                $ret = TRUE;
                break;
            default:
                $ret = FALSE;
        }
        return $ret;
    }

}
