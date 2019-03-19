<?php
/**
 * Latex Syntax Plugin
 * @author     Marc Català <mcatala@ioc.cat>
 */

if(!defined('DOKU_INC')) define('DOKU_INC',realpath(dirname(__FILE__).'/../../').'/');
if(!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');
require_once(DOKU_PLUGIN.'syntax.php');
require_once(DOKU_PLUGIN.'iocexportl/lib/renderlib.php');


class syntax_plugin_iocexportl_iocverd extends DokuWiki_Syntax_Plugin {

    function getInfo(){
        return array(
            'author' => 'Rafa',
            'name'   => 'IOC verd tags Plugin',
            'desc'   => 'Plugin to parse <verd> tags'
        );
    }
    // tipus de sintaxi: 'container', 'baseonly', 'formatting', 'substition', 'protected', 'disabled', 'paragraphs'
    function getType(){
        return 'formatting';
    }
    // tipus de paràgraf: 'normal', 'block', 'stack'
    function getPType(){
        return 'normal';
    }
    // ordre (invers) de prioritat en la seqüencia d'anàlisi
    function getSort(){
        return 520;
    }
    // array de tipos permitidos: 'container', 'baseonly', 'formatting', 'substition', 'protected', 'disabled', 'paragraphs'
    function getAllowedTypes(){
        return array('formatting');
    }

    /**
     * Connect pattern to lexer
     */
    function connectTo($mode) {
        $this->Lexer->addEntryPattern('<verd>(?=.*?</verd>)', $mode, 'plugin_iocexportl_iocverd');
    }

    function postConnect() {
        $this->Lexer->addExitPattern('</verd>', 'plugin_iocexportl_iocverd');
    }

    /**
     * Handle the match
     */
    function handle($match, $state, $pos, &$handler){
        return array($state, $match);
    }

    /**
     * Create output
     */
    function render($mode, &$renderer, $data) {

        list($state, $text) = $data;

        if ($mode === 'wikiiocmodel_psdom'){
            switch ($state) {
                case DOKU_LEXER_ENTER:
                    $node = new SpecialBlockNodeDoc(SpecialBlockNodeDoc::VERD_TYPE);
                    $renderer->getCurrentNode()->addContent($node);
                    $renderer->setCurrentNode($node);
                    break;
                case DOKU_LEXER_UNMATCHED:
                    //$text = '<span style="background-color:lightgreen;">' . $text . '</span>';
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
            return TRUE;

        }elseif ($mode === "iocxhtml") {
            switch ($state) {
                case DOKU_LEXER_ENTER :
                    break;
                case DOKU_LEXER_UNMATCHED :
                    $instructions = get_latex_instructions($text);
                    //delete document_start and document_end instructions
                    array_shift($instructions);
                    array_pop($instructions);
                    //delete p_open and p_close instructions
                    array_shift($instructions);
                    array_pop($instructions);
                    $renderer->doc .= '<span style="background-color:lightgreen;">';
                    $renderer->doc .= p_latex_render($mode, $instructions, $info);
                    $renderer->doc .= '</span>';
                    break;
                case DOKU_LEXER_EXIT :
                    break;
            }
            return TRUE;

        }elseif ($mode === 'ioccounter'){
            switch ($state) {
                case DOKU_LEXER_ENTER :
                    $renderer->doc .= '::IOCVERDINICI::';
                    break;
                case DOKU_LEXER_UNMATCHED :
                    $instructions = get_latex_instructions($text);
                    $renderer->doc .= p_latex_render($mode, $instructions, $info);
                    break;
                case DOKU_LEXER_EXIT :
                    $renderer->doc .= '::IOCVERDFINAL::';
                    break;
            }
            return TRUE;

        }elseif ($mode === 'iocexportl'){
            switch ($state) {
                case DOKU_LEXER_ENTER :
                    break;
                case DOKU_LEXER_UNMATCHED :
                    $instructions = get_latex_instructions($text);
                    $renderer->doc .= p_latex_render($mode, $instructions, $info);
                    break;
                case DOKU_LEXER_EXIT :
                    break;
            }
            return TRUE;

        }elseif ($mode === 'xhtml'){
            switch ($state) {
                case DOKU_LEXER_ENTER :
                    break;
                case DOKU_LEXER_UNMATCHED :
                    $instructions = p_get_instructions($text);
                    //delete document_start and document_end instructions
                    array_shift($instructions);
                    array_pop($instructions);
                    //delete p_open and p_close instructions
                    array_shift($instructions);
                    array_pop($instructions);
                    $renderer->doc .= '<span style="background-color:lightgreen;">';
                    $renderer->doc .= p_render($mode, $instructions, $info);
                    $renderer->doc .= '</span>';
                    break;
                case DOKU_LEXER_EXIT :
                    break;
            }
            return TRUE;
        }
        return FALSE;
    }
}
