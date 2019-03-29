<?php
/**
 * Iocsol tag Syntax Plugin
 * @author     Marc Català <mcatala@ioc.cat>
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 */
if(!defined('DOKU_INC')) die();
if(!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');
require_once(DOKU_PLUGIN.'syntax.php');
require_once(DOKU_PLUGIN.'iocexportl/lib/renderlib.php');

class syntax_plugin_iocexportl_iocsol extends DokuWiki_Syntax_Plugin {

    function getInfo(){
        return array(
            'author' => 'Marc Català',
            'email'  => 'mcatala@ioc.cat',
            'date'   => '2011-03-21',
            'name'   => 'IOC sol Plugin',
            'desc'   => 'Plugin to parse sol tags',
            'url'    => 'http://ioc.gencat.cat/',
        );
    }

    function getType()  {return 'protected';}
    function getPType() {return 'normal';} //stack, block, normal
    function getSort()  {return 513;}

    /**
     * Connect pattern to lexer
     */
    function connectTo($mode) {
        $this->Lexer->addEntryPattern('<sol>(?=.*?</sol>)',$mode,'plugin_iocexportl_iocsol');
    }
    function postConnect() {
        $this->Lexer->addExitPattern('</sol>','plugin_iocexportl_iocsol');
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
        list($state, $text) = $data;

        if ($mode === 'wikiiocmodel_psdom') {
            switch ($state) {
                case DOKU_LEXER_ENTER:
                    $node = new SpecialBlockNodeDoc(SpecialBlockNodeDoc::SOL_TYPE);
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

        }elseif ($mode === "iocxhtml" || $mode === 'wikiiocmodel_ptxhtml') {
            switch ($state) {
                case DOKU_LEXER_ENTER :
                    break;
                case DOKU_LEXER_UNMATCHED :
                    if (!isset($_SESSION['quizsol'])) {
                        $_SESSION['quizsol'] = array();
                    }
                    $instructions = get_latex_instructions($text);
                    //delete document_start and document_end instructions
                    array_shift($instructions);
                    array_pop($instructions);
                    //delete p_open and p_close instructions
                    array_shift($instructions);
                    array_pop($instructions);
                    $sol = p_latex_render($mode, $instructions, $info);
                    array_push($_SESSION['quizsol'], preg_replace('/\n/', '', $sol));
                    if ($_SESSION['quizmode'] !== 'relations') {
                        $renderer->doc .= '@IOCDROPDOWN@';
                    }
                    break;
                case DOKU_LEXER_EXIT :
                    break;
            }
            return TRUE;

        }elseif ($mode === 'ioccounter'){
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

        }elseif ($mode === 'iocexportl'){
            switch ($state) {
                case DOKU_LEXER_ENTER :
                    break;
                case DOKU_LEXER_UNMATCHED :
                    if (!isset($_SESSION['quizsol'])){
                        $_SESSION['quizsol'] = array();
                    }
                    $instructions = get_latex_instructions($text);
                    $sol = p_latex_render($mode, $instructions, $info);
                    array_push($_SESSION['quizsol'], preg_replace('/\n/', '', $sol));
                    if($_SESSION['quizmode'] !== 'relations'){
                        $renderer->doc .= '\quizrule{'.min(20,strlen($text)).'em}';
                    }else{
                        $renderer->doc .= ' (\hspace{5mm})';
                    }
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
                    if (!isset($_SESSION['quizsol'])){
                        $_SESSION['quizsol'] = array();
                    }
                    $instructions = get_latex_instructions($text);
                    $sol = p_latex_render($mode, $instructions, $info);
                    array_push($_SESSION['quizsol'], preg_replace('/\n/', '', $sol));
                    if($_SESSION['quizmode'] !== 'relations'){
                        $renderer->doc .= '@IOCDROPDOWN@';
                    }else{
                        $renderer->doc .= '';
                    }
                    break;
                case DOKU_LEXER_EXIT :
                    break;
            }
            return TRUE;
        }
        return FALSE;
    }
}
