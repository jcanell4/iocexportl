<?php
/**
 * Latex Syntax Plugin
 * @author     Marc Català <mcatala@ioc.cat>
 */
if(!defined('DOKU_INC')) die();
if(!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');
require_once(DOKU_PLUGIN.'syntax.php');
require_once(DOKU_PLUGIN.'iocexportl/lib/renderlib.php');


class syntax_plugin_iocexportl_iocnewcontent extends DokuWiki_Syntax_Plugin {

    function getInfo(){
        return array(
            'author' => 'Marc Català',
            'email'  => 'mcatala@ioc.cat',
            'date'   => '2012-09-04',
            'name'   => 'IOC newcontent tags Plugin',
            'desc'   => 'Plugin to parse newcontent tags',
            'url'    => 'http://ioc.gencat.cat/',
        );
    }

    // tipus de sintaxi: 'container', 'baseonly', 'formatting', 'substition', 'protected', 'disabled', 'paragraphs'
    function getType(){
        return 'paragraphs';
    }

    // tipus de paràgraf: 'normal', 'block', 'stack'
    function getPType(){
        return 'stack';
    }

    // ordre (invers) de prioritat en la seqüencia d'anàlisi
    function getSort(){
        return 513;
    }

    /**
     * Connect pattern to lexer
     */
    function connectTo($mode) {
        $this->Lexer->addEntryPattern('<newcontent>(?=.*?</newcontent>)', $mode, 'plugin_iocexportl_iocnewcontent');
    }

    function postConnect() {
        $this->Lexer->addExitPattern('</newcontent>', 'plugin_iocexportl_iocnewcontent');
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
        if ($mode == 'wikiiocmodel_psdom'){
            list ($state, $text) = $data;
            switch ($state) {
                case DOKU_LEXER_ENTER:
                    $node = new SpecialBlockNodeDoc(SpecialBlockNodeDoc::NEWCONTENT_TYPE);
                    $renderer->getCurrentNode()->addContent($node);
                    $renderer->setCurrentNode($node);
                    break;
                case DOKU_LEXER_UNMATCHED:
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
                    foreach ( $instructions as $instruction ) {
                        call_user_func_array(array(&$renderer, $instruction[0]),$instruction[1]);
                    }
                    break;
                case DOKU_LEXER_EXIT:
                    $renderer->setCurrentNode($renderer->getCurrentNode()->getOwner());
                    break;
            }
            return TRUE;
        }
        else if ($mode === 'ioccounter'){
            list ($state, $text) = $data;
            switch ($state) {
                case DOKU_LEXER_ENTER :
                    $renderer->doc .= '::IOCNEWCONTENTINICI::';
                    break;
                case DOKU_LEXER_UNMATCHED :
                    $instructions = get_latex_instructions($text);
                    $renderer->doc .= p_latex_render($mode, $instructions, $info);
                    break;
                case DOKU_LEXER_EXIT :
                    $renderer->doc .= '::IOCNEWCONTENTFINAL::';
                    break;
            }
            return TRUE;
        }
        elseif ($mode === 'iocexportl'){
            list ($state, $text) = $data;
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
        }
        elseif ($mode === 'xhtml'){
            list ($state, $text) = $data;
            switch ($state) {
                case DOKU_LEXER_ENTER :
                    $renderer->doc .= '<span class="newcontent">';
                    break;
                case DOKU_LEXER_UNMATCHED :
                    $instructions = p_get_instructions($text);
                    $renderer->doc .= p_render($mode, $instructions, $info);
                    break;
                case DOKU_LEXER_EXIT :
                    $renderer->doc .= '</span>';
                    break;
            }
            return TRUE;
        }
        elseif ($mode === 'iocxhtml' || $mode === 'wikiiocmodel_ptxhtml'){
            list ($state, $text) = $data;
            switch ($state) {
                case DOKU_LEXER_ENTER :
                    break;
                case DOKU_LEXER_UNMATCHED :
                    $instructions = get_latex_instructions($text);
                    //delete document_start and document_end instructions
                    if ($instructions[0][0] === "document_start") {
                        array_shift($instructions);
                        array_pop($instructions);
                    }
                    $renderer->doc .= p_latex_render($mode, $instructions, $info);
                    break;
                case DOKU_LEXER_EXIT :
                    break;
            }
            return TRUE;
        }
        return FALSE;
    }
}
