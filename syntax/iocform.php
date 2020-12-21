<?php
/**
 * Example Syntax Plugin:   Example Component.
 * @author     Eduard Diaz <edudiaz@scopia.es>
 */

if(!defined('DOKU_INC')) define('DOKU_INC',realpath(dirname(__FILE__).'/../../').'/');
if(!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');
require_once(DOKU_PLUGIN.'syntax.php');
require_once(DOKU_INC.'inc/auth.php');

class syntax_plugin_iocexportl_iocform extends DokuWiki_Syntax_Plugin {
    /**
     * return some info
     */
    function getInfo(){
        return array(
            'author' => 'Eduard Diaz',
            'email'  => 'edudiaz@scopia.es',
            'date'   => '14-04-2009',
            'name'   => 'Export Form Plugin',
            'desc'   => 'Creates a export form to create pdf o html',
            'url'    => 'http://www.scopia.es',
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
        return 309;
    }


    /**
     * Connect pattern to lexer
     */
    function connectTo($mode) {
        $this->Lexer->addSpecialPattern('~~EXPORTFORM[^\r\n]*?~~', $mode, 'plugin_iocexportl_iocform');
    }

    /**
     * Handle the match
     */
    function handle($match, $state, $pos, Doku_Handler $handler){
        return array($match, $state, $pos);
    }

    /**
     * Create output
     */
    function render($mode, Doku_Renderer $renderer, $data) {
        if ($mode !== 'xhtml') return TRUE;
        return FALSE; // do nothing -> everything is handled in action component
    }

}
