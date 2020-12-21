<?php
/**
 * Latex Syntax Plugin
 * @author     Marc Català <mcatala@ioc.cat>
 */
if(!defined('DOKU_INC')) die();
if(!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');
require_once(DOKU_PLUGIN.'syntax.php');
require_once(DOKU_PLUGIN.'iocexportl/lib/renderlib.php');

class syntax_plugin_iocexportl_iocgroc extends DokuWiki_Syntax_Plugin {

    function getInfo(){
        return array(
            'author' => 'Marc Català',
            'email'  => 'mcatala@ioc.cat',
            'date'   => '2011-02-24',
            'name'   => 'IOC groc tags Plugin',
            'desc'   => 'Plugin to parse groc tags',
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
        $this->Lexer->addEntryPattern('<groc.*?>(?=.*?</groc>)', $mode, 'plugin_iocexportl_iocgroc');
    }

    function postConnect() {
        $this->Lexer->addExitPattern('</groc>', 'plugin_iocexportl_iocgroc');
    }

    /**
     * Handle the match
     */
    function handle($match, $state, $pos, Doku_Handler $handler){
        return array($state, $match);
    }

    /**
     * Create output
     */
    function render($mode, Doku_Renderer $renderer, $data) {
        list($state, $text) = $data;

        if ($mode === 'wikiiocmodel_psdom'){
            switch ($state) {
                case DOKU_LEXER_ENTER:
                    $node = new SpecialBlockNodeDoc(SpecialBlockNodeDoc::VERD_TYPE);
                    $renderer->getCurrentNode()->addContent($node);
                    $renderer->setCurrentNode($node);
                    break;
                case DOKU_LEXER_UNMATCHED:
                    //$text = '<span style="background-color:lightyellow;">' . $text . '</span>';
                    $instructions = get_latex_instructions($text);
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
                case DOKU_LEXER_EXIT:
                    $renderer->setCurrentNode($renderer->getCurrentNode()->getOwner());
                    break;
            }
            return TRUE;

        }elseif ($mode === "iocxhtml" || $mode === "wikiiocmodel_ptxhtml") {
            switch ($state) {
                case DOKU_LEXER_ENTER :
                    break;
                case DOKU_LEXER_UNMATCHED :
                    $instructions = get_latex_instructions($text);
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
                    $renderer->doc .= '<span style="background-color:lightyellow;">';
                    $renderer->doc .= p_latex_render($mode, $instructions, $info);
                    $renderer->doc .= '</span>';
                    break;
                case DOKU_LEXER_EXIT :
                    break;
            }
            return TRUE;

        }elseif ($mode === 'ioccounter' || $mode === 'iocexportl') {
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

        }else {
            return FALSE;
        }
    }
}
