<?php
/**
 * Latex enlargepage command Syntax Plugin
 *
 * @author     Marc Català <reskit@gmail.com>
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 */

// must be run within Dokuwiki
if(!defined('DOKU_INC')) die();

if(!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');
require_once(DOKU_PLUGIN.'syntax.php');

class syntax_plugin_iocexportl_iocenlargepage extends DokuWiki_Syntax_Plugin {

   /**
    * Get an associative array with plugin info.
    */
    function getInfo(){
        return array(
            'author' => 'Marc Català',
            'email'  => 'reskit@gmail.com',
            'date'   => '2013-02-18',
            'name'   => 'IOC enlargepage Plugin',
            'desc'   => 'Plugin to replace !!!! with latex command',
            'url'    => 'http://ioc.gencat.cat/',
        );
    }

    function getType(){ return 'substition'; }
    function getPType(){ return 'normal'; }

    function getSort(){
        return 513;
    }


    /**
     * Connect pattern to lexer
     */
    function connectTo($mode) {
        $this->Lexer->addSpecialPattern('!{4}', $mode, 'plugin_iocexportl_iocenlargepage');
    }

    /**
     * Handle the match
     */

    function handle($match, $state, $pos, &$handler){
        return $match;
    }

   /**
    * output
    */
    function render($mode, &$renderer, $data) {
        if($mode === 'iocexportl'){
            $renderer->doc .= '\enlargethispage{\baselineskip}';
            return TRUE;
        }
        return FALSE;
    }
}
