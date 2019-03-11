<?php
/**
 * Block verd tag Syntax Plugin
 *
 * @author     Marc Català <mcatala@ioc.cat>
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 */

if(!defined('DOKU_INC')) define('DOKU_INC',realpath(dirname(__FILE__).'/../../').'/');
if(!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');
require_once(DOKU_PLUGIN.'syntax.php');
require_once(DOKU_PLUGIN.'iocexportl/lib/renderlib.php');

class syntax_plugin_iocexportl_iocblockverd extends DokuWiki_Syntax_Plugin {
    /**
     * return some info
     */
    function getInfo(){
        return array(
            'author' => 'Marc Català',
            'email'  => 'mcatala@ioc.cat',
            'date'   => '2011-02-24',
            'name'   => 'IOC verd tags Plugin',
            'desc'   => 'Plugin to parse verd tags',
            'url'    => 'http://ioc.gencat.cat/'
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
        return 512;
    }

    function getAllowedTypes(){
        return array('container');
    }


    /**
     * Connect pattern to lexer
     */
    function connectTo($mode) {
        $this->Lexer->addEntryPattern('<verd>\s?[Ii][nici|NICI].*?</verd>\s*\n?(?=.*\n?<verd>\s?[Ff][inal|INAL].*?</verd>)', $mode, 'plugin_iocexportl_iocblockverd');
    }

    function postConnect() {
        $this->Lexer->addExitPattern('\n?<verd>\s?[Ff][inal|INAL].*?</verd>', 'plugin_iocexportl_iocblockverd');
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
                    $node = new SpecialBlockNodeDoc(SpecialBlockNodeDoc::BLOCVERD_TYPE);
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
            return TRUE;
        }else if ($mode === 'ioccounter'){
            list ($state, $text) = $data;
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
        }elseif ($mode === 'iocexportl'
                || $mode === 'iocxhtml'){
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
                    break;
                case DOKU_LEXER_UNMATCHED :
                    $instructions = p_get_instructions($text);
                    $renderer->doc .= p_render($mode, $instructions, $info);
                    break;
                case DOKU_LEXER_EXIT :
                    break;
            }
            return TRUE;
        }
        return FALSE;
    }
}
