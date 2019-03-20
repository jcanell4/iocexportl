<?php
/**
 * lang Syntax Plugin
 *
 * @author     Marc Català <mcatala@ioc.cat>
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 */
if(!defined('DOKU_INC')) die();  // must be run within Dokuwiki
if(!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');
require_once(DOKU_PLUGIN.'syntax.php');
require_once(DOKU_PLUGIN.'iocexportl/lib/renderlib.php');

class syntax_plugin_iocexportl_iocprotectedcontent extends DokuWiki_Syntax_Plugin {

    function getInfo(){
        return array(
            'author' => 'Josep Cañellas',
            'email'  => 'jcanell4@ioc.cat',
            'date'   => '2018-06-15',
            'name'   => 'IOC to protect content in the ACE editor',
            'desc'   => 'Plugin to parse identifiable keys and replace them for content',
            'url'    => 'http://ioc.gencat.cat/',        );
    }

    function getType() { return 'container'; }
    function getPType() { return 'stack'; }
    function getAllowedTypes() {
        return array('container','substition','protected','disabled','formatting','paragraphs');
    }
    function getSort() { return 40; }

    /**
     * Connect pattern to lexer
     */
    function connectTo($mode) {
        $this->Lexer->addEntryPattern(":###", $mode, 'plugin_iocexportl_iocprotectedcontent');
    }

    function postConnect() {
        $this->Lexer->addExitPattern('###:', 'plugin_iocexportl_iocprotectedcontent');
    }

    /**
     * Handle the match
     */
    function handle($match, $state, $pos, &$handler){
        return array($state, $match);
    }

   /**
    * output
    */
    function render($mode, &$renderer, $data) {
        if ($mode === 'wikiiocmodel_psdom') {
            $this->renderPsdom($renderer, $data);
            return TRUE;
        }elseif (strpos("ioccounter/iocexportl/iocxhtml/wikiiocmodel_ptxhtml", $mode) !== FALSE){
            $this->renderGeneral($renderer, $data);
            return TRUE;
        }elseif ($mode === 'xhtml'){
            $this->renderWiki($renderer, $data);
            return TRUE;
        }
        return FALSE;
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
                $renderer->doc .= "<div class='iocprotectedcontent'>\n";
                break;
            case DOKU_LEXER_UNMATCHED :
                $renderer->doc .= $text;
                break;
            case DOKU_LEXER_EXIT :
                $renderer->doc .= "</div>\n";
                break;
        }
    }
}
