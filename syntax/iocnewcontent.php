<?php
/**
 * Latex Syntax Plugin
 * @author     Marc Català <mcatala@ioc.cat>
 */

if(!defined('DOKU_INC')) define('DOKU_INC',realpath(dirname(__FILE__).'/../../').'/');
if(!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');
require_once(DOKU_PLUGIN.'syntax.php');
require_once(DOKU_PLUGIN.'iocexportl/lib/renderlib.php');


class syntax_plugin_iocexportl_iocnewcontent extends DokuWiki_Syntax_Plugin {

    /**
     * return some info
     */
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
    
    /**
     * What kind of syntax are we?
     */
    function getType(){
        return 'paragraphs';
    }

    /**
     * What about paragraphs?
     */
    function getPType(){
        return 'stack';
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
        if ($mode === 'ioccounter'){
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
        }elseif ($mode === 'iocexportl'){
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
        }elseif ($mode === 'xhtml'){
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
        }elseif ($mode === 'iocxhtml'){
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
        return FALSE;
    }
}
