<?php
/**
 * Table and figure Syntax Plugin
 *
 * @author     Marc Català <mcatala@ioc.cat>
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 */

// must be run within Dokuwiki
if(!defined('DOKU_INC')) die();

if(!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');
require_once(DOKU_PLUGIN.'syntax.php');

class syntax_plugin_iocexportl_iocslpatternextratext extends DokuWiki_Syntax_Plugin {

   /**
    * Get an associative array with plugin info.
    */
    function getInfo(){
        return array(
            'author' => 'Marc Català',
            'email'  => 'mcatala@ioc.cat',
            'date'   => '2011-06-20',
            'name'   => 'IOC repference Plugin',
            'desc'   => 'Plugin to parse table and figures references',
            'url'    => 'http://ioc.gencat.cat/',
        );
    }

    function getType(){ return 'substition'; }
    function getPType(){ return 'normal'; }
    //'container','substition','protected','disabled','baseonly','formatting','paragraphs'

    function getSort(){
        return 513;
    }


    /**
     * Connect pattern to lexer
     */
    function connectTo($mode) {
        $this->Lexer->addSpecialPattern('<pat_extra_text>.*?</pat_extra_text>', $mode, 'plugin_iocexportl_iocslpatternextratext');
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
        $data = preg_replace('/<pat_extra_text>/', '', $data);
        $data = preg_replace('/<\/pat_extra_text>/', '', $data);
        $instructions = p_get_instructions( $data);
        array_shift($instructions);
        array_shift($instructions);
        array_pop($instructions);
        array_pop($instructions);
        $renderer->doc .= p_latex_render($mode, $instructions, $info);
        return FALSE;
    }
}
