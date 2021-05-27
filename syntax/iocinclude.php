<?php
/**
 * include tag Syntax Plugin
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 */

if(!defined('DOKU_INC')) define('DOKU_INC',realpath(dirname(__FILE__).'/../../').'/');
if(!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');
require_once(DOKU_PLUGIN.'syntax.php');

class syntax_plugin_iocexportl_iocinclude extends DokuWiki_Syntax_Plugin {

    /**
     * return some info
     */
    function getInfo(){
        return array(
            'name'   => 'IOC include tags Plugin',
            'desc'   => 'Plugin to parse include tags',
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
        $this->Lexer->addEntryPattern('<include.*?>(?=.*?</include>)', $mode, 'plugin_iocexportl_iocinclude');
    }

    function postConnect() {
        $this->Lexer->addExitPattern('</include>', 'plugin_iocexportl_iocinclude');
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
        return ($mode === 'iocexportl' || $mode === 'ioccounter' || $mode === 'wikiiocmodel_psdom');
    }

}
